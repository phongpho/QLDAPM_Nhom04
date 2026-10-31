# PHÂN TÍCH DATABASE & KẾ HOẠCH BỔ SUNG CÁC MODULE QUẢN LÝ CÒN THIẾU
> **Tài liệu theo dõi tiến độ & hiện trạng các module Quản trị dự án GymBro**  
> *Được biên soạn dựa trên cơ sở dữ liệu `data/gymshop.sql` và cấu trúc các module Admin*

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
| 10 | **`thuonghieu`** | ✅ **HOÀN THÀNH** | `admin/qlthuonghieu` | Quản lý thương hiệu, đếm sản phẩm, logo, ẩn/hiện, chống xóa ràng buộc |
| 11 | **`voucher`** | ✅ **HOÀN THÀNH** | `admin/qlvoucher` | Quản lý mã giảm giá (%, tiền mặt), KPI, progress bar dùng, đơn tối thiểu |
| 12 | **`danhgia`** | ✅ **HOÀN THÀNH** | `admin/qldanhgia` | Kiểm duyệt đánh giá khách hàng, xếp hạng sao, ẩn/hiển thị ngoài website |
| 13 | **`tintuc`** | ✅ **HOÀN THÀNH** | `admin/qltintuc` | Đăng & quản lý cẩm nang dinh dưỡng, sự kiện, thông báo khuyến mãi |

---

## 2. CHI TIẾT 4 MỤC QUẢN LÝ ĐÃ HOÀN THÀNH

### ✅ Mục 1: Quản lý Thương hiệu (`admin/qlthuonghieu`)
- **Tập tin**: `model/thuonghieu.php`, `admin/qlthuonghieu/index.php`, `admin/qlthuonghieu/main.php`.
- **Tính năng**:
  - Xem danh sách thương hiệu, logo trực quan, đếm sản phẩm đang liên kết.
  - Thêm thương hiệu mới kèm tải lên logo (JPG, PNG, WEBP, SVG) vào thư mục ảnh chuẩn.
  - Sửa thương hiệu & Đổi nhanh trạng thái `trangthai` (1 ↔ 0).
  - Ràng buộc an toàn: Không cho phép xóa thương hiệu đã có sản phẩm (bảo toàn toàn vẹn dữ liệu).

---

### ✅ Mục 2: Quản lý Khuyến mãi & Mã giảm giá (`admin/qlvoucher`)
- **Tập tin**: `model/voucher.php`, `admin/qlvoucher/index.php`, `admin/qlvoucher/main.php`.
- **Tính năng**:
  - Thống kê 4 thẻ KPI: Tổng voucher, Đang áp dụng, Đã hết hạn, Đã hết lượt dùng.
  - Tạo mới voucher: Giảm theo % (có trần giảm tối đa) hoặc giảm tiền mặt trực tiếp.
  - Quản lý hạn sử dụng, điều kiện đơn hàng tối thiểu, tiến độ sử dụng (`dasudung / soluong`).
  - Bảo toàn kế toán: Voucher đã có lượt dùng sẽ tự động chuyển sang tạm dừng/khóa thay vì xóa cứng.

---

### ✅ Mục 3: Quản lý Đánh giá & Phản hồi (`admin/qldanhgia`)
- **Tập tin**: `model/danhgia.php`, `admin/qldanhgia/index.php`, `admin/qldanhgia/main.php`.
- **Tính năng**:
  - Kiểm duyệt phản hồi của khách hàng: Cho phép ẩn/duyệt hiển thị đánh giá lên trang sản phẩm.
  - Thống kê điểm sao trung bình, lượng đánh giá 5 sao, đánh giá chờ duyệt.
  - Lọc theo số sao (1-5 sao), trạng thái, tìm kiếm từ khóa.

---

### ✅ Mục 4: Quản lý Bài viết & Tin tức (`admin/qltintuc`)
- **Tập tin**: `model/tintuc.php`, `admin/qltintuc/index.php`, `admin/qltintuc/main.php`.
- **Tính năng**:
  - Quản lý các bài viết cẩm nang tập gym, kiến thức dinh dưỡng, thông báo sự kiện khuyến mãi cho website.
  - Phân loại chuyên mục: Cẩm nang, Sự kiện, Khuyến mãi.
  - Hỗ trợ tải lên ảnh bìa đại diện và chuyển đổi nhanh giữa Hiển thị / Bản nháp.

---

## 3. TÍCH HỢP HỆ THỐNG
1. **Menu Sidebar (`admin/inc/sidebar.php`)**: Đã cập nhật đầy đủ cả 4 module mới với Feather Icons chuẩn và cú pháp `(strpos($_SERVER['SCRIPT_NAME'], '<module>') !== false) ? 'active' : ''`.
2. **Tìm kiếm toàn cục Spotlight (`Ctrl + K`)**: Đã tích hợp tìm kiếm nhanh trực tiếp Thương hiệu & Mã Voucher ngay trên thanh tìm kiếm thông minh.
3. **Quy tắc an toàn giao diện**: Không hiển thị mã ID database thô trên bảng (sử dụng cột số thứ tự tăng dần STT).
