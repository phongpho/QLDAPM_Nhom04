<div align="center">

#  GYMBRO - WEBSITE BÁN ĐỒ TẬP GYM & SẢN PHẨM BỔ TRỢ CÁ NHÂN HÓA

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-InnoDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Architecture](https://img.shields.io/badge/Architecture-MVC-success?style=for-the-badge)]()
[![Status](https://img.shields.io/badge/Status-Active-brightgreen?style=for-the-badge)]()

**Đồ án Quản lý Dự án Phần mềm - Nhóm 04**  
*Giải pháp thương mại điện tử thể thao tích hợp gợi ý sản phẩm thông minh dựa trên chỉ số cơ thể BMI và mục tiêu tập luyện.*

[Tính năng nổi bật](#-tính-năng-nổi-bật) •
[Công nghệ sử dụng](#-công-nghệ-sử-dụng) •
[Cấu trúc CSDL](#-cấu-trúc-cơ-sở-dữ-liệu) •
[Hướng dẫn cài đặt](#-hướng-dẫn-cài-đặt) •
[Thành viên nhóm](#-thành-viên-nhóm-nhóm-04)

</div>

---

##  Giới thiệu dự án

**GymBro (GymShop)** là nền tảng thương mại điện tử chuyên cung cấp quần áo tập gym, phụ kiện thể thao và các dòng thực phẩm bổ sung (Whey Protein, BCAA, Creatine, Pre-workout, Vitamin...). 

Điểm khác biệt cốt lõi của hệ thống là **công cụ phân tích thể trạng và gợi ý cá nhân hóa**. Thay vì để người dùng tự tìm kiếm giữa hàng trăm sản phẩm, hệ thống cho phép thiết lập hồ sơ thể trạng (chiều cao, cân nặng, độ tuổi, giới tính) và mục tiêu tập luyện (tăng cơ, giảm mỡ, duy trì vóc dáng...). Từ đó, thuật toán sẽ tính toán chỉ số cơ thể $\text{BMI}$ theo công thức:

$$\text{BMI} = \frac{\text{Cân nặng (kg)}}{(\text{Chiều cao (m)})^2}$$

Kết hợp cùng bảng quy tắc thể hình, website tự động đề xuất kích cỡ trang phục (Size) vừa vặn nhất cũng như các gói combo thực phẩm bổ sung và phụ kiện phù hợp với từng cá nhân.

---

##  Tính năng nổi bật

Hệ thống được thiết kế theo kiến trúc phân tầng với **7 module chức năng chính**:

### 1. Module Tính chỉ số cơ thể & Gợi ý cá nhân hóa
* **Form tư vấn trực quan:** Nhập chiều cao, cân nặng, độ tuổi, giới tính và mục tiêu tập luyện.
* **Đánh giá thể trạng tự động:** Tính toán chỉ số $\text{BMI}$ và phân loại thể trạng ngay lập tức.
* **Thuật toán gợi ý thông minh:** Tự động lọc và đề xuất danh sách trang phục đúng kích cỡ (S/M/L/XL) cùng các sản phẩm bổ trợ/combo tối ưu theo mục tiêu tập luyện của từng khách hàng.

### 2. Module Hiển thị sản phẩm
* **Danh mục đa dạng:** Phân nhóm rõ ràng gồm Đồ tập Nam/Nữ, Phụ kiện tập luyện và Thực phẩm bổ sung; hiển thị danh sách sản phẩm mới, bán chạy, nổi bật và đang khuyến mãi.
* **Chi tiết sản phẩm chuyên sâu:** Cung cấp đầy đủ hình ảnh, giá bán, bảng chọn Size/Màu sắc, chất liệu vải (đối với đồ tập) hoặc bảng thành phần dinh dưỡng, hướng dẫn sử dụng, hạn sử dụng (đối với thực phẩm bổ sung).

### 3. Module Tìm kiếm & Bộ lọc đa tiêu chí
* **Tìm kiếm thông minh:** Tra cứu nhanh theo tên sản phẩm, thương hiệu hoặc từ khóa công dụng/mục tiêu tập luyện.
* **Bộ lọc nâng cao & So sánh:** Lọc đồng thời theo khoảng giá, thương hiệu, kích cỡ (Size) và mục tiêu tập luyện; hỗ trợ đối chiếu thông số giữa các sản phẩm.

### 4. Module Người dùng (Khách hàng)
* Đăng ký, đăng nhập, bảo mật tài khoản và quản lý sổ địa chỉ giao hàng.
* Lưu trữ và cập nhật **Hồ sơ thể trạng cá nhân** để nhận gợi ý xuyên suốt quá trình mua sắm.
* Theo dõi lịch sử đơn hàng theo thời gian thực và quản lý danh sách sản phẩm yêu thích (*Wishlist*).

### 5. Module Giỏ hàng & Thanh toán
* Thêm, sửa, xóa sản phẩm, tự động tính toán tổng tiền và kiểm tra tồn kho.
* Áp dụng linh hoạt mã giảm giá (*Voucher*) và chương trình khuyến mãi.
* Hỗ trợ thanh toán đa phương thức, tích hợp cổng thanh toán thử nghiệm (*Sandbox*).

### 6. Module Đánh giá & Phản hồi
* Cho phép khách hàng đã mua sản phẩm gửi đánh giá sao và nhận xét thực tế sau quá trình sử dụng, tăng tính minh bạch và hỗ trợ cộng đồng người mua.

### 7. Module Quản trị hệ thống (Admin Dashboard)
* **Quản lý danh mục & sản phẩm:** Thêm, sửa, xóa, phân loại sản phẩm, kiểm soát số lượng tồn kho và hạn sử dụng.
* **Cấu hình thuật toán gợi ý:** Tùy chỉnh linh hoạt các mốc chỉ số $\text{BMI}$, bảng size và ma trận gợi ý sản phẩm theo mục tiêu.
* **Quản lý đơn hàng & vận chuyển:** Duyệt đơn và cập nhật luồng trạng thái.
* **Quản lý người dùng & phân quyền:** Quản lý tài khoản, khóa/mở khóa và phân quyền chi tiết (*Admin, Nhân viên, Khách hàng*).
* **Tiếp thị & Nội dung:** Quản lý sự kiện khuyến mãi, mã giảm giá (*Voucher*) và bài viết tin tức/kiến thức thể hình.
* **Báo cáo & Thống kê:** Trực quan hóa doanh thu theo ngày/tháng và thống kê danh sách sản phẩm bán chạy.

---

## Công nghệ sử dụng

| Thành phần | Công nghệ / Công cụ |
| :--- | :--- |
| **Kiến trúc phần mềm** | Mô hình **MVC** (Model - View - Controller) |
| **Ngôn ngữ Backend** | PHP (Kết nối cơ sở dữ liệu qua **PDO** an toàn, chống SQL Injection) |
| **Cơ sở dữ liệu** | MySQL / MariaDB (Engine: `InnoDB`, Collation: `utf8mb4_unicode_ci`) |
| **Giao diện (Frontend)** | HTML5, CSS3, JavaScript (Thiết kế Responsive, phong cách thể thao năng động) |
| **Môi trường phát triển** | XAMPP (Apache + MySQL), Git/GitHub |
| **Tích hợp bên thứ ba** | Cổng thanh toán trực tuyến (Môi trường *Sandbox*) |

---

## 🗄 Cấu trúc Cơ sở dữ liệu (`gymbro`)

Cơ sở dữ liệu **`gymbro`** bao gồm **14 bảng dữ liệu (Tables)** và **2 khung nhìn thống kê (Views)** được thiết kế chuẩn hóa:

| STT | Tên Bảng / View | Loại | Mô tả chức năng |
| :-: | :--- | :---: | :--- |
| 1 | `nguoidung` | Table | Lưu thông tin tài khoản, mật khẩu mã hóa và phân quyền (Admin/Nhân viên/Khách) |
| 2 | `hosothetrang` | Table | Lưu chiều cao, cân nặng, tuổi, giới tính, chỉ số $\text{BMI}$ và mục tiêu tập luyện |
| 3 | `bangsize` | Table | Quy tắc đối chiếu kích cỡ quần áo/phụ kiện theo số đo cơ thể |
| 4 | `danhmuc` | Table | Phân loại danh mục sản phẩm (Đồ tập, Phụ kiện, Thực phẩm bổ sung...) |
| 5 | `thuonghieu` | Table | Thông tin các thương hiệu phân phối sản phẩm |
| 6 | `sanpham` | Table | Thông tin chi tiết sản phẩm, giá bán, tồn kho, thành phần, mục tiêu phù hợp |
| 7 | `giohang` | Table | Lưu trữ tạm thời các sản phẩm khách hàng đang chọn mua |
| 8 | `donhang` | Table | Thông tin chung của đơn đặt hàng, trạng thái xử lý và thanh toán |
| 9 | `donhangct` | Table | Chi tiết từng mặt hàng, số lượng và đơn giá trong mỗi đơn hàng |
| 10 | `diachi` | Table | Danh sách địa chỉ nhận hàng của người dùng |
| 11 | `voucher` | Table | Quản lý mã giảm giá, điều kiện áp dụng và thời hạn khuyến mãi |
| 12 | `danhgia` | Table | Lưu điểm xếp hạng (sao) và nội dung phản hồi của khách hàng về sản phẩm |
| 13 | `yeuthich` | Table | Danh sách sản phẩm yêu thích (*Wishlist*) của từng khách hàng |
| 14 | `tintuc` | Table | Bài viết cẩm nang tập luyện, dinh dưỡng và tin tức sự kiện |


---

## Cấu trúc thư mục dự án

```text
QLDAPM_Nhom04/
├── app/
│   ├── config/          # Cấu hình kết nối CSDL (PDO) và hằng số hệ thống
│   ├── controllers/     # Xử lý luồng điều khiển và nghiệp vụ (Admin, User, Product, BMI...)
│   ├── models/          # Tương tác trực tiếp với CSDL gymshop và thuật toán gợi ý
│   └── views/           # Giao diện người dùng (Client) và trang quản trị (Admin Dashboard)
├── public/
│   ├── css/             # Các tệp định dạng giao diện
│   ├── js/              # Xử lý tương tác phía Client, Ajax, tính toán BMI nhanh
│   └── images/          # Hình ảnh sản phẩm, banner, thương hiệu
├── database/
│   └── gymshop.sql      # File khởi tạo cấu trúc bảng, dữ liệu mẫu và ma trận gợi ý
├── docs/                # Tài liệu báo cáo phạm vi, WBS, Use Case, ERD & Hướng dẫn sử dụng
├── index.php            # Điểm khởi đầu (Entry point) và điều hướng (Router) của ứng dụng
└── README.md            # Tài liệu giới thiệu và hướng dẫn dự án
```

---

## Hướng dẫn cài đặt

Thực hiện theo các bước dưới đây để chạy dự án trên môi trường máy cá nhân (**Localhost / XAMPP**):

### Bước 1: Chuẩn bị môi trường
* Cài đặt [XAMPP](https://www.apachefriends.org/) (hỗ trợ PHP $\ge 8.0$ và MySQL/MariaDB).
* Cài đặt [Git](https://git-scm.com/).

### Bước 2: Clone mã nguồn về máy
Mở Terminal / Git Bash tại thư mục `htdocs` của XAMPP (thường là `C:\xampp\htdocs\`) và chạy lệnh:

```bash
git clone https://github.com/phongpho/QLDAPM_Nhom04.git
```

### Bước 3: Khởi tạo Cơ sở dữ liệu
1. Mở **XAMPP Control Panel**, khởi động hai dịch vụ **Apache** và **MySQL**.
2. Truy cập [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
3. Tạo một cơ sở dữ liệu mới với tên **`gymbro`** (chọn bảng mã `utf8mb4_unicode_ci`).
4. Chọn tab **Import** (Nhập), tải lên file `gymshop.sql` trong thư mục `database/` của dự án và nhấn **Go** để tạo toàn bộ 14 bảng và 2 view.

### Bước 4: Cấu hình kết nối Database
Kiểm tra tệp cấu hình kết nối cơ sở dữ liệu trong dự án (ví dụ: `app/config/database.php`) và đảm bảo các thông số khớp với môi trường máy của bạn:

```php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'gymshop');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Bước 5: Trải nghiệm ứng dụng
Mở trình duyệt web và truy cập vào đường dẫn:
* **Trang khách hàng (Client):** `http://localhost/QLDAPM_Nhom04/`
* **Trang quản trị (Admin):** `http://localhost/QLDAPM_Nhom04/admin`

---

##  Quy trình phát triển (WBS)

Dự án được triển khai bài bản qua 6 giai đoạn theo Cấu trúc phân chia công việc (Work Breakdown Structure):
1. **Phân tích yêu cầu:** Khảo sát các hệ thống lớn (Gymshark, Nike, MyProtein, Wheystore...), chuẩn hóa công thức $\text{BMI}$, bảng size và đặc tả thuật toán gợi ý.
2. **Phân tích hệ thống:** Xác định yêu cầu chức năng/phi chức năng, mô hình hóa Use Case, DFD và ERD.
3. **Thiết kế hệ thống:** Thiết kế kiến trúc MVC, giao diện UI/UX, cơ sở dữ liệu `gymshop` và ma trận gợi ý.
4. **Cài đặt & Lập trình:** Xây dựng khung MVC với PDO, phát triển toàn bộ 7 module chức năng và tích hợp cổng thanh toán Sandbox.
5. **Kiểm thử & Báo cáo:** Kiểm thử End-to-End, kiểm thử từng module, độ chính xác của thuật toán cá nhân hóa, hiệu năng và bảo mật dữ liệu sức khỏe.
6. **Triển khai & Bảo trì:** Nghiệm thu trên môi trường XAMPP, biên soạn tài liệu hướng dẫn sử dụng và bàn giao hệ thống.

---

##  Thành viên nhóm (Nhóm 04)

Dự án được thực hiện bởi tập thể **Nhóm 04**:

| STT | Họ và Tên | Vai trò |
| :-: | :--- | :--- |
| 1 | **Phó Bảo Phong** | Trưởng nhóm  |
| 2 | **Thái Vĩnh Nghi** | Thành viên  |
| 3 | **Huỳnh Minh Thuận** | Thành viên  |
| 4 | **Tôn Anh Quân** | Thành viên |

---

<div align="center">
  <i>Dự án được xây dựng phục vụ mục đích học tập và nghiên cứu môn Quản lý Dự án Phần mềm.</i><br>
  <b>© 2026 Nhóm 04 - GymBro Project. All Rights Reserved.</b>
</div>
