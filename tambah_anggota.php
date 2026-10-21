<?php

require_once "src/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama_anggota = $_POST['nama_anggota'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $tanggal_daftar = $_POST['tanggal_daftar'];

    $query = "INSERT INTO anggota
              (nama_anggota, alamat, no_telp, tanggal_daftar)
              VALUES
              ('$nama_anggota', '$alamat', '$no_telp', '$tanggal_daftar')";

    mysqli_query($koneksi, $query);

    header("Location: anggota.php");
    exit;
}

?>