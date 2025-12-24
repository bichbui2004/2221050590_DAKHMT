<?php
// lấy tab
$tab = $_GET['tab'] ?? 'thongtin';
?>

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
