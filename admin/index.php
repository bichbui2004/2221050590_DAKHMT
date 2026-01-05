<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tài khoản</title>
    <link rel="stylesheet" href="quantri-taikhoan.css">
    <style>
        .box-trai .box{
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <?php
        session_start();
        if(!isset($_SESSION["username"])){
            header('location: ../dangnhap.php');
        }
    ?>
    <div class="box-trai">
        <div class="title" align="center">TRANG QUẢN LÝ</div>
        <ul class="box">
            <li><a href="index.php?page_layout=tongquan">Tổng quan</a></li>
            <li><a href="index.php?page_layout=taikhoan">Tài khoản</a></li>
            <li><a href="index.php?page_layout=danhmuc">Danh mục</a></li>
            <li><a href="index.php?page_layout=sanpham">Sản phẩm</a></li>
            <li><a href="index.php?page_layout=combo">Combo</a></li>
            <li><a href="index.php?page_layout=blog">Blog</a></li>
            <li><a href="index.php?page_layout=donhang">Đơn hàng</a></li>
        </ul>
    </div>
    <div class="box-phai">
        <ul class="menu">
            <li><a href="#" style="font-size: 35px; font-weight:bolder;font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; padding: 5px;color: white;">MONA BAKERY</a></li>
            <li><a href="" style="font-size: 15px; font-weight: 100;font-family: Arial, Helvetica, sans-serif; padding: 5px;color: white;"><?php echo "Xin chào " . $_SESSION["username"]; ?></a>
                <ul class="sub-menu">
                    <li><a href="../user/index.php?page_layout=trangchu">Trang chủ</a></li>
                    <li>
                        <a href="index.php?page_layout=dangxuat"
                            onclick="return confirm('Bạn chắc chắn muốn đăng xuất?')">Đăng xuất
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    <?php
        if(isset($_GET['page_layout'])){
            switch($_GET['page_layout']){
                case 'tongquan':
                    include "tongquan.php";
                    break;
                case 'taikhoan':
                    include "quantri-taikhoan.php";
                    break;
                case 'danhmuc':
                    include "quantri-danhmuc.php";
                    break;
                case 'sanpham':
                    include "quantri-sanpham.php";
                    break;
                case 'donhang':
                    include "quantri-donhang.php";
                    break;
                case 'themtaikhoan':
                    include "them/quantri-themtaikhoan.php";
                    break;
                case 'capnhattaikhoan':
                    include "capnhat/quantri-capnhattaikhoan.php";
                    break;
                case 'themdanhmuc':
                    include "them/quantri-themdanhmuc.php";
                    break;
                case 'capnhatdanhmuc':
                    include "capnhat/quantri-capnhatdanhmuc.php";
                    break;
                case 'themsanpham':
                    include "them/quantri-themsanpham.php";
                    break;
                case 'capnhatsanpham':
                    include "capnhat/quantri-capnhatsanpham.php";
                    break;
                case 'capnhatdonhang':
                    include "capnhat/quantri-capnhatdonhang.php";
                    break;
                case 'combo':
                    include "quantri-combo.php";
                    break;
                case 'themcombo':
                    include "them/quantri-themcombo.php";
                    break;
                case 'capnhatcombo':
                    include "capnhat/quantri-capnhatcombo.php";
                    break;
                case 'blog':
                    include "quantri-blog.php";
                    break;
                case 'themblog':
                    include "them/quantri-themblog.php";
                    break;
                case 'capnhatblog':
                    include "capnhat/quantri-capnhatblog.php";
                    break;
                case 'dangxuat':
                    session_unset();      // Xóa biến session
                    session_destroy();    // Hủy session
                    header("Location: index.php");
                    exit();
                    break;
            }
        }else{
                include "tongquan.php";
        }
    ?> 
    </div>
</body>
</html>