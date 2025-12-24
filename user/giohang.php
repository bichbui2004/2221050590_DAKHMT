<?php

// Nếu chưa có giỏ hàng
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ===== CẬP NHẬT SỐ LƯỢNG =====
if (isset($_POST['update_cart']) && isset($_POST['quantities'])) {
    foreach ($_POST['quantities'] as $product_id => $qty) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['so_luong'] = max(1, (int)$qty);
        }
    }
    header("Location: index.php?page_layout=giohang");
    exit();
}

// ===== XÓA SẢN PHẨM =====
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    header("Location: index.php?page_layout=giohang");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <style>
        .body{
            width: 90%;
            margin: 0 auto;
            min-height: 430px;
        }
        form{
            margin-bottom: 20px;
        }
        table { 
            border-collapse: collapse; 
            width: 100%; 
        }
        th, td { 
            padding: 10px; 
            border-bottom: 1px solid #ddd; 
            text-align: center; 
        }
        th { 
            background: #f2f2f2; 
        }
        img { 
            border-radius: 6px; 
        }
        .btn { 
            padding: 8px 14px; 
            border-radius: 6px; 
            text-decoration: none; 
        }
        .btn-update { 
            background: #3498db; 
            color: white; 
            border: none; 
        }
        .btn-delete { 
            color: red; 
        }
        .btn-buy { 
            background: #27ae60; 
            color: white; 
        }
        .btn-back { 
            background: #bdc3c7; 
            color: black; 
        }
    </style>
</head>
<body>
<div class="body">
<h2 align="center">🛒 Giỏ hàng của bạn</h2>

<?php if (!empty($_SESSION['cart'])): ?>

<form method="post">
    <table>
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Ảnh</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Xóa</th>
            </tr>
        </thead>
        <tbody>
            <?php $tong = 0; ?>
            <?php foreach ($_SESSION['cart'] as $id => $item): 
                $thanh_tien = $item['gia'] * $item['so_luong'];
                $tong += $thanh_tien;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($item['ten']); ?></td>
                <td>
                    <img src="../admin/<?php echo htmlspecialchars($item['hinh']); ?>" width="60">
                </td>
                <td><?php echo number_format($item['gia'], 0, ',', '.'); ?> đ</td>
                <td>
                    <input type="number"
                           name="quantities[<?php echo $id; ?>]"
                           value="<?php echo $item['so_luong']; ?>"
                           min="1"
                           style="width:60px;">
                </td>
                <td><?php echo number_format($thanh_tien, 0, ',', '.'); ?> đ</td>
                <td>
                    <a class="btn-delete"
                       href="index.php?page_layout=giohang&action=delete&id=<?php echo $id; ?>"
                       onclick="return confirm('Xóa sản phẩm này?')">
                        ❌
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Tổng cộng: <span style="color:#e67e22;">
        <?php echo number_format($tong, 0, ',', '.'); ?> đ
    </span></h3>

    <button type="submit" name="update_cart" class="btn btn-update">
        Cập nhật giỏ hàng
    </button>

    <br><br>

    <a href="index.php" class="btn btn-back">← Tiếp tục mua</a>
    <a href="index.php?page_layout=checkout" class="btn btn-buy">Thanh toán</a>
</form>

<?php else: ?>

<p>Giỏ hàng của bạn đang trống.</p>
<a href="index.php" class="btn btn-back">← Quay lại mua hàng</a>

<?php endif; ?>
</div>
</body>
</html>
