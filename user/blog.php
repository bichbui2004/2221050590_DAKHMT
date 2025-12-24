<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="blog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <main>
        <div class="blog-grid">
            <?php 
            include("../admin/connect.php");
            $sql = "SELECT * FROM blog ";
            $result = mysqli_query($conn, $sql);
            //echo $row['tieu_de'];
            while($row = mysqli_fetch_array($result)){
            ?>
            <div class="item"><a class="chitiet" href="index.php?page_layout=chitietbaiviet&id=<?php echo $row["id"] ?>">
                <div class="anh"><img src="../admin/<?php echo $row['hinh_anh']; ?>"></div>
                <div class="tieude"><?php echo $row['tieu_de']; ?></div></a>
            </div>
            <?php } ?>
        </div>
    </main>
    
</body>
</html>
