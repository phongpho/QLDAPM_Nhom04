<?php
include ('../model/database.php');
include ('../model/sanpham.php');


// === BẮT ĐẦU CODE MODULE 3 & 6 ===
/* =====================================================================
   ROUTER MODULE 3 (HIỂN THỊ SẢN PHẨM) & MODULE 6 (GIỎ HÀNG - THANH TOÁN)
   ---------------------------------------------------------------------
   Cách gọi: index.php?action=<action>
   - Khi KHÔNG truyền action (hoặc action không nằm trong danh sách), file
     này nạp main.php đúng như hành vi cũ.
   - Các file shim cùng thư mục (sanpham.php, chitiet.php, giohang.php,
     thanhtoan.php, goy.php) chỉ gán $_REQUEST['action'] rồi require file
     này, nhờ vậy các link cũ trong navbar/footer vẫn hoạt động bình thường.
   ===================================================================== */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../model/danhmuc.php');
require_once(__DIR__ . '/../model/giohang.php');
require_once(__DIR__ . '/../model/voucher.php');
require_once(__DIR__ . '/../model/donhang.php');
require_once(__DIR__ . '/../model/diachi.php');
require_once(__DIR__ . '/../model/vnpay.php');
require_once(__DIR__ . '/inc/module36.php');

$gsModule36 = true;   // đánh dấu view đang được nạp qua router

// Khách đang đăng nhập? (đăng nhập ở admin/ktnguoidung, khách có loai = 3)
$gsND = isset($_SESSION['nguoidung']) ? $_SESSION['nguoidung'] : null;
$gsNguoidungId = $gsND ? (int) $gsND['id'] : 0;

$sp   = new SANPHAM();
$dm36 = new DANHMUC();
$gh   = new GIOHANG($gsNguoidungId);
$vc   = new VOUCHER();
$dh   = new DONHANG();
$dc   = new DIACHI();
$vnpay = new VNPAY();

// Khách vãng lai vừa đăng nhập: đồng bộ giỏ hàng trong session vào bảng giohang
$gh->dongbo();

// Mã giảm giá đang áp dụng cho giỏ hàng (lưu trong session)
$gsVoucherApdung = isset($_SESSION['gs_voucher']) ? $_SESSION['gs_voucher'] : null;

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($action) {

    /* ===================== MODULE 3: HIỂN THỊ SẢN PHẨM ===================== */

    // Trang danh mục: lọc theo nhóm + đối tượng + mục tiêu, tìm kiếm, sắp xếp, phân trang
    case 'sanpham':
        $loai     = isset($_GET['loai']) ? trim($_GET['loai']) : '';
        $doituong = isset($_GET['doituong']) ? trim($_GET['doituong']) : '';
        $muctieu  = isset($_GET['muctieu']) ? trim($_GET['muctieu']) : '';
        $tukhoa   = isset($_GET['q']) ? trim($_GET['q']) : '';
        $sapxep   = isset($_GET['sapxep']) ? trim($_GET['sapxep']) : 'moi';
        $trang    = isset($_GET['trang']) ? max(1, (int) $_GET['trang']) : 1;
        $motrang  = 8;

        $tongsp = $sp->demtimkiemsanpham($tukhoa, $loai, $doituong, $muctieu);
        $tongsotrang = (int) ceil($tongsp / $motrang);
        if ($tongsotrang > 0 && $trang > $tongsotrang) {
            $trang = $tongsotrang;
        }
        $dsSanpham = $sp->timkiemsanpham($tukhoa, $loai, $doituong, $muctieu, $sapxep, $motrang, ($trang - 1) * $motrang);

        // Số lượng sản phẩm từng nhóm để hiển thị trên bộ lọc
        $soluongnhom = array(
            'quanao'  => $dm36->laysoluongsanphamtheoloai('quanao'),
            'phukien' => $dm36->laysoluongsanphamtheoloai('phukien'),
            'tpbs'    => $dm36->laysoluongsanphamtheoloai('tpbs')
        );

        $gsGoiy = gsTinhGoiyChiSo($sp, $gsNguoidungId);
        include('danhmuc.php');
        break;

    // Trang chi tiết sản phẩm
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

        // Gợi ý theo chỉ số cơ thể + size tự chọn sẵn cho sản phẩm này
        $gsGoiy = gsTinhGoiyChiSo($sp, $gsNguoidungId);
        $sizegoiy = '';
        if ($gsGoiy && $gsGoiy['sizegoiy'] && in_array($gsGoiy['sizegoiy']['size'], $mangsizes, true)) {
            $sizegoiy = $gsGoiy['sizegoiy']['size'];
        }

        include('detail.php');
        break;

    // Trang nhập chỉ số cơ thể & xem gợi ý cá nhân hóa (chỉ dành cho khách đã đăng nhập)
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

    // Lưu hồ sơ thể trạng của khách đang đăng nhập
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

    /* ===================== MODULE 6: GIỎ HÀNG ===================== */

    // Trang giỏ hàng
    case 'giohang':
        $giohang    = $gh->laygiohang();
        $gsTongtien = gsTinhTongtienGio($gh, $vc, $gsVoucherApdung);

        // Gợi ý mua kèm theo mục tiêu của khách (chỉ khách đã đăng nhập mới thấy khối này)
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

    // Thêm 1 sản phẩm vào giỏ (hỗ trợ cả AJAX)
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

    // Thêm cả combo gợi ý vào giỏ (đồ tập tự nhận size gợi ý, TPBS nhận size = '')
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
                $size = $sizechon;                                  // khách tự chọn size ở trang chi tiết
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

    // Tăng / giảm / cập nhật số lượng 1 dòng trong giỏ
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

    // Xóa 1 dòng trong giỏ
    case 'xoagiohang':
        $sanpham_id = isset($_REQUEST['sanpham_id']) ? (int) $_REQUEST['sanpham_id'] : 0;
        $size       = isset($_REQUEST['size']) ? (string) $_REQUEST['size'] : '';
        $gh->xoa($sanpham_id, $size);
        gsThongBao('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
        gsChuyenTrang('index.php?action=giohang');
        break;

    // Xóa toàn bộ giỏ hàng
    case 'xoatoangiohang':
        $gh->xoatatca();
        unset($_SESSION['gs_voucher']);
        gsThongBao('success', 'Đã xóa toàn bộ giỏ hàng!');
        gsChuyenTrang('index.php?action=giohang');
        break;

    // Áp dụng mã giảm giá
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

    // Bỏ mã giảm giá đang áp dụng
    case 'boapdungvoucher':
        unset($_SESSION['gs_voucher']);
        gsThongBao('success', 'Đã bỏ mã giảm giá!');
        gsChuyenTrang(gsQuayLai('index.php?action=giohang'));
        break;

    // Đếm số lượng sản phẩm trong giỏ (cập nhật badge trên navbar)
    case 'demgiohang':
        gsTraJson(array('soluong' => $gh->dem()));
        break;

    /* ===================== MODULE 6: THANH TOÁN ===================== */

    // Trang thanh toán
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

    // Chốt đơn: COD / chuyển khoản / VNPay
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

        // Khách chọn địa chỉ có sẵn trong sổ địa chỉ -> dùng đúng thông tin đã lưu
        $diachidachon = ($id_diachi > 0) ? $dc->laydiachitheoid($id_diachi, $gsNguoidungId) : null;
        if ($diachidachon) {
            $tennguoinhan = $diachidachon['tennguoinhan'];
            $sdtnhan      = $diachidachon['sodienthoai'];
            $diachinhan   = $diachidachon['diachi'];
            $luudiachi    = false;   // đã có trong sổ địa chỉ nên không lưu lại
        } else {
            $tennguoinhan = isset($_POST['tennguoinhan']) ? trim($_POST['tennguoinhan']) : '';
            $sdtnhan      = isset($_POST['sdtnhan']) ? trim($_POST['sdtnhan']) : '';
            $diachinhan   = isset($_POST['diachinhan']) ? trim($_POST['diachinhan']) : '';
        }

        if ($tennguoinhan === '' || $sdtnhan === '' || $diachinhan === '') {
            gsThongBao('error', 'Vui lòng nhập đầy đủ tên người nhận, số điện thoại và địa chỉ giao hàng!');
            gsChuyenTrang('index.php?action=thanhtoan');
        }

        // Phương thức thanh toán: ưu tiên radio ở trang thanh toán
        $phuongthuc_tt = 'cod';
        if (isset($_POST['phuongthuc_tt']) && in_array($_POST['phuongthuc_tt'], array('cod', 'chuyenkhoan', 'vnpay'), true)) {
            $phuongthuc_tt = $_POST['phuongthuc_tt'];
        } elseif ($action === 'datchuyenkhoan') {
            $phuongthuc_tt = 'chuyenkhoan';
        } elseif ($action === 'datvnpay') {
            $phuongthuc_tt = 'vnpay';
        }

        // Lưu địa chỉ mới vào sổ địa chỉ nếu khách chọn
        if ($luudiachi) {
            $dc->themdiachi($gsNguoidungId, $tennguoinhan, $sdtnhan, $diachinhan, 1);
        }

        // Tính tiền + kiểm tra lại voucher ngay tại thời điểm chốt đơn
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

        // Đơn đã tạo: bỏ voucher đang áp dụng và dọn giỏ hàng trong session
        unset($_SESSION['gs_voucher']);
        $_SESSION['cart'] = array();

        if ($phuongthuc_tt === 'vnpay') {
            if ($vnpay->dacauhinh()) {
                // Đã có tài khoản VNPay Sandbox thật -> chuyển sang cổng thanh toán VNPay
                header('Location: ' . $vnpay->taoUrlthanhtoan($kq['madonhang'], $kq['tongtien'], 'Thanh toan don hang'));
                exit();
            }
            // Chưa cấu hình: dùng cổng thanh toán VNPay mô phỏng để demo luôn chạy được
            gsChuyenTrang('vnpay_demo.php?madonhang=' . urlencode($kq['madonhang']));
        }

        gsChuyenTrang('index.php?action=hoantat&madonhang=' . urlencode($kq['madonhang']));
        break;

    // Trang kết quả đặt hàng (COD / chuyển khoản)
    case 'hoantat':
        $madonhang = isset($_GET['madonhang']) ? trim($_GET['madonhang']) : '';
        $donhang   = ($madonhang !== '') ? $dh->laydonhangtheoma($madonhang) : null;
        $chitiet   = $donhang ? $dh->laychitietdonhang($donhang['id']) : array();
        include('hoantat.php');
        break;

    // Cổng thanh toán VNPay Sandbox mô phỏng (chế độ demo nội bộ)
    case 'vnpay_demo':
        $madonhang = isset($_GET['madonhang']) ? trim($_GET['madonhang']) : '';
        $donhang   = ($madonhang !== '') ? $dh->laydonhangtheoma($madonhang) : null;
        include('vnpay_demo.php');
        break;

    // Khách bấm "Xác nhận thanh toán" / "Hủy giao dịch" ở cổng mô phỏng
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
            $dh->capnhatdatt($donhang['id'], 1);
        }

        // Trả về đúng các tham số mà VNPay gửi cho vnp_ReturnUrl
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

    // Trang nhận kết quả thanh toán VNPay (vnp_ReturnUrl)
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

    // Danh sách đơn hàng của khách
    case 'donhang':
        if ($gsNguoidungId <= 0) {
            gsThongBao('warning', 'Vui lòng đăng nhập để xem đơn hàng của bạn!');
            gsChuyenTrang('../admin/ktnguoidung/index.php?action=dangnhap');
        }
        $dsDonhang = $dh->laydanhsachdonhang($gsNguoidungId);
        include('donhang.php');
        break;

    default:
        // Trang chủ: tính trước khối "Gợi ý cá nhân hóa" + các section sản phẩm từ CSDL
        $gsGoiy = gsTinhGoiyChiSo($sp, $gsNguoidungId);
        include('main.php');
        break;
}
// === KẾT THÚC CODE MODULE 3 & 6 ===
?>
