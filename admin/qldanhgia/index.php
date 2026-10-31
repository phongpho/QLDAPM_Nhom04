<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Phân quyền: Cả Admin (1) và Nhân viên (2) đều được truy cập
if (!isset($_SESSION["nguoidung"])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
} elseif (!in_array((int)$_SESSION["nguoidung"]["loai"], [1, 2])) {
    echo "<script>alert('Bạn không có quyền truy cập vào chức năng Quản lý đánh giá!'); window.location.href='../ktnguoidung/index.php';</script>";
    exit();
}

$isAdmin = ((int)$_SESSION["nguoidung"]["loai"] === 1);

require_once("../../model/database.php");
require_once("../../model/danhgia.php");

$dg = new DANHGIA();
$action = $_REQUEST["action"] ?? "macdinh";

switch ($action) {
    case "macdinh":
        $danhgias = $dg->laydanhsachdanhgiaadmin();
        $thongke = $dg->thongkedanhgia();

        // Lọc theo số sao, trạng thái & từ khóa
        $filter_sao = (int)($_GET["sao"] ?? 0);
        $filter_trangthai = $_GET["trangthai"] ?? "";
        $filter_tukhoa = trim($_GET["tukhoa"] ?? "");

        if ($filter_sao > 0 || $filter_trangthai !== "" || !empty($filter_tukhoa)) {
            $danhgias = array_filter($danhgias, function ($item) use ($filter_sao, $filter_trangthai, $filter_tukhoa) {
                if ($filter_sao > 0 && (int)$item["sao"] !== $filter_sao) {
                    return false;
                }
                if ($filter_trangthai !== "" && (int)$item["trangthai"] !== (int)$filter_trangthai) {
                    return false;
                }
                if (!empty($filter_tukhoa)) {
                    $tukhoa = mb_strtolower($filter_tukhoa, 'UTF-8');
                    $tenKh = mb_strtolower($item["ten_khachhang"] ?? '', 'UTF-8');
                    $tenSp = mb_strtolower($item["tensanpham"] ?? '', 'UTF-8');
                    $nd = mb_strtolower($item["noidung"] ?? '', 'UTF-8');
                    if (mb_strpos($tenKh, $tukhoa) === false && 
                        mb_strpos($tenSp, $tukhoa) === false && 
                        mb_strpos($nd, $tukhoa) === false) {
                        return false;
                    }
                }
                return true;
            });
        }

        include("main.php");
        break;

    case "doitrangthai":
        $id = (int)($_GET["id"] ?? 0);
        $tt = (int)($_GET["tt"] ?? 0);
        $moi = ($tt === 1) ? 0 : 1;

        if ($id > 0) {
            $dg->doitrangthai($id, $moi);
            $msg = ($moi === 1) ? "Đã duyệt và hiển thị đánh giá lên trang sản phẩm!" : "Đã tạm ẩn đánh giá này khỏi website!";
            $_SESSION["thongbao"] = ["loai" => "info", "noidung" => $msg];
        }
        header("Location: index.php");
        exit();

    case "xoa":
        if (!$isAdmin) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Chỉ Quản trị viên cấp cao mới có quyền xóa đánh giá!"];
            header("Location: index.php");
            exit();
        }

        $id = (int)($_GET["id"] ?? 0);
        if ($id > 0) {
            $res = $dg->xoadanhgia($id);
            if ($res) {
                $_SESSION["thongbao"] = ["loai" => "success", "noidung" => "Đã xóa đánh giá vĩnh viễn!"];
            } else {
                $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Lỗi trong quá trình xóa dữ liệu!"];
            }
        }
        header("Location: index.php");
        exit();

    default:
        header("Location: index.php");
        exit();
}
