<?php
include("connect.php");

// 1. Lấy ID từ URL
$id = $_GET['id'];

// 2. Xử lý cập nhật TRƯỚC khi in HTML
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST["trang-thai"])) {
        $trangThai = $_POST["trang-thai"];
        $sql_update = "UPDATE `don_hang` SET `id_trang_thai`='$trangThai' WHERE id = '$id'";
        
        if (mysqli_query($conn, $sql_update)) {
            mysqli_close($conn);
            // Chuyển hướng về danh sách
            header('location: index.php?page_layout=donhang');
            exit(); 
        }
    }
}

// 3. Lấy thông tin đơn hàng cụ thể để hiển thị vào form
$sql_select = "SELECT dh.*, nd.ho_ten, dc.dia_chi FROM don_hang dh 
               JOIN nguoi_dung nd ON dh.id_nguoi_dung = nd.id 
               JOIN dia_chi_giao_hang dc ON dh.id_dia_chi = dc.id
               WHERE dh.id = '$id'";
$result = mysqli_query($conn, $sql_select);
$row = mysqli_fetch_array($result);

// Nếu không tìm thấy đơn hàng
if (!$row) {
    echo "Đơn hàng không tồn tại!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật đơn hàng</title>
    <style>
        .container {
            border: 1px solid #ddd;
            border-radius: 10px;
            width: fit-content;
            padding: 50px;
            margin: 50px auto;
            font-family: Arial, sans-serif;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 { font-size: 20px; text-align: center; margin-bottom: 20px; color: #333; }
        .box { margin-bottom: 15px; }
        .box p { margin: 0 0 5px 0; font-weight: bold; color: #555; }
        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box; /* Giúp input không bị tràn khung */
        }
        input[readonly] { background-color: #f9f9f9; color: #777; }
        input[type="submit"] {
            background-color: #f39c12;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }
        input[type="submit"]:hover { background-color: #e67e22; }
    </style>
</head>
<body>

<div class="container">
    <form action="" method="post">   
        <h1>Cập nhật đơn hàng</h1>
        
        <div class="box">
            <p>Mã đơn hàng</p>
            <input type="text" value="<?php echo $row['id']; ?>" readonly>
        </div>
        
        <div class="box">
            <p>Khách hàng</p>
            <input type="text" value="<?php echo $row['ho_ten']; ?>" readonly>
        </div>
        
        <div class="box">
            <p>Tổng tiền</p>
            <input type="text" value="<?php echo number_format($row['tong_tien'], 0, ',', '.'); ?>đ" readonly>
        </div>
        
        <div class="box">
            <p>Ngày đặt</p>
            <input type="text" value="<?php echo $row['ngay_dat']; ?>" readonly>
        </div>
        
        <div class="box">
            <p>Địa chỉ</p>
            <input type="text" value="<?php echo $row['dia_chi']; ?>" readonly>
        </div>

        <div class="box">
            <p>Trạng thái</p>
            <select name="trang-thai">
                <option value="1" <?php echo ($row['id_trang_thai'] == 1) ? "selected" : ""; ?>>Đang xử lý</option>
                <option value="2" <?php echo ($row['id_trang_thai'] == 2) ? "selected" : ""; ?>>Đang giao</option>
                <option value="3" <?php echo ($row['id_trang_thai'] == 3) ? "selected" : ""; ?>>Hoàn thành</option>
                <option value="4" <?php echo ($row['id_trang_thai'] == 4) ? "selected" : ""; ?>>Hủy</option>
            </select>
        </div>
        
        <div class="box">
            <input type="submit" value="Xác nhận cập nhật">
        </div>
    </form>
</div>

</body>
</html>