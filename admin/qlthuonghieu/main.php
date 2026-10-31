<?php
$title = "Quản lý thương hiệu - GymBro Admin";
include("../inc/header.php");
?>

<div class="container-fluid p-0">
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="align-middle me-1 text-primary" data-feather="award"></i> Quản lý Thương hiệu
            </h1>
            <p class="text-muted small mb-0">Quản lý danh sách các nhãn hàng, thương hiệu sản phẩm trên hệ thống</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalThemThuongHieu">
                <i class="align-middle me-1" data-feather="plus-circle"></i> Thêm thương hiệu mới
            </button>
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
                            <h5 class="card-title text-muted">Tổng thương hiệu</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary bg-primary-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="award"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-primary"><?= number_format($thongke['tong'] ?? 0) ?></h1>
                    <div class="mb-0 text-muted small">
                        <span>Số hãng hiện có</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title text-muted">Đang hiển thị</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-success bg-success-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-success"><?= number_format($thongke['hoatdong'] ?? 0) ?></h1>
                    <div class="mb-0 text-muted small">
                        <span>Đang hoạt động ngoài shop</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title text-muted">Đang tạm ẩn</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-warning bg-warning-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="eye-off"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-warning"><?= number_format($thongke['an'] ?? 0) ?></h1>
                    <div class="mb-0 text-muted small">
                        <span>Không hiển thị trên trang chủ</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title text-muted">Hàng hóa theo hãng</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-info bg-info-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="package"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-info"><?= number_format($thongke['tongsanpham'] ?? 0) ?></h1>
                    <div class="mb-0 text-muted small">
                        <span>Sản phẩm đã gán thương hiệu</span>
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
                        <input type="text" name="tukhoa" id="inputFilterLive" class="form-control bg-light border-start-0" 
                               placeholder="Tìm nhanh tên thương hiệu..." value="<?= htmlspecialchars($_GET["tukhoa"] ?? "") ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="trangthai" class="form-select bg-light" onchange="this.form.submit()">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="1" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "1") ? "selected" : "" ?>>Đang hiển thị</option>
                        <option value="0" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "0") ? "selected" : "" ?>>Đang tạm ẩn</option>
                    </select>
                </div>

                <div class="col-md-4 text-md-end">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="align-middle me-1" data-feather="rotate-ccw"></i> Làm mới
                    </a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tableThuongHieu">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">STT</th>
                            <th style="width: 100px;">Logo</th>
                            <th>Tên thương hiệu</th>
                            <th class="text-center" style="width: 170px;">Sản phẩm liên kết</th>
                            <th class="text-center" style="width: 140px;">Trạng thái</th>
                            <th class="text-center" style="width: 140px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($thuonghieus)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i data-feather="inbox" style="width: 48px; height: 48px; stroke-width: 1.5;" class="mb-2"></i>
                                    <p class="mb-0">Không tìm thấy thương hiệu nào phù hợp.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $stt = 1; foreach ($thuonghieus as $item): ?>
                                <tr>
                                    <td class="text-center fw-bold text-muted"><?= $stt++ ?></td>
                                    <td>
                                        <?php if (!empty($item["logo"])): ?>
                                            <div class="bg-white border rounded p-1 d-inline-block text-center shadow-xs" style="width: 64px; height: 44px;">
                                                <img src="../../assets/images/products/<?= htmlspecialchars($item["logo"]) ?>" 
                                                     alt="<?= htmlspecialchars($item["tenthuonghieu"]) ?>" 
                                                     class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                            </div>
                                        <?php else: ?>
                                            <div class="bg-light border rounded p-1 d-inline-flex align-items-center justify-content-center text-muted" style="width: 64px; height: 44px;">
                                                <i data-feather="image" style="width: 18px; height: 18px;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($item["tenthuonghieu"]) ?></div>
                                        <span class="text-muted small">Cập nhật hệ thống</span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ((int)$item["soluong_sanpham"] > 0): ?>
                                            <a href="../qlsanpham/index.php?thuonghieu_id=<?= $item['id'] ?>" class="badge bg-primary-subtle text-primary text-decoration-none px-3 py-2 rounded-pill">
                                                <i class="align-middle me-1" data-feather="package" style="width: 14px; height: 14px;"></i>
                                                <?= number_format($item["soluong_sanpham"]) ?> sản phẩm
                                            </a>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">0 sản phẩm</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ((int)$item["trangthai"] === 1): ?>
                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=1" 
                                               class="badge bg-success-subtle text-success text-decoration-none px-2 py-1 rounded"
                                               title="Bấm để ẩn thương hiệu">
                                                <i class="align-middle me-1" data-feather="check" style="width: 12px; height: 12px;"></i> Hiển thị
                                            </a>
                                        <?php else: ?>
                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=0" 
                                               class="badge bg-secondary-subtle text-secondary text-decoration-none px-2 py-1 rounded"
                                               title="Bấm để kích hoạt hiển thị">
                                                <i class="align-middle me-1" data-feather="eye-off" style="width: 12px; height: 12px;"></i> Tạm ẩn
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-primary btn-sua-th" 
                                                    data-id="<?= $item['id'] ?>"
                                                    data-ten="<?= htmlspecialchars($item['tenthuonghieu'], ENT_QUOTES) ?>"
                                                    data-logo="<?= htmlspecialchars($item['logo'] ?? '', ENT_QUOTES) ?>"
                                                    data-tt="<?= $item['trangthai'] ?>"
                                                    title="Chỉnh sửa thương hiệu">
                                                <i class="align-middle" data-feather="edit-2"></i>
                                            </button>
                                            
                                            <?php if ($isAdmin): ?>
                                                <?php if ((int)$item["soluong_sanpham"] > 0): ?>
                                                    <button type="button" class="btn btn-outline-secondary" 
                                                            title="Đã có sản phẩm liên kết, không thể xóa" 
                                                            onclick="alert('Thương hiệu <?= htmlspecialchars($item['tenthuonghieu']) ?> đang có <?= $item['soluong_sanpham'] ?> sản phẩm liên kết! Bạn chỉ có thể chuyển sang trạng thái Ẩn.')">
                                                        <i class="align-middle text-muted" data-feather="trash-2"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <a href="index.php?action=xoa&id=<?= $item['id'] ?>" 
                                                       class="btn btn-outline-danger" 
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn thương hiệu <?= htmlspecialchars($item['tenthuonghieu']) ?>?');"
                                                       title="Xóa thương hiệu">
                                                        <i class="align-middle" data-feather="trash-2"></i>
                                                    </a>
                                                <?php endif; ?>
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

<!-- Modal Thêm Thương Hiệu -->
<div class="modal fade" id="modalThemThuongHieu" tabindex="-1" aria-labelledby="modalThemLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="index.php?action=xulythem" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="modalThemLabel">
                        <i class="align-middle me-1 text-primary" data-feather="plus-circle"></i> Thêm thương hiệu mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên thương hiệu <span class="text-danger">*</span></label>
                        <input type="text" name="tenthuonghieu" class="form-control" required placeholder="Ví dụ: Gymshark, Nike Pro, Puma...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Logo thương hiệu</label>
                        <input type="file" name="flogo" class="form-control" accept="image/*" onchange="previewLogoAdd(event)">
                        <div class="form-text">Hỗ trợ JPG, PNG, WEBP, SVG. Nên dùng ảnh tỉ lệ ngang nền trong suốt (PNG).</div>
                        <div class="mt-2 text-center p-2 bg-light rounded border d-none" id="previewContainerAdd">
                            <img id="previewImgAdd" src="" alt="Logo xem trước" style="max-height: 60px; max-width: 100%; object-fit: contain;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Trạng thái ban đầu</label>
                        <select name="trangthai" class="form-select">
                            <option value="1">Hiển thị (Khách hàng thấy thương hiệu)</option>
                            <option value="0">Tạm ẩn (Chỉ lưu nội bộ)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="align-middle me-1" data-feather="save"></i> Lưu thương hiệu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa Thương Hiệu -->
<div class="modal fade" id="modalSuaThuongHieu" tabindex="-1" aria-labelledby="modalSuaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="index.php?action=xulysua" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_th_id">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="modalSuaLabel">
                        <i class="align-middle me-1 text-primary" data-feather="edit-2"></i> Chỉnh sửa thương hiệu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên thương hiệu <span class="text-danger">*</span></label>
                        <input type="text" name="tenthuonghieu" id="edit_th_ten" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Cập nhật Logo mới</label>
                        <input type="file" name="flogo" class="form-control" accept="image/*" onchange="previewLogoEdit(event)">
                        <div class="form-text">Để trống nếu muốn giữ nguyên logo hiện tại.</div>
                        <div class="mt-2 text-center p-2 bg-light rounded border" id="previewContainerEdit">
                            <span class="text-muted small d-block mb-1">Logo hiện tại / xem trước:</span>
                            <img id="previewImgEdit" src="" alt="Logo" style="max-height: 60px; max-width: 100%; object-fit: contain;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Trạng thái</label>
                        <select name="trangthai" id="edit_th_tt" class="form-select">
                            <option value="1">Hiển thị</option>
                            <option value="0">Tạm ẩn</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="align-middle me-1" data-feather="check"></i> Cập nhật thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Live Filter bảng thương hiệu
    const inputLive = document.getElementById("inputFilterLive");
    const tableBody = document.querySelector("#tableThuongHieu tbody");
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

    // 2. Click button Sửa -> Populate Modal Sửa
    const modalSuaEl = document.getElementById('modalSuaThuongHieu');
    const modalSua = new bootstrap.Modal(modalSuaEl);
    
    document.querySelectorAll(".btn-sua-th").forEach(btn => {
        btn.addEventListener("click", function() {
            const id = this.getAttribute("data-id");
            const ten = this.getAttribute("data-ten");
            const logo = this.getAttribute("data-logo");
            const tt = this.getAttribute("data-tt");

            document.getElementById("edit_th_id").value = id;
            document.getElementById("edit_th_ten").value = ten;
            document.getElementById("edit_th_tt").value = tt;

            const previewImg = document.getElementById("previewImgEdit");
            if (logo && logo.trim() !== "") {
                previewImg.src = "../../assets/images/products/" + logo;
                previewImg.style.display = "inline-block";
            } else {
                previewImg.src = "../../assets/images/products/product.png";
            }

            modalSua.show();
        });
    });
});

function previewLogoAdd(event) {
    const file = event.target.files[0];
    const container = document.getElementById("previewContainerAdd");
    const img = document.getElementById("previewImgAdd");
    if (file) {
        img.src = URL.createObjectURL(file);
        container.classList.remove("d-none");
    }
}

function previewLogoEdit(event) {
    const file = event.target.files[0];
    const img = document.getElementById("previewImgEdit");
    if (file) {
        img.src = URL.createObjectURL(file);
    }
}
</script>

<?php include("../inc/footer.php"); ?>
