<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .timkiem{
            min-height: 450px;
            width: 30%;
            margin: auto;
        }
        button{
            padding: 8px;
            
        }
        input{
            width:300px;
            padding:8px
        }
        .product-container {
            width: 80%;
            margin: auto;
            font-family: "Nunito", sans-serif;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .product-item {
            text-align:center;
        }

        .product-item img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 5px;
        }
        .product-item h3 {
            margin: 10px 0 5px;
            font-size: 18px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <?php
    include("../admin/connect.php");

    $tukhoa = "";
    if(isset($_GET['tukhoa'])){
        $tukhoa = trim($_GET['tukhoa']);
    }
    ?>
    <div class="timkiem">
        <h2 style="margin:20px 0;">Tìm kiếm sản phẩm</h2>
        <form method="get" action="index.php">
            <input type="hidden" name="page_layout" value="timkiem">
            <input type="text" name="tukhoa" placeholder="Nhập tên bánh cần tìm..." value="<?= htmlspecialchars($tukhoa) ?>">
            <button type="submit">Tìm</button>
        </form>
    </div>

    <?php
    if($tukhoa != ""){
        $sql = "SELECT * FROM san_pham 
                WHERE ten_san_pham LIKE '%$tukhoa%'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0){
    ?>
        <div class="product-container">
            <div class="product-grid">
        <?php
                while($row = mysqli_fetch_assoc($result)){
        ?>
                <div class="product-item">
                    <img src="../admin/<?php echo $row['hinh_anh']; ?>">
                    <h3><?php echo $row['ten_san_pham'];?></h3>
                    <span class="price"><?php echo $row['gia'] . "đ";?></span> <button style="font-size:15px "><i class="fa fa-shopping-cart"></i></button>
                </div>
        <?php
                }
        ?>
            </div>
        </div>
    <?php
        }else{
            echo "<p>Không tìm thấy sản phẩm phù hợp</p>";
        }
    }
    ?>

   
</body>
</html>