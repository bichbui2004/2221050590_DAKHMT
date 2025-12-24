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
    <?php
    session_start();
    ?>
    <header>
        <div class="logo">
            <img src="pic/Screenshot 2025-11-22 092946.png">
        </div>
        <ul class="menu">
            <li><a href="index.php?page_layout=trangchu">TRANG CHỦ</a></li>
            <li><a href="index.php?page_layout=gioithieu">GIỚI THIỆU</a></li>
            <li><a href="index.php?page_layout=banhngot">BÁNH NGỌT</a>
                <ul class="sub-menu">
                    <li><a href="index.php?page_layout=bonglantrungmuoi">Bông lan trứng muối</a></li>
                    <li><a href="index.php?page_layout=banhlanh">Bánh lạnh</a></li>
                    <li><a href="index.php?page_layout=banhmi">Bánh mì</a></li>
                    <li><a href="index.php?page_layout=cookie">Cookies</a></li>
                    <li><a href="index.php?page_layout=combo">Combo</a></li>
                </ul></li>
            <li><a href="index.php?page_layout=banhkem">BÁNH KEM</a>
                <ul class="sub-menu">
                    <li><a href="index.php?page_layout=banhcuoi">Bánh cưới</a></li>
                    <li><a href="index.php?page_layout=banhkhaitruong">Bánh khai trương</a></li>
                    <li><a href="index.php?page_layout=banhkiniem">Bánh kỉ niệm</a></li>
                    <li><a href="index.php?page_layout=banhsinhnhat">Bánh sinh nhật</a></li>
                </ul></li>
            <li><a href="index.php?page_layout=blog">BLOG</a></li>
            <li><a href="index.php?page_layout=timkiem"><i class="fa fa-search" style="font-size:20px"></i></a></li>
            <?php if (isset($_SESSION["username"])): ?>
                <li><a href="index.php?page_layout=taikhoan"><i class="fa fa-user" style="font-size:20px"></i></a></li>
                <li><a href="index.php?page_layout=giohang"><i class="fa fa-shopping-cart" style="font-size:20px"></i></a></li>
            <?php else: ?>
                <li><a href="../dangnhap.php">Đăng nhập</a></li>
                <li><a href="../dangki.php">Đăng kí</a></li>
            <?php endif; ?>
        </ul>
    </header>
    <?php
        if(isset($_GET['page_layout'])){
            switch($_GET['page_layout']){
                case 'trangchu':
                    include "trangchu.php";
                    break;
                case 'gioithieu':
                    include "gioithieu.php";
                    break;
                case 'banhngot':
                    include "banhngot.php";
                    break;
                case 'banhkem':
                    include "banhkem.php";
                    break;
                case 'blog':
                    include "blog.php";
                    break;
                case 'taikhoan':
                    include "taikhoan/index.php";
                    break;
                case 'giohang':
                    include "giohang.php";
                    break;
                case 'timkiem':
                    include "timkiem.php";
                    break;
                case 'bonglantrungmuoi':
                    include "trang/bonglantrungmuoi.php";
                    break;
                case 'banhlanh':
                    include "trang/banhlanh.php";
                    break;
                case 'banhmi':
                    include "trang/banhmi.php";
                    break;
                case 'cookie':
                    include "trang/cookie.php";
                    break;
                case 'combo':
                    include "trang/combo.php";
                    break;
                case 'banhsinhnhat':
                    include "trang/banhsinhnhat.php";
                    break;
                case 'banhcuoi':
                    include "trang/banhcuoi.php";
                    break;
                case 'banhkhaitruong':
                    include "trang/banhkhaitruong.php";
                    break;
                case 'banhkiniem':
                    include "trang/banhkiniem.php";
                    break;
                case 'chitiet':
                    include "chitiet/chitietsanpham.php";
                    break;
                case 'chitietbaiviet':
                    include "chitiet/chitietbaiviet.php";
                    break; 
                case 'chitietcombo':
                    include "chitiet/chitietcombo.php";
                    break;
                case 'chitietdonhang':
                    include "chitiet/chitietdonhang.php";
                    break;
                case 'checkout':
                    include "checkout.php";
                    break;                  
                case 'dangxuat':
                    session_unset();      // Xóa biến session
                    session_destroy();    // Hủy session
                    header("Location: index.php");
                    exit();
                    break;
            }
        }else{
                include "trangchu.php";
        }
    ?> 
    <footer>
        <div class="logo">
            <div><img src="pic/Screenshot 2025-11-22 112855.png"></div>
        </div>
        <div class="lienhe">
            <ul class="diachi">
                <li>GIỜ MỞ CỬA: 7:30 - 22:00</li>
                <li>Địa chỉ: Trường Đại học Mỏ - Địa Chất, 18 P. Viên, Đông Ngạc, Bắc Từ Liêm, Hà Nội</li>
            </ul>
            <ul class="thongtinlienhe">
                <li>Hotline: 024 3838 9633</li>
                <li>Email: support@student.humg.edu.vn</li>
            </ul>
        </div>
    </footer>
</body>
</html>
