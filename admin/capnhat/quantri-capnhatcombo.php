        <?php
            include("connect.php");
            $id = $_GET['id'];
            if(!empty($_POST["ten-combo"])&&
                !empty($_POST["mo-ta"])&&
                !empty($_POST["gia"])&&
                !empty($_POST["so-luong"])&&
                !empty($_POST["ds-san-pham"])&&
                !empty($_POST["so-luong-sp"])){

                    $tenCombo = $_POST["ten-combo"];
                    $moTa = $_POST["mo-ta"]; 
                    $gia = $_POST["gia"];
                    $soLuong = $_POST["so-luong"];
                    $sanPham = $_POST["ds-san-pham"];
                    $soLuongSP = $_POST['so-luong-sp'];
    // Lấy ảnh cũ
    $sqlOld = "SELECT hinh_anh FROM combo WHERE id = '$id'";
    $resultOld = mysqli_query($conn, $sqlOld);
    $oldData = mysqli_fetch_assoc($resultOld);
    $oldImage = $oldData["hinh_anh"];

    // Nếu KHÔNG chọn ảnh mới → giữ ảnh cũ
    if ($_FILES["fileToUpload"]["error"] == 4) {
        $target_file = $oldImage;
    }
    else {
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
        if($uploadOk == 1) {
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        } else {
            echo "File chưa được tải lên, sử dụng ảnh cũ.";
            $target_file = $oldData["hinh_anh"];
        }
    }

            // Cập nhật combo
            $sql = "UPDATE `combo` SET 
                        `ten_combo`='$tenCombo',
                        `mo_ta`='$moTa',
                        `gia_combo`='$gia',
                        `hinh_anh`='$target_file',
                        `so_luong`='$soLuong'
                    WHERE id = '$id'";
            mysqli_query($conn, $sql);

            // Cập nhật chi tiết combo
            mysqli_query($conn, "DELETE FROM chi_tiet_combo WHERE id_combo=$id");
            foreach ($sanPham as $id_sp) {
                $sl = $soLuongSP[$id_sp] ?? 1;
                mysqli_query($conn, "INSERT INTO chi_tiet_combo(id_combo, id_san_pham, so_luong) VALUES ($id, $id_sp, $sl)");
            }

            header('location: index.php?page_layout=combo');
            exit();

        } else {
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
        $sql = "SELECT * FROM combo WHERE id = $id";
        $combo = mysqli_fetch_assoc(mysqli_query($conn, $sql));

        $sqlCT = "SELECT * FROM chi_tiet_combo WHERE id_combo = $id";
        $resultCT = mysqli_query($conn, $sqlCT);
        $dsCT = [];
        while ($row = mysqli_fetch_assoc($resultCT)) {
            $dsCT[$row['id_san_pham']] = $row['so_luong'];
        }
    ?>
    <div class="container">
    <form action="index.php?page_layout=capnhatcombo&id=<?php echo $id?>" method="post" enctype="multipart/form-data">   
        <h1>Cập nhật sản phẩm</h1>
        <div class="box">
            <p>Combo</p>
            <input type="text" name="ten-combo" placeholder="Tên combo" value="<?php echo $combo['ten_combo']?>">
        </div>
        <div class="box">
            <label>Sản phẩm trong combo</label>
            <?php
            $sql = "SELECT * FROM san_pham WHERE id_loai NOT IN (4,5,6,7,8)";;
            $result = mysqli_query($conn, $sql);
            while ($sp = mysqli_fetch_assoc($result)) {
                $checked = isset($dsCT[$sp['id']]) ? "checked" : "";
                $sl = $dsCT[$sp['id']] ?? 1;
            ?>
                <div class="sp-item">
                    <input type="checkbox" name="ds-san-pham[]" value="<?= $sp['id'] ?>" <?= $checked ?>>
                    <?php echo $sp['ten_san_pham'] ?>
                    <input type="number" name="so-luong-sp[<?= $sp['id'] ?>]" value="<?php echo $sl ?>" min="1" style="width:80px;">
                </div>
            <?php } ?>
        </div>
        <div class="box">
            <p>Hình ảnh</p>
            <input type="file" name="fileToUpload" placeholder="Hình ảnh" value="<?php echo $combo['hinh_anh']?>">
        </div>
        <div class="box">
            <p>Giá</p>
            <input type="number" name="gia" placeholder="Giá bán" value="<?php echo $combo['gia_combo']?>">
        </div>
        <div class="box">
            <p>Số lượngr</p>
            <input type="number" name="so-luong" placeholder="Số lượng" value="<?php echo $combo['so_luong']?>">
        <div class="box">
            <p>Mô tả</p>
            <textarea name="mo-ta" placeholder="Mô tả"><?php echo $combo['mo_ta']?></textarea>
        </div>
        <div class="box"><input type="submit" value="Cập nhật"></div>

    </form>
    <div>
</body>
</html>