<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <style>
        body{
            background-color: rgba(220, 208, 173, 1);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        form{
            width:25%;
            background-color: white;
            margin: 0 auto;
            border-radius: 10px;
            padding: 20px;
        }
        .title{
            margin-top: 10px;
            border-bottom: 1px solid lightgray;
        }
        
        .thongtin{
            margin-top: 10px;
        }
        .box{
            display: flex;
            justify-content: space-around;
            margin: 5px;
            gap: 3px;
        }
        .box input{
            border-radius: 5px;
            border: 1px solid gray;
            padding: 5px;
            width: 100%;
        }
        .submit{
            margin-top: 10px;
        }
        .submit input{
            color:white;
            background-color: rgba(234, 124, 28, 1);
            font-weight: bold; 
            padding: 3px 35px; 
            border-radius: 5px; 
            border:1px solid rgba(241, 219, 219, 1);
        }
        .other{
            padding: 10px;
        }
        .warning{
            color: red;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <form action="dangki.php" method="post">
        <div align="center" class="title"> 
            <p style="font-size: 18px; font-weight: bold; padding: 3px; margin: 0px;">Tạo tài khoản mới</p>
            <p style="padding: 0px; margin: 0px;margin-bottom: 15px;">Nhanh chóng và dễ dàng</p>
        </div>
        <div class="thongtin">
            <div class="box">
                <input type="text" name="ho-ten" placeholder="Họ tên">
            </div>
            <div class="box">
                <input type="email" name="email" placeholder="Email">
            </div>
            <div class="box">
                <input type="text" name="so-dien-thoai" placeholder="Số điện thoại">
            </div>
            <div class="box">
                <input type="text" name="ten-dang-nhap" placeholder="Tên đăng nhập">
            </div>
            <div class="box">
                <input type="password" name="password" placeholder="Mật khẩu">
            </div>
        </div>
        <div class="submit" align=center>
            <input type="submit" value="Đăng kí">
        </div>
        <div align="center" class="other">
            <font  color="blue"><a href="/DO_AN_KHMT/dangnhap.php">Đã có tài khoản</a></font>
        </div>
        <?php
            include("admin/connect.php");
            if(!empty($_POST["ten-dang-nhap"])&&
                !empty($_POST["password"])&&
                !empty($_POST["ho-ten"])&&
                !empty($_POST["email"])&&
                !empty($_POST["so-dien-thoai"])){

                    $tenDangNhap = $_POST["ten-dang-nhap"];
                    $password = $_POST["password"];
                    $hoTen = $_POST["ho-ten"];
                    $email = $_POST["email"];
                    $soDienThoai = $_POST["so-dien-thoai"];

                    $sql = "SELECT id FROM nguoi_dung WHERE ten_dang_nhap = '$tenDangNhap'";
                    $result = mysqli_query($conn, $sql);

                    if(mysqli_num_rows($result) > 0){
                        echo '<p class="warning">Tài khoản này đã tồn tại</p>';
                    }else{

                    $sql = "INSERT INTO `nguoi_dung`(`mat_khau`, `ten_dang_nhap`, `id_quyen`,  `ho_ten`, `email`, `so_dien_thoai`) VALUES ('$password', '$tenDangNhap','1','$hoTen','$email','$soDienThoai')";
                    mysqli_query($conn,$sql);
                    mysqli_close($conn);
                    header('location: dangnhap.php');}
                }else{
                    echo '<p class="warning">Vui lòng nhập đầy đủ thông tin</p>';
                }
        ?>       
    </form>
</body>
</html>