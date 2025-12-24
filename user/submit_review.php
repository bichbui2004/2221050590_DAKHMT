<?php
session_start();
include "../admin/connect.php"; 

// 1. Kiểm tra đăng nhập
if(!isset($_SESSION['username']) || !isset($_SESSION['user_id'])){
    header("Location: ../dangnhap.php");
    exit;
}

// 2. Kiểm tra dữ liệu POST
if(isset($_POST['product_id'], $_POST['rating'], $_POST['comment'])){
    
    $user_id = $_SESSION['user_id'];
    $item_id = (int) $_POST['product_id']; // Đây là ID của sản phẩm hoặc combo
    $rating = (int) $_POST['rating'];
    $comment = htmlspecialchars(trim($_POST['comment'])); 
    $type = $_POST['type'] ?? 'san_pham'; // Nhận loại từ form ẩn

    // Kiểm tra dữ liệu hợp lệ cơ bản
    if($rating < 1 || $rating > 5 || empty($comment)){
        echo "<script>alert('Vui lòng nhập đầy đủ nội dung và chọn số sao!'); window.history.back();</script>";
        exit;
    }

    // 3. Chuẩn bị câu lệnh SQL dựa trên loại (Sản phẩm hoặc Combo)
    if ($type === 'combo') {
        // Nếu là combo, lưu vào cột id_combo, cột id_san_pham để NULL
        $sql = "INSERT INTO danh_gia_san_pham (id_nguoi_dung, id_san_pham, so_sao, noi_dung, ngay_danh_gia, id_combo) 
                VALUES (?, NULL, ?, ?, NOW(), ?)";
    } else {
        // Nếu là sản phẩm, lưu vào cột id_san_pham, cột id_combo để NULL
        $sql = "INSERT INTO danh_gia_san_pham (id_nguoi_dung, id_san_pham, so_sao, noi_dung, ngay_danh_gia, id_combo) 
                VALUES (?, ?, ?, ?, NOW(), NULL)";
    }

    $stmt = $conn->prepare($sql);

    if($stmt){
        // Gán tham số dựa theo loại
        if ($type === 'combo') {
            // "iisi" -> user_id (int), rating (int), comment (string), item_id (int cho combo)
            $stmt->bind_param("iisi", $user_id, $rating, $comment, $item_id);
        } else {
            // "iiis" -> user_id (int), item_id (int cho sản phẩm), rating (int), comment (string)
            $stmt->bind_param("iiis", $user_id, $item_id, $rating, $comment);
        }

        if ($stmt->execute()) {
            // Thành công: Quay về đúng trang chi tiết
            if ($type === 'combo') {
                header("Location: index.php?page_layout=chitietcombo&id=$item_id");
            } else {
                header("Location: index.php?page_layout=chitiet&id=$item_id");
            }
            exit;
        } else {
            echo "Lỗi khi thực thi: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Lỗi chuẩn bị câu lệnh: " . $conn->error;
    }

} else {
    header("Location: index.php"); 
    exit;
}
?>