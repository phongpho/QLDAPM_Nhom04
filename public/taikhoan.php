<?php
if (!isset($gsModule36)) {
    $_REQUEST['action'] = 'taikhoan';
    require __DIR__ . '/index.php';
    return;
}

$gsTieude = 'Tài khoản của tôi';
include(__DIR__ . '/inc/head-client.php');

$user = $nd->laythongtinnguoidung($gsND['email']) ?: $gsND;
$dsDiachi = $dc->laydanhsachdiachi($gsNguoidungId);
$hosoThetrang = $sp->layhosothetrang($gsNguoidungId);
$tabActive = $_GET['tab'] ?? 'profile';
?>

<section class="gs-section gs-section--grey gs-page-head">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb gs-breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tài khoản của tôi</li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="gs-page-head__title mb-1">Tài khoản của tôi</h1>
                <p class="gs-page-head__sub mb-0">Quản lý thông tin cá nhân, sổ địa chỉ và bảo mật tài khoản.</p>
            </div>
            <a class="btn-gs btn-gs--outline btn-gs--sm" href="index.php?action=donhang">Đơn mua của tôi</a>
        </div>
    </div>
</section>

<section class="gs-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-lg-3">
                <div class="gs-checkout__box p-3">
                    <div class="d-flex align-items-center gap-3 pb-3 border-bottom border-secondary border-opacity-25">
                        <?php
                        $avatar = !empty($user['hinhanh']) && file_exists('../assets/images/users/' . $user['hinhanh'])
                            ? '../assets/images/users/' . htmlspecialchars($user['hinhanh'])
                            : '../assets/images/users/user.png';
                        ?>
                        <img src="<?= $avatar ?>" alt="Avatar" width="56" height="56" class="rounded-circle object-fit-cover gs-account-avatar">
                        <div class="overflow-hidden">
                            <span class="text-white-50 small d-block">Tài khoản của</span>
                            <strong class="text-white text-truncate d-block fs-6"><?= htmlspecialchars($user['hoten']) ?></strong>
                        </div>
                    </div>

                    <div class="gs-account-nav pt-3" id="accountTabs" role="tablist">
                        <button class="gs-account-nav__item <?= ($tabActive === 'profile') ? 'active' : '' ?>"
                            id="tab-profile-btn" data-bs-toggle="list" data-bs-target="#pane-profile" type="button" role="tab">
                            Thông tin cá nhân
                        </button>

                        <button class="gs-account-nav__item <?= ($tabActive === 'address') ? 'active' : '' ?>"
                            id="tab-address-btn" data-bs-toggle="list" data-bs-target="#pane-address" type="button" role="tab">
                            Sổ địa chỉ nhận hàng
                        </button>

                        <button class="gs-account-nav__item <?= ($tabActive === 'thetrang') ? 'active' : '' ?>"
                            id="tab-thetrang-btn" data-bs-toggle="list" data-bs-target="#pane-thetrang" type="button" role="tab">
                            Hồ sơ thể trạng (BMI)
                        </button>

                        <button class="gs-account-nav__item <?= ($tabActive === 'password') ? 'active' : '' ?>"
                            id="tab-password-btn" data-bs-toggle="list" data-bs-target="#pane-password" type="button" role="tab">
                            Đổi mật khẩu
                        </button>

                        <div class="border-top border-secondary border-opacity-25 my-2 pt-2"></div>

                        <a href="index.php?action=donhang" class="gs-account-nav__item">
                            Đơn mua của tôi
                        </a>

                        <a href="index.php?action=dangxuat" class="gs-account-nav__item text-danger">
                            Đăng xuất
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-9">
                <div class="gs-checkout__box p-4">
                    <div class="tab-content" id="accountTabsContent">

                        <div class="tab-pane fade <?= ($tabActive === 'profile') ? 'show active' : '' ?>" id="pane-profile" role="tabpanel">
                            <div class="gs-checkout__box-head mb-4">
                                <h3 class="gs-checkout__title mb-1">Hồ sơ cá nhân</h3>
                                <span class="small text-white-50">Quản lý thông tin hồ sơ để bảo mật tài khoản</span>
                            </div>

                            <form action="index.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="capnhatthongtin">
                                <input type="hidden" name="txtid" value="<?= (int) $user['id'] ?>">

                                <div class="row g-4 align-items-center">
                                    <div class="col-12 col-md-8">
                                        <div class="mb-3">
                                            <label class="gs-form-label">Email đăng nhập</label>
                                            <input type="email" class="form-control gs-input" name="txtemail" value="<?= htmlspecialchars($user['email']) ?>" readonly style="opacity: 0.6; cursor: not-allowed;">
                                            <span class="small text-white-50 mt-1 d-block">Email không thể thay đổi sau khi đăng ký</span>
                                        </div>

                                        <div class="mb-3">
                                            <label class="gs-form-label">Họ và tên <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control gs-input" name="txthoten" value="<?= htmlspecialchars($user['hoten']) ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="gs-form-label">Số điện thoại <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control gs-input" name="txtsodt" value="<?= htmlspecialchars($user['sodienthoai']) ?>" pattern="[0-9]{10}" required>
                                        </div>

                                        <button type="submit" class="btn-gs btn-gs--primary btn-gs--md mt-2">
                                            LƯU THAY ĐỔI
                                        </button>
                                    </div>

                                    <div class="col-12 col-md-4 text-center border-start-md border-secondary border-opacity-25 ps-md-4">
                                        <div class="mb-3">
                                            <img src="<?= $avatar ?>" id="previewAvatar" alt="Avatar" width="120" height="120" class="rounded-circle object-fit-cover gs-account-avatar-large mb-3">
                                        </div>
                                        <label for="inputAvatar" class="btn-gs btn-gs--outline btn-gs--sm mb-2">
                                            Chọn ảnh mới
                                        </label>
                                        <input type="file" id="inputAvatar" name="fhinh" accept="image/*" class="d-none" onchange="previewImg(this)">
                                        <div class="text-white-50 small">Dung lượng file tối đa 2MB<br>Định dạng: JPG, PNG, WEBP</div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade <?= ($tabActive === 'address') ? 'show active' : '' ?>" id="pane-address" role="tabpanel">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gs-checkout__box-head mb-4 gap-2">
                                <div>
                                    <h3 class="gs-checkout__title mb-1">Địa chỉ của tôi</h3>
                                    <span class="small text-white-50">Địa chỉ nhận hàng phục vụ việc giao nhận sản phẩm</span>
                                </div>
                                <button type="button" class="btn-gs btn-gs--primary btn-gs--sm" data-bs-toggle="modal" data-bs-target="#modalThemDiaChi">
                                    + Thêm địa chỉ mới
                                </button>
                            </div>

                            <?php if (count($dsDiachi) === 0): ?>
                                <div class="text-center py-4 text-white-50">
                                    <p class="mb-0">Bạn chưa lưu địa chỉ nào. Hãy thêm địa chỉ để thanh toán nhanh hơn!</p>
                                </div>
                            <?php else: ?>
                                <div class="d-flex flex-column gap-3">
                                    <?php foreach ($dsDiachi as $dcItem): ?>
                                        <div class="p-3 rounded-3" style="background: var(--gs-dark-850); border: 1px solid var(--gs-border);">
                                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                                <div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <strong class="text-white fs-6"><?= htmlspecialchars($dcItem['tennguoinhan']) ?></strong>
                                                        <span class="text-white-50">•</span>
                                                        <span class="text-warning small"><?= htmlspecialchars($dcItem['sodienthoai']) ?></span>
                                                        <?php if ((int) $dcItem['macdinh'] === 1): ?>
                                                            <span class="gs-badge gs-badge--soft ms-2">Mặc định</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-white-50 small"><?= htmlspecialchars($dcItem['diachi']) ?></div>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <?php if ((int) $dcItem['macdinh'] !== 1): ?>
                                                        <a href="index.php?action=datdiachimacdinh&id=<?= (int)$dcItem['id'] ?>" class="btn-gs btn-gs--outline btn-gs--xs">
                                                            Thiết lập mặc định
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="index.php?action=xoadiachi&id=<?= (int)$dcItem['id'] ?>" class="btn-gs btn-gs--subtle-danger btn-gs--xs" onclick="return confirm('Bạn chắc chắn muốn xóa địa chỉ này?');">
                                                        Xóa
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade <?= ($tabActive === 'thetrang') ? 'show active' : '' ?>" id="pane-thetrang" role="tabpanel">
                            <div class="gs-checkout__box-head mb-4">
                                <h3 class="gs-checkout__title mb-1">Hồ sơ thể trạng &amp; BMI</h3>
                                <span class="small text-white-50">Cập nhật chỉ số để hệ thống gợi ý size đồ tập và combo dinh dưỡng phù hợp</span>
                            </div>

                            <?php 
                            $gsGoiy = gsTinhGoiyChiSo($sp, $gsNguoidungId);
                            include(__DIR__ . '/inc/bmi-form.php'); 
                            ?>
                        </div>

                        <div class="tab-pane fade <?= ($tabActive === 'password') ? 'show active' : '' ?>" id="pane-password" role="tabpanel">
                            <div class="gs-checkout__box-head mb-4">
                                <h3 class="gs-checkout__title mb-1">Đổi mật khẩu</h3>
                                <span class="small text-white-50">Để bảo mật tài khoản, vui lòng không chia sẻ mật khẩu cho người khác</span>
                            </div>

                            <form action="index.php" method="post" style="max-width: 480px;">
                                <input type="hidden" name="action" value="xldoimatkhau">

                                <div class="mb-3">
                                    <label class="gs-form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control gs-input" name="txtmatkhaucu" required>
                                </div>

                                <div class="mb-3">
                                    <label class="gs-form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control gs-input" name="txtmatkhaumoi" minlength="6" placeholder="Tối thiểu 6 ký tự" required>
                                </div>

                                <div class="mb-4">
                                    <label class="gs-form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control gs-input" name="txtxacnhanmatkhau" required>
                                </div>

                                <button type="submit" class="btn-gs btn-gs--primary btn-gs--md">
                                    XÁC NHẬN ĐỔI MẬT KHẨU
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalThemDiaChi" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: var(--gs-dark-800); border: 1px solid var(--gs-border); border-radius: var(--gs-radius-lg);">
            <div class="modal-header border-secondary border-opacity-25">
                <h5 class="modal-title text-white fw-bold">Thêm địa chỉ nhận hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php" method="post">
                <input type="hidden" name="action" value="themdiachimoi">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="gs-form-label">Tên người nhận <span class="text-danger">*</span></label>
                        <input type="text" class="form-control gs-input" name="tennguoinhan" placeholder="Họ và tên người nhận" required>
                    </div>
                    <div class="mb-3">
                        <label class="gs-form-label">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control gs-input" name="sodienthoai" pattern="[0-9]{10}" placeholder="Số điện thoại nhận hàng (10 số)" required>
                    </div>
                    <div class="mb-3">
                        <label class="gs-form-label">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                        <textarea class="form-control gs-input" name="diachi" rows="3" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành..." required></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="macdinh" value="1" id="chkMacdinh" checked>
                        <label class="form-check-label small text-white" for="chkMacdinh">
                            Đặt làm địa chỉ mặc định
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-25">
                    <button type="button" class="btn-gs btn-gs--outline btn-gs--sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn-gs btn-gs--primary btn-gs--sm px-3">Lưu địa chỉ</button>
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
