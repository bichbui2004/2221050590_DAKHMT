<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include("../admin/connect.php");

$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id == 0) die("Vui lòng đăng nhập để thanh toán.");

// Lấy thông tin địa chỉ mặc định của người dùng
$sql_addr = "SELECT dc.*, nd.ho_ten FROM dia_chi_giao_hang dc JOIN nguoi_dung nd ON dc.id_nguoi_dung = nd.id WHERE id_nguoi_dung = ? LIMIT 1";
$stmt = $conn->prepare($sql_addr);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$address = $stmt->get_result()->fetch_assoc();

if (!$address) {
    die("Bạn chưa có địa chỉ giao hàng. <a href='index.php?page_layout=taikhoan&tab=diachi'>Cập nhật ngay</a>");
}
?>

<div class="checkout-container" style="max-width: 800px; margin: 20px auto; font-family: sans-serif;">
    <h2>Xác nhận đơn hàng</h2>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <h4>Địa chỉ nhận hàng</h4>
        <p><strong>Người nhận:</strong> <?php echo $address['ho_ten']  ?></p>
        <p><strong>Địa chỉ:</strong> <?php echo "{$address['dia_chi']}, {$address['phuong_xa']}, {$address['quan_huyen']}, {$address['tinh_thanh']}"; ?></p>
    </div>

    <form action="xuly_donhang.php" method="POST">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f4f4f4; text-align: left;">
                    <th style="padding: 10px;">Sản phẩm</th>
                    <th style="padding: 10px;">Giá</th>
                    <th style="padding: 10px;">Số lượng</th>
                    <th style="padding: 10px;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $tong_tien = 0;
                foreach ($_SESSION['cart'] as $id => $item): 
                    $thanh_tien = $item['gia'] * $item['so_luong'];
                    $tong_tien += $thanh_tien;
                ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;"><?php echo $item['ten']; ?></td>
                    <td style="padding: 10px;"><?php echo number_format($item['gia']); ?>đ</td>
                    <td style="padding: 10px;"><?php echo $item['so_luong']; ?></td>
                    <td style="padding: 10px;"><?php echo number_format($thanh_tien); ?>đ</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="text-align: right; margin-top: 20px;">
            <h3>Tổng cộng: <span style="color: red;"><?php echo number_format($tong_tien); ?>đ</span></h3>
            <input type="hidden" name="tong_tien" value="<?php echo $tong_tien; ?>">
            <button type="submit" name="confirm_order" style="padding: 10px 30px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
                XÁC NHẬN ĐẶT HÀNG
            </button>
        </div>
    </form>
</div>