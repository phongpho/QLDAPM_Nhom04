<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Phân quyền: Cả Admin (1) và Nhân viên (2) đều được quản lý danh mục
if (!isset($_SESSION["nguoidung"])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
} elseif (!in_array($_SESSION["nguoidung"]["loai"], [1, 2])) {
    echo "<script>alert('Bạn không có quyền truy cập vào chức năng Quản lý danh mục!'); window.location.href='../ktnguoidung/index.php';</script>";
    exit();
}

require("../../model/database.php");
require("../../model/danhmuc.php");

$dm = new DANHMUC();

// 2. Xác định action
if (isset($_REQUEST["action"])) {
    $action = $_REQUEST["action"];
} else {
    $action = "macdinh";
}

// 3. Điều hướng xử lý theo action
switch ($action) {
    case "macdinh":
        $danhmuc = $dm->laydanhmuc();
        include("main.php");
        break;

    case "them":
        include("them.php");
        break;

    case "xlthem":
        $tendanhmuc = trim($_POST["txtten"]);
        $loai = $_POST["optloai"]; // quanao, phukien, tpbs
        if (!empty($tendanhmuc)) {
            $dm->themdanhmuc($tendanhmuc, $loai);
        }
        header("Location: index.php");
        exit();

    case "sua":
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            $d = $dm->laydanhmuctheoid($id);
            include("sua.php");
        } else {
            header("Location: index.php");
        }
        break;

    case "xlsua":
        $id = (int)$_POST["txtid"];
        $tendanhmuc = trim($_POST["txtten"]);
        $loai = $_POST["optloai"];
        if (!empty($tendanhmuc)) {
            $dm->suadanhmuc($id, $tendanhmuc, $loai);
        }
        header("Location: index.php");
        exit();

    case "xoa":
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            $dm->xoadanhmuc($id);
        }
        header("Location: index.php");
        exit();

    default:
        $danhmuc = $dm->laydanhmuc();
        include("main.php");
        break;
}
?>
