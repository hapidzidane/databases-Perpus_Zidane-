<?php
require_once "src/koneksi.php";

$data_anggota = mysqli_query(
    $koneksi,
    "SELECT * FROM anggota ORDER BY id_anggota DESC"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Anggota - Perpustakaan Zidane</title>

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

        <h1>Data Anggota</h1>

        <p class="page-description">
            Kelola data anggota perpustakaan.
        </p>

    </div>


    <section class="form-section">

        <h2>Tambah Anggota</h2>

        <form action="tambah_anggota.php" method="POST">

            <div class="form-grid">

                <div class="form-group">

                    <label>Nama Anggota</label>

                    <input
                        type="text"
                        name="nama_anggota"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Alamat</label>

                    <input
                        type="text"
                        name="alamat"
                        required
                    >

                </div>



                <div class="form-group">

                    <label>No. Telepon</label>

                    <input
                        type="text"
                        name="no_telp"
                        required
                    >

                </div>


                <!-- TANGGAL -->

                <div class="form-group">

                    <label>Tanggal Daftar</label>

                    <input
                        type="date"
                        name="tanggal_daftar"
                        required
                    >

                </div>

            </div>


            <button
                type="submit"
                class="btn"
            >
                Simpan
            </button>

        </form>

    </section>


    <!-- DAFTAR ANGGOTA -->

    <section class="table-section">

        <h2>Daftar Anggota</h2>

        <p>
            Data anggota yang tersimpan di database.
        </p>


        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Nama Anggota</th>

                        <th>Alamat</th>

                        <th>No. Telepon</th>

                        <th>Tanggal Daftar</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>

                <?php if (mysqli_num_rows($data_anggota) > 0): ?>

                    <?php while ($anggota = mysqli_fetch_assoc($data_anggota)): ?>

                        <tr>

                            <td>
                                <?= $anggota['id_anggota']; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($anggota['nama_anggota']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($anggota['alamat']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($anggota['no_telp']); ?>
                            </td>

                            <td>
                                <?= $anggota['tanggal_daftar']; ?>
                            </td>

                            <td>

                                <a
                                    href="edit_anggota.php?id=<?= $anggota['id_anggota']; ?>"
                                    class="edit"
                                >
                                    Edit
                                </a>

                                |

                                <a
                                    href="hapus_anggota.php?id=<?= $anggota['id_anggota']; ?>"
                                    class="delete"
                                    onclick="return confirm('Yakin ingin menghapus anggota ini?')"
                                >
                                    Hapus
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="6" class="empty">

                            Belum ada data anggota.

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>


<!-- FOOTER -->

<footer>

    <p>© 2026 Perpustakaan Zidane</p>

</footer>


</body>

</html>