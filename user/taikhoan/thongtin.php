<?php
include "../admin/connect.php"; 

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../dangnhap.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// 2. XỬ LÝ CẬP NHẬT KHI USER NHẤN LƯU
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ho_ten = $_POST['name'];
    $email = $_POST['email'];
    $sdt = $_POST['phone'];
    $ten_dang_nhap = $_POST['username'];
    
    // Cập nhật thông tin cơ bản trước
    $sql_update = "UPDATE nguoi_dung SET ho_ten = ?, email = ?, so_dien_thoai = ?, ten_dang_nhap = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssssi", $ho_ten, $email, $sdt, $ten_dang_nhap, $user_id);
    
    if ($stmt->execute()) {
        $message = "Cập nhật thông tin thành công!";
        $_SESSION['username'] = $ten_dang_nhap; // Cập nhật lại session nếu đổi tên
    }

    // Xử lý đổi mật khẩu (nếu người dùng có nhập mật khẩu mới)
    if (!empty($_POST['new_password'])) {
        $current_pass = $_POST['current_password'];
        $new_pass = $_POST['new_password'];
        $confirm_pass = $_POST['confirm_password'];

        // Kiểm tra mật khẩu cũ trong DB (giả sử cột mật khẩu là 'mat_khau')
        $check_pass_sql = "SELECT mat_khau FROM nguoi_dung WHERE id = ?";
        $stmt_check = $conn->prepare($check_pass_sql);
        $stmt_check->bind_param("i", $user_id);
        $stmt_check->execute();
        $res = $stmt_check->get_result()->fetch_assoc();

        if ($current_pass == $res['mat_khau']) { // Nếu dùng mã hóa thì dùng password_verify
            if ($new_pass === $confirm_pass) {
                $sql_pass = "UPDATE nguoi_dung SET mat_khau = ? WHERE id = ?";
                $stmt_p = $conn->prepare($sql_pass);
                $stmt_p->bind_param("si", $new_pass, $user_id);
                $stmt_p->execute();
                $message .= " Mật khẩu đã được đổi.";
            } else {
                $message = "Mật khẩu mới không khớp!";
            }
        } else {
            $message = "Mật khẩu hiện tại không đúng!";
        }
    }
}

// 3. LẤY DỮ LIỆU ĐỂ HIỂN THỊ LÊN FORM
$sql_user = "SELECT * FROM nguoi_dung WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_data = $stmt_user->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản</title>
    <link rel="stylesheet" href="taikhoanchitiet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="main-content">
    <?php if($message != ""): ?>
        <div style="padding: 10px; margin-bottom: 20px; background: #d4edda; color: #155724; border-radius: 5px;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="form-row">
            <div class="form-group">
                <label for="name">Họ Tên <span>*</span></label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_data['ho_ten'] ?? ''); ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="username">Tên đăng nhập <span>*</span></label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['ten_dang_nhap'] ?? ''); ?>" required>
            <p class="description">Tên này sẽ hiển thị trong trang Tài khoản và phần Đánh giá sản phẩm</p>
        </div>

        <div class="form-group">
            <label for="email">Địa chỉ email <span>*</span></label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="phone">Số điện thoại <span>*</span></label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user_data['so_dien_thoai'] ?? ''); ?>" required>
        </div>

        <div class="password-change">
            <h4>Thay đổi mật khẩu</h4>
            <div class="form-group password-input-group">
                <label for="current_password">Mật khẩu hiện tại (bỏ trống nếu không đổi)</label>
                <input type="password" id="current_password" name="current_password">
            </div>

            <div class="form-group password-input-group">
                <label for="new_password">Mật khẩu mới (bỏ trống nếu không đổi)</label>
                <input type="password" id="new_password" name="new_password">
            </div>

            <div class="form-group password-input-group">
                <label for="confirm_password">Xác nhận mật khẩu mới</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>
        </div>

        <button type="submit" class="save-button">Lưu thay đổi</button>
    </form>
</div>
</body>
</html>
