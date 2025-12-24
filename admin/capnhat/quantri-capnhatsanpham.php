<?php
include("connect.php");
$id = $_GET['id'];

if (!empty($_POST["ten-san-pham"]) &&
    !empty($_POST["gia"]) &&
    !empty($_POST["so-luong"]) &&
    !empty($_POST["danh-muc"])) {

    $tenSanPham = $_POST["ten-san-pham"];
    $moTa = isset($_POST["mo-ta"]) ? $_POST["mo-ta"] : "";
    $gia = $_POST["gia"];
    $soLuong = $_POST["so-luong"];
    $idLoai = $_POST["danh-muc"];

    // Lấy ảnh cũ
    $sqlOld = "SELECT hinh_anh FROM san_pham WHERE id = '$id'";
    $resultOld = mysqli_query($conn, $sqlOld);
    $oldData = mysqli_fetch_assoc($resultOld);
    $oldImage = $oldData["hinh_anh"];

    // Nếu KHÔNG chọn ảnh mới → giữ ảnh cũ
    if ($_FILES["fileToUpload"]["error"] == 4) {
        $target_file = $oldImage;
    }
    else {
        // Có chọn ảnh mới → xử lý upload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra file là ảnh
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check === false) {
            echo "File không phải ảnh.";
            $uploadOk = 0;
        }

        // Kiểm tra kích thước
        if ($_FILES["fileToUpload"]["size"] > 10000000) {
            echo "File quá lớn";
            $uploadOk = 0;
        }

        // Chỉ cho phép JPG, PNG, GIF
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" &&
            $imageFileType != "png" && $imageFileType != "gif") {
            echo "Chỉ cho phép file JPG, PNG, GIF.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        } else {
            $target_file = $oldImage;
        }
    }

    // Update dữ liệu
    $sql = "UPDATE san_pham SET ten_san_pham='$tenSanPham', hinh_anh='$target_file', mo_ta='$moTa', gia='$gia', so_luong='$soLuong', id_loai='$idLoai'
            WHERE id = '$id'";

    mysqli_query($conn, $sql);
    header('Location: index.php?page_layout=sanpham');
    exit();
}
else {
    echo '<p class="warning">Vui lòng nhập đầy đủ thông tin</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>php - Buổi 2</title>
    <style>
        .warning{
            margin-left: 100px;
        }
        h1{
            margin: 5px 0px;
        }
        .container{
            border: 1px solid black;
            border-radius: 10px;
            width: 35%;
            padding: 20px 0;
            display: flex;
            justify-content: center;
            margin-top: 20px;
            margin-left: 100px;
        }
        form{
            width:80%;
            
        }
        input{
            width:100%;
            padding: 5px 0;

        }
        .box{
            width:100%;
            margin: 10px 0;
        }
        .select{
            width: 100%;
            padding: 5px 0;
        }
        .warning{
            color: red;
            font-weight: bold;

        } 
    </style>
</head>
<body>
    <?php
        include("../admin/connect.php"); 
        $id = $_GET['id'];
        $sql = "SELECT sp.*, lb.ten_loai FROM san_pham sp JOIN loai_banh lb ON sp.id_loai = lb.id WHERE sp.id = '$id'";
        $result = mysqli_query($conn,$sql);
        $sanPham = mysqli_fetch_assoc($result);
    ?>
    <div class="container">
    <form action="index.php?page_layout=capnhatsanpham&id=<?php echo $id?>" method="post" enctype="multipart/form-data">   
        <h1>Cập nhật sản phẩm</h1>
        <div class="box">
            <p>Sản phẩm</p>
            <input type="text" name="ten-san-pham" placeholder="Tên sản phẩm" value="<?php echo $sanPham['ten_san_pham']?>">
        </div>
        <div class="box">
            <p>Danh mục</p>
            <select name="danh-muc">
                <?php 
                    include("connect.php");
                    $sql = "SELECT * FROM loai_banh";
                    $result = mysqli_query($conn, $sql);
                    while($danhMuc = mysqli_fetch_array($result)){
                ?> 
                    <option value="<?php echo $danhMuc['id'] ?>"<?php if ($danhMuc['id']==$sanPham['id_loai']) echo "selected" ?>><?php echo $danhMuc['ten_loai'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="box">
            <p>Hình ảnh</p>
            <input type="file" name="fileToUpload" placeholder="Hình ảnh"  value="<?php echo $sanpham['hinh_anh']?>">
        </div>
        <div class="box">
            <p>Giá</p>
            <input type="number" name="gia" placeholder="Giá bán"  value="<?php echo $sanPham['gia']?>">
        </div>
        <div class="box">
            <p>Số lượngr</p>
            <input type="number" name="so-luong" placeholder="Số lượng"  value="<?php echo $sanPham['so_luong']?>">
        <div class="box">
            <p>Mô tả</p>
            <textarea name="mo-ta" placeholder="Mô tả"><?php echo $sanPham['mo_ta']; ?></textarea>
        </div>
        <div class="box"><input type="submit" value="Cập nhật"></div>

    </form>
    <div>
</body>
</html>