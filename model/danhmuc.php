<?php
class DANHMUC
{
    private $id;
    private $tendanhmuc;
    private $loai;

    public function getID() { return $this->id; }
    public function setID($value) { $this->id = $value; }

    public function getTendanhmuc() { return $this->tendanhmuc; }
    public function setTendanhmuc($value) { $this->tendanhmuc = $value; }

    public function getLoai() { return $this->loai; }
    public function setLoai($value) { $this->loai = $value; }

    public function laydanhmuc()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM danhmuc ORDER BY id DESC";
            $cmd = $dbcon->prepare($sql);
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

    public function themdanhmuc($tendanhmuc, $loai)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "INSERT INTO danhmuc (tendanhmuc, loai) VALUES (:tendanhmuc, :loai)";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":tendanhmuc", $tendanhmuc);
            $cmd->bindValue(":loai", $loai);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function suadanhmuc($id, $tendanhmuc, $loai)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE danhmuc SET tendanhmuc = :tendanhmuc, loai = :loai WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":tendanhmuc", $tendanhmuc);
            $cmd->bindValue(":loai", $loai);
            $cmd->bindValue(":id", $id, PDO::PARAM_INT);
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
            $cmd->bindValue(":id", $id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laydanhmuctheoloai($loai)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM danhmuc WHERE loai = :loai ORDER BY tendanhmuc ASC";
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
            $sql = "SELECT COUNT(*) AS tong FROM sanpham WHERE danhmuc_id = :danhmuc_id AND trangthai = 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":danhmuc_id", (int) $danhmuc_id, PDO::PARAM_INT);
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            return $row ? (int) $row['tong'] : 0;
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
            return $row ? (int) $row['tong'] : 0;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }
}
?>
