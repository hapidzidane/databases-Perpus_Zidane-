<?php

require_once "src/koneksi.php";

if (isset($_GET['id'])) {

    $id_buku = $_GET['id'];

    mysqli_query(
        $koneksi,
        "DELETE FROM buku WHERE id_buku = '$id_buku'"
    );
}

header("Location: buku.php");
exit;

?>