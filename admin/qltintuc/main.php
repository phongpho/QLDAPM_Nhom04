<?php
$title = "Quản lý bài viết & tin tức - GymBro Admin";
include("../inc/header.php");
?>

<div class="container-fluid p-0">
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="align-middle me-1 text-primary" data-feather="file-text"></i> Quản lý Bài viết & Tin tức
            </h1>
            <p class="text-muted small mb-0">Quản lý các bài viết cẩm nang tập luyện, dinh dưỡng, sự kiện và khuyến mãi</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalThemTinTuc">
                <i class="align-middle me-1" data-feather="plus-circle"></i> Đăng bài viết mới
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
                            <h5 class="card-title text-muted">Tổng bài viết</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary bg-primary-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="file-text"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-primary"><?= number_format($thongke['tong'] ?? 0) ?></h1>
                    <div class="mb-0 text-muted small">
                        <span>Đang xuất bản: <strong><?= number_format($thongke['hienthi'] ?? 0) ?></strong></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title text-muted">Cẩm nang / Tin tức</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-info bg-info-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="book-open"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-info"><?= number_format($thongke['dem_tintuc'] ?? 0) ?></h1>
                    <div class="mb-0 text-muted small">
                        <span>Kiến thức thể hình, dinh dưỡng</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title text-muted">Sự kiện tập luyện</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-warning bg-warning-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="calendar"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-warning"><?= number_format($thongke['dem_sukien'] ?? 0) ?></h1>
                    <div class="mb-0 text-muted small">
                        <span>Workshop, giải đấu, offline</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title text-muted">Tin khuyến mãi</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-danger bg-danger-subtle rounded-3 p-2">
                                <i class="align-middle" data-feather="percent"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-2 fw-bold text-danger"><?= number_format($thongke['dem_khuyenmai'] ?? 0) ?></h1>
                    <div class="mb-0 text-muted small">
                        <span>Thông báo ưu đãi mua sắm</span>
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
                        <input type="text" name="tukhoa" id="inputFilterLiveTt" class="form-control bg-light border-start-0" 
                               placeholder="Tìm tiêu đề, nội dung bài viết..." value="<?= htmlspecialchars($_GET["tukhoa"] ?? "") ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="loai" class="form-select bg-light" onchange="this.form.submit()">
                        <option value="">-- Tất cả thể loại --</option>
                        <option value="tintuc" <?= (isset($_GET["loai"]) && $_GET["loai"] === "tintuc") ? "selected" : "" ?>>Cẩm nang / Tin tức</option>
                        <option value="sukien" <?= (isset($_GET["loai"]) && $_GET["loai"] === "sukien") ? "selected" : "" ?>>Sự kiện tập luyện</option>
                        <option value="khuyenmai" <?= (isset($_GET["loai"]) && $_GET["loai"] === "khuyenmai") ? "selected" : "" ?>>Tin khuyến mãi</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="trangthai" class="form-select bg-light" onchange="this.form.submit()">
                        <option value="">-- Trạng thái --</option>
                        <option value="1" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "1") ? "selected" : "" ?>>Đang hiển thị</option>
                        <option value="0" <?= (isset($_GET["trangthai"]) && $_GET["trangthai"] === "0") ? "selected" : "" ?>>Tạm ẩn / Nháp</option>
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
                <table class="table table-hover align-middle mb-0" id="tableTinTuc">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th style="width: 100px;">Hình ảnh</th>
                            <th>Tiêu đề bài viết</th>
                            <th style="width: 150px;">Chuyên mục</th>
                            <th style="width: 140px;">Ngày đăng</th>
                            <th class="text-center" style="width: 130px;">Trạng thái</th>
                            <th class="text-center" style="width: 120px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tintucs)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i data-feather="file-text" style="width: 48px; height: 48px; stroke-width: 1.5;" class="mb-2"></i>
                                    <p class="mb-0">Chưa có bài viết nào phù hợp.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $stt = 1; foreach ($tintucs as $item): ?>
                                <tr>
                                    <td class="text-center fw-bold text-muted"><?= $stt++ ?></td>
                                    <td>
                                        <?php if (!empty($item["hinhanh"])): ?>
                                            <div class="border rounded overflow-hidden shadow-xs" style="width: 72px; height: 48px;">
                                                <img src="../../assets/images/products/<?= htmlspecialchars($item["hinhanh"]) ?>" 
                                                     alt="<?= htmlspecialchars($item["tieude"]) ?>" 
                                                     class="w-100 h-100" style="object-fit: cover;">
                                            </div>
                                        <?php else: ?>
                                            <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted" style="width: 72px; height: 48px;">
                                                <i data-feather="image" style="width: 18px; height: 18px;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($item["tieude"]) ?></div>
                                        <div class="text-muted small text-truncate" style="max-width: 450px;">
                                            <?= htmlspecialchars(strip_tags($item["noidung"])) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php 
                                        if ($item["loai"] === "sukien"): ?>
                                            <span class="badge bg-warning-subtle text-warning border px-2 py-1 rounded-pill">
                                                <i class="align-middle me-1" data-feather="calendar" style="width: 12px; height: 12px;"></i> Sự kiện
                                            </span>
                                        <?php elseif ($item["loai"] === "khuyenmai"): ?>
                                            <span class="badge bg-danger-subtle text-danger border px-2 py-1 rounded-pill">
                                                <i class="align-middle me-1" data-feather="percent" style="width: 12px; height: 12px;"></i> Khuyến mãi
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-info-subtle text-info border px-2 py-1 rounded-pill">
                                                <i class="align-middle me-1" data-feather="book-open" style="width: 12px; height: 12px;"></i> Cẩm nang
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="small text-muted"><?= date('d/m/Y H:i', strtotime($item["ngaydang"])) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ((int)$item["trangthai"] === 1): ?>
                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=1" 
                                               class="badge bg-success-subtle text-success text-decoration-none px-2 py-1 rounded"
                                               title="Bấm để ẩn bài viết">
                                                <i class="align-middle me-1" data-feather="check" style="width: 12px; height: 12px;"></i> Hiển thị
                                            </a>
                                        <?php else: ?>
                                            <a href="index.php?action=doitrangthai&id=<?= $item['id'] ?>&tt=0" 
                                               class="badge bg-secondary-subtle text-secondary text-decoration-none px-2 py-1 rounded"
                                               title="Bấm để công khai bài viết">
                                                <i class="align-middle me-1" data-feather="eye-off" style="width: 12px; height: 12px;"></i> Bản nháp
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-primary btn-sua-tt" 
                                                    data-id="<?= $item['id'] ?>"
                                                    data-tieude="<?= htmlspecialchars($item['tieude'], ENT_QUOTES) ?>"
                                                    data-loai="<?= $item['loai'] ?>"
                                                    data-hinhanh="<?= htmlspecialchars($item['hinhanh'] ?? '', ENT_QUOTES) ?>"
                                                    data-tt="<?= $item['trangthai'] ?>"
                                                    data-noidung="<?= htmlspecialchars($item['noidung'], ENT_QUOTES) ?>"
                                                    title="Chỉnh sửa bài viết">
                                                <i class="align-middle" data-feather="edit-2"></i>
                                            </button>
                                            
                                            <?php if ($isAdmin): ?>
                                                <a href="index.php?action=xoa&id=<?= $item['id'] ?>" 
                                                   class="btn btn-outline-danger" 
                                                   onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');"
                                                   title="Xóa bài viết">
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

<!-- Modal Đăng Bài Viết Mới -->
<div class="modal fade" id="modalThemTinTuc" tabindex="-1" aria-labelledby="modalThemLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="index.php?action=xulythem" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="modalThemLabel">
                        <i class="align-middle me-1 text-primary" data-feather="plus-circle"></i> Đăng bài viết mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu đề bài viết <span class="text-danger">*</span></label>
                        <input type="text" name="tieude" class="form-control" required placeholder="Nhập tiêu đề bài viết...">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Chuyên mục / Thể loại <span class="text-danger">*</span></label>
                            <select name="loai" class="form-select">
                                <option value="tintuc">Cẩm nang tập luyện & Dinh dưỡng</option>
                                <option value="sukien">Sự kiện thể hình & Workshop</option>
                                <option value="khuyenmai">Thông báo ưu đãi & Khuyến mãi</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Trạng thái công khai</label>
                            <select name="trangthai" class="form-select">
                                <option value="1">Hiển thị ngay</option>
                                <option value="0">Lưu bản nháp</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ảnh bìa bài viết</label>
                        <input type="file" name="fhinhanh" class="form-control" accept="image/*" onchange="previewNewsAdd(event)">
                        <div class="mt-2 text-center p-2 bg-light rounded border d-none" id="previewContainerNewsAdd">
                            <img id="previewImgNewsAdd" src="" alt="Thumbnail" style="max-height: 120px; max-width: 100%; object-fit: contain;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nội dung bài viết <span class="text-danger">*</span></label>
                        <textarea name="noidung" class="form-control" rows="8" required placeholder="Viết nội dung chi tiết bài viết tại đây..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="align-middle me-1" data-feather="save"></i> Đăng bài viết
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa Bài Viết -->
<div class="modal fade" id="modalSuaTinTuc" tabindex="-1" aria-labelledby="modalSuaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="index.php?action=xulysua" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_tt_id">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="modalSuaLabel">
                        <i class="align-middle me-1 text-primary" data-feather="edit-2"></i> Chỉnh sửa bài viết
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu đề bài viết <span class="text-danger">*</span></label>
                        <input type="text" name="tieude" id="edit_tt_tieude" class="form-control" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Chuyên mục / Thể loại <span class="text-danger">*</span></label>
                            <select name="loai" id="edit_tt_loai" class="form-select">
                                <option value="tintuc">Cẩm nang tập luyện & Dinh dưỡng</option>
                                <option value="sukien">Sự kiện thể hình & Workshop</option>
                                <option value="khuyenmai">Thông báo ưu đãi & Khuyến mãi</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="trangthai" id="edit_tt_trangthai" class="form-select">
                                <option value="1">Hiển thị</option>
                                <option value="0">Tạm ẩn / Bản nháp</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Cập nhật ảnh bìa mới</label>
                        <input type="file" name="fhinhanh" class="form-control" accept="image/*" onchange="previewNewsEdit(event)">
                        <div class="form-text">Để trống nếu không muốn đổi ảnh bìa.</div>
                        <div class="mt-2 text-center p-2 bg-light rounded border" id="previewContainerNewsEdit">
                            <img id="previewImgNewsEdit" src="" alt="Thumbnail" style="max-height: 120px; max-width: 100%; object-fit: contain;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nội dung bài viết <span class="text-danger">*</span></label>
                        <textarea name="noidung" id="edit_tt_noidung" class="form-control" rows="8" required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="align-middle me-1" data-feather="check"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Live Search
    const inputLive = document.getElementById("inputFilterLiveTt");
    const tableBody = document.querySelector("#tableTinTuc tbody");
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

    // Modal Edit Populate
    const modalSuaEl = document.getElementById('modalSuaTinTuc');
    const modalSua = new bootstrap.Modal(modalSuaEl);
    
    document.querySelectorAll(".btn-sua-tt").forEach(btn => {
        btn.addEventListener("click", function() {
            document.getElementById("edit_tt_id").value = this.getAttribute("data-id");
            document.getElementById("edit_tt_tieude").value = this.getAttribute("data-tieude");
            document.getElementById("edit_tt_loai").value = this.getAttribute("data-loai");
            document.getElementById("edit_tt_trangthai").value = this.getAttribute("data-tt");
            document.getElementById("edit_tt_noidung").value = this.getAttribute("data-noidung");

            const hinh = this.getAttribute("data-hinhanh");
            const previewImg = document.getElementById("previewImgNewsEdit");
            if (hinh && hinh.trim() !== "") {
                previewImg.src = "../../assets/images/products/" + hinh;
                previewImg.style.display = "inline-block";
            } else {
                previewImg.src = "../../assets/images/products/product.png";
            }

            modalSua.show();
        });
    });
});

function previewNewsAdd(event) {
    const file = event.target.files[0];
    const container = document.getElementById("previewContainerNewsAdd");
    const img = document.getElementById("previewImgNewsAdd");
    if (file) {
        img.src = URL.createObjectURL(file);
        container.classList.remove("d-none");
    }
}

function previewNewsEdit(event) {
    const file = event.target.files[0];
    const img = document.getElementById("previewImgNewsEdit");
    if (file) {
        img.src = URL.createObjectURL(file);
    }
}
</script>

<?php include("../inc/footer.php"); ?>
