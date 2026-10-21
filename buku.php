<?php
require_once "src/koneksi.php";

$data_buku = mysqli_query(
    $koneksi,
    "SELECT * FROM buku ORDER BY id_buku DESC"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Buku - Perpustakaan Zidane</title>

    <link rel="stylesheet" href="src/style.css?v=2">
</head>

<body>

<header class="navbar">

    <div class="logo">
        <span>📚</span>
        <h2>Perpustakaan Zidane</h2>
    </div>

    <nav>
        <a href="index.php">Beranda</a>
        <a href="buku.php">Buku</a>
        <a href="anggota.php">Anggota</a>
        <a href="peminjaman.php">Peminjaman</a>
    </nav>

</header>


<main class="page">

    <div class="page-header">

        <h1>Data Buku</h1>

        <p class="page-description">
            Kelola data buku perpustakaan.
        </p>

    </div>

    <section class="form-section">

        <h2>Tambah Buku</h2>

        <form action="tambah_buku.php" method="POST">

            <div class="form-grid">

                <div class="form-group">
                    <label>Judul Buku</label>
                    <input type="text" name="judul" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" name="kategori" required>
                </div>

                <div class="form-group">
                    <label>Penerbit</label>
                    <input type="text" name="penerbit" required>
                </div>

                <div class="form-group">
                    <label>Penulis</label>
                    <input type="text" name="penulis" required>
                </div>

                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" min="0" required>
                </div>

                <div class="form-group">
                    <label>Tahun Terbit</label>
                    <input
                        type="number"
                        name="tahun_terbit"
                        min="1900"
                        max="2100"
                        required
                    >
                </div>

            </div>

            <button type="submit" class="btn">
                Simpan
            </button>

        </form>

    </section>

    <section class="table-section">

        <h2>Daftar Buku</h2>

        <p>
            Data buku yang tersimpan di database.
        </p>

        <div class="table-container">

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Penerbit</th>
                        <th>Penulis</th>
                        <th>Stok</th>
                        <th>Tahun</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (mysqli_num_rows($data_buku) > 0): ?>

                    <?php while ($buku = mysqli_fetch_assoc($data_buku)): ?>

                        <tr>

                            <td>
                                <?= $buku['id_buku']; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($buku['judul']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($buku['kategori']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($buku['penerbit']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($buku['penulis']); ?>
                            </td>

                            <td>
                                <?= $buku['stok']; ?>
                            </td>

                            <td>
                                <?= $buku['tahun_terbit']; ?>
                            </td>

                            <td>

                                <a
                                    href="edit_buku.php?id=<?= $buku['id_buku']; ?>"
                                    class="edit"
                                >
                                    Edit
                                </a>

                                |

                                <a
                                    href="hapus_buku.php?id=<?= $buku['id_buku']; ?>"
                                    class="delete"
                                    onclick="return confirm('Yakin ingin menghapus buku ini?')"
                                >
                                    Hapus
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="8" class="empty">
                            Belum ada data buku.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>


<footer>
    <p>© 2026 Perpustakaan Zidane</p>
</footer>

</body>

</html>