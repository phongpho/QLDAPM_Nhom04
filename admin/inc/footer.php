<footer class="footer">
    <div class="container-fluid">
        <div class="row text-muted align-items-center">
            <div class="col-12 col-sm-6 text-center text-sm-start mb-2 mb-sm-0">
                <p class="mb-0">
                    <strong class="text-dark">GymBro Admin</strong> &copy; <?= date('Y') ?>. Hệ thống quản trị nội bộ.
                </p>
            </div>
            <div class="col-12 col-sm-6 text-center text-sm-end">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item me-3">
                        <span class="text-success small d-inline-flex align-items-center gap-1">
                            <span class="d-inline-block rounded-circle bg-success" style="width: 7px; height: 7px;"></span>
                            Máy chủ vận hành ổn định
                        </span>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-muted small text-decoration-none" href="../ktnguoidung/index.php?action=hoso">Trợ giúp & Hướng dẫn</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- Tự động ẩn thông báo thành công hoặc thất bại sau 10 giây -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const flashAlerts = document.querySelectorAll(".alert");
        if (flashAlerts.length > 0) {
            setTimeout(function () {
                flashAlerts.forEach(function (alertElem) {
                    alertElem.style.transition = "opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), max-height 0.6s ease, margin 0.6s ease, padding 0.6s ease";
                    alertElem.style.opacity = "0";
                    alertElem.style.transform = "translateY(-6px)";
                    setTimeout(function () {
                        if (window.bootstrap && bootstrap.Alert) {
                            try {
                                const bsInst = bootstrap.Alert.getOrCreateInstance(alertElem);
                                if (bsInst) {
                                    bsInst.close();
                                    return;
                                }
                            } catch (err) {}
                        }
                        alertElem.remove();
                    }, 600);
                });
            }, 10000); // 10 giay
        }
    });
</script>