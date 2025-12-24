<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="quantri-taikhoan.css">
    <style>
        table{
        width: 50%;
        border: 1px solid rgb(185, 182, 182);
        margin-left: 30px;
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
            <a class="them" href="index.php?page_layout=themdanhmuc"><button>Thêm danh mục</button></a>
        </div>
    </div>
    <table>
        <tr>
            <th>Id</th>
            <th>Thể loại</th>
            <th>Chức năng</th>
        </tr>
        <?php 
            include("connect.php");
            $sql = "SELECT * FROM loai_banh";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)){
        ?>  
        <tr  align="center">
            <td class="border"><?php echo $row["id"] ?></td>
            <td class="border"><?php echo $row["ten_loai"] ?></td>
            <td class="chucnang" align="center">
                <a class="btn-fix" href="index.php?page_layout=capnhatdanhmuc&id=<?php echo $row["id"] ?>">Cập nhật</a>
                <a class="btn-delete" href="xoa/xoadanhmuc.php?id=<?php echo $row['id']; ?>">Xóa</a>
            </td>
        </tr>
        <?php }?>
    </table>
</body>
</html>