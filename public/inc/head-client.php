<?php
if (!isset($gsTieude) || $gsTieude === '') {
    $gsTieude = 'GYMBRO';
}

if (!isset($gsModule36)) {
    header('Location: ../index.php');
    exit();
}
$gsFlash = gsLayThongBao();
?>
<!DOCTYPE html>
<html lang="vi" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($gsTieude); ?> - GYMBRO</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/main.css" rel="stylesheet">
    <style>
        body {
            background-color: #0d0d0d !important;
            color: #ffffff !important;
        }
        .gs-section--grey {
            background-color: #141414 !important;
        }
        .gs-page-head__title, .gs-page-head__sub, .gs-section__title, .gs-section__sub {
            color: #ffffff !important;
        }
        .breadcrumb-item a, .breadcrumb-item.active {
            color: #888888 !important;
        }
        .gs-empty {
            color: #888888 !important;
        }
        .gs-tabs .nav-link {
            color: #9a9a9a !important;
        }
        .gs-tabs .nav-link:hover,
        .gs-tabs .nav-link.active {
            color: #ffffff !important;
        }
        .gs-mau {
            background: #1c1c1c !important;
            border-color: #3a3a3a !important;
            color: #ffffff !important;
        }
        .gs-detail__price .gs-price {
            color: #ffffff !important;
        }
        .gs-filter,
        .gs-cart,
        .gs-cart-summary,
        .gs-checkout__box,
        .gs-result,
        .gs-vnpay,
        .gs-order {
            color: #111111 !important;
        }
        .gs-voucher-suggest__item button {
            color: #111111 !important;
        }
        .gs-paymethod input[type="radio"],
        .gs-address input[type="radio"],
        .gs-check input[type="checkbox"] {
            color-scheme: light;
            accent-color: #111111;
        }
        .gs-result .table,
        .gs-checkout__box .table {
            --bs-table-bg: transparent;
            --bs-table-color: #111111;
            --bs-table-border-color: var(--gs-border);
        }
        .gs-cart-summary .badge,
        .gs-order .badge {
            color: #ffffff !important;
        }
        .gs-cart-summary .badge.text-dark,
        .gs-order .badge.text-dark {
            color: #111111 !important;
        }
    </style>

    <script src="../assets/js/main.js"></script>
    <?php if ($gsFlash) : ?>
        <script>
            window.gsFlash = <?php echo json_encode($gsFlash, JSON_UNESCAPED_UNICODE); ?>;
        </script>
    <?php endif; ?>
    <script src="../assets/js/giohang.js" defer></script>
</head>

<body>
    <?php include(__DIR__ . '/navbar.php'); ?>
