# HƯỚNG DẪN THAY ĐỔI CƠ SỞ DỮ LIỆU & QUY TẮC NGHIỆP VỤ
> **Tài liệu dành cho AI Agent & Developer tiếp tục phát triển dự án GymBro**  
> *Cập nhật lần cuối: Tháng 10/2026*

---

## 1. TỔNG QUAN CÁC THAY ĐỔI TRONG DATABASE (`data/gymshop.sql`)

Cơ sở dữ liệu đã được thiết kế lại hoàn chỉnh từ đầu trong file [data/gymshop.sql](file:///d:/xampp/htdocs/QLDAPM_Nhom04/data/gymshop.sql). Khi cài đặt hoặc làm mới hệ thống, chỉ cần import file này (đã có sẵn `DROP DATABASE IF EXISTS gymshop; CREATE DATABASE gymshop ...`).

### 1.1. Bảng `danhmuc` (Đã cập nhật)
```sql
CREATE TABLE danhmuc (
  id         INT NOT NULL AUTO_INCREMENT,
  tendanhmuc VARCHAR(50) NOT NULL,
  loai       ENUM('quanao','phukien','tpbs') NOT NULL COMMENT 'Nhóm lớn để hiển thị menu',
  trangthai  TINYINT NOT NULL DEFAULT 1 COMMENT '1 hiển thị, 0 ẩn',
  PRIMARY KEY (id),
  UNIQUE KEY uq_danhmuc_ten (tendanhmuc),
  KEY idx_danhmuc_loai_tt (loai, trangthai)
) ENGINE=InnoDB;
```
* **Thêm cột `trangthai`**: Giá trị `1` (Đang hiển thị trên website), `0` (Đang ẩn / Tạm khóa).
* **Thêm `UNIQUE KEY uq_danhmuc_ten`**: Đảm bảo không bị trùng tên danh mục ở tầng cơ sở dữ liệu.
* **Thêm `KEY idx_danhmuc_loai_tt`**: Tối ưu tốc độ tải menu và bộ lọc theo nhóm loại và trạng thái.

### 1.2. Bảng `thuonghieu` (Đã cập nhật)
```sql
CREATE TABLE thuonghieu (
  id            INT NOT NULL AUTO_INCREMENT,
  tenthuonghieu VARCHAR(80) NOT NULL,
  logo          VARCHAR(255) DEFAULT NULL,
  trangthai     TINYINT NOT NULL DEFAULT 1 COMMENT '1 hiển thị, 0 ẩn',
  PRIMARY KEY (id),
  UNIQUE KEY uq_thuonghieu_ten (tenthuonghieu),
  KEY idx_thuonghieu_tt (trangthai)
) ENGINE=InnoDB;
```
* **Thêm cột `trangthai`**: Hỗ trợ ẩn/hiện thương hiệu khi tạm ngừng phân phối hoặc hết hợp đồng.

### 1.3. Bảng `sanpham` (Đã có sẵn)
* Đã có cột `trangthai TINYINT NOT NULL DEFAULT 1 COMMENT '1 đang bán, 0 ẩn'`.
* Có khóa ngoại `CONSTRAINT fk_sanpham_danhmuc FOREIGN KEY (danhmuc_id) REFERENCES danhmuc (id)`.

### 1.4. Bảng `cauhinh_bmi` & Gợi ý đồ dùng `cauhinh_bmi_sanpham` (Đã cập nhật)
* Bảng `cauhinh_bmi` bổ sung các cột:
  * `muctieu_khuyendung ENUM('giamcan','tangcan','duytri') NOT NULL DEFAULT 'duytri'`: Mục tiêu tập luyện khuyến nghị.
  * `trangthai TINYINT NOT NULL DEFAULT 1`: Trạng thái Bật/Tắt mốc BMI.
  * `thutu TINYINT NOT NULL DEFAULT 1`: Thứ tự sắp xếp theo dải chỉ số BMI.
* Bảng liên kết `cauhinh_bmi_sanpham`:
  ```sql
  CREATE TABLE cauhinh_bmi_sanpham (
    cauhinh_bmi_id INT NOT NULL,
    sanpham_id     INT NOT NULL,
    uutien         TINYINT NOT NULL DEFAULT 0,
    PRIMARY KEY (cauhinh_bmi_id, sanpham_id),
    CONSTRAINT fk_bmisp_cauhinh FOREIGN KEY (cauhinh_bmi_id) REFERENCES cauhinh_bmi (id) ON DELETE CASCADE,
    CONSTRAINT fk_bmisp_sanpham FOREIGN KEY (sanpham_id) REFERENCES sanpham (id) ON DELETE CASCADE
  ) ENGINE=InnoDB;
  ```
* **Ý nghĩa nghiệp vụ**: Lưu trữ danh sách đồ dùng/sản phẩm tập luyện do Admin chọn riêng cho từng thể trạng BMI. Phía người dùng khi tính toán BMI sẽ ưu tiên hiển thị các sản phẩm này cùng combo phù hợp. Module `qlchisoBMI` chỉ **Admin (loại 1)** mới được phép truy cập và chỉnh sửa.

---

## 2. QUY TẮC NGHIỆP VỤ BẮT BUỘC (CRITICAL BUSINESS RULES)

### 🔴 Quy tắc 1: Kế thừa trạng thái Ẩn (Cascade Visibility)
> **"Nếu 1 danh mục bị ẩn (`danhmuc.trangthai = 0`), tất cả sản phẩm thuộc danh mục đó phải tự động bị ẩn theo ngoài trang bán hàng."**

* **Nguyên tắc kỹ thuật**: Tránh bulk update hàng loạt cột `sanpham.trangthai = 0` trong DB vì sẽ làm mất cờ trạng thái gốc của từng sản phẩm khi danh mục được hiện lại.
* **Cách thực thi chuẩn**: Mọi câu truy vấn lấy sản phẩm hiển thị ra ngoài trang khách hàng (Storefront, Tìm kiếm, Danh mục, Chi tiết, Giỏ hàng) **BẮT BUỘC** phải `JOIN` với bảng `danhmuc d` và kiểm tra điều kiện:
  ```sql
  WHERE s.trangthai = 1 AND d.trangthai = 1
  ```
* **Các hàm trong `model/sanpham.php` đã được chuẩn hóa theo quy tắc này**:
  * `chuoilocsanpham()`: Bắt buộc có `WHERE s.trangthai = 1 AND d.trangthai = 1`.
  * `laysanphamtheonhom()`: `WHERE s.trangthai = 1 AND d.trangthai = 1 AND d.loai = :loai`.
  * `laysanphammoi()`, `laysanphambanchay()`, `laysanphamnoibatmoi()`, `laysanphamkhuyenmai()`: Đều có `AND d.trangthai = 1`.
* Khi các AI Agent khác viết thêm chức năng liên quan đến sản phẩm, **phải tuân thủ điều kiện `d.trangthai = 1`** này.

---

### 🔴 Quy tắc 2: Phân quyền Quản trị (Admin vs Nhân viên)
Hệ thống sử dụng cột `nguoidung.loai`:
* `1`: Quản trị viên (Admin)
* `2`: Nhân viên bán hàng (Staff)
* `3`: Khách hàng (Customer)

| Hành động | Nhân viên (`loai = 2`) | Admin (`loai = 1`) | Ghi chú kỹ thuật |
| :--- | :---: | :---: | :--- |
| **Xem danh sách** |  Có |  Có | Hiển thị tất cả danh mục (cả đang hiện và đã ẩn) |
| **Thêm mới / Sửa** |  Có |  Có | Phải validate không rỗng và không trùng tên |
| **Ẩn / Hiện danh mục** |  **Có** |  **Có** | Dùng nút Toggle chuyển nhanh trạng thái `trangthai` (1 $\leftrightarrow$ 0) |
| **Xóa danh mục** | ❌ **KHÔNG** |  **Có** | • **Giao diện**: Không hiển thị nút Xóa cho nhân viên.<br>• **Backend**: Bắt buộc chặn ở controller nếu `$_SESSION['nguoidung']['loai'] != 1`.<br>• **Ràng buộc toàn vẹn**: Admin chỉ được xóa khi danh mục **chưa có sản phẩm nào** (`soluongsanpham == 0`). Nếu đã có sản phẩm, chặn xóa và hướng dẫn dùng tính năng **Ẩn**. |

---

## 3. QUY ƯỚC LẬP TRÌNH CHO CÁC MODULE TIẾP THEO

Khi tiếp tục triển khai các module quản trị khác (như `qlsanpham`, `qlthuonghieu`, `qlkhachhang`, `qldonhang`):

1. **bỏ qua**
2. **Không nạp trùng lặp Bootstrap Bundle**:
   * File `../inc/js/app.js` của giao diện Admin **đã chứa sẵn Bootstrap 5**.
   * **Tuyệt đối không chèn thêm** `<script src="...bootstrap.bundle.min.js"></script>` ở cuối các view, vì sẽ làm xung đột các sự kiện Collapse, Modal, Dropdown (khiến form không đóng/mở được).
3. **Không phơi bày Mã ID database trên UI**:
   * Sử dụng cột **STT** (Số thứ tự tăng dần: `$stt = 1; $stt++`) thay vì cột ID thô của CSDL.
4. **Kiểm tra trùng lặp dữ liệu**:
   * Trong Model luôn xây dựng hàm `kiemtratrungten($ten, $id_exclude = 0)` để kiểm tra trước khi thực thi lệnh `INSERT` hoặc `UPDATE`.
5. **Flash Message**:
   * Dùng `$_SESSION['thongbao']` cho thông báo thành công và `$_SESSION['loi']` cho cảnh báo / thất bại, sau khi render thì gọi `unset()`.
6. **Bảo toàn Logic của Admin**:
   * **Tuyệt đối không được sửa đổi logic của Admin** (bao gồm: cơ chế phân quyền Nhân viên chỉ được Ẩn/Hiện chứ không được Xóa, Admin chỉ được Xóa danh mục trống, quy tắc kế thừa ẩn sản phẩm, và cấu trúc điều hướng chuẩn đã thiết lập).

---

## 4. HƯỚNG DẪN HIỂN THỊ SẢN PHẨM GỢI Ý THEO CHỈ SỐ BMI (CLIENT)

Xem tài liệu chi tiết đầy đủ tại: **[HUONG_DAN_HIEN_THI_SAN_PHAM_BMI.md](file:///d:/xampp/htdocs/QLDAPM_Nhom04/data/HUONG_DAN_HIEN_THI_SAN_PHAM_BMI.md)**.
* **Quy tắc cốt lõi**: Khách hàng chỉ được nhìn thấy section **"Một số sản phẩm phù hợp với bạn"** khi **ĐÃ ĐĂNG NHẬP** và **ĐÃ NHẬP FORM CHỈ SỐ CƠ THỂ** (bảng `hosothetrang`). Nếu chưa đăng nhập hoặc chưa nhập chỉ số thể trạng, **tuyệt đối không hiển thị** section này trên trang chủ.

