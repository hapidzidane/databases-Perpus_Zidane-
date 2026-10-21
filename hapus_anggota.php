<?php

require_once "src/koneksi.php";


if (isset($_GET['id'])) {

    $id_anggota = $_GET['id'];

    mysqli_query(
        $koneksi,
        "DELETE FROM anggota
         WHERE id_anggota = '$id_anggota'"
    );

}

header("Location: anggota.php");
exit;

?>