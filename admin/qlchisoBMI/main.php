<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cấu hình chỉ số BMI & Gợi ý đồ dùng - GymBro Admin</title>

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
        .bmi-scale-bar {
            height: 12px;
            border-radius: 9999px;
            overflow: hidden;
            display: flex;
            background-color: #f1f5f9;
        }
        .bmi-scale-seg {
            height: 100%;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .bmi-scale-seg:hover {
            opacity: 0.85;
            filter: brightness(1.05);
        }
        .avatar-group {
            display: inline-flex;
            align-items: center;
        }
        .avatar-group-item {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            object-fit: cover;
            margin-left: -8px;
            background-color: #f8fafc;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.12);
            transition: transform 0.2s ease;
        }
        .avatar-group-item:first-child {
            margin-left: 0;
        }
        .avatar-group-item:hover {
            transform: scale(1.15);
            z-index: 2;
        }
        .avatar-group-more {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            background-color: #e2e8f0;
            color: #334155;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: -8px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.12);
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

                    <!-- Header & Tiêu đề -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h1 class="gb-title mb-0">Cấu hình chỉ số BMI</h1>
                                <span class="badge bg-purple-subtle text-purple border border-purple-subtle" title="Khu vực cấu hình chỉ dành riêng cho Quản trị viên cấp cao">
                                    <i class="fa fa-shield-halved me-1"></i> Quản trị viên
                                </span>
                            </div>
                            <p class="gb-subtitle mb-0">
                                Thiết lập các khoảng chỉ số thể trạng BMI, định hướng mục tiêu và cấu hình gợi ý sản phẩm thể thao phù hợp cho khách hàng.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-gb-primary shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#formThemMoc" aria-expanded="false" aria-controls="formThemMoc">
                                <i class="fa fa-plus-circle me-1"></i> Thêm mốc BMI mới
                            </button>
                        </div>
                    </div>

                    <!-- Thống kê nhanh theo Card Metric -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Tổng mốc cấu hình</div>
                                        <div class="gb-stat-number"><?= $tongMoc ?></div>
                                        <div class="text-muted small mt-1">Khoảng thể trạng BMI</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #eff6ff; color: #2563eb;">
                                        <i class="fa fa-ruler-combined"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Đang hoạt động</div>
                                        <div class="gb-stat-number text-success"><?= $demKichHoat ?></div>
                                        <div class="text-muted small mt-1">Áp dụng gợi ý ngay</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #ecfdf5; color: #059669;">
                                        <i class="fa fa-circle-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Sản phẩm gợi ý đã gán</div>
                                        <div class="gb-stat-number text-primary"><?= $tongDoDungGoiy ?></div>
                                        <div class="text-muted small mt-1">Liên kết theo thể trạng</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #fff7ed; color: #ea580c;">
                                        <i class="fa fa-box-open"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="gb-stat-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="gb-stat-label">Quyền quản trị</div>
                                        <div class="gb-stat-number text-purple" style="font-size: 1.15rem; font-weight: 700; margin-top: 0.5rem;">
                                            Toàn quyền
                                        </div>
                                        <div class="text-muted small mt-1">Chỉ dành cho Admin</div>
                                    </div>
                                    <div class="gb-icon-box" style="background-color: #f3e8ff; color: #7e22ce;">
                                        <i class="fa fa-shield-halved"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thước đo trực quan dải BMI tham chiếu -->
                    <div class="gb-card p-3 mb-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-1 mb-2">
                            <span class="fw-bold small text-dark d-flex align-items-center">
                                <i class="fa fa-chart-simple text-primary me-2"></i>Thang đo chỉ số thể trạng BMI chuẩn Châu Á (IDI & WPRO):
                            </span>
                            <span class="small text-muted d-inline-flex align-items-center gap-1.5">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.725rem; font-weight: 600;">Công thức tính</span>
                                <span class="text-dark fw-bold">BMI</span> = <span>Cân nặng (kg)</span> / <span>(Chiều cao (m))<sup>2</sup></span>
                            </span>
                        </div>
                        <div class="bmi-scale-bar mb-2">
                            <div class="bmi-scale-seg" style="width: 25%; background-color: #0284c7;" title="Thiếu cân (< 18.5)"></div>
                            <div class="bmi-scale-seg" style="width: 30%; background-color: #10b981;" title="Cân đối (18.5 - 22.9)"></div>
                            <div class="bmi-scale-seg" style="width: 25%; background-color: #f59e0b;" title="Thừa cân (23.0 - 24.9)"></div>
                            <div class="bmi-scale-seg" style="width: 20%; background-color: #ef4444;" title="Béo phì (>= 25.0)"></div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between text-muted small" style="font-size: 0.775rem;">
                            <span><i class="fa fa-circle me-1" style="color: #0284c7;"></i>Thiếu cân (&lt; 18.5)</span>
                            <span><i class="fa fa-circle me-1" style="color: #10b981;"></i>Cân đối / Chuẩn (18.5 - 22.9)</span>
                            <span><i class="fa fa-circle me-1" style="color: #f59e0b;"></i>Thừa cân (23.0 - 24.9)</span>
                            <span><i class="fa fa-circle me-1" style="color: #ef4444;"></i>Béo phì (&ge; 25.0)</span>
                        </div>
                    </div>

                    <!-- Thông báo Flash Alerts -->
                    <?php if (isset($_SESSION["thongbao"])): ?>
                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert" style="background-color: #ecfdf5; color: #065f46; border-left: 4px solid #10b981 !important;">
                            <i class="fa fa-circle-check fs-5"></i>
                            <div><strong>Thành công:</strong> <?= htmlspecialchars($_SESSION["thongbao"]) ?></div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION["thongbao"]); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION["loi"])): ?>
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
                            <i class="fa fa-triangle-exclamation fs-5"></i>
                            <div><strong>Cảnh báo:</strong> <?= htmlspecialchars($_SESSION["loi"]) ?></div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION["loi"]); ?>
                    <?php endif; ?>

                    <!-- Form Thêm mới mốc BMI (Dạng Collapse tinh chỉnh) -->
                    <div id="formThemMoc" class="collapse gb-card mb-4">
                        <div class="gb-card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <div class="gb-icon-box" style="width: 32px; height: 32px; font-size: 0.95rem; background-color: #eff6ff; color: #2563eb;">
                                    <i class="fa fa-plus"></i>
                                </div>
                                <h5 class="fw-bold mb-0 text-dark" style="font-size: 1rem;">Khởi tạo mốc cấu hình chỉ số BMI mới</h5>
                            </div>
                            <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="collapse" data-bs-target="#formThemMoc" aria-label="Đóng form">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <form action="index.php" method="POST" class="row g-3">
                                <input type="hidden" name="action" value="xlthem">

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Tên phân loại thể trạng <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="txtphanloai" class="form-control" placeholder="Ví dụ: Thiếu cân (Gầy), Cân đối, Thừa cân..." required>
                                    <div class="form-text">Tên phân loại xuất hiện trong hồ sơ thể trạng của khách hàng.</div>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">
                                        BMI tối thiểu (Min) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" step="0.1" name="txtmin" class="form-control" placeholder="0.0" required>
                                    <div class="form-text">Ngưỡng bắt đầu dải đo.</div>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">
                                        BMI tối đa (Max) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" step="0.1" name="txtmax" class="form-control" placeholder="18.4" required>
                                    <div class="form-text">Ngưỡng kết thúc dải đo.</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Mục tiêu khuyến nghị <span class="text-danger">*</span>
                                    </label>
                                    <select name="optmuctieu" class="form-select" required>
                                        <option value="tangcan">Tăng cân / Tăng cơ (tangcan)</option>
                                        <option value="duytri" selected>Duy trì vóc dáng (duytri)</option>
                                        <option value="giamcan">Giảm mỡ / Giảm cân (giamcan)</option>
                                    </select>
                                    <div class="form-text">Định hướng chế độ luyện tập cốt lõi.</div>
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label">
                                        Lời khuyên chuyên môn từ Huấn luyện viên & Dinh dưỡng
                                    </label>
                                    <textarea name="txtkhuyenghe" class="form-control" rows="2" placeholder="Lời khuyên về thói quen luyện tập, chế độ ăn và phụ kiện hỗ trợ cho nhóm thể trạng này..."></textarea>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Thứ tự hiển thị</label>
                                    <input type="number" name="txtthutu" class="form-control" value="<?= $tongMoc + 1 ?>" min="1">
                                    <div class="form-text">Ưu tiên sắp xếp.</div>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Trạng thái áp dụng</label>
                                    <select name="opttrangthai" class="form-select">
                                        <option value="1" selected>Kích hoạt ngay</option>
                                        <option value="0">Tạm tắt</option>
                                    </select>
                                    <div class="form-text">Bật/tắt gợi ý.</div>
                                </div>

                                <div class="col-12 pt-3 border-top d-flex justify-content-between align-items-center">
                                    <div class="small text-muted d-none d-md-block">
                                        <i class="fa fa-info-circle text-primary me-1"></i> Sau khi tạo mốc BMI, bạn có thể vào mục <strong>Sửa</strong> để chọn danh sách đồ dùng & sản phẩm gán gợi ý.
                                    </div>
                                    <div class="d-flex gap-2 ms-auto">
                                        <button type="button" class="btn btn-light px-3" data-bs-toggle="collapse" data-bs-target="#formThemMoc">
                                            Đóng lại
                                        </button>
                                        <button type="submit" class="btn btn-gb-primary px-4">
                                            <i class="fa fa-save me-1"></i> Lưu mốc BMI
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Bảng Danh sách cấu hình BMI & Đồ dùng gợi ý -->
                    <div class="gb-table-card">
                        <div class="gb-table-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="fw-bold text-dark mb-0 fs-6">Danh sách các khoảng BMI & Gợi ý đồ dùng</h5>
                                <span class="badge bg-slate-100 text-slate-700 border px-2 py-1" style="font-size: 0.75rem;">
                                    <?= count($danhsach) ?> mốc
                                </span>
                            </div>
                            <div class="small text-muted">
                                <i class="fa fa-circle-info text-primary me-1"></i>Nhấp <strong>"Chọn thêm"</strong> hoặc biểu tượng <i class="fa fa-pen text-warning mx-1"></i> để chọn đồ dùng gợi ý tương ứng
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="gb-table align-middle">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">STT</th>
                                        <th style="min-width: 170px;">Phân loại thể trạng</th>
                                        <th class="text-center" style="width: 140px;">Khoảng BMI</th>
                                        <th style="min-width: 170px;">Mục tiêu khuyến nghị</th>
                                        <th style="min-width: 250px;">Đồ dùng / Sản phẩm gợi ý</th>
                                        <th style="min-width: 230px;">Lời khuyên chuyên môn</th>
                                        <th class="text-center" style="width: 130px;">Trạng thái</th>
                                        <th class="text-center" style="width: 110px;">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($danhsach)): ?>
                                        <?php $stt = 1; foreach ($danhsach as $m): ?>
                                            <?php
                                            $badgeInfo = BMI::layBadgeMuctieu($m["muctieu_khuyendung"] ?? "duytri");
                                            $spList = $sanphamPreview[$m["id"]] ?? [];
                                            ?>
                                            <tr>
                                                <td class="text-center text-muted fw-semibold"><?= $stt++ ?></td>
                                                <td>
                                                    <span class="fw-bold text-dark d-block"><?= htmlspecialchars($m["phanloai"]) ?></span>
                                                    <span class="text-muted small">Thứ tự: <?= (int)$m["thutu"] ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark border px-2 py-1 fw-bold font-monospace" style="font-size: 0.85rem;">
                                                        <?= number_format((float)$m["bmi_min"], 1) ?> - <?= number_format((float)$m["bmi_max"], 1) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="<?= $badgeInfo['class'] ?> px-2.5 py-1">
                                                        <i class="fa <?= $badgeInfo['icon'] ?> me-1"></i><?= $badgeInfo['ten'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <!-- Đồ dùng gợi ý đã gán -->
                                                    <div class="d-flex align-items-center justify-content-between mb-1.5">
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                                            <i class="fa fa-box-open me-1"></i><?= (int)$m["soluong_sanpham"] ?> đồ dùng đã gán
                                                        </span>
                                                        <a href="index.php?action=sua&id=<?= $m['id'] ?>#secDoDung" class="small fw-semibold text-primary" title="Chỉnh sửa danh sách đồ dùng">
                                                            <i class="fa fa-plus-circle me-1"></i>Chọn thêm
                                                        </a>
                                                    </div>

                                                    <?php if (!empty($spList)): ?>
                                                        <div class="avatar-group mt-1" title="Một số sản phẩm tiêu biểu được gợi ý">
                                                            <?php foreach (array_slice($spList, 0, 4) as $sp): ?>
                                                                <?php
                                                                $img = !empty($sp['hinhanh']) ? '../../assets/images/products/' . $sp['hinhanh'] : '../../assets/images/products/no-image.png';
                                                                ?>
                                                                <img src="<?= htmlspecialchars($img) ?>"
                                                                     class="avatar-group-item"
                                                                     title="<?= htmlspecialchars($sp['tensanpham']) ?> (<?= number_format((float)$sp['giaban']) ?>đ)"
                                                                     alt="<?= htmlspecialchars($sp['tensanpham']) ?>"
                                                                     onerror="this.src='../../assets/images/products/no-image.png'">
                                                            <?php endforeach; ?>

                                                            <?php if (count($spList) > 4): ?>
                                                                <span class="avatar-group-more">+<?= count($spList) - 4 ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted small fst-italic">
                                                            <i class="fa fa-info-circle me-1"></i>Chưa chọn đồ dùng gợi ý
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <p class="small text-muted mb-0 text-truncate" style="max-width: 260px;" title="<?= htmlspecialchars($m['khuyenghe'] ?? '') ?>">
                                                        <?= htmlspecialchars($m["khuyenghe"] ?: "Chưa có lời khuyên cụ thể") ?>
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    <!-- Toggle trạng thái -->
                                                    <?php if ((int)$m["trangthai"] === 1): ?>
                                                        <a href="index.php?action=doitrangthai&id=<?= $m["id"] ?>&trangthai=0"
                                                           class="btn-toggle-status status-active"
                                                           title="Bấm để tạm ẩn mốc BMI này khỏi gợi ý">
                                                            <i class="fa fa-circle-check"></i> Hiển thị
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="index.php?action=doitrangthai&id=<?= $m["id"] ?>&trangthai=1"
                                                           class="btn-toggle-status status-inactive"
                                                           title="Bấm để kích hoạt lại gợi ý cho mốc BMI này">
                                                            <i class="fa fa-circle-xmark"></i> Tạm tắt
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                                        <a href="index.php?action=sua&id=<?= $m["id"] ?>"
                                                           class="btn-action-icon btn-action-edit"
                                                           title="Chỉnh sửa cấu hình & Gán đồ dùng gợi ý">
                                                            <i class="fa fa-pen"></i>
                                                        </a>
                                                        <a href="index.php?action=xoa&id=<?= $m["id"] ?>"
                                                           class="btn-action-icon btn-action-delete"
                                                           title="Xóa mốc BMI"
                                                           onclick="return confirm('Bạn có chắc chắn muốn xóa mốc BMI <?= htmlspecialchars($m['phanloai']) ?> khỏi hệ thống?');">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8">
                                                <div class="gb-empty-state">
                                                    <div class="gb-empty-icon">
                                                        <i class="fa fa-weight-scale"></i>
                                                    </div>
                                                    <h5 class="fw-bold text-dark">Chưa có mốc cấu hình BMI</h5>
                                                    <p class="text-muted small mb-3">
                                                        Hệ thống chưa thiết lập các khoảng chỉ số BMI để phân loại và gợi ý đồ dùng thể hình.
                                                    </p>
                                                    <button class="btn btn-gb-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#formThemMoc">
                                                        <i class="fa fa-plus-circle me-1"></i> Thêm mốc BMI đầu tiên
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer tóm tắt bảng -->
                        <div class="p-3 border-top bg-light bg-opacity-50 d-flex justify-content-between align-items-center">
                            <span class="small text-muted">
                                Hiển thị <strong><?= count($danhsach) ?></strong> mốc thể trạng BMI trong hệ thống
                            </span>
                            <span class="small text-muted d-none d-md-inline">
                                GymBro Fitness Admin · Hệ thống phân tích thể trạng & Gợi ý sản phẩm
                            </span>
                        </div>
                    </div>

                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>
</body>

</html>
