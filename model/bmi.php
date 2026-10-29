<?php
class BMI
{
    private static $migrated = false;

    public function __construct()
    {
        $this->tudongKiemtraDatabase();
    }

    // Tự động kiểm tra và nâng cấp CSDL nếu chưa cập nhật bảng mới
    private function tudongKiemtraDatabase()
    {
        if (self::$migrated) {
            return;
        }

        try {
            $dbcon = DATABASE::connect();
            $columns = $dbcon->query("SHOW COLUMNS FROM cauhinh_bmi")->fetchAll(PDO::FETCH_COLUMN);

            if (!in_array('muctieu_khuyendung', $columns)) {
                $dbcon->exec("ALTER TABLE cauhinh_bmi ADD COLUMN muctieu_khuyendung ENUM('giamcan','tangcan','duytri') NOT NULL DEFAULT 'duytri' COMMENT 'Mục tiêu khuyến nghị'");
            }
            if (!in_array('trangthai', $columns)) {
                $dbcon->exec("ALTER TABLE cauhinh_bmi ADD COLUMN trangthai TINYINT NOT NULL DEFAULT 1 COMMENT '1 hoạt động, 0 tắt'");
            }
            if (!in_array('thutu', $columns)) {
                $dbcon->exec("ALTER TABLE cauhinh_bmi ADD COLUMN thutu TINYINT NOT NULL DEFAULT 1");
            }

            $sqlTable = "CREATE TABLE IF NOT EXISTS cauhinh_bmi_sanpham (
              cauhinh_bmi_id INT NOT NULL,
              sanpham_id     INT NOT NULL,
              uutien         TINYINT NOT NULL DEFAULT 0,
              PRIMARY KEY (cauhinh_bmi_id, sanpham_id),
              CONSTRAINT fk_bmisp_cauhinh FOREIGN KEY (cauhinh_bmi_id) REFERENCES cauhinh_bmi (id) ON DELETE CASCADE,
              CONSTRAINT fk_bmisp_sanpham FOREIGN KEY (sanpham_id) REFERENCES sanpham (id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $dbcon->exec($sqlTable);

            self::$migrated = true;
        } catch (Exception $e) {
            // Không ngắt tiến trình nếu CSDL chưa bật hoặc đã tồn tại
        }
    }

    // Lấy toàn bộ danh sách các mốc BMI phục vụ trang Admin
    public function laydanhsachcauhinh()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT b.*, COUNT(DISTINCT bs.sanpham_id) AS soluong_sanpham
                    FROM cauhinh_bmi b
                    LEFT JOIN cauhinh_bmi_sanpham bs ON bs.cauhinh_bmi_id = b.id
                    GROUP BY b.id, b.phanloai, b.bmi_min, b.bmi_max, b.muctieu_khuyendung, b.khuyenghe, b.trangthai, b.thutu
                    ORDER BY b.thutu ASC, b.bmi_min ASC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Lấy các mốc BMI đang hoạt động (trangthai = 1)
    public function laycauhinhoatdong()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM cauhinh_bmi WHERE trangthai = 1 ORDER BY thutu ASC, bmi_min ASC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Lấy mốc cấu hình theo ID
    public function laycauhinhbyid($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM cauhinh_bmi WHERE id = :id LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Tra cứu mốc thể trạng theo chỉ số BMI cụ thể (phục vụ khách hàng)
    public function laycauhinhchiso($bmi)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM cauhinh_bmi
                    WHERE :bmi BETWEEN bmi_min AND bmi_max AND trangthai = 1
                    ORDER BY thutu ASC, id ASC
                    LIMIT 1";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":bmi", (float)$bmi);
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                // Nếu vượt quá min/max, lấy mốc gần nhất
                $sqlFallback = "SELECT * FROM cauhinh_bmi WHERE trangthai = 1 ORDER BY ABS((bmi_min + bmi_max)/2 - :bmi) ASC LIMIT 1";
                $cmdFallback = $dbcon->prepare($sqlFallback);
                $cmdFallback->bindValue(":bmi", (float)$bmi);
                $cmdFallback->execute();
                $row = $cmdFallback->fetch(PDO::FETCH_ASSOC);
            }
            return $row;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Thêm mốc cấu hình BMI mới
    public function themcauhinh($phanloai, $bmi_min, $bmi_max, $muctieu_khuyendung, $khuyenghe, $trangthai = 1, $thutu = 1, $sanpham_ids = [])
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "INSERT INTO cauhinh_bmi (phanloai, bmi_min, bmi_max, muctieu_khuyendung, khuyenghe, trangthai, thutu)
                    VALUES (:phanloai, :bmi_min, :bmi_max, :muctieu, :khuyenghe, :trangthai, :thutu)";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":phanloai", trim($phanloai));
            $cmd->bindValue(":bmi_min", (float)$bmi_min);
            $cmd->bindValue(":bmi_max", (float)$bmi_max);
            $cmd->bindValue(":muctieu", $muctieu_khuyendung);
            $cmd->bindValue(":khuyenghe", trim($khuyenghe));
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":thutu", (int)$thutu, PDO::PARAM_INT);
            $cmd->execute();

            $newId = (int)$dbcon->lastInsertId();
            if ($newId > 0 && !empty($sanpham_ids)) {
                $this->gansanphamgoiy($newId, $sanpham_ids);
            }
            return $newId;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Cập nhật mốc cấu hình BMI
    public function suacauhinh($id, $phanloai, $bmi_min, $bmi_max, $muctieu_khuyendung, $khuyenghe, $trangthai = 1, $thutu = 1, $sanpham_ids = [])
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE cauhinh_bmi
                    SET phanloai = :phanloai,
                        bmi_min = :bmi_min,
                        bmi_max = :bmi_max,
                        muctieu_khuyendung = :muctieu,
                        khuyenghe = :khuyenghe,
                        trangthai = :trangthai,
                        thutu = :thutu
                    WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":phanloai", trim($phanloai));
            $cmd->bindValue(":bmi_min", (float)$bmi_min);
            $cmd->bindValue(":bmi_max", (float)$bmi_max);
            $cmd->bindValue(":muctieu", $muctieu_khuyendung);
            $cmd->bindValue(":khuyenghe", trim($khuyenghe));
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":thutu", (int)$thutu, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $ok = $cmd->execute();

            // Cập nhật danh sách đồ dùng gợi ý
            $this->gansanphamgoiy($id, $sanpham_ids);
            return $ok;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Đổi trạng thái Bật / Tắt mốc BMI
    public function doitrangthai($id, $trangthai)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "UPDATE cauhinh_bmi SET trangthai = :trangthai WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":trangthai", (int)$trangthai, PDO::PARAM_INT);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Xóa mốc BMI
    public function xoacauhinh($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "DELETE FROM cauhinh_bmi WHERE id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$id, PDO::PARAM_INT);
            return $cmd->execute();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Gán danh sách sản phẩm gợi ý cho mốc BMI
    public function gansanphamgoiy($cauhinh_bmi_id, $sanpham_ids)
    {
        $dbcon = DATABASE::connect();
        try {
            // Xóa các liên kết cũ
            $sqlDel = "DELETE FROM cauhinh_bmi_sanpham WHERE cauhinh_bmi_id = :id";
            $cmdDel = $dbcon->prepare($sqlDel);
            $cmdDel->bindValue(":id", (int)$cauhinh_bmi_id, PDO::PARAM_INT);
            $cmdDel->execute();

            // Chèn lại danh sách mới
            if (!empty($sanpham_ids) && is_array($sanpham_ids)) {
                $sqlIns = "INSERT IGNORE INTO cauhinh_bmi_sanpham (cauhinh_bmi_id, sanpham_id, uutien) VALUES (:bmi_id, :sp_id, 1)";
                $cmdIns = $dbcon->prepare($sqlIns);
                foreach ($sanpham_ids as $spId) {
                    $spId = (int)$spId;
                    if ($spId > 0) {
                        $cmdIns->bindValue(":bmi_id", (int)$cauhinh_bmi_id, PDO::PARAM_INT);
                        $cmdIns->bindValue(":sp_id", $spId, PDO::PARAM_INT);
                        $cmdIns->execute();
                    }
                }
            }
            return true;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Lấy danh sách ID sản phẩm đang được gán cho mốc BMI
    public function laysanphamidsbycauhinh($cauhinh_bmi_id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT sanpham_id FROM cauhinh_bmi_sanpham WHERE cauhinh_bmi_id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$cauhinh_bmi_id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Lấy chi tiết thông tin các sản phẩm gợi ý cho mốc BMI (tuân thủ quy tắc s.trangthai = 1 và d.trangthai = 1)
    public function laysanphamchitietbycauhinh($cauhinh_bmi_id, $limit = 0)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT s.*, d.tendanhmuc, d.loai, t.tenthuonghieu
                    FROM cauhinh_bmi_sanpham bs
                    JOIN sanpham s ON s.id = bs.sanpham_id
                    JOIN danhmuc d ON d.id = s.danhmuc_id
                    LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
                    WHERE bs.cauhinh_bmi_id = :id AND s.trangthai = 1 AND d.trangthai = 1
                    ORDER BY bs.uutien DESC, s.noibat DESC, s.luotmua DESC";
            if ($limit > 0) {
                $sql .= " LIMIT " . (int)$limit;
            }
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", (int)$cauhinh_bmi_id, PDO::PARAM_INT);
            $cmd->execute();
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Lấy tất cả sản phẩm đang hoạt động, nhóm theo 3 nhóm lớn: quanao, phukien, tpbs (phục vụ Product Picker)
    public function laysanphamtheoloai_active()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT s.id, s.tensanpham, s.giaban, s.giagoc, s.hinhanh, s.muctieu, s.doituong, d.loai, d.tendanhmuc, t.tenthuonghieu
                    FROM sanpham s
                    JOIN danhmuc d ON d.id = s.danhmuc_id
                    LEFT JOIN thuonghieu t ON t.id = s.thuonghieu_id
                    WHERE s.trangthai = 1 AND d.trangthai = 1
                    ORDER BY d.loai ASC, s.noibat DESC, s.tensanpham ASC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            $ds = $cmd->fetchAll(PDO::FETCH_ASSOC);

            $ketqua = [
                'quanao'  => [],
                'phukien' => [],
                'tpbs'    => []
            ];

            foreach ($ds as $sp) {
                $loai = $sp['loai'] ?? 'quanao';
                if (!isset($ketqua[$loai])) {
                    $ketqua[$loai] = [];
                }
                $ketqua[$loai][] = $sp;
            }
            return $ketqua;
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Helper: Tính chỉ số BMI từ chiều cao (cm) và cân nặng (kg)
    public static function tinhBmi($chieucao, $cannang)
    {
        $m = (float)$chieucao / 100;
        if ($m <= 0) return 0;
        return round((float)$cannang / ($m * $m), 1);
    }

    // Helper: Tên thân thiện cho mục tiêu
    public static function layTenMuctieu($muctieu)
    {
        $mang = [
            'tangcan' => 'Tăng cân / Tăng cơ',
            'giamcan' => 'Giảm mỡ / Giảm cân',
            'duytri'  => 'Duy trì vóc dáng'
        ];
        return $mang[$muctieu] ?? 'Duy trì vóc dáng';
    }

    // Helper: Màu sắc & Icon đại diện cho mục tiêu
    public static function layBadgeMuctieu($muctieu)
    {
        switch ($muctieu) {
            case 'tangcan':
                return [
                    'class' => 'badge bg-primary-subtle text-primary border border-primary-subtle',
                    'icon'  => 'fa-dumbbell',
                    'ten'   => 'Tăng cân / Tăng cơ'
                ];
            case 'giamcan':
                return [
                    'class' => 'badge bg-danger-subtle text-danger border border-danger-subtle',
                    'icon'  => 'fa-fire',
                    'ten'   => 'Giảm mỡ / Giảm cân'
                ];
            default:
                return [
                    'class' => 'badge bg-success-subtle text-success border border-success-subtle',
                    'icon'  => 'fa-heart-pulse',
                    'ten'   => 'Duy trì vóc dáng'
                ];
        }
    }
}
?>
