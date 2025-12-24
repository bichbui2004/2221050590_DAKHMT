<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bánh kem</title>
    <link rel="stylesheet" href="banhkem.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <main class="product-container">
        <div class="product-top">
            <p>Hiển thị 1-9 của 16 kết quả</p>
            <form method="get" action="index.php">
                <input type="hidden" name="page_layout" value="banhkem">
                <select name="sap-xep" onchange="this.form.submit()">
                    <option value="1" <?php if($_GET['sap-xep'] ?? '' == 1) echo 'selected'; ?>>Mới nhất</option>
                    <option value="2" <?php if($_GET['sap-xep'] ?? '' == 2) echo 'selected'; ?>>Phổ biến nhất</option>
                    <option value="3" <?php if($_GET['sap-xep'] ?? '' == 3) echo 'selected'; ?>>Giá: Cao đến thấp</option>
                    <option value="4" <?php if($_GET['sap-xep'] ?? '' == 4) echo 'selected'; ?>>Giá: Thấp đến cao</option>
                </select>
            </form>
        </div>
        <?php
        $sap_xep = $_GET['sap-xep'] ?? 1;
        switch($sap_xep){
            case 1:
                $order_by = "ORDER BY id DESC";
                break;
            case 2:
                $order_by = "ORDER BY so_luong_ban DESC";
                break;
            case 3:
                $order_by = "ORDER BY gia ASC";
                break;
            case 4:
                $order_by = "ORDER BY gia DESC";
                break;
            default:
                $order_by = "ORDER BY id DESC";
        }
        ?>
        <div class="product-grid">
            <?php 
            include("../admin/connect.php");
            $so_sp_1_trang = 16;
            $trang_hien_tai = isset($_GET['trang']) ? $_GET['trang'] : 1;
            $bat_dau = ($trang_hien_tai - 1) * $so_sp_1_trang;
            $sql = "SELECT * FROM san_pham WHERE id_loai IN (5,6,7,8) $order_by LIMIT $bat_dau, $so_sp_1_trang";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)){
            ?>
            <div class="product-item"><a class="chitiet" href="index.php?page_layout=chitiet&id=<?php echo $row["id"] ?>">
                <img src="../admin/<?php echo $row['hinh_anh']; ?>">
                <h3><?php echo $row['ten_san_pham'];?></h3></a>
                <span class="price"><?php echo $row['gia'] . "đ";?></span> <button style="font-size:15px "><i class="fa fa-shopping-cart"></i></button>
            </div>
            <?php } ?>
        </div>
        <!--Tạo phân trang-->
        <?php
        $sql = "SELECT COUNT(*) AS tong FROM san_pham WHERE id_loai IN (5,6,7,8)";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        $tong_so_trang = ceil($row['tong'] / $so_sp_1_trang);
        ?>

        <div class="pagination">
        <?php for($i = 1; $i <= $tong_so_trang; $i++){ ?>
            <a class="page <?php if($i == $trang_hien_tai) echo 'active'; ?>" href="index.php?page_layout=banhkem&trang=<?php echo $i; ?>&sap-xep=<?php echo $sap_xep; ?>">
            <?php echo $i; ?>
            </a>
        <?php } ?>
        </div>

    </main>
    
</body>
</html>
</script>