<?php
/* ============================================================
   GYMSHOP - Trang chủ (Client Homepage) - DỮ LIỆU CỨNG
   Không kết nối CSDL: toàn bộ dữ liệu mẫu khai báo trong file này.
   ============================================================ */


if (!function_exists('gs_tien')) {
    function gs_tien($n)
    {
        return number_format((float) $n, 0, ',', '.');
    }
}


if (!function_exists('gs_phan_tram')) {
    function gs_phan_tram($giagoc, $giaban)
    {
        $giagoc = (float) $giagoc;
        $giaban = (float) $giaban;
        if ($giagoc > 0 && $giaban < $giagoc) {
            return (int) round(($giagoc - $giaban) / $giagoc * 100);
        }
        return 0;
    }
}


if (!function_exists('gs_sizes')) {
    function gs_sizes($sizes)
    {
        if (empty($sizes)) {
            return array();
        }
        $mang = array_map('trim', explode(',', $sizes));
        return array_values(array_filter($mang, 'strlen'));
    }
}


if (!function_exists('gs_la_moi')) {
    function gs_la_moi($ngaytao)
    {
        if (empty($ngaytao)) {
            return false;
        }
        return strtotime($ngaytao) >= strtotime('-30 days');
    }
}

/* ------------------------------------------------------------
   DỮ LIỆU CỨNG: 4 danh mục nổi bật (Shop by Category)
   ------------------------------------------------------------ */
$danhMucNoiBat = array(
    array(
        'tieude' => 'Đồ tập nam',
        'soluong' => 5,
        'link' => 'sanpham.php?loai=quanao&doituong=nam',
        'hinhanh' => 'https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?auto=format&fit=crop&w=800&q=80',
    ),
    array(
        'tieude' => 'Đồ tập nữ',
        'soluong' => 4,
        'link' => 'sanpham.php?loai=quanao&doituong=nu',
        'hinhanh' => 'https://images.unsplash.com/photo-1546483875-ad9014c88eba?auto=format&fit=crop&w=800&q=80',
    ),
    array(
        'tieude' => 'Whey & TPBS',
        'soluong' => 8,
        'link' => 'sanpham.php?loai=tpbs',
        'hinhanh' => 'https://images.unsplash.com/photo-1594882645126-14020914d58d?auto=format&fit=crop&w=800&q=80',
    ),
    array(
        'tieude' => 'Phụ kiện',
        'soluong' => 3,
        'link' => 'sanpham.php?loai=phukien',
        'hinhanh' => 'https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?auto=format&fit=crop&w=800&q=80',
    ),
);

/* ------------------------------------------------------------
   DỮ LIỆU CỨNG: 8 sản phẩm bán chạy (sắp theo luotmua giảm dần)
   Cấu trúc khớp bảng sanpham: id, tensanpham, giaban, giagoc,
   hinhanh, sizes, doituong, noibat, luotmua, tendanhmuc, ngaytao
   ------------------------------------------------------------ */
$spBanChay = array(
    array(
        'id' => 10, 'tensanpham' => 'Bình lắc Shaker 700ml', 'giaban' => 99000, 'giagoc' => 129000,
        'hinhanh' => 'shaker-700.jpg', 'sizes' => null, 'doituong' => 'unisex',
        'noibat' => 1, 'luotmua' => 120, 'tendanhmuc' => 'Bình nước & túi', 'ngaytao' => '2026-10-10 08:00:00',
    ),
    array(
        'id' => 12, 'tensanpham' => 'Whey Gold Standard 5lb', 'giaban' => 1790000, 'giagoc' => 1990000,
        'hinhanh' => 'whey-gold.jpg', 'sizes' => null, 'doituong' => 'unisex',
        'noibat' => 1, 'luotmua' => 88, 'tendanhmuc' => 'Whey Protein', 'ngaytao' => '2026-10-10 08:00:00',
    ),
    array(
        'id' => 11, 'tensanpham' => 'Whey Isolate 2lb', 'giaban' => 1250000, 'giagoc' => 1450000,
        'hinhanh' => 'whey-isolate.jpg', 'sizes' => null, 'doituong' => 'unisex',
        'noibat' => 1, 'luotmua' => 66, 'tendanhmuc' => 'Whey Protein', 'ngaytao' => '2026-10-10 08:00:00',
    ),
    array(
        'id' => 14, 'tensanpham' => 'Creatine Monohydrate 300g', 'giaban' => 390000, 'giagoc' => 450000,
        'hinhanh' => 'creatine.jpg', 'sizes' => null, 'doituong' => 'unisex',
        'noibat' => 1, 'luotmua' => 61, 'tendanhmuc' => 'Creatine', 'ngaytao' => '2026-10-10 08:00:00',
    ),
    array(
        'id' => 16, 'tensanpham' => 'L-Carnitine 3000 dạng nước', 'giaban' => 420000, 'giagoc' => 490000,
        'hinhanh' => 'l-carnitine.jpg', 'sizes' => null, 'doituong' => 'unisex',
        'noibat' => 1, 'luotmua' => 57, 'tendanhmuc' => 'Hỗ trợ đốt mỡ', 'ngaytao' => '2026-10-10 08:00:00',
    ),
    array(
        'id' => 5, 'tensanpham' => 'Quần legging nữ cạp cao', 'giaban' => 399000, 'giagoc' => 479000,
        'hinhanh' => 'legging-caocap.jpg', 'sizes' => 'S,M,L', 'doituong' => 'nu',
        'noibat' => 1, 'luotmua' => 52, 'tendanhmuc' => 'Quần tập', 'ngaytao' => '2026-10-10 08:00:00',
    ),
    array(
        'id' => 18, 'tensanpham' => 'Vitamin tổng hợp Daily 90 viên', 'giaban' => 320000, 'giagoc' => 380000,
        'hinhanh' => 'vitamin.jpg', 'sizes' => null, 'doituong' => 'unisex',
        'noibat' => 0, 'luotmua' => 48, 'tendanhmuc' => 'Vitamin & Khoáng chất', 'ngaytao' => '2026-10-10 08:00:00',
    ),
    array(
        'id' => 4, 'tensanpham' => 'Áo bra thể thao nữ Support', 'giaban' => 259000, 'giagoc' => 319000,
        'hinhanh' => 'bra-support.jpg', 'sizes' => 'S,M,L', 'doituong' => 'nu',
        'noibat' => 1, 'luotmua' => 45, 'tendanhmuc' => 'Áo tập', 'ngaytao' => '2026-10-10 08:00:00',
    ),
);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymShop - Trang chủ</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- Design System (nhúng trong main.php) -->
    <style>
        :root {
            --gs-black: #111111;
            --gs-white: #ffffff;
            --gs-grey-bg: #f5f5f5;
            --gs-text: #000000;
            --gs-muted: #767676;
            --gs-border: #e5e5e5;
            --gs-radius: 4px;
            --gs-font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            --gs-shadow-lg: 0 20px 50px rgba(0, 0, 0, 0.15);
            --gs-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        *,
        *::before,
        *::after { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--gs-font);
            color: var(--gs-text);
            background: var(--gs-white);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; }
        img { max-width: 100%; display: block; }

        /* Navbar Bootstrap cua nhom: hover trang + shadow khi cuon */
        .hover-white:hover { color: #ffffff !important; }
        .navbar.sticky-top.is-scrolled { box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.28) !important; }

        /* BUTTONS */
        .btn-gs {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-family: var(--gs-font);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border-radius: var(--gs-radius);
            border: 1px solid transparent;
            padding: 0.8rem 1.75rem;
            font-size: 0.82rem;
            line-height: 1;
            cursor: pointer;
            transition: var(--gs-transition);
            white-space: nowrap;
        }

        .btn-gs--dark { background: var(--gs-black); color: var(--gs-white); border-color: var(--gs-black); }
        .btn-gs--dark:hover { background: var(--gs-white); color: var(--gs-black); }
        .btn-gs--light { background: var(--gs-white); color: var(--gs-black); border-color: var(--gs-white); }
        .btn-gs--light:hover { background: transparent; color: var(--gs-white); border-color: var(--gs-white); }
        .btn-gs--outline { background: transparent; color: var(--gs-black); border-color: var(--gs-black); }
        .btn-gs--outline:hover { background: var(--gs-black); color: var(--gs-white); }
        .btn-gs--outline-light { background: transparent; color: var(--gs-white); border-color: rgba(255, 255, 255, 0.85); }
        .btn-gs--outline-light:hover { background: var(--gs-white); color: var(--gs-black); border-color: var(--gs-white); }
        .btn-gs--lg { padding: 1rem 2.4rem; font-size: 0.9rem; }

        /* HERO */
        .gs-hero {
            position: relative;
            min-height: min(88vh, 820px);
            display: flex;
            align-items: center;
            background: var(--gs-black);
            color: #ffffff;
            overflow: hidden;
        }

        .gs-hero__bg { position: absolute; inset: 0; background-size: cover; background-position: center; }

        .gs-hero__overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(17, 17, 17, 0.86) 0%, rgba(17, 17, 17, 0.55) 48%, rgba(17, 17, 17, 0.12) 100%);
        }

        .gs-hero__content { position: relative; z-index: 2; max-width: 660px; padding: 4.5rem 0; }

        .gs-hero__kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: #ffffff;
            margin-bottom: 1.2rem;
        }

        .gs-hero__kicker::before { content: ""; width: 42px; height: 2px; background: #ffffff; }

        .gs-hero__title {
            font-size: clamp(2.4rem, 6vw, 4.6rem);
            font-weight: 900;
            letter-spacing: -1px;
            line-height: 0.98;
            text-transform: uppercase;
            margin: 0 0 1.25rem;
        }

        .gs-hero__sub {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.85);
            max-width: 520px;
            line-height: 1.65;
            margin-bottom: 2rem;
        }

        .gs-hero__cta { display: flex; flex-wrap: wrap; gap: 0.9rem; }

        /* SECTION CHUNG */
        .gs-section { padding: 4.5rem 0; }
        .gs-section--grey { background: var(--gs-grey-bg); }

        .gs-section__head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            margin-bottom: 2.25rem;
        }

        .gs-section__title {
            font-weight: 800;
            letter-spacing: -0.5px;
            text-transform: uppercase;
            font-size: 1.7rem;
            line-height: 1.1;
            margin: 0;
        }

        .gs-section__sub { color: var(--gs-muted); font-size: 0.9rem; max-width: 480px; line-height: 1.5; }

        .gs-section__link {
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--gs-black);
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .gs-section__link i { transition: transform 0.25s ease; }
        .gs-section__link:hover i { transform: translateX(4px); }

        /* USP CA NHAN HOA */
        .gs-usps {
            background: var(--gs-black);
            color: #ffffff;
            padding: 5.5rem 0;
            position: relative;
            overflow: hidden;
        }

        .gs-usps__watermark {
            position: absolute;
            right: -2rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16rem;
            font-weight: 900;
            letter-spacing: -8px;
            color: rgba(255, 255, 255, 0.04);
            line-height: 1;
            pointer-events: none;
            user-select: none;
            text-transform: uppercase;
        }

        .gs-usps__kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: #ffffff;
        }

        .gs-usps__kicker::before { content: ""; width: 34px; height: 2px; background: #ffffff; }

        .gs-usps__title {
            font-size: clamp(1.8rem, 3.5vw, 2.6rem);
            font-weight: 800;
            letter-spacing: -0.5px;
            text-transform: uppercase;
            line-height: 1.1;
            margin: 1rem 0 1.25rem;
        }

        .gs-usps__text {
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.95rem;
            line-height: 1.7;
            max-width: 520px;
            margin-bottom: 1.5rem;
        }

        .gs-usps__list {
            list-style: none;
            margin: 0 0 0.5rem;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .gs-usps__list li { display: flex; align-items: center; gap: 0.7rem; font-size: 0.9rem; color: #e5e5e5; }

        .gs-usps__list i {
            color: #ffffff;
            width: 22px;
            height: 22px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.62rem;
            flex-shrink: 0;
        }

        .gs-consult-card {
            background: #ffffff;
            color: var(--gs-black);
            border-radius: 8px;
            padding: 2rem;
            box-shadow: var(--gs-shadow-lg);
            position: relative;
            z-index: 2;
        }

        .gs-consult-card__title {
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: -0.3px;
            font-size: 1.05rem;
            margin-bottom: 1.4rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .gs-consult-card label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--gs-muted);
            margin-bottom: 0.35rem;
            display: block;
        }

        .gs-consult-card input,
        .gs-consult-card select {
            width: 100%;
            height: 46px;
            border: 1px solid var(--gs-border);
            border-radius: var(--gs-radius);
            padding: 0 0.9rem;
            font-size: 0.9rem;
            color: var(--gs-black);
            background: #ffffff;
            outline: none;
            transition: border-color 0.2s ease;
        }

        .gs-consult-card input:focus,
        .gs-consult-card select:focus { border-color: var(--gs-black); }

        /* SHOP BY CATEGORY */
        .gs-cat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; }

        .gs-cat-card {
            position: relative;
            display: block;
            border-radius: var(--gs-radius);
            overflow: hidden;
            aspect-ratio: 3 / 4;
            background: var(--gs-black);
        }

        .gs-cat-card__img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
        .gs-cat-card:hover .gs-cat-card__img { transform: scale(1.06); }

        .gs-cat-card__overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0) 40%, rgba(0, 0, 0, 0.72) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 1.4rem;
            color: #ffffff;
        }

        .gs-cat-card__title { font-weight: 800; text-transform: uppercase; letter-spacing: -0.3px; font-size: 1.1rem; margin: 0; }
        .gs-cat-card__count { font-size: 0.75rem; color: rgba(255, 255, 255, 0.82); margin-top: 0.25rem; }

        /* PRODUCT CARD */
        .gs-product-card { position: relative; background: #ffffff; }

        .gs-product-card__media {
            position: relative;
            aspect-ratio: 3 / 4;
            overflow: hidden;
            background: var(--gs-grey-bg);
            border-radius: var(--gs-radius);
        }

        .gs-product-card__link { display: block; width: 100%; height: 100%; }

        .gs-product-card__img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
        .gs-product-card:hover .gs-product-card__img { transform: scale(1.07); }

        .gs-product-card__badges {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.4rem;
            z-index: 2;
        }

        .badge-gs {
            font-size: 0.62rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.28rem 0.55rem;
            border-radius: 2px;
            line-height: 1;
            display: inline-block;
        }

        .badge-gs--sale { background: #e02f2f; color: #ffffff; }
        .badge-gs--hot { background: var(--gs-black); color: #ffffff; }
        .badge-gs--new { background: #ffffff; color: var(--gs-black); border: 1px solid var(--gs-border); }

        .gs-product-card__actions {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 3;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.55) 100%);
            padding: 3rem 0.9rem 0.9rem;
            transform: translateY(8px);
            opacity: 0;
            transition: var(--gs-transition);
        }

        .gs-product-card:hover .gs-product-card__actions { opacity: 1; transform: translateY(0); }

        .gs-size-pills { display: flex; flex-wrap: wrap; gap: 0.4rem; }

        .gs-size-pill {
            min-width: 34px;
            height: 30px;
            padding: 0 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            color: var(--gs-black);
            font-size: 0.7rem;
            font-weight: 800;
            border-radius: 3px;
            border: 1px solid #ffffff;
            cursor: pointer;
            transition: var(--gs-transition);
        }

        .gs-size-pill:hover { background: var(--gs-black); color: #ffffff; }

        .gs-quick-add {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            background: #ffffff;
            color: var(--gs-black);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 0.55rem 1rem;
            border-radius: 3px;
            border: 0;
            cursor: pointer;
            width: 100%;
            transition: var(--gs-transition);
        }

        .gs-quick-add:hover { background: var(--gs-black); color: #ffffff; }

        .gs-product-card__body { padding: 0.85rem 0.15rem 0; }

        .gs-product-card__cat {
            font-size: 0.68rem;
            color: var(--gs-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 0.25rem;
        }

        .gs-product-card__name {
            font-size: 0.92rem;
            font-weight: 600;
            color: var(--gs-black);
            line-height: 1.35;
            margin-bottom: 0.45rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.5rem;
        }

        .gs-product-card__name:hover { text-decoration: underline; }

        .gs-product-card__price { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
        .gs-price { font-size: 0.95rem; font-weight: 800; color: var(--gs-black); }
        .gs-price--old { font-size: 0.78rem; font-weight: 500; color: var(--gs-muted); text-decoration: line-through; }

        /* FOOTER */
        .gs-footer { background: var(--gs-black); color: #a3a3a3; padding: 4.5rem 0 0; font-size: 0.88rem; }
        .gs-footer a { color: #a3a3a3; transition: color 0.2s ease; }
        .gs-footer a:hover { color: #ffffff; }
        .gs-footer__logo { font-size: 1.5rem; font-weight: 900; letter-spacing: -0.5px; color: #ffffff; margin-bottom: 1rem; display: inline-block; }
        .gs-footer__desc { line-height: 1.7; max-width: 280px; margin-bottom: 1.25rem; }
        .gs-footer__title { color: #ffffff; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; font-size: 0.85rem; margin-bottom: 1.25rem; }
        .gs-footer__link { display: block; padding: 0.3rem 0; }

        .gs-footer__social { display: flex; gap: 0.5rem; margin-top: 0.5rem; }

        .gs-footer__social a {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1px solid #2a2a2a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #e5e5e5;
            font-size: 0.9rem;
            transition: var(--gs-transition);
        }

        .gs-footer__social a:hover { background: #ffffff; color: var(--gs-black); border-color: #ffffff; }

        .gs-newsletter-form { display: flex; margin-top: 1rem; }

        .gs-newsletter-input {
            flex: 1;
            min-width: 0;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            color: #ffffff;
            padding: 0.8rem 1rem;
            border-radius: 4px 0 0 4px;
            font-size: 0.85rem;
            outline: none;
        }

        .gs-newsletter-input::placeholder { color: #767676; }
        .gs-newsletter-input:focus { border-color: #ffffff; }

        .gs-newsletter-btn {
            background: #ffffff;
            color: var(--gs-black);
            border: 0;
            border-radius: 0 4px 4px 0;
            padding: 0 1.1rem;
            font-size: 0.85rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .gs-newsletter-btn:hover { background: #e5e5e5; }

        .gs-pay { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1.25rem; }

        .gs-pay-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            color: #e5e5e5;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            padding: 0.45rem 0.8rem;
            border-radius: 4px;
        }

        .gs-footer__bottom { border-top: 1px solid #222222; margin-top: 3.5rem; padding: 1.4rem 0; }

        .gs-footer__bottom-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            font-size: 0.78rem;
            color: #767676;
        }

        /* BACK TO TOP */
        .gs-backtop {
            position: fixed;
            right: 1.25rem;
            bottom: 1.25rem;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--gs-black);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: var(--gs-transition);
            z-index: 1050;
        }

        .gs-backtop.is-visible { opacity: 1; visibility: visible; transform: translateY(0); }
        .gs-backtop:hover { background: #333333; }

        /* SWEETALERT2 */
        .gs-swal-popup { font-family: var(--gs-font); border-radius: 8px; }
        .gs-swal-popup .swal2-title { font-size: 1rem; font-weight: 700; }
        .gs-swal-popup .swal2-confirm { background: var(--gs-black) !important; border-radius: 4px !important; }

        /* RESPONSIVE */
        @media (max-width: 1199.98px) {
            .gs-cat-grid { gap: 1rem; }
        }

        @media (max-width: 991.98px) {
            .gs-cat-grid { grid-template-columns: repeat(2, 1fr); }
            .gs-hero { min-height: 72vh; }
            .gs-usps__watermark { font-size: 9rem; letter-spacing: -4px; }
            .gs-section { padding: 3.5rem 0; }
            .gs-section__head { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
            .gs-product-card__actions { opacity: 1; transform: none; padding: 2.2rem 0.7rem 0.7rem; }
            .gs-size-pill { min-width: 30px; height: 28px; font-size: 0.66rem; }
            .gs-quick-add { font-size: 0.68rem; padding: 0.5rem 0.8rem; }
        }

        @media (max-width: 575.98px) {
            .gs-hero__title { font-size: 2.2rem; }
            .gs-hero__cta .btn-gs { flex: 1 1 auto; }
            .gs-cat-grid { grid-template-columns: repeat(2, 1fr); gap: 0.7rem; }
            .gs-cat-card__title { font-size: 0.95rem; }
            .gs-cat-card__overlay { padding: 1rem; }
            .gs-usps { padding: 3.5rem 0; }
            .gs-consult-card { padding: 1.5rem; }
        }
    </style>

    <script>
        /* Ảnh lỗi -> placeholder SVG (khai báo sớm tránh race với sự kiện onerror) */
        window.gsOnErrorImg = function (img) {
            img.onerror = null;
            img.src = 'data:image/svg+xml;utf8,' + encodeURIComponent(
                '<svg xmlns="http://www.w3.org/2000/svg" width="600" height="800" viewBox="0 0 600 800">' +
                '<rect width="600" height="800" fill="#f5f5f5"/>' +
                '<g fill="none" stroke="#d0d0d0" stroke-width="2">' +
                '<rect x="170" y="260" width="260" height="280" rx="8"/>' +
                '<circle cx="300" cy="210" r="70"/>' +
                '</g>' +
                '<text x="300" y="600" text-anchor="middle" font-family="Arial" font-size="32" fill="#a3a3a3" letter-spacing="6">GYMSHOP</text>' +
                '</svg>'
            );
        };
    </script>
</head>

<body>
    <?php include('inc/navbar.php'); ?>
    

    <!-- HERO BANNER -->
    <section class="gs-hero">
        <div class="gs-hero__bg" style="background-image:url('https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=1800&q=80');"></div>
        <div class="gs-hero__overlay"></div>
        <div class="container position-relative">
            <div class="gs-hero__content">
                <span class="gs-hero__kicker">Gymshop - Đồ tập &amp; Dinh dưỡng thể thao</span>
                <h1 class="gs-hero__title">Bứt phá giới hạn<br>chinh phục vóc dáng</h1>
                <p class="gs-hero__sub">
                    Trang phục tập luyện, phụ kiện và thực phẩm bổ sung được cá nhân hóa theo chỉ số cơ thể của riêng bạn.
                </p>
                <div class="gs-hero__cta">
                    <a href="sanpham.php?loai=quanao" class="btn-gs btn-gs--light">Đồ tập gym</a>
                    <a href="sanpham.php?loai=tpbs" class="btn-gs btn-gs--outline-light">Thực phẩm bổ trợ</a>
                </div>
            </div>
        </div>
    </section>

    <!-- USP CÁ NHÂN HÓA -->
    <section class="gs-usps">
        <div class="gs-usps__watermark" aria-hidden="true">BMI</div>
        <div class="container position-relative">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="gs-usps__kicker">Cá nhân hóa thông minh</span>
                    <h2 class="gs-usps__title">Tính BMI &amp; gợi ý combo chuẩn form</h2>
                    <p class="gs-usps__text">
                        Nhập chiều cao, cân nặng và mục tiêu để hệ thống tự động tính BMI và gợi ý combo tập luyện chuẩn form cho bạn.
                    </p>
                    <ul class="gs-usps__list">
                        <li><i class="fa-solid fa-check"></i> Tự động tính BMI, TDEE từ hồ sơ thể trạng</li>
                        <li><i class="fa-solid fa-check"></i> Gợi ý trang phục, phụ kiện &amp; thực phẩm bổ sung phù hợp</li>
                        <li><i class="fa-solid fa-check"></i> Loại trừ sản phẩm gây dị ứng, đúng mục tiêu tập luyện</li>
                    </ul>
                    <a href="goy.php" class="btn-gs btn-gs--light btn-gs--lg mt-3">Tính chỉ số &amp; gợi ý ngay</a>
                </div>
                <div class="col-lg-6">
                    <div class="gs-consult-card">
                        <div class="gs-consult-card__title"><i class="fa-solid fa-calculator"></i> Tư vấn nhanh</div>
                        <form action="goy.php" method="get">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label for="gsChieuCao">Chiều cao (cm)</label>
                                    <input type="number" id="gsChieuCao" name="chieucao" min="100" max="250" step="0.1" placeholder="VD: 175" required>
                                </div>
                                <div class="col-6">
                                    <label for="gsCanNang">Cân nặng (kg)</label>
                                    <input type="number" id="gsCanNang" name="cannang" min="25" max="300" step="0.1" placeholder="VD: 70" required>
                                </div>
                                <div class="col-6">
                                    <label for="gsGioiTinh">Giới tính</label>
                                    <select id="gsGioiTinh" name="gioitinh">
                                        <option value="nam">Nam</option>
                                        <option value="nu">Nữ</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="gsMucTieu">Mục tiêu</label>
                                    <select id="gsMucTieu" name="muctieu">
                                        <option value="tangcan">Tăng cân / Tăng cơ</option>
                                        <option value="giamcan">Giảm cân / Giảm mỡ</option>
                                        <option value="duytri">Duy trì vóc dáng</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn-gs btn-gs--dark w-100 mt-3">Tính chỉ số &amp; gợi ý ngay</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SHOP BY CATEGORY -->
    <section class="gs-section">
        <div class="container">
            <div class="gs-section__head">
                <div>
                    <h2 class="gs-section__title">Mua sắm theo danh mục</h2>
                </div>
                <span class="gs-section__sub">Khám phá bộ sưu tập dành riêng cho mục tiêu tập luyện của bạn</span>
            </div>
            <div class="gs-cat-grid">
                <?php foreach ($danhMucNoiBat as $dm): ?>
                    <a class="gs-cat-card" href="<?php echo $dm['link']; ?>">
                        <img class="gs-cat-card__img" src="<?php echo $dm['hinhanh']; ?>" alt="<?php echo $dm['tieude']; ?>" loading="lazy" onerror="gsOnErrorImg(this)">
                        <div class="gs-cat-card__overlay">
                            <h3 class="gs-cat-card__title"><?php echo $dm['tieude']; ?></h3>
                            <span class="gs-cat-card__count"><?php echo $dm['soluong']; ?> sản phẩm</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- TRENDING PRODUCTS -->
    <section class="gs-section gs-section--grey">
        <div class="container">
            <div class="gs-section__head">
                <div>
                    <h2 class="gs-section__title">Sản phẩm bán chạy</h2>
                </div>
                <a class="gs-section__link" href="sanpham.php">Xem tất cả <i class="fa-solid fa-arrow-right-long"></i></a>
            </div>
            <div class="row g-4">
                <?php foreach ($spBanChay as $sp): ?>
                    <?php
                    $phanTram = gs_phan_tram($sp['giagoc'], $sp['giaban']);
                    $sizes = gs_sizes($sp['sizes']);
                    $laMoi = gs_la_moi($sp['ngaytao']);
                    $tenSP = htmlspecialchars($sp['tensanpham'], ENT_QUOTES, 'UTF-8');
                    ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="gs-product-card" data-id="<?php echo (int) $sp['id']; ?>" data-name="<?php echo $tenSP; ?>">
                            <div class="gs-product-card__media">
                                <a class="gs-product-card__link" href="chitiet.php?id=<?php echo (int) $sp['id']; ?>">
                                    <img class="gs-product-card__img"
                                         src="uploads/<?php echo htmlspecialchars($sp['hinhanh'] ? $sp['hinhanh'] : 'no-image.jpg'); ?>"
                                         alt="<?php echo $tenSP; ?>"
                                         loading="lazy"
                                         onerror="gsOnErrorImg(this)">
                                    <span class="gs-product-card__badges">
                                        <?php if ($phanTram > 0): ?>
                                            <span class="badge-gs badge-gs--sale">-<?php echo $phanTram; ?>%</span>
                                        <?php endif; ?>
                                        <?php if ((int) $sp['noibat'] === 1): ?>
                                            <span class="badge-gs badge-gs--hot">Hot</span>
                                        <?php endif; ?>
                                        <?php if ($laMoi && (int) $sp['noibat'] !== 1): ?>
                                            <span class="badge-gs badge-gs--new">Mới</span>
                                        <?php endif; ?>
                                    </span>
                                </a>

                                <div class="gs-product-card__actions">
                                    <?php if (!empty($sizes)): ?>
                                        <div class="gs-size-pills">
                                            <?php foreach ($sizes as $sz): ?>
                                                <button type="button" class="gs-size-pill" data-size="<?php echo htmlspecialchars($sz); ?>"><?php echo htmlspecialchars($sz); ?></button>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <button type="button" class="gs-quick-add"><i class="fa-solid fa-bag-shopping"></i> Thêm nhanh</button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="gs-product-card__body">
                                <div class="gs-product-card__cat"><?php echo htmlspecialchars($sp['tendanhmuc']); ?></div>
                                <a class="gs-product-card__name" href="chitiet.php?id=<?php echo (int) $sp['id']; ?>"><?php echo $tenSP; ?></a>
                                <div class="gs-product-card__price">
                                    <span class="gs-price"><?php echo gs_tien($sp['giaban']); ?>đ</span>
                                    <?php if ($phanTram > 0): ?>
                                        <span class="gs-price--old"><?php echo gs_tien($sp['giagoc']); ?>đ</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <script>
        (function () {
            'use strict';

            document.addEventListener('DOMContentLoaded', function () {
                var navbar = document.querySelector('.navbar.sticky-top');
                var backTop = document.getElementById('gsBackTop');

                /* Sticky navbar thêm shadow khi cuộn + nút về đầu trang */
                function onScroll() {
                    if (navbar) {
                        navbar.classList.toggle('is-scrolled', window.scrollY > 0);
                    }
                    if (backTop) {
                        backTop.classList.toggle('is-visible', window.scrollY > 400);
                    }
                }

                window.addEventListener('scroll', onScroll, { passive: true });
                onScroll();

                if (backTop) {
                    backTop.addEventListener('click', function () {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    });
                }

                /* Thông báo SweetAlert2 */
                function showAddToast(name, size) {
                    var message = size
                        ? 'Đã thêm ' + name + ' - Size ' + size + ' vào giỏ hàng!'
                        : 'Đã thêm ' + name + ' vào giỏ hàng!';

                    if (typeof Swal === 'undefined') {
                        window.alert(message);
                        return;
                    }

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 2200,
                        timerProgressBar: true,
                        customClass: { popup: 'gs-swal-popup' }
                    });
                }

                /* Chọn size nhanh / thêm nhanh trên thẻ sản phẩm */
                document.addEventListener('click', function (e) {
                    var pill = e.target.closest('.gs-size-pill');
                    if (pill) {
                        e.preventDefault();
                        var card = pill.closest('.gs-product-card');
                        var name = card ? card.getAttribute('data-name') : 'Sản phẩm';
                        showAddToast(name, pill.getAttribute('data-size'));
                        return;
                    }

                    var quick = e.target.closest('.gs-quick-add');
                    if (quick) {
                        e.preventDefault();
                        var card2 = quick.closest('.gs-product-card');
                        var name2 = card2 ? card2.getAttribute('data-name') : 'Sản phẩm';
                        showAddToast(name2, '');
                    }
                });

                /* Form đăng ký nhận tin ở footer */
                var newsForm = document.getElementById('gsNewsletterForm');
                if (newsForm) {
                    newsForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        var input = newsForm.querySelector('input[type="email"]');
                        var email = input ? input.value.trim() : '';

                        function notify(options) {
                            if (typeof Swal === 'undefined') {
                                window.alert(options.title);
                                return;
                            }
                            Swal.fire(options);
                        }

                        if (!email) {
                            notify({
                                icon: 'warning',
                                title: 'Vui lòng nhập email!',
                                customClass: { popup: 'gs-swal-popup' }
                            });
                            return;
                        }

                        notify({
                            icon: 'success',
                            title: 'Đăng ký thành công!',
                            text: 'Cảm ơn bạn đã đăng ký nhận tin & voucher từ GYMSHOP.',
                            confirmButtonText: 'Đóng',
                            customClass: { popup: 'gs-swal-popup' }
                        });
                        newsForm.reset();
                    });
                }
            });
        })();
    </script>

    <?php include('inc/footer.php'); ?>