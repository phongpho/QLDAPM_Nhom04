<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập &amp; Đăng ký - GYMBRO</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/main.css?v=<?= file_exists(__DIR__ . '/../assets/css/main.css') ? filemtime(__DIR__ . '/../assets/css/main.css') : time(); ?>" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', 'Be Vietnam Pro', sans-serif;
            background: linear-gradient(135deg, #1A1A1A 0%, #141414 45%, #2A1016 100%) !important;
            min-height: 100vh;
            color: #1A1A1A !important;
        }
        .gb-auth-wrapper {
            background: #FFFFFF !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 20px !important;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45) !important;
            max-width: 960px;
            width: 95%;
            overflow: hidden;
        }
        .gb-auth-hero {
            background: linear-gradient(135deg, #141414 0%, #201015 100%);
            position: relative;
            overflow: hidden;
        }
        .gb-auth-hero__img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top center;
            display: block;
        }
        .gb-auth-hero__overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(20,20,20,0.1) 0%, rgba(20,20,20,0.3) 50%, rgba(20,20,20,0.92) 100%);
        }
    </style>
</head>

<body>
    <main class="d-flex align-items-center justify-content-center min-vh-100 py-4 py-md-5">
        <div class="card gb-auth-wrapper border-0">
            <div class="row g-0 align-items-stretch">
                <div class="col-lg-5 d-none d-lg-block position-relative gb-auth-hero">
                    <img src="../assets/images/banners/tieuvy_bmi_popup.jpg?v=<?= file_exists(__DIR__ . '/../assets/images/banners/tieuvy_bmi_popup.jpg') ? filemtime(__DIR__ . '/../assets/images/banners/tieuvy_bmi_popup.jpg') : time(); ?>" 
                         alt="Hoa hậu Trần Tiểu Vy - GymBro Ambassador" 
                         class="gb-auth-hero__img">
                    <div class="gb-auth-hero__overlay"></div>
                    <div class="position-absolute bottom-0 start-0 w-100 p-4 text-white" style="z-index: 2;">
                        <span class="badge text-uppercase px-2 py-1 mb-2" style="background: #D02545; font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 700;">
                            GYMBRO AMBASSADOR
                        </span>
                        <h4 class="fw-bold mb-1 text-white" style="font-size: 1.25rem;">HOA HẬU TRẦN TIỂU VY</h4>
                        <p class="small text-white-50 mb-0" style="line-height: 1.5;">
                            Đồng hành kiến tạo vóc dáng &amp; tư vấn chỉ số thể hình BMI chuẩn form cùng GymBro.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-lg-7 d-flex flex-column justify-content-center p-4 p-sm-5">
                    <div class="text-center mb-3">
                        <a href="index.php" class="text-decoration-none d-inline-block">
                            <img src="../assets/images/systems/logo.png?v=<?= file_exists(__DIR__ . '/../assets/images/systems/logo.png') ? filemtime(__DIR__ . '/../assets/images/systems/logo.png') : time(); ?>" 
                                 alt="GYMBRO" height="46" style="height: 46px; max-height: 46px; width: auto; object-fit: contain; filter: brightness(0);">
                        </a>
                        <p class="small text-muted mt-2 mb-0">Đồng hành cùng mục tiêu tập luyện &amp; vóc dáng của bạn</p>
                    </div>

                    <?php if (isset($thongbao_loi) && !empty($thongbao_loi)): ?>
                        <div class="alert alert-danger py-2 px-3 small text-center" role="alert">
                            <?= htmlspecialchars($thongbao_loi) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($thongbao_thanhcong) && !empty($thongbao_thanhcong)): ?>
                        <div class="alert alert-success py-2 px-3 small text-center" role="alert">
                            <?= htmlspecialchars($thongbao_thanhcong) ?>
                        </div>
                    <?php endif; ?>

                    <ul class="nav nav-pills nav-fill mb-3 gb-auth-nav" id="authTab" role="tablist">
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

                        <div class="tab-pane fade <?= (!isset($tabActive) || $tabActive === 'login') ? 'show active' : '' ?>"
                            id="tab-login" role="tabpanel">
                            <form action="index.php" method="post">
                                <input type="hidden" name="action" value="xldangnhap">

                                <div class="mb-3">
                                    <label for="login-email" class="form-label small fw-semibold text-dark">Địa chỉ Email</label>
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
                                        <label for="login-pass" class="form-label small fw-semibold mb-0 text-dark">Mật khẩu</label>
                                        <a href="#" class="text-muted small text-decoration-none">Quên mật khẩu?</a>
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
                                    Đăng nhập
                                </button>
                            </form>
                        </div>

                        <div class="tab-pane fade <?= (isset($tabActive) && $tabActive === 'register') ? 'show active' : '' ?>"
                            id="tab-register" role="tabpanel">
                            <form action="index.php" method="post">
                                <input type="hidden" name="action" value="xldangky">

                                <div class="mb-2">
                                    <label for="reg-name" class="form-label small fw-semibold text-dark">Họ và tên</label>
                                    <input type="text" class="form-control" id="reg-name" name="txthoten"
                                        placeholder="Nguyễn Văn A"
                                        value="<?= htmlspecialchars($regHoten ?? '') ?>" required>
                                </div>

                                <div class="mb-2">
                                    <label for="reg-email" class="form-label small fw-semibold text-dark">Địa chỉ Email</label>
                                    <input type="email" class="form-control" id="reg-email" name="txtemail"
                                        placeholder="name@example.com"
                                        value="<?= htmlspecialchars($regEmail ?? '') ?>" required>
                                </div>

                                <div class="mb-2">
                                    <label for="reg-phone" class="form-label small fw-semibold text-dark">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="reg-phone" name="txtsodt"
                                        placeholder="0901234567" pattern="[0-9]{10}"
                                        value="<?= htmlspecialchars($regSodt ?? '') ?>" required>
                                </div>

                                <div class="mb-2">
                                    <label for="reg-pass" class="form-label small fw-semibold text-dark">Mật khẩu</label>
                                    <input type="password" class="form-control" id="reg-pass" name="txtmatkhau"
                                        placeholder="Tối thiểu 6 ký tự" minlength="6" required>
                                </div>

                                <div class="mb-3">
                                    <label for="reg-repass" class="form-label small fw-semibold text-dark">Xác nhận mật khẩu</label>
                                    <input type="password" class="form-control" id="reg-repass" name="txtxacnhanmatkhau"
                                        placeholder="Nhập lại mật khẩu" required>
                                </div>

                                <button class="btn btn-auth-submit w-100 fw-semibold py-2" type="submit">
                                    Đăng ký tài khoản
                                </button>
                            </form>
                        </div>

                    </div>

                    <div class="text-center mt-3 pt-3 border-top">
                        <a href="index.php" class="text-muted small text-decoration-none">
                            &larr; Quay lại trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
