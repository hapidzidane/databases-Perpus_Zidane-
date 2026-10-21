
<?php
require_once "src/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: tambah_peminjaman.php");
    exit;
}

$id_anggota = filter_input(
    INPUT_POST, "id_anggota", FILTER_VALIDATE_INT
);
$id_buku = filter_input(
    INPUT_POST, "id_buku", FILTER_VALIDATE_INT
);
$jumlah = filter_input(
    INPUT_POST, "jumlah", FILTER_VALIDATE_INT
);
$tanggal_pinjam = $_POST["tanggal_pinjam"] ?? "";

$tanggal = DateTime::createFromFormat("!Y-m-d", $tanggal_pinjam);
$tanggal_valid = $tanggal &&
    $tanggal->format("Y-m-d") === $tanggal_pinjam;

if (
    !$id_anggota || !$id_buku ||
    !$jumlah || $jumlah < 1 ||
    !$tanggal_valid
) {
    header("Location: tambah_peminjaman.php?pesan=gagal");
    exit;
}

mysqli_begin_transaction($koneksi);

try {
    // Pastikan anggota terdaftar.
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT id_anggota FROM anggota WHERE id_anggota = ?"
    );
    mysqli_stmt_bind_param($stmt, "i", $id_anggota);
    mysqli_stmt_execute($stmt);

    $anggota = mysqli_stmt_get_result($stmt);
    $anggota_ada = mysqli_num_rows($anggota) > 0;
    mysqli_stmt_close($stmt);

    if (!$anggota_ada) {
        throw new Exception("Anggota tidak ditemukan.");
    }

    // Periksa stok buku dan kunci baris selama transaksi.
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT stok FROM buku WHERE id_buku = ? FOR UPDATE"
    );
    mysqli_stmt_bind_param($stmt, "i", $id_buku);
    mysqli_stmt_execute($stmt);

    $hasil_buku = mysqli_stmt_get_result($stmt);
    $buku = mysqli_fetch_assoc($hasil_buku);
    mysqli_stmt_close($stmt);

    if (!$buku || (int)$buku["stok"] < $jumlah) {
        throw new Exception("Stok buku tidak mencukupi.");
    }

    // Simpan transaksi tanpa petugas.
    $status = "Dipinjam";

    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO peminjaman
        (id_anggota, status, tanggal_pinjam)
        VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iss",
        $id_anggota,
        $status,
        $tanggal_pinjam
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Transaksi gagal disimpan.");
    }

    $id_peminjaman = mysqli_insert_id($koneksi);
    mysqli_stmt_close($stmt);

    // Simpan detail buku.
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO detail_peminjaman
        (id_peminjaman, id_buku, jumlah)
        VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iii",
        $id_peminjaman,
        $id_buku,
        $jumlah
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Detail peminjaman gagal disimpan.");
    }
    mysqli_stmt_close($stmt);

    // Kurangi stok buku.
    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE buku
         SET stok = stok - ?
         WHERE id_buku = ? AND stok >= ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iii",
        $jumlah,
        $id_buku,
        $jumlah
    );

    if (
        !mysqli_stmt_execute($stmt) ||
        mysqli_stmt_affected_rows($stmt) !== 1
    ) {
        throw new Exception("Stok buku gagal diperbarui.");
    }
    mysqli_stmt_close($stmt);

    mysqli_commit($koneksi);

    header("Location: peminjaman.php?pesan=berhasil");
    exit;

} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    error_log("Tambah peminjaman gagal: " . $e->getMessage());

    header("Location: tambah_peminjaman.php?pesan=gagal");
    exit;
}
