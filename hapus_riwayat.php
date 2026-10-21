
<?php
require_once "src/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: peminjaman.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id_peminjaman', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: peminjaman.php?pesan=gagal");
    exit;
}

mysqli_begin_transaction($koneksi);

try {
    // Pastikan transaksi sudah dikembalikan.
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT status FROM peminjaman
         WHERE id_peminjaman = ? FOR UPDATE"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $hasil = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($hasil);
    mysqli_stmt_close($stmt);

    if (!$data || $data['status'] !== 'Dikembalikan') {
        throw new Exception("Transaksi belum dikembalikan.");
    }

    // Hapus detail peminjaman terlebih dahulu.
    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM detail_peminjaman
         WHERE id_peminjaman = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Hapus riwayat transaksi.
    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM peminjaman
         WHERE id_peminjaman = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_commit($koneksi);

    header("Location: peminjaman.php?pesan=terhapus");
    exit;

} catch (Throwable $e) {
    mysqli_rollback($koneksi);

    header("Location: peminjaman.php?pesan=gagal");
    exit;
}
