<?php
/* =====================================================================
   MODULE 6 - TÍCH HỢP VNPAY SANDBOX
   - taoUrlthanhtoan(): tạo URL thanh toán chuẩn VNPay (vnp_SecureHash sha512)
   - kiemTrachuky()   : kiểm tra chữ ký dữ liệu VNPay trả về
   - Chế độ DEMO nội bộ: khi chưa điền vnp_TmnCode / vnp_HashSecret (hoặc chạy
     offline), hệ thống dùng cổng thanh toán VNPay mô phỏng (vnpay_demo.php)
     để luồng đặt hàng luôn chạy được khi demo trước giảng viên.
   ===================================================================== */
class VNPAY
{
    // Cấu hình VNPay Sandbox - điền thông tin thật do VNPay cấp để chạy online
    const VNP_URL = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    const VNP_TMNCODE = '';      // vnp_TmnCode
    const VNP_HASHSECRET = '';   // vnp_HashSecret
    const VNP_VERSION = '2.1.0';

    // Thẻ test của VNPay Sandbox (hiển thị sẵn ở màn hình mô phỏng)
    const THE_TEST = '9704198526191432198';       // Ngân hàng NCB
    const CHU_THE_TEST = 'NGUYEN VAN A';
    const NGAY_PHAT_HANH_TEST = '07/15';
    const OTP_TEST = '123456';

    // Đã cấu hình tài khoản VNPay thật chưa?
    public function dacauhinh()
    {
        return self::VNP_TMNCODE !== '' && self::VNP_HASHSECRET !== '';
    }

    // IP của khách (bắt buộc với VNPay)
    public function layIp()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
    }

    // URL tuyệt đối của trang nhận kết quả (vnp_ReturnUrl)
    public function layReturnUrl()
    {
        return $this->layUrlgoc() . '/vnpay_return.php';
    }

    // URL tuyệt đối của cổng thanh toán mô phỏng (chế độ demo)
    public function layUrlDemoThanhToan()
    {
        return $this->layUrlgoc() . '/vnpay_demo.php';
    }

    // Xây URL gốc của thư mục public/ (tự nhận diện http/https + tên miền)
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

    /* ------------------------------------------------------------------
       Tạo URL thanh toán VNPay Sandbox
       ------------------------------------------------------------------ */
    public function taoUrlthanhtoan($madonhang, $sotien, $mota = 'Thanh toan don hang', $nganhang = 'NCB')
    {
        $duLieu = array(
            'vnp_Version'    => self::VNP_VERSION,
            'vnp_Command'    => 'pay',
            'vnp_TmnCode'    => self::VNP_TMNCODE,
            'vnp_Locale'     => 'vn',
            'vnp_CurrCode'   => 'VND',
            'vnp_TxnRef'     => $madonhang,
            'vnp_OrderInfo'  => $mota . ' ' . $madonhang,
            'vnp_OrderType'  => 'other',
            'vnp_Amount'     => (int) round($sotien) * 100,
            'vnp_ReturnUrl'  => $this->layReturnUrl(),
            'vnp_IpAddr'     => $this->layIp(),
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_ExpireDate' => date('YmdHis', strtotime('+15 minutes'))
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

    /* ------------------------------------------------------------------
       Kiểm tra chữ ký dữ liệu VNPay trả về (vnp_SecureHash)
       ------------------------------------------------------------------ */
    public function kiemTrachuky($duLieu)
    {
        if (!isset($duLieu['vnp_SecureHash'])) {
            return false;
        }
        $chuky_nhan = $duLieu['vnp_SecureHash'];
        unset($duLieu['vnp_SecureHash'], $duLieu['vnp_SecureHashType']);

        ksort($duLieu);
        $chuoi_bam = '';
        $i = 0;
        foreach ($duLieu as $khoa => $giatri) {
            if ($i === 1) {
                $chuoi_bam .= '&' . urlencode($khoa) . '=' . urlencode($giatri);
            } else {
                $chuoi_bam .= urlencode($khoa) . '=' . urlencode($giatri);
                $i = 1;
            }
        }

        return hash_hmac('sha512', $chuoi_bam, self::VNP_HASHSECRET) === $chuky_nhan;
    }

    /* ------------------------------------------------------------------
       Ảnh QR VietQR cho phương thức chuyển khoản khoản ngân hàng
       (dùng API ảnh công khai của VietQR, tự điền số tiền + nội dung là mã đơn)
       ------------------------------------------------------------------ */
    public function layUrlQrVietQR($madonhang, $sotien, $nganhang = '970436', $sotaikhoan = '1234567890', $tenchu = 'GYMBRO')
    {
        return 'https://img.vietqr.io/image/' . $nganhang . '-' . $sotaikhoan . '-compact2.png'
            . '?amount=' . (int) round($sotien)
            . '&addInfo=' . urlencode('Thanh toan don hang ' . $madonhang)
            . '&accountName=' . urlencode($tenchu);
    }
}
?>
