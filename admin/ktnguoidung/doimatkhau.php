<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["nguoidung"])) {
    header("Location: index.php?action=dangnhap");
    exit();
}

$currentUser = $_SESSION["nguoidung"];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đổi mật khẩu - GymBro Admin</title>

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
</head>

<body>
    <div class="wrapper">
        <?php include('../inc/sidebar.php'); ?>

        <div class="main">
            <?php include('../inc/header.php'); ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <!-- Breadcrumbs & Tiêu đề -->
                    <div class="gb-breadcrumb">
                        <a href="index.php"><i class="fa fa-sliders me-1"></i>Bảng điều khiển</a>
                        <i class="fa fa-chevron-right" style="font-size: 0.65rem;"></i>
                        <span class="text-dark fw-semibold">Đổi mật khẩu</span>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                            <div class="gb-edit-card shadow-sm">
                                <div class="gb-edit-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="gb-icon-badge" style="background-color: #fffbeb; color: #d97706;">
                                            <i class="fa fa-key"></i>
                                        </div>
                                        <div>
                                            <h5 class="gb-edit-title mb-0">Đổi mật khẩu tài khoản</h5>
                                            <small class="text-muted">Cập nhật mật khẩu bảo vệ tài khoản định kỳ</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-4">

                                    <div class="gb-summary-box mb-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fa fa-user-circle text-primary fs-5"></i>
                                            <div>
                                                <div class="fw-bold text-dark small"><?= htmlspecialchars($currentUser["hoten"]) ?></div>
                                                <div class="text-muted small"><?= htmlspecialchars($currentUser["email"]) ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (isset($msg_error)): ?>
                                        <div class="alert alert-danger alert-dismissible fade show border-0 d-flex align-items-center gap-2 mb-3" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
                                            <i class="fa fa-circle-exclamation fs-5"></i>
                                            <div><?= htmlspecialchars($msg_error) ?></div>
                                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($msg_success)): ?>
                                        <div class="alert alert-success alert-dismissible fade show border-0 d-flex align-items-center gap-2 mb-3" role="alert" style="background-color: #ecfdf5; color: #065f46; border-left: 4px solid #10b981 !important;">
                                            <i class="fa fa-circle-check fs-5"></i>
                                            <div><?= htmlspecialchars($msg_success) ?></div>
                                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <form method="post" action="index.php" onsubmit="return validatePasswordChange();">
                                        <input type="hidden" name="action" value="xldoimatkhau">

                                        <!-- Mật khẩu hiện tại -->
                                        <div class="mb-3">
                                            <label class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted border-end-0"><i class="fa fa-lock"></i></span>
                                                <input class="form-control border-start-0 border-end-0" type="password" id="txtmatkhaucu" name="txtmatkhaucu" placeholder="Nhập mật khẩu đang sử dụng" required>
                                                <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePassword('txtmatkhaucu', 'eyeOld')">
                                                    <i class="fa fa-eye" id="eyeOld"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Mật khẩu mới -->
                                        <div class="mb-3">
                                            <label class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted border-end-0"><i class="fa fa-key"></i></span>
                                                <input class="form-control border-start-0 border-end-0" type="password" id="txtmatkhaumoi" name="txtmatkhaumoi" placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)" required minlength="6">
                                                <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePassword('txtmatkhaumoi', 'eyeNew')">
                                                    <i class="fa fa-eye" id="eyeNew"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">Mật khẩu mới phải có tối thiểu 6 ký tự và khác mật khẩu hiện tại.</div>
                                        </div>

                                        <!-- Xác nhận mật khẩu mới -->
                                        <div class="mb-4">
                                            <label class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted border-end-0"><i class="fa fa-shield-halved"></i></span>
                                                <input class="form-control border-start-0 border-end-0" type="password" id="txtxacnhanmatkhau" name="txtxacnhanmatkhau" placeholder="Nhập lại mật khẩu mới vừa đặt" required>
                                                <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePassword('txtxacnhanmatkhau', 'eyeConfirm')">
                                                    <i class="fa fa-eye" id="eyeConfirm"></i>
                                                </button>
                                            </div>
                                            <div id="matchError" class="text-danger small mt-1 d-none">Mật khẩu xác nhận không trùng khớp!</div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                            <a href="index.php" class="btn btn-light px-3">
                                                <i class="fa fa-arrow-left me-1"></i> Quay lại
                                            </a>
                                            <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                                <i class="fa fa-save me-1"></i> Cập nhật mật khẩu
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

            <?php include('../inc/footer.php'); ?>
        </div>
    </div>

    <script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    function validatePasswordChange() {
        const newPass = document.getElementById('txtmatkhaumoi').value;
        const confirmPass = document.getElementById('txtxacnhanmatkhau').value;
        const oldPass = document.getElementById('txtmatkhaucu').value;
        const matchError = document.getElementById('matchError');

        if (newPass.length < 6) {
            alert('Mật khẩu mới phải có tối thiểu 6 ký tự!');
            return false;
        }

        if (newPass === oldPass) {
            alert('Mật khẩu mới không được trùng với mật khẩu hiện tại!');
            return false;
        }

        if (newPass !== confirmPass) {
            matchError.classList.remove('d-none');
            return false;
        } else {
            matchError.classList.add('d-none');
        }

        return true;
    }

    document.getElementById('txtxacnhanmatkhau').addEventListener('input', function() {
        const newPass = document.getElementById('txtmatkhaumoi').value;
        const matchError = document.getElementById('matchError');
        if (this.value && this.value !== newPass) {
            matchError.classList.remove('d-none');
        } else {
            matchError.classList.add('d-none');
        }
    });
    </script>
</body>

</html>