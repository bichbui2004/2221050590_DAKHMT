<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include("../admin/connect.php");

if (isset($_POST['confirm_order']) && !empty($_SESSION['cart'])) {
    $user_id = $_SESSION['user_id'];
    $tong_tien = $_POST['tong_tien'];
    $ngay_dat = date('Y-m-d H:i:s');
    $id_trang_thai = 1;

    // --- BƯỚC MỚI: TỰ ĐỘNG LẤY ĐỊA CHỈ ---
    // Lấy địa chỉ đầu tiên tìm thấy của người dùng này
    $sql_get_addr = "SELECT id FROM dia_chi_giao_hang WHERE id_nguoi_dung = $user_id LIMIT 1";
    $result_addr = mysqli_query($conn, $sql_get_addr);
    $addr_row = mysqli_fetch_assoc($result_addr);

    if (!$addr_row) {
        // Nếu người dùng chưa từng có địa chỉ nào trong bảng dia_chi_giao_hang
        echo "<script>alert('Bạn chưa có địa chỉ giao hàng. Vui lòng cập nhật địa chỉ trong hồ sơ!'); window.history.back();</script>";
        exit();
    }
    
    $id_dia_chi = $addr_row['id']; 
    // -------------------------------------

    $conn->begin_transaction();

    try {
        // 1. Lưu vào bảng don_hang (có id_dia_chi lấy được ở trên)
        $sql_order = "INSERT INTO don_hang (id_nguoi_dung, tong_tien, ngay_dat, id_trang_thai, id_dia_chi) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_order);
        
        // "idssi" -> i: user_id, d: tong_tien, s: ngay_dat, s: id_trang_thai, i: id_dia_chi
        $stmt->bind_param("idssi", $user_id, $tong_tien, $ngay_dat, $id_trang_thai, $id_dia_chi);
        $stmt->execute();
        
        $id_don_hang = $conn->insert_id;

        // 2. Lưu vào bảng chi_tiet_don_hang (Giữ nguyên logic sản phẩm/combo của bạn)
        $sql_detail = "INSERT INTO chi_tiet_don_hang (id_don_hang, id_san_pham, id_combo, so_luong, gia) VALUES (?, ?, ?, ?, ?)";
        $stmt_detail = $conn->prepare($sql_detail);

        foreach ($_SESSION['cart'] as $key => $item) {
            $item_id = $item['id'];
            $so_luong = $item['so_luong'];
            $gia = $item['gia'];
            $type = $item['type'];

            $id_san_pham_val = ($type !== 'combo') ? $item_id : null;
            $id_combo_val = ($type === 'combo') ? $item_id : null;
            
            $stmt_detail->bind_param("iiiid", $id_don_hang, $id_san_pham_val, $id_combo_val, $so_luong, $gia);
            $stmt_detail->execute();

            // Cập nhật tồn kho
            $table = ($type === 'combo') ? 'combo' : 'san_pham';
            $conn->query("UPDATE $table SET so_luong = so_luong - $so_luong WHERE id = $item_id");
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