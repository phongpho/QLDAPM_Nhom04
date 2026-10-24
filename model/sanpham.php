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

    public function laysanphamnoibat()
    {
        $dbcon = DATABASE::connect();
        try{
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

    

    public function tachsizethanhmmang($sizes)
    {
        if ($sizes === null || trim($sizes) === '') {
            return array();
        }
        $mang = array();
        foreach (explode(',', $sizes) as $s) {
            $s = trim($s);
            if ($s !== '') {
                $mang[] = $s;
            }
        }
        return $mang;
    }

    private function chuoilocsanpham($tukhoa, $loai, $doituong, $muctieu, &$params, $thuonghieu_id = 0)
    {
        // Sản phẩm chỉ hiển thị khi cả sản phẩm đang bán (s.trangthai = 1) VÀ danh mục đang hiển thị (d.trangthai = 1)
        $dk = " WHERE s.trangthai = 1 AND d.trangthai = 1 ";
        $tukhoa = trim((string) $tukhoa);
        if ($tukhoa !== '') {
            $dk .= " AND (s.tensanpham LIKE :tukhoa1 OR s.mota LIKE :tukhoa2) ";
            $params[':tukhoa1'] = '%' . $tukhoa . '%';
            $params[':tukhoa2'] = '%' . $tukhoa . '%';
        }
        if ($loai === 'quanao' || $loai === 'phukien' || $loai === 'tpbs') {
            $dk .= " AND d.loai = :loaidm ";
            $params[':loaidm'] = $loai;
        }
        if ($doituong === 'nam' || $doituong === 'nu') {
            $dk .= " AND s.doituong IN (:doituong, 'unisex') ";
            $params[':doituong'] = $doituong;
        }
        if ($muctieu === 'giamcan' || $muctieu === 'tangcan' || $muctieu === 'duytri') {
            $dk .= " AND FIND_IN_SET(:muctieu, s.muctieu) > 0 ";
            $params[':muctieu'] = $muctieu;
        }
        if ((int) $thuonghieu_id > 0) {
            $dk .= " AND s.thuonghieu_id = :thuonghieu_id ";
            $params[':thuonghieu_id'] = (int) $thuonghieu_id;
        }
        return $dk;
    }

     
    public function laysanphamtheonhom($loai, $doituong = '', $limit = 4)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
                    FROM sanpham s
                    JOIN danhmuc d ON d.id = s.danhmuc_id
                    LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
                    WHERE s.trangthai = 1 AND d.trangthai = 1 AND d.loai = :loai";
            if ($doituong === 'nam' || $doituong === 'nu') {
                $sql .= " AND s.doituong IN (:doituong, 'unisex')";
            }
            $sql .= " ORDER BY s.noibat DESC, s.luotmua DESC, s.ngaytao DESC";
            if ((int) $limit > 0) {
                $sql .= " LIMIT " . (int) $limit;
            }
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":loai", $loai);
            if ($doituong === 'nam' || $doituong === 'nu') {
                $cmd->bindValue(":doituong", $doituong);
            }
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laysanphammoi($limit = 4)
    {
        return $this->laysanphamtheodanhsach(
            "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
             FROM sanpham s
             JOIN danhmuc d ON d.id = s.danhmuc_id
             LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
             WHERE s.trangthai = 1 AND d.trangthai = 1
             ORDER BY s.ngaytao DESC, s.id DESC
             LIMIT " . (int) $limit
        );
    }

    public function laysanphambanchay($limit = 4)
    {
        return $this->laysanphamtheodanhsach(
            "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
             FROM sanpham s
             JOIN danhmuc d ON d.id = s.danhmuc_id
             LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
             WHERE s.trangthai = 1 AND d.trangthai = 1
             ORDER BY s.luotmua DESC, s.luotxem DESC
             LIMIT " . (int) $limit
        );
    }

    public function laysanphamnoibatmoi($limit = 4)
    {
        return $this->laysanphamtheodanhsach(
            "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
             FROM sanpham s
             JOIN danhmuc d ON d.id = s.danhmuc_id
             LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
             WHERE s.trangthai = 1 AND d.trangthai = 1 AND s.noibat = 1
             ORDER BY s.ngaytao DESC, s.id DESC
             LIMIT " . (int) $limit
        );
    }

    public function laysanphamkhuyenmai($limit = 4)
    {
        return $this->laysanphamtheodanhsach(
            "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
             FROM sanpham s
             JOIN danhmuc d ON d.id = s.danhmuc_id
             LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
             WHERE s.trangthai = 1 AND d.trangthai = 1 AND s.giagoc > s.giaban
             ORDER BY (s.giagoc - s.giaban) DESC
             LIMIT " . (int) $limit
        );
    }

    private function laysanphamtheodanhsach($sql)
    {
        $dbcon = DATABASE::connect();
        try {
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laysanphamchitiet($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu, t.logo AS logo_thuonghieu
                    FROM sanpham s
                    JOIN danhmuc d ON d.id = s.danhmuc_id
                    LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
                    WHERE s.id = :id
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

    public function laysanphamtuongtu($danhmuc_id, $tru_id = 0, $limit = 4)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
                    FROM sanpham s
                    JOIN danhmuc d ON d.id = s.danhmuc_id
                    LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
                    WHERE s.trangthai = 1 AND s.danhmuc_id = :danhmuc_id AND s.id <> :tru_id
                    ORDER BY s.luotmua DESC
                    LIMIT " . (int) $limit;
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":danhmuc_id", (int) $danhmuc_id, PDO::PARAM_INT);
            $cmd->bindValue(":tru_id", (int) $tru_id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function timkiemsanpham($tukhoa = '', $loai = '', $doituong = '', $muctieu = '', $sapxep = 'moi', $limit = 12, $offset = 0, $thuonghieu_id = 0)
    {
        $dbcon = DATABASE::connect();
        try {
            $params = array();
            $dk = $this->chuoilocsanpham($tukhoa, $loai, $doituong, $muctieu, $params, $thuonghieu_id);

            switch ($sapxep) {
                case 'banchay':
                    $order = " ORDER BY s.luotmua DESC, s.luotxem DESC ";
                    break;
                case 'giathap':
                    $order = " ORDER BY s.giaban ASC ";
                    break;
                case 'giacao':
                    $order = " ORDER BY s.giaban DESC ";
                    break;
                case 'giamgia':
                    $order = " ORDER BY (s.giagoc - s.giaban) DESC ";
                    break;
                default:
                    $order = " ORDER BY s.ngaytao DESC, s.id DESC ";
                    break;
            }

            $sql = "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
                    FROM sanpham s
                    JOIN danhmuc d ON d.id = s.danhmuc_id
                    LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id" . $dk . $order
                . " LIMIT " . (int) $limit . " OFFSET " . (int) $offset;

            $cmd = $dbcon->prepare($sql);
            foreach ($params as $k => $v) {
                $cmd->bindValue($k, $v);
            }
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function demtimkiemsanpham($tukhoa = '', $loai = '', $doituong = '', $muctieu = '', $thuonghieu_id = 0)
    {
        $dbcon = DATABASE::connect();
        try {
            $params = array();
            $dk = $this->chuoilocsanpham($tukhoa, $loai, $doituong, $muctieu, $params, $thuonghieu_id);
            $sql = "SELECT COUNT(*) AS tong
                    FROM sanpham s
                    JOIN danhmuc d ON d.id = s.danhmuc_id" . $dk;
            $cmd = $dbcon->prepare($sql);
            foreach ($params as $k => $v) {
                $cmd->bindValue($k, $v);
            }
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            return $row ? (int) $row['tong'] : 0;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laydanhsachthuonghieu()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT t.*, COUNT(s.id) AS soluongsp
                    FROM thuonghieu t
                    LEFT JOIN sanpham s ON s.thuonghieu_id = t.id AND s.trangthai = 1
                    WHERE t.logo IS NOT NULL AND t.logo <> ''
                    GROUP BY t.id, t.tenthuonghieu, t.logo
                    ORDER BY t.id ASC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array();
        }
    }

    public function laysanphamtheomuctieu($muctieu, $gioitinh = 'nam', $limit = 8)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
                    FROM sanpham s
                    JOIN danhmuc d ON d.id = s.danhmuc_id
                    LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
                    WHERE s.trangthai = 1 AND s.soluongton > 0
                      AND FIND_IN_SET(:muctieu, s.muctieu) > 0
                      AND s.doituong IN (:gioitinh, 'unisex')
                    ORDER BY s.noibat DESC, s.luotmua DESC
                    LIMIT " . (int) $limit;
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":muctieu", $muctieu);
            $cmd->bindValue(":gioitinh", $gioitinh);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laysanphamtheocombo($muctieu, $gioitinh = 'nam')
    {
        return array(
            'quanao'  => $this->laymotdientrongcombo('quanao', $muctieu, $gioitinh),
            'phukien' => $this->laymotdientrongcombo('phukien', $muctieu, $gioitinh),
            'tpbs'    => $this->laymotdientrongcombo('tpbs', $muctieu, $gioitinh)
        );
    }

    private function laymotdientrongcombo($loai, $muctieu, $gioitinh)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
                    FROM sanpham s
                    JOIN danhmuc d ON d.id = s.danhmuc_id
                    LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
                    WHERE s.trangthai = 1 AND s.soluongton > 0
                      AND d.loai = :loai
                      AND FIND_IN_SET(:muctieu, s.muctieu) > 0
                      AND s.doituong IN (:gioitinh, 'unisex')
                    ORDER BY s.noibat DESC, s.luotmua DESC
                    LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":loai", $loai);
            $cmd->bindValue(":muctieu", $muctieu);
            $cmd->bindValue(":gioitinh", $gioitinh);
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            return $row ? $row : null;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laysizegoiy($gioitinh, $chieucao, $cannang)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT size, thutu
                    FROM bangsize
                    WHERE gioitinh = :gioitinh AND chieucao_min <= :chieucao AND chieucao_max > :chieucao
                    LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":gioitinh", $gioitinh);
            $cmd->bindValue(":chieucao", $chieucao);
            $cmd->execute();
            $theocao = $cmd->fetch(PDO::FETCH_ASSOC);

            $sql = "SELECT size, thutu
                    FROM bangsize
                    WHERE gioitinh = :gioitinh AND cannang_min <= :cannang AND cannang_max > :cannang
                    LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":gioitinh", $gioitinh);
            $cmd->bindValue(":cannang", $cannang);
            $cmd->execute();
            $theonang = $cmd->fetch(PDO::FETCH_ASSOC);

            if (!$theocao && !$theonang) {
                return null;
            }
            if ($theocao && !$theonang) {
                $chon = $theocao;
            } elseif (!$theocao && $theonang) {
                $chon = $theonang;
            } else {
                $chon = ((int) $theonang['thutu'] > (int) $theocao['thutu']) ? $theonang : $theocao;
            }

            return array(
                'size'      => $chon['size'],
                'thutu'     => (int) $chon['thutu'],
                'size_cao'  => $theocao ? $theocao['size'] : null,
                'size_nang' => $theonang ? $theonang['size'] : null
            );
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laycauhinhbmi($bmi)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT phanloai, bmi_min, bmi_max, khuyenghe
                    FROM cauhinh_bmi
                    WHERE :bmi BETWEEN bmi_min AND bmi_max
                    LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":bmi", $bmi);
            $cmd->execute();
            return $cmd->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function layhosothetrang($nguoidung_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM hosothetrang WHERE nguoidung_id = :nguoidung_id LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function luuhosothetrang($nguoidung_id, $chieucao, $cannang, $gioitinh, $tuoi, $muctieu, $bmi)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "INSERT INTO hosothetrang (nguoidung_id, chieucao, cannang, gioitinh, tuoi, muctieu, bmi)
                    VALUES (:nguoidung_id, :chieucao, :cannang, :gioitinh, :tuoi, :muctieu, :bmi)
                    ON DUPLICATE KEY UPDATE chieucao = VALUES(chieucao), cannang = VALUES(cannang),
                                            gioitinh = VALUES(gioitinh), tuoi = VALUES(tuoi),
                                            muctieu = VALUES(muctieu), bmi = VALUES(bmi)";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int) $nguoidung_id, PDO::PARAM_INT);
            $cmd->bindValue(":chieucao", $chieucao);
            $cmd->bindValue(":cannang", $cannang);
            $cmd->bindValue(":gioitinh", $gioitinh);
            $cmd->bindValue(":tuoi", (int) $tuoi, PDO::PARAM_INT);
            $cmd->bindValue(":muctieu", $muctieu);
            $cmd->bindValue(":bmi", $bmi);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laydanhgiasanpham($sanpham_id, $limit = 10)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT dg.*, nd.hoten, nd.hinhanh
                    FROM danhgia dg
                    JOIN nguoidung nd ON nd.id = dg.nguoidung_id
                    WHERE dg.sanpham_id = :sanpham_id AND dg.trangthai = 1
                    ORDER BY dg.ngay DESC
                    LIMIT " . (int) $limit;
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":sanpham_id", (int) $sanpham_id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laydiemdanhgiasanpham($sanpham_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT COUNT(*) AS soluot, IFNULL(AVG(sao), 0) AS diem
                    FROM danhgia
                    WHERE sanpham_id = :sanpham_id AND trangthai = 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":sanpham_id", (int) $sanpham_id, PDO::PARAM_INT);
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            return array(
                'soluot' => $row ? (int) $row['soluot'] : 0,
                'diem'   => $row ? round((float) $row['diem'], 1) : 0
            );
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    public function laysoluongton($sanpham_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT soluongton FROM sanpham WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int) $sanpham_id, PDO::PARAM_INT);
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            return $row ? (int) $row['soluongton'] : 0;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    
    public function laysanphamquantri()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT s.*, d.tendanhmuc, d.loai AS loai_danhmuc, t.tenthuonghieu
                    FROM sanpham s
                    LEFT JOIN danhmuc d ON d.id = s.danhmuc_id
                    LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
                    ORDER BY s.id DESC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            return [];
        }
    }

    public function laytatcathuonghieu()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM thuonghieu ORDER BY tenthuonghieu ASC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function themsanpham($tensanpham, $mota, $giagoc, $giaban, $soluongton, $hinhanh, $danhmuc_id, $thuonghieu_id, $doituong, $sizes, $mausac, $chatlieu, $thanhphan, $muctieu, $hansudung, $noibat, $trangthai)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "INSERT INTO sanpham (tensanpham, mota, giagoc, giaban, soluongton, hinhanh, danhmuc_id, thuonghieu_id, doituong, sizes, mausac, chatlieu, thanhphan, muctieu, hansudung, noibat, trangthai)
                    VALUES (:tensanpham, :mota, :giagoc, :giaban, :soluongton, :hinhanh, :danhmuc_id, :thuonghieu_id, :doituong, :sizes, :mausac, :chatlieu, :thanhphan, :muctieu, :hansudung, :noibat, :trangthai)";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":tensanpham", $tensanpham);
            $cmd->bindValue(":mota", $mota);
            $cmd->bindValue(":giagoc", $giagoc);
            $cmd->bindValue(":giaban", $giaban);
            $cmd->bindValue(":soluongton", (int)$soluongton, PDO::PARAM_INT);
            $cmd->bindValue(":hinhanh", $hinhanh);
            $cmd->bindValue(":danhmuc_id", (int)$danhmuc_id, PDO::PARAM_INT);
            $cmd->bindValue(":thuonghieu_id", !empty($thuonghieu_id) ? (int)$thuonghieu_id : null, !empty($thuonghieu_id) ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $cmd->bindValue(":doituong", $doituong);
            $cmd->bindValue(":sizes", !empty($sizes) ? $sizes : null);
            $cmd->bindValue(":mausac", !empty($mausac) ? $mausac : null);
            $cmd->bindValue(":chatlieu", !empty($chatlieu) ? $chatlieu : null);
            $cmd->bindValue(":thanhphan", !empty($thanhphan) ? $thanhphan : null);
            $cmd->bindValue(":muctieu", !empty($muctieu) ? $muctieu : 'giamcan,tangcan,duytri');
            $cmd->bindValue(":hansudung", !empty($hansudung) ? $hansudung : null);
            $cmd->bindValue(":noibat", (int)$noibat, PDO::PARAM_INT);
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi thêm sản phẩm: " . $e->getMessage() . "</p>";
            return false;
        }
    }

    public function capnhatsanpham($id, $tensanpham, $mota, $giagoc, $giaban, $soluongton, $hinhanh, $danhmuc_id, $thuonghieu_id, $doituong, $sizes, $mausac, $chatlieu, $thanhphan, $muctieu, $hansudung, $noibat, $trangthai)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE sanpham SET
                        tensanpham = :tensanpham,
                        mota = :mota,
                        giagoc = :giagoc,
                        giaban = :giaban,
                        soluongton = :soluongton,
                        hinhanh = :hinhanh,
                        danhmuc_id = :danhmuc_id,
                        thuonghieu_id = :thuonghieu_id,
                        doituong = :doituong,
                        sizes = :sizes,
                        mausac = :mausac,
                        chatlieu = :chatlieu,
                        thanhphan = :thanhphan,
                        muctieu = :muctieu,
                        hansudung = :hansudung,
                        noibat = :noibat,
                        trangthai = :trangthai
                    WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmd->bindValue(":tensanpham", $tensanpham);
            $cmd->bindValue(":mota", $mota);
            $cmd->bindValue(":giagoc", $giagoc);
            $cmd->bindValue(":giaban", $giaban);
            $cmd->bindValue(":soluongton", (int)$soluongton, PDO::PARAM_INT);
            $cmd->bindValue(":hinhanh", $hinhanh);
            $cmd->bindValue(":danhmuc_id", (int)$danhmuc_id, PDO::PARAM_INT);
            $cmd->bindValue(":thuonghieu_id", !empty($thuonghieu_id) ? (int)$thuonghieu_id : null, !empty($thuonghieu_id) ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $cmd->bindValue(":doituong", $doituong);
            $cmd->bindValue(":sizes", !empty($sizes) ? $sizes : null);
            $cmd->bindValue(":mausac", !empty($mausac) ? $mausac : null);
            $cmd->bindValue(":chatlieu", !empty($chatlieu) ? $chatlieu : null);
            $cmd->bindValue(":thanhphan", !empty($thanhphan) ? $thanhphan : null);
            $cmd->bindValue(":muctieu", !empty($muctieu) ? $muctieu : 'giamcan,tangcan,duytri');
            $cmd->bindValue(":hansudung", !empty($hansudung) ? $hansudung : null);
            $cmd->bindValue(":noibat", (int)$noibat, PDO::PARAM_INT);
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi cập nhật sản phẩm: " . $e->getMessage() . "</p>";
            return false;
        }
    }

    public function doitrangthaisanpham($id, $trangthai)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE sanpham SET trangthai = :trangthai WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function doinoibatsanpham($id, $noibat)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE sanpham SET noibat = :noibat WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":noibat", (int)$noibat, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function kiemtratudonhang($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT COUNT(*) AS total FROM donhangct WHERE sanpham_id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            return ($row && (int)$row["total"] > 0);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function xoasanpham($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $cmd1 = $dbcon->prepare("DELETE FROM yeuthich WHERE sanpham_id = :id");
            $cmd1->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmd1->execute();

            $cmd2 = $dbcon->prepare("DELETE FROM giohang WHERE sanpham_id = :id");
            $cmd2->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmd2->execute();

            $sql = "DELETE FROM sanpham WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>