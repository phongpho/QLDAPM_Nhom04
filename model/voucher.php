<?php
class VOUCHER
{
    private $id;
    private $ma;
    private $mota;
    private $loai;
    private $giatri;
    private $donhang_toithieu;
    private $giamtoida;
    private $ngayhethan;
    private $soluong;
    private $dasudung;
    private $trangthai;

    public function getID()
    {
        return $this->id;
    }
    public function setID($value)
    {
        $this->id = $value;
    }

    public function getMa()
    {
        return $this->ma;
    }
    public function setMa($value)
    {
        $this->ma = $value;
    }

    public function getMota()
    {
        return $this->mota;
    }
    public function setMota($value)
    {
        $this->mota = $value;
    }

    public function getLoai()
    {
        return $this->loai;
    }
    public function setLoai($value)
    {
        $this->loai = $value;
    }

    public function getGiatri()
    {
        return $this->giatri;
    }
    public function setGiatri($value)
    {
        $this->giatri = $value;
    }

    public function getDonhang_toithieu()
    {
        return $this->donhang_toithieu;
    }
    public function setDonhang_toithieu($value)
    {
        $this->donhang_toithieu = $value;
    }

    public function getGiamtoida()
    {
        return $this->giamtoida;
    }
    public function setGiamtoida($value)
    {
        $this->giamtoida = $value;
    }

    public function getNgayhethan()
    {
        return $this->ngayhethan;
    }
    public function setNgayhethan($value)
    {
        $this->ngayhethan = $value;
    }

    public function getSoluong()
    {
        return $this->soluong;
    }
    public function setSoluong($value)
    {
        $this->soluong = $value;
    }

    public function getDasudung()
    {
        return $this->dasudung;
    }
    public function setDasudung($value)
    {
        $this->dasudung = $value;
    }

    public function getTrangthai()
    {
        return $this->trangthai;
    }
    public function setTrangthai($value)
    {
        $this->trangthai = $value;
    }

    public function laVoucherHoiVienMoi($voucher)
    {
        if (!$voucher) {
            return false;
        }
        $ma = isset($voucher['ma']) ? strtoupper(trim($voucher['ma'])) : '';
        $mota = isset($voucher['mota']) ? mb_strtolower($voucher['mota'], 'UTF-8') : '';
        return ($ma === 'TANBINH' || mb_strpos($mota, 'hội viên mới', 0, 'UTF-8') !== false || mb_strpos($mota, 'thành viên mới', 0, 'UTF-8') !== false);
    }

    public function daDungVoucherHoiVienMoi($nguoidungId, $voucherId = 0)
    {
        $nguoidungId = (int) $nguoidungId;
        if ($nguoidungId <= 0) {
            return false;
        }
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT COUNT(*) FROM donhang 
                    WHERE nguoidung_id = :uid 
                      AND trangthai != 6 
                      AND (voucher_id = :vid OR voucher_id IN (SELECT id FROM voucher WHERE ma = 'TANBINH'))";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":uid", $nguoidungId, PDO::PARAM_INT);
            $cmd->bindValue(":vid", (int) $voucherId, PDO::PARAM_INT);
            $cmd->execute();
            if (((int) $cmd->fetchColumn()) > 0) {
                return true;
            }
            $sql2 = "SELECT COUNT(*) FROM donhang WHERE nguoidung_id = :uid AND trangthai != 6";
            $cmd2 = $dbcon->prepare($sql2);
            $cmd2->bindValue(":uid", $nguoidungId, PDO::PARAM_INT);
            $cmd2->execute();
            return ((int) $cmd2->fetchColumn()) > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function kiemTraVoucher($ma, $tamtinh, $nguoidungId = 0)
    {
        $ma = trim((string) $ma);
        if ($ma === '') {
            return array('ok' => false, 'message' => 'Vui lòng nhập mã giảm giá!', 'voucher' => null, 'giamgia' => 0);
        }

        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM voucher
                    WHERE ma = :ma AND trangthai = 1
                      AND ngayhethan > NOW() AND dasudung < soluong
                    LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":ma", $ma);
            $cmd->execute();
            $voucher = $cmd->fetch(PDO::FETCH_ASSOC);

            if (!$voucher) {
                return array(
                    'ok' => false,
                    'message' => 'Mã "' . $ma . '" không tồn tại, đã hết hạn hoặc đã hết lượt sử dụng!',
                    'voucher' => null,
                    'giamgia' => 0
                );
            }

            if ($this->laVoucherHoiVienMoi($voucher)) {
                if ((int) $nguoidungId > 0 && $this->daDungVoucherHoiVienMoi($nguoidungId, $voucher['id'])) {
                    return array(
                        'ok' => false,
                        'message' => 'Mã ' . $voucher['ma'] . ' chỉ áp dụng cho hội viên mới trong lần mua đầu tiên!',
                        'voucher' => null,
                        'giamgia' => 0
                    );
                }
            }

            if ((float) $tamtinh < (float) $voucher['donhang_toithieu']) {
                return array(
                    'ok' => false,
                    'message' => 'Mã ' . $voucher['ma'] . ' chỉ áp dụng cho đơn hàng từ '
                        . number_format((float) $voucher['donhang_toithieu'], 0, ',', '.') . 'đ!',
                    'voucher' => null,
                    'giamgia' => 0
                );
            }

            $giamgia = $this->tinhgiamgia($voucher, $tamtinh);
            return array(
                'ok' => true,
                'message' => 'Đã áp dụng mã ' . $voucher['ma'] . ', giảm ' . number_format($giamgia, 0, ',', '.') . 'đ!',
                'voucher' => $voucher,
                'giamgia' => $giamgia
            );
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function tinhgiamgia($voucher, $tamtinh)
    {
        $tamtinh = (float) $tamtinh;
        if ($voucher['loai'] === 'phantram') {
            $giam = round($tamtinh * ((float) $voucher['giatri'] / 100));
            if ((float) $voucher['giamtoida'] > 0 && $giam > (float) $voucher['giamtoida']) {
                $giam = (float) $voucher['giamtoida'];
            }
        } else {
            $giam = min((float) $voucher['giatri'], $tamtinh);
        }
        return (float) $giam;
    }

    public function layvouchertheoid($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM voucher WHERE id = :id LIMIT 1";
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

    public function tangdasudung($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE voucher SET dasudung = dasudung + 1
                    WHERE id = :id AND dasudung < soluong";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int) $id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->rowCount() > 0;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function layvoucherdanghoatdong($limit = 5, $nguoidungId = 0)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM voucher
                    WHERE trangthai = 1 AND ngayhethan > NOW() AND dasudung < soluong
                    ORDER BY donhang_toithieu ASC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            $danhsach = $cmd->fetchAll(PDO::FETCH_ASSOC);
            $ketqua = array();
            $nguoidungId = (int) $nguoidungId;
            foreach ($danhsach as $v) {
                if ($this->laVoucherHoiVienMoi($v) && $nguoidungId > 0 && $this->daDungVoucherHoiVienMoi($nguoidungId, $v['id'])) {
                    continue;
                }
                $ketqua[] = $v;
                if (count($ketqua) >= (int) $limit) {
                    break;
                }
            }
            return $ketqua;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }
}
?>
