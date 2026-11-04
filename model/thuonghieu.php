<?php
class THUONGHIEU
{
    private $id;
    private $tenthuonghieu;
    private $logo;
    private $trangthai;

    public function getID() { return $this->id; }
    public function setID($value) { $this->id = $value; }

    public function getTenthuonghieu() { return $this->tenthuonghieu; }
    public function setTenthuonghieu($value) { $this->tenthuonghieu = $value; }

    public function getLogo() { return $this->logo; }
    public function setLogo($value) { $this->logo = $value; }

    public function getTrangthai() { return $this->trangthai; }
    public function setTrangthai($value) { $this->trangthai = $value; }

    // Lấy toàn bộ thương hiệu kèm số lượng sản phẩm liên kết
    public function laydanhsachthuonghieu()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT t.*, COUNT(s.id) AS soluong_sanpham 
                    FROM thuonghieu t 
                    LEFT JOIN sanpham s ON s.thuonghieu_id = t.id 
                    GROUP BY t.id 
                    ORDER BY t.id DESC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi laydanhsachthuonghieu: " . $e->getMessage());
            return [];
        }
    }

    // Lấy danh sách thương hiệu đang hoạt động (dành cho dropdown chọn hãng)
    public function laythuonghieuhoatdong()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM thuonghieu WHERE trangthai = 1 ORDER BY tenthuonghieu ASC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi laythuonghieuhoatdong: " . $e->getMessage());
            return [];
        }
    }

    // Lấy chi tiết 1 thương hiệu
    public function laythuonghieutheoid($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT t.*, COUNT(s.id) AS soluong_sanpham 
                    FROM thuonghieu t 
                    LEFT JOIN sanpham s ON s.thuonghieu_id = t.id 
                    WHERE t.id = :id 
                    GROUP BY t.id LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmd->execute();
            $res = $cmd->fetch(PDO::FETCH_ASSOC);
            return $res ? $res : null;
        } catch (PDOException $e) {
            error_log("Lỗi laythuonghieutheoid: " . $e->getMessage());
            return null;
        }
    }

    // Kiểm tra tên thương hiệu đã tồn tại chưa
    public function kiemtratrungten($tenthuonghieu, $exclude_id = 0)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT COUNT(*) FROM thuonghieu WHERE LOWER(TRIM(tenthuonghieu)) = LOWER(TRIM(:ten))";
            if ($exclude_id > 0) {
                $sql .= " AND id != :id";
            }
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":ten", $tenthuonghieu);
            if ($exclude_id > 0) {
                $cmd->bindValue(":id", (int)$exclude_id, PDO::PARAM_INT);
            }
            $cmd->execute();
            return ((int)$cmd->fetchColumn()) > 0;
        } catch (PDOException $e) {
            error_log("Lỗi kiemtratrungten: " . $e->getMessage());
            return false;
        }
    }

    // Thêm thương hiệu mới
    public function themthuonghieu($tenthuonghieu, $logo, $trangthai = 1)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "INSERT INTO thuonghieu (tenthuonghieu, logo, trangthai) VALUES (:ten, :logo, :trangthai)";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":ten", trim($tenthuonghieu));
            $cmd->bindValue(":logo", !empty($logo) ? $logo : null);
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->execute();
            return $dbcon->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi themthuonghieu: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật thương hiệu
    public function capnhatthuonghieu($id, $tenthuonghieu, $logo = null, $trangthai = 1)
    {
        $dbcon = DATABASE::connect();
        try {
            if ($logo !== null && $logo !== '') {
                $sql = "UPDATE thuonghieu SET tenthuonghieu = :ten, logo = :logo, trangthai = :trangthai WHERE id = :id";
            } else {
                $sql = "UPDATE thuonghieu SET tenthuonghieu = :ten, trangthai = :trangthai WHERE id = :id";
            }
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":ten", trim($tenthuonghieu));
            if ($logo !== null && $logo !== '') {
                $cmd->bindValue(":logo", $logo);
            }
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            error_log("Lỗi capnhatthuonghieu: " . $e->getMessage());
            return false;
        }
    }

    // Đổi trạng thái hiển thị
    public function doitrangthai($id, $trangthai)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE thuonghieu SET trangthai = :trangthai WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            error_log("Lỗi doitrangthai thuonghieu: " . $e->getMessage());
            return false;
        }
    }

    // Kiểm tra thương hiệu đã có sản phẩm chưa
    public function dacosanpham($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT COUNT(*) FROM sanpham WHERE thuonghieu_id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmd->execute();
            return ((int)$cmd->fetchColumn()) > 0;
        } catch (PDOException $e) {
            error_log("Lỗi dacosanpham: " . $e->getMessage());
            return true;
        }
    }

    // Xóa thương hiệu (chỉ cho phép khi chưa liên kết sản phẩm nào)
    public function xoathuonghieu($id)
    {
        if ($this->dacosanpham($id)) {
            return false;
        }
        $dbcon = DATABASE::connect();
        try {
            $sql = "DELETE FROM thuonghieu WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            error_log("Lỗi xoathuonghieu: " . $e->getMessage());
            return false;
        }
    }

    // Thống kê nhanh
    public function thongkethuonghieu()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT 
                        COUNT(*) AS tong,
                        SUM(CASE WHEN trangthai = 1 THEN 1 ELSE 0 END) AS hoatdong,
                        SUM(CASE WHEN trangthai = 0 THEN 1 ELSE 0 END) AS an
                    FROM thuonghieu";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            $res = $cmd->fetch(PDO::FETCH_ASSOC);

            // Đếm tổng sản phẩm có thương hiệu
            $sql2 = "SELECT COUNT(*) FROM sanpham WHERE thuonghieu_id IS NOT NULL";
            $cmd2 = $dbcon->prepare($sql2);
            $cmd2->execute();
            $res['tongsanpham'] = (int)$cmd2->fetchColumn();

            return $res;
        } catch (PDOException $e) {
            error_log("Lỗi thongkethuonghieu: " . $e->getMessage());
            return ['tong' => 0, 'hoatdong' => 0, 'an' => 0, 'tongsanpham' => 0];
        }
    }
}
?>

