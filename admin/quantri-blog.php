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
        <h1>Danh sách bài viết</h1>
        <div>
            <a class="them" href="index.php?page_layout=themblog"><button>Thêm bài viết</button></a>
        </div>
    </div>
    <table>
        <tr>
            <th width=15%>Hình ảnh</th>
            <th>Tiêu đề</th>
            <th width=60%>Nội dung</th>
            <th width=12%>Chức năng</th>
        </tr>
        <?php 
            include("connect.php");
            $sql = "SELECT * FROM blog";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)){
        ?>  
        <tr>
            <td align="center" class="border">
                <img src="<?php echo $row["hinh_anh"] ?>" width="150px">
            </td>
            <td class="border"><?php echo $row["tieu_de"] ?></td>
            <td class="border"><?php echo $row["noi_dung"] ?></td>
            <td class="chucnang" align="center">
                <a class="btn-fix" href="index.php?page_layout=capnhatblog&id=<?php echo $row["id"] ?>">Cập nhật</a>
                <a class="btn-delete" href="xoa/xoablog.php?id=<?php echo $row['id']; ?>">Xóa</a>
            </td>
        </tr>
        <?php }?>
    </table>
</body>
</html>