<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Phân quyền: Cả Admin (1) và Nhân viên (2) đều được truy cập
if (!isset($_SESSION["nguoidung"])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
} elseif (!in_array((int)$_SESSION["nguoidung"]["loai"], [1, 2])) {
    echo "<script>alert('Bạn không có quyền truy cập vào chức năng Quản lý thương hiệu!'); window.location.href='../ktnguoidung/index.php';</script>";
    exit();
}

$isAdmin = ((int)$_SESSION["nguoidung"]["loai"] === 1);

require_once("../../model/database.php");
require_once("../../model/thuonghieu.php");

$th = new THUONGHIEU();
$action = $_REQUEST["action"] ?? "macdinh";

switch ($action) {
    case "macdinh":
        $thuonghieus = $th->laydanhsachthuonghieu();
        $thongke = $th->thongkethuonghieu();

        // Lọc tìm kiếm & trạng thái nếu có
        $filter_trangthai = $_GET["trangthai"] ?? "";
        $filter_tukhoa = trim($_GET["tukhoa"] ?? "");

        if ($filter_trangthai !== "" || !empty($filter_tukhoa)) {
            $thuonghieus = array_filter($thuonghieus, function ($item) use ($filter_trangthai, $filter_tukhoa) {
                if ($filter_trangthai !== "" && (int)$item["trangthai"] !== (int)$filter_trangthai) {
                    return false;
                }
                if (!empty($filter_tukhoa)) {
                    $tukhoa = mb_strtolower($filter_tukhoa, 'UTF-8');
                    $ten = mb_strtolower($item["tenthuonghieu"], 'UTF-8');
                    if (mb_strpos($ten, $tukhoa) === false) {
                        return false;
                    }
                }
                return true;
            });
        }

        include("main.php");
        break;

    case "xulythem":
        $tenthuonghieu = trim($_POST["tenthuonghieu"] ?? "");
        $trangthai = isset($_POST["trangthai"]) ? (int)$_POST["trangthai"] : 1;

        if (empty($tenthuonghieu)) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Vui lòng nhập tên thương hiệu!"];
            header("Location: index.php");
            exit();
        }

        if ($th->kiemtratrungten($tenthuonghieu)) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Tên thương hiệu \"$tenthuonghieu\" đã tồn tại trên hệ thống!"];
            header("Location: index.php");
            exit();
        }

        $logo = null;
        $fileUpload = $_FILES["flogo"] ?? null;
        if ($fileUpload && $fileUpload["error"] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($fileUpload["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            if (in_array($ext, $allowed)) {
                $logo = "brand_" . time() . "_" . uniqid() . "." . $ext;
                $uploadDir = "../../assets/images/products/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                move_uploaded_file($fileUpload["tmp_name"], $uploadDir . $logo);
            }
        }

        $res = $th->themthuonghieu($tenthuonghieu, $logo, $trangthai);
        if ($res) {
            $_SESSION["thongbao"] = ["loai" => "success", "noidung" => "Thêm thương hiệu mới thành công!"];
        } else {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Không thể thêm thương hiệu. Vui lòng kiểm tra lại!"];
        }
        header("Location: index.php");
        exit();

    case "xulysua":
        $id = (int)($_POST["id"] ?? 0);
        $tenthuonghieu = trim($_POST["tenthuonghieu"] ?? "");
        $trangthai = isset($_POST["trangthai"]) ? (int)$_POST["trangthai"] : 1;

        if ($id <= 0 || empty($tenthuonghieu)) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Dữ liệu cập nhật không hợp lệ!"];
            header("Location: index.php");
            exit();
        }

        if ($th->kiemtratrungten($tenthuonghieu, $id)) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Tên thương hiệu \"$tenthuonghieu\" đã trùng với một thương hiệu khác!"];
            header("Location: index.php");
            exit();
        }

        $logo = null;
        $fileUpload = $_FILES["flogo"] ?? null;
        if ($fileUpload && $fileUpload["error"] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($fileUpload["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            if (in_array($ext, $allowed)) {
                $logo = "brand_" . time() . "_" . uniqid() . "." . $ext;
                $uploadDir = "../../assets/images/products/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                move_uploaded_file($fileUpload["tmp_name"], $uploadDir . $logo);
            }
        }

        $res = $th->capnhatthuonghieu($id, $tenthuonghieu, $logo, $trangthai);
        if ($res) {
            $_SESSION["thongbao"] = ["loai" => "success", "noidung" => "Cập nhật thương hiệu thành công!"];
        } else {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Cập nhật thương hiệu thất bại!"];
        }
        header("Location: index.php");
        exit();

    case "doitrangthai":
        $id = (int)($_GET["id"] ?? 0);
        $tt = (int)($_GET["tt"] ?? 0);
        $moi = ($tt === 1) ? 0 : 1;

        if ($id > 0) {
            $th->doitrangthai($id, $moi);
            $msg = ($moi === 1) ? "Đã hiển thị thương hiệu!" : "Đã tạm ẩn thương hiệu!";
            $_SESSION["thongbao"] = ["loai" => "info", "noidung" => $msg];
        }
        header("Location: index.php");
        exit();

    case "xoa":
        // Chỉ admin cấp 1 được phép xóa
        if (!$isAdmin) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Chỉ Quản trị viên cấp cao mới có quyền xóa thương hiệu!"];
            header("Location: index.php");
            exit();
        }

        $id = (int)($_GET["id"] ?? 0);
        if ($id > 0) {
            if ($th->dacosanpham($id)) {
                $_SESSION["thongbao"] = [
                    "loai" => "warning", 
                    "noidung" => "Không thể xóa thương hiệu này vì đã có sản phẩm liên kết! Bạn có thể chuyển sang trạng thái \"Ẩn\"."
                ];
            } else {
                $res = $th->xoathuonghieu($id);
                if ($res) {
                    $_SESSION["thongbao"] = ["loai" => "success", "noidung" => "Đã xóa thương hiệu vĩnh viễn!"];
                } else {
                    $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Lỗi trong quá trình xóa dữ liệu!"];
                }
            }
        }
        header("Location: index.php");
        exit();

    default:
        header("Location: index.php");
        exit();
}
