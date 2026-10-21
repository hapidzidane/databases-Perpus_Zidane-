
<?php
require_once "src/koneksi.php";

$query = "
    SELECT
        p.id_peminjaman,
        a.nama_anggota,
        p.tanggal_pinjam,
        p.status,
        GROUP_CONCAT(
            CONCAT(b.judul, ' (', dp.jumlah, ')')
            SEPARATOR ', '
        ) AS daftar_buku,
        COALESCE(SUM(dp.jumlah), 0) AS total_buku
    FROM peminjaman p
    JOIN anggota a
        ON p.id_anggota = a.id_anggota
    LEFT JOIN detail_peminjaman dp
        ON p.id_peminjaman = dp.id_peminjaman
    LEFT JOIN buku b
        ON dp.id_buku = b.id_buku
    GROUP BY
        p.id_peminjaman,
        a.nama_anggota,
        p.tanggal_pinjam,
        p.status
    ORDER BY p.id_peminjaman DESC
";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Gagal mengambil data peminjaman: " .
        htmlspecialchars(mysqli_error($koneksi)));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Peminjaman - Perpustakaan Zidane</title>

    <link rel="stylesheet" href="src/style.css?v=6">
</head>
<body>

<div class="peminjaman-container">

    <div class="peminjaman-header">
        <div>
            <h1>Data Peminjaman</h1>
            <p>Kelola transaksi peminjaman buku perpustakaan.</p>
        </div>

        <a href="tambah_peminjaman.php" class="btn-tambah">
            + Tambah Peminjaman
        </a>
    </div>

    <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'berhasil') { ?>
        <p class="pesan-sukses">
            Peminjaman berhasil disimpan.
        </p>
    <?php } ?>

    <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'gagal') { ?>
        <p class="pesan-error">
            Peminjaman gagal disimpan. Periksa data dan stok buku.
        </p>
    <?php } ?>
    

    <div class="tabel-scroll">
        <table class="tabel-peminjaman">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Anggota</th>
                    <th>Buku</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php if (mysqli_num_rows($result) > 0) { ?>

                    <?php $no = 1; ?>

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                        <tr>
                            <td>
                                <?= $no++; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nama_anggota']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $row['daftar_buku'] ?? 'Belum ada buku'
                                ); ?>
                            </td>

                            <td>
                                <?= (int)$row['total_buku']; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['tanggal_pinjam']); ?>
                            </td>

                            <td>
                                <span class="status-peminjaman">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </td>

                            <td>
                                <?php if ($row['status'] === 'Dipinjam') { ?>

                                    <form
                                        action="kembalikan_buku.php"
                                        method="POST"
                                        onsubmit="return confirm('Proses pengembalian buku ini?');"
                                    >
                                        <input
                                            type="hidden"
                                            name="id_peminjaman"
                                            value="<?= (int)$row['id_peminjaman']; ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn-kembali"
                                        >
                                            Kembalikan
                                        </button>
                                    </form>

                                <?php } else { ?>

                                    <span>Selesai</span>
                                    <form
            action="hapus_riwayat.php"
            method="POST"
            onsubmit="return confirm('Yakin ingin menghapus riwayat ini?');"
        >
            <input
                type="hidden"
                name="id_peminjaman"
                value="<?= (int)$row['id_peminjaman']; ?>"
            >

            <button type="submit" class="btn-hapus-riwayat">
                Hapus Riwayat
            </button>
        </form>

                                <?php } ?>
                            </td>
                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="7" style="text-align: center; padding: 24px;">
                            Belum ada data peminjaman.
                        </td>
                    </tr>

                <?php } ?>

            </tbody>
        </table>
    </div>
    <div class="navigasi-beranda">
        <a href="index.php" class="btn-beranda">
            &larr; Kembali ke Beranda
        </a>
    </div>

</div>

</body>
</html>
