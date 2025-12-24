        <?php
            include("connect.php");
            if(!empty($_POST["ten-san-pham"])&&
                !empty($_POST["gia"])&&
                !empty($_POST["so-luong"])&&
                !empty($_POST["danh-muc"])){

                    $tenSanPham = $_POST["ten-san-pham"];
                    $moTa = isset($_POST["mo-ta"]) ? $_POST["mo-ta"] : "";
                    $gia = $_POST["gia"];
                    $soLuong = $_POST["so-luong"];
                    $idLoai = $_POST["danh-muc"];

                    #Bắt đầu xử lý thêm ảnh
        // Xử lý ảnh
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra xem file ảnh có hợp lệ không
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File không phải là ảnh.";
                $uploadOk = 0;
            }
        }

        // Kiểm tra nếu file đã tồn tại
        if (file_exists($target_file)) {
            echo "File này đã tồn tại trên hệ thông";
            $uploadOk = 2;
        }

        // Kiểm tra kích thước file
        if ($_FILES["fileToUpload"]["size"] > 10000000) {
            echo "File quá lớn";
            $uploadOk = 0;
        }

        // Cho phép các định dạng file ảnh nhất định
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Chỉ những file JPG, JPEG, PNG & GIF mới được chấp nhận.";
            $uploadOk = 0;
        }
        
        #Kết thúc xử lý ảnh
        if($uploadOk == 0){
            echo "File của bạn chưa được tải lên";
        }
        else{
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                //Đoạn code xử lý login ban đầu
                 $sql = "INSERT INTO `san_pham`( `ten_san_pham`, `hinh_anh`, `mo_ta`, `gia`, `so_luong`, `id_loai`) VALUES ('$tenSanPham','$target_file','$moTa','$gia','$soLuong','$idLoai')";
                    mysqli_query($conn,$sql);
                    $sanpham_id = mysqli_insert_id($conn);
                    header('location: index.php?page_layout=sanpham');
            }
            
        }  
                }else{
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
    <div class="container">
    <form action="index.php?page_layout=themsanpham" method="post" enctype="multipart/form-data">   
        <h1>Thêm sản phẩm</h1>
        <div class="box">
            <p>Sản phẩm</p>
            <input type="text" name="ten-san-pham" placeholder="Tên sản phẩm">
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
                    <option value="<?php echo $danhMuc['id'] ?>"><?php echo $danhMuc['ten_loai'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="box">
            <p>Hình ảnh</p>
            <input type="file" name="fileToUpload" placeholder="Hình ảnh">
        </div>
        <div class="box">
            <p>Giá</p>
            <input type="number" name="gia" placeholder="Giá bán">
        </div>
        <div class="box">
            <p>Số lượngr</p>
            <input type="number" name="so-luong" placeholder="Số lượng">
        <div class="box">
            <p>Mô tả</p>
            <textarea name="mo-ta" placeholder="Mô tả"></textarea>
        </div>
        <div class="box"><input type="submit" value="Thêm"></div>

    </form>
    <div>
</body>
</html>