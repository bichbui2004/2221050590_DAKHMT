<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include("../admin/connect.php");

// 1. Kiểm tra ID đơn hàng
if (!isset($_GET['id'])) {
    echo "Không tìm thấy mã đơn hàng.";
    exit;
}

$id_don_hang = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// 2. Lấy thông tin chung của đơn hàng (Để đảm bảo tính bảo mật, check luôn user_id)
$sql_order = "SELECT dh.*, tt.ten_trang_thai 
              FROM don_hang dh
              JOIN trang_thai tt ON dh.id_trang_thai = tt.id
              WHERE dh.id = $id_don_hang AND dh.id_nguoi_dung = $user_id";
$res_order = mysqli_query($conn, $sql_order);
$order_info = mysqli_fetch_assoc($res_order);

if (!$order_info) {
    echo "Đơn hàng không tồn tại hoặc bạn không có quyền xem.";
    exit;
}

// 3. Lấy danh sách sản phẩm & combo trong đơn hàng này
$sql_items = "SELECT ct.*, 
                     sp.ten_san_pham, sp.hinh_anh as hinh_sp,
                     cb.ten_combo, cb.hinh_anh as hinh_cb
              FROM chi_tiet_don_hang ct
              LEFT JOIN san_pham sp ON ct.id_san_pham = sp.id
              LEFT JOIN combo cb ON ct.id_combo = cb.id
              WHERE ct.id_don_hang = $id_don_hang";
$res_items = mysqli_query($conn, $sql_items);
?>

<div style="max-width: 900px; margin: 20px auto; font-family: Arial, sans-serif; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2 style="color: #333; border-bottom: 2px solid #f39c12; padding-bottom: 10px;">Chi tiết đơn hàng #<?php echo $id_don_hang; ?></h2>
    
    <div style="display: flex; justify-content: space-between; margin-bottom: 20px; background: #f9f9f9; padding: 15px; border-radius: 5px;">
        <div>
            <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order_info['ngay_dat'])); ?></p>
            <p><strong>Trạng thái:</strong> 
                <span style="color: #e67e22; font-weight: bold;"><?php echo $order_info['ten_trang_thai']; ?></span>
            </p>
        </div>
        <div style="text-align: right;">
            <p><strong>Khách hàng:</strong> <?php echo $_SESSION['username']; ?></p>
            <p style="font-size: 18px; color: #d9534f;"><strong>Tổng cộng: <?php echo number_format($order_info['tong_tien'], 0, ',', '.'); ?>đ</strong></p>
        </div>
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f39c12; color: white;">
                <th style="padding: 10px; border: 1px solid #ddd;">Hình ảnh</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Tên mặt hàng</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Số lượng</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Đơn giá</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php while($item = mysqli_fetch_assoc($res_items)): ?>
                <?php 
                    // Logic nhận diện Sản phẩm hay Combo
                    $is_combo = !empty($item['id_combo']);
                    $ten = $is_combo ? $item['ten_combo'] : $item['ten_san_pham'];
                    $hinh = $is_combo ? $item['hinh_cb'] : $item['hinh_sp'];
                    $thanh_tien = $item['so_luong'] * $item['gia'];
                ?>
                <tr style="text-align: center;">
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <img src="../admin/<?php echo $hinh; ?>" width="60" style="border-radius: 5px;">
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd; text-align: left;">
                        <strong><?php echo $ten; ?></strong><br>
                        <small style="color: #888;"><?php echo $is_combo ? "[Combo]" : "[Sản phẩm]"; ?></small>
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $item['so_luong']; ?></td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo number_format($item['gia'], 0, ',', '.'); ?>đ</td>
                    <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">
                        <?php echo number_format($thanh_tien, 0, ',', '.'); ?>đ
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <a href="index.php?page_layout=taikhoan&tab=donhang" style="text-decoration: none; background: #6c757d; color: white; padding: 10px 20px; border-radius: 5px;">
            <i class="fa fa-arrow-left"></i> Quay lại lịch sử đơn hàng
        </a>
        
        <?php if ($order_info['id_trang_thai'] == 1): ?>
            <a href="xuly_huydon.php?id=<?php echo $id_don_hang; ?>" 
               onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')"
               style="text-decoration: none; background: #d9534f; color: white; padding: 10px 20px; border-radius: 5px; float: right;">
                Hủy đơn hàng này
            </a>
        <?php endif; ?>
    </div>
</div>