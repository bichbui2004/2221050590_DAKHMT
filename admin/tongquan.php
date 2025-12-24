<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tổng quan</title>
    <style>
        .content {
            flex: 1;
            padding: 30px;
            background-color: white;
        }

        .page-title {
            margin-bottom: 30px;
            color: #333;
        }

      
        .stats-row {
            display: flex; 
            gap: 100px;
            margin-bottom: 30px;
        }

        .card {
            flex: 1; 
            border: 1px solid #ddd; 
            padding: 20px;
            border-radius: 5px; 
            text-align: center;
        }

        .card h3 {
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: normal;
        }

        .card .number {
            font-size: 32px;
            font-weight: bold;
        }
    </style>
</head>
<body>
        <?php
        include("connect.php");
        $sql_tk = "SELECT COUNT(*) FROM nguoi_dung";
        $result_tk = mysqli_query($conn, $sql_tk);
        $row_tk = mysqli_fetch_row($result_tk)[0];

        $sql_dm = "SELECT COUNT(*) FROM loai_banh";
        $result_dm = mysqli_query($conn, $sql_dm);
        $row_dm = mysqli_fetch_row($result_dm)[0];

        $sql_sp = "SELECT COUNT(*) FROM san_pham";
        $result_sp = mysqli_query($conn, $sql_sp);
        $row_sp = mysqli_fetch_row($result_sp)[0];

        $sql_dh = "SELECT COUNT(*) FROM don_hang";
        $result_dh = mysqli_query($conn, $sql_dh);
        $row_dh = mysqli_fetch_row($result_dh)[0];

        $sql_blog = "SELECT COUNT(*) FROM blog";
        $result_blog = mysqli_query($conn, $sql_blog);
        $row_blog = mysqli_fetch_row($result_blog)[0];

        $sql_cb = "SELECT COUNT(*) FROM combo";
        $result_cb = mysqli_query($conn, $sql_cb);
        $row_cb = mysqli_fetch_row($result_cb)[0];
    ?>
        <div class="content">
            <h2 class="page-title">Tổng quan</h2>

            <div class="stats-row">
                <div class="card">
                    <h3><a href="index.php?page_layout=taikhoan">Tài khoản</a></h3>
                    <div class="number"><?php echo $row_tk?></div>
                </div>

                <div class="card">
                    <h3><a href="index.php?page_layout=danhmuc">Danh mục</a></h3>
                    <div class="number"><?php echo $row_dm?></div>
                </div>

                <div class="card">
                    <h3><a href="index.php?page_layout=sanpham">Sản phẩm</a></h3>
                    <div class="number"><?php echo $row_sp?></div>
                </div>
            </div>

            <div class="stats-row">
                <div class="card">
                    <h3><a href="index.php?page_layout=donhang">Đơn hàng</a></h3>
                    <div class="number red"><?php echo $row_dh?></div>
                </div>

                <div class="card">
                    <h3><a href="index.php?page_layout=blog">Blog</a></h3>
                    <div class="number"><?php echo $row_blog?></div>
                </div>

                <div class="card">
                    <h3><a href="index.php?page_layout=combo">Combo</a></h3>
                    <div class="number"><?php echo $row_cb?></div>
                </div>
            </div>
        </div>
</body>
</html>