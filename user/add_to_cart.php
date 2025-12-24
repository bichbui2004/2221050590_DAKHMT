<?php
session_start();
include("../admin/connect.php");

/* 1. LẤY DỮ LIỆU TỪ FORM */
$id       = (int)$_POST['id'];
$ten      = $_POST['ten'];
$gia      = (int)$_POST['gia'];
$hinh     = $_POST['hinh'];
$so_luong = (int)$_POST['so_luong'];
$type     = isset($_POST['type']) ? $_POST['type'] : 'san_pham'; // Nhận diện loại hàng

/* 2. KIỂM TRA TỒN KHO THEO LOẠI (SẢN PHẨM HOẶC COMBO) */
if ($type === 'combo') {
    $sql = "SELECT so_luong FROM combo WHERE id = $id";
} else {
    $sql = "SELECT so_luong FROM san_pham WHERE id = $id";
}

$result = mysqli_query($conn, $sql);
$check = mysqli_fetch_assoc($result);

if (!$check) {
    // Nếu không tìm thấy hàng trong kho
    header("Location: index.php");
    exit();
}

$ton_kho = (int)$check['so_luong'];

// Giới hạn lại số lượng mua nếu vượt quá tồn kho
if ($so_luong > $ton_kho) {
    $so_luong = $ton_kho;
}

/* 3. KHỞI TẠO GIỎ HÀNG */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* 4. CẬP NHẬT GIỎ HÀNG */
// Để tránh trùng ID giữa sản phẩm lẻ và combo (ví dụ cùng ID là 1), 
// ta nên tạo key trong giỏ hàng kết hợp giữa Type và ID.
$cart_key = $type . "_" . $id;

if (isset($_SESSION['cart'][$cart_key])) {
    $tong = $_SESSION['cart'][$cart_key]['so_luong'] + $so_luong;

    if ($tong > $ton_kho) {
        $tong = $ton_kho;
    }

    $_SESSION['cart'][$cart_key]['so_luong'] = $tong;
} else {
    $_SESSION['cart'][$cart_key] = [
        'id'       => $id,
        'type'     => $type, // Lưu lại loại để sau này xử lý thanh toán dễ hơn
        'ten'      => $ten,
        'gia'      => $gia,
        'hinh'     => $hinh,
        'so_luong' => $so_luong
    ];
}

/* 5. QUAY LẠI TRANG CHI TIẾT TƯƠNG ỨNG */
if ($type === 'combo') {
    header("Location: index.php?page_layout=chitietcombo&id=$id");
} else {
    header("Location: index.php?page_layout=chitiet&id=$id");
}
exit();
?>