<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Phân quyền: Cả Admin (1) và Nhân viên (2) đều được vào trang Quản lý danh mục
if (!isset($_SESSION["nguoidung"])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
} elseif (!in_array($_SESSION["nguoidung"]["loai"], [1, 2])) {
    echo "<script>alert('Bạn không có quyền truy cập vào chức năng Quản lý danh mục!'); window.location.href='../ktnguoidung/index.php';</script>";
    exit();
}

require_once("../../model/database.php");
require_once("../../model/danhmuc.php");

$dm = new DANHMUC();

// 2. Xác định action
$action = $_REQUEST["action"] ?? "macdinh";

// 3. Điều hướng xử lý theo action
switch ($action) {
    case "macdinh":
        $danhmuc = $dm->laydanhmuc();

        // Thống kê nhanh
        $tongDanhmuc = count($danhmuc);
        $demQuanao = 0;
        $demPhukien = 0;
        $demTpbs = 0;
        $demHien = 0;
        $demAn = 0;

        foreach ($danhmuc as $d) {
            if ($d["loai"] === "quanao") $demQuanao++;
            elseif ($d["loai"] === "phukien") $demPhukien++;
            elseif ($d["loai"] === "tpbs") $demTpbs++;

            if ((int)$d["trangthai"] === 1) $demHien++;
            else $demAn++;
        }

        // Hỗ trợ lọc theo loại & trạng thái nếu có
        $filter_loai = $_GET["loai"] ?? "";
        $filter_trangthai = $_GET["trangthai"] ?? "";
        $filter_tukhoa = trim($_GET["tukhoa"] ?? "");

        if (!empty($filter_loai) || $filter_trangthai !== "" || !empty($filter_tukhoa)) {
            $danhmuc = array_filter($danhmuc, function ($item) use ($filter_loai, $filter_trangthai, $filter_tukhoa) {
                if (!empty($filter_loai) && $item["loai"] !== $filter_loai) {
                    return false;
                }
                if ($filter_trangthai !== "" && (int)$item["trangthai"] !== (int)$filter_trangthai) {
                    return false;
                }
                if (!empty($filter_tukhoa)) {
                    $tukhoa = mb_strtolower($filter_tukhoa, 'UTF-8');
                    $ten = mb_strtolower($item["tendanhmuc"], 'UTF-8');
                    if (strpos($ten, $tukhoa) === false) {
                        return false;
                    }
                }
                return true;
            });
        }

        include("main.php");
        break;

    case "xlthem":
        $tendanhmuc = trim($_POST["txtten"] ?? "");
        $loai = $_POST["optloai"] ?? "quanao";
        $trangthai = isset($_POST["opttrangthai"]) ? (int)$_POST["opttrangthai"] : 1;

        if (empty($tendanhmuc)) {
            $_SESSION["loi"] = "Vui lòng nhập tên danh mục!";
        } elseif ($dm->kiemtratrungten($tendanhmuc)) {
            $_SESSION["loi"] = "Danh mục \"" . htmlspecialchars($tendanhmuc) . "\" đã tồn tại trong hệ thống!";
        } else {
            $dm->themdanhmuc($tendanhmuc, $loai, $trangthai);
            $_SESSION["thongbao"] = "Thêm danh mục mới thành công!";
        }
        header("Location: index.php");
        exit();

    case "sua":
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            $d = $dm->laydanhmuctheoid($id);
            if ($d) {
                $soLuongSP = $dm->laysoluongsanphamtheodanhmuc($id);
                include("sua.php");
            } else {
                $_SESSION["loi"] = "Không tìm thấy danh mục yêu cầu!";
                header("Location: index.php");
                exit();
            }
        } else {
            header("Location: index.php");
            exit();
        }
        break;

    case "xlsua":
        $id = (int)($_POST["txtid"] ?? 0);
        $tendanhmuc = trim($_POST["txtten"] ?? "");
        $loai = $_POST["optloai"] ?? "quanao";
        $trangthai = isset($_POST["opttrangthai"]) ? (int)$_POST["opttrangthai"] : 1;

        if ($id <= 0) {
            $_SESSION["loi"] = "Mã danh mục không hợp lệ!";
        } elseif (empty($tendanhmuc)) {
            $_SESSION["loi"] = "Tên danh mục không được để trống!";
        } elseif ($dm->kiemtratrungten($tendanhmuc, $id)) {
            $_SESSION["loi"] = "Tên danh mục \"" . htmlspecialchars($tendanhmuc) . "\" đã trùng với danh mục khác!";
        } else {
            $dm->suadanhmuc($id, $tendanhmuc, $loai, $trangthai);
            $_SESSION["thongbao"] = "Cập nhật danh mục thành công!";
        }
        header("Location: index.php");
        exit();

    case "doitrangthai":
        // Cả Admin (1) và Nhân viên (2) đều được phép Bật / Tắt Ẩn - Hiện
        if (isset($_GET["id"]) && isset($_GET["trangthai"])) {
            $id = (int)$_GET["id"];
            $trangthai = (int)$_GET["trangthai"];
            $dm->doitrangthai($id, $trangthai);
            if ($trangthai === 1) {
                $_SESSION["thongbao"] = "Đã hiển thị lại danh mục trên website!";
            } else {
                $_SESSION["thongbao"] = "Đã ẩn danh mục khỏi website thành công!";
            }
        }
        header("Location: index.php");
        exit();

    case "xoa":
        // CHỈ DUY NHẤT ADMIN (loai == 1) MỚI ĐƯỢC PHÉP XÓA
        if ($_SESSION["nguoidung"]["loai"] != 1) {
            $_SESSION["loi"] = "Bạn không có quyền xóa danh mục! Nhân viên chỉ được phép Ẩn/Hiện danh mục.";
            header("Location: index.php");
            exit();
        }

        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            // Kiểm tra số lượng sản phẩm liên kết trước khi xóa
            $soLuongSP = $dm->laysoluongsanphamtheodanhmuc($id);
            if ($soLuongSP > 0) {
                $_SESSION["loi"] = "Không thể xóa! Danh mục này đang chứa " . $soLuongSP . " sản phẩm. Vui lòng chọn ẨN danh mục để bảo toàn dữ liệu hàng hóa & đơn hàng.";
            } else {
                $dm->xoadanhmuc($id);
                $_SESSION["thongbao"] = "Đã xóa danh mục thành công!";
            }
        }
        header("Location: index.php");
        exit();

    default:
        header("Location: index.php?action=macdinh");
        exit();
}
?>
