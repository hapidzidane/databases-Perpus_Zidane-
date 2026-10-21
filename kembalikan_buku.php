
<?php
require_once "src/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: peminjaman.php");
    exit;
}

$id_peminjaman = filter_input(
    INPUT_POST,
    "id_peminjaman",
    FILTER_VALIDATE_INT
);

if (!$id_peminjaman || $id_peminjaman < 1) {
    header("Location: peminjaman.php?pesan=gagal");
    exit;
}

mysqli_begin_transaction($koneksi);

try {
    // Kunci transaksi agar tidak dikembalikan dua kali bersamaan.
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT status
         FROM peminjaman
         WHERE id_peminjaman = ?
         FOR UPDATE"
    );

    mysqli_stmt_bind_param($stmt, "i", $id_peminjaman);
    mysqli_stmt_execute($stmt);

    $hasil = mysqli_stmt_get_result($stmt);
    $peminjaman = mysqli_fetch_assoc($hasil);
    mysqli_stmt_close($stmt);

    if (!$peminjaman || $peminjaman["status"] !== "Dipinjam") {
        throw new Exception("Peminjaman tidak ditemukan atau sudah dikembalikan.");
    }

    // Ambil seluruh detail buku yang dipinjam.
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT id_buku, jumlah
         FROM detail_peminjaman
         WHERE id_peminjaman = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id_peminjaman);
    mysqli_stmt_execute($stmt);

    $hasil_detail = mysqli_stmt_get_result($stmt);
    $detail = [];

    while ($baris = mysqli_fetch_assoc($hasil_detail)) {
        $detail[] = $baris;
    }

    mysqli_stmt_close($stmt);

    if (count($detail) === 0) {
        throw new Exception("Detail buku tidak ditemukan.");
    }

    // Kembalikan stok semua buku dalam transaksi.
    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE buku SET stok = stok + ? WHERE id_buku = ?"
    );

    foreach ($detail as $item) {
        $jumlah = (int)$item["jumlah"];
        $id_buku = (int)$item["id_buku"];

        mysqli_stmt_bind_param(
            $stmt,
            "ii",
            $jumlah,
            $id_buku
        );

        if (
            !mysqli_stmt_execute($stmt) ||
            mysqli_stmt_affected_rows($stmt) !== 1
        ) {
            throw new Exception("Gagal memperbarui stok buku.");
        }
    }

    mysqli_stmt_close($stmt);

    // Ubah status transaksi.
    $status_baru = "Dikembalikan";

    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE peminjaman
         SET status = ?
         WHERE id_peminjaman = ? AND status = 'Dipinjam'"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "si",
        $status_baru,
        $id_peminjaman
    );

    if (
        !mysqli_stmt_execute($stmt) ||
        mysqli_stmt_affected_rows($stmt) !== 1
    ) {
        throw new Exception("Status pengembalian gagal diperbarui.");
    }

    mysqli_stmt_close($stmt);

    mysqli_commit($koneksi);

    header("Location: peminjaman.php?pesan=dikembalikan");
    exit;

} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    error_log("Pengembalian buku gagal: " . $e->getMessage());

    header("Location: peminjaman.php?pesan=gagal");
    exit;
}
