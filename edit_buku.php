<?php

require_once "src/koneksi.php";

if (!isset($_GET['id'])) {

    header("Location: buku.php");
    exit;
}

$id_buku = $_GET['id'];

if (isset($_POST['update'])) {

    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $penerbit = $_POST['penerbit'];
    $penulis = $_POST['penulis'];
    $stok = $_POST['stok'];
    $tahun_terbit = $_POST['tahun_terbit'];

    mysqli_query(
        $koneksi,
        "UPDATE buku SET
            judul = '$judul',
            kategori = '$kategori',
            penerbit = '$penerbit',
            penulis = '$penulis',
            stok = '$stok',
            tahun_terbit = '$tahun_terbit'
        WHERE id_buku = '$id_buku'"
    );

    header("Location: buku.php");
    exit;
}


$data = mysqli_query(
    $koneksi,
    "SELECT * FROM buku WHERE id_buku = '$id_buku'"
);

$buku = mysqli_fetch_assoc($data);


// Jika data tidak ditemukan

if (!$buku) {

    echo "Data buku tidak ditemukan.";
    exit;
}

?>


<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Buku - Perpustakaan Zidane</title>

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

        <h1>Edit Buku</h1>

        <p class="page-description">
            Ubah data buku yang dipilih.
        </p>

    </div>


    <section class="form-section">

        <form method="POST">

            <div class="form-grid">

                <div class="form-group">

                    <label>Judul Buku</label>

                    <input
                        type="text"
                        name="judul"
                        value="<?= htmlspecialchars($buku['judul']); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Kategori</label>

                    <input
                        type="text"
                        name="kategori"
                        value="<?= htmlspecialchars($buku['kategori']); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Penerbit</label>

                    <input
                        type="text"
                        name="penerbit"
                        value="<?= htmlspecialchars($buku['penerbit']); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Penulis</label>

                    <input
                        type="text"
                        name="penulis"
                        value="<?= htmlspecialchars($buku['penulis']); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Stok</label>

                    <input
                        type="number"
                        name="stok"
                        value="<?= $buku['stok']; ?>"
                        min="0"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Tahun Terbit</label>

                    <input
                        type="number"
                        name="tahun_terbit"
                        value="<?= $buku['tahun_terbit']; ?>"
                        min="1900"
                        max="2100"
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
                href="buku.php"
                class="btn"
            >
                Batal
            </a>

        </form>

    </section>

</main>


<footer>

    <p>© 2026 Perpustakaan Zidane</p>

</footer>

</body>

</html>