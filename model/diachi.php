<?php

class DIACHI
{
    private $id;
    private $nguoidung_id;
    private $tennguoinhan;
    private $sodienthoai;
    private $diachi;
    private $macdinh;

    public function getID() { return $this->id; }
    public function setID($value) { $this->id = $value; }

    public function getNguoidung_id() { return $this->nguoidung_id; }
    public function setNguoidung_id($value) { $this->nguoidung_id = $value; }

    public function getTennguoinhan() { return $this->tennguoinhan; }
    public function setTennguoinhan($value) { $this->tennguoinhan = $value; }

    public function getSodienthoai() { return $this->sodienthoai; }
    public function setSodienthoai($value) { $this->sodienthoai = $value; }

    public function getDiachi() { return $this->diachi; }
    public function setDiachi($value) { $this->diachi = $value; }

    public function getMacdinh() { return $this->macdinh; }
    public function setMacdinh($value) { $this->macdinh = $value; }

    public function laydanhsachdiachi($nguoidung_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM diachi
                    WHERE nguoidung_id = :nguoidung_id
                    ORDER BY macdinh DESC, id DESC";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laydiachimacdinh($nguoidung_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM diachi
                    WHERE nguoidung_id = :nguoidung_id
                    ORDER BY macdinh DESC, id DESC
                    LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            return $row ? $row : null;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laydiachitheoid($id, $nguoidung_id = 0)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM diachi WHERE id = :id";
            if ($nguoidung_id > 0) {
                $sql .= " AND nguoidung_id = :nguoidung_id";
            }
            $sql .= " LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int) $id, PDO::PARAM_INT);
            if ($nguoidung_id > 0) {
                $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            }
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            return $row ? $row : null;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function themdiachi($nguoidung_id, $tennguoinhan, $sodienthoai, $diachi, $macdinh = 0)
    {
        $dbcon = DATABASE::connect();
        try {
            $macdinh = (int) $macdinh === 1 ? 1 : 0;
            if ($macdinh === 1) {
                $this->bodiacchimacdinh($nguoidung_id);
            }
            $sql = "INSERT INTO diachi (nguoidung_id, tennguoinhan, sodienthoai, diachi, macdinh)
                    VALUES (:nguoidung_id, :tennguoinhan, :sodienthoai, :diachi, :macdinh)";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            $cmd->bindValue(":tennguoinhan", $tennguoinhan);
            $cmd->bindValue(":sodienthoai", $sodienthoai);
            $cmd->bindValue(":diachi", $diachi);
            $cmd->bindValue(":macdinh", $macdinh, PDO::PARAM_INT);
            $cmd->execute();
            return (int) $dbcon->lastInsertId();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function suadiachi($id, $nguoidung_id, $tennguoinhan, $sodienthoai, $diachi, $macdinh = 0)
    {
        $dbcon = DATABASE::connect();
        try {
            $macdinh = (int) $macdinh === 1 ? 1 : 0;
            if ($macdinh === 1) {
                $this->bodiacchimacdinh($nguoidung_id);
            }
            $sql = "UPDATE diachi
                    SET tennguoinhan = :tennguoinhan,
                        sodienthoai = :sodienthoai,
                        diachi = :diachi,
                        macdinh = :macdinh
                    WHERE id = :id AND nguoidung_id = :nguoidung_id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":tennguoinhan", $tennguoinhan);
            $cmd->bindValue(":sodienthoai", $sodienthoai);
            $cmd->bindValue(":diachi", $diachi);
            $cmd->bindValue(":macdinh", $macdinh, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int) $id, PDO::PARAM_INT);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function xoadiachi($id, $nguoidung_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "DELETE FROM diachi WHERE id = :id AND nguoidung_id = :nguoidung_id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int) $id, PDO::PARAM_INT);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function datmacdinh($id, $nguoidung_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $this->bodiacchimacdinh($nguoidung_id);
            $sql = "UPDATE diachi SET macdinh = 1 WHERE id = :id AND nguoidung_id = :nguoidung_id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int) $id, PDO::PARAM_INT);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    private function bodiacchimacdinh($nguoidung_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE diachi SET macdinh = 0 WHERE nguoidung_id = :nguoidung_id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }
}
?>
