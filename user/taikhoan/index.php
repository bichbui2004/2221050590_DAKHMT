<?php
// lấy tab
$tab = $_GET['tab'] ?? 'thongtin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            width: 100%;
            margin: 0px;
        }
        header{
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 95%;
            margin: auto;
            z-index: 100;
            position: relative;
        }
        .menu{
            display: flex;
            position: relative;
        }
        a{
            list-style: none;
            text-decoration-line: none;
            color: black;
        }
        li{
            list-style: none;
            padding: 10px;
        }
        ul{
            margin: 0px;
            padding: 0px;
        }
        .menu li ul{
            position:absolute;
            background-color: rgba(89, 81, 81, 0.3);
            border-radius: 5px;
            margin: 0px;
            top: 33px;
            display:none;
            z-index: 101;
        }
        .menu li:hover ul{
            display: block;
        }
        .sub-menu li{ 
            padding: 5px 10px;
            margin: 0px;
            border-radius: 0px 0px 5px 5px;
        }
        .sub-menu li a{
            color: white;
        }
        .sub-menu li:hover{
            background-color: rgba(89, 89, 94, 0.5);
        }
        .account-page {
            display: flex;
            max-width: 1200px;
            margin: auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }
        /* 1. Thanh Menu Điều hướng (Sidebar) */
        .sidebar {
            flex: 0 0 250px; /* Độ rộng cố định */
            padding: 30px 0;
            border-right: 1px solid #eee;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar li a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: black;
            transition: all 0.2s;
        }
        .sidebar li a:hover {
            color: #ff8c00;
            background-color: #f9f9f9;
        }
        .sidebar li.active a {
            color: #ff8c00; 
            font-weight: 600;
            background-color: #fff;
        }
        .main-content {
            flex-grow: 1;
            padding: 30px 40px;
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            flex: 1;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
            margin: 10px 0px;
        }
        label span {
            color: red;
            margin-left: 2px;
        }
        input[type="text"], 
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }
        input:focus {
            border-color: #ff8c00;
            outline: none;
        }
        .description {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
            }

        /* Phần Đổi mật khẩu */
            .password-change {
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 4px;
            margin-top: 30px;
        }
        .password-change h4 {
            margin-top: 0;
            font-weight: 600;
            color: #555;
        }
        /* Nút Lưu thay đổi */
        .save-button {
            background-color: #ff8c00; /* Màu cam */
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 40px; /* Bo tròn hoàn toàn */
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            margin-top: 30px;
            transition: background-color 0.2s;
        }
        .save-button:hover {
            background-color: #e67e00; 
        }
        footer{
            background-color: black;
            color: white;
            display: flex;
            align-items: center;
            gap: 90px;
        }
        footer .logo{
            margin-left: 30px;
        }
        .lienhe{
            display: flex;
            gap: 40px;
            width: 70%;
            align-items: center;
            margin-right: 50px;
        }
    </style>
</head>
<body>
    <main class="account-page">
    <div class="sidebar">
        <ul>
            <li><a href="index.php?page_layout=taikhoan&tab=donhang">Đơn hàng</a></li>
            <li><a href="index.php?page_layout=taikhoan&tab=diachi">Địa chỉ</a></li>
            <li><a href="index.php?page_layout=taikhoan&tab=thongtin">Tài khoản</a></li>
            <li>
                <a href="index.php?page_layout=dangxuat"
                   onclick="return confirm('Bạn chắc chắn muốn đăng xuất?')">
                    Đăng xuất
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <?php
        switch ($tab) {
            case 'donhang':
                include "taikhoan/donhang.php";
                break;

            case 'diachi':
                include "taikhoan/diachi.php";
                break;

            default:
                include "taikhoan/thongtin.php";
        }
        ?>
    </div>
</main>
</body>
</html>
