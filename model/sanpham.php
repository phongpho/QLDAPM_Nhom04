<?php
class SANPHAM
{
    private $id;
    private $tensanpham;
    private $mota;
    private $giagoc;
    private $giaban;
    private $soluongton;
    private $hinhanh;
    private $danhmuc_id;
    private $thuonghieu_id;
    private $doituong;
    private $sizes;
    private $mausac;
    private $chatlieu;
    private $thanhphan;
    private $muctieu;
    private $hansudung;
    private $noibat;
    private $trangthai;
    private $luotxem;
    private $luotmua;
    private $ngaytao;

    public function getID() { return $this->id; }
    public function setID($value) { $this->id = $value; }

    public function getTensanpham() { return $this->tensanpham; }
    public function setTensanpham($value) { $this->tensanpham = $value; }

    public function getMota() { return $this->mota; }
    public function setMota($value) { $this->mota = $value; }

    public function getGiagoc() { return $this->giagoc; }
    public function setGiagoc($value) { $this->giagoc = $value; }

    public function getGiaban() { return $this->giaban; }
    public function setGiaban($value) { $this->giaban = $value; }

    public function getSoluongton() { return $this->soluongton; }
    public function setSoluongton($value) { $this->soluongton = $value; }

    public function getHinhanh() { return $this->hinhanh; }
    public function setHinhanh($value) { $this->hinhanh = $value; }

    public function getDanhmuc_id() { return $this->danhmuc_id; }
    public function setDanhmuc_id($value) { $this->danhmuc_id = $value; }

    public function getThuonghieu_id() { return $this->thuonghieu_id; }
    public function setThuonghieu_id($value) { $this->thuonghieu_id = $value; }

    public function getDoituong() { return $this->doituong; }
    public function setDoituong($value) { $this->doituong = $value; }

    public function getSizes() { return $this->sizes; }
    public function setSizes($value) { $this->sizes = $value; }

    public function getMausac() { return $this->mausac; }
    public function setMausac($value) { $this->mausac = $value; }

    public function getChatlieu() { return $this->chatlieu; }
    public function setChatlieu($value) { $this->chatlieu = $value; }

    public function getThanhphan() { return $this->thanhphan; }
    public function setThanhphan($value) { $this->thanhphan = $value; }

    public function getMuctieu() { return $this->muctieu; }
    public function setMuctieu($value) { $this->muctieu = $value; }

    public function getHansudung() { return $this->hansudung; }
    public function setHansudung($value) { $this->hansudung = $value; }

    public function getNoibat() { return $this->noibat; }
    public function setNoibat($value) { $this->noibat = $value; }

    public function getTrangthai() { return $this->trangthai; }
    public function setTrangthai($value) { $this->trangthai = $value; }

    public function getLuotxem() { return $this->luotxem; }
    public function setLuotxem($value) { $this->luotxem = $value; }

    public function getLuotmua() { return $this->luotmua; }
    public function setLuotmua($value) { $this->luotmua = $value; }

    public function getNgaytao() { return $this->ngaytao; }
    public function setNgaytao($value) { $this->ngaytao = $value; }


    public function laysanpham()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM sanpham";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            $result = $cmd->fetchAll();
            return $result;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo "<p>Lỗi truy vấn: $error_message</p>";
            exit();
        }
    }

    // Lấy sản phẩm theo danh mục
    public function laysanphamtheodanhmuc($danhmuc_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM sanpham WHERE danhmuc_id = :madm";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":madm", $danhmuc_id);
            $cmd->execute();
            $result = $cmd->fetchAll();
            return $result;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo "<p>Lỗi truy vấn: $error_message</p>";
            exit();
        }
    }

    // Lấy sản phẩm theo id
    public function laysanphamtheoid($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM sanpham WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", $id);
            $cmd->execute();
            $result = $cmd->fetch();
            return $result;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo "<p>Lỗi truy vấn: $error_message</p>";
            exit();
        }
    }

    // Cập nhật lượt xem
    public function tangluotxem($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE sanpham SET luotxem=luotxem+1 WHERE id=:id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", $id);
            $result = $cmd->execute();
            return $result;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo "<p>Lỗi truy vấn: $error_message</p>";
            exit();
        }
    }

    // Lấy sản phẩm nổi bật (lượt xem cao)
    public function laysanphamnoibat()
    {
        $dbcon = DATABASE::connect();
        try{
            // Thêm điều kiện trangthai = 1 (đang bán) cho chặt chẽ với CSDL mới
            $sql = "SELECT * FROM sanpham WHERE trangthai = 1 
                    ORDER BY luotxem DESC 
                    LIMIT 3";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            $result = $cmd->fetchAll();
            return $result;
        }
        catch(PDOException $e){
            $error_message = $e->getMessage();
            echo "<p>Lỗi truy vấn: $error_message</p>";
            exit();
        }
    }

    // Lấy 4 sản phẩm theo danh mục (Dùng cho trang chủ)
    public function lay4sanphamtheodanhmuc($danhmuc_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM sanpham 
                    WHERE danhmuc_id = :danhmuc_id AND trangthai = 1
                    LIMIT 4";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":danhmuc_id", $danhmuc_id);
            $cmd->execute();
            return $cmd->fetchAll();
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo "<p>Lỗi truy vấn: $error_message</p>";
            exit();
        }
    }
}
?>