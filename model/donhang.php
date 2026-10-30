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

    public function laybadgetrangthai($trangthai)
    {
        switch ((int) $trangthai) {
            case 1:
                return array(
                    'ten' => 'Mới',
                    'class' => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                    'icon' => 'fa-bell'
                );
            case 2:
                return array(
                    'ten' => 'Đã xác nhận',
                    'class' => 'bg-info-subtle text-info border border-info-subtle',
                    'icon' => 'fa-check'
                );
            case 3:
                return array(
                    'ten' => 'Đang đóng gói',
                    'class' => 'bg-primary-subtle text-primary border border-primary-subtle',
                    'icon' => 'fa-box'
                );
            case 4:
                return array(
                    'ten' => 'Đang giao hàng',
                    'class' => 'bg-warning-subtle text-warning border border-warning-subtle',
                    'icon' => 'fa-truck-fast'
                );
            case 5:
                return array(
                    'ten' => 'Hoàn thành',
                    'class' => 'bg-success-subtle text-success border border-success-subtle',
                    'icon' => 'fa-circle-check'
                );
            case 6:
                return array(
                    'ten' => 'Đã hủy',
                    'class' => 'bg-danger-subtle text-danger border border-danger-subtle',
                    'icon' => 'fa-circle-xmark'
                );
            default:
                return array(
                    'ten' => 'Không rõ',
                    'class' => 'bg-light text-muted border',
                    'icon' => 'fa-question'
                );
        }
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

    // --- CÁC PHƯƠNG THỨC QUẢN TRỊ ADMIN (qldonhang) ---

    // Lấy toàn bộ danh sách đơn hàng cho Admin kèm số lượng mặt hàng và thông tin khách
    public function laydanhsachdonhangquantri($tukhoa = '', $trangthai = '', $phuongthuc = '', $datt = '', $sapxep = 'moinhat')
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT dh.*,
                           nd.hoten AS ten_taikhoan,
                           nd.gmail AS email_taikhoan,
                           (SELECT COUNT(*) FROM donhangct ct WHERE ct.donhang_id = dh.id) AS sosanpham,
                           (SELECT SUM(soluong) FROM donhangct ct WHERE ct.donhang_id = dh.id) AS tongsoluong
                    FROM donhang dh
                    LEFT JOIN nguoidung nd ON nd.id = dh.nguoidung_id
                    WHERE 1=1";

            $params = array();

            if (!empty($tukhoa)) {
                $sql .= " AND (dh.madonhang LIKE :kw OR dh.tennguoinhan LIKE :kw OR dh.sdtnhan LIKE :kw OR nd.hoten LIKE :kw OR nd.gmail LIKE :kw)";
                $params[':kw'] = '%' . trim($tukhoa) . '%';
            }

            if ($trangthai !== '' && is_numeric($trangthai)) {
                $sql .= " AND dh.trangthai = :trangthai";
                $params[':trangthai'] = (int) $trangthai;
            }

            if (!empty($phuongthuc)) {
                $sql .= " AND dh.phuongthuc_tt = :phuongthuc";
                $params[':phuongthuc'] = $phuongthuc;
            }

            if ($datt !== '' && is_numeric($datt)) {
                $sql .= " AND dh.datt = :datt";
                $params[':datt'] = (int) $datt;
            }

            switch ($sapxep) {
                case 'cuNhat':
                    $sql .= " ORDER BY dh.id ASC";
                    break;
                case 'giacao':
                    $sql .= " ORDER BY dh.tongtien DESC, dh.id DESC";
                    break;
                case 'giathap':
                    $sql .= " ORDER BY dh.tongtien ASC, dh.id DESC";
                    break;
                case 'moinhat':
                default:
                    $sql .= " ORDER BY dh.id DESC";
                    break;
            }

            $cmd = $dbcon->prepare($sql);
            foreach ($params as $key => $val) {
                $cmd->bindValue($key, $val);
            }
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Thống kê nhanh đơn hàng phục vụ 4 thẻ KPI Metrics
    public function thongkedonhangquantri()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT 
                        COUNT(*) AS tong_don,
                        SUM(CASE WHEN trangthai = 1 THEN 1 ELSE 0 END) AS don_moi,
                        SUM(CASE WHEN trangthai = 2 THEN 1 ELSE 0 END) AS don_xacnhan,
                        SUM(CASE WHEN trangthai IN (3, 4) THEN 1 ELSE 0 END) AS don_vanchuyen,
                        SUM(CASE WHEN trangthai = 5 THEN 1 ELSE 0 END) AS don_hoanthanh,
                        SUM(CASE WHEN trangthai = 6 THEN 1 ELSE 0 END) AS don_huy,
                        SUM(CASE WHEN trangthai = 5 THEN tongtien ELSE 0 END) AS doanhthu_hoanthanh,
                        SUM(CASE WHEN trangthai != 6 THEN tongtien ELSE 0 END) AS tong_doanhso
                    FROM donhang";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);

            return array(
                'tong_don' => (int) ($row['tong_don'] ?? 0),
                'don_moi' => (int) ($row['don_moi'] ?? 0),
                'don_xacnhan' => (int) ($row['don_xacnhan'] ?? 0),
                'don_vanchuyen' => (int) ($row['don_vanchuyen'] ?? 0),
                'don_hoanthanh' => (int) ($row['don_hoanthanh'] ?? 0),
                'don_huy' => (int) ($row['don_huy'] ?? 0),
                'doanhthu_hoanthanh' => (float) ($row['doanhthu_hoanthanh'] ?? 0),
                'tong_doanhso' => (float) ($row['tong_doanhso'] ?? 0)
            );
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Cập nhật thông tin vận chuyển & ghi chú nội bộ
    public function capnhatvanchuyen($donhang_id, $doitacgiaohang, $ghichu)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE donhang SET doitacgiaohang = :doitac, ghichu = :ghichu WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":doitac", trim($doitacgiaohang));
            $cmd->bindValue(":ghichu", trim($ghichu));
            $cmd->bindValue(":id", (int) $donhang_id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Xóa đơn hàng (Chỉ cho phép Admin xóa khi đơn đã Hủy - trangthai = 6)
    public function xoadonhang($donhang_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sqlKiemtra = "SELECT trangthai FROM donhang WHERE id = :id";
            $cmdKiemtra = $dbcon->prepare($sqlKiemtra);
            $cmdKiemtra->bindValue(":id", (int) $donhang_id, PDO::PARAM_INT);
            $cmdKiemtra->execute();
            $dh = $cmdKiemtra->fetch(PDO::FETCH_ASSOC);

            if (!$dh) {
                return array('ok' => false, 'message' => 'Đơn hàng không tồn tại!');
            }

            if ((int) $dh['trangthai'] !== 6) {
                return array('ok' => false, 'message' => 'Chỉ được phép xóa đơn hàng đã ở trạng thái ĐÃ HỦY để bảo toàn số liệu kế toán!');
            }

            $dbcon->beginTransaction();

            $sqlCt = "DELETE FROM donhangct WHERE donhang_id = :id";
            $cmdCt = $dbcon->prepare($sqlCt);
            $cmdCt->bindValue(":id", (int) $donhang_id, PDO::PARAM_INT);
            $cmdCt->execute();

            $sqlDh = "DELETE FROM donhang WHERE id = :id";
            $cmdDh = $dbcon->prepare($sqlDh);
            $cmdDh->bindValue(":id", (int) $donhang_id, PDO::PARAM_INT);
            $cmdDh->execute();

            $dbcon->commit();
            return array('ok' => true, 'message' => 'Đã xóa đơn hàng thành công!');
        } catch (Exception $e) {
            if ($dbcon->inTransaction()) {
                $dbcon->rollBack();
            }
            return array('ok' => false, 'message' => 'Lỗi khi xóa đơn hàng: ' . $e->getMessage());
        }
    }
}
