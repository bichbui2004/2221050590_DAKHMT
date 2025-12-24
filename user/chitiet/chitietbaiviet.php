<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .container{
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        .content{
            width: 80%;
            margin: auto;
            font-size: 17px;
        }
        .container img{
            width: 500px;
            height: 400px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php
        include(__DIR__ . "/../../admin/connect.php");
        $id = $_GET['id'];
        $sql = "SELECT * FROM blog WHERE id = '$id'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
    ?>
    <div class="container">
        <h1><?php echo $row['tieu_de'] ?></h1>
        <div style="display: flex; gap: 25px">
            <p align="center"><img src ="../admin/<?php echo $row['hinh_anh']; ?>"></p>
            <div class="content">
                <?php echo $row['noi_dung'] ?>
            </div>
        </div>
    </div>
</body>
</html>