<?php
include("connect.php");

$id = $_GET['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST["chon_trang_thai"])) {
        $trangThai = $_POST["chon_trang_thai"];
        $sql_update = "UPDATE `don_hang` SET `id_trang_thai`='$trangThai' WHERE id = '$id'";
        
        if (mysqli_query($conn, $sql_update)) {
            mysqli_close($conn);
            header('location: index.php?page_layout=donhang');
        }
    }
}

$sql_select = "SELECT dh.*,tt.*, nd.ho_ten, dc.dia_chi FROM don_hang dh 
               JOIN nguoi_dung nd ON dh.id_nguoi_dung = nd.id 
               JOIN dia_chi_giao_hang dc ON dh.id_dia_chi = dc.id
               JOIN trang_thai tt ON dh.id_trang_thai = tt.id
               WHERE dh.id = '$id'";
$result = mysqli_query($conn, $sql_select);
$row = mysqli_fetch_array($result);

$sql_sp = "SELECT ct.*, sp.ten_san_pham, sp.hinh_anh AS sp_hinh_anh , cb.ten_combo, cb.hinh_anh AS cb_hinh_anh
           FROM chi_tiet_don_hang ct
           LEFT JOIN san_pham sp ON ct.id_san_pham = sp.id 
           LEFT JOIN combo cb ON ct.id_combo = cb.id
           WHERE ct.id_don_hang = '$id'"; 
$query_sp = mysqli_query($conn, $sql_sp);
if (!$row) {
    echo "Đơn hàng không tồn tại!";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Chi tiết đơn hàng #<?php echo $id; ?></title>
    <style>
        .khung-lon { 
            display: flex; 
            padding: 20px; 
            background-color: #fff; 
            border-radius: 8px; }
        .cot-trai { 
            width: 45%; 
            padding-right: 30px; 
            border-right: 1px solid #ddd; }
        .cot-phai { 
            width: 55%; 
            padding-left: 30px; }
        .dong { 
            margin-bottom: 15px; 
            border-bottom: 1px solid #f9f9f9; 
            padding-bottom: 10px; }
        .nhan { 
            font-weight: bold; 
            width: 140px; 
            display: inline-block; 
            color: #555; }
        .badge { 
            background: #007bff; 
            color: #fff; 
            padding: 4px 12px; 
            border-radius: 4px; 
            font-size: 14px; }
        .item{ 
            display: flex; 
            align-items: center; 
            margin-bottom: 12px; 
            border-bottom: 1px solid #eee; 
            padding-bottom: 8px; }
        .anh { 
            width: 50px; 
            height: 50px; 
            object-fit: cover;
            margin-right: 15px; 
            border: 1px solid #ddd;  }
        select { 
            padding: 8px; 
            border-radius: 4px;
            border: 1px solid #ccc; 
            width: 180px; }
        .btn-luu {
            background-color: #28a745;
            color: white;
            padding: 10px 25px;
            border-radius: 4px;
            font-size: 14px;
            margin-top: 15px;}
    </style>
</head>
<body style="background-color: #f4f4f4; margin: 0;">
<div style="padding: 20px; font-family: Arial;">
    <h2 style="margin-bottom: 25px; display: flex; align-content: center; gap: 10px;">
        Chi tiết đơn #<?php echo $id; ?> 
        <span class="badge"><?php echo $row['ten_trang_thai']; ?></span>
    </h2>

    <div class="khung-lon">
        <div class="cot-trai">
            <div class="dong"><span class="nhan">Người mua:</span> <?php echo $row['ho_ten']; ?></div> 
            <div class="dong"><span class="nhan">Ngày đặt:</span> <?php echo $row['ngay_dat']; ?></div>
            <div class="dong"><span class="nhan">Tổng tiền:</span> <b style="color:red;"><?php echo number_format($row['tong_tien'], 0, ',', '.'); ?>đ</b></div>
            
            <form method="POST">
                <div class="dong" style="border:none;">
                    <span class="nhan">Cập nhật trạng thái:</span>
                    <select name="chon_trang_thai">
                        <?php
                        $sql_all_tt = "SELECT * FROM trang_thai";
                        $query_all_tt = mysqli_query($conn, $sql_all_tt);
                        while($row_tt = mysqli_fetch_array($query_all_tt)) {
                            $check = ($row_tt['tt.id'] == $row['id_trang_thai']) ? "selected" : "";
                            echo "<option value='".$row_tt['id']."' $check>".$row_tt['ten_trang_thai']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="btn_luu" class="btn-luu">Lưu và Quay lại</button>
            </form>
        </div>

        <div class="cot-phai">
            <div style="display: flex; justify-content: space-between; font-weight: bold; border-bottom: 2px solid #5d5fef; padding-bottom: 8px; margin-bottom: 15px;">
                <span>Sản phẩm đã chọn</span>
                <span>Số lượng</span>
            </div>

            <?php while($sp = mysqli_fetch_array($query_sp)) { 
            if (!empty($sp['id_combo'])) {
                $tenHienThi = "[COMBO] " . $sp['ten_combo'];
                $anhHienThi = $sp['cb_hinh_anh']; 
            } else {
                $tenHienThi = $sp['ten_san_pham'];
                $anhHienThi = $sp['sp_hinh_anh']; 
            }
            ?>
                <div class="item">
                    <img src="<?php echo $anhHienThi; ?>" class="anh">
                   <div style="flex: 1; font-size: 14px;"><?php echo $tenHienThi; ?></div>
                    <div style="font-weight: bold; color: #5d5fef;"><?php echo $sp['so_luong']; ?></div>
            </div>
        <?php } ?>
        </div>
    </div>
</div>

</body>
</html>