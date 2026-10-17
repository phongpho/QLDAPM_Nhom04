<?php

if (!function_exists('gsThongBao')) {
    function gsThongBao($loai, $noidung)
    {
        $_SESSION['gs_flash'] = array('loai' => $loai, 'noidung' => $noidung);
    }

    function gsLayThongBao()
    {
        if (!isset($_SESSION['gs_flash'])) {
            return null;
        }
        $thongbao = $_SESSION['gs_flash'];
        unset($_SESSION['gs_flash']);
        return $thongbao;
    }

    function gsChuyenTrang($duongdan)
    {
        header('Location: ' . $duongdan);
        exit();
    }

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

    function gsTien($sotien)
    {
        return number_format((float) $sotien, 0, ',', '.') . 'đ';
    }

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

    function gsTinhBmi($chieucao, $cannang)
    {
        $m = (float) $chieucao / 100;
        if ($m <= 0) {
            return 0;
        }
        return round((float) $cannang / ($m * $m), 1);
    }

    function gsTenMuctieu($muctieu)
    {
        $mang = array(
            'giamcan' => 'Giảm cân / Giảm mỡ',
            'tangcan' => 'Tăng cân / Tăng cơ',
            'duytri'  => 'Duy trì vóc dáng'
        );
        return isset($mang[$muctieu]) ? $mang[$muctieu] : $muctieu;
    }

    function gsTenLoai($loai)
    {
        $mang = array(
            'quanao'  => 'Đồ tập',
            'phukien' => 'Phụ kiện',
            'tpbs'    => 'Thực phẩm bổ sung'
        );
        return isset($mang[$loai]) ? $mang[$loai] : $loai;
    }

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
