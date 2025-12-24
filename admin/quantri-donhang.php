<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="quantri-taikhoan.css">
    <style>
        table{
        width: 95%;
        border: 1px solid rgb(185, 182, 182);
        margin: auto;
        }
        th{
        font-size: 20px;
        padding-bottom: 5px;
        border-right: 1px solid rgb(185, 182, 182);
        }
        td{
        padding: 10px;
        }
        .border{
            border-right: 1px solid rgb(185, 182, 182);
        }
        .btn-fix{
        border-radius: 5px;
        background-color: orange;
        color: black;
        padding: 5px 8px;   
        border: none; 
        font-weight:bold;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        }
        .btn-delete{
        border-radius: 5px;
        background-color: red;
        color: white;
        padding: 5px 8px;    
        border: none;
        font-weight:bold;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Danh sách đơn hàng</h1>
    </div>
    <table>
        <tr>
            <th>Mã đơn hàng</th>
            <th>Tên khách hàng</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Ngày đặt</th>
            <th>Địa chỉ</th>
            <th>Chức năng</th>
        </tr>
        <?php 
            include("connect.php");
             $sql = "SELECT dh.id AS ma_don_hang, nd.ho_ten, dh.tong_tien, 
                   tt.ten_trang_thai, dh.ngay_dat, 
                   dc.dia_chi, dh.id_trang_thai
             FROM don_hang dh JOIN nguoi_dung nd ON dh.id_nguoi_dung = nd.id 
             JOIN trang_thai tt ON dh.id_trang_thai = tt.id 
             JOIN dia_chi_giao_hang dc ON dh.id_dia_chi = dc.id
             ORDER BY ngay_dat DESC";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)){
        ?>  
        <tr>
            <td align="center" class="border"><?php echo $row["ma_don_hang"] ?></td>
            <td align="center" class="border"><?php echo $row["ho_ten"] ?></td>
            <td align="center" class="border"><?php echo $row["tong_tien"] ?></td>
            <td align="center" class="border"><?php echo $row["ten_trang_thai"] ?></td>
            <td align="center" class="border"><?php echo $row["ngay_dat"] ?></td>
            <td align="center" class="border"><?php echo $row["dia_chi"] ?></td>
            <td class="chucnang" align="center">
                <a class="btn-fix" href="index.php?page_layout=capnhatdonhang&id=<?php echo $row["ma_don_hang"] ?>">Cập nhật</a>
                <a class="btn-delete" href="xoa/xoadonhang.php?id=<?php echo $row['ma_don_hang']; ?>">Xóa</a>
            </td>
        </tr>
        <?php }?>
    </table>
</body>
</html>