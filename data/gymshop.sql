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
  muctieu_khuyendung ENUM('giamcan','tangcan','duytri') NOT NULL DEFAULT 'duytri' COMMENT 'Mục tiêu khuyến nghị',
  khuyenghe TEXT DEFAULT NULL,
  trangthai TINYINT NOT NULL DEFAULT 1 COMMENT '1 hoạt động, 0 tắt',
  thutu TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE cauhinh_bmi_sanpham (
  cauhinh_bmi_id INT NOT NULL,
  sanpham_id     INT NOT NULL,
  uutien         TINYINT NOT NULL DEFAULT 0 COMMENT '1: Sản phẩm tiêu biểu, 0: Bổ trợ',
  PRIMARY KEY (cauhinh_bmi_id, sanpham_id),
  CONSTRAINT fk_bmisp_cauhinh FOREIGN KEY (cauhinh_bmi_id) REFERENCES cauhinh_bmi (id) ON DELETE CASCADE,
  CONSTRAINT fk_bmisp_sanpham FOREIGN KEY (sanpham_id) REFERENCES sanpham (id) ON DELETE CASCADE
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- DỮ LIỆU MẪU
-- =====================================================================

-- Mật khẩu mặc định: "password"
INSERT INTO nguoidung (gmail, sodienthoai, matkhau, hoten, loai, trangthai) VALUES
('pbphong@gmail.com', '0900000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Phó Bảo Phong', 1, 1),
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

INSERT INTO cauhinh_bmi (phanloai, bmi_min, bmi_max, muctieu_khuyendung, khuyenghe, trangthai, thutu) VALUES
('Thiếu cân (Gầy)', 0.0, 18.4, 'tangcan', 'Bạn nên ưu tiên mục tiêu Tăng cân - Tăng cơ, kết hợp tập tạ và bổ sung Whey Protein, Mass Gainer cùng Creatine.', 1, 1),
('Bình thường (Chuẩn)', 18.5, 22.9, 'duytri', 'Thể trạng của bạn rất tốt! Hãy duy trì lịch tập đều đặn kết hợp trang phục thoáng khí và BCAA/Whey để giữ nét cơ.', 1, 2),
('Thừa cân', 23.0, 24.9, 'giamcan', 'Bạn nên hướng tới mục tiêu Giảm mỡ - Giữ cơ, tăng cường Cardio/HIIT với đồ tập thấm hút mồ hôi và TPBS đốt mỡ an toàn.', 1, 3),
('Béo phì', 25.0, 99.9, 'giamcan', 'Hãy ưu tiên chế độ Giảm cân, tập luyện cường độ vừa phải kết hợp bảo vệ khớp (đai lưng, băng gối) và kiểm soát calo.', 1, 4);

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
('Áo Thun Tập Gym Nam Gymshark Onyx Compression', 'Áo thun tập gym nam Gymshark Onyx Compression sở hữu thiết kế ôm sát body (Compression-fit) tôn trọn khung cơ bắp vai, ngực và bắp tay của gymer. Ứng dụng công nghệ dệt nén cơ học giúp tăng cường tuần hoàn máu, hỗ trợ giảm rung cơ khi nâng tạ nặng. Chất vải 88% Polyester phối 12% Elastane cao cấp co giãn 4 chiều linh hoạt, bề mặt vải thoáng khí với công nghệ Quick-Dry thấm hút và khuếch tán mồ hôi siêu tốc. Đường chỉ may phẳng Flatlock không gờ nổi, loại bỏ hoàn toàn hiện tượng cọ xát gây rát da khi tập cường độ cao.\n\nMặc trực tiếp trong các buổi tập gym, thể hình, chạy bộ, CrossFit hoặc tập luyện ngoài trời. Phù hợp nhất cho các buổi tập thân trên (Push / Pull) để cảm nhận lực nén cơ tối ưu.\n\nBẢO QUẢN CHUẨN GYMER:\n- Giặt bằng nước lạnh dưới 30°C, ưu tiên giặt tay hoặc dùng túi giặt khi giặt máy.\n- Lộn trái áo trước khi giặt và phơi để giữ form dáng và màu sắc bền lâu.\n- Không dùng chất tẩy trắng hoặc nước xả vải làm suy giảm độ đàn hồi sợi Elastane.\n- Phơi trong bóng râm thoáng gió, không ủi/là trực tiếp lên logo in nhiệt.', 450000, 350000, 50, 'ao_nam_1.jpg', 1, 1, 'nam', 'S,M,L,XL', 'Đen,Xám,Xanh Navy', '88% Polyester, 12% Elastane', NULL, 'tangcan,duytri', NULL, 1, 1, 121, 45),
('Quần Short Tập Gym Nam 2 Lớp Nike Pro Flex', 'Quần short tập gym nam 2 lớp Nike Pro Flex mang đến sự tự tin tuyệt đối cho mọi bài tập chân nặng. Thiết kế 2 lớp thông minh gồm lớp ngoài vải dệt nhẹ co giãn 4 chiều và lớp lót nén cơ đùi trong ôm sát, chống cọ xát bẹn và ngăn ngừa hiện tượng lộ hàng khi Squat, Deadlift hay Lunge. Điểm nhấn là túi khóa kéo bên hông sâu rộng, ôm chắc điện thoại thông minh và phụ kiện không bị rung lắc khi chạy nhảy. Cạp thun thể thao mềm mại có dây rút tùy chỉnh độ vừa vặn.\n\nThích hợp cho các buổi tập chân (Leg Day), Squat, chạy bộ cự ly dài, đạp xe và tập luyện thể thao đa năng.\n\nBẢO QUẢN CHUẨN GYMER:\n- Kéo kín các khóa túi trước khi cho vào máy giặt để tránh móc rách vải.\n- Giặt với nước lạnh hoặc nhiệt độ thường, tránh ngâm lâu trong xà phòng.\n- Phơi ở nơi thoáng mát, tránh phơi trực tiếp dưới ánh nắng mặt trời gay gắt.', 380000, 290000, 40, 'quan_nam_1.jpg', 2, 2, 'nam', 'S,M,L,XL', 'Đen,Xám Đậm', 'Polyester kết hợp Spandex thoáng khí', NULL, 'giamcan,tangcan,duytri', NULL, 1, 1, 97, 38),
('Set Đồ Tập Gym Nữ Bra & Legging Nâng Mông', 'Set đồ tập gym nữ cao cấp gồm Sport Bra trợ lực vừa và quần Legging cạp cao nâng mông dệt liền mạch (Seamless). Chất liệu sợi Nylon kết hợp Spandex mật độ cao mang lại cảm giác mềm mại như làn da thứ hai, độ co giãn vượt trội cùng khả năng định hình đường cong eo - hông hoàn hảo. Cạp quần bản to 10cm gen nhẹ vòng bụng dưới, đường rãnh nâng mông tôn trọn vòng 3 căng tròn quyến rũ. Vải dày dặn đạt tiêu chuẩn Squat-Proof 100%, không bị xuyên thấu dưới ánh đèn phòng gym.\n\nSử dụng cho các buổi tập Gym, Yoga, Pilates, Cardio hoặc mặc như trang phục thể thao phong cách Athleisure hàng ngày.\n\nBẢO QUẢN CHUẨN GYMER:\n- Tháo rời miếng mút lót đệm của áo bra trước khi giặt để giữ form đệm không bị biến dạng.\n- Giặt tay hoặc chọn chế độ giặt nhẹ với túi giặt chuyên dụng.\n- Không sấy khô bằng máy sấy nhiệt cao, phơi ngang trên móc tại nơi thoáng khí.', 550000, 420000, 35, 'set_nu_1.jpg', 1, 1, 'nu', 'S,M,L,XL', 'Hồng Pastel,Đen,Tím', 'Nylon & Spandex dệt kim không đường may', NULL, 'giamcan,duytri', NULL, 1, 1, 210, 62),
('Găng Tay Tập Gym Có Quấn Cổ Tay Bảo Vệ Khớp', 'Găng tay tập gym tích hợp đai quấn cổ tay là phụ kiện trợ thủ đắc lực bảo vệ toàn diện đôi bàn tay của gymer. Lòng bàn tay lót da Microfiber chịu mài mòn cao cùng đệm Gel silicon tổ ong chống trơn trượt, tăng độ ma sát bám dính chắc chắn vào thanh đòn và tay cầm máy tập. Đai quấn cổ tay bản rộng may liền khóa dán Velcro kép trợ lực tối đa cho khớp cổ tay, ngăn ngừa tình trạng trật khớp hoặc đau mỏi cổ tay khi đẩy tạ nặng.\n\nĐeo găng vào tay, siết đai quấn cổ tay vừa vặn với cổ tay trước khi bước vào các hiệp tập Bench Press, Overhead Press, Dumbbell Press hoặc xà đơn.\n\nBẢO QUẢN CHUẨN GYMER:\n- Mở thông thoáng găng tay sau mỗi buổi tập để thoát hơi ẩm và mồ hôi đọng lại.\n- Giặt tay nhẹ nhàng với xà phòng loãng định kỳ 1-2 tuần/lần.\n- Phơi khô tự nhiên trong bóng mát, không phơi trên nắp máy sưởi hoặc dưới nắng gắt.', 250000, 190000, 60, 'gang_tay_1.jpg', 3, 1, 'unisex', 'S,M,L,XL', 'Đen Đỏ,Đen', 'Da Microfiber + Đệm Gel Silicon chống trượt', NULL, 'tangcan,duytri', NULL, 1, 1, 80, 29),
('Bình Lắc Shaker MyProtein 700ml Kèm Quả Lắc Inox', 'Bình lắc Shaker MyProtein 700ml chuẩn gym chuyên nghiệp được sản xuất từ chất liệu nhựa nguyên sinh Polypropylene cao cấp, đạt tiêu chuẩn an toàn thực phẩm FDA, không chứa BPA và DEHP độc hại. Thiết kế nắp đậy ren xoắn kết hợp gioăng cao su chống rò rỉ nước 100%, thoải mái bỏ vào balo tập mà không lo tràn đổ. Đi kèm quả lắc lò xo bằng inox 304 không gỉ giúp đánh tan mịn màng các loại bột dinh dưỡng nhanh chóng.\n\nCho lượng nước hoặc sữa hạt vào bình trước (nước nguội hoặc nước lạnh), thêm bột dinh dưỡng (Whey, Mass, BCAA, Pre-workout), thả quả lắc inox vào và đóng chặt nắp. Lắc đều từ 15-20 giây là có thể thưởng thức.\n\nBẢO QUẢN CHUẨN GYMER:\n- Rửa sạch bình ngay sau khi uống để tránh vi khuẩn và mùi chua bám vào nhựa.\n- Không cho nước sôi hoặc nước nóng trên 60°C vào bình.\n- Mở nắp bình khi úp ráo nước để bình luôn thơm tho, sạch sẽ.', 150000, 110000, 100, 'binh_lac_1.jpg', 4, 4, 'unisex', NULL, 'Đen Khói,Trắng Trong', 'Nhựa PP nguyên sinh BPA-Free & Inox 304', NULL, 'giamcan,tangcan,duytri', NULL, 0, 1, 65, 54),
('Whey Gold Standard 100% Isolate 5Lbs (2.27kg)', 'Whey Gold Standard 100% Isolate 5Lbs (2.27kg) từ Optimum Nutrition là tiêu chuẩn vàng trong ngành thực phẩm bổ sung toàn cầu. Mỗi khẩu phần cung cấp 24g Protein tinh khiết kết hợp từ Whey Protein Isolate và Whey Peptides thủy phân hấp thu nhanh vào sợi cơ. Sản phẩm chứa 5.5g BCAA tự nhiên và 4g Glutamine hỗ trợ phục hồi mô cơ tổn thương sau tập, giảm đau nhức cơ bắp hiệu quả. Hàm lượng chất béo, đường và tinh bột cực thấp, lý tưởng cho mục tiêu tăng cơ nạc.\n\nPha 1 muỗng gạt ngang (khoảng 30.4g) với 200 - 250ml nước lạnh hoặc sữa hạt không đường. Dùng bình lắc lắc đều trong 20 giây và uống ngay.\n\nTHỜI ĐIỂM SỬ DỤNG TỐT NHẤT:\n- Ngay sau buổi tập trong vòng 30 - 45 phút (thời điểm vàng nạp protein).\n- Buổi sáng ngay sau khi thức dậy để bổ sung amino acid sau giấc ngủ dài.\n\nBẢO QUẢN CHUẨN GYMER:\n- Đậy kín nắp hộp sau mỗi lần sử dụng, bảo quản nơi khô ráo, thoáng mát dưới 25°C.', 2150000, 1890000, 25, 'whey_gold.jpg', 5, 3, 'unisex', NULL, 'Vị Chocolate,Vị Vani,Vị Dâu', NULL, '24g Protein, 5.5g BCAA, 4g Glutamine, 1g Carb, 120 Calories / 1 muỗng (30g)', 'tangcan,duytri', '2027-12-31', 1, 1, 310, 88),
('Lipo-6 Black Ultra Concentrate Hỗ Trợ Đốt Mỡ (60 Viên)', 'Lipo-6 Black Ultra Concentrate 60 Viên từ Nutrex Research là dòng sản phẩm hỗ trợ đốt mỡ cắt nét cơ bắp với công thức cô đặc cực mạnh. Sản phẩm hoạt động theo cơ chế tăng sinh nhiệt tự nhiên (Thermogenesis), kích thích tốc độ trao đổi chất và chuyển hóa mô mỡ thừa thành năng lượng phục vụ buổi tập. Chứa Caffeine Anhydrous 200mg, Theobromine và chiết xuất tiêu đen Bioperine giúp tăng khả năng tập trung, tỉnh táo cao độ và kiểm soát cơn thèm ăn.\n\nUống 1 viên vào buổi sáng và 1 viên vào buổi chiều trước khi tập luyện 30 phút cùng 1 ly nước lớn.\n\nLƯU Ý QUAN TRỌNG:\n- Không dùng quá 2 viên trong vòng 24 giờ.\n- Không uống trong vòng 6 tiếng trước khi đi ngủ để tránh mất ngủ.\n- Kết hợp cùng chế độ ăn thâm hụt calo và tập luyện thể thao đều đặn để đạt kết quả tối ưu.\n\nBẢO QUẢN CHUẨN GYMER:\n- Đậy chặt nắp lọ, để nơi khô ráo thoáng mát, tránh ánh sáng trực tiếp.', 750000, 620000, 29, 'lipo6.jpg', 6, 3, 'unisex', NULL, 'Hộp 60 viên', NULL, 'Caffeine Anhydrous 200mg, L-Carnitine, Chiết xuất trà xanh & hạt tiêu đen', 'giamcan', '2027-08-15', 1, 1, 146, 42);

INSERT INTO voucher (ma, mota, loai, giatri, donhang_toithieu, giamtoida, ngayhethan, soluong, dasudung, trangthai) VALUES
('GYM10', 'Giảm 10% tối đa 100.000đ cho đơn từ 300.000đ', 'phantram', 10, 300000, 100000, '2027-12-31 23:59:59', 100, 0, 1),
('GIAM50K', 'Giảm trực tiếp 50.000đ cho đơn từ 500.000đ', 'sotien', 50000, 500000, NULL, '2027-12-31 23:59:59', 50, 0, 1),
('TANBINH', 'Ưu đãi 20% tối đa 150.000đ cho hội viên mới', 'phantram', 20, 200000, 150000, '2027-12-31 23:59:59', 200, 0, 1);

-- Gợi ý đồ dùng / sản phẩm mẫu theo từng mốc BMI
INSERT INTO cauhinh_bmi_sanpham (cauhinh_bmi_id, sanpham_id, uutien) VALUES
(1, 1, 1), (1, 6, 1), (1, 30, 1), (1, 5, 0),
(2, 1, 1), (2, 2, 1), (2, 6, 1), (2, 4, 0),
(3, 2, 1), (3, 7, 1), (3, 20, 1), (3, 22, 0),
(4, 7, 1), (4, 20, 1), (4, 22, 1), (4, 4, 0);
