<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include("../admin/connect.php");

if (isset($_POST['confirm_order']) && !empty($_SESSION['cart'])) {
    $user_id = $_SESSION['user_id'];
    $tong_tien = (float)$_POST['tong_tien'];
    $ngay_dat = date('Y-m-d H:i:s');
    $id_trang_thai = 1;

    // --- LẤY ĐỊA CHỈ MẶC ĐỊNH ---
    $sql_get_addr = "SELECT id FROM dia_chi_giao_hang WHERE id_nguoi_dung = ? LIMIT 1";
    $stmt_addr = $conn->prepare($sql_get_addr);
    $stmt_addr->bind_param("i", $user_id);
    $stmt_addr->execute();
    $addr_row = $stmt_addr->get_result()->fetch_assoc();

    if (!$addr_row) {
        echo "<script>alert('Bạn chưa có địa chỉ giao hàng. Vui lòng cập nhật địa chỉ trong hồ sơ!'); window.history.back();</script>";
        exit();
    }
    $id_dia_chi = $addr_row['id'];

    $conn->begin_transaction();

    try {
        $sql_order = "INSERT INTO don_hang (id_nguoi_dung, tong_tien, ngay_dat, id_trang_thai, id_dia_chi) 
                      VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_order);
        $stmt->bind_param("idsii", $user_id, $tong_tien, $ngay_dat, $id_trang_thai, $id_dia_chi);
        $stmt->execute();
        $id_don_hang = $conn->insert_id;

        $sql_detail = "INSERT INTO chi_tiet_don_hang (id_don_hang, id_san_pham, id_combo, so_luong, gia) 
                       VALUES (?, ?, ?, ?, ?)";
        $stmt_detail = $conn->prepare($sql_detail);

        foreach ($_SESSION['cart'] as $item) {
            $item_id   = $item['id'];
            $so_luong  = $item['so_luong'];
            $gia       = $item['gia'];
            $type      = $item['type'];

            $id_san_pham_val = ($type === 'san_pham') ? $item_id : null;
            $id_combo_val    = ($type === 'combo') ? $item_id : null;

            $stmt_detail->bind_param("iiiid", $id_don_hang, $id_san_pham_val, $id_combo_val, $so_luong, $gia);
            $stmt_detail->execute();

            $table = ($type === 'combo') ? 'combo' : 'san_pham';
            $sql_update = "UPDATE $table SET so_luong = so_luong - ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ii", $so_luong, $item_id);
            $stmt_update->execute();
        }

        $sql_cart = "SELECT id FROM gio_hang WHERE id_nguoi_dung = ? LIMIT 1";
        $stmt_cart = $conn->prepare($sql_cart);
        $stmt_cart->bind_param("i", $user_id);
        $stmt_cart->execute();
        $row_cart = $stmt_cart->get_result()->fetch_assoc();
        if ($row_cart) {
            $id_gio_hang = $row_cart['id'];
            $conn->query("DELETE FROM chi_tiet_gio_hang WHERE id_gio_hang = $id_gio_hang");
            $conn->query("DELETE FROM gio_hang WHERE id = $id_gio_hang");
        }

        $conn->commit();

        unset($_SESSION['cart']);

        echo "<script>alert('Đặt hàng thành công!'); window.location.href='index.php?page_layout=taikhoan&tab=donhang';</script>";

    } catch (Exception $e) {
        $conn->rollback();
        echo "Lỗi: " . $e->getMessage();
    }
}
?>
