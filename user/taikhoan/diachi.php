<?php
// Đảm bảo session đã được khởi tạo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra kết nối và session
include("../admin/connect.php");
$user_id = $_SESSION['user_id'] ?? 0;
$message = "";

if ($user_id == 0) {
    die("Vui lòng đăng nhập để xem trang này.");
}

// 1. LẤY DỮ LIỆU ĐỊA CHỈ HIỆN TẠI
// Lưu ý: Sửa tên bảng cho đúng với database của bạn (tôi dùng dia_chi_nhan_hang)
$sql_address = "SELECT * FROM dia_chi_giao_hang WHERE id_nguoi_dung = ? LIMIT 1";
$stmt_addr = $conn->prepare($sql_address);
$stmt_addr->bind_param("i", $user_id);
$stmt_addr->execute();
$address_data = $stmt_addr->get_result()->fetch_assoc();

// 2. XỬ LÝ CẬP NHẬT KHI NHẤN NÚT
if (isset($_POST['update_address'])) {
    $dc_chitiet = $_POST['dia_chi'];
    $phuong = $_POST['phuong_xa'];
    $quan = $_POST['quan_huyen'];
    $tinh = $_POST['tinh_thanh'];

    if ($address_data) {
        // Nếu đã có địa chỉ thì UPDATE
        $sql_up_addr = "UPDATE dia_chi_giao_hang SET dia_chi=?, phuong_xa=?, quan_huyen=?, tinh_thanh=? WHERE id_nguoi_dung=?";
        $stmt_up = $conn->prepare($sql_up_addr);
        $stmt_up->bind_param("ssssi", $dc_chitiet, $phuong, $quan, $tinh, $user_id);
    } else {
        // Nếu chưa có thì INSERT mới
        $sql_up_addr = "INSERT INTO dia_chi_giao_hang (dia_chi, phuong_xa, quan_huyen, tinh_thanh, id_nguoi_dung) VALUES (?, ?, ?, ?, ?)";
        $stmt_up = $conn->prepare($sql_up_addr);
        $stmt_up->bind_param("ssssi", $dc_chitiet, $phuong, $quan, $tinh, $user_id);
    }
    
    if($stmt_up->execute()) {
        $message = "Cập nhật địa chỉ thành công!";
        // Cập nhật lại biến hiển thị ngay lập tức
        $address_data = [
            'dia_chi' => $dc_chitiet,
            'phuong_xa' => $phuong,
            'quan_huyen' => $quan,
            'tinh_thanh' => $tinh
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Địa chỉ giao hàng</title>
    <link rel="stylesheet" href="taikhoan.css"> </head>
<body>
<div class="account-container" style="display: flex; max-width: 1200px; margin: 20px auto; gap: 20px; font-family: sans-serif;">

    <div class="main-content" style="flex: 1; background: #fff; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
        
        <?php if($message != ""): ?>
            <div style="padding: 15px; margin-bottom: 20px; background: #d4edda; color: #155724; border-radius: 5px;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <h4 style="margin-top: 0;">Địa chỉ giao hàng</h4>
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Số nhà, Tên đường</label>
                <input type="text" name="dia_chi" style="width: 100%; padding: 8px;" value="<?php echo htmlspecialchars($address_data['dia_chi'] ?? ''); ?>" placeholder="VD: 123 Đường ABC">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Phường / Xã</label>
                <input type="text" name="phuong_xa" style="width: 100%; padding: 8px;" value="<?php echo htmlspecialchars($address_data['phuong_xa'] ?? ''); ?>">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Quận / Huyện</label>
                <input type="text" name="quan_huyen" style="width: 100%; padding: 8px;" value="<?php echo htmlspecialchars($address_data['quan_huyen'] ?? ''); ?>">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Tỉnh / Thành phố</label>
                <input type="text" name="tinh_thanh" style="width: 100%; padding: 8px;" value="<?php echo htmlspecialchars($address_data['tinh_thanh'] ?? ''); ?>">
            </div>

            <button type="submit" name="update_address" class="save-button" style="padding: 10px 20px; cursor: pointer; background: #333; color: #fff; border: none; border-radius: 4px;">
                Cập nhật địa chỉ
            </button>
        </form>

    </div>
</div> 
</body>
</html>