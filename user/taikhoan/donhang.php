<?php 
    // Đảm bảo session đã chạy để lấy $user_id
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include("../admin/connect.php");

    $user_id = $_SESSION['user_id'] ?? 0;

    if ($user_id == 0) {
        die("Vui lòng đăng nhập.");
    }

    $sql_orders = "SELECT * FROM don_hang WHERE id_nguoi_dung = ? ORDER BY ngay_dat DESC";
    $stmt_orders = $conn->prepare($sql_orders);
    $stmt_orders->bind_param("i", $user_id);
    $stmt_orders->execute();
    $orders_result = $stmt_orders->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử mua hàng</title>
    <link rel="stylesheet" href="taikhoan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .order-history{
            min-height: 320px;
        }
    </style>
</head>
<body>
    <div class="account-container" style="display: flex; max-width: 1200px; margin: 20px auto; gap: 20px; font-family: sans-serif;">

        <div class="main-content" style="flex: 1; background: #fff; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
            <div class="order-history">
                <h3 style="margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Lịch sử đơn hàng</h3>
                
                <table style="width:100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background: #f8f9fa; border-bottom: 2px solid #eee;">
                            <th style="padding: 12px 10px;">Mã đơn</th>
                            <th style="padding: 12px 10px;">Ngày đặt</th>
                            <th style="padding: 12px 10px;">Tổng tiền</th>
                            <th style="padding: 12px 10px;">Trạng thái</th>
                            <th style="padding: 12px 10px; text-align: center;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($orders_result->num_rows > 0): ?>
                            <?php while($order = $orders_result->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px 10px; font-weight: bold;"><?php echo $order['id']; ?></td>
                                <td style="padding: 15px 10px;"><?php echo date('d/m/Y', strtotime($order['ngay_dat'])); ?></td>
                                <td style="padding: 15px 10px; color: #d9534f; font-weight: bold;"><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>đ</td>
                                <td style="padding: 15px 10px;">
                                    <?php
                                    $status_text = "";
                                    $color = "#495057";
                                    switch($order['id_trang_thai']) {
                                        case 1: $status_text = "Đang xử lý"; $color = "#ffc107"; break;
                                        case 2: $status_text = "Đang giao"; $color = "#17a2b8"; break;
                                        case 3: $status_text = "Hoàn thành"; $color = "#28a745"; break;
                                        case 4: $status_text = "Đã hủy"; $color = "#dc3545"; break;
                                    }
                                    ?>
                                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: <?php echo $color; ?>; color: #fff;">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                                <td style="padding: 15px 10px; text-align: center;">
                                <a href="index.php?page_layout=chitietdonhang&id=<?php echo $order['id']; ?>" 
                                style="text-decoration: none; color: #007bff; font-size: 14px; margin-right: 10px;">
                                <i class="fa fa-eye"></i> Chi tiết
                                </a>

                                <?php if ($order['id_trang_thai'] == 1): ?>
                                    <a href="xuly_huydon.php?id=<?php echo $order['id']; ?>" 
                                    onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');"
                                    style="text-decoration: none; color: #d9534f; font-size: 14px;">
                                    <i class="fa fa-times"></i> Hủy đơn
                                    </a>
                                <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="padding: 30px; text-align: center; color: #888;">Bạn chưa có đơn hàng nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>