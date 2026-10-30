<!DOCTYPE html>
<html lang="vi" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập &amp; Đăng ký - GYMBRO</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,600;0,700;0,800;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/main.css?v=<?= file_exists(__DIR__ . '/../assets/css/main.css') ? filemtime(__DIR__ . '/../assets/css/main.css') : time(); ?>" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 50% 10%, rgba(249, 115, 22, 0.12) 0%, transparent 50%),
                        radial-gradient(circle at 90% 80%, rgba(239, 68, 68, 0.08) 0%, transparent 40%),
                        #070a0f !important;
            min-height: 100vh;
        }
        .gb-auth-card {
            background: #141c2b !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 20px !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7), 0 0 30px rgba(249, 115, 22, 0.1) !important;
            width: 440px;
            max-width: 94%;
        }
        .gb-auth-nav {
            background: #101622;
            padding: 4px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .gb-auth-nav .nav-link {
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.02em;
            color: #94a3b8 !important;
            transition: all 0.2s ease;
        }
        .gb-auth-nav .nav-link.active {
            background-color: var(--gs-primary) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.35);
        }
        .form-control, .input-group-text {
            background-color: #101622 !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
        }
        .form-control:focus {
            border-color: var(--gs-primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.2) !important;
            background-color: #141c2b !important;
        }
        .btn-auth-submit {
            background-color: var(--gs-primary);
            border-color: var(--gs-primary);
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(249, 115, 22, 0.35);
        }
        .btn-auth-submit:hover {
            background-color: #ea580c;
            border-color: #ea580c;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.5);
        }
    </style>
</head>

<body>
    <main class="d-flex align-items-center justify-content-center min-vh-100 py-5">
        <div class="card gb-auth-card">
            <div class="card-body p-4 p-sm-5">

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
                <ul class="nav nav-pills nav-fill mb-4 gb-auth-nav" id="authTab" role="tablist">
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
                                <label for="login-email" class="form-label small fw-medium text-light">Địa chỉ Email</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa-regular fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="login-email" name="txtemail"
                                        placeholder="name@example.com"
                                        value="<?= htmlspecialchars($loginEmail ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="login-pass" class="form-label small fw-medium mb-0 text-light">Mật khẩu</label>
                                    <a href="#" class="link-secondary small text-decoration-none">Quên mật khẩu?</a>
                                </div>
                                <div class="input-group mt-1">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="login-pass" name="txtmatkhau"
                                        placeholder="Nhập mật khẩu" required>
                                </div>
                            </div>

                            <button class="btn btn-auth-submit w-100 fw-semibold py-2" type="submit">
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

                            <button class="btn btn-auth-submit w-100 fw-semibold py-2" type="submit">
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
