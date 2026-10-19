<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'taikhoan';
    require __DIR__ . '/index.php';
    return;
}

$gsTieude = 'Hồ sơ cá nhân - GYMBRO';
include(__DIR__ . '/inc/head-client.php');

$user = $nd->laythongtinnguoidung($gsND['email']);
$dsDiachi = $dc->laydanhsachdiachi($gsNguoidungId);
$hosoThetrang = $sp->layhosothetrang($gsNguoidungId);

// Lấy thông báo lỗi/thành công
$tabActive = $_GET['tab'] ?? 'profile';
?>

<section class="gs-section py-4">
    <div class="container">
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb gs-breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-secondary text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Tài khoản của tôi</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- SIDEBAR TRÁI -->
            <div class="col-12 col-lg-3">
                <div class="card border-secondary-subtle p-3" style="background-color: #141414; border-color: #2a2a2a !important;">
                    
                    <!-- Avatar & Tên tóm tắt -->
                    <div class="d-flex align-items-center gap-3 pb-3 border-bottom border-secondary">
                        <?php
                        $avatar = !empty($user['hinhanh']) && file_exists('../assets/images/users/' . $user['hinhanh'])
                            ? '../assets/images/users/' . htmlspecialchars($user['hinhanh'])
                            : '../assets/images/users/user.png';
                        ?>
                        <img src="<?= $avatar ?>" alt="Avatar" width="55" height="55" class="rounded-circle object-fit-cover border border-danger">
                        <div class="overflow-hidden">
                            <span class="text-secondary small d-block">Tài khoản của</span>
                            <strong class="text-white text-truncate d-block"><?= htmlspecialchars($user['hoten']) ?></strong>
                        </div>
                    </div>

                    <!-- Menu List-Group Tabs -->
                    <div class="list-group list-group-flush pt-2" id="accountTabs" role="tablist">
                        <button class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2 px-1 d-flex align-items-center gap-2 <?= ($tabActive === 'profile') ? 'active text-danger fw-bold' : '' ?>"
                            id="tab-profile-btn" data-bs-toggle="list" data-bs-target="#pane-profile" type="button" role="tab">
                            <i class="fa-regular fa-user" style="width: 20px;"></i> Thông tin cá nhân
                        </button>

                        <button class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2 px-1 d-flex align-items-center gap-2 <?= ($tabActive === 'address') ? 'active text-danger fw-bold' : '' ?>"
                            id="tab-address-btn" data-bs-toggle="list" data-bs-target="#pane-address" type="button" role="tab">
                            <i class="fa-solid fa-map-location-dot" style="width: 20px;"></i> Sổ địa chỉ nhận hàng
                        </button>

                        <button class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2 px-1 d-flex align-items-center gap-2 <?= ($tabActive === 'thetrang') ? 'active text-danger fw-bold' : '' ?>"
                            id="tab-thetrang-btn" data-bs-toggle="list" data-bs-target="#pane-thetrang" type="button" role="tab">
                            <i class="fa-solid fa-weight-scale" style="width: 20px;"></i> Hồ sơ thể trạng (BMI)
                        </button>

                        <button class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2 px-1 d-flex align-items-center gap-2 <?= ($tabActive === 'password') ? 'active text-danger fw-bold' : '' ?>"
                            id="tab-password-btn" data-bs-toggle="list" data-bs-target="#pane-password" type="button" role="tab">
                            <i class="fa-solid fa-key" style="width: 20px;"></i> Đổi mật khẩu
                        </button>

                        <hr class="border-secondary my-2">

                        <a href="index.php?action=donhang" class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2 px-1 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-box-open" style="width: 20px;"></i> Đơn mua của tôi
                        </a>

                        <a href="index.php?action=dangxuat" class="list-group-item list-group-item-action bg-transparent text-danger border-0 py-2 px-1 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-arrow-right-from-bracket" style="width: 20px;"></i> Đăng xuất
                        </a>
                    </div>

                </div>
            </div>

            <!-- NỘI DUNG PHẢI -->
            <div class="col-12 col-lg-9">
                <div class="card border-secondary-subtle p-4" style="background-color: #141414; border-color: #2a2a2a !important;">
                    <div class="tab-content" id="accountTabsContent">

                        <!-- TAB 1: THÔNG TIN CÁ NHÂN -->
                        <div class="tab-pane fade <?= ($tabActive === 'profile') ? 'show active' : '' ?>" id="pane-profile" role="tabpanel">
                            <div class="border-bottom border-secondary pb-3 mb-4">
                                <h4 class="text-white fw-bold mb-1">Hồ Sơ Của Tôi</h4>
                                <span class="text-secondary small">Quản lý thông tin hồ sơ để bảo mật tài khoản</span>
                            </div>

                            <form action="index.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="capnhatthongtin">
                                <input type="hidden" name="txtid" value="<?= (int) $user['id'] ?>">

                                <div class="row g-4 align-items-center">
                                    <div class="col-12 col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label small text-secondary">Email đăng nhập</label>
                                            <input type="email" class="form-control bg-dark text-white border-secondary" name="txtemail" value="<?= htmlspecialchars($user['email']) ?>" readonly style="opacity: 0.7;">
                                            <span class="form-text text-secondary small">Email không thể thay đổi sau khi đăng ký</span>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small text-secondary">Họ và tên <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-white border-secondary" style="background-color: #1e1e1e;" name="txthoten" value="<?= htmlspecialchars($user['hoten']) ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small text-secondary">Số điện thoại <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control text-white border-secondary" style="background-color: #1e1e1e;" name="txtsodt" value="<?= htmlspecialchars($user['sodienthoai']) ?>" pattern="[0-9]{10}" required>
                                        </div>

                                        <button type="submit" class="btn btn-danger px-4 mt-2">
                                            <i class="fa-solid fa-floppy-disk me-1"></i> Lưu thay đổi
                                        </button>
                                    </div>

                                    <div class="col-12 col-md-4 text-center border-start-md border-secondary">
                                        <div class="mb-3">
                                            <img src="<?= $avatar ?>" id="previewAvatar" alt="Avatar" width="120" height="120" class="rounded-circle object-fit-cover border border-2 border-danger mb-2">
                                        </div>
                                        <label for="inputAvatar" class="btn btn-outline-light btn-sm mb-2">
                                            <i class="fa-solid fa-camera me-1"></i> Chọn ảnh mới
                                        </label>
                                        <input type="file" id="inputAvatar" name="fhinh" accept="image/*" class="d-none" onchange="previewImg(this)">
                                        <div class="text-secondary small">Dụng lượng file tối đa 2MB<br>Định dạng: JPG, PNG, WEBP</div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- TAB 2: SỔ ĐỊA CHỈ NHẬN HÀNG -->
                        <div class="tab-pane fade <?= ($tabActive === 'address') ? 'show active' : '' ?>" id="pane-address" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center border-bottom border-secondary pb-3 mb-4">
                                <div>
                                    <h4 class="text-white fw-bold mb-1">Địa Chỉ Của Tôi</h4>
                                    <span class="text-secondary small">Địa chỉ nhận hàng phục vụ việc giao nhận sản phẩm</span>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalThemDiaChi">
                                    <i class="fa-solid fa-plus me-1"></i> Thêm địa chỉ mới
                                </button>
                            </div>

                            <?php if (count($dsDiachi) === 0): ?>
                                <div class="text-center py-4 text-secondary">
                                    <i class="fa-solid fa-map-location-dot fs-1 mb-2"></i>
                                    <p>Bạn chưa lưu địa chỉ nào. Hãy thêm địa chỉ để thanh toán nhanh hơn!</p>
                                </div>
                            <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($dsDiachi as $dcItem): ?>
                                        <div class="list-group-item bg-transparent text-white border-secondary py-3 px-0">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <strong class="fs-6"><?= htmlspecialchars($dcItem['tennguoinhan']) ?></strong>
                                                        <span class="text-secondary">|</span>
                                                        <span class="text-secondary"><?= htmlspecialchars($dcItem['sodienthoai']) ?></span>
                                                        <?php if ((int) $dcItem['macdinh'] === 1): ?>
                                                            <span class="badge bg-danger ms-2">Mặc định</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-white-50 small"><?= htmlspecialchars($dcItem['diachi']) ?></div>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <?php if ((int) $dcItem['macdinh'] !== 1): ?>
                                                        <a href="index.php?action=datdiachimacdinh&id=<?= (int)$dcItem['id'] ?>" class="btn btn-outline-secondary btn-sm text-white-50">
                                                            Thiết lập mặc định
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="index.php?action=xoadiachi&id=<?= (int)$dcItem['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa địa chỉ này?');">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- TAB 3: HỒ SƠ THỂ TRẠNG (BMI) -->
                        <div class="tab-pane fade <?= ($tabActive === 'thetrang') ? 'show active' : '' ?>" id="pane-thetrang" role="tabpanel">
                            <div class="border-bottom border-secondary pb-3 mb-4">
                                <h4 class="text-white fw-bold mb-1">Hồ Sơ Thể Trạng &amp; BMI</h4>
                                <span class="text-secondary small">Cập nhật chỉ số để hệ thống gợi ý size đồ tập và combo dinh dưỡng phù hợp</span>
                            </div>

                            <?php 
                            $gsGoiy = gsTinhGoiyChiSo($sp, $gsNguoidungId);
                            include(__DIR__ . '/inc/form-chiso.php'); 
                            ?>
                        </div>

                        <!-- TAB 4: ĐỔI MẬT KHẨU -->
                        <div class="tab-pane fade <?= ($tabActive === 'password') ? 'show active' : '' ?>" id="pane-password" role="tabpanel">
                            <div class="border-bottom border-secondary pb-3 mb-4">
                                <h4 class="text-white fw-bold mb-1">Đổi Mật Khẩu</h4>
                                <span class="text-secondary small">Để bảo mật tài khoản, vui lòng không chia sẻ mật khẩu cho người khác</span>
                            </div>

                            <form action="index.php" method="post" style="max-width: 500px;">
                                <input type="hidden" name="action" value="xldoimatkhau">

                                <div class="mb-3">
                                    <label class="form-label small text-secondary">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control text-white border-secondary" style="background-color: #1e1e1e;" name="txtmatkhaucu" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small text-secondary">Mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control text-white border-secondary" style="background-color: #1e1e1e;" name="txtmatkhaumoi" minlength="6" placeholder="Tối thiểu 6 ký tự" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small text-secondary">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control text-white border-secondary" style="background-color: #1e1e1e;" name="txtxacnhanmatkhau" required>
                                </div>

                                <button type="submit" class="btn btn-danger px-4">
                                    <i class="fa-solid fa-key me-1"></i> Xác nhận đổi mật khẩu
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- MODAL THÊM ĐỊA CHỈ MỚI -->
<div class="modal fade" id="modalThemDiaChi" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-secondary-subtle" style="background-color: #141414;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white fw-bold">Thêm Địa Chỉ Nhận Hàng Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php" method="post">
                <input type="hidden" name="action" value="themdiachimoi">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Tên người nhận <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-white border-secondary" style="background-color: #1e1e1e;" name="tennguoinhan" placeholder="VD: Nguyễn Văn A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control text-white border-secondary" style="background-color: #1e1e1e;" name="sodienthoai" pattern="[0-9]{10}" placeholder="0901234567" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                        <textarea class="form-control text-white border-secondary" style="background-color: #1e1e1e;" name="diachi" rows="3" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố..." required></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="macdinh" value="1" id="chkMacdinh">
                        <label class="form-check-label small text-white" for="chkMacdinh">
                            Đặt làm địa chỉ mặc định
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger btn-sm px-3">Hoàn thành</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImg(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewAvatar').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php include(__DIR__ . '/inc/footer.php'); ?>
