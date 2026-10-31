<?php
class VNPAY
{
    const VNP_URL = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    const VNP_TMNCODE = 'Q283846N';
    const VNP_HASHSECRET = 'QVZUTGRVUPJWBFUBNBBMFDVMAXWKFADL';
    const VNP_VERSION = '2.1.0';

    const THE_TEST = '9704198526191432198';
    const CHU_THE_TEST = 'NGUYEN VAN A';
    const NGAY_PHAT_HANH_TEST = '07/15';
    const OTP_TEST = '123456';

    
    public function dacauhinh()
    {
        return self::VNP_TMNCODE !== '' && self::VNP_HASHSECRET !== '';
    }

    public function layIp()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
    }

    public function layReturnUrl()
    {
        return $this->layUrlgoc() . '/vnpay_return.php';
    }


    private function layUrlgoc()
    {
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);
        $giao_thuc = $https ? 'https' : 'http';
        $ten_mien = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';

        $thu_muc = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])) : '/public';
        $thu_muc = rtrim($thu_muc, '/');
        if ($thu_muc === '' || $thu_muc === '.') {
            $thu_muc = '';
        }
        return $giao_thuc . '://' . $ten_mien . $thu_muc;
    }

    
    public function taoUrlthanhtoan($madonhang, $sotien, $mota = 'Thanh toan don hang', $nganhang = 'NCB')
    {
        $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
        $now = new DateTime('now', $tz);
        $vnp_CreateDate = $now->format('YmdHis');
        $expire = clone $now;
        $expire->modify('+15 minutes');
        $vnp_ExpireDate = $expire->format('YmdHis');

        $duLieu = array(
            'vnp_Version' => self::VNP_VERSION,
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => self::VNP_TMNCODE,
            'vnp_Locale' => 'vn',
            'vnp_CurrCode' => 'VND',
            'vnp_TxnRef' => $madonhang,
            'vnp_OrderInfo' => $mota . ' ' . $madonhang,
            'vnp_OrderType' => 'other',
            'vnp_Amount' => (int) round($sotien) * 100,
            'vnp_ReturnUrl' => $this->layReturnUrl(),
            'vnp_IpAddr' => $this->layIp(),
            'vnp_CreateDate' => $vnp_CreateDate,
            'vnp_ExpireDate' => $vnp_ExpireDate
        );
        if ($nganhang !== '') {
            $duLieu['vnp_BankCode'] = $nganhang;
        }

        ksort($duLieu);
        $chuoi_bam = '';
        $chuoi_truyvan = '';
        $i = 0;
        foreach ($duLieu as $khoa => $giatri) {
            if ($i === 1) {
                $chuoi_bam .= '&' . urlencode($khoa) . '=' . urlencode($giatri);
            } else {
                $chuoi_bam .= urlencode($khoa) . '=' . urlencode($giatri);
                $i = 1;
            }
            $chuoi_truyvan .= urlencode($khoa) . '=' . urlencode($giatri) . '&';
        }

        $chuky = hash_hmac('sha512', $chuoi_bam, self::VNP_HASHSECRET);
        return self::VNP_URL . '?' . $chuoi_truyvan . 'vnp_SecureHash=' . $chuky;
    }

    
    public function kiemTrachuky($duLieu)
    {
        if (!isset($duLieu['vnp_SecureHash'])) {
            return false;
        }
        $chuky_nhan = $duLieu['vnp_SecureHash'];

        $duLieuLoc = array();
        foreach ($duLieu as $khoa => $giatri) {
            if (substr($khoa, 0, 4) === 'vnp_' && $khoa !== 'vnp_SecureHash' && $khoa !== 'vnp_SecureHashType') {
                $duLieuLoc[$khoa] = $giatri;
            }
        }

        ksort($duLieuLoc);
        $chuoi_bam = '';
        $i = 0;
        foreach ($duLieuLoc as $khoa => $giatri) {
            if ($i === 1) {
                $chuoi_bam .= '&' . urlencode($khoa) . '=' . urlencode($giatri);
            } else {
                $chuoi_bam .= urlencode($khoa) . '=' . urlencode($giatri);
                $i = 1;
            }
        }

        return hash_hmac('sha512', $chuoi_bam, self::VNP_HASHSECRET) === $chuky_nhan;
    }

   
    public function layUrlQrVietQR($madonhang, $sotien, $nganhang = '970436', $sotaikhoan = '1234567890', $tenchu = 'GYMBRO')
    {
        return 'https://img.vietqr.io/image/' . $nganhang . '-' . $sotaikhoan . '-compact2.png'
            . '?amount=' . (int) round($sotien)
            . '&addInfo=' . urlencode('Thanh toan don hang ' . $madonhang)
            . '&accountName=' . urlencode($tenchu);
    }
}
?>