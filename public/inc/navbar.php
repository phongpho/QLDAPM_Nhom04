<?php
/* ============================================================
   GYMSHOP - Header / Navbar
   ============================================================ */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($conn)) {
    require_once dirname(__DIR__, 2) . '/model/database.php';
}

$nguoidung_id = isset($_SESSION['nguoidung_id']) ? (int) $_SESSION['nguoidung_id'] : 0;
$soGioHang   = 0;
$soYeuThich  = 0;

if ($nguoidung_id > 0) {
    try {
        $st = $conn->prepare("SELECT COALESCE(SUM(soluong), 0) FROM giohang WHERE nguoidung_id = :uid");
        $st->execute(array(':uid' => $nguoidung_id));
        $soGioHang = (int) $st->fetchColumn();

        $st2 = $conn->prepare("SELECT COUNT(*) FROM yeuthich WHERE nguoidung_id = :uid");
        $st2->execute(array(':uid' => $nguoidung_id));
        $soYeuThich = (int) $st2->fetchColumn();
    } catch (Exception $e) {
        $soGioHang  = 0;
        $soYeuThich = 0;
    }
}

$hrefTaiKhoan = ($nguoidung_id > 0) ? 'taikhoan.php' : 'dangnhap.php';

$marqueeItems = array(
    array('fa-solid fa-truck-fast', 'Freeship cho đơn từ 500.000đ'),
    array('fa-solid fa-ticket', 'Nhập WELCOME10 - giảm 10% cho khách mới'),
    array('fa-solid fa-bolt', 'SALE15 - giảm 15% cho đơn từ 800.000đ'),
    array('fa-solid fa-rotate-left', 'Miễn phí đổi size trong 7 ngày'),
);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYMSHOP - Đồ tập gym & Thực phẩm bổ sung cá nhân hóa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/main.css" rel="stylesheet">
</head>
<body>

<!-- TOP BAR: thông báo chạy chữ -->
<div class="gs-topbar">
    <div class="gs-marquee">
        <div class="gs-marquee__group">
            <?php foreach ($marqueeItems as $item): ?>
                <span class="gs-marquee__item"><i class="<?php echo $item[0]; ?>"></i><?php echo $item[1]; ?></span>
            <?php endforeach; ?>
        </div>
        <div class="gs-marquee__group" aria-hidden="true">
            <?php foreach ($marqueeItems as $item): ?>
                <span class="gs-marquee__item"><i class="<?php echo $item[0]; ?>"></i><?php echo $item[1]; ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- MAIN HEADER -->
<header class="gs-header" id="gsHeader">
    <div class="container">
        <div class="gs-header__inner">
            <a class="gs-logo" href="index.php">GYM<em>SHOP</em></a>

            <nav class="gs-nav d-none d-lg-flex">
                <a class="gs-nav__link" href="sanpham.php?doituong=nam">Nam</a>
                <a class="gs-nav__link" href="sanpham.php?doituong=nu">Nữ</a>
                <a class="gs-nav__link" href="sanpham.php?loai=phukien">Phụ kiện</a>
                <a class="gs-nav__link" href="sanpham.php?loai=tpbs">Thực phẩm bổ sung</a>
                <a class="gs-nav__link" href="goy.php">
                    Gợi ý theo chỉ số
                    <span class="gs-badge-personal">Cá nhân hóa</span>
                </a>
            </nav>

            <div class="gs-header-actions">
                <button class="gs-icon-btn d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#gsMobileMenu" aria-controls="gsMobileMenu" aria-expanded="false" aria-label="Mở menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <button class="gs-icon-btn" type="button" data-bs-toggle="modal" data-bs-target="#gsSearchModal" aria-label="Tìm kiếm">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a class="gs-icon-btn" href="yeuthich.php" aria-label="Yêu thích">
                    <i class="fa-regular fa-heart"></i>
                    <span class="gs-count-badge"><?php echo $soYeuThich > 0 ? $soYeuThich : ''; ?></span>
                </a>
                <a class="gs-icon-btn" href="<?php echo $hrefTaiKhoan; ?>" aria-label="Tài khoản">
                    <i class="fa-regular fa-user"></i>
                </a>
                <a class="gs-icon-btn" href="giohang.php" aria-label="Giỏ hàng">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span class="gs-count-badge"><?php echo $soGioHang > 0 ? $soGioHang : ''; ?></span>
                </a>
            </div>
        </div>
    </div>

    <!-- MOBILE MENU -->
    <div class="collapse d-lg-none gs-mobile-menu" id="gsMobileMenu">
        <a class="gs-mobile-menu__link" href="sanpham.php?doituong=nam">Nam</a>
        <a class="gs-mobile-menu__link" href="sanpham.php?doituong=nu">Nữ</a>
        <a class="gs-mobile-menu__link" href="sanpham.php?loai=phukien">Phụ kiện</a>
        <a class="gs-mobile-menu__link" href="sanpham.php?loai=tpbs">Thực phẩm bổ sung</a>
        <a class="gs-mobile-menu__link" href="goy.php">
            Gợi ý theo chỉ số
            <span class="gs-badge-personal">Cá nhân hóa</span>
        </a>
    </div>
</header>

<!-- SEARCH MODAL -->
<div class="modal fade gs-search-modal" id="gsSearchModal" tabindex="-1" aria-labelledby="gsSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="gs-search-wrap">
                <i class="fa-solid fa-magnifying-glass gs-search-icon"></i>
                <form action="sanpham.php" method="get" class="gs-search-form">
                    <input type="text" name="q" class="gs-search-input" placeholder="Tìm kiếm sản phẩm, thương hiệu, mục tiêu tập luyện..." autocomplete="off" aria-label="Tìm kiếm">
                </form>
                <button class="gs-icon-btn" type="button" data-bs-dismiss="modal" aria-label="Đóng">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="gs-search-suggestions">
                <span class="gs-search-suggestions__label">Gợi ý tìm kiếm:</span>
                <a class="gs-chip" href="sanpham.php?q=whey">Whey Protein</a>
                <a class="gs-chip" href="sanpham.php?q=legging">Legging</a>
                <a class="gs-chip" href="sanpham.php?q=creatine">Creatine</a>
                <a class="gs-chip" href="sanpham.php?doituong=nam">Đồ tập nam</a>
                <a class="gs-chip" href="sanpham.php?doituong=nu">Đồ tập nữ</a>
            </div>
        </div>
    </div>
</div>
