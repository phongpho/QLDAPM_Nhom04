<?php
/* =====================================================================
   MODULE 6 - GIá»Ž HÃ€NG
   Quáº£n lÃ½ giá» hÃ ng trÃªn 2 nguá»“n dá»¯ liá»‡u:
   - KhÃ¡ch Ä‘Ã£ Ä‘Äƒng nháº­p : thao tÃ¡c trá»±c tiáº¿p trÃªn báº£ng `giohang`
   - KhÃ¡ch chÆ°a Ä‘Äƒng nháº­p: lÆ°u táº¡m vÃ o $_SESSION['cart'], tá»± Ä‘á»“ng bá»™ vÃ o
     báº£ng `giohang` ngay khi Ä‘Äƒng nháº­p (xem hÃ m dongbo())
   Má»™t dÃ²ng giá» hÃ ng = (sanpham_id, size) - size = '' vá»›i sáº£n pháº©m freesize
   ===================================================================== */
class GIOHANG
{
    private $nguoidung_id;

    public function __construct($nguoidung_id = 0)
    {
        $this->nguoidung_id = (int) $nguoidung_id;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
    }

    public function getNguoidungId()
    {
        return $this->nguoidung_id;
    }

    // KhÃ¡ch Ä‘Ã£ Ä‘Äƒng nháº­p thÃ¬ giá» náº±m trong CSDL
    public function dadangnhap()
    {
        return $this->nguoidung_id > 0;
    }

    /* KhÃ³a 1 dÃ²ng giá» hÃ ng trong session: "sanpham_id|size" */
    private function khoadong($sanpham_id, $size)
    {
        return (int) $sanpham_id . '|' . (string) $size;
    }

    /* ------------------------------------------------------------------
       Danh sÃ¡ch sáº£n pháº©m trong giá» (kÃ¨m thÃ´ng tin sáº£n pháº©m Ä‘á»ƒ hiá»ƒn thá»‹)
       ------------------------------------------------------------------ */
    public function laygiohang()
    {
        if ($this->dadangnhap()) {
            $dbcon = DATABASE::connect();
            try {
                $sql = "SELECT gh.id, gh.sanpham_id, gh.size, gh.soluong,
                               s.tensanpham, s.hinhanh, s.giaban, s.giagoc, s.soluongton, s.sizes,
                               d.tendanhmuc, t.tenthuonghieu,
                               (s.giaban * gh.soluong) AS thanhtien
                        FROM giohang gh
                        JOIN sanpham s ON s.id = gh.sanpham_id
                        LEFT JOIN danhmuc d ON d.id = s.danhmuc_id
                        LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
                        WHERE gh.nguoidung_id = :nguoidung_id
                        ORDER BY gh.id DESC";
                $cmd = $dbcon->prepare($sql);
                $cmd->bindValue(":nguoidung_id", $this->nguoidung_id, PDO::PARAM_INT);
                $cmd->execute();
                return $cmd->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "<p>Lá»—i truy váº¥n: " . $e->getMessage() . "</p>";
                exit();
            }
        }

        // KhÃ¡ch vÃ£ng lai: Ä‘á»c tá»« session rá»“i láº¥y thÃ´ng tin sáº£n pháº©m
        $gio = array();
        $sp = new SANPHAM();
        foreach ($_SESSION['cart'] as $item) {
            $sanpham = $sp->laysanphamchitiet($item['sanpham_id']);
            if (!$sanpham) {
                continue;
            }
            $gio[] = array(
                'id'             => $this->khoadong($item['sanpham_id'], $item['size']),
                'sanpham_id'     => (int) $item['sanpham_id'],
                'size'           => (string) $item['size'],
                'soluong'        => (int) $item['soluong'],
                'tensanpham'     => $sanpham['tensanpham'],
                'hinhanh'        => $sanpham['hinhanh'],
                'giaban'         => $sanpham['giaban'],
                'giagoc'         => $sanpham['giagoc'],
                'soluongton'     => $sanpham['soluongton'],
                'sizes'          => $sanpham['sizes'],
                'tendanhmuc'     => $sanpham['tendanhmuc'],
                'tenthuonghieu'  => $sanpham['tenthuonghieu'],
                'thanhtien'      => (float) $sanpham['giaban'] * (int) $item['soluong']
            );
        }
        return $gio;
    }

    // Sá»‘ lÆ°á»£ng Ä‘Ã£ cÃ³ trong giá» cá»§a 1 dÃ²ng (sanpham_id + size)
    public function laysoluongtronggio($sanpham_id, $size = '')
    {
        if ($this->dadangnhap()) {
            $dbcon = DATABASE::connect();
            try {
                $sql = "SELECT soluong FROM giohang
                        WHERE nguoidung_id = :nguoidung_id AND sanpham_id = :sanpham_id AND size = :size
                        LIMIT 1";
                $cmd = $dbcon->prepare($sql);
                $cmd->bindValue(":nguoidung_id", $this->nguoidung_id, PDO::PARAM_INT);
                $cmd->bindValue(":sanpham_id", (int) $sanpham_id, PDO::PARAM_INT);
                $cmd->bindValue(":size", (string) $size);
                $cmd->execute();
                $row = $cmd->fetch(PDO::FETCH_ASSOC);
                return $row ? (int) $row['soluong'] : 0;
            } catch (PDOException $e) {
                echo "<p>Lá»—i truy váº¥n: " . $e->getMessage() . "</p>";
                exit();
            }
        }

        $khoa = $this->khoadong($sanpham_id, $size);
        return isset($_SESSION['cart'][$khoa]) ? (int) $_SESSION['cart'][$khoa]['soluong'] : 0;
    }

    /* ------------------------------------------------------------------
       ThÃªm sáº£n pháº©m vÃ o giá» (kiá»ƒm tra khÃ´ng vÆ°á»£t quÃ¡ soluongton)
       Tráº£ vá»: array('ok' => bool, 'message' => string)
       ------------------------------------------------------------------ */
    public function them($sanpham_id, $size = '', $soluong = 1)
    {
        $sp = new SANPHAM();
        $sanpham = $sp->laysanphamchitiet($sanpham_id);
        if (!$sanpham || (int) $sanpham['trangthai'] !== 1) {
            return array('ok' => false, 'message' => 'Sáº£n pháº©m khÃ´ng tá»“n táº¡i hoáº·c Ä‘Ã£ ngá»«ng bÃ¡n!');
        }

        $soluong = max(1, (int) $soluong);
        $size = (string) $size;
        $ton = (int) $sanpham['soluongton'];
        if ($ton <= 0) {
            return array('ok' => false, 'message' => 'Sáº£n pháº©m "' . $sanpham['tensanpham'] . '" Ä‘Ã£ háº¿t hÃ ng!');
        }

        // Kiá»ƒm tra size cÃ³ náº±m trong danh sÃ¡ch size cá»§a sáº£n pháº©m hay khÃ´ng
        $mangsizes = $sp->tachsizethanhmmang($sanpham['sizes']);
        if (count($mangsizes) > 0 && !in_array($size, $mangsizes, true)) {
            return array('ok' => false, 'message' => 'Vui lÃ²ng chá»n size cho sáº£n pháº©m "' . $sanpham['tensanpham'] . '"!');
        }
        if (count($mangsizes) === 0) {
            $size = '';
        }

        $cur = $this->laysoluongtronggio($sanpham_id, $size);
        $moi = $cur + $soluong;
        $thongbao = 'ÄÃ£ thÃªm "' . $sanpham['tensanpham'] . '" vÃ o giá» hÃ ng!';

        if ($moi > $ton) {
            $moi = $ton;
            $thongbao = 'Kho chá»‰ cÃ²n ' . $ton . ' sáº£n pháº©m "' . $sanpham['tensanpham'] . '", giá» hÃ ng Ä‘Ã£ Ä‘Æ°á»£c cáº­p nháº­t!';
            if ($moi === $cur) {
                return array('ok' => false, 'message' => 'Báº¡n Ä‘Ã£ cÃ³ tá»‘i Ä‘a ' . $ton . ' sáº£n pháº©m nÃ y trong giá»!');
            }
        }

        if ($this->dadangnhap()) {
            $dbcon = DATABASE::connect();
            try {
                if ($moi === $cur + $soluong) {
                    // TrÆ°á»ng há»£p bÃ¬nh thÆ°á»ng: cá»™ng dá»“n sá»‘ lÆ°á»£ng theo Ä‘Ãºng rÃ ng buá»™c uq_giohang_item
                    $sql = "INSERT INTO giohang (nguoidung_id, sanpham_id, size, soluong)
                            VALUES (:nguoidung_id, :sanpham_id, :size, :soluong)
                            ON DUPLICATE KEY UPDATE soluong = soluong + VALUES(soluong)";
                } else {
                    // VÆ°á»£t tá»“n kho: ghi Ä‘Ã¨ báº±ng sá»‘ lÆ°á»£ng tá»‘i Ä‘a cÃ²n láº¡i
                    $sql = "INSERT INTO giohang (nguoidung_id, sanpham_id, size, soluong)
                            VALUES (:nguoidung_id, :sanpham_id, :size, :soluong)
                            ON DUPLICATE KEY UPDATE soluong = VALUES(soluong)";
                }
                $cmd = $dbcon->prepare($sql);
                $cmd->bindValue(":nguoidung_id", $this->nguoidung_id, PDO::PARAM_INT);
                $cmd->bindValue(":sanpham_id", (int) $sanpham_id, PDO::PARAM_INT);
                $cmd->bindValue(":size", $size);
                $cmd->bindValue(":soluong", $moi === $cur + $soluong ? $soluong : $moi, PDO::PARAM_INT);
                $cmd->execute();
                return array('ok' => true, 'message' => $thongbao, 'soluong' => $moi);
            } catch (PDOException $e) {
                echo "<p>Lá»—i truy váº¥n: " . $e->getMessage() . "</p>";
                exit();
            }
        }

        $khoa = $this->khoadong($sanpham_id, $size);
        $_SESSION['cart'][$khoa] = array(
            'sanpham_id' => (int) $sanpham_id,
            'size'       => $size,
            'soluong'    => $moi
        );
        return array('ok' => true, 'message' => $thongbao, 'soluong' => $moi);
    }

    /* ThÃªm nhiá»u sáº£n pháº©m cÃ¹ng lÃºc (dÃ¹ng cho "ThÃªm cáº£ Combo vÃ o giá»")
       $items = array(array('sanpham_id' => 1, 'size' => 'L', 'soluong' => 1), ...) */
    public function themnhieu($items)
    {
        $ketqua = array('ok' => true, 'message' => '', 'soluong' => 0, 'danhsach' => array());
        foreach ($items as $item) {
            $kq = $this->them(
                isset($item['sanpham_id']) ? $item['sanpham_id'] : 0,
                isset($item['size']) ? $item['size'] : '',
                isset($item['soluong']) ? $item['soluong'] : 1
            );
            $ketqua['danhsach'][] = $kq;
            if (!$kq['ok']) {
                $ketqua['ok'] = false;
                $ketqua['message'] = $kq['message'];
            }
            $ketqua['soluong']++;
        }
        if ($ketqua['ok']) {
            $ketqua['message'] = 'ÄÃ£ thÃªm cáº£ combo (' . $ketqua['soluong'] . ' sáº£n pháº©m) vÃ o giá» hÃ ng!';
        }
        return $ketqua;
    }

    /* ------------------------------------------------------------------
       Cáº­p nháº­t / tÄƒng / giáº£m sá»‘ lÆ°á»£ng 1 dÃ²ng trong giá»
       ------------------------------------------------------------------ */
    public function capnhat($sanpham_id, $size, $soluong)
    {
        $sanpham = (new SANPHAM())->laysanphamchitiet($sanpham_id);
        if (!$sanpham) {
            return array('ok' => false, 'message' => 'Sáº£n pháº©m khÃ´ng tá»“n táº¡i!');
        }

        $soluong = max(1, (int) $soluong);
        $ton = (int) $sanpham['soluongton'];
        $thongbao = 'ÄÃ£ cáº­p nháº­t sá»‘ lÆ°á»£ng!';
        if ($soluong > $ton) {
            $soluong = max(1, $ton);
            $thongbao = 'Kho chá»‰ cÃ²n ' . $ton . ' sáº£n pháº©m, sá»‘ lÆ°á»£ng Ä‘Ã£ Ä‘Æ°á»£c Ä‘iá»u chá»‰nh!';
        }

        if ($this->dadangnhap()) {
            $dbcon = DATABASE::connect();
            try {
                $sql = "UPDATE giohang SET soluong = :soluong
                        WHERE nguoidung_id = :nguoidung_id AND sanpham_id = :sanpham_id AND size = :size";
                $cmd = $dbcon->prepare($sql);
                $cmd->bindValue(":soluong", $soluong, PDO::PARAM_INT);
                $cmd->bindValue(":nguoidung_id", $this->nguoidung_id, PDO::PARAM_INT);
                $cmd->bindValue(":sanpham_id", (int) $sanpham_id, PDO::PARAM_INT);
                $cmd->bindValue(":size", (string) $size);
                $cmd->execute();
            } catch (PDOException $e) {
                echo "<p>Lá»—i truy váº¥n: " . $e->getMessage() . "</p>";
                exit();
            }
            return array('ok' => true, 'message' => $thongbao, 'soluong' => $soluong);
        }

        $khoa = $this->khoadong($sanpham_id, $size);
        if (isset($_SESSION['cart'][$khoa])) {
            $_SESSION['cart'][$khoa]['soluong'] = $soluong;
        }
        return array('ok' => true, 'message' => $thongbao, 'soluong' => $soluong);
    }

    public function tang($sanpham_id, $size)
    {
        $cur = $this->laysoluongtronggio($sanpham_id, $size);
        return $this->capnhat($sanpham_id, $size, $cur + 1);
    }

    public function giam($sanpham_id, $size)
    {
        $cur = $this->laysoluongtronggio($sanpham_id, $size);
        return $this->capnhat($sanpham_id, $size, $cur - 1);
    }

    // XÃ³a 1 dÃ²ng trong giá»
    public function xoa($sanpham_id, $size = '')
    {
        if ($this->dadangnhap()) {
            $dbcon = DATABASE::connect();
            try {
                $sql = "DELETE FROM giohang
                        WHERE nguoidung_id = :nguoidung_id AND sanpham_id = :sanpham_id AND size = :size";
                $cmd = $dbcon->prepare($sql);
                $cmd->bindValue(":nguoidung_id", $this->nguoidung_id, PDO::PARAM_INT);
                $cmd->bindValue(":sanpham_id", (int) $sanpham_id, PDO::PARAM_INT);
                $cmd->bindValue(":size", (string) $size);
                return $cmd->execute();
            } catch (PDOException $e) {
                echo "<p>Lá»—i truy váº¥n: " . $e->getMessage() . "</p>";
                exit();
            }
        }

        $khoa = $this->khoadong($sanpham_id, $size);
        unset($_SESSION['cart'][$khoa]);
        return true;
    }

    // XÃ³a toÃ n bá»™ giá» hÃ ng
    public function xoatatca()
    {
        if ($this->dadangnhap()) {
            $dbcon = DATABASE::connect();
            try {
                $sql = "DELETE FROM giohang WHERE nguoidung_id = :nguoidung_id";
                $cmd = $dbcon->prepare($sql);
                $cmd->bindValue(":nguoidung_id", $this->nguoidung_id, PDO::PARAM_INT);
                return $cmd->execute();
            } catch (PDOException $e) {
                echo "<p>Lá»—i truy váº¥n: " . $e->getMessage() . "</p>";
                exit();
            }
        }

        $_SESSION['cart'] = array();
        return true;
    }

    // Tá»•ng sá»‘ lÆ°á»£ng sáº£n pháº©m trong giá» (hiá»ƒn thá»‹ trÃªn navbar)
    public function dem()
    {
        if ($this->dadangnhap()) {
            $dbcon = DATABASE::connect();
            try {
                $sql = "SELECT IFNULL(SUM(soluong), 0) AS tong FROM giohang WHERE nguoidung_id = :nguoidung_id";
                $cmd = $dbcon->prepare($sql);
                $cmd->bindValue(":nguoidung_id", $this->nguoidung_id, PDO::PARAM_INT);
                $cmd->execute();
                $row = $cmd->fetch(PDO::FETCH_ASSOC);
                return $row ? (int) $row['tong'] : 0;
            } catch (PDOException $e) {
                echo "<p>Lá»—i truy váº¥n: " . $e->getMessage() . "</p>";
                exit();
            }
        }

        $tong = 0;
        foreach ($_SESSION['cart'] as $item) {
            $tong += (int) $item['soluong'];
        }
        return $tong;
    }

    // Tá»•ng táº¡m tÃ­nh = tá»•ng (giaban * soluong)
    public function tamtinh()
    {
        $tong = 0;
        foreach ($this->laygiohang() as $item) {
            $tong += (float) $item['giaban'] * (int) $item['soluong'];
        }
        return $tong;
    }

    // Danh sÃ¡ch sáº£n pháº©m_id Ä‘ang cÃ³ trong giá» (dÃ¹ng Ä‘á»ƒ lá»c gá»£i Ã½ mua kÃ¨m)
    public function laysanphamidtronggio()
    {
        $mang = array();
        foreach ($this->laygiohang() as $item) {
            $mang[] = (int) $item['sanpham_id'];
        }
        return $mang;
    }

    /* ------------------------------------------------------------------
       Äá»“ng bá»™ giá» hÃ ng khÃ¡ch vÃ£ng lai (session) vÃ o báº£ng giohang
       Gá»i ngay sau khi khÃ¡ch Ä‘Äƒng nháº­p
       ------------------------------------------------------------------ */
    public function dongbo()
    {
        if (!$this->dadangnhap() || count($_SESSION['cart']) === 0) {
            return false;
        }
        foreach ($_SESSION['cart'] as $item) {
            $this->them($item['sanpham_id'], $item['size'], $item['soluong']);
        }
        $_SESSION['cart'] = array();
        return true;
    }
}
?>
