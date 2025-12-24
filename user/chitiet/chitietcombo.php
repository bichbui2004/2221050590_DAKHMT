<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Combo</title>
    <style>
        .container-detail {
            width: 80%; margin: 30px auto; font-family: Arial, sans-serif; }
        .detail { display: flex; gap: 50px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .img img { width: 300px; height: 350px; object-fit: cover; border-radius: 10px; box-shadow: 0 8px 20px rgba(0,0,0,0.2); }
        .content h1 { font-size: 32px; color: #f39c12; margin-bottom: 15px; }
        .price { font-size: 24px; color: #e74c3c; font-weight: bold; margin: 10px 0; }
        .status { font-weight: bold; }
        .btn-group { margin-top: 30px; display: flex; gap: 15px; }
        .btn-add-cart { background: #f39c12; color: white; padding: 14px 30px; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; }
        .reviews-section { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom: 25px; }
        .reviews-section h2 { color: #f39c12; border-bottom: 3px solid #f39c12; padding-bottom: 10px; display: inline-block; }
        .avg-rating { background: #f0f8ff; padding: 15px; border-radius: 8px; font-size: 18px; color: #f39c12; font-weight: bold; margin: 20px 0; }
        .review { border-bottom: 1px solid #eee; padding: 18px 0; }
        .stars { color: #f39c12; font-size: 20px; }
        .product-list { display: flex; gap: 10px; flex-wrap: wrap; }
        .banh { padding: 10px; border: 1px solid lightgray; width: 210px; border-radius: 8px; text-align: center; }
        .banh img { width: 100%; height: 200px; object-fit: cover; border-radius: 5px; }
        .star-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 5px; }
        .star-input input { display: none; }
        .star-input label { font-size: 30px; color: #ddd; cursor: pointer; }
        .star-input input:checked ~ label, .star-input label:hover, .star-input label:hover ~ label { color: #f39c12; }
        .write-review textarea {
            width: 100%;
            height: 110px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 15px 0;
            font-family: inherit;
        }

        .btn-submit {
            background: #f39c12;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <?php
        include(__DIR__ . "/../../admin/connect.php");
        
        // Bảo mật ID đầu vào
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        
        // Lấy thông tin Combo
        $sql = "SELECT * FROM combo WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if (!$row) {
            echo "<div class='container-detail'><h2>Combo không tồn tại!</h2></div>";
            exit;
        }

        // Lấy đánh giá trung bình dựa trên id_combo
        $sql_avg = "SELECT AVG(so_sao) as avg_rating, COUNT(*) as total_reviews 
                    FROM danh_gia_san_pham 
                    WHERE id_combo = '$id'";
        $result_avg = mysqli_query($conn, $sql_avg);
        $avg_data = mysqli_fetch_assoc($result_avg);
        $avg_rating = $avg_data['avg_rating'] !== null ? round($avg_data['avg_rating'], 1) : 0;
        $total_reviews = $avg_data['total_reviews'];
    ?>

    <div class="container-detail">
        <div class="detail">
            <div class="img">
                <img src="../admin/<?php echo $row['hinh_anh']; ?>" alt="Combo Image">
            </div>
            <div class="content">
                <h1><?php echo $row['ten_combo'];?></h1>
                <p class="price">Giá: <?php echo number_format($row['gia_combo'], 0, ',', '.');?> đ</p>
                <p><strong>Mô tả:</strong> <?php echo $row['mo_ta'];?></p>
                <p class="status">Còn lại: <span style="color:green;"><?php echo $row['so_luong'];?></span></p>
                
                <div class="btn-group">
                <?php if(isset($_SESSION['username'])): ?>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="type" value="combo">
                        <input type="hidden" name="ten" value="<?php echo $row['ten_combo']; ?>">
                        <input type="hidden" name="gia" value="<?php echo $row['gia_combo']; ?>">
                        <input type="hidden" name="hinh" value="<?php echo $row['hinh_anh']; ?>">
                        <p>
                            Số lượng: <input type="number" name="so_luong" value="1" min="1" max="<?php echo $row['so_luong']; ?>" style="width:70px; padding:5px;">
                        </p>
                        <button type="submit" class="btn-add-cart">
                            <i class="fa fa-shopping-cart"></i> Thêm Combo vào giỏ
                        </button>
                    </form>
                <?php else: ?>
                    <button class="btn-add-cart" onclick="alert('Bạn cần đăng nhập để thực hiện hành động này!');">
                        <i class="fa fa-shopping-cart"></i> Thêm vào giỏ
                    </button>
                <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="reviews-section">
            <h2>Nhận xét Combo</h2>
            <div class="avg-rating">
                Đánh giá trung bình: 
                <?php echo ($total_reviews > 0) ? $avg_rating . "/5 (" . $total_reviews . " đánh giá)" : "Chưa có đánh giá nào"; ?>
            </div>

            <div class="write-review">
                <h3>Đánh giá của bạn</h3>
                <?php if(isset($_SESSION['username'])): ?>
                <form action="submit_review.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                    <input type="hidden" name="type" value="combo">
                    <div class="star-input">
                        <input type="radio" name="rating" id="star5" value="5" required><label for="star5">★</label>
                        <input type="radio" name="rating" id="star4" value="4"><label for="star4">★</label>
                        <input type="radio" name="rating" id="star3" value="3"><label for="star3">★</label>
                        <input type="radio" name="rating" id="star2" value="2"><label for="star2">★</label>
                        <input type="radio" name="rating" id="star1" value="1"><label for="star1">★</label>
                    </div>
                    <textarea name="comment" placeholder="Chia sẻ cảm nhận về Combo này..." required></textarea>
                    <button type="submit" class="btn-submit">Gửi nhận xét</button>
                </form>
                <?php else: ?>
                    <p>Vui lòng <a href="../login.php">đăng nhập</a> để để lại nhận xét.</p>
                <?php endif; ?>
            </div>

            <div class="other-reviews" style="margin-top:30px;">
                <h3>Nhận xét từ khách hàng</h3>
                <?php
                $sql_reviews = "SELECT r.*, nd.ten_dang_nhap FROM danh_gia_san_pham r 
                                JOIN nguoi_dung nd ON r.id_nguoi_dung = nd.id 
                                WHERE r.id_combo = '$id' 
                                ORDER BY r.id DESC";
                $result_reviews = mysqli_query($conn, $sql_reviews);

                if(mysqli_num_rows($result_reviews) > 0){
                    while($review = mysqli_fetch_assoc($result_reviews)){
                        echo '<div class="review">';
                        echo '<p class="user-name">'.htmlspecialchars($review['ten_dang_nhap']).' <small>('.date('d/m/Y', strtotime($review['ngay_danh_gia'])).')</small></p>';
                        echo '<p class="stars">'.str_repeat('★', $review['so_sao']).str_repeat('☆', 5-$review['so_sao']).'</p>';
                        echo '<p>'.htmlspecialchars($review['noi_dung']).'</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Chưa có đánh giá nào cho combo này.</p>';
                }
                ?>
            </div>
        </div>

        <div class="reviews-section">
            <h2>Combo khác dành cho bạn</h2>
            <div class="product-list">
                <?php
                $sql_related = "SELECT * FROM combo WHERE id != '$id' ORDER BY RAND() LIMIT 4";
                $result_related = mysqli_query($conn, $sql_related);
                while ($sp = mysqli_fetch_assoc($result_related)) { ?>
                    <div class="banh">
                        <a href="index.php?page_layout=chitietcombo&id=<?php echo $sp["id"] ?>" style="text-decoration:none; color:inherit;">
                            <img src="../admin/<?php echo $sp['hinh_anh']; ?>" alt="Combo">
                            <div style="margin:10px 0; font-weight:bold;"><?php echo $sp['ten_combo']; ?></div>
                            <div class="price" style="font-size:16px;"><?php echo number_format($sp['gia_combo'], 0, ',', '.'); ?> đ</div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>