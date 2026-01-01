<?php
include("../admin/connect.php");

//load giỏ hàng từ DB
$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id > 0) {
    $sql_cart = "SELECT gh.id, ct.id_san_pham, ct.id_combo, ct.so_luong, ct.gia,
                        sp.ten_san_pham, sp.hinh_anh,
                        cb.ten_combo, cb.hinh_anh AS hinh_combo
                 FROM gio_hang gh
                 JOIN chi_tiet_gio_hang ct ON gh.id = ct.id_gio_hang
                 LEFT JOIN san_pham sp ON ct.id_san_pham = sp.id
                 LEFT JOIN combo cb ON ct.id_combo = cb.id
                 WHERE gh.id_nguoi_dung = ?";
    $stmt = $conn->prepare($sql_cart);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $_SESSION['cart'] = [];
    while ($row = $result->fetch_assoc()) {
        $type = $row['id_combo'] ? 'combo' : 'san_pham';
        $id   = $row['id_combo'] ?: $row['id_san_pham'];
        $cart_key = $type . "_" . $id;

        // lấy tên và hình đúng theo loại
        if ($type === 'combo') {
            $ten  = $row['ten_combo'];
            $hinh = $row['hinh_combo'];
        } else {
            $ten  = $row['ten_san_pham'];
            $hinh = $row['hinh_anh'];
        }

        $_SESSION['cart'][$cart_key] = [
            'id'       => $id,
            'type'     => $type,
            'ten'      => $ten,
            'gia'      => $row['gia'],
            'hinh'     => $hinh,
            'so_luong' => $row['so_luong']
        ];
    }
}
// Nếu chưa có giỏ hàng trong session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
//CẬP NHẬT SỐ LƯỢNG
if (isset($_POST['update_cart']) && isset($_POST['quantities'])) {
    foreach ($_POST['quantities'] as $product_id => $qty) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['so_luong'] = max(1, (int)$qty);

            // CẬP NHẬT DB
            if ($user_id > 0) {
                $sql_cart = "SELECT id FROM gio_hang WHERE id_nguoi_dung = ? LIMIT 1";
                $stmt = $conn->prepare($sql_cart);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $row = $stmt->get_result()->fetch_assoc();
                if ($row) {
                    $id_gio_hang = $row['id'];
                    $sql_update = "UPDATE chi_tiet_gio_hang 
                                   SET so_luong = ? 
                                   WHERE id_gio_hang = ? 
                                   AND (id_san_pham = ? OR id_combo = ?)";
                    $stmt_up = $conn->prepare($sql_update);
                    $id_san_pham = ($_SESSION['cart'][$product_id]['type'] === 'san_pham') ? $_SESSION['cart'][$product_id]['id'] : null;
                    $id_combo    = ($_SESSION['cart'][$product_id]['type'] === 'combo') ? $_SESSION['cart'][$product_id]['id'] : null;
                    $stmt_up->bind_param("iiii", $_SESSION['cart'][$product_id]['so_luong'], $id_gio_hang, $id_san_pham, $id_combo);
                    $stmt_up->execute();
                }
            }
        }
    }
    header("Location: index.php?page_layout=giohang");
    exit();
}

// XÓA SẢN PHẨM 
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id']) && isset($_GET['type'])) {
    $id = (int)$_GET['id'];
    $type = $_GET['type'];
    $cart_key = $type . "_" . $id;

    if (isset($_SESSION['cart'][$cart_key])) {
        unset($_SESSION['cart'][$cart_key]);

        //  XÓA TRONG DB 
        if ($user_id > 0) {
            $sql_cart = "SELECT id FROM gio_hang WHERE id_nguoi_dung = ? LIMIT 1";
            $stmt = $conn->prepare($sql_cart);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if ($row) {
                $id_gio_hang = $row['id'];
                $sql_del = "DELETE FROM chi_tiet_gio_hang 
                            WHERE id_gio_hang = ? 
                            AND (id_san_pham = ? OR id_combo = ?)";
                $stmt_del = $conn->prepare($sql_del);
                $id_san_pham = ($type === 'san_pham') ? $id : null;
                $id_combo    = ($type === 'combo') ? $id : null;
                $stmt_del->bind_param("iii", $id_gio_hang, $id_san_pham, $id_combo);
                $stmt_del->execute();
            }
        }
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
                <td><img src="../admin/<?php echo htmlspecialchars($item['hinh']); ?>" width="60"></td>
                <td><?php echo number_format($item['gia'],0,',','.'); ?> đ</td>
                <td><input type="number" name="quantities[<?php echo $id; ?>]" value="<?php echo $item['so_luong']; ?>" min="1" style="width:60px;"></td>
                <td><?php echo number_format($thanh_tien,0,',','.'); ?> đ</td>
                <td>
                    <a class="btn-delete"
                       href="index.php?page_layout=giohang&action=delete&id=<?php echo $item['id']; ?>&type=<?php echo $item['type']; ?>"
                       onclick="return confirm('Xóa sản phẩm <?php echo addslashes($item['ten']); ?> khỏi giỏ hàng?')">❌</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Tổng cộng: <span style="color:#e67e22;"><?php echo number_format($tong,0,',','.'); ?> đ</span></h3>

    <button type="submit" name="update_cart" class="btn btn-update">Cập nhật giỏ hàng</button>
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
