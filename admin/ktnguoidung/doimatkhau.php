<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu chưa đăng nhập thì bắt quay về trang login
if (!isset($_SESSION["nguoidung"])) {
    header("Location: index.php?action=dangnhap");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đổi mật khẩu - GymBro Admin</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
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
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-8 col-lg-6">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white border-bottom py-3">
                                    <h4 class="card-title text-primary mb-0 fw-bold">
                                        <i class="fa fa-key me-2"></i>ĐỔI MẬT KHẨU
                                    </h4>
                                    <small class="text-muted">Tài khoản: <?= htmlspecialchars($_SESSION["nguoidung"]["hoten"] ?? "") ?> (<?= htmlspecialchars($_SESSION["nguoidung"]["email"] ?? "") ?>)</small>
                                </div>
                                <div class="card-body p-4">
                                    <?php if (isset($msg_error)): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="fa fa-exclamation-triangle me-2"></i><?= $msg_error ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($msg_success)): ?>
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="fa fa-check-circle me-2"></i><?= $msg_success ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <form method="post" action="index.php" onsubmit="return validatePasswordChange();">
                                        <input type="hidden" name="action" value="xldoimatkhau">

                                        <!-- Mật khẩu hiện tại -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                                <input class="form-control" type="password" id="txtmatkhaucu" name="txtmatkhaucu" placeholder="Nhập mật khẩu hiện tại" required>
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('txtmatkhaucu', 'eyeOld')">
                                                    <i class="fa fa-eye" id="eyeOld"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Mật khẩu mới -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Mật khẩu mới <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-key"></i></span>
                                                <input class="form-control" type="password" id="txtmatkhaumoi" name="txtmatkhaumoi" placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)" required minlength="6">
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('txtmatkhaumoi', 'eyeNew')">
                                                    <i class="fa fa-eye" id="eyeNew"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">Mật khẩu nên chứa ít nhất 6 ký tự.</div>
                                        </div>

                                        <!-- Xác nhận mật khẩu mới -->
                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-shield-alt"></i></span>
                                                <input class="form-control" type="password" id="txtxacnhanmatkhau" name="txtxacnhanmatkhau" placeholder="Nhập lại mật khẩu mới" required>
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('txtxacnhanmatkhau', 'eyeConfirm')">
                                                    <i class="fa fa-eye" id="eyeConfirm"></i>
                                                </button>
                                            </div>
                                            <div id="matchError" class="text-danger small mt-1 d-none">Mật khẩu xác nhận không trùng khớp!</div>
                                        </div>

                                        <div class="d-flex justify-content-between pt-2">
                                            <a href="index.php" class="btn btn-secondary px-3">
                                                <i class="fa fa-arrow-left me-1"></i> Quay lại
                                            </a>
                                            <button type="submit" class="btn btn-primary px-4">
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