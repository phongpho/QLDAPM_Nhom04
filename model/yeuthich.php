<?php
class YEUTHICH
{
    // lay danh sach san pham yeu thich cua nguoi dung
    public function laydanhsachyeuthich($nguoidung_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT y.ngay AS ngay_thich, s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
                    FROM yeuthich y
                    JOIN sanpham s ON s.id = y.sanpham_id
                    JOIN danhmuc d ON d.id = s.danhmuc_id
                    LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
                    WHERE y.nguoidung_id = :nguoidung_id AND s.trangthai = 1 AND d.trangthai = 1
                    ORDER BY y.ngay DESC";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array();
        }
    }

    // dem so luong san pham yeu thich
    public function demsoluongyeuthich($nguoidung_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT COUNT(*) AS tong
                    FROM yeuthich y
                    JOIN sanpham s ON s.id = y.sanpham_id
                    JOIN danhmuc d ON d.id = s.danhmuc_id
                    WHERE y.nguoidung_id = :nguoidung_id AND s.trangthai = 1 AND d.trangthai = 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            return $row ? (int) $row['tong'] : 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    // kiem tra xem san pham da duoc yeu thich chua
    public function kiemtrayeuthich($nguoidung_id, $sanpham_id)
    {
        if ((int) $nguoidung_id <= 0 || (int) $sanpham_id <= 0) {
            return false;
        }
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT 1 FROM yeuthich WHERE nguoidung_id = :nguoidung_id AND sanpham_id = :sanpham_id LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            $cmd->bindValue(":sanpham_id", (int) $sanpham_id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->fetchColumn() !== false;
        } catch (PDOException $e) {
            return false;
        }
    }

    // lay danh sach id cac san pham da thich
    public function laydanhsachidyeuthich($nguoidung_id)
    {
        if ((int) $nguoidung_id <= 0) {
            return array();
        }
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT sanpham_id FROM yeuthich WHERE nguoidung_id = :nguoidung_id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_COLUMN, 0);
        } catch (PDOException $e) {
            return array();
        }
    }

    // them san pham vao danh sach yeu thich
    public function themyeuthich($nguoidung_id, $sanpham_id)
    {
        if ((int) $nguoidung_id <= 0 || (int) $sanpham_id <= 0) {
            return false;
        }
        if ($this->kiemtrayeuthich($nguoidung_id, $sanpham_id)) {
            return true;
        }
        $dbcon = DATABASE::connect();
        try {
            $sql = "INSERT INTO yeuthich (nguoidung_id, sanpham_id, ngay) VALUES (:nguoidung_id, :sanpham_id, NOW())";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            $cmd->bindValue(":sanpham_id", (int) $sanpham_id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // xoa san pham khoi danh sach yeu thich
    public function xoayeuthich($nguoidung_id, $sanpham_id)
    {
        if ((int) $nguoidung_id <= 0 || (int) $sanpham_id <= 0) {
            return false;
        }
        $dbcon = DATABASE::connect();
        try {
            $sql = "DELETE FROM yeuthich WHERE nguoidung_id = :nguoidung_id AND sanpham_id = :sanpham_id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            $cmd->bindValue(":sanpham_id", (int) $sanpham_id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // thay doi trang thai thich (toggle)
    public function thaydoithich($nguoidung_id, $sanpham_id)
    {
        if ((int) $nguoidung_id <= 0 || (int) $sanpham_id <= 0) {
            return array('ok' => false, 'message' => 'Vui lòng đăng nhập!');
        }
        if ($this->kiemtrayeuthich($nguoidung_id, $sanpham_id)) {
            $this->xoayeuthich($nguoidung_id, $sanpham_id);
            $thich = false;
        } else {
            $this->themyeuthich($nguoidung_id, $sanpham_id);
            $thich = true;
        }
        $tong = $this->demsoluongyeuthich($nguoidung_id);
        return array('ok' => true, 'thich' => $thich, 'tong' => $tong);
    }
}
