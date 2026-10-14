<?php
class NGUOIDUNG
{
    private $id;
    private $gmail;
    private $google_id;
    private $sodienthoai;
    private $matkhau;
    private $hoten;
    private $loai;
    private $trangthai;
    private $hinhanh;
    private $ngaytao;

    public function getID() { return $this->id; }
    public function setID($value) { $this->id = $value; }

    public function getGmail() { return $this->gmail; }
    public function setGmail($value) { $this->gmail = $value; }

    public function getGoogle_id() { return $this->google_id; }
    public function setGoogle_id($value) { $this->google_id = $value; }

    public function getSodienthoai() { return $this->sodienthoai; }
    public function setSodienthoai($value) { $this->sodienthoai = $value; }

    public function getMatkhau() { return $this->matkhau; }
    public function setMatkhau($value) { $this->matkhau = $value; }

    public function getHoten() { return $this->hoten; }
    public function setHoten($value) { $this->hoten = $value; }

    public function getLoai() { return $this->loai; }
    public function setLoai($value) { $this->loai = $value; }

    public function getTrangthai() { return $this->trangthai; }
    public function setTrangthai($value) { $this->trangthai = $value; }

    public function getHinhanh() { return $this->hinhanh; }
    public function setHinhanh($value) { $this->hinhanh = $value; }

    public function getNgaytao() { return $this->ngaytao; }
    public function setNgaytao($value) { $this->ngaytao = $value; }

    public function laydanhsachnguoidung()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT id, gmail AS email, sodienthoai, hoten, loai, trangthai, hinhanh
                    FROM nguoidung
                    ORDER BY id DESC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function kiemtratrung($email, $sodt)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT id
                    FROM nguoidung
                    WHERE gmail = :email OR sodienthoai = :sodt
                    LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":email", $email);
            $cmd->bindValue(":sodt", $sodt);
            $cmd->execute();
            return $cmd->fetch(PDO::FETCH_ASSOC) ? true : false;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function themnguoidung($email, $matkhau, $sodt, $hoten, $loai = 3)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "INSERT INTO nguoidung (gmail, matkhau, sodienthoai, hoten, loai)
                    VALUES (:gmail, :matkhau, :sodienthoai, :hoten, :loai)";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":gmail", $email);
            $cmd->bindValue(":matkhau", password_hash($matkhau, PASSWORD_DEFAULT));
            $cmd->bindValue(":sodienthoai", $sodt);
            $cmd->bindValue(":hoten", $hoten);
            $cmd->bindValue(":loai", (int) $loai, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function kiemtranguoidunghople($email, $matkhau)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT *
                    FROM nguoidung
                    WHERE gmail = :email AND trangthai = 1
                    LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":email", $email);
            $cmd->execute();
            $user = $cmd->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($matkhau, $user["matkhau"])) {
                return true;
            }

            return false;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laythongtinnguoidung($email)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT id, gmail AS email, sodienthoai, hoten, loai, trangthai, hinhanh
                    FROM nguoidung
                    WHERE gmail = :email
                    LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":email", $email);
            $cmd->execute();
            return $cmd->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function capnhatnguoidung($mand, $email, $sodt, $hoten, $hinhanh)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE nguoidung
                    SET gmail = :email,
                        sodienthoai = :sodienthoai,
                        hoten = :hoten,
                        hinhanh = :hinhanh
                    WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":email", $email);
            $cmd->bindValue(":sodienthoai", $sodt);
            $cmd->bindValue(":hoten", $hoten);
            $cmd->bindValue(":hinhanh", $hinhanh);
            $cmd->bindValue(":id", $mand, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function doimatkhau($email, $matkhaumoi)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE nguoidung
                    SET matkhau = :matkhau
                    WHERE gmail = :email";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":matkhau", password_hash($matkhaumoi, PASSWORD_DEFAULT));
            $cmd->bindValue(":email", $email);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function doiloainguoidung($email, $loai)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE nguoidung
                    SET loai = :loai
                    WHERE gmail = :email";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":loai", (int) $loai, PDO::PARAM_INT);
            $cmd->bindValue(":email", $email);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function doitrangthai($id, $trangthai)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE nguoidung
                    SET trangthai = :trangthai
                    WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":trangthai", (int) $trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int) $id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }
}
?>
