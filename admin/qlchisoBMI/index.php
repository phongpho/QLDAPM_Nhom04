<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Phân quyền: CHỈ DUY NHẤT QUẢN TRỊ VIÊN (Admin - loai == 1) MỚI ĐƯỢC PHÉP TRUY CẬP
if (!isset($_SESSION["nguoidung"])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
} elseif ((int)$_SESSION["nguoidung"]["loai"] !== 1) {
    echo "<script>alert('Bạn không có quyền truy cập vào chức năng Cấu hình chỉ số BMI! Chỉ Quản trị viên (Admin) mới có quyền này.'); window.location.href='../ktnguoidung/index.php';</script>";
    exit();
}

require_once("../../model/database.php");
require_once("../../model/bmi.php");

$bmiModel = new BMI();

// 2. Xác định action
$action = $_REQUEST["action"] ?? "macdinh";

// 3. Điều hướng xử lý
switch ($action) {
    case "macdinh":
        $danhsachAll = $bmiModel->laydanhsachcauhinh();

        // Thống kê nhanh
        $tongMoc = count($danhsachAll);
        $demKichHoat = 0;
        $demTat = 0;
        $tongDoDungGoiy = 0;

        foreach ($danhsachAll as $m) {
            if ((int)$m["trangthai"] === 1) {
                $demKichHoat++;
            } else {
                $demTat++;
            }
            $tongDoDungGoiy += (int)$m["soluong_sanpham"];
        }

        // Lọc từ khóa tìm kiếm nếu có
        $filter_tukhoa = trim($_GET["tukhoa"] ?? "");
        $danhsach = array_filter($danhsachAll, function($item) use ($filter_tukhoa) {
            if (!empty($filter_tukhoa)) {
                $kw = mb_strtolower($filter_tukhoa, 'UTF-8');
                $phanloai = mb_strtolower($item["phanloai"] ?? "", 'UTF-8');
                $khuyenghe = mb_strtolower($item["khuyenghe"] ?? "", 'UTF-8');
                $muctieu = mb_strtolower($item["muctieu_khuyendung"] ?? "", 'UTF-8');
                if (strpos($phanloai, $kw) === false && strpos($khuyenghe, $kw) === false && strpos($muctieu, $kw) === false) {
                    return false;
                }
            }
            return true;
        });

        // Lấy trước sản phẩm gợi ý mẫu cho từng mốc để preview
        $sanphamPreview = [];
        foreach ($danhsach as $m) {
            $sanphamPreview[$m["id"]] = $bmiModel->laysanphamchitietbycauhinh($m["id"], 6);
        }

        include("main.php");
        break;

    case "xlthem":
        $phanloai = trim($_POST["txtphanloai"] ?? "");
        $bmi_min = (float)($_POST["txtmin"] ?? 0);
        $bmi_max = (float)($_POST["txtmax"] ?? 0);
        $muctieu = $_POST["optmuctieu"] ?? "duytri";
        $khuyenghe = trim($_POST["txtkhuyenghe"] ?? "");
        $trangthai = isset($_POST["opttrangthai"]) ? (int)$_POST["opttrangthai"] : 1;
        $thutu = isset($_POST["txtthutu"]) ? (int)$_POST["txtthutu"] : 1;
        $sanpham_ids = $_POST["sanpham_ids"] ?? [];

        if (empty($phanloai)) {
            $_SESSION["loi"] = "Vui lòng nhập tên phân loại thể trạng!";
        } elseif ($bmi_min < 0 || $bmi_max <= $bmi_min) {
            $_SESSION["loi"] = "Khoảng chỉ số BMI không hợp lệ! Mức lớn nhất (Max) phải lớn hơn mức nhỏ nhất (Min).";
        } else {
            $bmiModel->themcauhinh($phanloai, $bmi_min, $bmi_max, $muctieu, $khuyenghe, $trangthai, $thutu, $sanpham_ids);
            $_SESSION["thongbao"] = "Thêm mốc cấu hình chỉ số BMI mới thành công!";
        }
        header("Location: index.php");
        exit();

    case "sua":
        $id = (int)($_GET["id"] ?? 0);
        if ($id <= 0) {
            $_SESSION["loi"] = "Mã cấu hình không hợp lệ!";
            header("Location: index.php");
            exit();
        }

        $cauhinh = $bmiModel->laycauhinhbyid($id);
        if (!$cauhinh) {
            $_SESSION["loi"] = "Không tìm thấy mốc cấu hình BMI yêu cầu!";
            header("Location: index.php");
            exit();
        }

        // Lấy danh sách ID các sản phẩm đã được chọn cho mốc này
        $selectedSpIds = $bmiModel->laysanphamidsbycauhinh($id);

        // Lấy toàn bộ sản phẩm đang hoạt động, gom nhóm theo loại để hiển thị bộ chọn sản phẩm
        $dsSanPhamTheoLoai = $bmiModel->laysanphamtheoloai_active();

        include("sua.php");
        break;

    case "xlsua":
        $id = (int)($_POST["txtid"] ?? 0);
        $phanloai = trim($_POST["txtphanloai"] ?? "");
        $bmi_min = (float)($_POST["txtmin"] ?? 0);
        $bmi_max = (float)($_POST["txtmax"] ?? 0);
        $muctieu = $_POST["optmuctieu"] ?? "duytri";
        $khuyenghe = trim($_POST["txtkhuyenghe"] ?? "");
        $trangthai = isset($_POST["opttrangthai"]) ? (int)$_POST["opttrangthai"] : 1;
        $thutu = isset($_POST["txtthutu"]) ? (int)$_POST["txtthutu"] : 1;
        $sanpham_ids = $_POST["sanpham_ids"] ?? [];

        if ($id <= 0) {
            $_SESSION["loi"] = "Mã cấu hình không hợp lệ!";
        } elseif (empty($phanloai)) {
            $_SESSION["loi"] = "Tên phân loại thể trạng không được để trống!";
        } elseif ($bmi_min < 0 || $bmi_max <= $bmi_min) {
            $_SESSION["loi"] = "Khoảng chỉ số BMI không hợp lệ! Mức lớn nhất (Max) phải lớn hơn mức nhỏ nhất (Min).";
        } else {
            $bmiModel->suacauhinh($id, $phanloai, $bmi_min, $bmi_max, $muctieu, $khuyenghe, $trangthai, $thutu, $sanpham_ids);
            $_SESSION["thongbao"] = "Cập nhật cấu hình chỉ số BMI & đồ dùng gợi ý thành công!";
        }
        header("Location: index.php");
        exit();

    case "doitrangthai":
        $id = (int)($_GET["id"] ?? 0);
        $trangthai = (int)($_GET["trangthai"] ?? 1);
        if ($id > 0) {
            $bmiModel->doitrangthai($id, $trangthai);
            $_SESSION["thongbao"] = ($trangthai === 1) ? "Đã kích hoạt mốc chỉ số BMI!" : "Đã tạm tắt mốc chỉ số BMI!";
        }
        header("Location: index.php");
        exit();

    case "xoa":
        $id = (int)($_GET["id"] ?? 0);
        if ($id > 0) {
            $bmiModel->xoacauhinh($id);
            $_SESSION["thongbao"] = "Đã xóa mốc cấu hình chỉ số BMI thành công!";
        }
        header("Location: index.php");
        exit();

    default:
        header("Location: index.php?action=macdinh");
        exit();
}
?>
