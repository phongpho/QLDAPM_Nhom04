# PHÂN TÍCH DATABASE & KẾ HOẠCH BỔ SUNG CÁC MODULE QUẢN LÝ CÒN THIẾU
> **Tài liệu bàn giao & định hướng phát triển dành cho Developer và AI Agent tiếp quản dự án GymBro**  
> *Được biên soạn dựa trên cơ sở dữ liệu `data/gymshop.sql` và cấu trúc các module Admin hiện tại*

---

## 1. BẢNG ĐỐI CHIẾU THỰC TRẠNG CSDL VS ADMIN HIỆN TẠI

| STT | Bảng trong Database (`gymshop.sql`) | Đã có Module Admin? | Tên Module hiện tại | Nhận xét / Tình trạng |
| :---: | :--- | :---: | :--- | :--- |
| 1 | `nguoidung` (loai = 1, 2) | ✅ Có | `admin/qlnhansu` | Quản trị viên cấp cao & Nhân viên bán hàng |
| 2 | `nguoidung` (loai = 3) | ✅ Có | `admin/qlkhachhang` | Quản lý thành viên, khách hàng đăng ký |
| 3 | `diachi` | ✅ Có | Tích hợp trong `qlkhachhang` & `qldonhang` | Lưu sổ địa chỉ giao nhận của khách |
| 4 | `hosothetrang` | ✅ Có | Tích hợp trong `qlkhachhang` | Hồ sơ thể hình, chỉ số BMI của khách |
| 5 | `danhmuc` | ✅ Có | `admin/qldanhmuc` | Quản lý danh mục hàng hóa (quần áo, phụ kiện, TPBS) |
| 6 | `sanpham` | ✅ Có | `admin/qlsanpham` | Quản lý hàng hóa, tồn kho, phân loại, giá bán |
| 7 | `cauhinh_bmi` & `cauhinh_bmi_sanpham` | ✅ Có | `admin/qlchisoBMI` | Cấu hình dải đo BMI & Gán sản phẩm gợi ý |
| 8 | `donhang` & `donhangct` | ✅ Có | `admin/qldonhang` | Quản lý đơn hàng, điều phối vận chuyển & doanh số |
| 9 | `giohang` & `yeuthich` | ℹ️ Bảng động | Xử lý tự động qua Client | Dữ liệu phiên tạm thời & danh sách yêu thích của khách |
| 10 | **`thuonghieu`** | ❌ **CHƯA CÓ** | *(Chưa có `admin/qlthuonghieu`)* | **RẤT CẦN THIẾT (Ưu tiên 1)** |
| 11 | **`voucher`** | ❌ **CHƯA CÓ** | *(Chưa có `admin/qlvoucher`)* | **RẤT CẦN THIẾT (Ưu tiên 2)** |
| 12 | **`danhgia`** | ❌ **CHƯA CÓ** | *(Chưa có `admin/qldanhgia`)* | **NÊN CÓ (Ưu tiên 3 - Duyệt bình luận)** |
| 13 | **`tintuc`** | ❌ **CHƯA CÓ** | *(Chưa có `admin/qltintuc`)* | **TÙY CHỌN (Ưu tiên 4 - Tin bài/Sự kiện)** |

---

## 2. CHI TIẾT 4 MỤC QUẢN LÝ CÒN THIẾU CẦN BỔ SUNG

### 🔴 Mục 1: Quản lý Thương hiệu (`admin/qlthuonghieu`) — *ƯU TIÊN CAO NHẤT*
- **Thực trạng**: Bảng `thuonghieu` đã có sẵn trong CSDL (`id`, `tenthuonghieu`, `logo`, `trangthai`), bảng `sanpham` có khóa ngoại `thuonghieu_id`, nhưng trong Admin chưa có trang CRUD thương hiệu. Khi thêm/sửa sản phẩm, Admin chỉ chọn được các thương hiệu có sẵn trong dữ liệu mẫu.
- **Tính năng cần xây dựng**:
  - Xem danh sách thương hiệu (logo, tên hãng, số lượng sản phẩm đang liên kết, trạng thái ẩn/hiện).
  - Thêm thương hiệu mới kèm upload logo.
  - Sửa thương hiệu & Đổi nhanh trạng thái `trangthai` (1 $\leftrightarrow$ 0).
  - Ràng buộc toàn vẹn: Thương hiệu đã có sản phẩm thì chỉ được Ẩn, không được xóa cứng (chuẩn quy tắc Admin).

---

### 🔴 Mục 2: Quản lý Khuyến mãi & Mã giảm giá (`admin/qlvoucher`) — *ƯU TIÊN CAO*
- **Thực trạng**: Bảng `voucher` đã có sẵn đầy đủ thuộc tính trong CSDL:
  - `ma`: Mã voucher (VD: `GYMNEW`, `FREESHIP50`, `WHEY10`).
  - `loai`: Giảm theo phần trăm (`phantram`) hoặc tiền mặt (`sotien`).
  - `giatri`, `donhang_toithieu`, `giamtoida`, `ngayhethan`, `soluong`, `dasudung`, `trangthai`.
  - Model `model/voucher.php` đã có sẵn các hàm cơ bản, đơn hàng `qldonhang` cũng đang đọc mã voucher, nhưng Admin **chưa có giao diện tạo và quản lý mã voucher**.
- **Tính năng cần xây dựng**:
  - Thống kê voucher: Đang hiệu lực, Hết hạn, Hết lượt sử dụng.
  - Tạo mới mã voucher: Thiết lập % hoặc số tiền giảm, đơn tối thiểu, hạn sử dụng, giới hạn lượt dùng.
  - Khóa/Tạm dừng phát hành voucher (Toggle `trangthai`).

---

### 🟡 Mục 3: Quản lý Đánh giá & Phản hồi (`admin/qldanhgia`) — *ƯU TIÊN TRUNG BÌNH*
- **Thực trạng**: Bảng `danhgia` lưu đánh giá sản phẩm của khách (`nguoidung_id`, `sanpham_id`, `sao` 1-5 sao, `noidung`, `trangthai` 1 hiện / 0 ẩn, `ngay`).
- **Tính năng cần xây dựng**:
  - Kiểm duyệt phản hồi của khách hàng: Cho phép ẩn/xóa các đánh giá vi phạm, spam hoặc khiếm nhã trước khi hiển thị ngoài website.
  - Thống kê tỷ lệ đánh giá sao trung bình theo từng sản phẩm.

---

### ⚪ Mục 4: Quản lý Bài viết & Tin tức (`admin/qltintuc`) — *TÙY CHỌN*
- **Thực trạng**: Bảng `tintuc` (`tieude`, `noidung`, `hinhanh`, `loai` tintuc/sukien/khuyenmai, `trangthai`, `ngaydang`).
- **Tính năng**: Quản lý các bài viết cẩm nang tập gym, kiến thức dinh dưỡng, thông báo sự kiện khuyến mãi cho website.

---

## 3. QUY ƯỚC LẬP TRÌNH & BẢO TOÀN HỆ THỐNG KHI TRIỂN KHAI

Khi Agent hoặc Developer tiếp theo thực hiện phát triển các module này, **BẮT BUỘC** tuân thủ các quy tắc sau:
1. **Bảo toàn Logic của Admin**:
   - Tuyệt đối không được sửa đổi logic của các module Admin đã hoàn thiện.
   - Nhân viên (`loai == 2`) chỉ được thêm, sửa, ẩn/hiện, không được xóa các thực thể có ràng buộc dữ liệu.
2. **Không nạp trùng lặp Bootstrap Bundle**:
   - File `../inc/js/app.js` của giao diện Admin đã chứa sẵn Bootstrap 5. Không chèn thêm thẻ `<script src="...bootstrap.bundle.min.js"></script>`.
3. **Sidebar Navigation**:
   - Khi thêm module mới vào menu sidebar (`admin/inc/sidebar.php`), luôn sử dụng cú pháp kiểm tra trực tiếp:
     ```php
     <li class="sidebar-item <?= (strpos($_SERVER['SCRIPT_NAME'], 'qlthuonghieu') !== false) ? 'active' : '' ?>">
     <li class="sidebar-item <?= (strpos($_SERVER['SCRIPT_NAME'], 'qlvoucher') !== false) ? 'active' : '' ?>">
     ```
4. **Không phơi bày Mã ID database thô trên UI**:
   - Luôn sử dụng cột số thứ tự tăng dần (**STT**) trên bảng dữ liệu.

