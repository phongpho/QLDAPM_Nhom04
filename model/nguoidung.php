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

    public function getID()
    {
        return $this->id;
    }
    public function setID($value)
    {
        $this->id = $value;
    }

    public function getGmail()
    {
        return $this->gmail;
    }
    public function setGmail($value)
    {
        $this->gmail = $value;
    }

    public function getGoogle_id()
    {
        return $this->google_id;
    }
    public function setGoogle_id($value)
    {
        $this->google_id = $value;
    }

    public function getSodienthoai()
    {
        return $this->sodienthoai;
    }
    public function setSodienthoai($value)
    {
        $this->sodienthoai = $value;
    }

    public function getMatkhau()
    {
        return $this->matkhau;
    }
    public function setMatkhau($value)
    {
        $this->matkhau = $value;
    }

    public function getHoten()
    {
        return $this->hoten;
    }
    public function setHoten($value)
    {
        $this->hoten = $value;
    }

    public function getLoai()
    {
        return $this->loai;
    }
    public function setLoai($value)
    {
        $this->loai = $value;
    }

    public function getTrangthai()
    {
        return $this->trangthai;
    }
    public function setTrangthai($value)
    {
        $this->trangthai = $value;
    }

    public function getHinhanh()
    {
        return $this->hinhanh;
    }
    public function setHinhanh($value)
    {
        $this->hinhanh = $value;
    }

    public function getNgaytao()
    {
        return $this->ngaytao;
    }
    public function setNgaytao($value)
    {
        $this->ngaytao = $value;
    }

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

    public function themnguoidung($email, $matkhau, $sodt, $hoten, $loai = 3, $hinhanh = null)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "INSERT INTO nguoidung (gmail, matkhau, sodienthoai, hoten, loai, hinhanh)
                    VALUES (:gmail, :matkhau, :sodienthoai, :hoten, :loai, :hinhanh)";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":gmail", $email);
            $cmd->bindValue(":matkhau", password_hash($matkhau, PASSWORD_DEFAULT));
            $cmd->bindValue(":sodienthoai", $sodt);
            $cmd->bindValue(":hoten", $hoten);
            $cmd->bindValue(":loai", (int) $loai, PDO::PARAM_INT);
            $cmd->bindValue(":hinhanh", $hinhanh);
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

    public function laynguoidungtheoid($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT id, gmail AS email, sodienthoai, hoten, loai, trangthai, hinhanh
                    FROM nguoidung
                    WHERE id = :id
                    LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int) $id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function kiemtratrungkhisua($id, $email, $sodt)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT id
                    FROM nguoidung
                    WHERE (gmail = :email OR sodienthoai = :sodt) AND id != :id
                    LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":email", $email);
            $cmd->bindValue(":sodt", $sodt);
            $cmd->bindValue(":id", (int) $id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->fetch(PDO::FETCH_ASSOC) ? true : false;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function suanhanvien($id, $email, $sodt, $hoten, $trangthai, $hinhanh, $matkhaumoi = null)
    {
        $dbcon = DATABASE::connect();
        try {
            if (!empty($matkhaumoi)) {
                $sql = "UPDATE nguoidung
                        SET gmail = :email,
                            sodienthoai = :sodienthoai,
                            hoten = :hoten,
                            trangthai = :trangthai,
                            hinhanh = :hinhanh,
                            matkhau = :matkhau
                        WHERE id = :id";
                $cmd = $dbcon->prepare($sql);
                $cmd->bindValue(":matkhau", password_hash($matkhaumoi, PASSWORD_DEFAULT));
            } else {
                $sql = "UPDATE nguoidung
                        SET gmail = :email,
                            sodienthoai = :sodienthoai,
                            hoten = :hoten,
                            trangthai = :trangthai,
                            hinhanh = :hinhanh
                        WHERE id = :id";
                $cmd = $dbcon->prepare($sql);
            }
            $cmd->bindValue(":email", $email);
            $cmd->bindValue(":sodienthoai", $sodt);
            $cmd->bindValue(":hoten", $hoten);
            $cmd->bindValue(":trangthai", (int) $trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":hinhanh", $hinhanh);
            $cmd->bindValue(":id", (int) $id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function xoanguoidung($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "DELETE FROM nguoidung WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int) $id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laydanhsachkhachhangquantri()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT u.id, u.gmail AS email, u.sodienthoai, u.hoten, u.loai, u.trangthai, u.hinhanh, u.ngaytao,
                           COUNT(d.id) AS tong_donhang,
                           IFNULL(SUM(CASE WHEN d.trangthai <> 0 THEN d.tongtien ELSE 0 END), 0) AS tong_chitieu
                    FROM nguoidung u
                    LEFT JOIN donhang d ON d.nguoidung_id = u.id
                    WHERE u.loai = 3
                    GROUP BY u.id, u.gmail, u.sodienthoai, u.hoten, u.loai, u.trangthai, u.hinhanh, u.ngaytao
                    ORDER BY u.id DESC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            return [];
        }
    }

    public function laychitietkhachhang($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sqlUser = "SELECT id, gmail AS email, sodienthoai, hoten, loai, trangthai, hinhanh, ngaytao
                        FROM nguoidung
                        WHERE id = :id AND loai = 3
                        LIMIT 1";
            $cmdUser = $dbcon->prepare($sqlUser);
            $cmdUser->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmdUser->execute();
            $user = $cmdUser->fetch(PDO::FETCH_ASSOC);
            if (!$user) return null;

            $sqlDc = "SELECT * FROM diachi WHERE nguoidung_id = :id ORDER BY macdinh DESC, id DESC";
            $cmdDc = $dbcon->prepare($sqlDc);
            $cmdDc->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmdDc->execute();
            $user["diachi_list"] = $cmdDc->fetchAll(PDO::FETCH_ASSOC);

            $sqlTt = "SELECT * FROM hosothetrang WHERE nguoidung_id = :id LIMIT 1";
            $cmdTt = $dbcon->prepare($sqlTt);
            $cmdTt->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmdTt->execute();
            $user["thetrang"] = $cmdTt->fetch(PDO::FETCH_ASSOC);

            $sqlDh = "SELECT id, ngaytao, tongtien, trangthai, phuongthuctt
                      FROM donhang
                      WHERE nguoidung_id = :id
                      ORDER BY id DESC LIMIT 10";
            $cmdDh = $dbcon->prepare($sqlDh);
            $cmdDh->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmdDh->execute();
            $user["donhang_list"] = $cmdDh->fetchAll(PDO::FETCH_ASSOC);

            return $user;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            return null;
        }
    }

    public function kiemtradonhangkhachhang($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT COUNT(*) AS total FROM donhang WHERE nguoidung_id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            return ($row && (int)$row["total"] > 0);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function xoakhachhang($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $cmd1 = $dbcon->prepare("DELETE FROM diachi WHERE nguoidung_id = :id");
            $cmd1->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmd1->execute();

            $cmd2 = $dbcon->prepare("DELETE FROM giohang WHERE nguoidung_id = :id");
            $cmd2->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmd2->execute();

            $cmd3 = $dbcon->prepare("DELETE FROM yeuthich WHERE nguoidung_id = :id");
            $cmd3->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmd3->execute();

            $cmd4 = $dbcon->prepare("DELETE FROM hosothetrang WHERE nguoidung_id = :id");
            $cmd4->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmd4->execute();

            $sql = "DELETE FROM nguoidung WHERE id = :id AND loai = 3";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
