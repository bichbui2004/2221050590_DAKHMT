<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="trangchu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <main>
        <div class="banner">
            <div class="slide active">
                <img src="pic/thumb-1-5.jpg">
                <a href="index.php?page_layout=gioithieu" class="banner-button">Về chúng tôi</a>
            </div>
            <div class="slide">
                <img src="pic/Bakery.png">
                <a href="index.php?page_layout=gioithieu" class="banner-button">Về chúng tôi</a>
            </div>
            <div class="slide">
                <img src="pic/Black-Forest-Cake-FT-RECIPE0623-29bb291902e845bab17a7fc1d65e4ebb.jpg">
                <a href="index.php?page_layout=gioithieu" class="banner-button">Về chúng tôi</a>
            </div>
            <div class="next">❯</div>
            <div class="prev">❮</div>
        </div>

        <div class="tintuc">
            <?php 
            include("../admin/connect.php");
            $sql = "SELECT * FROM blog ";
            $result = mysqli_query($conn, $sql);
            //echo $row['tieu_de'];
            while($row = mysqli_fetch_array($result)){
            ?>
            <div class="baiviet"><a class="chitiet" href="index.php?page_layout=chitietbaiviet&id=<?php echo $row["id"] ?>">
                <div class="anh"><img src="../admin/<?php echo $row['hinh_anh']; ?>"></div>
                <div class="tieude"><?php echo $row['tieu_de']; ?></div></a>
            </div>
           
            <?php } ?>
        </div>

        <div class="marquee">
            <div class="marquee-content">
                <img src="pic/phap_1_1.jpg" width="500px">
                <img src="pic/2-banh-dat-trong-gio-1530206918631705009723.webp" width="300px">
                <img src="pic/0555bbe9368a05bff51437bdc1ff702e.webp" width="400px">

                <img src="pic/phap_1_1.jpg" width="500px">
                <img src="pic/2-banh-dat-trong-gio-1530206918631705009723.webp" width="300px">
                <img src="pic/0555bbe9368a05bff51437bdc1ff702e.webp" width="400px">
            </div>
        </div>

        <div class="best-seller" align="center">
            <h1 style="font-size: 40px;">Best Seller</h1>
            <h2>Sự lựa chọn hàng đầu. Hương vị tạo nên tên tuổi chúng tôi.</h2>
            <div class="ds-banh">
                <?php 
                $sql = "SELECT * FROM san_pham order by id DESC LIMIT 8 "; //lấy ngẫu nhiên order by RAND() LIMIT 8 
                $result = mysqli_query($conn, $sql);
                while($spham = mysqli_fetch_array($result)){
                ?>
                <div class="banh"><a class="chitiet" href="index.php?page_layout=chitiet&id=<?php echo $spham["id"] ?>">
                    <div><img src="../admin/<?php echo $spham['hinh_anh']; ?>"></div>
                    <div><?php echo $spham['ten_san_pham']; ?></div></a>
                    <div class="gia-gio">
                        <span class="gia"><?php echo $spham['gia'] ."đ"; ?></span>  <button style="font-size:15px "><i class="fa fa-shopping-cart"></i></button>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>
</body>
</html>
<script>
let index = 0;
const slides = document.querySelectorAll(".slide");

function showSlide(i) {
    slides.forEach((img, k) => {
        img.classList.remove("active");
        if (k === i) img.classList.add("active");
    });
}

document.querySelector(".next").onclick = () => {
    index = (index + 1) % slides.length;
    showSlide(index);
};

document.querySelector(".prev").onclick = () => {
    index = (index - 1 + slides.length) % slides.length;
    showSlide(index);
};
</script>
