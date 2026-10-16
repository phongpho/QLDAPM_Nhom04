<?php
/* =====================================================================
   HÀM DÙNG CHUNG CHO MODULE 3 (HIỂN THỊ SẢN PHẨM) & MODULE 6 (GIỎ HÀNG)
   Được nạp 1 lần từ router public/index.php
   ===================================================================== */

// Ghi thông báo hiển thị ở lần tải trang kế tiếp (SweetAlert2)
if (!function_exists('gsThongBao')) {
    function gsThongBao($loai, $noidung)
    {
        $_SESSION['gs_flash'] = array('loai' => $loai, 'noidung' => $noidung);
    }

    // Đọc và xóa thông báo đang chờ
    function gsLayThongBao()
    {
        if (!isset($_SESSION['gs_flash'])) {
            return null;
        }
        $thongbao = $_SESSION['gs_flash'];
        unset($_SESSION['gs_flash']);
        return $thongbao;
    }

    // Chuyển trang rồi dừng xử lý
    function gsChuyenTrang($duongdan)
    {
        header('Location: ' . $duongdan);
        exit();
    }

    // Quay lại trang trước đó (an toàn: chỉ chấp nhận referer cùng tên miền)
    function gsQuayLai($macdinh = 'index.php')
    {
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
            if ($host !== '' && strpos($_SERVER['HTTP_REFERER'], $host) !== false) {
                return $_SERVER['HTTP_REFERER'];
            }
        }
        return $macdinh;
    }

    // Yêu cầu gửi từ fetch()/AJAX -> trả JSON thay vì chuyển trang
    function gsLaAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    function gsTraJson($mang)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($mang);
        exit();
    }

    // Định dạng tiền Việt Nam
    function gsTien($sotien)
    {
        return number_format((float) $sotien, 0, ',', '.') . 'đ';
    }

    /* ------------------------------------------------------------------
       Tính BMI + gợi ý size + sản phẩm/combo theo chỉ số cơ thể
       - Lấy hồ sơ thể trạng của khách đang đăng nhập (nếu có)
       - Đối chiếu bmi với bảng cauhinh_bmi -> phanloai + khuyenghe
       - Tra bảng bangsize -> size gợi ý (thutu lớn hơn giữa size theo cao và theo nặng)
       - Lọc sản phẩm theo muctieu (FIND_IN_SET) + gioitinh (IN + 'unisex')
       - Ghép combo: 1 Đồ tập + 1 Phụ kiện + 1 TPBS
       Trả về null nếu chưa đủ dữ liệu để gợi ý
       ------------------------------------------------------------------ */
    function gsTinhGoiyChiSo($sp, $nguoidung_id, $chieucao = null, $cannang = null, $gioitinh = null, $tuoi = null, $muctieu = null)
    {
        $hoso = ($nguoidung_id > 0) ? $sp->layhosothetrang($nguoidung_id) : null;

        if ($hoso) {
            if ($chieucao === null || $chieucao === '') { $chieucao = $hoso['chieucao']; }
            if ($cannang === null || $cannang === '') { $cannang = $hoso['cannang']; }
            if ($gioitinh === null || $gioitinh === '') { $gioitinh = $hoso['gioitinh']; }
            if ($tuoi === null || $tuoi === '') { $tuoi = $hoso['tuoi']; }
            if ($muctieu === null || $muctieu === '') { $muctieu = $hoso['muctieu']; }
        }

        if ($chieucao === null || $chieucao === '' || $cannang === null || $cannang === '') {
            return null;
        }
        $chieucao = (float) $chieucao;
        $cannang = (float) $cannang;
        if ($chieucao < 100 || $chieucao > 250 || $cannang < 25 || $cannang > 300) {
            return null;
        }
        if ($gioitinh !== 'nam' && $gioitinh !== 'nu') {
            $gioitinh = 'nam';
        }
        if ($muctieu !== 'giamcan' && $muctieu !== 'tangcan' && $muctieu !== 'duytri') {
            $muctieu = 'duytri';
        }

        $bmi = gsTinhBmi($chieucao, $cannang);
        $cauhinh = $sp->laycauhinhbmi($bmi);
        $sizegoiy = $sp->laysizegoiy($gioitinh, $chieucao, $cannang);
        $danhsach = $sp->laysanphamtheomuctieu($muctieu, $gioitinh, 8);
        $combo = $sp->laysanphamtheocombo($muctieu, $gioitinh);

        return array(
            'cohoso'   => $hoso ? true : false,
            'hoso'     => $hoso,
            'chieucao' => $chieucao,
            'cannang'  => $cannang,
            'tuoi'     => (int) $tuoi,
            'gioitinh' => $gioitinh,
            'muctieu'  => $muctieu,
            'bmi'      => $bmi,
            'cauhinh'  => $cauhinh,
            'sizegoiy' => $sizegoiy,
            'sanpham'  => $danhsach,
            'combo'    => $combo
        );
    }

    // BMI = cân nặng / (chiều cao/100)^2 (làm tròn 1 chữ số thập phân)
    function gsTinhBmi($chieucao, $cannang)
    {
        $m = (float) $chieucao / 100;
        if ($m <= 0) {
            return 0;
        }
        return round((float) $cannang / ($m * $m), 1);
    }

    // Nhãn mục tiêu tập luyện
    function gsTenMuctieu($muctieu)
    {
        $mang = array(
            'giamcan' => 'Giảm cân / Giảm mỡ',
            'tangcan' => 'Tăng cân / Tăng cơ',
            'duytri'  => 'Duy trì vóc dáng'
        );
        return isset($mang[$muctieu]) ? $mang[$muctieu] : $muctieu;
    }

    // Nhãn nhóm lớn danh mục
    function gsTenLoai($loai)
    {
        $mang = array(
            'quanao'  => 'Đồ tập',
            'phukien' => 'Phụ kiện',
            'tpbs'    => 'Thực phẩm bổ sung'
        );
        return isset($mang[$loai]) ? $mang[$loai] : $loai;
    }

    /* ------------------------------------------------------------------
       Tính tiền giỏ hàng: tạm tính - giảm giá + phí vận chuyển
       Phí vận chuyển: 30.000đ, miễn phí cho đơn từ 500.000đ
       ------------------------------------------------------------------ */
    function gsTinhTongtienGio($gh, $vc, $voucherApdung = null)
    {
        $tamtinh = (float) $gh->tamtinh();
        $giamgia = 0;
        $voucher = null;
        if ($voucherApdung && isset($voucherApdung['ma'])) {
            $kq = $vc->kiemTraVoucher($voucherApdung['ma'], $tamtinh);
            if ($kq['ok']) {
                $giamgia = (float) $kq['giamgia'];
                $voucher = $kq['voucher'];
            }
        }

        $phivanchuyen = 0;
        if ($tamtinh > 0 && $tamtinh < 500000) {
            $phivanchuyen = 30000;
        }

        return array(
            'tamtinh'      => $tamtinh,
            'giamgia'      => $giamgia,
            'voucher'      => $voucher,
            'phivanchuyen' => $phivanchuyen,
            'tongtien'     => max(0, $tamtinh - $giamgia + $phivanchuyen)
        );
    }

    /* Chuyển danh sách giỏ hàng thành danh sách dòng đơn hàng để tạo donhangct */
    function gsDongdonhang($giohang)
    {
        $items = array();
        foreach ($giohang as $dong) {
            $items[] = array(
                'sanpham_id' => (int) $dong['sanpham_id'],
                'tensanpham' => $dong['tensanpham'],
                'size'       => (string) $dong['size'],
                'dongia'     => (float) $dong['giaban'],
                'soluong'    => (int) $dong['soluong']
            );
        }
        return $items;
    }
}
?>
