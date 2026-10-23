<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản lý danh mục - GymBro Admin</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="../inc/css/app.css" rel="stylesheet">
    <script src="../inc/js/app.js"></script>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <?php include('../inc/sidebar.php'); ?>

        <div class="main">
            <?php include('../inc/header.php'); ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <!-- Header & Tiêu đề -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h1 class="h3 mb-1 text-dark fw-bold">
                                <i class="fa fa-folder-tree text-primary me-2"></i>Quản lý danh mục sản phẩm
                            </h1>
                            <p class="text-muted small mb-0">Quản lý hệ thống phân nhóm Quần áo, Phụ kiện và Thực phẩm bổ sung.</p>
                        </div>
                        <div>
                            <button class="btn btn-primary shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#formThemDanhMuc" aria-expanded="false" aria-controls="formThemDanhMuc">
                                <i class="fa fa-plus-circle me-1"></i> Thêm danh mục mới
                            </button>
                        </div>
                    </div>

                    <!-- Thống kê nhanh theo Card -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="card shadow-sm border-0 mb-0">
                                <div class="card-body py-3 d-flex align-items-center">
                                    <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-3 me-3">
                                        <i class="fa fa-layer-group fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Tổng danh mục</div>
                                        <h4 class="fw-bold mb-0"><?= $tongDanhmuc ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card shadow-sm border-0 mb-0">
                                <div class="card-body py-3 d-flex align-items-center">
                                    <div class="rounded-3 bg-info bg-opacity-10 text-info p-3 me-3">
                                        <i class="fa fa-shirt fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Quần áo Gym</div>
                                        <h4 class="fw-bold mb-0"><?= $demQuanao ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card shadow-sm border-0 mb-0">
                                <div class="card-body py-3 d-flex align-items-center">
                                    <div class="rounded-3 bg-warning bg-opacity-10 text-warning p-3 me-3">
                                        <i class="fa fa-dumbbell fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Phụ kiện tập</div>
                                        <h4 class="fw-bold mb-0"><?= $demPhukien ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card shadow-sm border-0 mb-0">
                                <div class="card-body py-3 d-flex align-items-center">
                                    <div class="rounded-3 bg-success bg-opacity-10 text-success p-3 me-3">
                                        <i class="fa fa-flask fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">TPBS & Tăng cơ</div>
                                        <h4 class="fw-bold mb-0"><?= $demTpbs ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông báo Flash Alerts -->
                    <?php if (isset($_SESSION["thongbao"])): ?>
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fa fa-check-circle me-2"></i><?= $_SESSION["thongbao"] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION["thongbao"]); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION["loi"])): ?>
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fa fa-exclamation-triangle me-2"></i><?= $_SESSION["loi"] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION["loi"]); ?>
                    <?php endif; ?>

                    <!-- Form Thêm mới danh mục (Dạng Collapse) -->
                    <div id="formThemDanhMuc" class="collapse card shadow-sm border-0 mb-4">
                        <div class="card-header bg-primary bg-opacity-10 border-0 py-3">
                            <h5 class="card-title fw-bold text-primary mb-0">
                                <i class="fa fa-plus-circle me-2"></i>Thêm mới danh mục hàng hóa
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="index.php" method="POST" class="row g-3">
                                <input type="hidden" name="action" value="xlthem">

                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
                                    <input type="text" name="txtten" class="form-control" placeholder="Ví dụ: Áo thun nam, Đai lưng..." required>
                                    <div class="form-text">Tên danh mục không được trùng lặp.</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Nhóm lớn <span class="text-danger">*</span></label>
                                    <select name="optloai" class="form-select" required>
                                        <option value="quanao">Quần áo thể thao (quanao)</option>
                                        <option value="phukien">Phụ kiện tập luyện (phukien)</option>
                                        <option value="tpbs">Thực phẩm bổ sung (tpbs)</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Trạng thái ban đầu</label>
                                    <select name="opttrangthai" class="form-select">
                                        <option value="1" selected>Hiển thị ngay</option>
                                        <option value="0">Tạm ẩn</option>
                                    </select>
                                </div>

                                <div class="col-12 text-end pt-2">
                                    <button type="button" class="btn btn-outline-secondary me-2" data-bs-toggle="collapse" data-bs-target="#formThemDanhMuc">
                                        Đóng
                                    </button>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fa fa-save me-1"></i> Lưu danh mục
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Bảng Danh sách & Bộ lọc -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <form method="GET" action="index.php" class="row g-2 align-items-center">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-muted"></i></span>
                                        <input type="text" name="tukhoa" class="form-control bg-light border-start-0" placeholder="Tìm tên danh mục..." value="<?= htmlspecialchars($filter_tukhoa ?? '') ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <select name="loai" class="form-select" onchange="this.form.submit()">
                                        <option value=""> Tất cả nhóm hàng </option>
                                        <option value="quanao" <?= (($filter_loai ?? '') === 'quanao') ? 'selected' : '' ?>>Quần áo thể thao</option>
                                        <option value="phukien" <?= (($filter_loai ?? '') === 'phukien') ? 'selected' : '' ?>>Phụ kiện tập luyện</option>
                                        <option value="tpbs" <?= (($filter_loai ?? '') === 'tpbs') ? 'selected' : '' ?>>Thực phẩm bổ sung</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <select name="trangthai" class="form-select" onchange="this.form.submit()">
                                        <option value=""> Tất cả trạng thái </option>
                                        <option value="1" <?= (($filter_trangthai ?? '') === '1') ? 'selected' : '' ?>>Đang hiển thị</option>
                                        <option value="0" <?= (($filter_trangthai ?? '') === '0') ? 'selected' : '' ?>>Đã ẩn</option>
                                    </select>
                                </div>

                                <div class="col-md-2 text-end">
                                    <?php if (!empty($filter_loai) || ($filter_trangthai ?? '') !== '' || !empty($filter_tukhoa)): ?>
                                        <a href="index.php" class="btn btn-outline-secondary w-100" title="Bỏ tất cả bộ lọc">
                                            <i class="fa fa-rotate-left me-1"></i> Bỏ lọc
                                        </a>
                                    <?php else: ?>
                                        <button type="submit" class="btn btn-secondary w-100">
                                            <i class="fa fa-filter me-1"></i> Lọc
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;" class="text-center">STT</th>
                                        <th>Tên danh mục</th>
                                        <th>Nhóm lớn</th>
                                        <th class="text-center">Sản phẩm liên kết</th>
                                        <th class="text-center" style="width: 150px;">Trạng thái</th>
                                        <th class="text-center" style="width: 180px;">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($danhmuc)): ?>
                                        <?php $stt = 1;
                                        foreach ($danhmuc as $d): ?>
                                            <tr>
                                                <td class="text-center text-muted"><?= $stt++ ?></td>
                                                <td>
                                                    <span class="fw-semibold text-dark fs-6"><?= htmlspecialchars($d["tendanhmuc"]) ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($d["loai"] === "quanao"): ?>
                                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">
                                                            <i class="fa fa-shirt me-1"></i> Quần áo thể thao
                                                        </span>
                                                    <?php elseif ($d["loai"] === "phukien"): ?>
                                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1">
                                                            <i class="fa fa-dumbbell me-1"></i> Phụ kiện tập
                                                        </span>
                                                    <?php elseif ($d["loai"] === "tpbs"): ?>
                                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                                            <i class="fa fa-flask me-1"></i> Thực phẩm bổ sung
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary"><?= htmlspecialchars($d["loai"]) ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php $soSP = (int)($d["soluongsanpham"] ?? 0); ?>
                                                    <?php if ($soSP > 0): ?>
                                                        <span class="badge bg-info bg-opacity-10 text-info fw-semibold rounded-pill px-3 py-1">
                                                            <i class="fa fa-box me-1"></i><?= $soSP ?> sản phẩm
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-muted border rounded-pill px-3 py-1">
                                                            0 sản phẩm
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <!-- Nút Toggle Ẩn/Hiện: Cả Nhân viên và Admin đều thao tác được -->
                                                    <?php if ((int)$d["trangthai"] === 1): ?>
                                                        <a href="index.php?action=doitrangthai&id=<?= $d["id"] ?>&trangthai=0"
                                                            class="btn btn-sm btn-outline-success"
                                                            title="Bấm để ẨN danh mục khỏi website người dùng">
                                                            <i class="fa fa-toggle-on text-success me-1"></i>Hiển thị
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="index.php?action=doitrangthai&id=<?= $d["id"] ?>&trangthai=1"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Bấm để HIỂN THỊ lại danh mục">
                                                            <i class="fa fa-toggle-off text-danger me-1"></i>Đã ẩn
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <!-- Nút Sửa: Cả Admin và Nhân viên đều thấy -->
                                                    <a href="index.php?action=sua&id=<?= $d["id"] ?>"
                                                        class="btn btn-sm btn-warning me-1"
                                                        title="Sửa danh mục">
                                                        <i class="fa fa-pen me-1"></i>Sửa
                                                    </a>

                                                    <!-- Nút Xóa: CHỈ ADMIN (loai == 1) MỚI CÓ -->
                                                    <?php if ($_SESSION["nguoidung"]["loai"] == 1): ?>
                                                        <a href="index.php?action=xoa&id=<?= $d["id"] ?>"
                                                            class="btn btn-sm btn-danger"
                                                            title="Xóa danh mục (Chỉ Admin)"
                                                            onclick="return confirm('Bạn có chắc chắn muốn XÓA danh mục <?= htmlspecialchars($d['tendanhmuc']) ?> khỏi hệ thống?');">
                                                            <i class="fa fa-trash me-1"></i>Xóa
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="btn btn-sm btn-light text-muted border disabled"
                                                            title="Nhân viên chỉ có thể Ẩn danh mục, không thể xóa">
                                                            <i class="fa fa-lock me-1"></i>Xóa
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="fa fa-box-open fa-2x mb-2 d-block"></i>
                                                Không tìm thấy danh mục nào phù hợp!
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>


                    </div>

                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>
</body>

</html>