<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra quyền Admin (loai == 1)
if (!isset($_SESSION["nguoidung"]) || (int)$_SESSION["nguoidung"]["loai"] !== 1) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cập nhật mốc BMI & Gợi ý đồ dùng - GymBro Admin</title>

    <!-- Google Fonts: Plus Jakarta Sans for modern administrative craft -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- AdminKit base -->
    <link href="../inc/css/app.css" rel="stylesheet">
    <script src="../inc/js/app.js"></script>

    <!-- Bootstrap 5 & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- Unified Admin Design System -->
    <link href="../inc/css/main.css" rel="stylesheet">

    <style>
        .product-picker-card {
            border: 1px solid var(--gb-slate-200);
            border-radius: var(--gb-radius-md);
            padding: 10px 12px;
            transition: var(--gb-transition);
            cursor: pointer;
            background-color: var(--gb-white);
            height: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            user-select: none;
            position: relative;
        }
        .product-picker-card:hover {
            border-color: var(--gb-primary);
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: var(--gb-shadow-sm);
        }
        .product-picker-card.is-selected {
            border-color: var(--gb-primary);
            background-color: var(--gb-primary-subtle);
            box-shadow: 0 0 0 1px var(--gb-primary);
        }
        .product-picker-card img {
            width: 48px;
            height: 48px;
            border-radius: var(--gb-radius-sm);
            object-fit: cover;
            flex-shrink: 0;
            background-color: #f1f5f9;
            border: 1px solid var(--gb-slate-200);
        }
        .nav-pills .nav-link {
            color: var(--gb-slate-600);
            border: 1px solid var(--gb-slate-200);
            border-radius: var(--gb-radius-md);
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.85rem;
            background-color: var(--gb-white);
            transition: var(--gb-transition);
        }
        .nav-pills .nav-link:hover {
            border-color: var(--gb-primary);
            color: var(--gb-primary);
            background-color: #f8fafc;
        }
        .nav-pills .nav-link.active {
            background-color: var(--gb-primary) !important;
            border-color: var(--gb-primary) !important;
            color: var(--gb-white) !important;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include('../inc/sidebar.php'); ?>

        <div class="main">
            <?php include('../inc/header.php'); ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <!-- Breadcrumbs & Navigation -->
                    <div class="gb-breadcrumb justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <a href="index.php"><i class="fa fa-ruler-combined me-1"></i>Cấu hình chỉ số BMI</a>
                            <i class="fa fa-chevron-right" style="font-size: 0.65rem;"></i>
                            <span class="text-dark fw-semibold">Cập nhật: <?= htmlspecialchars($cauhinh['phanloai']) ?></span>
                        </div>
                        <span class="badge bg-purple-subtle text-purple border border-purple-subtle" title="Khu vực quản trị cấp cao">
                            <i class="fa fa-shield-halved me-1"></i> Quản trị viên
                        </span>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-12 col-xl-11">

                            <!-- Flash Alert if error -->
                            <?php if (isset($_SESSION["loi"])): ?>
                                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
                                    <i class="fa fa-triangle-exclamation fs-5"></i>
                                    <div><strong>Lỗi:</strong> <?= htmlspecialchars($_SESSION["loi"]) ?></div>
                                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php unset($_SESSION["loi"]); ?>
                            <?php endif; ?>

                            <!-- Edit Card Wrapper -->
                            <div class="gb-card mb-4">
                                <div class="gb-card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                                    <div>
                                        <h2 class="gb-title mb-1" style="font-size: 1.25rem;">Cập nhật mốc BMI & Gợi ý đồ dùng</h2>
                                        <p class="gb-subtitle mb-0">Chỉnh sửa dải đo chỉ số BMI, định hướng mục tiêu và gán đồ dùng / sản phẩm tập luyện tương ứng</p>
                                    </div>
                                    <a href="index.php" class="btn btn-sm btn-light border text-secondary fw-semibold">
                                        <i class="fa fa-arrow-left me-1"></i> Quay lại danh sách
                                    </a>
                                </div>

                                <div class="p-4">
                                    <!-- Summary Box mốc hiện tại -->
                                    <?php
                                    $badgeInfo = BMI::layBadgeMuctieu($cauhinh["muctieu_khuyendung"] ?? "duytri");
                                    ?>
                                    <div class="gb-summary-box d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="gb-icon-box" style="width: 48px; height: 48px; background-color: #eff6ff; color: #2563eb; border-radius: 12px; font-size: 1.35rem;">
                                                <i class="fa fa-weight-scale"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                                                    <span><?= htmlspecialchars($cauhinh['phanloai']) ?></span>
                                                    <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.775rem;">
                                                        BMI: <?= number_format((float)$cauhinh['bmi_min'], 1) ?> - <?= number_format((float)$cauhinh['bmi_max'], 1) ?>
                                                    </span>
                                                </div>
                                                <div class="text-muted small mt-1">
                                                    Mục tiêu áp dụng: 
                                                    <span class="<?= $badgeInfo['class'] ?> px-2 py-0.5 ms-1">
                                                        <i class="fa <?= $badgeInfo['icon'] ?> me-1"></i><?= $badgeInfo['ten'] ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-6" id="badgeSelectedCount">
                                                <i class="fa fa-box-open me-1"></i>Đã chọn: <?= count($selectedSpIds) ?> sản phẩm gợi ý
                                            </span>
                                        </div>
                                    </div>

                                    <form action="index.php" method="POST" id="formSuaBmi">
                                        <input type="hidden" name="action" value="xlsua">
                                        <input type="hidden" name="txtid" value="<?= (int)$cauhinh['id'] ?>">

                                        <!-- PHẦN 1: THÔNG TIN MỐC BMI & LỜI KHUYÊN -->
                                        <div class="border rounded-3 p-3 p-md-4 mb-4" style="background-color: #f8fafc;">
                                            <h6 class="fw-bold text-dark mb-3 d-flex align-items-center">
                                                <i class="fa fa-sliders text-primary me-2"></i>1. Thông tin dải đo BMI & Mục tiêu thể trạng
                                            </h6>
                                            <div class="row g-3">
                                                <div class="col-md-5">
                                                    <label class="form-label">
                                                        Tên phân loại thể trạng <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="txtphanloai" class="form-control"
                                                           value="<?= htmlspecialchars($cauhinh['phanloai']) ?>" required
                                                           placeholder="Ví dụ: Thiếu cân (Gầy), Cân đối, Thừa cân...">
                                                    <div class="form-text">Tên phân loại hiển thị trên trang cá nhân của người dùng.</div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">
                                                        BMI tối thiểu (Min) <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="number" step="0.1" name="txtmin" class="form-control"
                                                           value="<?= (float)$cauhinh['bmi_min'] ?>" required>
                                                    <div class="form-text">Ngưỡng dưới.</div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">
                                                        BMI tối đa (Max) <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="number" step="0.1" name="txtmax" class="form-control"
                                                           value="<?= (float)$cauhinh['bmi_max'] ?>" required>
                                                    <div class="form-text">Ngưỡng trên.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">
                                                        Mục tiêu khuyến nghị <span class="text-danger">*</span>
                                                    </label>
                                                    <select name="optmuctieu" class="form-select" required>
                                                        <option value="tangcan" <?= ($cauhinh['muctieu_khuyendung'] === 'tangcan') ? 'selected' : '' ?>>
                                                            Tăng cân / Tăng cơ (tangcan)
                                                        </option>
                                                        <option value="duytri" <?= ($cauhinh['muctieu_khuyendung'] === 'duytri') ? 'selected' : '' ?>>
                                                            Duy trì vóc dáng (duytri)
                                                        </option>
                                                        <option value="giamcan" <?= ($cauhinh['muctieu_khuyendung'] === 'giamcan') ? 'selected' : '' ?>>
                                                            Giảm mỡ / Giảm cân (giamcan)
                                                        </option>
                                                    </select>
                                                    <div class="form-text">Định hướng chế độ luyện tập.</div>
                                                </div>

                                                <div class="col-md-8">
                                                    <label class="form-label">
                                                        Lời khuyên chuyên môn từ Huấn luyện viên & Chuyên gia dinh dưỡng
                                                    </label>
                                                    <textarea name="txtkhuyenghe" class="form-control" rows="3"
                                                              placeholder="Nhập lời khuyên hiển thị cho khách hàng khi họ đạt chỉ số BMI này..."><?= htmlspecialchars($cauhinh['khuyenghe'] ?? '') ?></textarea>
                                                    <div class="form-text">Lời khuyên này xuất hiện trực quan trên trang phân tích thể trạng của khách hàng.</div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Thứ tự hiển thị</label>
                                                    <input type="number" name="txtthutu" class="form-control"
                                                           value="<?= (int)($cauhinh['thutu'] ?? 1) ?>" min="1">
                                                    <div class="form-text">Ưu tiên sắp xếp.</div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Trạng thái áp dụng</label>
                                                    <select name="opttrangthai" class="form-select">
                                                        <option value="1" <?= ((int)$cauhinh['trangthai'] === 1) ? 'selected' : '' ?>>Kích hoạt</option>
                                                        <option value="0" <?= ((int)$cauhinh['trangthai'] === 0) ? 'selected' : '' ?>>Tạm tắt</option>
                                                    </select>
                                                    <div class="form-text">Bật/tắt gợi ý.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- PHẦN 2: CHỌN ĐỒ DÙNG / SẢN PHẨM GỢI Ý PHÙ HỢP -->
                                        <div class="border rounded-3 p-3 p-md-4 mb-4 bg-white" id="secDoDung">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-1 d-flex align-items-center">
                                                        <i class="fa fa-boxes-packing text-primary me-2"></i>2. Gán các đồ dùng / sản phẩm gợi ý phù hợp
                                                    </h6>
                                                    <p class="text-muted small mb-0">
                                                        Tích chọn các sản phẩm tập gym, phụ kiện thể thao hoặc thực phẩm bổ sung phù hợp cho thể trạng này.
                                                    </p>
                                                </div>
                                                <!-- Ô tìm kiếm nhanh sản phẩm -->
                                                <div style="min-width: 280px;">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-muted"></i></span>
                                                        <input type="text" id="quickSearchSP" class="form-control bg-light border-start-0"
                                                               placeholder="Tìm nhanh sản phẩm bên dưới...">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tabs phân nhóm loại sản phẩm -->
                                            <ul class="nav nav-pills gap-2 mb-3" id="pills-tab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="tab-quanao-btn" data-bs-toggle="pill" data-bs-target="#tab-quanao" type="button" role="tab">
                                                        <i class="fa fa-shirt me-1"></i> Quần áo & Đồ tập (<?= count($dsSanPhamTheoLoai['quanao'] ?? []) ?>)
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="tab-phukien-btn" data-bs-toggle="pill" data-bs-target="#tab-phukien" type="button" role="tab">
                                                        <i class="fa fa-dumbbell me-1"></i> Phụ kiện tập Gym (<?= count($dsSanPhamTheoLoai['phukien'] ?? []) ?>)
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="tab-tpbs-btn" data-bs-toggle="pill" data-bs-target="#tab-tpbs" type="button" role="tab">
                                                        <i class="fa fa-flask me-1"></i> Thực phẩm bổ sung (<?= count($dsSanPhamTheoLoai['tpbs'] ?? []) ?>)
                                                    </button>
                                                </li>
                                            </ul>

                                            <div class="tab-content" id="pills-tabContent">
                                                <!-- TAB 1: ĐỒ TẬP (QUẦN ÁO) -->
                                                <div class="tab-pane fade show active" id="tab-quanao" role="tabpanel">
                                                    <div class="row g-2.5" id="list-quanao">
                                                        <?php if (!empty($dsSanPhamTheoLoai['quanao'])): ?>
                                                            <?php foreach ($dsSanPhamTheoLoai['quanao'] as $sp): ?>
                                                                <?php $isSelected = in_array((int)$sp['id'], array_map('intval', $selectedSpIds)); ?>
                                                                <div class="col-12 col-md-6 col-lg-4 sp-item-wrap" data-name="<?= mb_strtolower($sp['tensanpham'], 'UTF-8') ?>">
                                                                    <label class="product-picker-card <?= $isSelected ? 'is-selected' : '' ?>">
                                                                        <input type="checkbox" name="sanpham_ids[]" value="<?= (int)$sp['id'] ?>"
                                                                               class="form-check-input sp-checkbox mt-0" <?= $isSelected ? 'checked' : '' ?>>
                                                                        <?php
                                                                        $hinh = !empty($sp['hinhanh']) ? '../../assets/images/products/' . $sp['hinhanh'] : '../../assets/images/products/no-image.png';
                                                                        ?>
                                                                        <img src="<?= htmlspecialchars($hinh) ?>" alt="<?= htmlspecialchars($sp['tensanpham']) ?>"
                                                                             onerror="this.src='../../assets/images/products/no-image.png'">
                                                                        <div class="flex-grow-1 overflow-hidden">
                                                                            <div class="fw-semibold text-dark text-truncate small mb-1" title="<?= htmlspecialchars($sp['tensanpham']) ?>">
                                                                                <?= htmlspecialchars($sp['tensanpham']) ?>
                                                                            </div>
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <span class="text-danger fw-bold small"><?= number_format((float)$sp['giaban']) ?>đ</span>
                                                                                <span class="badge bg-light text-secondary border" style="font-size: 10px;"><?= htmlspecialchars($sp['doituong']) ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <div class="col-12 py-3 text-muted text-center">Chưa có sản phẩm quần áo nào đang bán.</div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <!-- TAB 2: PHỤ KIỆN -->
                                                <div class="tab-pane fade" id="tab-phukien" role="tabpanel">
                                                    <div class="row g-2.5" id="list-phukien">
                                                        <?php if (!empty($dsSanPhamTheoLoai['phukien'])): ?>
                                                            <?php foreach ($dsSanPhamTheoLoai['phukien'] as $sp): ?>
                                                                <?php $isSelected = in_array((int)$sp['id'], array_map('intval', $selectedSpIds)); ?>
                                                                <div class="col-12 col-md-6 col-lg-4 sp-item-wrap" data-name="<?= mb_strtolower($sp['tensanpham'], 'UTF-8') ?>">
                                                                    <label class="product-picker-card <?= $isSelected ? 'is-selected' : '' ?>">
                                                                        <input type="checkbox" name="sanpham_ids[]" value="<?= (int)$sp['id'] ?>"
                                                                               class="form-check-input sp-checkbox mt-0" <?= $isSelected ? 'checked' : '' ?>>
                                                                        <?php
                                                                        $hinh = !empty($sp['hinhanh']) ? '../../assets/images/products/' . $sp['hinhanh'] : '../../assets/images/products/no-image.png';
                                                                        ?>
                                                                        <img src="<?= htmlspecialchars($hinh) ?>" alt="<?= htmlspecialchars($sp['tensanpham']) ?>"
                                                                             onerror="this.src='../../assets/images/products/no-image.png'">
                                                                        <div class="flex-grow-1 overflow-hidden">
                                                                            <div class="fw-semibold text-dark text-truncate small mb-1" title="<?= htmlspecialchars($sp['tensanpham']) ?>">
                                                                                <?= htmlspecialchars($sp['tensanpham']) ?>
                                                                            </div>
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <span class="text-danger fw-bold small"><?= number_format((float)$sp['giaban']) ?>đ</span>
                                                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 10px;">Phụ kiện</span>
                                                                            </div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <div class="col-12 py-3 text-muted text-center">Chưa có phụ kiện tập gym nào đang bán.</div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <!-- TAB 3: THỰC PHẨM BỔ SUNG -->
                                                <div class="tab-pane fade" id="tab-tpbs" role="tabpanel">
                                                    <div class="row g-2.5" id="list-tpbs">
                                                        <?php if (!empty($dsSanPhamTheoLoai['tpbs'])): ?>
                                                            <?php foreach ($dsSanPhamTheoLoai['tpbs'] as $sp): ?>
                                                                <?php $isSelected = in_array((int)$sp['id'], array_map('intval', $selectedSpIds)); ?>
                                                                <div class="col-12 col-md-6 col-lg-4 sp-item-wrap" data-name="<?= mb_strtolower($sp['tensanpham'], 'UTF-8') ?>">
                                                                    <label class="product-picker-card <?= $isSelected ? 'is-selected' : '' ?>">
                                                                        <input type="checkbox" name="sanpham_ids[]" value="<?= (int)$sp['id'] ?>"
                                                                               class="form-check-input sp-checkbox mt-0" <?= $isSelected ? 'checked' : '' ?>>
                                                                        <?php
                                                                        $hinh = !empty($sp['hinhanh']) ? '../../assets/images/products/' . $sp['hinhanh'] : '../../assets/images/products/no-image.png';
                                                                        ?>
                                                                        <img src="<?= htmlspecialchars($hinh) ?>" alt="<?= htmlspecialchars($sp['tensanpham']) ?>"
                                                                             onerror="this.src='../../assets/images/products/no-image.png'">
                                                                        <div class="flex-grow-1 overflow-hidden">
                                                                            <div class="fw-semibold text-dark text-truncate small mb-1" title="<?= htmlspecialchars($sp['tensanpham']) ?>">
                                                                                <?= htmlspecialchars($sp['tensanpham']) ?>
                                                                            </div>
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <span class="text-danger fw-bold small"><?= number_format((float)$sp['giaban']) ?>đ</span>
                                                                                <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 10px;">TPBS</span>
                                                                            </div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <div class="col-12 py-3 text-muted text-center">Chưa có thực phẩm bổ sung nào đang bán.</div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- THANH HÀNH ĐỘNG DƯỚI CÙNG -->
                                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 pt-3 border-top">
                                            <a href="index.php" class="btn btn-light px-4 border text-secondary fw-semibold">
                                                <i class="fa fa-arrow-left me-1"></i> Hủy & Quay lại
                                            </a>
                                            <button type="submit" class="btn btn-gb-primary px-5 shadow-sm">
                                                <i class="fa fa-save me-1"></i> Lưu cấu hình & Đồ dùng gợi ý
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.sp-checkbox');
            const badgeCount = document.getElementById('badgeSelectedCount');
            const searchInput = document.getElementById('quickSearchSP');

            function updateCount() {
                const checked = document.querySelectorAll('.sp-checkbox:checked').length;
                if (badgeCount) {
                    badgeCount.innerHTML = '<i class="fa fa-box-open me-1"></i>Đã chọn: ' + checked + ' sản phẩm gợi ý';
                }
            }

            checkboxes.forEach(function (cb) {
                cb.addEventListener('change', function () {
                    const card = this.closest('.product-picker-card');
                    if (this.checked) {
                        card.classList.add('is-selected');
                    } else {
                        card.classList.remove('is-selected');
                    }
                    updateCount();
                });
            });

            // Lọc nhanh tên sản phẩm theo text nhập vào
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const query = this.value.trim().toLowerCase();
                    const items = document.querySelectorAll('.sp-item-wrap');
                    items.forEach(function (item) {
                        const name = item.getAttribute('data-name') || '';
                        if (query === '' || name.includes(query)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>
