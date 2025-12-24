<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body{
            background-color: rgb(218, 228, 237);
            width: 100%;
            height: 100vh;
            margin: 0px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        form{
            width:fit-content;
            padding: 50px;
            background-color: white;
            margin: auto;
            border-radius: 10px;
            border: 1px solid white;

        }
        .chinh{
            display:flex;
            gap: 30px;
            margin-bottom: 20px;
        }
        .phu{
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        a{
            color: black;
        }
        .warning{
            color: red;
            display: flex;
            justify-content: center;
        }

    </style>
</head>
<body>
    <form action="dangnhap.php" method="post">
        <div class="chinh">
            <div class="anh">
                <img src="Screenshot 2025-09-22 124457.png" width="250px">
            </div>
            <div class="thongtin">
                <h1>Log in</h1>
                <div style="border-bottom: 1px solid black; display: flex; align-items: center;">
                    <div><i class="material-icons">person</i></div>
                    <div><input style="border: none;" type="text" name="username" placeholder="User name"></div>
                </div>
                <div style="margin-top: 15px; display: flex; align-items: center; border-bottom: 1px solid black;">
                    <div><i class="fa fa-lock"></i></div>
                    <div><input style="border:none" type="password" name="password" placeholder=" Password"></div>
                </div>
                <div style="margin-top: 20px;">
                    <input style="padding: 10px 30px; color:white; border-radius: 5px; background-color: rgb(124, 124, 206); border: 1px solid gray;" type="submit" value="Log in">
                </div>
            </div>
        </div>
        <div style="width: 30%; margin: auto">
            <u><a href="/DO_AN_KHMT/dangki.php">Create an account</a></u>
        </div>
    <?php 
        session_start();
        include('admin/connect.php');
        if(isset($_POST['username']) && isset($_POST['password'])){
        $tenDangNhap = $_POST['username']; 
        $matKhau = $_POST['password'];  
        $sql = "SELECT * from nguoi_dung nd where ten_dang_nhap = '$tenDangNhap' and mat_khau = '$matKhau'"; 
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result)>0){
            $nguoiDung = mysqli_fetch_assoc($result);
            $_SESSION["username"] = $nguoiDung["ten_dang_nhap"];
            $_SESSION["id_quyen"] = $nguoiDung["id_quyen"];
            $_SESSION['user_id'] = $nguoiDung['id'];
            if ($_SESSION["id_quyen"] == 2) {
                header('location: admin/index.php');
            } else {
                    header('location: user/index.php');
                }
                exit(); 
            }
        else{
            echo "<p class='warning'>Sai thong tin dang nhap</p>";
        }
    }
        
    ?>      
    </form>

</body>
</html>