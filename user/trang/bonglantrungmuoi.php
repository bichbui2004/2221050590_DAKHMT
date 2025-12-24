<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bánh kem</title>
    <link rel="stylesheet" href="banhkem.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
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
    </style>
</head>
<body>
    <main class="product-container">
        <div class="product-grid">
            <?php
            include("../admin/connect.php");
            $sql = "SELECT * FROM san_pham WHERE id_loai = 1 order by id desc";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)){
            ?>
            <div class="product-item"><a class="chitiet" href="index.php?page_layout=chitiet&id=<?php echo $row["id"] ?>">
                <img src="../admin/<?php echo $row['hinh_anh']; ?>">
                <h3><?php echo $row['ten_san_pham'];?></h3></a>
                <span class="price"><?php echo $row['gia'] . "đ";?></span> <button style="font-size:15px "><i class="fa fa-shopping-cart"></i></button>
            </div>
            <?php } ?>
        </div>
    </main>
    
</body>
</html>
</script>