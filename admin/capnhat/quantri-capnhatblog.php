<?php
include("connect.php");
$id = $_GET['id'];

if (!empty($_POST["tieu-de"]) &&
    !empty($_POST["noi-dung"])) {

    $tieuDe = $_POST["tieu-de"];
    $noiDung = $_POST["noi-dung"];

    // Lấy ảnh cũ
    $sqlOld = "SELECT hinh_anh FROM blog WHERE id = '$id'";
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
    $sql = "UPDATE blog SET tieu_de='$tieuDe', hinh_anh='$target_file', noi_dung='$noiDung' WHERE id = '$id'";

    mysqli_query($conn, $sql);
    header('Location: index.php?page_layout=blog');
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
        $sql = "SELECT * FROM blog WHERE id = '$id'";
        $result = mysqli_query($conn,$sql);
        $baiViet = mysqli_fetch_assoc($result);
    ?>
    <div class="container">
    <form action="index.php?page_layout=capnhatblog&id=<?php echo $id?>" method="post" enctype="multipart/form-data">   
        <h1>Cập nhật bài viết</h1>
        <div class="box">
            <p>Tiêu đề</p>
            <input type="text" name="tieu-de" placeholder="Tiêu đề" value="<?php echo $baiViet['tieu_de']?>">
        </div>
        <div class="box">
            <p>Hình ảnh</p>
            <input type="file" name="fileToUpload" placeholder="Hình ảnh"  value="<?php echo $baiViet['hinh_anh']?>">
        </div>
        <div class="box">
            <p>Nội dung</p>
            <textarea name="noi-dung" placeholder="Nội dung"><?php echo $baiViet['noi_dung']; ?></textarea>
        </div>
        <div class="box"><input type="submit" value="Cập nhật"></div>

    </form>
    <div>
</body>
</html>