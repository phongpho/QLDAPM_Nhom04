-- =====================================================================
--  GYMSHOP - Web bán đồ tập gym & sản phẩm bổ trợ cá nhân hóa
--  Đồ án Quản lý dự án phần mềm - Nhóm 04
--  MySQL 5.7+ / MariaDB 10.3+ (XAMPP) - InnoDB - utf8mb4
--  Cách chạy: phpMyAdmin > Import, hoặc  mysql -u root < gymshop.sql
--
--  TÀI KHOẢN MẪU (mật khẩu chung: "password")
--     Admin 0900000001 | Nhân viên 0900000002 | Khách 0900000003
--
--  Ý TƯỞNG GỢI Ý CÁ NHÂN HÓA (đơn giản):
--    Khách nhập: chiều cao, cân nặng, giới tính, mục tiêu (giảm cân/tăng cân/duy trì)
--      1) Hiển thị sản phẩm có sanpham.muctieu chứa mục tiêu của khách
--      2) Với quần áo: tra bảng bangsize để gợi ý size theo chiều cao + cân nặng
--      3) PHP tính BMI để hiển thị cho khách (không lưu bảng riêng)
--
--  QUY ƯỚC
--     nguoidung.loai    : 1 Admin, 2 Nhân viên, 3 Khách hàng
--     muctieu           : 'giamcan' | 'tangcan' | 'duytri'
--     gioitinh/doituong : 'nam' | 'nu'  (sản phẩm thêm 'unisex')
--     donhang.trangthai : 1 Mới, 2 Đã xác nhận, 3 Đã đóng gói, 4 Đang giao,
--                         5 Hoàn thành, 6 Đã hủy
-- =====================================================================

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

-- Địa chỉ giao hàng (thay cho bảng thongtinchitiet trong bài tập trên lớp)
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

-- Hồ sơ thể trạng: mỗi người dùng 1 dòng (nhập lại thì cập nhật dòng đó)
CREATE TABLE hosothetrang (
  id           INT NOT NULL AUTO_INCREMENT,
  nguoidung_id INT NOT NULL,
  chieucao     DECIMAL(5,1) NOT NULL COMMENT 'cm',
  cannang      DECIMAL(5,1) NOT NULL COMMENT 'kg',
  gioitinh     ENUM('nam','nu') NOT NULL,
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
-- 2. SẢN PHẨM
-- =====================================================================

CREATE TABLE danhmuc (
  id         INT NOT NULL AUTO_INCREMENT,
  tendanhmuc VARCHAR(50) NOT NULL,
  loai       ENUM('quanao','phukien','tpbs') NOT NULL COMMENT 'Nhóm lớn để hiển thị menu',
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE thuonghieu (
  id            INT NOT NULL AUTO_INCREMENT,
  tenthuonghieu VARCHAR(80) NOT NULL,
  logo     VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_thuonghieu_ten (tenthuonghieu)
) ENGINE=InnoDB;

CREATE TABLE sanpham (
  id          INT NOT NULL AUTO_INCREMENT,
  tensanpham  VARCHAR(255) NOT NULL,
  mota        TEXT DEFAULT NULL,
  giagoc      DECIMAL(12,0) NOT NULL DEFAULT 0 COMMENT 'Giá niêm yết (hiển thị gạch ngang)',
  giaban      DECIMAL(12,0) NOT NULL DEFAULT 0 COMMENT 'Giá bán hiện tại',
  soluongton  INT NOT NULL DEFAULT 0,
  hinhanh     VARCHAR(255) DEFAULT NULL,
  danhmuc_id  INT NOT NULL,
  thuonghieu_id INT DEFAULT NULL,
  doituong    ENUM('nam','nu','unisex') NOT NULL DEFAULT 'unisex',
  sizes       VARCHAR(30) DEFAULT NULL COMMENT 'Các size đang có, ví dụ "S,M,L,XL". NULL = sản phẩm không có size',
  muctieu     SET('giamcan','tangcan','duytri') NOT NULL DEFAULT 'giamcan,tangcan,duytri'
                COMMENT 'Sản phẩm phù hợp mục tiêu nào (admin tick nhiều mục tiêu)',
  hansudung   DATE DEFAULT NULL COMMENT 'Dành cho thực phẩm bổ sung',
  noibat      TINYINT NOT NULL DEFAULT 0,
  trangthai   TINYINT NOT NULL DEFAULT 1 COMMENT '1 đang bán, 0 ẩn (không xóa cứng vì đơn cũ còn tham chiếu)',
  luotxem     INT NOT NULL DEFAULT 0,
  luotmua     INT NOT NULL DEFAULT 0,
  ngaytao     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_sanpham_danhmuc (danhmuc_id),
  KEY idx_sanpham_thuonghieu (thuonghieu_id),
  KEY idx_sanpham_hienthi (trangthai, noibat),
  FULLTEXT KEY ft_sanpham_tim (tensanpham, mota),
  CONSTRAINT fk_sanpham_danhmuc FOREIGN KEY (danhmuc_id) REFERENCES danhmuc (id),
  CONSTRAINT fk_sanpham_thuonghieu FOREIGN KEY (thuonghieu_id) REFERENCES thuonghieu (id) ON DELETE SET NULL,
  CONSTRAINT ck_sanpham_gia CHECK (giaban >= 0 AND giagoc >= 0)
) ENGINE=InnoDB;

-- Bảng size: khớp theo giới tính. Mỗi size có khoảng chiều cao và khoảng cân nặng.
-- Quy tắc: size gợi ý = size LỚN HƠN trong (size theo chiều cao, size theo cân nặng)
-- Khoảng: min bao gồm, max loại trừ
CREATE TABLE bangsize (
  id           INT NOT NULL AUTO_INCREMENT,
  gioitinh     ENUM('nam','nu') NOT NULL,
  size         VARCHAR(5) NOT NULL,
  thutu        TINYINT NOT NULL COMMENT 'S=1, M=2, L=3, XL=4 (dùng để so sánh)',
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
  size         VARCHAR(5) NOT NULL DEFAULT '' COMMENT 'Rỗng nếu sản phẩm không có size',
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
  giatri           DECIMAL(12,0) NOT NULL COMMENT '% hoặc số tiền VND tùy loại',
  donhang_toithieu DECIMAL(12,0) NOT NULL DEFAULT 0,
  giamtoida        DECIMAL(12,0) DEFAULT NULL COMMENT 'Trần giảm (cho loại phần trăm)',
  ngayhethan       DATETIME NOT NULL,
  soluong          INT NOT NULL DEFAULT 0 COMMENT 'Tổng số lượt phát hành',
  dasudung         INT NOT NULL DEFAULT 0,
  trangthai        TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uq_voucher_ma (ma)
) ENGINE=InnoDB;

CREATE TABLE donhang (
  id            INT NOT NULL AUTO_INCREMENT,
  nguoidung_id  INT NOT NULL,
  tennguoinhan  VARCHAR(100) NOT NULL COMMENT 'Chép từ địa chỉ lúc đặt, tránh sai khi khách sửa địa chỉ sau này',
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
  tongtien      DECIMAL(12,0) NOT NULL DEFAULT 0 COMMENT '= tamtinh - giamgia + phivanchuyen',
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
  dongia     DECIMAL(12,0) NOT NULL DEFAULT 0 COMMENT 'Giá tại thời điểm mua',
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

-- Mỗi khách đánh giá 1 sản phẩm 1 lần (PHP kiểm tra đã mua mới cho đánh giá)
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

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- DỮ LIỆU MẪU
-- =====================================================================

-- Mật khẩu: "password"
INSERT INTO nguoidung (id,gmail,sodienthoai,matkhau,hoten,loai) VALUES
(1,'admin@gymshop.vn','0900000001','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Quản trị viên',1),
(2,'nhanvien@gymshop.vn','0900000002','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Nhân viên bán hàng',2),
(3,'an.nguyen@gmail.com','0900000003','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Nguyễn Văn An',3);

INSERT INTO diachi (nguoidung_id,tennguoinhan,sodienthoai,diachi,macdinh) VALUES
(3,'Nguyễn Văn An','0900000003','12 Nguyễn Văn Cừ, Phường An Hòa, Quận Ninh Kiều, Cần Thơ',1);

-- Khách mẫu: nam, 175cm, 82kg -> BMI 26.8, mục tiêu giảm cân
INSERT INTO hosothetrang (nguoidung_id,chieucao,cannang,gioitinh,muctieu,bmi) VALUES
(3,175.0,82.0,'nam','giamcan',26.8);

INSERT INTO danhmuc (id,tendanhmuc,loai) VALUES
(1,'Áo tập','quanao'),
(2,'Quần tập','quanao'),
(3,'Găng tay & đai lưng','phukien'),
(4,'Bình nước & túi','phukien'),
(5,'Whey Protein','tpbs'),
(6,'BCAA / EAA','tpbs'),
(7,'Creatine','tpbs'),
(8,'Pre-workout','tpbs'),
(9,'Vitamin & Khoáng chất','tpbs'),
(10,'Hỗ trợ đốt mỡ','tpbs'),
(11,'Mass Gainer','tpbs');

INSERT INTO thuonghieu (id,tenthuonghieu) VALUES
(1,'Gymshark'),(2,'Nike'),(3,'Harbinger'),(4,'Myprotein'),
(5,'Optimum Nutrition'),(6,'Nutrex'),(7,'Now Foods');

-- hinhanh: đặt file ảnh thật vào thư mục uploads của dự án
INSERT INTO sanpham
(id,tensanpham,mota,giagoc,giaban,soluongton,hinhanh,danhmuc_id,thuonghieu_id,doituong,sizes,muctieu,hansudung,noibat,luotxem,luotmua) VALUES
(1,'Áo tank nam Pro Dry thoáng khí','Áo ba lỗ thấm hút mồ hôi, nhanh khô, hợp tập cardio.',399000,329000,135,'ao-tank-prodry.jpg',1,1,'nam','S,M,L,XL','giamcan,duytri',NULL,1,420,38),
(2,'Áo thun Oversize Gym Essential','Form rộng thoải mái, chất cotton co giãn.',349000,289000,110,'ao-oversize.jpg',1,2,'unisex','M,L,XL','tangcan,duytri',NULL,0,260,21),
(3,'Áo Compression nam ôm dáng','Áo ôm sát tôn dáng, hỗ trợ cơ bắp khi tập nặng.',449000,369000,50,'ao-compression.jpg',1,1,'nam','M,L,XL','tangcan,duytri',NULL,1,310,27),
(4,'Áo bra thể thao nữ Support','Bra đệm mỏng, nâng đỡ tốt, thoáng khí.',319000,259000,70,'bra-support.jpg',1,2,'nu','S,M,L','giamcan,duytri',NULL,1,380,45),
(5,'Quần legging nữ cạp cao','Legging cạp cao ôm dáng, không lộ khi squat.',479000,399000,95,'legging-caocap.jpg',2,1,'nu','S,M,L','giamcan,duytri',NULL,1,450,52),
(6,'Quần short nam thoáng khí','Short 2 lớp có túi khóa kéo, nhẹ và thoáng.',299000,259000,75,'short-nam.jpg',2,2,'nam','M,L,XL','giamcan,duytri',NULL,0,240,33),
(7,'Quần jogger co giãn Unisex','Jogger ống bo, co giãn 4 chiều.',399000,349000,65,'jogger.jpg',2,2,'unisex','M,L,XL','tangcan,duytri',NULL,0,300,29),
(8,'Găng tay tập tạ Harbinger','Găng đệm lòng bàn tay, chống chai tay.',249000,199000,80,'gang-tay.jpg',3,3,'unisex','M,L','tangcan,duytri',NULL,0,190,41),
(9,'Đai lưng lifting 10cm','Đai da hỗ trợ lưng khi squat/deadlift nặng.',649000,549000,24,'dai-lung.jpg',3,3,'unisex','M,L','tangcan',NULL,0,210,14),
(10,'Bình lắc Shaker 700ml','Bình lắc whey có lò xo đánh tan, nắp chống tràn.',129000,99000,180,'shaker-700.jpg',4,4,'unisex',NULL,'giamcan,tangcan,duytri',NULL,1,330,120),
(11,'Whey Isolate 2lb','Whey Isolate ít đường, ít béo, hấp thu nhanh - hợp giảm cân.',1450000,1250000,50,'whey-isolate.jpg',5,4,'unisex',NULL,'giamcan,duytri','2027-12-31',1,520,66),
(12,'Whey Gold Standard 5lb','Whey blend giàu đạm, hỗ trợ tăng cơ.',1990000,1790000,30,'whey-gold.jpg',5,5,'unisex',NULL,'tangcan,duytri','2028-03-31',1,610,88),
(13,'BCAA 2:1:1 hương trái cây','Hỗ trợ phục hồi cơ và giảm mỏi khi tập.',520000,450000,60,'bcaa.jpg',6,6,'unisex',NULL,'giamcan,duytri','2027-10-31',0,230,35),
(14,'Creatine Monohydrate 300g','Tăng sức mạnh và hiệu suất tập cường độ cao.',450000,390000,50,'creatine.jpg',7,4,'unisex',NULL,'tangcan','2028-06-30',1,400,61),
(15,'Pre-workout THE Pre-Workout','Tăng tỉnh táo và năng lượng cho buổi tập.',790000,690000,50,'preworkout.jpg',8,4,'unisex',NULL,'giamcan,tangcan','2027-09-30',0,350,40),
(16,'L-Carnitine 3000 dạng nước','Hỗ trợ chuyển hóa chất béo, hợp mục tiêu giảm cân.',490000,420000,35,'l-carnitine.jpg',10,6,'unisex',NULL,'giamcan','2027-11-30',1,380,57),
(17,'Mass Gainer 5kg','Sữa tăng cân giàu calo cho người gầy, khó lên cân.',1550000,1350000,20,'mass-gainer.jpg',11,5,'unisex',NULL,'tangcan','2027-08-31',0,270,30),
(18,'Vitamin tổng hợp Daily 90 viên','Bổ sung vitamin & khoáng chất hằng ngày.',380000,320000,60,'vitamin.jpg',9,7,'unisex',NULL,'giamcan,tangcan,duytri','2028-01-31',0,200,48);

INSERT INTO bangsize (gioitinh,size,thutu,chieucao_min,chieucao_max,cannang_min,cannang_max) VALUES
('nam','S',1,0,165,0,60),
('nam','M',2,165,172,60,68),
('nam','L',3,172,178,68,76),
('nam','XL',4,178,250,76,300),
('nu','S',1,0,155,0,50),
('nu','M',2,155,162,50,57),
('nu','L',3,162,168,57,65),
('nu','XL',4,168,250,65,300);

INSERT INTO voucher (id,ma,mota,loai,giatri,donhang_toithieu,giamtoida,ngayhethan,soluong,dasudung) VALUES
(1,'WELCOME10','Giảm 10% cho khách mới (tối đa 100.000đ)','phantram',10,300000,100000,'2026-12-31 23:59:59',500,1),
(2,'GYM50K','Giảm 50.000đ cho đơn từ 500.000đ','sotien',50000,500000,NULL,'2026-12-31 23:59:59',300,0),
(3,'SALE15','Giảm 15% (tối đa 200.000đ)','phantram',15,800000,200000,'2026-10-31 23:59:59',100,0);

-- Đơn hàng mẫu để demo báo cáo
INSERT INTO donhang (id,nguoidung_id,tennguoinhan,sdtnhan,diachinhan,ngay,trangthai,phuongthuc_tt,datt,voucher_id,tamtinh,giamgia,phivanchuyen,tongtien) VALUES
(1,3,'Nguyễn Văn An','0900000003','12 Nguyễn Văn Cừ, Phường An Hòa, Quận Ninh Kiều, Cần Thơ','2026-09-20 10:15:00',5,'cod',1,NULL,1147000,0,0,1147000),
(2,3,'Nguyễn Văn An','0900000003','12 Nguyễn Văn Cừ, Phường An Hòa, Quận Ninh Kiều, Cần Thơ','2026-10-02 14:30:00',4,'vnpay',1,1,769000,76900,0,692100),
(3,3,'Nguyễn Văn An','0900000003','12 Nguyễn Văn Cừ, Phường An Hòa, Quận Ninh Kiều, Cần Thơ','2026-10-10 19:45:00',1,'cod',0,NULL,450000,0,30000,480000);

INSERT INTO donhangct (donhang_id,sanpham_id,size,dongia,soluong,thanhtien) VALUES
(1,1,'XL',329000,2,658000),
(1,14,'',390000,1,390000),
(1,10,'',99000,1,99000),
(2,16,'',420000,1,420000),
(2,7,'XL',349000,1,349000),
(3,13,'',450000,1,450000);

INSERT INTO danhgia (nguoidung_id,sanpham_id,sao,noidung,ngay) VALUES
(3,1,5,'Áo mát, thấm mồ hôi tốt, mặc tập rất thoải mái.','2026-09-25 20:00:00'),
(3,14,4,'Dùng đều 2 tuần thấy tạ lên rõ, vị dễ uống.','2026-10-05 08:30:00');

INSERT INTO yeuthich (nguoidung_id,sanpham_id) VALUES (3,5),(3,14),(3,16);

INSERT INTO tintuc (tieude,noidung,loai) VALUES
('Cách tính BMI cho người tập gym','<p>BMI = cân nặng (kg) / chiều cao (m)². Chỉ số này giúp bạn chọn mục tiêu phù hợp...</p>','tintuc'),
('Khai trương: giảm 15% mọi sản phẩm bổ trợ','<p>Nhập mã SALE15 khi thanh toán cho đơn từ 800.000đ.</p>','khuyenmai'),
('Giải chạy cộng đồng GymShop Run 2026','<p>Đăng ký tham gia để nhận quà và voucher.</p>','sukien');

-- =====================================================================
-- VIEW BÁO CÁO (chỉ tính đơn Hoàn thành)
-- =====================================================================

CREATE OR REPLACE VIEW v_doanhthu_ngay AS
SELECT DATE(ngay) AS ngay, COUNT(*) AS so_don, SUM(tongtien) AS doanhthu
FROM donhang
WHERE trangthai = 5
GROUP BY DATE(ngay);

CREATE OR REPLACE VIEW v_sanpham_banchay AS
SELECT sp.id, sp.tensanpham, SUM(ct.soluong) AS da_ban, SUM(ct.thanhtien) AS doanhthu
FROM donhangct ct
JOIN donhang dh ON dh.id = ct.donhang_id AND dh.trangthai = 5
JOIN sanpham sp ON sp.id = ct.sanpham_id
GROUP BY sp.id, sp.tensanpham;

-- =====================================================================
-- THAM KHẢO CHO PHẦN PHP (dùng PDO, thay :tham_so bằng giá trị khách nhập)
-- =====================================================================

-- [1] BMI (tính trong PHP):  bmi = cannang / (chieucao/100)^2
--     Phân loại (chuẩn châu Á): < 18.5 thiếu cân | < 23 bình thường | < 25 thừa cân | >= 25 béo phì
--     Gợi ý mục tiêu: thiếu cân -> tangcan, thừa cân/béo phì -> giamcan, bình thường -> duytri

-- [2] GỢI Ý SẢN PHẨM theo mục tiêu + giới tính
--     :muctieu = 'giamcan' | 'tangcan' | 'duytri'   :doituong = 'nam' | 'nu'
/*
SELECT sp.id, sp.tensanpham, sp.giaban, sp.sizes, sp.hinhanh
FROM sanpham sp
WHERE sp.trangthai = 1
  AND sp.soluongton > 0
  AND FIND_IN_SET(:muctieu, sp.muctieu) > 0
  AND (sp.doituong = 'unisex' OR sp.doituong = :doituong)
ORDER BY sp.luotmua DESC;
*/

-- [3] GỢI Ý SIZE theo chiều cao + cân nặng (dùng tên tham số khác nhau để PDO không báo lỗi)
--     Kết quả rỗng nếu số đo nằm ngoài bảng. PHP kiểm tra thêm size đó có trong sanpham.sizes
--     bằng FIND_IN_SET(size, sizes) hoặc str_contains/explode trong PHP.
/*
SELECT b.size
FROM bangsize b
WHERE b.gioitinh = :g1
  AND b.thutu = GREATEST(
        (SELECT thutu FROM bangsize WHERE gioitinh = :g2 AND :cc >= chieucao_min AND :cc2 < chieucao_max),
        (SELECT thutu FROM bangsize WHERE gioitinh = :g3 AND :cn >= cannang_min  AND :cn2 < cannang_max));
*/
