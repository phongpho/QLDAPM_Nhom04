SET NAMES utf8mb4;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+00:00';
SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS gymshop;
CREATE DATABASE gymshop DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gymshop;

-- =====================================================================
-- 1. NGƯỜI DÙNG
-- =====================================================================

CREATE TABLE nguoidung (
  id          INT NOT NULL AUTO_INCREMENT,
  gmail       VARCHAR(100) DEFAULT NULL,
  google_id   VARCHAR(50)  DEFAULT NULL,
  sodienthoai VARCHAR(10)  NOT NULL,
  matkhau     VARCHAR(255) NOT NULL COMMENT 'Lưu bằng password_hash()',
  hoten       VARCHAR(255) NOT NULL,
  loai        TINYINT NOT NULL DEFAULT 3 COMMENT '1 Admin, 2 Nhân viên, 3 Khách',
  trangthai   TINYINT NOT NULL DEFAULT 1 COMMENT '1 hoạt động, 0 bị khóa',
  hinhanh     VARCHAR(255) DEFAULT NULL,
  ngaytao     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_nguoidung_gmail (gmail),
  UNIQUE KEY uq_nguoidung_google (google_id),
  UNIQUE KEY uq_nguoidung_sdt (sodienthoai)
) ENGINE=InnoDB;

-- Địa chỉ giao hàng
CREATE TABLE diachi (
  id           INT NOT NULL AUTO_INCREMENT,
  nguoidung_id INT NOT NULL,
  tennguoinhan VARCHAR(100) NOT NULL,
  sodienthoai  VARCHAR(10)  NOT NULL,
  diachi       VARCHAR(255) NOT NULL,
  macdinh      TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_diachi_nguoidung (nguoidung_id),
  CONSTRAINT fk_diachi_nguoidung FOREIGN KEY (nguoidung_id) REFERENCES nguoidung (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Hồ sơ thể trạng: mỗi người dùng 1 dòng
CREATE TABLE hosothetrang (
  id           INT NOT NULL AUTO_INCREMENT,
  nguoidung_id INT NOT NULL,
  chieucao     DECIMAL(5,1) NOT NULL COMMENT 'cm',
  cannang      DECIMAL(5,1) NOT NULL COMMENT 'kg',
  gioitinh     ENUM('nam','nu') NOT NULL,
  tuoi         INT NOT NULL,
  muctieu      ENUM('giamcan','tangcan','duytri') NOT NULL,
  bmi          DECIMAL(4,1) NOT NULL COMMENT 'PHP tính: cannang / (chieucao/100)^2',
  ngaycapnhat  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_hoso_nguoidung (nguoidung_id),
  CONSTRAINT fk_hoso_nguoidung FOREIGN KEY (nguoidung_id) REFERENCES nguoidung (id) ON DELETE CASCADE,
  CONSTRAINT ck_hoso_chieucao CHECK (chieucao BETWEEN 100 AND 250),
  CONSTRAINT ck_hoso_cannang  CHECK (cannang  BETWEEN 25 AND 300)
) ENGINE=InnoDB;

-- =====================================================================
-- 2. SẢN PHẨM & DANH MỤC & THƯƠNG HIỆU
-- =====================================================================

CREATE TABLE danhmuc (
  id         INT NOT NULL AUTO_INCREMENT,
  tendanhmuc VARCHAR(50) NOT NULL,
  loai       ENUM('quanao','phukien','tpbs') NOT NULL COMMENT 'Nhóm lớn để hiển thị menu',
  trangthai  TINYINT NOT NULL DEFAULT 1 COMMENT '1 hiển thị, 0 ẩn',
  PRIMARY KEY (id),
  UNIQUE KEY uq_danhmuc_ten (tendanhmuc),
  KEY idx_danhmuc_loai_tt (loai, trangthai)
) ENGINE=InnoDB;

CREATE TABLE thuonghieu (
  id            INT NOT NULL AUTO_INCREMENT,
  tenthuonghieu VARCHAR(80) NOT NULL,
  logo          VARCHAR(255) DEFAULT NULL,
  trangthai     TINYINT NOT NULL DEFAULT 1 COMMENT '1 hiển thị, 0 ẩn',
  PRIMARY KEY (id),
  UNIQUE KEY uq_thuonghieu_ten (tenthuonghieu),
  KEY idx_thuonghieu_tt (trangthai)
) ENGINE=InnoDB;

CREATE TABLE sanpham (
  id            INT NOT NULL AUTO_INCREMENT,
  tensanpham    VARCHAR(255) NOT NULL,
  mota          TEXT DEFAULT NULL,
  giagoc        DECIMAL(12,0) NOT NULL DEFAULT 0 COMMENT 'Giá niêm yết',
  giaban        DECIMAL(12,0) NOT NULL DEFAULT 0 COMMENT 'Giá bán hiện tại',
  soluongton    INT NOT NULL DEFAULT 0,
  hinhanh       VARCHAR(255) DEFAULT NULL,
  danhmuc_id    INT NOT NULL,
  thuonghieu_id INT DEFAULT NULL,
  doituong      ENUM('nam','nu','unisex') NOT NULL DEFAULT 'unisex',
  sizes         VARCHAR(30) DEFAULT NULL COMMENT 'Ví dụ "S,M,L,XL", NULL = không size',
  mausac        VARCHAR(100) DEFAULT NULL,
  chatlieu      VARCHAR(100) DEFAULT NULL,
  thanhphan     TEXT DEFAULT NULL,
  muctieu       SET('giamcan','tangcan','duytri') NOT NULL DEFAULT 'giamcan,tangcan,duytri',
  hansudung     DATE DEFAULT NULL COMMENT 'Dành cho TPBS',
  noibat        TINYINT NOT NULL DEFAULT 0,
  trangthai     TINYINT NOT NULL DEFAULT 1 COMMENT '1 đang bán, 0 ẩn (không xóa cứng vì đơn cũ tham chiếu)',
  luotxem       INT NOT NULL DEFAULT 0,
  luotmua       INT NOT NULL DEFAULT 0,
  ngaytao       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_sanpham_danhmuc (danhmuc_id),
  KEY idx_sanpham_thuonghieu (thuonghieu_id),
  KEY idx_sanpham_hienthi (trangthai, noibat),
  FULLTEXT KEY ft_sanpham_tim (tensanpham, mota),
  CONSTRAINT fk_sanpham_danhmuc FOREIGN KEY (danhmuc_id) REFERENCES danhmuc (id),
  CONSTRAINT fk_sanpham_thuonghieu FOREIGN KEY (thuonghieu_id) REFERENCES thuonghieu (id) ON DELETE SET NULL,
  CONSTRAINT ck_sanpham_gia CHECK (giaban >= 0 AND giagoc >= 0)
) ENGINE=InnoDB;

-- Bảng size gợi ý thể trạng
CREATE TABLE bangsize (
  id           INT NOT NULL AUTO_INCREMENT,
  gioitinh     ENUM('nam','nu') NOT NULL,
  size         VARCHAR(5) NOT NULL,
  thutu        TINYINT NOT NULL COMMENT 'S=1, M=2, L=3, XL=4',
  chieucao_min DECIMAL(5,1) NOT NULL,
  chieucao_max DECIMAL(5,1) NOT NULL,
  cannang_min  DECIMAL(5,1) NOT NULL,
  cannang_max  DECIMAL(5,1) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_bangsize (gioitinh, size)
) ENGINE=InnoDB;

-- =====================================================================
-- 3. GIỎ HÀNG - VOUCHER - ĐƠN HÀNG
-- =====================================================================

CREATE TABLE giohang (
  id           INT NOT NULL AUTO_INCREMENT,
  nguoidung_id INT NOT NULL,
  sanpham_id   INT NOT NULL,
  size         VARCHAR(5) NOT NULL DEFAULT '',
  soluong      INT NOT NULL DEFAULT 1,
  ngaythem     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_giohang_item (nguoidung_id, sanpham_id, size),
  KEY idx_giohang_sanpham (sanpham_id),
  CONSTRAINT fk_giohang_nguoidung FOREIGN KEY (nguoidung_id) REFERENCES nguoidung (id) ON DELETE CASCADE,
  CONSTRAINT fk_giohang_sanpham   FOREIGN KEY (sanpham_id)   REFERENCES sanpham (id)   ON DELETE CASCADE,
  CONSTRAINT ck_giohang_sl CHECK (soluong > 0)
) ENGINE=InnoDB;

CREATE TABLE voucher (
  id               INT NOT NULL AUTO_INCREMENT,
  ma               VARCHAR(30) NOT NULL,
  mota             VARCHAR(255) DEFAULT NULL,
  loai             ENUM('phantram','sotien') NOT NULL,
  giatri           DECIMAL(12,0) NOT NULL COMMENT '% hoặc số tiền VND',
  donhang_toithieu DECIMAL(12,0) NOT NULL DEFAULT 0,
  giamtoida        DECIMAL(12,0) DEFAULT NULL,
  ngayhethan       DATETIME NOT NULL,
  soluong          INT NOT NULL DEFAULT 0,
  dasudung         INT NOT NULL DEFAULT 0,
  trangthai        TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uq_voucher_ma (ma)
) ENGINE=InnoDB;

CREATE TABLE donhang (
  id            INT NOT NULL AUTO_INCREMENT,
  nguoidung_id  INT NOT NULL,
  tennguoinhan  VARCHAR(100) NOT NULL,
  sdtnhan       VARCHAR(10)  NOT NULL,
  diachinhan    VARCHAR(255) NOT NULL,
  ngay          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  trangthai     TINYINT NOT NULL DEFAULT 1 COMMENT '1 Mới, 2 Xác nhận, 3 Đóng gói, 4 Đang giao, 5 Hoàn thành, 6 Hủy',
  phuongthuc_tt ENUM('cod','chuyenkhoan','vnpay') NOT NULL DEFAULT 'cod',
  datt          TINYINT NOT NULL DEFAULT 0 COMMENT '1 = đã thanh toán',
  voucher_id    INT DEFAULT NULL,
  tamtinh       DECIMAL(12,0) NOT NULL DEFAULT 0,
  giamgia       DECIMAL(12,0) NOT NULL DEFAULT 0,
  phivanchuyen  DECIMAL(12,0) NOT NULL DEFAULT 0,
  tongtien      DECIMAL(12,0) NOT NULL DEFAULT 0,
  doitacgiaohang VARCHAR(255),
  madonhang     VARCHAR(255),
  ghichu        VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_donhang_nguoidung (nguoidung_id),
  KEY idx_donhang_ngay (ngay),
  CONSTRAINT fk_donhang_nguoidung FOREIGN KEY (nguoidung_id) REFERENCES nguoidung (id),
  CONSTRAINT fk_donhang_voucher   FOREIGN KEY (voucher_id)   REFERENCES voucher (id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE donhangct (
  id         INT NOT NULL AUTO_INCREMENT,
  donhang_id INT NOT NULL,
  sanpham_id INT NOT NULL,
  size       VARCHAR(5) NOT NULL DEFAULT '',
  dongia     DECIMAL(12,0) NOT NULL DEFAULT 0,
  soluong    INT NOT NULL DEFAULT 1,
  thanhtien  DECIMAL(12,0) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_dhct_donhang (donhang_id),
  KEY idx_dhct_sanpham (sanpham_id),
  CONSTRAINT fk_dhct_donhang FOREIGN KEY (donhang_id) REFERENCES donhang (id) ON DELETE CASCADE,
  CONSTRAINT fk_dhct_sanpham FOREIGN KEY (sanpham_id) REFERENCES sanpham (id),
  CONSTRAINT ck_dhct_sl CHECK (soluong > 0)
) ENGINE=InnoDB;

-- =====================================================================
-- 4. TƯƠNG TÁC & NỘI DUNG
-- =====================================================================

CREATE TABLE yeuthich (
  nguoidung_id INT NOT NULL,
  sanpham_id   INT NOT NULL,
  ngay         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (nguoidung_id, sanpham_id),
  CONSTRAINT fk_yt_nguoidung FOREIGN KEY (nguoidung_id) REFERENCES nguoidung (id) ON DELETE CASCADE,
  CONSTRAINT fk_yt_sanpham   FOREIGN KEY (sanpham_id)   REFERENCES sanpham (id)   ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE danhgia (
  id           INT NOT NULL AUTO_INCREMENT,
  nguoidung_id INT NOT NULL,
  sanpham_id   INT NOT NULL,
  sao          TINYINT NOT NULL,
  noidung      TEXT DEFAULT NULL,
  trangthai    TINYINT NOT NULL DEFAULT 1 COMMENT '1 hiển thị, 0 ẩn',
  ngay         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_danhgia (nguoidung_id, sanpham_id),
  KEY idx_danhgia_sanpham (sanpham_id),
  CONSTRAINT fk_danhgia_nguoidung FOREIGN KEY (nguoidung_id) REFERENCES nguoidung (id) ON DELETE CASCADE,
  CONSTRAINT fk_danhgia_sanpham   FOREIGN KEY (sanpham_id)   REFERENCES sanpham (id)   ON DELETE CASCADE,
  CONSTRAINT ck_danhgia_sao CHECK (sao BETWEEN 1 AND 5)
) ENGINE=InnoDB;

CREATE TABLE tintuc (
  id        INT NOT NULL AUTO_INCREMENT,
  tieude    VARCHAR(255) NOT NULL,
  noidung   MEDIUMTEXT NOT NULL,
  hinhanh   VARCHAR(255) DEFAULT NULL,
  loai      ENUM('tintuc','sukien','khuyenmai') NOT NULL DEFAULT 'tintuc',
  trangthai TINYINT NOT NULL DEFAULT 1,
  ngaydang  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE cauhinh_bmi (
  id INT NOT NULL AUTO_INCREMENT,
  phanloai VARCHAR(50) NOT NULL COMMENT 'Gầy, Chuẩn, Thừa cân, Béo phì',
  bmi_min DECIMAL(4,1) NOT NULL,
  bmi_max DECIMAL(4,1) NOT NULL,
  khuyenghe TEXT DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- DỮ LIỆU MẪU
-- =====================================================================

-- Mật khẩu mặc định: "password"
INSERT INTO nguoidung (gmail, sodienthoai, matkhau, hoten, loai, trangthai) VALUES
('admin@gymshop.vn', '0900000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quản trị viên', 1, 1),
('nhanvien@gymshop.vn', '0900000002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nhân viên bán hàng', 2, 1),
('an.nguyen@gmail.com', '0900000003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyễn Văn An', 3, 1);

INSERT INTO danhmuc (tendanhmuc, loai, trangthai) VALUES
('Áo tập Gym', 'quanao', 1),
('Quần tập Gym', 'quanao', 1),
('Găng tay & Đai lưng', 'phukien', 1),
('Bình lắc & Phụ kiện', 'phukien', 1),
('Whey Protein & Tăng cơ', 'tpbs', 1),
('Pre-Workout & Đốt mỡ', 'tpbs', 1),
('Vitamin & Sức khỏe', 'tpbs', 1);

INSERT INTO thuonghieu (tenthuonghieu, logo, trangthai) VALUES
('Gymshark', 'gymshark.png', 1),
('Nike Pro', 'nike.png', 1),
('Optimum Nutrition', 'on.png', 1),
('MyProtein', 'myprotein.png', 1),
('Under Armour', 'underarmour.png', 1),
('Adidas', 'adidas.png', 1),
('Harbinger', 'harbinger.png', 1),
('BlenderBottle', 'blenderbottle.png', 1),
('Dymatize', 'dymatize.png', 1),
('Applied Nutrition', 'appliednutrition.png', 1),
('MuscleTech', 'muscletech.png', 1),
('Now Foods', 'nowfoods.png', 1);

INSERT INTO cauhinh_bmi (phanloai, bmi_min, bmi_max, khuyenghe) VALUES
('Thiếu cân (Gầy)', 0.0, 18.4, 'Bạn nên ưu tiên mục tiêu Tăng cân - Tăng cơ, kết hợp tập tạ và bổ sung Whey Protein, Mass Gainer cùng Creatine.'),
('Bình thường (Chuẩn)', 18.5, 22.9, 'Thể trạng của bạn rất tốt! Hãy duy trì lịch tập đều đặn kết hợp trang phục thoáng khí và BCAA/Whey để giữ nét cơ.'),
('Thừa cân', 23.0, 24.9, 'Bạn nên hướng tới mục tiêu Giảm mỡ - Giữ cơ, tăng cường Cardio/HIIT với đồ tập thấm hút mồ hôi và TPBS đốt mỡ an toàn.'),
('Béo phì', 25.0, 99.9, 'Hãy ưu tiên chế độ Giảm cân, tập luyện cường độ vừa phải kết hợp bảo vệ khớp (đai lưng, băng gối) và kiểm soát calo.');

INSERT INTO bangsize (gioitinh, size, thutu, chieucao_min, chieucao_max, cannang_min, cannang_max) VALUES
('nam', 'S',  1, 150.0, 165.0, 45.0, 58.0),
('nam', 'M',  2, 165.0, 172.0, 58.0, 68.0),
('nam', 'L',  3, 172.0, 178.0, 68.0, 78.0),
('nam', 'XL', 4, 178.0, 210.0, 78.0, 150.0),
('nu',  'S',  1, 145.0, 155.0, 38.0, 46.0),
('nu',  'M',  2, 155.0, 162.0, 46.0, 53.0),
('nu',  'L',  3, 162.0, 168.0, 53.0, 60.0),
('nu',  'XL', 4, 168.0, 195.0, 60.0, 120.0);

INSERT INTO hosothetrang (nguoidung_id, chieucao, cannang, gioitinh, tuoi, muctieu, bmi) VALUES
(3, 173.0, 70.0, 'nam', 22, 'tangcan', 23.4);

INSERT INTO diachi (nguoidung_id, tennguoinhan, sodienthoai, diachi, macdinh) VALUES
(3, 'Nguyễn Văn An', '0900000003', '18 Ung Văn Khiêm, P. Đông Xuyên, TP. Long Xuyên, An Giang', 1);

INSERT INTO sanpham (tensanpham, mota, giagoc, giaban, soluongton, hinhanh, danhmuc_id, thuonghieu_id, doituong, sizes, mausac, chatlieu, thanhphan, muctieu, hansudung, noibat, trangthai, luotxem, luotmua) VALUES
('Áo Thun Tập Gym Nam Gymshark Onyx Compression', 'Áo tập ôm body tôn dáng cơ bắp, co giãn 4 chiều, thoát mồ hôi cực nhanh khi tập nặng.', 450000, 350000, 50, 'ao_nam_1.jpg', 1, 1, 'nam', 'S,M,L,XL', 'Đen,Xám,Xanh Navy', '88% Polyester, 12% Elastane', NULL, 'tangcan,duytri', NULL, 1, 1, 120, 45),
('Quần Short Tập Gym Nam 2 Lớp Nike Pro Flex', 'Quần đùi 2 lớp có túi khóa kéo đựng điện thoại, phù hợp chạy bộ, Squat và Deadlift.', 380000, 290000, 40, 'quan_nam_1.jpg', 2, 2, 'nam', 'S,M,L,XL', 'Đen,Xám Đậm', 'Polyester kết hợp Spandex thoáng khí', NULL, 'giamcan,tangcan,duytri', NULL, 1, 1, 95, 38),
('Set Đồ Tập Gym Nữ Bra & Legging Nâng Mông', 'Thiết kế cạp cao ôm gọn vòng eo, chất vải dày dặn không lộ khi Squat.', 550000, 420000, 35, 'set_nu_1.jpg', 1, 1, 'nu', 'S,M,L,XL', 'Hồng Pastel,Đen,Tím', 'Nylon & Spandex dệt kim không đường may', NULL, 'giamcan,duytri', NULL, 1, 1, 210, 62),
('Găng Tay Tập Gym Có Quấn Cổ Tay Bảo Vệ Khớp', 'Chống chai tay, tăng độ bám đòn tạ và cố định cổ tay khi đẩy ngực, kéo xô.', 250000, 190000, 60, 'gang_tay_1.jpg', 3, 1, 'unisex', 'S,M,L,XL', 'Đen Đỏ,Đen', 'Da Microfiber + Đệm Gel Silicon chống trượt', NULL, 'tangcan,duytri', NULL, 1, 1, 80, 29),
('Bình Lắc Shaker MyProtein 700ml Kèm Quả Lắc Inox', 'Nhựa chuẩn BPA-Free an toàn sức khỏe, nắp vặn chống rò rỉ nước 100%.', 150000, 110000, 100, 'binh_lac_1.jpg', 4, 4, 'unisex', NULL, 'Đen Khói,Trắng Trong', 'Nhựa PP nguyên sinh BPA-Free & Inox 304', NULL, 'giamcan,tangcan,duytri', NULL, 0, 1, 65, 54),
('Whey Gold Standard 100% Isolate 5Lbs (2.27kg)', 'Hỗ trợ phục hồi và phát triển cơ bắp nhanh chóng sau tập. Pha 1 muỗng với 250ml nước lạnh uống ngay sau buổi tập.', 2150000, 1890000, 25, 'whey_gold.jpg', 5, 3, 'unisex', NULL, 'Vị Chocolate,Vị Vani,Vị Dâu', NULL, '24g Protein, 5.5g BCAA, 4g Glutamine, 1g Carb, 120 Calories / 1 muỗng (30g)', 'tangcan,duytri', '2027-12-31', 1, 1, 310, 88),
('Lipo-6 Black Ultra Concentrate Hỗ Trợ Đốt Mỡ (60 Viên)', 'Tăng sinh nhiệt, chuyển hóa mỡ thừa thành năng lượng trong buổi tập Cardio. Dùng 1 viên trước khi tập 30 phút.', 750000, 620000, 30, 'lipo6.jpg', 6, 3, 'unisex', NULL, 'Hộp 60 viên', NULL, 'Caffeine Anhydrous 200mg, L-Carnitine, Chiết xuất trà xanh & hạt tiêu đen', 'giamcan', '2027-08-15', 1, 1, 145, 41),
('Áo Thun Tập Gym Nam Under Armour Tech 2.0', 'Áo cổ tròn tay ngắn, vải mềm khô nhanh, không có đường may ở vai nên ít cọ xát khi nâng tạ.', 650000, 520000, 45, 'ao_nam_ua_tech.jpg', 1, 5, 'nam', 'S,M,L,XL', 'Đen,Xám,Xanh Navy', '100% Polyester', NULL, 'tangcan,duytri', NULL, 0, 1, 236, 86),
('Áo Tank Top Gym Nam Gymshark Critical', 'Áo ba lỗ khoét nách rộng, để lộ vai và lưng khi tập Push hoặc Pull.', 480000, 390000, 50, 'ao_nam_gymshark_tank.jpg', 1, 1, 'nam', 'S,M,L,XL', 'Đen,Trắng,Xám Đậm', 'Cotton pha Polyester', NULL, 'tangcan,duytri', NULL, 0, 1, 133, 32),
('Áo Thun Tập Luyện Nam Adidas Aeroready', 'Áo thun công nghệ Aeroready thấm hút mồ hôi, form regular, hợp tập cardio và tập tạ nhẹ.', 590000, 450000, 55, 'ao_nam_adidas_aeroready.jpg', 1, 6, 'nam', 'S,M,L,XL', 'Đen,Xanh Than,Xám', '100% Polyester tái chế', NULL, 'giamcan,duytri', NULL, 0, 1, 125, 29),
('Áo Bra Tập Gym Nữ Nike Pro Indy', 'Áo bra đỡ ngực mức nhẹ, dây đeo chéo lưng, hợp Yoga, Pilates và tập cường độ thấp đến vừa.', 790000, 650000, 40, 'bra_nu_nike_indy.jpg', 1, 2, 'nu', 'S,M,L,XL', 'Đen,Hồng Phấn,Trắng', 'Polyester Dri-FIT pha Spandex', NULL, 'giamcan,duytri', NULL, 1, 1, 86, 29),
('Áo Croptop Tập Gym Nữ Gymshark Training', 'Áo croptop ôm sát, vải co giãn 4 chiều, không lộ khi cúi người hoặc giơ tay.', 520000, 420000, 38, 'croptop_nu_gymshark.jpg', 1, 1, 'nu', 'S,M,L,XL', 'Đen,Tím,Xanh Mint', 'Nylon pha Elastane', NULL, 'giamcan,duytri', NULL, 0, 1, 117, 42),
('Quần Short Tập Gym Nam Under Armour Tech', 'Quần short dài trên gối, cạp thun có dây rút, hai túi bên hông.', 520000, 420000, 48, 'quan_nam_ua_short.jpg', 2, 5, 'nam', 'S,M,L,XL', 'Đen,Xám,Xanh Navy', '100% Polyester', NULL, 'giamcan,tangcan,duytri', NULL, 0, 1, 185, 63),
('Quần Jogger Tập Gym Nam Nike Dri-FIT', 'Quần jogger bo gấu, vải Dri-FIT dày vừa, hợp tập buổi sáng sớm hoặc tập ở phòng lạnh.', 950000, 790000, 30, 'quan_nam_nike_jogger.jpg', 2, 2, 'nam', 'S,M,L,XL', 'Đen,Xám Đậm', 'Polyester Dri-FIT', NULL, 'tangcan,duytri', NULL, 0, 1, 46, 13),
('Quần Short Tập Luyện Nam Adidas Designed 4 Training', 'Quần short co giãn nhẹ, cạp thun, có túi khóa kéo phía sau đựng thẻ hoặc chìa khóa.', 650000, 520000, 42, 'quan_nam_adidas_short.jpg', 2, 6, 'nam', 'S,M,L,XL', 'Đen,Xanh Than', 'Polyester tái chế pha Elastane', NULL, 'giamcan,tangcan,duytri', NULL, 0, 1, 99, 20),
('Quần Legging Nữ Gymshark Vital Seamless', 'Legging cạp cao dệt liền không đường may, ôm đùi và mông, không xuyên thấu khi Squat.', 1050000, 850000, 36, 'legging_nu_gymshark_vital.jpg', 2, 1, 'nu', 'S,M,L,XL', 'Đen,Hồng Pastel,Xám', 'Nylon pha Elastane', NULL, 'giamcan,duytri', NULL, 1, 1, 126, 49),
('Quần Legging Nữ Adidas Optime Training', 'Legging cạp cao, có túi nhỏ ở cạp, dùng cho Gym, Yoga và chạy bộ.', 1100000, 890000, 34, 'legging_nu_adidas_optime.jpg', 2, 6, 'nu', 'S,M,L,XL', 'Đen,Xanh Than', 'Polyester tái chế pha Elastane', NULL, 'giamcan,duytri', NULL, 0, 1, 267, 89),
('Quần Short Tập Gym Nữ Nike Pro 3 Inch', 'Quần short bó dài 3 inch, mặc trong hoặc mặc ngoài, không bị cuộn lên khi tập chân.', 620000, 490000, 40, 'quan_nu_nike_pro_short.jpg', 2, 2, 'nu', 'S,M,L,XL', 'Đen,Xám,Xanh Dương', 'Polyester pha Spandex', NULL, 'giamcan,tangcan,duytri', NULL, 0, 1, 295, 115),
('Găng Tay Tập Gym Harbinger Pro Có Quấn Cổ Tay', 'Đệm lòng bàn tay, dây quấn cổ tay cố định khớp khi đẩy ngực, kéo xô và đẩy vai.', 480000, 390000, 45, 'gang_tay_harbinger_pro.jpg', 3, 7, 'unisex', 'S,M,L,XL', 'Đen,Đen Đỏ', 'Da tổng hợp, lưới thoáng, đệm xốp', NULL, 'tangcan,duytri', NULL, 0, 1, 83, 28),
('Đai Lưng Tập Tạ Harbinger 4 Inch', 'Đai bản rộng 4 inch, khóa dán Velcro, giữ thân người ổn định khi Squat và Deadlift nặng.', 850000, 690000, 25, 'dai_lung_harbinger_4inch.jpg', 3, 7, 'unisex', 'S,M,L,XL', 'Đen', 'Nylon bọc xốp, khóa dán', NULL, 'tangcan,giamcan', NULL, 1, 1, 170, 62),
('Dây Kéo Lưng Lifting Straps Harbinger', 'Dây quấn cổ tay giúp giữ đòn tạ khi Deadlift và kéo xô mà không phụ thuộc lực nắm.', 250000, 190000, 60, 'day_keo_harbinger.jpg', 3, 7, 'unisex', NULL, 'Đen,Đỏ', 'Cotton bản dày, đệm cổ tay Neoprene', NULL, 'tangcan', NULL, 0, 1, 120, 40),
('Băng Quấn Gối Harbinger Knee Sleeves', 'Băng gối Neoprene dày 5mm, giữ ấm và hỗ trợ khớp gối khi Squat, Lunge và chạy bộ.', 520000, 420000, 32, 'bang_goi_harbinger.jpg', 3, 7, 'unisex', 'S,M,L,XL', 'Đen', 'Neoprene 5mm', NULL, 'giamcan,tangcan,duytri', NULL, 0, 1, 196, 42),
('Bình Lắc BlenderBottle Classic 28oz (820ml)', 'Bình có quả lắc dây thép BlenderBall, nắp vặn có vòng đệm chống rò, có vạch chia ml.', 280000, 220000, 80, 'binh_lac_blender_classic.jpg', 4, 8, 'unisex', NULL, 'Đen,Trong Suốt,Xanh Navy', 'Nhựa Tritan không chứa BPA', NULL, 'giamcan,tangcan,duytri', NULL, 1, 1, 227, 75),
('Bình Lắc BlenderBottle ProStak 22oz (650ml)', 'Bình có ngăn chứa riêng cho bột hoặc viên, tách ra được, tiện mang khi đi tập.', 420000, 340000, 50, 'binh_lac_blender_prostak.jpg', 4, 8, 'unisex', NULL, 'Đen,Xám', 'Nhựa Tritan không chứa BPA', NULL, 'giamcan,tangcan,duytri', NULL, 0, 1, 57, 21),
('Bình Nước Thể Thao MyProtein 1 Lít', 'Bình dung tích 1 lít, nắp bật có khóa, có vạch đo lượng nước uống trong ngày.', 190000, 150000, 70, 'binh_nuoc_myprotein_1l.jpg', 4, 4, 'unisex', NULL, 'Đen,Xanh Dương,Trắng', 'Nhựa PP không chứa BPA', NULL, 'giamcan,tangcan,duytri', NULL, 0, 1, 201, 53),
('Khăn Tập Gym Microfiber Gymshark', 'Khăn microfiber khô nhanh, kích thước 40x80cm, có khuy gài để treo vào máy tập.', 220000, 170000, 65, 'khan_tap_gymshark.jpg', 4, 1, 'unisex', NULL, 'Đen,Xám', 'Microfiber', NULL, 'giamcan,tangcan,duytri', NULL, 0, 1, 75, 19),
('Túi Trống Tập Gym Under Armour Undeniable Duffle', 'Túi trống có ngăn giày riêng và ngăn ướt, dây đeo vai có đệm, chịu nước nhẹ.', 1200000, 950000, 22, 'tui_trong_ua_duffle.jpg', 4, 5, 'unisex', NULL, 'Đen,Xám Đậm', 'Polyester chống thấm nước', NULL, 'giamcan,tangcan,duytri', NULL, 0, 1, 165, 54),
('Whey Protein Dymatize ISO100 Hydrolyzed 5Lbs (2.3kg)', 'Whey thủy phân tách béo và đường, hấp thu nhanh sau tập. Pha 1 muỗng với 200 đến 250ml nước.', 2350000, 2090000, 20, 'whey_dymatize_iso100.jpg', 5, 9, 'unisex', NULL, 'Vị Chocolate,Vị Vani,Vị Dâu', NULL, 'Khoảng 25g Protein, 5g BCAA, dưới 1g đường, khoảng 110 Calories mỗi muỗng (30g)', 'tangcan,duytri', '2028-03-31', 1, 1, 118, 42),
('Sữa Tăng Cân Optimum Nutrition Serious Mass 12Lbs (5.4kg)', 'Mass Gainer nhiều Calories cho người gầy khó tăng cân. Dùng 1 đến 2 lần mỗi ngày, uống kèm bữa chính.', 1950000, 1690000, 18, 'mass_on_serious_mass.jpg', 5, 3, 'unisex', NULL, 'Vị Chocolate,Vị Vani', NULL, 'Khoảng 1250 Calories, 50g Protein, 250g Carb mỗi 2 muỗng lớn (334g), bổ sung Creatine và vitamin', 'tangcan', '2028-01-31', 0, 1, 290, 107),
('Whey Protein MyProtein Impact Whey 2.5kg', 'Whey Concentrate giá dễ tiếp cận, dùng 1 muỗng sau tập hoặc giữa các bữa ăn.', 1450000, 1190000, 28, 'whey_myprotein_impact.jpg', 5, 4, 'unisex', NULL, 'Vị Chocolate,Vị Vani,Vị Dâu', NULL, 'Khoảng 21g Protein, 4g BCAA, 103 Calories mỗi muỗng (25g)', 'tangcan,duytri', '2028-02-28', 0, 1, 176, 54),
('Creatine Monohydrate MuscleTech Platinum 100% 400g', 'Creatine Monohydrate dạng bột không hương vị, dùng 5g mỗi ngày với nước, tăng sức mạnh khi tập nặng.', 690000, 550000, 35, 'creatine_muscletech_400g.jpg', 5, 11, 'unisex', NULL, 'Không Vị', NULL, '5g Creatine Monohydrate mỗi lần dùng, khoảng 80 lần dùng mỗi hộp', 'tangcan,duytri', '2028-06-30', 1, 1, 180, 69),
('BCAA Applied Nutrition Amino-Hydrate 450g', 'Bột BCAA kèm chất điện giải, uống trong lúc tập để bù nước và hạn chế mất cơ khi giảm mỡ.', 650000, 520000, 30, 'bcaa_applied_amino_hydrate.jpg', 5, 10, 'unisex', NULL, 'Vị Cam,Vị Dưa Hấu,Vị Chanh Dây', NULL, 'BCAA tỉ lệ 2:1:1 (Leucine, Isoleucine, Valine), Glutamine, chất điện giải', 'giamcan,duytri', '2028-04-30', 0, 1, 87, 27),
('Pre-Workout Applied Nutrition ABE 315g', 'Bột pre-workout chứa Caffeine, Beta-Alanine và Citrulline. Dùng 1 muỗng pha với nước 20 đến 30 phút trước khi tập.', 780000, 650000, 26, 'pre_applied_abe.jpg', 6, 10, 'unisex', NULL, 'Vị Cherry Cola,Vị Táo Xanh', NULL, 'Caffeine, Beta-Alanine, L-Citrulline, Taurine, Vitamin nhóm B', 'tangcan,duytri', '2028-02-29', 1, 1, 118, 39),
('Hydroxycut Hardcore Elite MuscleTech 100 Viên', 'Viên hỗ trợ đốt mỡ chứa Caffeine, dùng khi tập Cardio hoặc HIIT. Không dùng gần giờ ngủ.', 750000, 620000, 24, 'dotmo_muscletech_hydroxycut.jpg', 6, 11, 'unisex', NULL, 'Hộp 100 viên', NULL, 'Caffeine Anhydrous, chiết xuất trà xanh, L-Theanine', 'giamcan', '2028-05-31', 0, 1, 206, 60),
('Amino Energy Optimum Nutrition 270g (30 Lần Dùng)', 'Bột Amino kèm Caffeine từ trà xanh và hạt cà phê xanh, dùng trước hoặc trong lúc tập.', 640000, 520000, 33, 'amino_on_energy.jpg', 6, 3, 'unisex', NULL, 'Vị Cam,Vị Dâu,Vị Trái Cây Hỗn Hợp', NULL, 'Khoảng 5g Amino Acid, khoảng 100mg Caffeine mỗi lần dùng (9g)', 'giamcan,duytri', '2028-01-31', 0, 1, 107, 31),
('Vitamin Tổng Hợp Optimum Nutrition Opti-Men 90 Viên', 'Multivitamin cho nam giới tập luyện, dùng 3 viên mỗi ngày sau bữa ăn.', 690000, 560000, 30, 'vitamin_on_opti_men.jpg', 7, 3, 'nam', NULL, 'Hộp 90 viên', NULL, 'Vitamin A, C, D, E, nhóm B, khoáng chất, hỗn hợp Amino Acid', 'tangcan,duytri', '2028-04-30', 0, 1, 172, 68),
('Vitamin Tổng Hợp Optimum Nutrition Opti-Women 120 Viên', 'Multivitamin cho nữ giới tập luyện, có Sắt và Canxi, dùng 2 viên mỗi ngày sau bữa ăn.', 640000, 520000, 28, 'vitamin_on_opti_women.jpg', 7, 3, 'nu', NULL, 'Hộp 120 viên', NULL, 'Vitamin A, C, D, E, nhóm B, Sắt, Canxi, Folate', 'giamcan,duytri', '2028-04-30', 0, 1, 155, 47),
('Omega-3 Now Foods 1000mg 200 Viên', 'Dầu cá chứa EPA và DHA, dùng 1 đến 2 viên mỗi ngày cùng bữa ăn.', 560000, 450000, 40, 'omega3_now_foods.jpg', 7, 12, 'unisex', NULL, 'Hộp 200 viên', NULL, 'Dầu cá (EPA, DHA), Vitamin E', 'giamcan,duytri', '2028-08-31', 0, 1, 164, 35),
('Vitamin D3 Now Foods 5000 IU 240 Viên', 'Vitamin D3 dạng viên mềm, dùng 1 viên mỗi ngày cùng bữa ăn có chất béo.', 420000, 340000, 40, 'vitamin_d3_now_foods.jpg', 7, 12, 'unisex', NULL, 'Hộp 240 viên', NULL, 'Vitamin D3 (Cholecalciferol) 5000 IU mỗi viên, dầu ô liu', 'tangcan,duytri', '2028-10-31', 0, 1, 109, 27);

INSERT INTO voucher (ma, mota, loai, giatri, donhang_toithieu, giamtoida, ngayhethan, soluong, dasudung, trangthai) VALUES
('GYM10', 'Giảm 10% tối đa 100.000đ cho đơn từ 300.000đ', 'phantram', 10, 300000, 100000, '2027-12-31 23:59:59', 100, 0, 1),
('GIAM50K', 'Giảm trực tiếp 50.000đ cho đơn từ 500.000đ', 'sotien', 50000, 500000, NULL, '2027-12-31 23:59:59', 50, 0, 1),
('TANBINH', 'Ưu đãi 20% tối đa 150.000đ cho hội viên mới', 'phantram', 20, 200000, 150000, '2027-12-31 23:59:59', 200, 0, 1);
