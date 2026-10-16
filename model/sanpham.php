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

    // =================================================================
    // MODULE 3 - HIỂN THỊ SẢN PHẨM (PHẦN BỔ SUNG)
    // =================================================================

    // Tách chuỗi sizes ("S,M,L,XL") thành mảng; NULL/rỗng -> mảng rỗng
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

    // Điều kiện lọc dùng chung cho trang danh mục & tìm kiếm
    private function chuoilocsanpham($tukhoa, $loai, $doituong, $muctieu, &$params)
    {
        $dk = " WHERE s.trangthai = 1 ";
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
        return $dk;
    }

    // Lấy sản phẩm theo nhóm lớn (danhmuc.loai) kết hợp đối tượng
    // Nam: doituong IN ('nam','unisex') - Nữ: IN ('nu','unisex') - Phụ kiện/TPBS: không lọc đối tượng
    public function laysanphamtheonhom($loai, $doituong = '', $limit = 4)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
                    FROM sanpham s
                    JOIN danhmuc d ON d.id = s.danhmuc_id
                    LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
                    WHERE s.trangthai = 1 AND d.loai = :loai";
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

    // Sản phẩm mới: ngày tạo giảm dần
    public function laysanphammoi($limit = 4)
    {
        return $this->laysanphamtheodanhsach(
            "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
             FROM sanpham s
             JOIN danhmuc d ON d.id = s.danhmuc_id
             LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
             WHERE s.trangthai = 1
             ORDER BY s.ngaytao DESC, s.id DESC
             LIMIT " . (int) $limit
        );
    }

    // Sản phẩm bán chạy: lượt mua giảm dần
    public function laysanphambanchay($limit = 4)
    {
        return $this->laysanphamtheodanhsach(
            "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
             FROM sanpham s
             JOIN danhmuc d ON d.id = s.danhmuc_id
             LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
             WHERE s.trangthai = 1
             ORDER BY s.luotmua DESC, s.luotxem DESC
             LIMIT " . (int) $limit
        );
    }

    // Sản phẩm nổi bật (admin bật cờ noibat): mới nhất trước
    public function laysanphamnoibatmoi($limit = 4)
    {
        return $this->laysanphamtheodanhsach(
            "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
             FROM sanpham s
             JOIN danhmuc d ON d.id = s.danhmuc_id
             LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
             WHERE s.trangthai = 1 AND s.noibat = 1
             ORDER BY s.ngaytao DESC, s.id DESC
             LIMIT " . (int) $limit
        );
    }

    // Sản phẩm khuyến mãi: giagoc > giaban, giảm nhiều nhất trước
    public function laysanphamkhuyenmai($limit = 4)
    {
        return $this->laysanphamtheodanhsach(
            "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
             FROM sanpham s
             JOIN danhmuc d ON d.id = s.danhmuc_id
             LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
             WHERE s.trangthai = 1 AND s.giagoc > s.giaban
             ORDER BY (s.giagoc - s.giaban) DESC
             LIMIT " . (int) $limit
        );
    }

    // Chạy một câu SELECT trả về nhiều dòng (dùng nội bộ cho các hàm danh sách)
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

    // Chi tiết sản phẩm: JOIN danhmuc (biết loai) + thuonghieu (tenthuonghieu)
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

    // Sản phẩm cùng danh mục (trang chi tiết)
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

    // Trang danh mục / tìm kiếm: lọc theo loại, đối tượng, mục tiêu, từ khóa + sắp xếp + phân trang
    public function timkiemsanpham($tukhoa = '', $loai = '', $doituong = '', $muctieu = '', $sapxep = 'moi', $limit = 12, $offset = 0)
    {
        $dbcon = DATABASE::connect();
        try {
            $params = array();
            $dk = $this->chuoilocsanpham($tukhoa, $loai, $doituong, $muctieu, $params);

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

    // Đếm tổng số sản phẩm thỏa điều kiện lọc (phục vụ phân trang)
    public function demtimkiemsanpham($tukhoa = '', $loai = '', $doituong = '', $muctieu = '')
    {
        $dbcon = DATABASE::connect();
        try {
            $params = array();
            $dk = $this->chuoilocsanpham($tukhoa, $loai, $doituong, $muctieu, $params);
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

    // Sản phẩm gợi ý cá nhân hóa: đúng mục tiêu tập luyện + đúng giới tính + còn hàng
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

    // Gợi ý Combo theo thể trạng: 1 Đồ tập + 1 Phụ kiện + 1 TPBS phù hợp mục tiêu & giới tính
    public function laysanphamtheocombo($muctieu, $gioitinh = 'nam')
    {
        return array(
            'quanao'  => $this->laymotdientrongcombo('quanao', $muctieu, $gioitinh),
            'phukien' => $this->laymotdientrongcombo('phukien', $muctieu, $gioitinh),
            'tpbs'    => $this->laymotdientrongcombo('tpbs', $muctieu, $gioitinh)
        );
    }

    // Lấy 1 sản phẩm đại diện cho một nhóm lớn trong combo gợi ý
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

    /**
     * Quy tắc gợi ý size từ bảng bangsize:
     * - size_cao: chieucao >= chieucao_min AND chieucao < chieucao_max
     * - size_nang: cannang >= cannang_min AND cannang < cannang_max
     * - Size gợi ý = size có thutu LỚN HƠN trong 2 size trên
     * Trả về mảng: size, thutu, size_cao, size_nang (null nếu không tra được)
     */
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

    // Đối chiếu BMI với bảng cauhinh_bmi để lấy phân loại + lời khuyên
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

    // Hồ sơ thể trạng của khách đang đăng nhập
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

    // Lưu hồ sơ thể trạng (mỗi người dùng 1 dòng -> nếu có thì cập nhật)
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

    // Danh sách đánh giá đang hiển thị của sản phẩm
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

    // Điểm đánh giá trung bình + số lượt đánh giá
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

    // Số lượng còn trong kho
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

    // Trừ tồn kho + tăng lượt mua (dùng trong transaction khi đặt hàng)
    // Trả về true nếu trừ được (kho còn đủ), false nếu không đủ hàng
    public function trusoluongvatangluotmua($sanpham_id, $soluong)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE sanpham
                    SET soluongton = soluongton - :soluong,
                        luotmua = luotmua + :soluong
                    WHERE id = :id AND soluongton >= :soluong";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":soluong", (int) $soluong, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int) $sanpham_id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->rowCount() > 0;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }
}
?>