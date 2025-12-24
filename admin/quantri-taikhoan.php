<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tài khoản</title>
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
            <h1>Tài khoản</h1>
            <a class="them" href="index.php?page_layout=themtaikhoan"><button>Thêm tài khoản</button></a>
        </div>
        <table class="ds-taikhoan">
            <tr>
              <th>Tên đăng nhập</th>
              <th>Họ tên</th>
              <th>Email</th>
              <th>Số điện thoại</th>
              <th>Vai trò</th>
              <th>Chức năng</th>
            </tr>
            <?php 
              include("connect.php");
              $sql = "SELECT nd.*, q.ten_quyen FROM nguoi_dung nd join quyen q on nd.id_quyen = q.id";
              $result = mysqli_query($conn, $sql);
              while($row = mysqli_fetch_array($result)){
            ?> 
            <tr align="center">
                <td class="border"><?php echo $row["ten_dang_nhap"] ?></td>
                <td class="border"><?php echo $row["ho_ten"] ?></td>
                <td class="border"><?php echo $row["email"] ?></td>
                <td class="border"><?php echo $row["so_dien_thoai"] ?></td>
                <td class="border"><?php echo $row["ten_quyen"] ?></td>
                <td class="hanhDong">
                  <a class="btn-fix" href="index.php?page_layout=capnhattaikhoan&id=<?php echo $row["id"] ?>">Cập nhật</a>
                  <a class="btn-delete" href="xoa/xoataikhoan.php?id=<?php echo $row["id"] ?>">Xóa</a>
                </td>
              </tr>  
              <?php }?>     
        </table>
</body>
</html>