<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <style>
        .container-detail {
            width: 80%;
            margin: 30px auto;
            font-family: Arial, Helvetica, sans-serif;
        }

        .detail {
            display: flex;
            gap: 50px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .img img {
            width: 300px;
            height: 350px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .content h1 {
            font-size: 32px;
            color: #f39c12;
            margin-bottom: 15px;
        }

        .content p {
            font-size: 16px;
            margin: 10px 0;
            color: #444;
        }

        .status { font-weight: bold; }

        .btn-group {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }

        .btn-add-cart {
            background: #f39c12;
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-favorite {
            background: white;
            color: #f39c12;
            border: 2px solid #f39c12;
            padding: 14px 25px;
            border-radius: 8px;
            cursor: pointer;
        }

        .description, .reviews-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .description h2, .reviews-section h2 {
            color: #f39c12;
            border-bottom: 3px solid #f39c12;
            padding-bottom: 10px;
            display: inline-block;
        }

        .avg-rating {
            background: #f0f8ff;
            padding: 15px;
            border-radius: 8px;
            font-size: 18px;
            color: #f39c12;
            font-weight: bold;
            margin: 20px 0;
        }

        .review {
            border-bottom: 1px solid #eee;
            padding: 18px 0;
        }

        .review:last-child { border-bottom: none; }

        .user-name {
            font-weight: bold;
            color: #333;
        }

        .stars {
            color: #f39c12;
            font-size: 20px;
            margin: 8px 0;
        }

        .review p {
            margin: 10px 0;
            line-height: 1.6;
        }

        .review small {
            color: #999;
        }

        .write-review {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px dashed #ddd;
        }

        .write-review h3 {
            color: #f39c12;
        }

        .star-input label {
            font-size: 28px;
            color: #ddd;
            cursor: pointer;
        }

        .star-input label:hover, .star-input label:hover ~ label {
            color: #f39c12;
        }

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
        .product-list{
            display: flex;
            gap: 10px;
        }
        .banh{
            padding: 5px;
            border: 1px solid lightgray;
        }
        .banh img{
            width: 210px;
            height: 250px;
            object-fit: cover;
        }
        .gia-gio{
            margin-top: 5px;
        }
        .star-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 5px; }
        .star-input input { display: none; }
        .star-input label { font-size: 30px; color: #ddd; cursor: pointer; }
        .star-input input:checked ~ label, .star-input label:hover, .star-input label:hover ~ label { color: #f39c12; }
        
    </style>
</head>
<body>
    <?php
        include(__DIR__ . "/../../admin/connect.php");
        $id = $_GET['id'];
        $sql = "SELECT sp.*, lb.ten_loai as ten_danh_muc FROM `san_pham` sp JOIN loai_banh lb ON sp.id_loai = lb.id WHERE sp.id = '$id'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
    ?>
    <div class="container-detail">
        <div class="detail">
            <div class="img">
                <img src="../admin/<?php echo $row['hinh_anh']; ?>">
            </div>
            <div class="content">
                <h1><?php echo $row['ten_san_pham'];?></h1>
                <p>Danh mục: <?php echo $row['ten_danh_muc'];?></p>
                <p>Giá: <?php echo $row['gia'];?></p>
                <p>Mô tả: <?php echo $row['mo_ta'];?></p>
                <p class="status">Còn lại: <span style="color:green;"><?php echo $row['so_luong'];?></span></p>
            <div class="btn-group">
            <?php
            if(isset($_SESSION['username'])) { 
            ?>
                <form action="add_to_cart.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="type" value="san_pham">
                    <input type="hidden" name="ten" value="<?php echo $row['ten_san_pham']; ?>">
                    <input type="hidden" name="gia" value="<?php echo $row['gia']; ?>">
                    <input type="hidden" name="hinh" value="<?php echo $row['hinh_anh']; ?>">
                    <p>
                        Số lượng:<input type="number" name="so_luong" value="1" min="1" max="<?php echo $row['so_luong']; ?>" style="width:70px; padding:5px; margin-left:10px;">
                    </p>
                    <button type="submit" class="btn-add-cart">
                        <i class="fa fa-shopping-cart"></i> Thêm vào giỏ
                    </button>
                </form>
            <?php
            } else { 
            ?>
                <button class="btn-add-cart" onclick="alert('Bạn cần đăng nhập để thực hiện hành động này!');">
                    <i class="fa fa-shopping-cart"></i> Thêm vào giỏ
                </button>
            <?php
            }
            ?>
            </div>

            </div>
        </div>
        <?php
        // Lấy đánh giá trung bình và tổng số đánh giá
        $product_id = $row['id'];
        $sql_avg = "SELECT AVG(so_sao) as avg_rating, COUNT(*) as total_reviews 
                    FROM danh_gia_san_pham 
                    WHERE id_san_pham = '$id'";
        $result_avg = mysqli_query($conn, $sql_avg);
        $avg_data = mysqli_fetch_assoc($result_avg);

        $avg_rating = $avg_data['avg_rating'] !== null ? round($avg_data['avg_rating'], 1) : 0;
        $total_reviews = $avg_data['total_reviews'];
        ?>

        <!-- CẢM NGHĨ  -->
        <div class="reviews-section">
            <h2>Nhận xét </h2>
            <div class="avg-rating">
                Đánh giá trung bình: 
                <?php 
                if($total_reviews > 0){
                    echo $avg_rating . "/5 (" . $total_reviews . " đánh giá)";
                } else {
                    echo "Chưa có đánh giá nào";
                }
                ?>
            </div>


            <!-- Form đánh giá -->
        <div class="write-review">
            <h3>Đánh giá sản phẩm</h3>
            <?php if(isset($_SESSION['username'])): ?>
            <form action="submit_review.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <div class="star-input">
                    <input type="radio" name="rating" id="star5" value="5" required><label for="star5">★</label>
                    <input type="radio" name="rating" id="star4" value="4"><label for="star4">★</label>
                    <input type="radio" name="rating" id="star3" value="3"><label for="star3">★</label>
                    <input type="radio" name="rating" id="star2" value="2"><label for="star2">★</label>
                    <input type="radio" name="rating" id="star1" value="1"><label for="star1">★</label>
                </div>
                <textarea name="comment" placeholder="Chia sẻ cảm nhận của bạn..." required></textarea>
                <button type="submit" class="btn-submit">Gửi</button>
            </form>
            <?php else: ?>
                <p>Vui lòng <a href="../login.php">đăng nhập</a> để đánh giá sản phẩm.</p>
            <?php endif; ?>
        </div>

        <!-- Đánh giá của người dùng khác -->
        <div class="write-review">
            <h3>Đánh giá của người dùng khác</h3>
            <?php
            $sql_reviews = "SELECT r.*, nd.ten_dang_nhap FROM danh_gia_san_pham r 
                            JOIN nguoi_dung nd ON r.id_nguoi_dung = nd.id 
                            WHERE r.id_san_pham = '$product_id' 
                            ORDER BY r.id DESC";
            $result_reviews = mysqli_query($conn, $sql_reviews);

            if(mysqli_num_rows($result_reviews) > 0){
                while($review = mysqli_fetch_assoc($result_reviews)){
                    echo '<div class="review">';
                    echo '<p class="user-name">'.htmlspecialchars($review['ten_dang_nhap']).' <small>('.date('d/m/Y H:i', strtotime($review['ngay_danh_gia'])).')</small></p>';
                    echo '<p class="stars">'.str_repeat('★', $review['so_sao']).str_repeat('☆', 5-$review['so_sao']).'</p>';
                    echo '<p>'.htmlspecialchars($review['noi_dung']).'</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>Chưa có đánh giá nào.</p>';
            }
            ?>
        </div>
    </div>
        <!--Sản phẩm tương tự-->
        <?php
        $id_sp   = $row['id'];
        $id_loai = $row['id_loai'];

        $sql = "SELECT * FROM san_pham
                WHERE id_loai = $id_loai
                AND id != $id_sp
                ORDER BY RAND() LIMIT 5";

        $result = mysqli_query($conn, $sql);
        ?>

        <div class="reviews-section">
            <h2>Sản phẩm tương tự</h2>

            <div class="product-list">
                <?php while ($sp = mysqli_fetch_assoc($result)) { ?>
                    <div class="banh"><a class="chitiet" href="index.php?page_layout=chitiet&id=<?php echo $sp["id"] ?>">
                        <div><img src="../admin/<?php echo $sp['hinh_anh']; ?>"></div>
                        <div><?php echo $sp['ten_san_pham']; ?></div></a>
                        <div class="gia-gio">
                            <span class="gia"><?php echo $sp['gia'] ."đ"; ?></span>  <button style="font-size:15px "><i class="fa fa-shopping-cart"></i></button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>