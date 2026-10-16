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

    // Lấy tất cả danh mục
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

    // Lấy danh mục theo ID
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

    // Thêm danh mục
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

    // Cập nhật danh mục
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

    // Xóa danh mục
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
}
?>
