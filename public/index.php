<?php
include ('../model/database.php');
include ('../model/sanpham.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../model/danhmuc.php');
require_once(__DIR__ . '/../model/giohang.php');
require_once(__DIR__ . '/../model/voucher.php');
require_once(__DIR__ . '/../model/donhang.php');
require_once(__DIR__ . '/../model/diachi.php');
require_once(__DIR__ . '/../model/vnpay.php');
require_once(__DIR__ . '/../model/nguoidung.php');
require_once(__DIR__ . '/inc/shop_helper.php');

$gsModule36 = true;

$gsND = isset($_SESSION['nguoidung']) ? $_SESSION['nguoidung'] : null;
$gsNguoidungId = $gsND ? (int) $gsND['id'] : 0;

$sp   = new SANPHAM();
$dm36 = new DANHMUC();
$gh   = new GIOHANG($gsNguoidungId);
$vc   = new VOUCHER();
$dh   = new DONHANG();
$dc   = new DIACHI();
$vnpay = new VNPAY();
$nd   = new NGUOIDUNG();

$gh->dongbo();

$gsVoucherApdung = isset($_SESSION['gs_voucher']) ? $_SESSION['gs_voucher'] : null;

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($action) {

    case 'dangnhap':
        if ($gsNguoidungId > 0) {
            gsChuyenTrang('index.php');
        }
        include('dangnhap.php');
        break;

    case 'xldangnhap':
        if ($gsNguoidungId > 0) {
            gsChuyenTrang('index.php');
        }
        $email    = trim($_POST['txtemail'] ?? '');
        $matkhau  = $_POST['txtmatkhau'] ?? '';
        if ($nd->kiemtranguoidunghople($email, $matkhau)) {
            $user = $nd->laythongtinnguoidung($email);
            $_SESSION['nguoidung'] = $user;
            $gh->dongbo();
            $redirect = $_SESSION['redirect_sau_login'] ?? 'index.php';
            unset($_SESSION['redirect_sau_login']);
            gsThongBao('success', 'Đăng nhập thành công! Chào mừng bạn trở lại, ' . $user['hoten'] . '!');
            gsChuyenTrang($redirect);
        } else {
            $thongbao_loi = 'Email hoặc mật khẩu không chính xác!';
            $loginEmail   = $email;
            $tabActive    = 'login';
            include('dangnhap.php');
        }
        break;

    case 'xldangky':
        if ($gsNguoidungId > 0) {
            gsChuyenTrang('index.php');
        }
        $hoten    = trim($_POST['txthoten'] ?? '');
        $email    = trim($_POST['txtemail'] ?? '');
        $sodt     = trim($_POST['txtsodt'] ?? '');
        $matkhau  = $_POST['txtmatkhau'] ?? '';
        $xacnhan  = $_POST['txtxacnhanmatkhau'] ?? '';

        if ($matkhau !== $xacnhan) {
            $thongbao_loi = 'Mật khẩu xác nhận không khớp!';
            $regHoten = $hoten; $regEmail = $email; $regSodt = $sodt;
            $tabActive = 'register';
            include('dangnhap.php');
        } elseif ($nd->kiemtratrung($email, $sodt)) {
            $thongbao_loi = 'Email hoặc số điện thoại này đã được sử dụng!';
            $regHoten = $hoten; $regEmail = $email; $regSodt = $sodt;
            $tabActive = 'register';
            include('dangnhap.php');
        } else {
            $nd->themnguoidung($email, $matkhau, $sodt, $hoten, 3);
            $thongbao_thanhcong = 'Đăng ký thành công! Hãy đăng nhập để bắt đầu mua sắm.';
            $tabActive = 'login';
            $loginEmail = $email;
            include('dangnhap.php');
        }
        break;

    case 'dangxuat':
        unset($_SESSION['nguoidung']);
        unset($_SESSION['gs_cart']);
        unset($_SESSION['gs_voucher']);
        gsThongBao('success', 'Đã đăng xuất thành công. Hẹn gặp lại bạn!');
        gsChuyenTrang('index.php');
        break;

    case 'taikhoan':
        if ($gsNguoidungId <= 0) {
            gsThongBao('warning', 'Vui lòng đăng nhập để xem thông tin tài khoản!');
            gsChuyenTrang('index.php?action=dangnhap');
        }
        include('taikhoan.php');
        break;

    case 'capnhatthongtin':
        if ($gsNguoidungId <= 0) {
            gsChuyenTrang('index.php?action=dangnhap');
        }
        $hoten = trim($_POST['txthoten'] ?? '');
        $sodt  = trim($_POST['txtsodt'] ?? '');
        $userHientai = $nd->laythongtinnguoidung($gsND['email']);
        $hinhanh = $userHientai['hinhanh'] ?? null;

        // Xử lý upload ảnh đại diện mới
        if (isset($_FILES['fhinh']) && $_FILES['fhinh']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['fhinh']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $tenMoi = 'user_' . $gsNguoidungId . '_' . time() . '.' . $ext;
                $duongdanUpload = '../assets/images/users/' . $tenMoi;
                if (move_uploaded_file($_FILES['fhinh']['tmp_name'], $duongdanUpload)) {
                    $hinhanh = $tenMoi;
                }
            }
        }

        $nd->capnhatnguoidung($gsNguoidungId, $gsND['email'], $sodt, $hoten, $hinhanh);
        $_SESSION['nguoidung'] = $nd->laythongtinnguoidung($gsND['email']);
        gsThongBao('success', 'Cập nhật thông tin cá nhân thành công!');
        gsChuyenTrang('index.php?action=taikhoan&tab=profile');
        break;

    case 'xldoimatkhau':
        if ($gsNguoidungId <= 0) {
            gsChuyenTrang('index.php?action=dangnhap');
        }
        $mkcu   = $_POST['txtmatkhaucu'] ?? '';
        $mkmoi  = $_POST['txtmatkhaumoi'] ?? '';
        $xacnhan = $_POST['txtxacnhanmatkhau'] ?? '';

        if (!$nd->kiemtranguoidunghople($gsND['email'], $mkcu)) {
            gsThongBao('error', 'Mật khẩu hiện tại không chính xác!');
            gsChuyenTrang('index.php?action=taikhoan&tab=password');
        } elseif ($mkmoi !== $xacnhan) {
            gsThongBao('error', 'Mật khẩu mới và xác nhận mật khẩu không khớp!');
            gsChuyenTrang('index.php?action=taikhoan&tab=password');
        } elseif (strlen($mkmoi) < 6) {
            gsThongBao('error', 'Mật khẩu mới phải có tối thiểu 6 ký tự!');
            gsChuyenTrang('index.php?action=taikhoan&tab=password');
        } else {
            $nd->doimatkhau($gsND['email'], $mkmoi);
            gsThongBao('success', 'Đổi mật khẩu thành công!');
            gsChuyenTrang('index.php?action=taikhoan&tab=password');
        }
        break;

    case 'themdiachimoi':
        if ($gsNguoidungId <= 0) {
            gsChuyenTrang('index.php?action=dangnhap');
        }
        $ten     = trim($_POST['tennguoinhan'] ?? '');
        $sdt     = trim($_POST['sodienthoai'] ?? '');
        $diachi  = trim($_POST['diachi'] ?? '');
        $macdinh = isset($_POST['macdinh']) ? 1 : 0;

        if (!empty($ten) && !empty($sdt) && !empty($diachi)) {
            $dc->themdiachi($gsNguoidungId, $ten, $sdt, $diachi, $macdinh);
            gsThongBao('success', 'Thêm địa chỉ giao hàng thành công!');
        } else {
            gsThongBao('error', 'Vui lòng điền đầy đủ thông tin địa chỉ!');
        }
        gsChuyenTrang('index.php?action=taikhoan&tab=address');
        break;

    case 'datdiachimacdinh':
        if ($gsNguoidungId <= 0) {
            gsChuyenTrang('index.php?action=dangnhap');
        }
        $id_dc = (int) ($_GET['id'] ?? 0);
        if ($id_dc > 0) {
            $dc->datmacdinh($id_dc, $gsNguoidungId);
            gsThongBao('success', 'Đã đặt làm địa chỉ mặc định!');
        }
        gsChuyenTrang('index.php?action=taikhoan&tab=address');
        break;

    case 'xoadiachi':
        if ($gsNguoidungId <= 0) {
            gsChuyenTrang('index.php?action=dangnhap');
        }
        $id_dc = (int) ($_GET['id'] ?? 0);
        if ($id_dc > 0) {
            $dc->xoadiachi($id_dc, $gsNguoidungId);
            gsThongBao('success', 'Đã xóa địa chỉ thành công!');
        }
        gsChuyenTrang('index.php?action=taikhoan&tab=address');
        break;

    case 'sanpham':
        $loai          = isset($_GET['loai']) ? trim($_GET['loai']) : '';
        $doituong      = isset($_GET['doituong']) ? trim($_GET['doituong']) : '';
        $muctieu       = isset($_GET['muctieu']) ? trim($_GET['muctieu']) : '';
        $thuonghieu_id = isset($_GET['thuonghieu']) ? (int) $_GET['thuonghieu'] : 0;
        $khoanggia     = isset($_GET['gia']) ? trim($_GET['gia']) : '';
        $size          = isset($_GET['size']) ? trim($_GET['size']) : '';
        $tukhoa        = isset($_GET['q']) ? trim($_GET['q']) : '';
        $sapxep        = isset($_GET['sapxep']) ? trim($_GET['sapxep']) : 'moi';
        $trang         = isset($_GET['trang']) ? max(1, (int) $_GET['trang']) : 1;
        $motrang       = 8;

        $tongsp = $sp->demtimkiemsanpham($tukhoa, $loai, $doituong, $muctieu, $thuonghieu_id, $khoanggia, $size);
        $tongsotrang = (int) ceil($tongsp / $motrang);
        if ($tongsotrang > 0 && $trang > $tongsotrang) {
            $trang = $tongsotrang;
        }
        $dsSanpham = $sp->timkiemsanpham($tukhoa, $loai, $doituong, $muctieu, $sapxep, $motrang, ($trang - 1) * $motrang, $thuonghieu_id, $khoanggia, $size);

        $soluongnhom = array(
            'quanao'  => $dm36->laysoluongsanphamtheoloai('quanao'),
            'phukien' => $dm36->laysoluongsanphamtheoloai('phukien'),
            'tpbs'    => $dm36->laysoluongsanphamtheoloai('tpbs')
        );

        $dsThuonghieu = $sp->laydanhsachthuonghieu();
        $gsGoiy = gsTinhGoiyChiSo($sp, $gsNguoidungId);
        include('danhmuc.php');
        break;

    case 'chitiet':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $sanpham = ($id > 0) ? $sp->laysanphamchitiet($id) : null;
        if (!$sanpham || (int) $sanpham['trangthai'] !== 1) {
            gsThongBao('error', 'Sản phẩm không tồn tại hoặc đã ngừng bán!');
            gsChuyenTrang('index.php?action=sanpham');
        }

        $sp->tangluotxem($id);
        $mangsizes      = $sp->tachsizethanhmmang($sanpham['sizes']);
        $dsDanhgia      = $sp->laydanhgiasanpham($id, 20);
        $diemDanhgia    = $sp->laydiemdanhgiasanpham($id);
        $sanphamtuongtu = $sp->laysanphamtuongtu($sanpham['danhmuc_id'], $id, 4);

        $gsGoiy = gsTinhGoiyChiSo($sp, $gsNguoidungId);
        $sizegoiy = '';
        if ($gsGoiy && $gsGoiy['sizegoiy'] && in_array($gsGoiy['sizegoiy']['size'], $mangsizes, true)) {
            $sizegoiy = $gsGoiy['sizegoiy']['size'];
        }

        include('detail.php');
        break;

    case 'goy':
        if ($gsNguoidungId <= 0) {
            gsThongBao('warning', 'Vui lòng đăng nhập để xem gợi ý cá nhân hóa theo chỉ số cơ thể!');
            gsChuyenTrang('../admin/ktnguoidung/index.php?action=dangnhap');
        }
        $chieucao = isset($_GET['chieucao']) ? (float) $_GET['chieucao'] : null;
        $cannang  = isset($_GET['cannang']) ? (float) $_GET['cannang'] : null;
        $gioitinh = isset($_GET['gioitinh']) ? trim($_GET['gioitinh']) : null;
        $tuoi     = isset($_GET['tuoi']) ? (int) $_GET['tuoi'] : null;
        $muctieu  = isset($_GET['muctieu']) ? trim($_GET['muctieu']) : null;

        $gsGoiy = gsTinhGoiyChiSo($sp, $gsNguoidungId, $chieucao, $cannang, $gioitinh, $tuoi, $muctieu);
        include('goiy.php');
        break;

    case 'luuhoso':
        if ($gsNguoidungId <= 0) {
            gsThongBao('warning', 'Vui lòng đăng nhập để lưu hồ sơ thể trạng!');
            gsChuyenTrang('../admin/ktnguoidung/index.php?action=dangnhap');
        }

        $chieucao = isset($_POST['chieucao']) ? (float) $_POST['chieucao'] : 0;
        $cannang  = isset($_POST['cannang']) ? (float) $_POST['cannang'] : 0;
        $gioitinh = (isset($_POST['gioitinh']) && $_POST['gioitinh'] === 'nu') ? 'nu' : 'nam';
        $tuoi     = isset($_POST['tuoi']) ? (int) $_POST['tuoi'] : 0;
        $muctieu  = isset($_POST['muctieu']) ? trim($_POST['muctieu']) : 'duytri';

        $muctieuhople = in_array($muctieu, array('giamcan', 'tangcan', 'duytri'), true);
        if (!$muctieuhople || $tuoi < 10 || $tuoi > 100 || $chieucao < 100 || $chieucao > 250 || $cannang < 25 || $cannang > 300) {
            gsThongBao('error', 'Chỉ số cơ thể không hợp lệ! Chiều cao 100-250cm, cân nặng 25-300kg, tuổi 10-100.');
            gsChuyenTrang('index.php?action=goy');
        }

        $bmi = gsTinhBmi($chieucao, $cannang);
        $sp->luuhosothetrang($gsNguoidungId, $chieucao, $cannang, $gioitinh, $tuoi, $muctieu, $bmi);
        gsThongBao('success', 'Đã lưu chỉ số cơ thể! Xem gợi ý size và combo dành riêng cho bạn.');
        gsChuyenTrang('index.php?action=goy');
        break;

    case 'giohang':
        $giohang    = $gh->laygiohang();
        $gsTongtien = gsTinhTongtienGio($gh, $vc, $gsVoucherApdung);

        $gsGoiy = $gh->dadangnhap() ? gsTinhGoiyChiSo($sp, $gsNguoidungId) : null;
        $goiyMuakem = array();
        $idsanphamtronggio = $gh->laysanphamidtronggio();
        if ($gsGoiy) {
            foreach ($gsGoiy['sanpham'] as $g) {
                if (!in_array((int) $g['id'], $idsanphamtronggio)) {
                    $goiyMuakem[] = $g;
                }
            }
        }
        $goiyMuakem = array_slice($goiyMuakem, 0, 4);
        if (count($goiyMuakem) === 0) {
            $goiyMuakem = $sp->laysanphambanchay(4);
        }
        $dsVoucher = $vc->layvoucherdanghoatdong(3);
        include('cart.php');
        break;

    case 'themvaogiohang':
        $sanpham_id = isset($_POST['sanpham_id']) ? (int) $_POST['sanpham_id'] : 0;
        $size       = isset($_POST['size']) ? (string) $_POST['size'] : '';
        $soluong    = isset($_POST['soluong']) ? (int) $_POST['soluong'] : 1;

        $kq = $gh->them($sanpham_id, $size, $soluong);
        if (gsLaAjax()) {
            gsTraJson(array('ok' => $kq['ok'], 'message' => $kq['message'], 'soluonggio' => $gh->dem()));
        }
        gsThongBao($kq['ok'] ? 'success' : 'error', $kq['message']);
        gsChuyenTrang(gsQuayLai('index.php?action=giohang'));
        break;

    case 'themcombo':
        $items = array();
        $gsGoiy = gsTinhGoiyChiSo($sp, $gsNguoidungId);
        $sizegoiy = ($gsGoiy && $gsGoiy['sizegoiy']) ? $gsGoiy['sizegoiy']['size'] : '';
        $sizechon = isset($_POST['size']) ? trim($_POST['size']) : '';

        $dssanpham = array(
            'quanao'  => isset($_POST['quanao_id']) ? (int) $_POST['quanao_id'] : 0,
            'phukien' => isset($_POST['phukien_id']) ? (int) $_POST['phukien_id'] : 0,
            'tpbs'    => isset($_POST['tpbs_id']) ? (int) $_POST['tpbs_id'] : 0
        );
        foreach ($dssanpham as $nhom => $idsp) {
            if ($idsp <= 0) {
                continue;
            }
            $sanphamcombo = $sp->laysanphamchitiet($idsp);
            if (!$sanphamcombo) {
                continue;
            }
            $mangsizes = $sp->tachsizethanhmmang($sanphamcombo['sizes']);
            $size = '';
            if ($nhom === 'quanao' && $sizechon !== '' && in_array($sizechon, $mangsizes, true)) {
                $size = $sizechon;
            } elseif (count($mangsizes) > 0) {
                $size = ($sizegoiy !== '' && in_array($sizegoiy, $mangsizes, true)) ? $sizegoiy : $mangsizes[0];
            }
            $items[] = array('sanpham_id' => $idsp, 'size' => $size, 'soluong' => 1);
        }

        $kq = $gh->themnhieu($items);
        if (gsLaAjax()) {
            gsTraJson(array('ok' => $kq['ok'], 'message' => $kq['message'], 'soluonggio' => $gh->dem()));
        }
        gsThongBao($kq['ok'] ? 'success' : 'error', $kq['message']);
        gsChuyenTrang(gsQuayLai('index.php?action=giohang'));
        break;

    case 'capnhatgiohang':
        $sanpham_id = isset($_POST['sanpham_id']) ? (int) $_POST['sanpham_id'] : 0;
        $size       = isset($_POST['size']) ? (string) $_POST['size'] : '';

        if (isset($_POST['tang'])) {
            $kq = $gh->tang($sanpham_id, $size);
        } elseif (isset($_POST['giam'])) {
            $kq = $gh->giam($sanpham_id, $size);
        } else {
            $kq = $gh->capnhat($sanpham_id, $size, isset($_POST['soluong']) ? (int) $_POST['soluong'] : 1);
        }

        if (gsLaAjax()) {
            gsTraJson(array(
                'ok' => $kq['ok'],
                'message' => $kq['message'],
                'soluong' => isset($kq['soluong']) ? $kq['soluong'] : 0,
                'soluonggio' => $gh->dem(),
                'tamtinh' => $gh->tamtinh()
            ));
        }
        gsThongBao($kq['ok'] ? 'success' : 'error', $kq['message']);
        gsChuyenTrang('index.php?action=giohang');
        break;

    case 'xoagiohang':
        $sanpham_id = isset($_REQUEST['sanpham_id']) ? (int) $_REQUEST['sanpham_id'] : 0;
        $size       = isset($_REQUEST['size']) ? (string) $_REQUEST['size'] : '';
        $gh->xoa($sanpham_id, $size);
        gsThongBao('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
        gsChuyenTrang('index.php?action=giohang');
        break;

    case 'xoatoangiohang':
        $gh->xoatatca();
        unset($_SESSION['gs_voucher']);
        gsThongBao('success', 'Đã xóa toàn bộ giỏ hàng!');
        gsChuyenTrang('index.php?action=giohang');
        break;

    case 'apdungvoucher':
        $ma = isset($_POST['ma']) ? $_POST['ma'] : (isset($_GET['ma']) ? $_GET['ma'] : '');
        $kq = $vc->kiemTraVoucher($ma, $gh->tamtinh());
        if ($kq['ok']) {
            $_SESSION['gs_voucher'] = array('ma' => $kq['voucher']['ma']);
        } else {
            unset($_SESSION['gs_voucher']);
        }

        if (gsLaAjax()) {
            gsTraJson(array('ok' => $kq['ok'], 'message' => $kq['message'], 'giamgia' => $kq['giamgia']));
        }
        gsThongBao($kq['ok'] ? 'success' : 'error', $kq['message']);
        $quaylai = (isset($_POST['quaylai']) && $_POST['quaylai'] === 'thanhtoan')
            ? 'index.php?action=thanhtoan'
            : 'index.php?action=giohang';
        gsChuyenTrang($quaylai);
        break;

    case 'boapdungvoucher':
        unset($_SESSION['gs_voucher']);
        gsThongBao('success', 'Đã bỏ mã giảm giá!');
        gsChuyenTrang(gsQuayLai('index.php?action=giohang'));
        break;

    case 'demgiohang':
        gsTraJson(array('soluong' => $gh->dem()));
        break;

    case 'thanhtoan':
        if ($gsNguoidungId <= 0) {
            gsThongBao('warning', 'Vui lòng đăng nhập để tiến hành thanh toán!');
            gsChuyenTrang('../admin/ktnguoidung/index.php?action=dangnhap');
        }
        $giohang = $gh->laygiohang();
        if (count($giohang) === 0) {
            gsThongBao('warning', 'Giỏ hàng của bạn đang trống!');
            gsChuyenTrang('index.php?action=giohang');
        }
        $dsDiachi      = $dc->laydanhsachdiachi($gsNguoidungId);
        $diachimacdinh = $dc->laydiachimacdinh($gsNguoidungId);
        $gsTongtien    = gsTinhTongtienGio($gh, $vc, $gsVoucherApdung);
        include('checkout.php');
        break;

    case 'datchotdon':
    case 'datcod':
    case 'datchuyenkhoan':
    case 'datvnpay':
        if ($gsNguoidungId <= 0) {
            gsThongBao('warning', 'Vui lòng đăng nhập để tiến hành thanh toán!');
            gsChuyenTrang('../admin/ktnguoidung/index.php?action=dangnhap');
        }
        $giohang = $gh->laygiohang();
        if (count($giohang) === 0) {
            gsThongBao('warning', 'Giỏ hàng của bạn đang trống!');
            gsChuyenTrang('index.php?action=giohang');
        }

        $id_diachi    = isset($_POST['id_diachi']) ? (int) $_POST['id_diachi'] : 0;
        $ghichu       = isset($_POST['ghichu']) ? trim($_POST['ghichu']) : '';
        $luudiachi    = isset($_POST['luudiachi']) && (int) $_POST['luudiachi'] === 1;

        $diachidachon = ($id_diachi > 0) ? $dc->laydiachitheoid($id_diachi, $gsNguoidungId) : null;
        if ($diachidachon) {
            $tennguoinhan = $diachidachon['tennguoinhan'];
            $sdtnhan      = $diachidachon['sodienthoai'];
            $diachinhan   = $diachidachon['diachi'];
            $luudiachi    = false;
        } else {
            $tennguoinhan = isset($_POST['tennguoinhan']) ? trim($_POST['tennguoinhan']) : '';
            $sdtnhan      = isset($_POST['sdtnhan']) ? trim($_POST['sdtnhan']) : '';
            $diachinhan   = isset($_POST['diachinhan']) ? trim($_POST['diachinhan']) : '';
        }

        if ($tennguoinhan === '' || $sdtnhan === '' || $diachinhan === '') {
            gsThongBao('error', 'Vui lòng nhập đầy đủ tên người nhận, số điện thoại và địa chỉ giao hàng!');
            gsChuyenTrang('index.php?action=thanhtoan');
        }

        $phuongthuc_tt = 'cod';
        if (isset($_POST['phuongthuc_tt']) && in_array($_POST['phuongthuc_tt'], array('cod', 'chuyenkhoan', 'vnpay'), true)) {
            $phuongthuc_tt = $_POST['phuongthuc_tt'];
        } elseif ($action === 'datchuyenkhoan') {
            $phuongthuc_tt = 'chuyenkhoan';
        } elseif ($action === 'datvnpay') {
            $phuongthuc_tt = 'vnpay';
        }

        if ($luudiachi) {
            $dc->themdiachi($gsNguoidungId, $tennguoinhan, $sdtnhan, $diachinhan, 1);
        }

        $gsTongtien = gsTinhTongtienGio($gh, $vc, $gsVoucherApdung);
        $voucherChot = null;
        if ($gsTongtien['voucher']) {
            $voucherChot = array('id' => $gsTongtien['voucher']['id'], 'giamgia' => $gsTongtien['giamgia']);
        }

        $kq = $dh->taidonhang(
            $gsNguoidungId,
            $tennguoinhan,
            $sdtnhan,
            $diachinhan,
            $phuongthuc_tt,
            $ghichu,
            gsDongdonhang($giohang),
            $voucherChot,
            $gsTongtien['phivanchuyen'],
            0
        );

        if (!$kq['ok']) {
            gsThongBao('error', $kq['message']);
            gsChuyenTrang('index.php?action=thanhtoan');
        }

        unset($_SESSION['gs_voucher']);
        $_SESSION['cart'] = array();

        if ($phuongthuc_tt === 'vnpay') {
            if ($vnpay->dacauhinh()) {
                header('Location: ' . $vnpay->taoUrlthanhtoan($kq['madonhang'], $kq['tongtien'], 'Thanh toan don hang'));
                exit();
            }
            gsChuyenTrang('vnpay_demo.php?madonhang=' . urlencode($kq['madonhang']));
        }

        gsChuyenTrang('index.php?action=hoantat&madonhang=' . urlencode($kq['madonhang']));
        break;

    case 'hoantat':
        $madonhang = isset($_GET['madonhang']) ? trim($_GET['madonhang']) : '';
        $donhang   = ($madonhang !== '') ? $dh->laydonhangtheoma($madonhang) : null;
        $chitiet   = $donhang ? $dh->laychitietdonhang($donhang['id']) : array();
        include('hoantat.php');
        break;

    case 'vnpay_demo':
        $madonhang = isset($_GET['madonhang']) ? trim($_GET['madonhang']) : '';
        $donhang   = ($madonhang !== '') ? $dh->laydonhangtheoma($madonhang) : null;
        include('vnpay_demo.php');
        break;

    case 'vnpay_demo_xuly':
        $madonhang = isset($_POST['madonhang']) ? trim($_POST['madonhang']) : '';
        $donhang   = ($madonhang !== '') ? $dh->laydonhangtheoma($madonhang) : null;
        if (!$donhang) {
            gsThongBao('error', 'Không tìm thấy đơn hàng cần thanh toán!');
            gsChuyenTrang('index.php');
        }

        $ketqua = (isset($_POST['ketqua']) && $_POST['ketqua'] === 'huy') ? 'huy' : 'thanhcong';
        $mahoso = ($ketqua === 'thanhcong') ? '00' : '24';
        if ($ketqua === 'thanhcong') {
            $otp = isset($_POST['otp']) ? trim($_POST['otp']) : '';
            if ($otp !== VNPAY::OTP_TEST) {
                gsThongBao('error', 'Mã OTP không đúng! Thẻ test dùng OTP ' . VNPAY::OTP_TEST . '.');
                gsChuyenTrang('index.php?action=vnpay_demo&madonhang=' . urlencode($madonhang));
            }
            $dh->capnhatdatt($donhang['id'], 1);
        }

        $truyvan = http_build_query(array(
            'vnp_TxnRef'            => $donhang['madonhang'],
            'vnp_Amount'            => (int) round((float) $donhang['tongtien']) * 100,
            'vnp_OrderInfo'         => 'Thanh toan don hang ' . $donhang['madonhang'],
            'vnp_ResponseCode'      => $mahoso,
            'vnp_TransactionStatus' => $mahoso,
            'vnp_TransactionNo'     => 'DEMO' . $donhang['id'],
            'vnp_PayDate'           => date('YmdHis'),
            'vnp_SecureHash'        => 'demo'
        ));
        gsChuyenTrang('vnpay_return.php?' . $truyvan);
        break;

    case 'vnpay_return':
        $madonhang = isset($_GET['vnp_TxnRef']) ? trim($_GET['vnp_TxnRef']) : '';
        $donhang   = ($madonhang !== '') ? $dh->laydonhangtheoma($madonhang) : null;

        $lademo = isset($_GET['vnp_SecureHash']) && $_GET['vnp_SecureHash'] === 'demo';
        $hopLe  = false;
        if ($lademo) {
            $hopLe = true;
        } elseif ($vnpay->dacauhinh()) {
            $hopLe = $vnpay->kiemTrachuky($_GET);
        }

        $thanhcong = (isset($_GET['vnp_ResponseCode']) && $_GET['vnp_ResponseCode'] === '00')
            && (!isset($_GET['vnp_TransactionStatus']) || $_GET['vnp_TransactionStatus'] === '00');

        if (!$donhang) {
            gsThongBao('error', 'Không tìm thấy đơn hàng tương ứng với mã ' . $madonhang . '!');
        } elseif (!$hopLe) {
            gsThongBao('error', 'Kết quả thanh toán không hợp lệ (chữ ký VNPay không đúng)!');
        } elseif ($thanhcong) {
            $dh->capnhatdatt($donhang['id'], 1);
            gsThongBao('success', 'Thanh toán VNPay thành công! Đơn hàng đã được ghi nhận.');
        } else {
            gsThongBao('warning', 'Giao dịch VNPay đã bị hủy hoặc thất bại. Bạn có thể thanh toán lại hoặc chọn COD.');
        }

        $chitiet = $donhang ? $dh->laychitietdonhang($donhang['id']) : array();
        include('vnpay_return.php');
        break;

    case 'donhang':
        if ($gsNguoidungId <= 0) {
            gsThongBao('warning', 'Vui lòng đăng nhập để xem đơn hàng của bạn!');
            gsChuyenTrang('../admin/ktnguoidung/index.php?action=dangnhap');
        }
        $dsDonhang = $dh->laydanhsachdonhang($gsNguoidungId);
        include('donhang.php');
        break;

    default:
        $gsGoiy = gsTinhGoiyChiSo($sp, $gsNguoidungId);
        include('main.php');
        break;
}
?>
