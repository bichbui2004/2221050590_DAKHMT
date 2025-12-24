<?php
session_start();
include("../admin/connect.php");

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $id_don_hang = (int)$_GET['id'];
    $user_id = $_SESSION['user_id'];

    // 1. Kiểm tra đơn hàng có đúng của người dùng này và đang ở trạng thái 'Đang xử lý' (1) không
    $sql_check = "SELECT id_trang_thai FROM don_hang WHERE id = $id_don_hang AND id_nguoi_dung = $user_id";
    $res = mysqli_query($conn, $sql_check);
    $order = mysqli_fetch_assoc($res);

    if ($order && $order['id_trang_thai'] == 1) {
        $conn->begin_transaction();
        try {
            // 2. Cập nhật trạng thái đơn hàng thành 4 (Hủy)
            $sql_update = "UPDATE don_hang SET id_trang_thai = 4 WHERE id = $id_don_hang";
            mysqli_query($conn, $sql_update);

            // 3. Lấy các mặt hàng trong đơn để hoàn trả kho
            $sql_items = "SELECT id_san_pham, id_combo, so_luong FROM chi_tiet_don_hang WHERE id_don_hang = $id_don_hang";
            $res_items = mysqli_query($conn, $sql_items);

            while ($item = mysqli_fetch_assoc($res_items)) {
                $sl = $item['so_luong'];
                if (!empty($item['id_combo'])) {
                    $id_cb = $item['id_combo'];
                    mysqli_query($conn, "UPDATE combo SET so_luong = so_luong + $sl WHERE id = $id_cb");
                } else {
                    $id_sp = $item['id_san_pham'];
                    mysqli_query($conn, "UPDATE san_pham SET so_luong = so_luong + $sl WHERE id = $id_sp");
                }
            }

            $conn->commit();
            echo "<script>alert('Hủy đơn hàng thành công. Sản phẩm đã được hoàn trả kho!'); window.location.href='index.php?page_layout=taikhoan&tab=donhang';</script>";
        } catch (Exception $e) {
            $conn->rollback();
            echo "Có lỗi xảy ra: " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Không thể hủy đơn hàng này!'); window.history.back();</script>";
    }
} else {
    header("Location: index.php");
}
?>