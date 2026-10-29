# HƯỚNG DẪN HIỂN THỊ SẢN PHẨM GỢI Ý THEO CHỈ SỐ BMI (DÀNH CHO CLIENT / STOREFRONT)
> **Tài liệu bàn giao kỹ thuật dành cho Developer & AI Agent phát triển giao diện Khách hàng**  
> *Được biên soạn dựa trên cấu trúc CSDL và Module Admin [admin/qlchisoBMI](file:///d:/xampp/htdocs/QLDAPM_Nhom04/admin/qlchisoBMI/index.php)*

---

## 1. MỤC TIÊU NGHIỆP VỤ (BUSINESS REQUIREMENT)

Khi khách hàng truy cập website (đặc biệt là Trang chủ `public/main.php` hoặc Trang tài khoản `public/taikhoan.php`):
1. **Khách đã đăng nhập VÀ ĐÃ NHẬP chỉ số thể trạng** (đã có dữ liệu trong bảng `hosothetrang`):
   - Hệ thống tự động xác định mốc BMI tương ứng đã được Quản trị viên cấu hình trong bảng `cauhinh_bmi`.
   - Lấy danh sách sản phẩm được Admin gán riêng cho thể trạng đó từ bảng `cauhinh_bmi_sanpham`.
   - Hiển thị danh sách này trong một section riêng có tiêu đề: **"Một số sản phẩm phù hợp với bạn"** kèm thông tin tóm tắt thể trạng (BMI, phân loại, lời khuyên).
2. **QUY TẮC BẮT BUỘC**:
   - Nếu khách **chưa đăng nhập** $\rightarrow$ **TUYỆT ĐỐI KHÔNG hiển thị** section này.
   - Nếu khách **đã đăng nhập nhưng CHƯA nhập chỉ số cơ thể** (chưa có dòng trong `hosothetrang`) $\rightarrow$ **TUYỆT ĐỐI KHÔNG hiển thị** section này.
   - Chỉ hiển thị các sản phẩm có trạng thái đang bán (`sanpham.trangthai = 1`) và thuộc danh mục đang mở (`danhmuc.trangthai = 1`).

---

## 2. KIẾN TRÚC CƠ SỞ DỮ LIỆU & QUAN HỆ BẢNG

```
[nguoidung] (id, hoten, loai, trangthai)
     │
     └── (1 - 1) ──> [hosothetrang] (id, nguoidung_id, chieucao, cannang, gioitinh, tuoi, muctieu, bmi)
                          │
                          │ (Dựa vào giá trị bmi nằm trong khoảng [bmi_min, bmi_max])
                          ▼
                     [cauhinh_bmi] (id, phanloai, bmi_min, bmi_max, muctieu_khuyendung, khuyenghe, trangthai, thutu)
                          │
                          └── (1 - N) ──> [cauhinh_bmi_sanpham] (cauhinh_bmi_id, sanpham_id, uutien)
                                               │
                                               └── (N - 1) ──> [sanpham] (id, tensanpham, giaban, hinhanh, trangthai)
                                                                    │
                                                                    └──> [danhmuc] (id, tendanhmuc, loai, trangthai)
```

### Các bảng dữ liệu chính cần quan tâm:
1. **`hosothetrang`**:
   - Lưu thông tin thể hình người dùng: `chieucao` (cm), `cannang` (kg), `gioitinh` ('nam'/'nu'), `muctieu` ('giamcan'/'tangcan'/'duytri'), `bmi` (thập phân).
   - Truy vấn kiểm tra: `SELECT * FROM hosothetrang WHERE nguoidung_id = :uid LIMIT 1`.
2. **`cauhinh_bmi`**:
   - Do Quản trị viên thiết lập dải đo, trạng thái và lời khuyên.
   - Cột `trangthai = 1`: Mốc cấu hình đang có hiệu lực.
3. **`cauhinh_bmi_sanpham`**:
   - Bảng liên kết chứa danh sách `sanpham_id` mà Admin đã tích chọn cho từng mốc BMI.

---

## 3. CÁC HÀM MODEL CÓ SẴN (TÁI SỬ DỤNG - KHÔNG CẦN VIẾT LẠI)

Hệ thống đã xây dựng sẵn toàn bộ các hàm nghiệp vụ trong [model/bmi.php](file:///d:/xampp/htdocs/QLDAPM_Nhom04/model/bmi.php) và [model/sanpham.php](file:///d:/xampp/htdocs/QLDAPM_Nhom04/model/sanpham.php):

### 3.1. Trong `model/sanpham.php`:
```php
// Kiểm tra hồ sơ thể trạng của người dùng
$hoso = $spModel->layhosothetrang($nguoidung_id);
// Trả về: array chứa chieucao, cannang, bmi, muctieu... HOẶC false/null nếu chưa nhập
```

### 3.2. Trong `model/bmi.php`:
```php
require_once(__DIR__ . '/../../model/bmi.php'); // hoặc đường dẫn tương đối tới model/bmi.php
$bmiModel = new BMI();

// 1. Tìm mốc cấu hình phù hợp với chỉ số BMI
$cauhinh = $bmiModel->laycauhinhchiso($bmi);
// Trả về: array dòng cauhinh_bmi (id, phanloai, khuyenghe, muctieu_khuyendung...)

// 2. Lấy danh sách chi tiết các sản phẩm gợi ý do Admin đã gán (đã tự động lọc trangthai = 1)
$dsSanPhamGoiy = $bmiModel->laysanphamchitietbycauhinh($cauhinh['id'], 8); // lấy tối đa 8 sản phẩm
```

---

## 4. HƯỚNG DẪN TRIỂN KHAI CODE CHI TIẾT

### Bước 1: Viết logic lấy dữ liệu (Controller hoặc file View chính)

Tại file điều hướng hiển thị (ví dụ `public/index.php` hoặc đầu file `public/main.php`):

```php
<?php
// Khởi tạo biến gợi ý mặc định là null
$dsSanPhamBmiGoiy = null;
$thongTinBmiKhach = null;

// Kiểm tra: 1. Khách đã đăng nhập chưa?
$nguoidungId = isset($_SESSION['nguoidung']['id']) ? (int)$_SESSION['nguoidung']['id'] : 0;

if ($nguoidungId > 0) {
    // 2. Khách đã có hồ sơ thể trạng chưa?
    require_once(__DIR__ . '/../model/sanpham.php');
    $spModel = new SANPHAM();
    $hosoThetrang = $spModel->layhosothetrang($nguoidungId);

    // CHỈ XỬ LÝ KHI ĐÃ CÓ HỒ SƠ THỂ TRẠNG
    if (!empty($hosoThetrang) && !empty($hosoThetrang['bmi'])) {
        require_once(__DIR__ . '/../model/bmi.php');
        $bmiModel = new BMI();

        $bmiVal = (float)$hosoThetrang['bmi'];
        $cauhinhBmi = $bmiModel->laycauhinhchiso($bmiVal);

        if ($cauhinhBmi && (int)$cauhinhBmi['trangthai'] === 1) {
            // Lấy danh sách sản phẩm gợi ý do Admin gán cho mốc BMI này
            $sanphamList = $bmiModel->laysanphamchitietbycauhinh($cauhinhBmi['id'], 8);

            if (!empty($sanphamList)) {
                $thongTinBmiKhach = [
                    'hoso'      => $hosoThetrang,
                    'cauhinh'   => $cauhinhBmi,
                    'bmi'       => $bmiVal,
                    'phanloai'  => $cauhinhBmi['phanloai'],
                    'khuyenghe' => $cauhinhBmi['khuyenghe']
                ];
                $dsSanPhamBmiGoiy = $sanphamList;
            }
        }
    }
}
?>
```

---

### Bước 2: Hiển thị Section "Một số sản phẩm phù hợp với bạn"

Tại giao diện trang chủ `public/main.php` (đặt phía dưới Banner / Danh mục hoặc vị trí mong muốn):

```php
<!-- ================================================================= -->
<!-- SECTION: MỘT SỐ SẢN PHẨM PHÙ HỢP VỚI BẠN (GỢI Ý THEO CHỈ SỐ BMI)  -->
<!-- ĐIỀU KIỆN: CHỈ HIỂN THỊ KHI ĐÃ ĐĂNG NHẬP VÀ ĐÃ NHẬP FORM THỂ TRẠNG -->
<!-- ================================================================= -->
<?php if (!empty($thongTinBmiKhach) && !empty($dsSanPhamBmiGoiy)): ?>
<section class="gs-section gs-section--bmi-recommended py-5" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
    <div class="container">
        
        <!-- Header của Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge" style="background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 0.8rem; font-weight: 700;">
                        <i class="fa-solid fa-heart-pulse me-1"></i> Gợi ý theo chỉ số BMI
                    </span>
                    <span class="badge bg-light text-secondary border">
                        Thể trạng: <strong><?= htmlspecialchars($thongTinBmiKhach['phanloai']) ?></strong> (BMI: <?= number_format($thongTinBmiKhach['bmi'], 1) ?>)
                    </span>
                </div>
                <h2 class="gs-section__title mb-1" style="font-size: 1.6rem; font-weight: 800; color: #0f172a;">
                    Một số sản phẩm phù hợp với bạn
                </h2>
                <p class="text-muted small mb-0">
                    <?php if (!empty($thongTinBmiKhach['khuyenghe'])): ?>
                        <i class="fa-solid fa-lightbulb text-warning me-1"></i>
                        <?= htmlspecialchars($thongTinBmiKhach['khuyenghe']) ?>
                    <?php else: ?>
                        Các trang phục tập gym, phụ kiện và thực phẩm bổ sung được tuyển chọn phù hợp nhất với thể trạng của bạn.
                    <?php endif; ?>
                </p>
            </div>
            <div>
                <a href="taikhoan.php#thetrang" class="btn btn-sm btn-outline-primary" style="font-weight: 600; border-radius: 8px;">
                    <i class="fa-solid fa-sliders me-1"></i> Cập nhật chỉ số cơ thể
                </a>
            </div>
        </div>

        <!-- Danh sách thẻ sản phẩm gợi ý -->
        <div class="row g-4">
            <?php foreach ($dsSanPhamBmiGoiy as $card): ?>
                <?php
                // Tái sử dụng thẻ sản phẩm chuẩn của Storefront
                include(__DIR__ . '/inc/product-card.php'); 
                ?>
            <?php endforeach; ?>
        </div>

    </div>
</section>
<?php endif; ?>
```

---

## 5. CÁC QUY TẮC BẢO TOÀN HỆ THỐNG (AGENT GUIDELINES)

Khi thực hiện task này, AI Agent hoặc Lập trình viên **BẮT BUỘC** tuân thủ các quy định sau:

1. **Tuyệt đối không sửa logic trong `admin/`**:
   - Quản trị viên là người toàn quyền cấu hình mốc BMI và gán sản phẩm thông qua module `admin/qlchisoBMI`.
   - Storefront chỉ đọc dữ liệu từ CSDL (`SELECT`), không làm thay đổi cấu hình mốc BMI.
2. **Quy tắc hiển thị nghiêm ngặt**:
   - Nếu `!isset($_SESSION['nguoidung'])` $\rightarrow$ KHÔNG render thẻ HTML của section này.
   - Nếu đã đăng nhập nhưng `$hosoThetrang == null` $\rightarrow$ KHÔNG render thẻ HTML của section này (không để lại khoảng trắng, không hiển thị form chèn giữa trang chủ).
3. **Quy tắc đồng bộ trạng thái Ẩn/Hiện (`trangthai`)**:
   - Chỉ hiển thị sản phẩm khi `sanpham.trangthai = 1` VÀ `danhmuc.trangthai = 1`. Hàm `BMI::laysanphamchitietbycauhinh()` trong [model/bmi.php](file:///d:/xampp/htdocs/QLDAPM_Nhom04/model/bmi.php) đã tích hợp sẵn điều kiện này, hãy ưu tiên gọi hàm này.
4. **Trường hợp một mốc BMI chưa được Admin gán sản phẩm**:
   - Nếu mốc BMI đó có `$sanphamList` rỗng (`count == 0`), section cũng sẽ tự động ẩn để tránh hiển thị khung trống gây ảnh hưởng trải nghiệm người dùng.

---

## 6. DANH SÁCH FILE LIÊN QUAN

| File | Vai trò | Trạng thái |
| :--- | :--- | :--- |
| [model/bmi.php](file:///d:/xampp/htdocs/QLDAPM_Nhom04/model/bmi.php) | Chứa các hàm nghiệp vụ: `laycauhinhchiso()`, `laysanphamchitietbycauhinh()` | Đã hoàn thiện & kiểm tra |
| [model/sanpham.php](file:///d:/xampp/htdocs/QLDAPM_Nhom04/model/sanpham.php) | Chứa hàm `layhosothetrang($nguoidung_id)` | Đã có sẵn |
| [public/main.php](file:///d:/xampp/htdocs/QLDAPM_Nhom04/public/main.php) | Trang chủ người dùng - Nơi đặt section gợi ý | Cần chèn section |
| [public/inc/product-card.php](file:///d:/xampp/htdocs/QLDAPM_Nhom04/public/inc/product-card.php) | Component thẻ hiển thị 1 sản phẩm | Tái sử dụng |
| [public/taikhoan.php](file:///d:/xampp/htdocs/QLDAPM_Nhom04/public/taikhoan.php) | Nơi khách hàng cập nhật form chỉ số thể hình | Đã có sẵn |
