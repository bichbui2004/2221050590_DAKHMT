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

$user_id  = $_SESSION['user_id'] ?? 0; // Nếu có đăng nhập

/* 2. KIỂM TRA TỒN KHO */
if ($type === 'combo') {
    $sql = "SELECT so_luong FROM combo WHERE id = ?";
} else {
    $sql = "SELECT so_luong FROM san_pham WHERE id = ?";
}
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$check = $result->fetch_assoc();

if (!$check) {
    header("Location: index.php");
    exit();
}

$ton_kho = (int)$check['so_luong'];
if ($so_luong > $ton_kho) {
    $so_luong = $ton_kho;
}

/* 3. KHỞI TẠO GIỎ HÀNG TRONG SESSION */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* 4. CẬP NHẬT GIỎ HÀNG TRONG SESSION */
$cart_key = $type . "_" . $id;
if (isset($_SESSION['cart'][$cart_key])) {
    $tong = $_SESSION['cart'][$cart_key]['so_luong'] + $so_luong;
    if ($tong > $ton_kho) $tong = $ton_kho;
    $_SESSION['cart'][$cart_key]['so_luong'] = $tong;
} else {
    $_SESSION['cart'][$cart_key] = [
        'id'       => $id,
        'type'     => $type,
        'ten'      => $ten,
        'gia'      => $gia,
        'hinh'     => $hinh,
        'so_luong' => $so_luong
    ];
}

/* 5. CẬP NHẬT GIỎ HÀNG TRONG DATABASE (nếu có đăng nhập) */
if ($user_id > 0) {
    // Kiểm tra giỏ hàng hiện tại của user
    $sql_check_cart = "SELECT id FROM gio_hang WHERE id_nguoi_dung = ? LIMIT 1";
    $stmt_cart = $conn->prepare($sql_check_cart);
    $stmt_cart->bind_param("i", $user_id);
    $stmt_cart->execute();
    $res_cart = $stmt_cart->get_result();

    if ($row_cart = $res_cart->fetch_assoc()) {
        $id_gio_hang = $row_cart['id'];
    } else {
        // Tạo giỏ hàng mới
        $sql_new_cart = "INSERT INTO gio_hang (id_nguoi_dung, tong_tien) VALUES (?, 0)";
        $stmt_new = $conn->prepare($sql_new_cart);
        $stmt_new->bind_param("i", $user_id);
        $stmt_new->execute();
        $id_gio_hang = $conn->insert_id;
    }

    // Kiểm tra sản phẩm đã có trong chi tiết giỏ hàng chưa
    $sql_check_item = "SELECT id, so_luong FROM chi_tiet_gio_hang 
                       WHERE id_gio_hang = ? AND 
                             ((id_san_pham = ? AND ? IS NOT NULL) OR (id_combo = ? AND ? IS NOT NULL)) 
                       LIMIT 1";
    $stmt_item = $conn->prepare($sql_check_item);
    $id_san_pham = ($type === 'san_pham') ? $id : null;
    $id_combo   = ($type === 'combo') ? $id : null;
    $stmt_item->bind_param("iiiii", $id_gio_hang, $id_san_pham, $id_san_pham, $id_combo, $id_com_bo);
    $stmt_item->execute();
    $res_item = $stmt_item->get_result();

    if ($row_item = $res_item->fetch_assoc()) {
        // Cập nhật số lượng
        $new_qty = $row_item['so_luong'] + $so_luong;
        if ($new_qty > $ton_kho) $new_qty = $ton_kho;
        $sql_update_item = "UPDATE chi_tiet_gio_hang SET so_luong = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update_item);
        $stmt_update->bind_param("ii", $new_qty, $row_item['id']);
        $stmt_update->execute();
    } else {
        // Thêm mới chi tiết giỏ hàng
        $sql_add_item = "INSERT INTO chi_tiet_gio_hang (id_gio_hang, id_san_pham, id_combo, so_luong, gia) 
                         VALUES (?, ?, ?, ?, ?)";
        $stmt_add = $conn->prepare($sql_add_item);
        $stmt_add->bind_param("iiiii", $id_gio_hang, $id_san_pham, $id_combo, $so_luong, $gia);
        $stmt_add->execute();
    }

    // Cập nhật tổng tiền giỏ hàng
    $sql_update_total = "UPDATE gio_hang SET tong_tien = tong_tien + ? WHERE id = ?";
    $stmt_total = $conn->prepare($sql_update_total);
    $tong_them = $gia * $so_luong;
    $stmt_total->bind_param("di", $tong_them, $id_gio_hang);
    $stmt_total->execute();
}

/* 6. QUAY LẠI TRANG CHI TIẾT */
if ($type === 'combo') {
    header("Location: index.php?page_layout=chitietcombo&id=$id");
} else {
    header("Location: index.php?page_layout=chitiet&id=$id");
}
exit();
?>
