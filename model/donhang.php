<?php

class DONHANG
{
    private $id;
    private $nguoidung_id;
    private $tennguoinhan;
    private $sdtnhan;
    private $diachinhan;
    private $ngay;
    private $trangthai;
    private $phuongthuc_tt;
    private $datt;
    private $voucher_id;
    private $tamtinh;
    private $giamgia;
    private $phivanchuyen;
    private $tongtien;
    private $doitacgiaohang;
    private $madonhang;
    private $ghichu;

    public function getID() { return $this->id; }
    public function setID($value) { $this->id = $value; }

    public function getNguoidung_id() { return $this->nguoidung_id; }
    public function setNguoidung_id($value) { $this->nguoidung_id = $value; }

    public function getTennguoinhan() { return $this->tennguoinhan; }
    public function setTennguoinhan($value) { $this->tennguoinhan = $value; }

    public function getSdtnhan() { return $this->sdtnhan; }
    public function setSdtnhan($value) { $this->sdtnhan = $value; }

    public function getDiachinhan() { return $this->diachinhan; }
    public function setDiachinhan($value) { $this->diachinhan = $value; }

    public function getNgay() { return $this->ngay; }
    public function setNgay($value) { $this->ngay = $value; }

    public function getTrangthai() { return $this->trangthai; }
    public function setTrangthai($value) { $this->trangthai = $value; }

    public function getPhuongthuc_tt() { return $this->phuongthuc_tt; }
    public function setPhuongthuc_tt($value) { $this->phuongthuc_tt = $value; }

    public function getDatt() { return $this->datt; }
    public function setDatt($value) { $this->datt = $value; }

    public function getVoucher_id() { return $this->voucher_id; }
    public function setVoucher_id($value) { $this->voucher_id = $value; }

    public function getTamtinh() { return $this->tamtinh; }
    public function setTamtinh($value) { $this->tamtinh = $value; }

    public function getGiamgia() { return $this->giamgia; }
    public function setGiamgia($value) { $this->giamgia = $value; }

    public function getPhivanchuyen() { return $this->phivanchuyen; }
    public function setPhivanchuyen($value) { $this->phivanchuyen = $value; }

    public function getTongtien() { return $this->tongtien; }
    public function setTongtien($value) { $this->tongtien = $value; }

    public function getDoitacgiaohang() { return $this->doitacgiaohang; }
    public function setDoitacgiaohang($value) { $this->doitacgiaohang = $value; }

    public function getMadonhang() { return $this->madonhang; }
    public function setMadonhang($value) { $this->madonhang = $value; }

    public function getGhichu() { return $this->ghichu; }
    public function setGhichu($value) { $this->ghichu = $value; }

    public function laytentrangthai($trangthai)
    {
        $mang = array(
            1 => 'Mới',
            2 => 'Xác nhận',
            3 => 'Đóng gói',
            4 => 'Đang giao',
            5 => 'Hoàn thành',
            6 => 'Hủy'
        );
        return isset($mang[(int) $trangthai]) ? $mang[(int) $trangthai] : 'Không xác định';
    }

    public function laymautrangthai($trangthai)
    {
        $mang = array(
            1 => 'bg-secondary',
            2 => 'bg-info text-dark',
            3 => 'bg-primary',
            4 => 'bg-warning text-dark',
            5 => 'bg-success',
            6 => 'bg-danger'
        );
        return isset($mang[(int) $trangthai]) ? $mang[(int) $trangthai] : 'bg-secondary';
    }

    public function laytenphuongthuc($phuongthuc)
    {
        $mang = array(
            'cod' => 'Thanh toán khi nhận hàng (COD)',
            'chuyenkhoan' => 'Chuyển khoản ngân hàng',
            'vnpay' => 'Thanh toán VNPay'
        );
        return isset($mang[$phuongthuc]) ? $mang[$phuongthuc] : $phuongthuc;
    }

   
    public function taidonhang($nguoidung_id, $tennguoinhan, $sdtnhan, $diachinhan, $phuongthuc_tt, $ghichu, $items, $voucher = null, $phivanchuyen = 0, $datt = 0)
    {
        if (count($items) === 0) {
            return array('ok' => false, 'message' => 'Giỏ hàng của bạn đang trống!');
        }

        $tamtinh = 0;
        foreach ($items as $item) {
            $tamtinh += (float) $item['dongia'] * (int) $item['soluong'];
        }
        $giamgia = $voucher ? (float) $voucher['giamgia'] : 0;
        if ($giamgia > $tamtinh) {
            $giamgia = $tamtinh;
        }
        $phivanchuyen = (float) $phivanchuyen;
        $tongtien = $tamtinh - $giamgia + $phivanchuyen;
        $madonhang = 'GYM' . date('YmdHis') . rand(10, 99);

        $dbcon = DATABASE::connect();
        $sp = new SANPHAM();
        $vc = new VOUCHER();

        try {
            $dbcon->beginTransaction();

            $sql = "INSERT INTO donhang (nguoidung_id, tennguoinhan, sdtnhan, diachinhan, trangthai,
                                         phuongthuc_tt, datt, voucher_id, tamtinh, giamgia, phivanchuyen,
                                         tongtien, madonhang, ghichu)
                    VALUES (:nguoidung_id, :tennguoinhan, :sdtnhan, :diachinhan, 1,
                            :phuongthuc_tt, :datt, :voucher_id, :tamtinh, :giamgia, :phivanchuyen,
                            :tongtien, :madonhang, :ghichu)";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            $cmd->bindValue(":tennguoinhan", $tennguoinhan);
            $cmd->bindValue(":sdtnhan", $sdtnhan);
            $cmd->bindValue(":diachinhan", $diachinhan);
            $cmd->bindValue(":phuongthuc_tt", $phuongthuc_tt);
            $cmd->bindValue(":datt", (int) $datt, PDO::PARAM_INT);
            if ($voucher && isset($voucher['id']) && (int) $voucher['id'] > 0) {
                $cmd->bindValue(":voucher_id", (int) $voucher['id'], PDO::PARAM_INT);
            } else {
                $cmd->bindValue(":voucher_id", null, PDO::PARAM_NULL);
            }
            $cmd->bindValue(":tamtinh", $tamtinh);
            $cmd->bindValue(":giamgia", $giamgia);
            $cmd->bindValue(":phivanchuyen", $phivanchuyen);
            $cmd->bindValue(":tongtien", $tongtien);
            $cmd->bindValue(":madonhang", $madonhang);
            $cmd->bindValue(":ghichu", $ghichu);
            $cmd->execute();
            $donhang_id = (int) $dbcon->lastInsertId();

            foreach ($items as $item) {
                $thanhtien = (float) $item['dongia'] * (int) $item['soluong'];

                $sqlct = "INSERT INTO donhangct (donhang_id, sanpham_id, size, dongia, soluong, thanhtien)
                          VALUES (:donhang_id, :sanpham_id, :size, :dongia, :soluong, :thanhtien)";
                $cmdct = $dbcon->prepare($sqlct);
                $cmdct->bindValue(":donhang_id", $donhang_id, PDO::PARAM_INT);
                $cmdct->bindValue(":sanpham_id", (int) $item['sanpham_id'], PDO::PARAM_INT);
                $cmdct->bindValue(":size", (string) $item['size']);
                $cmdct->bindValue(":dongia", (float) $item['dongia']);
                $cmdct->bindValue(":soluong", (int) $item['soluong'], PDO::PARAM_INT);
                $cmdct->bindValue(":thanhtien", $thanhtien);
                $cmdct->execute();

                if (!$sp->trusoluongvatangluotmua($item['sanpham_id'], $item['soluong'])) {
                    $ten = isset($item['tensanpham']) ? $item['tensanpham'] : ('#' . $item['sanpham_id']);
                    throw new Exception('Sản phẩm "' . $ten . '" không đủ số lượng trong kho, vui lòng kiểm tra lại giỏ hàng!');
                }
            }

            if ($voucher && isset($voucher['id']) && (int) $voucher['id'] > 0) {
                if (!$vc->tangdasudung((int) $voucher['id'])) {
                    throw new Exception('Mã giảm giá đã hết lượt sử dụng!');
                }
            }

            $sqlxoa = "DELETE FROM giohang WHERE nguoidung_id = :nguoidung_id";
            $cmdxoa = $dbcon->prepare($sqlxoa);
            $cmdxoa->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            $cmdxoa->execute();

            $dbcon->commit();

            return array(
                'ok' => true,
                'message' => 'Đặt hàng thành công!',
                'madonhang' => $madonhang,
                'donhang_id' => $donhang_id,
                'tamtinh' => $tamtinh,
                'giamgia' => $giamgia,
                'phivanchuyen' => $phivanchuyen,
                'tongtien' => $tongtien
            );
        } catch (Exception $e) {
            if ($dbcon->inTransaction()) {
                $dbcon->rollBack();
            }
            return array('ok' => false, 'message' => 'Lỗi đặt hàng: ' . $e->getMessage());
        }
    }

    public function laydonhangtheoid($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT dh.*, v.ma AS ma_voucher
                    FROM donhang dh
                    LEFT JOIN voucher v ON v.id = dh.voucher_id
                    WHERE dh.id = :id
                    LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int) $id, PDO::PARAM_INT);
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            return $row ? $row : null;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laydonhangtheoma($madonhang)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM donhang WHERE madonhang = :madonhang LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":madonhang", $madonhang);
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            return $row ? $row : null;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laychitietdonhang($donhang_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT ct.*, s.tensanpham, s.hinhanh
                    FROM donhangct ct
                    JOIN sanpham s ON s.id = ct.sanpham_id
                    WHERE ct.donhang_id = :donhang_id
                    ORDER BY ct.id ASC";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":donhang_id", (int) $donhang_id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laydanhsachdonhang($nguoidung_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT dh.*,
                           (SELECT COUNT(*) FROM donhangct ct WHERE ct.donhang_id = dh.id) AS sosanpham
                    FROM donhang dh
                    WHERE dh.nguoidung_id = :nguoidung_id
                    ORDER BY dh.id DESC";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function capnhatdatt($donhang_id, $datt = 1)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE donhang SET datt = :datt WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":datt", (int) $datt, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int) $donhang_id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function capnhattrangthai($donhang_id, $trangthai)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE donhang SET trangthai = :trangthai WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":trangthai", (int) $trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int) $donhang_id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }
}
?>
