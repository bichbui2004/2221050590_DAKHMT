<?php 
    include('../connect.php');
    $id = $_GET['id'];
    $sql = "delete from san_pham where id = '$id'";
    mysqli_query($conn, $sql);
    header('location: ../index.php?page_layout=sanpham');
?>