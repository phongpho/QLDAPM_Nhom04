<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Phân quyền: Cả Admin (1) và Nhân viên (2) đều được truy cập
if (!isset($_SESSION["nguoidung"])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
} elseif (!in_array((int)$_SESSION["nguoidung"]["loai"], [1, 2])) {
    echo "<script>alert('Bạn không có quyền truy cập vào chức năng Quản lý bài viết & tin tức!'); window.location.href='../ktnguoidung/index.php';</script>";
    exit();
}

$isAdmin = ((int)$_SESSION["nguoidung"]["loai"] === 1);

require_once("../../model/database.php");
require_once("../../model/tintuc.php");

$ttModel = new TINTUC();
$action = $_REQUEST["action"] ?? "macdinh";

switch ($action) {
    case "macdinh":
        $tintucs = $ttModel->laydanhsachtintucadmin();
        $thongke = $ttModel->thongketintuc();

        // Lọc theo loại, trạng thái & từ khóa
        $filter_loai = $_GET["loai"] ?? "";
        $filter_trangthai = $_GET["trangthai"] ?? "";
        $filter_tukhoa = trim($_GET["tukhoa"] ?? "");

        if (!empty($filter_loai) || $filter_trangthai !== "" || !empty($filter_tukhoa)) {
            $tintucs = array_filter($tintucs, function ($item) use ($filter_loai, $filter_trangthai, $filter_tukhoa) {
                if (!empty($filter_loai) && $item["loai"] !== $filter_loai) {
                    return false;
                }
                if ($filter_trangthai !== "" && (int)$item["trangthai"] !== (int)$filter_trangthai) {
                    return false;
                }
                if (!empty($filter_tukhoa)) {
                    $tukhoa = mb_strtolower($filter_tukhoa, 'UTF-8');
                    $td = mb_strtolower($item["tieude"], 'UTF-8');
                    $nd = mb_strtolower($item["noidung"], 'UTF-8');
                    if (mb_strpos($td, $tukhoa) === false && mb_strpos($nd, $tukhoa) === false) {
                        return false;
                    }
                }
                return true;
            });
        }

        include("main.php");
        break;

    case "xulythem":
        $tieude = trim($_POST["tieude"] ?? "");
        $loai = in_array($_POST["loai"] ?? '', ['tintuc', 'sukien', 'khuyenmai']) ? $_POST["loai"] : 'tintuc';
        $noidung = trim($_POST["noidung"] ?? "");
        $trangthai = isset($_POST["trangthai"]) ? (int)$_POST["trangthai"] : 1;

        if (empty($tieude) || empty($noidung)) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Vui lòng nhập đầy đủ tiêu đề và nội dung bài viết!"];
            header("Location: index.php");
            exit();
        }

        $hinhanh = null;
        $fileUpload = $_FILES["fhinhanh"] ?? null;
        if ($fileUpload && $fileUpload["error"] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($fileUpload["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $hinhanh = "news_" . time() . "_" . uniqid() . "." . $ext;
                $uploadDir = "../../assets/images/products/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                move_uploaded_file($fileUpload["tmp_name"], $uploadDir . $hinhanh);
            }
        }

        $res = $ttModel->themtintuc($tieude, $noidung, $hinhanh, $loai, $trangthai);
        if ($res) {
            $_SESSION["thongbao"] = ["loai" => "success", "noidung" => "Đăng bài viết mới thành công!"];
        } else {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Không thể lưu bài viết. Vui lòng thử lại!"];
        }
        header("Location: index.php");
        exit();

    case "xulysua":
        $id = (int)($_POST["id"] ?? 0);
        $tieude = trim($_POST["tieude"] ?? "");
        $loai = in_array($_POST["loai"] ?? '', ['tintuc', 'sukien', 'khuyenmai']) ? $_POST["loai"] : 'tintuc';
        $noidung = trim($_POST["noidung"] ?? "");
        $trangthai = isset($_POST["trangthai"]) ? (int)$_POST["trangthai"] : 1;

        if ($id <= 0 || empty($tieude) || empty($noidung)) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Dữ liệu cập nhật bài viết không hợp lệ!"];
            header("Location: index.php");
            exit();
        }

        $hinhanh = null;
        $fileUpload = $_FILES["fhinhanh"] ?? null;
        if ($fileUpload && $fileUpload["error"] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($fileUpload["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $hinhanh = "news_" . time() . "_" . uniqid() . "." . $ext;
                $uploadDir = "../../assets/images/products/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                move_uploaded_file($fileUpload["tmp_name"], $uploadDir . $hinhanh);
            }
        }

        $res = $ttModel->capnhattintuc($id, $tieude, $noidung, $hinhanh, $loai, $trangthai);
        if ($res) {
            $_SESSION["thongbao"] = ["loai" => "success", "noidung" => "Cập nhật bài viết thành công!"];
        } else {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Cập nhật bài viết thất bại!"];
        }
        header("Location: index.php");
        exit();

    case "doitrangthai":
        $id = (int)($_GET["id"] ?? 0);
        $tt = (int)($_GET["tt"] ?? 0);
        $moi = ($tt === 1) ? 0 : 1;

        if ($id > 0) {
            $ttModel->doitrangthai($id, $moi);
            $msg = ($moi === 1) ? "Đã công khai hiển thị bài viết!" : "Đã chuyển bài viết về dạng ẩn/nháp!";
            $_SESSION["thongbao"] = ["loai" => "info", "noidung" => $msg];
        }
        header("Location: index.php");
        exit();

    case "xoa":
        if (!$isAdmin) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Chỉ Quản trị viên cấp cao mới có quyền xóa bài viết!"];
            header("Location: index.php");
            exit();
        }

        $id = (int)($_GET["id"] ?? 0);
        if ($id > 0) {
            $res = $ttModel->xoatintuc($id);
            if ($res) {
                $_SESSION["thongbao"] = ["loai" => "success", "noidung" => "Đã xóa bài viết vĩnh viễn!"];
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

