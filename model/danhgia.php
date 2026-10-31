<?php
class DANHGIA
{
    private $id;
    private $nguoidung_id;
    private $sanpham_id;
    private $sao;
    private $noidung;
    private $trangthai;
    private $ngay;

    public function getID() { return $this->id; }
    public function setID($value) { $this->id = $value; }

    public function getNguoidung_id() { return $this->nguoidung_id; }
    public function setNguoidung_id($value) { $this->nguoidung_id = $value; }

    public function getSanpham_id() { return $this->sanpham_id; }
    public function setSanpham_id($value) { $this->sanpham_id = $value; }

    public function getSao() { return $this->sao; }
    public function setSao($value) { $this->sao = $value; }

    public function getNoidung() { return $this->noidung; }
    public function setNoidung($value) { $this->noidung = $value; }

    public function getTrangthai() { return $this->trangthai; }
    public function setTrangthai($value) { $this->trangthai = $value; }

    public function getNgay() { return $this->ngay; }
    public function setNgay($value) { $this->ngay = $value; }

    // Lấy toàn bộ đánh giá cho trang quản trị kèm thông tin khách và sản phẩm
    public function laydanhsachdanhgiaadmin()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT dg.*, 
                           nd.hoten AS ten_khachhang, 
                           nd.gmail AS email_khachhang, 
                           nd.sodienthoai AS sdt_khachhang, 
                           nd.hinhanh AS anh_khachhang,
                           sp.tensanpham, 
                           sp.hinhanh AS anh_sanpham,
                           sp.giaban
                    FROM danhgia dg
                    LEFT JOIN nguoidung nd ON nd.id = dg.nguoidung_id
                    LEFT JOIN sanpham sp ON sp.id = dg.sanpham_id
                    ORDER BY dg.id DESC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi laydanhsachdanhgiaadmin: " . $e->getMessage());
            return [];
        }
    }

    // Lấy đánh giá theo ID
    public function laydanhgiatheoid($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT dg.*, nd.hoten AS ten_khachhang, sp.tensanpham 
                    FROM danhgia dg
                    LEFT JOIN nguoidung nd ON nd.id = dg.nguoidung_id
                    LEFT JOIN sanpham sp ON sp.id = dg.sanpham_id
                    WHERE dg.id = :id LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmd->execute();
            $res = $cmd->fetch(PDO::FETCH_ASSOC);
            return $res ? $res : null;
        } catch (PDOException $e) {
            error_log("Lỗi laydanhgiatheoid: " . $e->getMessage());
            return null;
        }
    }

    // Đổi trạng thái kiểm duyệt (1: Hiện, 0: Ẩn)
    public function doitrangthai($id, $trangthai)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE danhgia SET trangthai = :trangthai WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            error_log("Lỗi doitrangthai danhgia: " . $e->getMessage());
            return false;
        }
    }

    // Xóa đánh giá vi phạm
    public function xoadanhgia($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "DELETE FROM danhgia WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            error_log("Lỗi xoadanhgia: " . $e->getMessage());
            return false;
        }
    }

    // Thống kê nhanh đánh giá
    public function thongkedanhgia()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT 
                        COUNT(*) AS tong,
                        ROUND(AVG(sao), 1) AS diemsao_tb,
                        SUM(CASE WHEN sao = 5 THEN 1 ELSE 0 END) AS danhgia_5sao,
                        SUM(CASE WHEN trangthai = 1 THEN 1 ELSE 0 END) AS hienthi,
                        SUM(CASE WHEN trangthai = 0 THEN 1 ELSE 0 END) AS an_chotam
                    FROM danhgia";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            $res = $cmd->fetch(PDO::FETCH_ASSOC);
            return [
                'tong' => (int)($res['tong'] ?? 0),
                'diemsao_tb' => (float)($res['diemsao_tb'] ?? 5.0),
                'danhgia_5sao' => (int)($res['danhgia_5sao'] ?? 0),
                'hienthi' => (int)($res['hienthi'] ?? 0),
                'an_chotam' => (int)($res['an_chotam'] ?? 0)
            ];
        } catch (PDOException $e) {
            error_log("Lỗi thongkedanhgia: " . $e->getMessage());
            return ['tong' => 0, 'diemsao_tb' => 5.0, 'danhgia_5sao' => 0, 'hienthi' => 0, 'an_chotam' => 0];
        }
    }
}
?>
