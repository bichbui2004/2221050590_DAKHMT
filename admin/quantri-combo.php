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
        <h1>Danh sách combo</h1>
        <div>
            <a class="them" href="index.php?page_layout=themcombo"><button>Thêm combo</button></a>
        </div>
    </div>
    <table>
        <tr>
            <th>Hình ảnh</th>
            <th>Tên combo</th>
            <th width=30%>Mô tả</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th width=25%>Sản phẩm</th>
            <th>Chức năng</th>
        </tr>
        <?php 
            include("connect.php");
            $sql = "SELECT cb.*, GROUP_CONCAT(sp.ten_san_pham) as ds_sanpham FROM combo cb
                    JOIN chi_tiet_combo ctcb ON cb.id = ctcb.id_combo
                    JOIN san_pham sp ON ctcb.id_san_pham = sp.id
                    GROUP BY cb.id";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)){
        ?>  
        <tr>
            <td align="center" class="border">
                <img src="<?php echo $row["hinh_anh"] ?>" width="70px">
            </td>
            <td class="border"><?php echo $row["ten_combo"] ?></td>
            <td class="border"><?php echo $row["mo_ta"] ?></td>
            <td class="border"><?php echo $row["gia_combo"] ?></td>
            <td class="border"><?php echo $row["so_luong"] ?></td>
            <td class="border"><?php echo $row["ds_sanpham"] ?></td>
            <td class="chucnang" align="center">
                <a class="btn-fix" href="index.php?page_layout=capnhatcombo&id=<?php echo $row["id"] ?>">Cập nhật</a>
                <a class="btn-delete" href="xoa/xoacombo.php?id=<?php echo $row['id']; ?>">Xóa</a>
            </td>
        </tr>
        <?php }?>
    </table>
</body>
</html>