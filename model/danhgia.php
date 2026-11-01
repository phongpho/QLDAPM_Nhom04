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

    // Kiểm tra khách hàng đã mua sản phẩm này chưa (hoặc là Admin/Nhân viên thì luôn được phép)
    public function kiemtraduocdanhgia($nguoidung_id, $sanpham_id)
    {
        if ((int)$nguoidung_id <= 0 || (int)$sanpham_id <= 0) {
            return false;
        }
        $dbcon = DATABASE::connect();
        try {
            // 1. Kiểm tra nếu đã mua sản phẩm trong đơn hàng (không bị hủy d.trangthai != 6)
            $sql = "SELECT COUNT(*) 
                    FROM donhang d
                    JOIN donhangct ct ON ct.donhang_id = d.id
                    WHERE d.nguoidung_id = :nguoidung_id 
                      AND ct.sanpham_id = :sanpham_id 
                      AND d.trangthai != 6";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int)$nguoidung_id, PDO::PARAM_INT);
            $cmd->bindValue(":sanpham_id", (int)$sanpham_id, PDO::PARAM_INT);
            $cmd->execute();
            if ((int)$cmd->fetchColumn() > 0) {
                return true;
            }

            // 2. Nếu là Admin hoặc Nhân viên thì luôn cho phép test đánh giá
            $sqlRole = "SELECT loai FROM nguoidung WHERE id = :uid LIMIT 1";
            $cmdRole = $dbcon->prepare($sqlRole);
            $cmdRole->bindValue(":uid", (int)$nguoidung_id, PDO::PARAM_INT);
            $cmdRole->execute();
            $loai = (int)$cmdRole->fetchColumn();
            return ($loai === 1 || $loai === 2);
        } catch (PDOException $e) {
            error_log("Lỗi kiemtraduocdanhgia: " . $e->getMessage());
            return false;
        }
    }

    // Lấy đánh giá của người dùng cho 1 sản phẩm cụ thể (nếu có)
    public function laydanhgiacuatoi($nguoidung_id, $sanpham_id)
    {
        if ((int)$nguoidung_id <= 0 || (int)$sanpham_id <= 0) {
            return null;
        }
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM danhgia 
                    WHERE nguoidung_id = :nguoidung_id AND sanpham_id = :sanpham_id 
                    LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int)$nguoidung_id, PDO::PARAM_INT);
            $cmd->bindValue(":sanpham_id", (int)$sanpham_id, PDO::PARAM_INT);
            $cmd->execute();
            $res = $cmd->fetch(PDO::FETCH_ASSOC);
            return $res ? $res : null;
        } catch (PDOException $e) {
            error_log("Lỗi laydanhgiacuatoi: " . $e->getMessage());
            return null;
        }
    }

    // Lấy danh sách ID các sản phẩm mà người dùng đã đánh giá
    public function laydanhsachidsanphamdadanhgia($nguoidung_id)
    {
        if ((int)$nguoidung_id <= 0) {
            return [];
        }
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT sanpham_id FROM danhgia WHERE nguoidung_id = :nguoidung_id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int)$nguoidung_id, PDO::PARAM_INT);
            $cmd->execute();
            $ids = $cmd->fetchAll(PDO::FETCH_COLUMN);
            return array_map('intval', $ids);
        } catch (PDOException $e) {
            error_log("Lỗi laydanhsachidsanphamdadanhgia: " . $e->getMessage());
            return [];
        }
    }

    // Lưu hoặc cập nhật đánh giá của khách hàng
    public function luudanhgia($nguoidung_id, $sanpham_id, $sao, $noidung)
    {
        if ((int)$nguoidung_id <= 0 || (int)$sanpham_id <= 0) {
            return false;
        }
        $dbcon = DATABASE::connect();
        try {
            $sao = max(1, min(5, (int)$sao));
            $noidung = trim((string)$noidung);

            $sql = "INSERT INTO danhgia (nguoidung_id, sanpham_id, sao, noidung, trangthai, ngay)
                    VALUES (:nguoidung_id, :sanpham_id, :sao, :noidung, 1, NOW())
                    ON DUPLICATE KEY UPDATE 
                        sao = :sao_up,
                        noidung = :noidung_up,
                        trangthai = 1,
                        ngay = NOW()";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":nguoidung_id", (int)$nguoidung_id, PDO::PARAM_INT);
            $cmd->bindValue(":sanpham_id", (int)$sanpham_id, PDO::PARAM_INT);
            $cmd->bindValue(":sao", $sao, PDO::PARAM_INT);
            $cmd->bindValue(":noidung", $noidung, PDO::PARAM_STR);
            $cmd->bindValue(":sao_up", $sao, PDO::PARAM_INT);
            $cmd->bindValue(":noidung_up", $noidung, PDO::PARAM_STR);
            return $cmd->execute();
        } catch (PDOException $e) {
            error_log("Lỗi luudanhgia: " . $e->getMessage());
            return false;
        }
    }
}
?>
