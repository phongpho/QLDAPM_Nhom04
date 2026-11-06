<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Chỉ cho phép Admin (loai == 1) hoặc Nhân viên (loai == 2)
if (!isset($_SESSION["nguoidung"]) || !in_array((int)$_SESSION["nguoidung"]["loai"], [1, 2])) {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => 'error', 'message' => 'Truy cập bị từ chối!']);
    exit();
}

require_once("../../model/database.php");

header('Content-Type: application/json; charset=utf-8');

$query = trim($_GET["q"] ?? "");
if (mb_strlen($query, 'UTF-8') < 1) {
    echo json_encode(['status' => 'success', 'total' => 0, 'data' => []]);
    exit();
}

$dbcon = DATABASE::connect();
$kw = '%' . $query . '%';

$ketqua = [
    'donhang'   => [],
    'sanpham'   => [],
    'khachhang' => [],
    'nhansu'    => [],
    'danhmuc'   => [],
    'thuonghieu'=> [],
    'voucher'   => [],
    'tintuc'    => []
];
$total = 0;

try {
    // 1. Tìm đơn hàng (Mã đơn hàng, tên người nhận, SĐT)
    $sqlDh = "SELECT id, madonhang, tennguoinhan, sdtnhan, tongtien, trangthai 
              FROM donhang 
              WHERE madonhang LIKE :kw OR tennguoinhan LIKE :kw OR sdtnhan LIKE :kw 
              ORDER BY id DESC LIMIT 5";
    $cmdDh = $dbcon->prepare($sqlDh);
    $cmdDh->bindValue(':kw', $kw);
    $cmdDh->execute();
    while ($row = $cmdDh->fetch(PDO::FETCH_ASSOC)) {
        $maHienThi = !empty($row["madonhang"]) ? $row["madonhang"] : ('#' . $row["id"]);
        $ketqua['donhang'][] = [
            'id'       => $row['id'],
            'title'    => $maHienThi,
            'subtitle' => $row['tennguoinhan'] . ' · ' . $row['sdtnhan'] . ' · ' . number_format((float)$row['tongtien'], 0, ',', '.') . 'đ',
            'url'      => '../qldonhang/index.php?action=chitiet&id=' . $row['id'],
            'badge'    => 'Đơn hàng'
        ];
        $total++;
    }

    // 2. Tìm sản phẩm (Tên sản phẩm, ID)
    $sqlSp = "SELECT s.id, s.tensanpham, s.giaban, s.hinhanh, d.tendanhmuc 
              FROM sanpham s 
              LEFT JOIN danhmuc d ON d.id = s.danhmuc_id
              WHERE s.tensanpham LIKE :kw OR s.id = :exactId 
              ORDER BY s.id DESC LIMIT 5";
    $cmdSp = $dbcon->prepare($sqlSp);
    $cmdSp->bindValue(':kw', $kw);
    $cmdSp->bindValue(':exactId', is_numeric($query) ? (int)$query : 0, PDO::PARAM_INT);
    $cmdSp->execute();
    while ($row = $cmdSp->fetch(PDO::FETCH_ASSOC)) {
        $img = !empty($row['hinhanh']) ? '../../assets/images/products/' . $row['hinhanh'] : '../../assets/images/products/product.png';
        $ketqua['sanpham'][] = [
            'id'       => $row['id'],
            'title'    => $row['tensanpham'],
            'subtitle' => number_format((float)$row['giaban'], 0, ',', '.') . 'đ · ' . ($row['tendanhmuc'] ?? 'Chưa phân loại'),
            'image'    => $img,
            'url'      => '../qlsanpham/index.php?action=sua&id=' . $row['id'],
            'badge'    => 'Hàng hóa'
        ];
        $total++;
    }

    // 3. Tìm khách hàng (Họ tên, SĐT, Email - loai = 3)
    $sqlKh = "SELECT id, hoten, gmail, sodienthoai, hinhanh 
              FROM nguoidung 
              WHERE loai = 3 AND (hoten LIKE :kw OR gmail LIKE :kw OR sodienthoai LIKE :kw) 
              ORDER BY id DESC LIMIT 5";
    $cmdKh = $dbcon->prepare($sqlKh);
    $cmdKh->bindValue(':kw', $kw);
    $cmdKh->execute();
    while ($row = $cmdKh->fetch(PDO::FETCH_ASSOC)) {
        $img = !empty($row['hinhanh']) ? '../../assets/images/users/' . $row['hinhanh'] : '../../assets/images/users/user.png';
        $ketqua['khachhang'][] = [
            'id'       => $row['id'],
            'title'    => $row['hoten'],
            'subtitle' => ($row['gmail'] ?? '') . ' · ' . $row['sodienthoai'],
            'image'    => $img,
            'url'      => '../qlkhachhang/index.php?action=chitiet&id=' . $row['id'],
            'badge'    => 'Khách hàng'
        ];
        $total++;
    }

    // 4. Tìm danh mục hàng hóa
    $sqlDm = "SELECT id, tendanhmuc, loai 
              FROM danhmuc 
              WHERE tendanhmuc LIKE :kw 
              ORDER BY id DESC LIMIT 4";
    $cmdDm = $dbcon->prepare($sqlDm);
    $cmdDm->bindValue(':kw', $kw);
    $cmdDm->execute();
    while ($row = $cmdDm->fetch(PDO::FETCH_ASSOC)) {
        $loaiTen = ($row['loai'] === 'quanao') ? 'Quần áo' : (($row['loai'] === 'phukien') ? 'Phụ kiện' : 'TPBS');
        $ketqua['danhmuc'][] = [
            'id'       => $row['id'],
            'title'    => $row['tendanhmuc'],
            'subtitle' => 'Nhóm: ' . $loaiTen,
            'url'      => '../qldanhmuc/index.php?action=sua&id=' . $row['id'],
            'badge'    => 'Danh mục'
        ];
        $total++;
    }

    // 5. Tìm thương hiệu
    $sqlTh = "SELECT id, tenthuonghieu, logo 
              FROM thuonghieu 
              WHERE tenthuonghieu LIKE :kw 
              ORDER BY id DESC LIMIT 4";
    $cmdTh = $dbcon->prepare($sqlTh);
    $cmdTh->bindValue(':kw', $kw);
    $cmdTh->execute();
    while ($row = $cmdTh->fetch(PDO::FETCH_ASSOC)) {
        $img = !empty($row['logo']) ? '../../assets/images/products/' . $row['logo'] : null;
        $ketqua['thuonghieu'][] = [
            'id'       => $row['id'],
            'title'    => $row['tenthuonghieu'],
            'subtitle' => 'Nhãn hàng / Thương hiệu',
            'image'    => $img,
            'url'      => '../qlthuonghieu/index.php?tukhoa=' . urlencode($row['tenthuonghieu']),
            'badge'    => 'Thương hiệu'
        ];
        $total++;
    }

    // 6. Tìm voucher khuyến mãi
    $sqlVc = "SELECT id, ma, mota, loai, giatri 
              FROM voucher 
              WHERE ma LIKE :kw OR mota LIKE :kw 
              ORDER BY id DESC LIMIT 4";
    $cmdVc = $dbcon->prepare($sqlVc);
    $cmdVc->bindValue(':kw', $kw);
    $cmdVc->execute();
    while ($row = $cmdVc->fetch(PDO::FETCH_ASSOC)) {
        $giam = ($row['loai'] === 'phantram') ? ('Giảm ' . (float)$row['giatri'] . '%') : ('Giảm ' . number_format((float)$row['giatri'], 0, ',', '.') . 'đ');
        $ketqua['voucher'][] = [
            'id'       => $row['id'],
            'title'    => $row['ma'],
            'subtitle' => $giam . ' · ' . ($row['mota'] ?? 'Mã ưu đãi'),
            'url'      => '../qlvoucher/index.php?tukhoa=' . urlencode($row['ma']),
            'badge'    => 'Voucher'
        ];
        $total++;
    }

    // 7. Tìm nhân sự (Quản trị viên & Nhân viên: loai IN (1, 2))
    $sqlNs = "SELECT id, hoten, gmail, sodienthoai, hinhanh, loai 
              FROM nguoidung 
              WHERE loai IN (1, 2) AND (hoten LIKE :kw OR gmail LIKE :kw OR sodienthoai LIKE :kw) 
              ORDER BY id DESC LIMIT 4";
    $cmdNs = $dbcon->prepare($sqlNs);
    $cmdNs->bindValue(':kw', $kw);
    $cmdNs->execute();
    while ($row = $cmdNs->fetch(PDO::FETCH_ASSOC)) {
        $img = !empty($row['hinhanh']) ? '../../assets/images/users/' . $row['hinhanh'] : '../../assets/images/users/user.png';
        $roleName = ((int)$row['loai'] === 1) ? 'Quản trị viên' : 'Nhân sự';
        $ketqua['nhansu'][] = [
            'id'       => $row['id'],
            'title'    => $row['hoten'],
            'subtitle' => $roleName . ' · ' . ($row['gmail'] ?? '') . ' · ' . $row['sodienthoai'],
            'image'    => $img,
            'url'      => '../qlnhansu/index.php?action=sua&id=' . $row['id'],
            'badge'    => $roleName
        ];
        $total++;
    }

    // 8. Tìm bài viết tin tức & cẩm nang
    $sqlTt = "SELECT id, tieude, loai, hinhanh, ngaydang 
              FROM tintuc 
              WHERE tieude LIKE :kw OR noidung LIKE :kw 
              ORDER BY id DESC LIMIT 4";
    $cmdTt = $dbcon->prepare($sqlTt);
    $cmdTt->bindValue(':kw', $kw);
    $cmdTt->execute();
    while ($row = $cmdTt->fetch(PDO::FETCH_ASSOC)) {
        $img = !empty($row['hinhanh']) ? '../../assets/images/news/' . $row['hinhanh'] : null;
        $loaiBai = ($row['loai'] === 'khuyenmai') ? 'Khuyến mãi' : (($row['loai'] === 'kienthuc') ? 'Kiến thức' : 'Cẩm nang');
        $ketqua['tintuc'][] = [
            'id'       => $row['id'],
            'title'    => $row['tieude'],
            'subtitle' => $loaiBai . ' · ' . date('d/m/Y', strtotime($row['ngaydang'])),
            'image'    => $img,
            'url'      => '../qltintuc/index.php?tukhoa=' . urlencode($row['tieude']),
            'badge'    => 'Tin tức'
        ];
        $total++;
    }

    echo json_encode([
        'status' => 'success',
        'query'  => $query,
        'total'  => $total,
        'data'   => $ketqua
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Lỗi máy chủ khi tra cứu: ' . $e->getMessage()
    ]);
}
?>
