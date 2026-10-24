<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng nhập hệ thống - GymBro Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="../inc/css/app.css" rel="stylesheet">
    <script src="../inc/js/app.js"></script>

    <!-- Bootstrap 5 & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- Unified Admin Design System -->
    <link href="../inc/css/main.css" rel="stylesheet">
</head>

<body style="background-color: #0f172a;">
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-4 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="text-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 p-3 shadow-lg mb-3" style="width: 56px; height: 56px;">
                                <i class="fa fa-dumbbell fs-3"></i>
                            </div>
                            <h1 class="h3 fw-bold text-white mb-1">GymBro Admin</h1>
                            <p class="text-muted small">Cổng thông tin quản trị và vận hành nội bộ</p>
                        </div>

                        <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                            <div class="card-body p-4 p-sm-5">
                                <h4 class="fw-bold text-dark mb-1">Đăng nhập</h4>
                                <p class="text-muted small mb-4">Nhập tài khoản quản trị để tiếp tục phiên làm việc</p>

                                <?php if (!empty($thongbao)): ?>
                                    <div class="alert alert-danger alert-dismissible fade show border-0 d-flex align-items-center gap-2 mb-3 py-2" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
                                        <i class="fa fa-circle-exclamation fs-5"></i>
                                        <div class="small fw-semibold"><?= htmlspecialchars($thongbao) ?></div>
                                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <form action="index.php" method="post">
                                    <input type="hidden" name="action" value="xldangnhap">

                                    <div class="mb-3">
                                        <label class="form-label">Email tài khoản</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-muted border-end-0"><i class="fa fa-envelope"></i></span>
                                            <input class="form-control border-start-0" type="email" name="txtemail" placeholder="name@gymbro.vn" required autofocus />
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Mật khẩu</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-muted border-end-0"><i class="fa fa-lock"></i></span>
                                            <input class="form-control border-start-0" type="password" name="txtmatkhau" placeholder="Nhập mật khẩu" required />
                                        </div>
                                    </div>

                                    <div class="d-grid mb-2">
                                        <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                            <i class="fa fa-right-to-bracket me-1"></i> Đăng nhập hệ thống
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="text-center mt-3 text-muted small">
                            GymBro Admin &copy; <?= date('Y') ?>. Hệ thống quản trị an toàn.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>