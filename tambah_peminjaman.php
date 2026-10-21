
<?php
require_once "src/koneksi.php";

$query_anggota = mysqli_query(
    $koneksi,
    "SELECT id_anggota, nama_anggota
     FROM anggota
     ORDER BY nama_anggota ASC"
);

$query_buku = mysqli_query(
    $koneksi,
    "SELECT id_buku, judul, stok
     FROM buku
     WHERE stok > 0
     ORDER BY judul ASC"
);

if (!$query_anggota || !$query_buku) {
    die("Gagal mengambil data anggota atau buku.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Peminjaman - Perpustakaan Zidane</title>
    <link rel="stylesheet" href="src/style.css?v=5">
</head>
<body>

<div class="peminjaman-container">
    <h1>Tambah Peminjaman</h1>
    <p>Isi data anggota dan buku yang akan dipinjam.</p>

    <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'gagal') { ?>
        <p class="pesan-error">
            Gagal menyimpan peminjaman. Periksa kembali data dan stok buku.
        </p>
    <?php } ?>

    <form action="proses_tambah_peminjaman.php" method="POST">

        <label for="id_anggota">Anggota</label>
        <select name="id_anggota" id="id_anggota" required>
            <option value="">-- Pilih Anggota --</option>

            <?php while ($anggota = mysqli_fetch_assoc($query_anggota)) { ?>
                <option value="<?= (int)$anggota['id_anggota']; ?>">
                    <?= htmlspecialchars($anggota['nama_anggota']); ?>
                </option>
            <?php } ?>
        </select>

        <label for="id_buku">Buku</label>
        <select name="id_buku" id="id_buku" required>
            <option value="">-- Pilih Buku --</option>

            <?php while ($buku = mysqli_fetch_assoc($query_buku)) { ?>
                <option value="<?= (int)$buku['id_buku']; ?>">
                    <?= htmlspecialchars($buku['judul']); ?>
                    (Stok: <?= (int)$buku['stok']; ?>)
                </option>
            <?php } ?>
        </select>

        <label for="jumlah">Jumlah Buku</label>
        <input
            type="number"
            name="jumlah"
            id="jumlah"
            min="1"
            value="1"
            required
        >

        <label for="tanggal_pinjam">Tanggal Peminjaman</label>
        <input
            type="date"
            name="tanggal_pinjam"
            id="tanggal_pinjam"
            value="<?= date('Y-m-d'); ?>"
            required
        >

        <div class="form-actions">
            <button type="submit">Simpan Peminjaman</button>
            <a href="peminjaman.php" class="btn-kembali">Kembali</a>
        </div>

    </form>
</div>

</body>
</html>
