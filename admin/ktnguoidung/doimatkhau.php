<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu chưa đăng nhập thì bắt quay về trang login
if (!isset($_SESSION["nguoidung"])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
}

include("../inc/top.php"); 
?>

<div class="row">
    <div class="col-12 col-md-10 m-auto">
        <div class="card p-5">
            <div class="card-header">
                <h4 class="text-info text-center">ĐỔI MẬT KHẨU</h4>
            </div>
            <div class="card-body">
                <?php if (isset($msg_error)): ?>
                    <div class="alert alert-danger text-center"><?php echo $msg_error; ?></div>
                <?php endif; ?>
                <?php if (isset($msg_success)): ?>
                    <div class="alert alert-success text-center"><?php echo $msg_success; ?></div>
                <?php endif; ?>

                <form method="post" action="../ktnguoidung/index.php">
                    <input type="hidden" name="action" value="xldoimatkhau">

                    <div class="my-3">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input class="form-control" type="password" name="txtmatkhaucu" placeholder="Nhập mật khẩu hiện tại" required>
                    </div>

                    <div class="my-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input class="form-control" type="password" name="txtmatkhaumoi" placeholder="Nhập mật khẩu mới" required>
                    </div>

                    <div class="my-3">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input class="form-control" type="password" name="txtxacnhanmatkhau" placeholder="Nhập lại mật khẩu mới" required>
                    </div>

                    <div class="my-3 text-center">
                        <input class="btn btn-primary" type="submit" value="Cập nhật">
                        <input class="btn btn-warning" type="reset" value="Hủy bỏ">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../inc/bottom.php"); ?>