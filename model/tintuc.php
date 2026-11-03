<?php
class TINTUC
{
    private $id;
    private $tieude;
    private $noidung;
    private $hinhanh;
    private $loai;
    private $trangthai;
    private $ngaydang;

    public function getID() { return $this->id; }
    public function setID($value) { $this->id = $value; }

    public function getTieude() { return $this->tieude; }
    public function setTieude($value) { $this->tieude = $value; }

    public function getNoidung() { return $this->noidung; }
    public function setNoidung($value) { $this->noidung = $value; }

    public function getHinhanh() { return $this->hinhanh; }
    public function setHinhanh($value) { $this->hinhanh = $value; }

    public function getLoai() { return $this->loai; }
    public function setLoai($value) { $this->loai = $value; }

    public function getTrangthai() { return $this->trangthai; }
    public function setTrangthai($value) { $this->trangthai = $value; }

    public function getNgaydang() { return $this->ngaydang; }
    public function setNgaydang($value) { $this->ngaydang = $value; }

    // Lấy toàn bộ danh sách bài viết cho trang Admin
    public function laydanhsachtintucadmin()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM tintuc ORDER BY ngaydang DESC, id DESC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi laydanhsachtintucadmin: " . $e->getMessage());
            return [];
        }
    }

    // Lấy chi tiết bài viết
    public function laytintuctheoid($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM tintuc WHERE id = :id LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmd->execute();
            $res = $cmd->fetch(PDO::FETCH_ASSOC);
            return $res ? $res : null;
        } catch (PDOException $e) {
            error_log("Lỗi laytintuctheoid: " . $e->getMessage());
            return null;
        }
    }

    // Thêm bài viết mới
    public function themtintuc($tieude, $noidung, $hinhanh, $loai = 'tintuc', $trangthai = 1)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "INSERT INTO tintuc (tieude, noidung, hinhanh, loai, trangthai, ngaydang) 
                    VALUES (:tieude, :noidung, :hinhanh, :loai, :trangthai, NOW())";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":tieude", trim($tieude));
            $cmd->bindValue(":noidung", $noidung);
            $cmd->bindValue(":hinhanh", !empty($hinhanh) ? $hinhanh : null);
            $cmd->bindValue(":loai", in_array($loai, ['tintuc', 'sukien', 'khuyenmai']) ? $loai : 'tintuc');
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->execute();
            return $dbcon->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi themtintuc: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật bài viết
    public function capnhattintuc($id, $tieude, $noidung, $hinhanh = null, $loai = 'tintuc', $trangthai = 1)
    {
        $dbcon = DATABASE::connect();
        try {
            if ($hinhanh !== null && $hinhanh !== '') {
                $sql = "UPDATE tintuc SET tieude = :tieude, noidung = :noidung, hinhanh = :hinhanh, loai = :loai, trangthai = :trangthai WHERE id = :id";
            } else {
                $sql = "UPDATE tintuc SET tieude = :tieude, noidung = :noidung, loai = :loai, trangthai = :trangthai WHERE id = :id";
            }
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":tieude", trim($tieude));
            $cmd->bindValue(":noidung", $noidung);
            if ($hinhanh !== null && $hinhanh !== '') {
                $cmd->bindValue(":hinhanh", $hinhanh);
            }
            $cmd->bindValue(":loai", in_array($loai, ['tintuc', 'sukien', 'khuyenmai']) ? $loai : 'tintuc');
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            error_log("Lỗi capnhattintuc: " . $e->getMessage());
            return false;
        }
    }

    // Đổi trạng thái hiển thị
    public function doitrangthai($id, $trangthai)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE tintuc SET trangthai = :trangthai WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            error_log("Lỗi doitrangthai tintuc: " . $e->getMessage());
            return false;
        }
    }

    // Xóa bài viết
    public function xoatintuc($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "DELETE FROM tintuc WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            error_log("Lỗi xoatintuc: " . $e->getMessage());
            return false;
        }
    }

    // Thống kê nhanh tin tức
    public function thongketintuc()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT 
                        COUNT(*) AS tong,
                        SUM(CASE WHEN loai = 'tintuc' THEN 1 ELSE 0 END) AS dem_tintuc,
                        SUM(CASE WHEN loai = 'sukien' THEN 1 ELSE 0 END) AS dem_sukien,
                        SUM(CASE WHEN loai = 'khuyenmai' THEN 1 ELSE 0 END) AS dem_khuyenmai,
                        SUM(CASE WHEN trangthai = 1 THEN 1 ELSE 0 END) AS hienthi
                    FROM tintuc";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            $res = $cmd->fetch(PDO::FETCH_ASSOC);
            return [
                'tong' => (int)($res['tong'] ?? 0),
                'dem_tintuc' => (int)($res['dem_tintuc'] ?? 0),
                'dem_sukien' => (int)($res['dem_sukien'] ?? 0),
                'dem_khuyenmai' => (int)($res['dem_khuyenmai'] ?? 0),
                'hienthi' => (int)($res['hienthi'] ?? 0)
            ];
        } catch (PDOException $e) {
            error_log("Lỗi thongketintuc: " . $e->getMessage());
            return ['tong' => 0, 'dem_tintuc' => 0, 'dem_sukien' => 0, 'dem_khuyenmai' => 0, 'hienthi' => 0];
        }
    }
}
?>

