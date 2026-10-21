<?php

require_once "src/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $penerbit = $_POST['penerbit'];
    $penulis = $_POST['penulis'];
    $stok = $_POST['stok'];
    $tahun_terbit = $_POST['tahun_terbit'];

    $query = "INSERT INTO buku
              (judul, kategori, penerbit, penulis, stok, tahun_terbit)
              VALUES
              ('$judul', '$kategori', '$penerbit', '$penulis', '$stok', '$tahun_terbit')";

    mysqli_query($koneksi, $query);

    header("Location: buku.php");
    exit;
}

?>