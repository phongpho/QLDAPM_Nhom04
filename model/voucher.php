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

    public function getID() { return $this->id; }
    public function setID($value) { $this->id = $value; }

    public function getMa() { return $this->ma; }
    public function setMa($value) { $this->ma = $value; }

    public function getMota() { return $this->mota; }
    public function setMota($value) { $this->mota = $value; }

    public function getLoai() { return $this->loai; }
    public function setLoai($value) { $this->loai = $value; }

    public function getGiatri() { return $this->giatri; }
    public function setGiatri($value) { $this->giatri = $value; }

    public function getDonhang_toithieu() { return $this->donhang_toithieu; }
    public function setDonhang_toithieu($value) { $this->donhang_toithieu = $value; }

    public function getGiamtoida() { return $this->giamtoida; }
    public function setGiamtoida($value) { $this->giamtoida = $value; }

    public function getNgayhethan() { return $this->ngayhethan; }
    public function setNgayhethan($value) { $this->ngayhethan = $value; }

    public function getSoluong() { return $this->soluong; }
    public function setSoluong($value) { $this->soluong = $value; }

    public function getDasudung() { return $this->dasudung; }
    public function setDasudung($value) { $this->dasudung = $value; }

    public function getTrangthai() { return $this->trangthai; }
    public function setTrangthai($value) { $this->trangthai = $value; }

    public function kiemTraVoucher($ma, $tamtinh)
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

    public function layvoucherdanghoatdong($limit = 5)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM voucher
                    WHERE trangthai = 1 AND ngayhethan > NOW() AND dasudung < soluong
                    ORDER BY donhang_toithieu ASC
                    LIMIT " . (int) $limit;
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // ========================================================
    // CÁC PHƯƠNG THỨC DÀNH CHO TRANG QUẢN TRỊ ADMIN (qlvoucher)
    // ========================================================

    // Lấy toàn bộ danh sách voucher trong hệ thống
    public function laydanhsachvoucheradmin()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM voucher ORDER BY id DESC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi laydanhsachvoucheradmin: " . $e->getMessage());
            return [];
        }
    }

    // Kiểm tra trùng lặp mã voucher
    public function kiemtratrungma($ma, $exclude_id = 0)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT COUNT(*) FROM voucher WHERE UPPER(TRIM(ma)) = UPPER(TRIM(:ma))";
            if ($exclude_id > 0) {
                $sql .= " AND id != :id";
            }
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":ma", $ma);
            if ($exclude_id > 0) {
                $cmd->bindValue(":id", (int)$exclude_id, PDO::PARAM_INT);
            }
            $cmd->execute();
            return ((int)$cmd->fetchColumn()) > 0;
        } catch (PDOException $e) {
            error_log("Lỗi kiemtratrungma voucher: " . $e->getMessage());
            return false;
        }
    }

    // Thêm mới voucher
    public function themvoucher($ma, $mota, $loai, $giatri, $donhang_toithieu, $giamtoida, $ngayhethan, $soluong, $trangthai = 1)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "INSERT INTO voucher (ma, mota, loai, giatri, donhang_toithieu, giamtoida, ngayhethan, soluong, dasudung, trangthai)
                    VALUES (:ma, :mota, :loai, :giatri, :donhang_toithieu, :giamtoida, :ngayhethan, :soluong, 0, :trangthai)";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":ma", strtoupper(trim($ma)));
            $cmd->bindValue(":mota", !empty($mota) ? trim($mota) : null);
            $cmd->bindValue(":loai", $loai);
            $cmd->bindValue(":giatri", (float)$giatri);
            $cmd->bindValue(":donhang_toithieu", (float)$donhang_toithieu);
            $cmd->bindValue(":giamtoida", ($loai === 'phantram' && !empty($giamtoida)) ? (float)$giamtoida : null, ($loai === 'phantram' && !empty($giamtoida)) ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $cmd->bindValue(":ngayhethan", $ngayhethan);
            $cmd->bindValue(":soluong", (int)$soluong, PDO::PARAM_INT);
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->execute();
            return $dbcon->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi themvoucher: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật voucher
    public function capnhatvoucher($id, $ma, $mota, $loai, $giatri, $donhang_toithieu, $giamtoida, $ngayhethan, $soluong, $trangthai = 1)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE voucher SET 
                        ma = :ma, 
                        mota = :mota, 
                        loai = :loai, 
                        giatri = :giatri, 
                        donhang_toithieu = :donhang_toithieu, 
                        giamtoida = :giamtoida, 
                        ngayhethan = :ngayhethan, 
                        soluong = :soluong, 
                        trangthai = :trangthai 
                    WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":ma", strtoupper(trim($ma)));
            $cmd->bindValue(":mota", !empty($mota) ? trim($mota) : null);
            $cmd->bindValue(":loai", $loai);
            $cmd->bindValue(":giatri", (float)$giatri);
            $cmd->bindValue(":donhang_toithieu", (float)$donhang_toithieu);
            $cmd->bindValue(":giamtoida", ($loai === 'phantram' && !empty($giamtoida)) ? (float)$giamtoida : null, ($loai === 'phantram' && !empty($giamtoida)) ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $cmd->bindValue(":ngayhethan", $ngayhethan);
            $cmd->bindValue(":soluong", (int)$soluong, PDO::PARAM_INT);
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            error_log("Lỗi capnhatvoucher: " . $e->getMessage());
            return false;
        }
    }

    // Đổi trạng thái kích hoạt
    public function doitrangthai($id, $trangthai)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE voucher SET trangthai = :trangthai WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            error_log("Lỗi doitrangthai voucher: " . $e->getMessage());
            return false;
        }
    }

    // Xóa voucher (nếu chưa từng có lượt dùng nào)
    public function xoavoucher($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $v = $this->layvouchertheoid($id);
            if (!$v) return false;
            if ((int)$v["dasudung"] > 0) {
                // Đã có người dùng, chuyển sang ẩn thay vì xóa để bảo toàn lịch sử đơn hàng
                return $this->doitrangthai($id, 0);
            }
            $sql = "DELETE FROM voucher WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            error_log("Lỗi xoavoucher: " . $e->getMessage());
            return false;
        }
    }

    // Thống kê nhanh voucher
    public function thongkevoucheradmin()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT 
                        COUNT(*) AS tong,
                        SUM(CASE WHEN trangthai = 1 AND ngayhethan > NOW() AND dasudung < soluong THEN 1 ELSE 0 END) AS dangapdung,
                        SUM(CASE WHEN ngayhethan <= NOW() THEN 1 ELSE 0 END) AS hethan,
                        SUM(CASE WHEN dasudung >= soluong THEN 1 ELSE 0 END) AS hetluot,
                        SUM(dasudung) AS tongluotdung
                    FROM voucher";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            $res = $cmd->fetch(PDO::FETCH_ASSOC);
            return [
                'tong' => (int)($res['tong'] ?? 0),
                'dangapdung' => (int)($res['dangapdung'] ?? 0),
                'hethan' => (int)($res['hethan'] ?? 0),
                'hetluot' => (int)($res['hetluot'] ?? 0),
                'tongluotdung' => (int)($res['tongluotdung'] ?? 0)
            ];
        } catch (PDOException $e) {
            error_log("Lỗi thongkevoucheradmin: " . $e->getMessage());
            return ['tong' => 0, 'dangapdung' => 0, 'hethan' => 0, 'hetluot' => 0, 'tongluotdung' => 0];
        }
    }
}
?>