CREATE DATABASE IF NOT EXISTS cua_hang_banh_ngot;
USE cua_hang_banh_ngot;
-- Bảng phân quyền
CREATE TABLE IF NOT EXISTS quyen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_quyen VARCHAR(50) NOT NULL
);
INSERT INTO quyen(ten_quyen) VALUES ('Khách hàng'), ('Admin');
-- Bảng loại bánh
CREATE TABLE IF NOT EXISTS loai_banh (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_loai VARCHAR(100) NOT NULL
);
INSERT INTO loai_banh(ten_loai) VALUES ('Bông lan'),('Bánh lanh'),('Bánh mì'),('Cookie'),('Bánh cưới'),('Bánh khai trương'),('Bánh kỉ niệm'),('Bánh sinh nhật');
-- Bảng sản phẩm
CREATE TABLE IF NOT EXISTS san_pham (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_san_pham VARCHAR(200) NOT NULL,
    hinh_anh varchar(255) NOT NULL,
    mo_ta TEXT,
    gia int NOT NULL,
    so_luong INT DEFAULT 0,
    id_loai INT,
    FOREIGN KEY (id_loai) REFERENCES loai_banh(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Bảng người dùng
CREATE TABLE IF NOT EXISTS nguoi_dung (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mat_khau VARCHAR(255),
    ten_dang_nhap varchar(50),
    id_quyen int,
    ho_ten VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    so_dien_thoai VARCHAR(20),
    FOREIGN KEY (id_quyen) REFERENCES quyen(id)
);

-- Bảng địa chỉ giao hàng
CREATE TABLE IF NOT EXISTS dia_chi_giao_hang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_nguoi_dung INT,
    dia_chi VARCHAR(255),
    phuong_xa VARCHAR(100),
    quan_huyen VARCHAR(100),
    tinh_thanh VARCHAR(100),
    mac_dinh BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_nguoi_dung) REFERENCES nguoi_dung(id) ON DELETE CASCADE ON UPDATE CASCADE
);
-- Bảng giỏ hàng
CREATE TABLE IF NOT EXISTS gio_hang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_nguoi_dung INT UNIQUE,
    tong_tien int DEFAULT 0,
    FOREIGN KEY (id_nguoi_dung) REFERENCES nguoi_dung(id) ON DELETE CASCADE ON UPDATE CASCADE
);
-- Bảng combo
CREATE TABLE IF NOT EXISTS combo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_combo VARCHAR(200) NOT NULL,
    mo_ta TEXT,
    gia_combo int NOT NULL,
    hinh_anh VARCHAR(255)
);
-- bảng chi tiết combo
CREATE TABLE IF NOT EXISTS chi_tiet_combo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_combo INT,
    id_san_pham INT,
    so_luong INT NOT NULL DEFAULT 1,
    FOREIGN KEY (id_combo) REFERENCES combo(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_san_pham) REFERENCES san_pham(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Bảng chi tiết giỏ hàng
CREATE TABLE IF NOT EXISTS chi_tiet_gio_hang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_gio_hang INT,
    id_san_pham INT,
    id_combo int,
    so_luong INT NOT NULL DEFAULT 1,
    gia int NOT NULL,
    FOREIGN KEY (id_gio_hang) REFERENCES gio_hang(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_san_pham) REFERENCES san_pham(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_combo) REFERENCES combo(id) ON DELETE CASCADE ON UPDATE CASCADE
);


-- Bảng trạng thái: Đang xử lý / Đang giao / Hoàn thành / Hủy
CREATE TABLE IF NOT EXISTS trang_thai (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_trang_thai VARCHAR(50)
);
INSERT INTO trang_thai(ten_trang_thai) VALUES ('Đang xử lý'), ('Đang giao'), ('Hoàn thành'), ('Hủy');
-- Bảng đơn hàng
CREATE TABLE IF NOT EXISTS don_hang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_nguoi_dung INT,
    tong_tien int,
    id_trang_thai int,  
    ngay_dat DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_dia_chi INT,
    FOREIGN KEY (id_nguoi_dung) REFERENCES nguoi_dung(id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (id_dia_chi) REFERENCES dia_chi_giao_hang(id) ON UPDATE CASCADE,
    FOREIGN KEY (id_trang_thai) REFERENCES trang_thai(id) ON UPDATE CASCADE
);

-- Chi tiết đơn hàng
CREATE TABLE IF NOT EXISTS chi_tiet_don_hang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_don_hang INT,
    id_san_pham INT,
    id_combo INT,
    so_luong INT,
    gia int,
    FOREIGN KEY (id_don_hang) REFERENCES don_hang(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_san_pham) REFERENCES san_pham(id),
    FOREIGN KEY (id_combo) REFERENCES combo(id)
);

-- Thanh toán
CREATE TABLE IF NOT EXISTS thanh_toan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_don_hang INT,
    phuong_thuc VARCHAR(50),  -- COD, VNPay
    so_tien int,
    trang_thai VARCHAR(50),  -- Thành công / Chưa thanh toán
    FOREIGN KEY (id_don_hang) REFERENCES don_hang(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Đánh giá sản phẩm
CREATE TABLE IF NOT EXISTS danh_gia_san_pham (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_nguoi_dung INT,
    id_san_pham INT,
    so_sao INT CHECK (so_sao BETWEEN 1 AND 5),
    noi_dung TEXT,
    ngay_danh_gia DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_nguoi_dung) REFERENCES nguoi_dung(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_san_pham) REFERENCES san_pham(id) ON DELETE CASCADE ON UPDATE CASCADE
);




