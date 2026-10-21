<?php

require_once "src/koneksi.php";


if (!isset($_GET['id'])) {

    header("Location: anggota.php");
    exit;

}

$id_anggota = $_GET['id'];

if (isset($_POST['update'])) {

    $nama_anggota = $_POST['nama_anggota'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $tanggal_daftar = $_POST['tanggal_daftar'];

    mysqli_query(
        $koneksi,
        "UPDATE anggota SET
            nama_anggota = '$nama_anggota',
            alamat = '$alamat',
            no_telp = '$no_telp',
            tanggal_daftar = '$tanggal_daftar'
        WHERE id_anggota = '$id_anggota'"
    );

    header("Location: anggota.php");
    exit;

}



$data = mysqli_query(
    $koneksi,
    "SELECT * FROM anggota
     WHERE id_anggota = '$id_anggota'"
);

$anggota = mysqli_fetch_assoc($data);


if (!$anggota) {

    echo "Data anggota tidak ditemukan.";
    exit;

}

?>


<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Anggota - Perpustakaan Zidane</title>

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

        <h1>Edit Anggota</h1>

        <p class="page-description">

            Ubah data anggota yang dipilih.

        </p>

    </div>

    <section class="form-section">


        <form method="POST">


            <div class="form-grid">

                <div class="form-group">

                    <label>Nama Anggota</label>

                    <input
                        type="text"
                        name="nama_anggota"
                        value="<?= htmlspecialchars($anggota['nama_anggota']); ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Alamat</label>

                    <input
                        type="text"
                        name="alamat"
                        value="<?= htmlspecialchars($anggota['alamat']); ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>No. Telepon</label>

                    <input
                        type="text"
                        name="no_telp"
                        value="<?= htmlspecialchars($anggota['no_telp']); ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Tanggal Daftar</label>

                    <input
                        type="date"
                        name="tanggal_daftar"
                        value="<?= $anggota['tanggal_daftar']; ?>"
                        required
                    >

                </div>

            </div>


            <button
                type="submit"
                name="update"
                class="btn"
            >
                Simpan Perubahan
            </button>


            <a
                href="anggota.php"
                class="btn"
            >
                Batal
            </a>


        </form>


    </section>


</main>



<!-- FOOTER -->

<footer>

    <p>© 2026 Perpustakaan Zidane</p>

</footer>


</body>

</html>