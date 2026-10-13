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
  tuoi		   INT NOT NULL,
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
  mausac      VARCHAR(100) DEFAULT NULL,
  chatlieu    VARCHAR(100) DEFAULT NULL,
  thanhphan   TEXT DEFAULT NULL,
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
  
  doitacgiaohang VARCHAR(255),
  madonhang VARCHAR(255),
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


CREATE TABLE cauhinh_bmi (
  id INT NOT NULL AUTO_INCREMENT,
  phanloai VARCHAR(50) NOT NULL COMMENT 'Ví dụ: Gầy, Bình thường, Thừa cân, Béo phì',
  bmi_min DECIMAL(4,1) NOT NULL COMMENT 'Mức BMI nhỏ nhất của khoảng',
  bmi_max DECIMAL(4,1) NOT NULL COMMENT 'Mức BMI lớn nhất của khoảng',
  khuyenghe TEXT DEFAULT NULL COMMENT 'Lời khuyên chung để hiển thị cho khách hàng',
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

