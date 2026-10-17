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
<html lang="vi">

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
