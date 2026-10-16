# 🏋️‍♂️ GymBro - Website Bán Đồ Tập Gym & Sản Phẩm Bổ Trợ Cá Nhân Hóa

> **Đồ án môn học:** Quản lý Dự án Phần mềm (Nhóm 04)  
> **Đề tài:** Xây dựng website thương mại điện tử chuyên cung cấp quần áo, phụ kiện tập gym và thực phẩm bổ sung, tích hợp tính năng **gợi ý sản phẩm cá nhân hóa thông minh** dựa trên chỉ số cơ thể (BMI), hồ sơ thể trạng và mục tiêu tập luyện của từng khách hàng.

---

## 👥 Thành viên thực hiện (Nhóm 04)

| STT | Họ và tên | Vai trò | GitHub |
| :-: | :--- | :--- | :--- |
| 1 | **Phó Bảo Phong** | Thành viên phát triển | [@phongpho](https://github.com/phongpho) / [@phobaophong](https://github.com/phobaophong) |
| 2 | **Thái Vĩnh Nghi** | Thành viên phát triển | [@ThaiVinhNghi](https://github.com/ThaiVinhNghi) |
| 3 | **Huỳnh Minh Thuận** | Thành viên phát triển | - |
| 4 | **Tôn Anh Quân** | Thành viên phát triển | - |

---

## 🎯 Mục tiêu & Điểm nổi bật của dự án

* **Trải nghiệm mua sắm thể thao hiện đại:** Giao diện năng động, trực quan, dễ dàng tìm kiếm và lọc sản phẩm theo nhiều tiêu chí (giá, kích cỡ S/M/L/XL, thương hiệu, mục tiêu tập luyện).
* **Tư vấn & Cá nhân hóa thông minh (Core Feature):** 
  * Công cụ tính toán chỉ số **BMI** và thiết lập **Hồ sơ thể trạng** (chiều cao, cân nặng, độ tuổi, giới tính, mục tiêu: Tăng cơ, Giảm mỡ, Duy trì vóc dáng...).
  * Thuật toán tự động lọc và đề xuất trang phục, phụ kiện và combo thực phẩm bổ sung (Whey Protein, BCAA, Creatine, Pre-workout, Vitamin...) phù hợp nhất với thể trạng người dùng.
* **Quản trị toàn diện:** Hệ thống Dashboard dành cho Admin/Nhân viên giúp quản lý sản phẩm, tồn kho, hạn sử dụng, đơn hàng, cấu hình mốc BMI gợi ý và thống kê doanh thu.

---

## 🧩 Các Module chức năng chính

### 1. 👤 Module Người dùng & Khách hàng
* Đăng ký, đăng nhập, phân quyền tài khoản (Admin, Nhân viên, Khách hàng), quản lý thông tin cá nhân và sổ địa chỉ giao hàng.
* Khởi tạo và lưu trữ **Hồ sơ thể trạng** cá nhân.
* Quản lý danh sách sản phẩm yêu thích (**Wishlist**) và theo dõi lịch sử, trạng thái đơn hàng.

### 2. 🧮 Module Tính chỉ số cơ thể & Gợi ý cá nhân hóa
* Form nhập liệu trực quan: Chiều cao, cân nặng, tuổi, giới tính, mục tiêu tập luyện.
* Tự động tính chỉ số cơ thể **BMI** và phân loại thể trạng.
* Đề xuất danh sách sản phẩm và combo tối ưu dựa trên mục tiêu tập luyện của từng cá nhân.

### 3. 🛍️ Module Hiển thị & Tìm kiếm sản phẩm
* Phân loại danh mục rõ ràng: Đồ tập Nam/Nữ, Phụ kiện tập luyện, Thực phẩm bổ sung.
* Hiển thị nhóm sản phẩm Mới, Bán chạy, Nổi bật và Khuyến mãi.
* Chi tiết sản phẩm đầy đủ: Hình ảnh, bảng chọn Size/Màu sắc, chất liệu, thành phần dinh dưỡng, hướng dẫn sử dụng và số lượng tồn kho.
* Tìm kiếm thông minh theo từ khóa, thương hiệu, công dụng kết hợp bộ lọc đa tiêu chí.

### 4. 🛒 Module Giỏ hàng & Thanh toán
* Thêm, sửa số lượng, xóa sản phẩm trong giỏ hàng và tính toán tổng tiền tự động.
* Áp dụng mã giảm giá (**Voucher**) và thanh toán đa phương thức (hỗ trợ tích hợp cổng thanh toán Sandbox).

### 5. ⭐ Module Đánh giá & Phản hồi
* Cho phép khách hàng đánh giá sao và gửi nhận xét về sản phẩm sau khi mua hàng.

### 6. 🛠️ Module Quản trị (Admin Dashboard)
* **Quản lý danh mục, thương hiệu & sản phẩm:** Thêm, sửa, xóa sản phẩm, quản lý bảng size, tồn kho và hạn sử dụng.
* **Quản lý đơn hàng:** Duyệt đơn, cập nhật trạng thái (*Mới, Đã xác nhận, Đã đóng gói, Đang giao, Đã giao/Hoàn thành, Đã hủy*).
* **Quản lý người dùng:** Xem danh sách, khóa/mở khóa tài khoản, phân quyền.
* **Cấu hình gợi ý cá nhân hóa:** Tùy chỉnh quy tắc và các mốc chỉ số BMI gắn với mục tiêu tập luyện.
* **Quản lý tiếp thị:** Quản lý sự kiện, Voucher khuyến mãi và bài viết tin tức.
* **Thống kê & Báo cáo:** Thống kê doanh thu theo ngày và báo cáo danh sách sản phẩm bán chạy.

---

## 🗄️ Cấu trúc Cơ sở dữ liệu (`gymshop`)

Hệ thống sử dụng **MySQL / MariaDB** (bảng mã `utf8mb4_unicode_ci`) bao gồm **14 bảng dữ liệu** và **2 Views thống kê**:

| Tên Bảng / View | Loại | Mô tả chức năng |
| :--- | :---: | :--- |
| `nguoidung` | Table | Lưu thông tin tài khoản, mật khẩu và phân quyền (Admin, Nhân viên, Khách hàng) |
| `hosothetrang` | Table | Lưu chiều cao, cân nặng, BMI và mục tiêu tập luyện của người dùng |
| `diachi` | Table | Quản lý danh sách địa chỉ nhận hàng của khách hàng |
| `danhmuc` | Table | Quản lý danh mục sản phẩm (Quần áo, Phụ kiện, TPBS...) |
| `thuonghieu` | Table | Quản lý thông tin các thương hiệu sản phẩm |
| `sanpham` | Table | Lưu thông tin chi tiết sản phẩm, giá bán, tồn kho, mô tả, mục tiêu phù hợp |
| `bangsize` | Table | Quản lý kích cỡ sản phẩm (S, M, L, XL...) |
| `giohang` | Table | Lưu trữ giỏ hàng của người dùng |
| `voucher` | Table | Quản lý mã giảm giá, chương trình khuyến mãi |
| `donhang` | Table | Lưu thông tin chung của đơn đặt hàng và trạng thái vận chuyển |
| `donhangct` | Table | Lưu chi tiết từng sản phẩm, số lượng và đơn giá trong đơn hàng |
| `danhgia` | Table | Lưu đánh giá và bình luận của khách hàng về sản phẩm |
| `yeuthich` | Table | Lưu danh sách sản phẩm yêu thích (Wishlist) |
| `tintuc` | Table | Quản lý các bài viết tin tức, kiến thức tập luyện & dinh dưỡng |
| `v_doanhthu_ngay` | View | View tổng hợp và thống kê doanh thu theo ngày |
| `v_sanpham_banchay`| View | View thống kê danh sách các sản phẩm bán chạy nhất |

---

## 📂 Cấu trúc thư mục dự án

```text
QLDAPM_Nhom04/
├── admin/          # Giao diện và xử lý chức năng trang Quản trị (Admin Dashboard)
├── assets/         # Tài nguyên tĩnh (CSS, JS, Hình ảnh, Logo, Fonts...)
├── data/           # Chứa file khởi tạo cơ sở dữ liệu (.sql)
├── model/          # Tầng xử lý dữ liệu và kết nối Database (PDO)
├── public/         # Giao diện và các trang phía Khách hàng (Client)
├── index.php       # Điểm vào chính (Entry point) điều hướng của ứng dụng
└── README.md       # Tài liệu giới thiệu và hướng dẫn dự án
