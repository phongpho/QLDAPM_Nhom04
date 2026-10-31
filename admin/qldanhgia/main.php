<?php
$title = "Quản lý đánh giá & phản hồi - GymBro Admin";
include("../inc/header.php");
?>

<div class="container-fluid p-0">
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="align-middle me-1 text-primary" data-feather="message-square"></i> Quản lý Đánh giá & Phản hồi
            </h1>
            <p class="text-muted small mb-0">Kiểm duyệt và quản lý các phản hồi, chấm điểm sản phẩm từ khách hàng</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="index.php" class="btn btn-outline-primary">
                <i class="align-middle me-1" data-feather="rotate-ccw"></i> Tải lại danh sách
            </a>
        </div>
    </div>

    <!-- Thông báo nếu có -->
    <?php if (isset($_SESSION["thongbao"])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION["thongbao"]["loai"]) ?> alert-dismissible fade show" role="alert">
            <i class="align-middle me-2" data-feather="<?= $_SESSION["thongbao"]["loai"] === 'success' ? 'check-circle' : 'alert-circle' ?>"></i>
            <?= htmlspecialchars($_SESSION["thongbao"]["noidung"]) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION["thongbao"]); ?>
    <?php endif; ?>

    <!-- KPI Metric Cards -->
    <div class="row mb-4">
        <div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title text-muted">Tổng số đánh giá</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary bg-primary-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="message-square"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-primary"><?= number_format($thongke['tong'] ?? 0) ?></h1>
                    <div class="mb-0 text-muted small">
                        <span>Từ toàn bộ khách mua hàng</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title text-muted">Điểm đánh giá TB</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-warning bg-warning-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="star"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-warning"><?= number_format($thongke['diemsao_tb'] ?? 5.0, 1) ?> <span class="fs-4 text-muted">/ 5</span></h1>
                    <div class="mb-0 text-muted small">
                        <span>Hài lòng toàn shop</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title text-muted">Đánh giá 5 sao</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-success bg-success-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="thumbs-up"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-success"><?= number_format($thongke['danhgia_5sao'] ?? 0) ?></h1>
                    <div class="mb-0 text-muted small">
                        <span>Mức hài lòng tối đa</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title text-muted">Đang tạm ẩn / Chờ duyệt</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-danger bg-danger-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="eye-off"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-danger"><?= number_format($thongke['an_chotam'] ?? 0) ?></h1>
                    <div class="mb-0 text-muted small">
                        <span>Cần kiểm duyệt nội dung</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng danh sách & Bộ lọc -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <form method="GET" action="index.php" class="row g-2 align-items-center">
                <input type="hidden" name="action" value="macdinh">
                
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i data-feather="search" class="align-middle text-muted"></i></span>
                        <input type="text" name="tukhoa" id="inputFilterLiveDg" class="form-control bg-light border-start-0" 
                               placeholder="Tìm theo tên khách, sản phẩm, nội dung..." value="<?= htmlspecialchars($_GET["tukhoa"] ?? "") ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="sao" class="form-select bg-light" onchange="this.form.submit()">
                        <option value="0">-- Tất cả số sao --</option>
                        <option value="5" <?= (isset($_GET["sao"]) && (int)$_GET["sao"] === 5) ? "selected" : "" ?>>5 sao ★★★★★</option>
                        <option value="4" <?= (isset($_GET["sao"]) && (int)$_GET["sao"] === 4) ? "selected" : "" ?>>4 sao ★★★★</option>
                        <option value="3" <?= (isset($_GET["sao"]) && (int)$_GET["sao"] === 3) ? "selected" : "" ?>>3 sao ★★★</option>
                        <option value="2" <?= (isset($_GET["sao"]) && (int)$_GET["sao"] === 2) ? "selected" : "" ?>>2 sao ★★</option>
                        <option value="1" <?= (isset($_GET["sao"]) && (int)$_GET["sao"] === 1) ? "selected" : "" ?>>1 sao ★</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="trangthai" class="form-select bg-light" onchange="this.form.submit()">
                        <option value="">-- Trạng thái --</option>
                        <option value="1" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "1") ? "selected" : "" ?>>Đã hiển thị</option>
                        <option value="0" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "0") ? "selected" : "" ?>>Đang tạm ẩn</option>
                    </select>
                </div>

                <div class="col-md-2 text-md-end">
                    <a href="index.php" class="btn btn-outline-secondary w-100">
                        <i class="align-middle me-1" data-feather="rotate-ccw"></i> Làm mới
                    </a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tableDanhGia">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th style="width: 220px;">Khách hàng</th>
                            <th style="width: 260px;">Sản phẩm</th>
                            <th style="width: 140px;">Số sao</th>
                            <th>Nội dung nhận xét</th>
                            <th class="text-center" style="width: 130px;">Trạng thái</th>
                            <th class="text-center" style="width: 110px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($danhgias)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i data-feather="message-circle" style="width: 48px; height: 48px; stroke-width: 1.5;" class="mb-2"></i>
                                    <p class="mb-0">Chưa có phản hồi, đánh giá nào phù hợp với bộ lọc.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $stt = 1; foreach ($danhgias as $item): ?>
                                <tr>
                                    <td class="text-center fw-bold text-muted"><?= $stt++ ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php 
                                            $avatar = !empty($item["anh_khachhang"]) ? '../../assets/images/users/' . $item["anh_khachhang"] : '../../assets/images/users/user.png';
                                            ?>
                                            <img src="<?= $avatar ?>" alt="<?= htmlspecialchars($item["ten_khachhang"] ?? 'Khách') ?>" 
                                                 class="rounded-circle me-2 border shadow-xs" style="width: 38px; height: 38px; object-fit: cover;">
                                            <div>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($item["ten_khachhang"] ?? 'Khách vãng lai') ?></div>
                                                <div class="text-muted small"><?= htmlspecialchars($item["sdt_khachhang"] ?? $item["email_khachhang"] ?? '') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php 
                                            $imgSp = !empty($item["anh_sanpham"]) ? '../../assets/images/products/' . $item["anh_sanpham"] : '../../assets/images/products/product.png';
                                            ?>
                                            <img src="<?= $imgSp ?>" alt="<?= htmlspecialchars($item["tensanpham"] ?? '') ?>" 
                                                 class="rounded border me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            <div class="text-truncate" style="max-width: 190px;">
                                                <a href="../qlsanpham/index.php?action=sua&id=<?= $item['sanpham_id'] ?>" 
                                                   class="text-decoration-none fw-semibold text-dark small" title="<?= htmlspecialchars($item["tensanpham"] ?? '') ?>">
                                                    <?= htmlspecialchars($item["tensanpham"] ?? 'Sản phẩm đã xóa') ?>
                                                </a>
                                                <div class="text-muted small"><?= number_format((float)($item["giaban"] ?? 0), 0, ',', '.') ?>đ</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-warning">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="align-middle" data-feather="star" 
                                                   style="width: 15px; height: 15px; fill: <?= $i <= (int)$item['sao'] ? '#f59e0b' : 'none' ?>; color: <?= $i <= (int)$item['sao'] ? '#f59e0b' : '#cbd5e1' ?>;"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <div class="text-muted small mt-1">
                                            <?= date('d/m/Y H:i', strtotime($item["ngay"])) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="bg-light p-2 rounded small text-dark border">
                                            <?= nl2br(htmlspecialchars($item["noidung"] ?? '(Không có lời bình luận)')) ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if ((int)$item["trangthai"] === 1): ?>
                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=1" 
                                               class="badge bg-success-subtle text-success text-decoration-none px-2 py-1 rounded"
                                               title="Bấm để ẩn đánh giá">
                                                <i class="align-middle me-1" data-feather="check" style="width: 12px; height: 12px;"></i> Đã hiển thị
                                            </a>
                                        <?php else: ?>
                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=0" 
                                               class="badge bg-secondary-subtle text-secondary text-decoration-none px-2 py-1 rounded"
                                               title="Bấm để duyệt hiển thị">
                                                <i class="align-middle me-1" data-feather="eye-off" style="width: 12px; height: 12px;"></i> Tạm ẩn
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=<?= $item['trangthai'] ?>" 
                                               class="btn btn-outline-secondary" 
                                               title="<?= (int)$item['trangthai'] === 1 ? 'Ẩn đánh giá' : 'Hiển thị đánh giá' ?>">
                                                <i class="align-middle" data-feather="<?= (int)$item['trangthai'] === 1 ? 'eye-off' : 'eye' ?>"></i>
                                            </a>
                                            
                                            <?php if ($isAdmin): ?>
                                                <a href="index.php?action=xoa&id=<?= $item['id'] ?>" 
                                                   class="btn btn-outline-danger" 
                                                   onclick="return confirm('Bạn có chắc chắn muốn xóa phản hồi này vĩnh viễn?');"
                                                   title="Xóa đánh giá">
                                                    <i class="align-middle" data-feather="trash-2"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const inputLive = document.getElementById("inputFilterLiveDg");
    const tableBody = document.querySelector("#tableDanhGia tbody");
    if (inputLive && tableBody) {
        inputLive.addEventListener("input", function() {
            const query = this.value.toLowerCase().trim();
            const rows = tableBody.querySelectorAll("tr");
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    }
});
</script>

<?php include("../inc/footer.php"); ?>
