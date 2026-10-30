<!DOCTYPE html>
<html lang="vi" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập & Đăng ký - GYMBRO</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0d0d0d;
        }
        .card {
            background-color: #141414 !important;
            border-color: #2a2a2a !important;
        }
        .form-control, .input-group-text {
            background-color: #1e1e1e !important;
            border-color: #333 !important;
            color: #e0e0e0 !important;
        }
        .form-control::placeholder {
            color: #555 !important;
        }
        .form-control:focus {
            background-color: #242424 !important;
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220,53,69,.15) !important;
        }
        .bg-body-tertiary {
            background-color: #1e1e1e !important;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            transition: all 0.2s ease;
        }
        .btn-danger:hover {
            background-color: #bb2d3b;
            border-color: #b02a37;
        }
        .nav-pills .nav-link {
            color: #adb5bd;
            font-weight: 500;
        }
        .nav-pills .nav-link.active {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>

<body>
    <main class="d-flex align-items-center justify-content-center min-vh-100 py-5">
        <div class="card border-secondary-subtle shadow-lg" style="width: 420px; max-width: 92%;">
            <div class="card-body p-4">

                <!-- logo & tiêu đề -->
                <div class="text-center mb-3">
                    <a href="index.php" class="text-decoration-none d-inline-block">
                        <img src="../assets/images/systems/logo.png?v=<?= file_exists(__DIR__ . '/../assets/images/systems/logo.png') ? filemtime(__DIR__ . '/../assets/images/systems/logo.png') : time(); ?>" 
                             alt="GYMBRO" height="50" style="height: 50px; max-height: 50px; width: auto; object-fit: contain;">
                    </a>
                    <p class="small text-body-secondary mt-2">Đồng hành cùng mục tiêu tập luyện của bạn</p>
                </div>

                <!-- Thông báo -->
                <?php if (isset($thongbao_loi) && !empty($thongbao_loi)): ?>
                    <div class="alert alert-danger py-2 px-3 small text-center" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($thongbao_loi) ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($thongbao_thanhcong) && !empty($thongbao_thanhcong)): ?>
                    <div class="alert alert-success py-2 px-3 small text-center" role="alert">
                        <i class="fa-solid fa-circle-check me-1"></i> <?= htmlspecialchars($thongbao_thanhcong) ?>
                    </div>
                <?php endif; ?>

                <!-- Tab Đăng nhập / Đăng ký -->
                <ul class="nav nav-pills nav-fill mb-4 bg-body-tertiary p-1 rounded-3" id="authTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= (!isset($tabActive) || $tabActive === 'login') ? 'active' : '' ?> py-2"
                            id="login-tab" data-bs-toggle="pill" data-bs-target="#tab-login" type="button" role="tab">
                            Đăng nhập
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= (isset($tabActive) && $tabActive === 'register') ? 'active' : '' ?> py-2"
                            id="register-tab" data-bs-toggle="pill" data-bs-target="#tab-register" type="button" role="tab">
                            Đăng ký
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="authTabContent">

                    <!-- TAB ĐĂNG NHẬP -->
                    <div class="tab-pane fade <?= (!isset($tabActive) || $tabActive === 'login') ? 'show active' : '' ?>"
                        id="tab-login" role="tabpanel">
                        <form action="index.php" method="post">
                            <input type="hidden" name="action" value="xldangnhap">

                            <div class="mb-3">
                                <label for="login-email" class="form-label small fw-medium">Địa chỉ Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-body-tertiary text-body-secondary">
                                        <i class="fa-regular fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="login-email" name="txtemail"
                                        placeholder="name@example.com"
                                        value="<?= htmlspecialchars($loginEmail ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="login-pass" class="form-label small fw-medium mb-0">Mật khẩu</label>
                                    <a href="#" class="link-secondary small text-decoration-none">Quên mật khẩu?</a>
                                </div>
                                <div class="input-group mt-1">
                                    <span class="input-group-text bg-body-tertiary text-body-secondary">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="login-pass" name="txtmatkhau"
                                        placeholder="Nhập mật khẩu" required>
                                </div>
                            </div>

                            <button class="btn btn-danger w-100 fw-semibold py-2" type="submit">
                                <i class="fa-solid fa-right-to-bracket me-1"></i> Đăng nhập
                            </button>
                        </form>
                    </div>

                    <!-- TAB ĐĂNG KÝ -->
                    <div class="tab-pane fade <?= (isset($tabActive) && $tabActive === 'register') ? 'show active' : '' ?>"
                        id="tab-register" role="tabpanel">
                        <form action="index.php" method="post">
                            <input type="hidden" name="action" value="xldangky">

                            <div class="mb-3">
                                <label for="reg-name" class="form-label small fw-medium">Họ và tên</label>
                                <input type="text" class="form-control" id="reg-name" name="txthoten"
                                    placeholder="Nguyễn Văn A"
                                    value="<?= htmlspecialchars($regHoten ?? '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="reg-email" class="form-label small fw-medium">Địa chỉ Email</label>
                                <input type="email" class="form-control" id="reg-email" name="txtemail"
                                    placeholder="name@example.com"
                                    value="<?= htmlspecialchars($regEmail ?? '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="reg-phone" class="form-label small fw-medium">Số điện thoại</label>
                                <input type="tel" class="form-control" id="reg-phone" name="txtsodt"
                                    placeholder="0901234567" pattern="[0-9]{10}"
                                    value="<?= htmlspecialchars($regSodt ?? '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="reg-pass" class="form-label small fw-medium">Mật khẩu</label>
                                <input type="password" class="form-control" id="reg-pass" name="txtmatkhau"
                                    placeholder="Tối thiểu 6 ký tự" minlength="6" required>
                            </div>

                            <div class="mb-4">
                                <label for="reg-repass" class="form-label small fw-medium">Xác nhận mật khẩu</label>
                                <input type="password" class="form-control" id="reg-repass" name="txtxacnhanmatkhau"
                                    placeholder="Nhập lại mật khẩu" required>
                            </div>

                            <button class="btn btn-danger w-100 fw-semibold py-2" type="submit">
                                <i class="fa-solid fa-user-plus me-1"></i> Đăng ký tài khoản
                            </button>
                        </form>
                    </div>

                </div>

                <!-- Quay về trang chủ -->
                <div class="text-center mt-4 pt-2 border-top border-secondary-subtle">
                    <a href="index.php" class="link-secondary small text-decoration-none">
                        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại trang chủ
                    </a>
                </div>

            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
