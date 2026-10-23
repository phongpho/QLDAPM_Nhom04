<?php
class DANHMUC
{
    private $id;
    private $tendanhmuc;
    private $loai;
    private $trangthai;

    public function getID() { return $this->id; }
    public function setID($value) { $this->id = $value; }

    public function getTendanhmuc() { return $this->tendanhmuc; }
    public function setTendanhmuc($value) { $this->tendanhmuc = $value; }

    public function getLoai() { return $this->loai; }
    public function setLoai($value) { $this->loai = $value; }

    public function getTrangthai() { return $this->trangthai; }
    public function setTrangthai($value) { $this->trangthai = $value; }

    // Lấy toàn bộ danh mục phục vụ trang quản trị Admin (kèm số lượng sản phẩm liên kết)
    public function laydanhmuc()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT d.*, COUNT(s.id) AS soluongsanpham
                    FROM danhmuc d
                    LEFT JOIN sanpham s ON d.id = s.danhmuc_id
                    GROUP BY d.id, d.tendanhmuc, d.loai, d.trangthai
                    ORDER BY d.id DESC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Lấy danh mục đang hoạt động (trangthai = 1) dành cho giao diện khách hàng / menu
    public function laydanhmuchoatdong($loai = '')
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM danhmuc WHERE trangthai = 1";
            if (!empty($loai)) {
                $sql .= " AND loai = :loai";
            }
            $sql .= " ORDER BY tendanhmuc ASC";
            $cmd = $dbcon->prepare($sql);
            if (!empty($loai)) {
                $cmd->bindValue(":loai", $loai);
            }
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laydanhmuctheoid($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM danhmuc WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", $id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Kiểm tra trùng tên danh mục khi thêm hoặc cập nhật
    public function kiemtratrungten($tendanhmuc, $id_exclude = 0)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT COUNT(*) AS dem FROM danhmuc WHERE LOWER(TRIM(tendanhmuc)) = LOWER(TRIM(:tendanhmuc))";
            if ($id_exclude > 0) {
                $sql .= " AND id != :id_exclude";
            }
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":tendanhmuc", $tendanhmuc);
            if ($id_exclude > 0) {
                $cmd->bindValue(":id_exclude", (int)$id_exclude, PDO::PARAM_INT);
            }
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            return ($row && (int)$row['dem'] > 0);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function themdanhmuc($tendanhmuc, $loai, $trangthai = 1)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "INSERT INTO danhmuc (tendanhmuc, loai, trangthai) VALUES (:tendanhmuc, :loai, :trangthai)";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":tendanhmuc", $tendanhmuc);
            $cmd->bindValue(":loai", $loai);
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function suadanhmuc($id, $tendanhmuc, $loai, $trangthai = 1)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE danhmuc SET tendanhmuc = :tendanhmuc, loai = :loai, trangthai = :trangthai WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":tendanhmuc", $tendanhmuc);
            $cmd->bindValue(":loai", $loai);
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Đổi trạng thái Ẩn / Hiện nhanh (Bật/Tắt công tắc)
    public function doitrangthai($id, $trangthai)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE danhmuc SET trangthai = :trangthai WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function xoadanhmuc($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "DELETE FROM danhmuc WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laydanhmuctheoloai($loai, $chi_hoat_dong = true)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM danhmuc WHERE loai = :loai";
            if ($chi_hoat_dong) {
                $sql .= " AND trangthai = 1";
            }
            $sql .= " ORDER BY tendanhmuc ASC";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":loai", $loai);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laysoluongsanphamtheodanhmuc($danhmuc_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT COUNT(*) AS tong FROM sanpham WHERE danhmuc_id = :danhmuc_id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":danhmuc_id", (int)$danhmuc_id, PDO::PARAM_INT);
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            return $row ? (int)$row['tong'] : 0;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laysoluongsanphamtheoloai($loai, $doituong = '')
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT COUNT(*) AS tong
                    FROM sanpham s
                    JOIN danhmuc d ON d.id = s.danhmuc_id
                    WHERE s.trangthai = 1 AND d.loai = :loai";
            if ($doituong === 'nam' || $doituong === 'nu') {
                $sql .= " AND s.doituong IN (:doituong, 'unisex')";
            }
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":loai", $loai);
            if ($doituong === 'nam' || $doituong === 'nu') {
                $cmd->bindValue(":doituong", $doituong);
            }
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            return $row ? (int)$row['tong'] : 0;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }
}
?>
