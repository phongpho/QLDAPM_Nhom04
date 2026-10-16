<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Phân quyền: Cả Admin (1) và Nhân viên (2) đều được quản lý hàng hóa / sản phẩm
if (!isset($_SESSION["nguoidung"])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
} elseif (!in_array($_SESSION["nguoidung"]["loai"], [1, 2])) {
    echo "<script>alert('Bạn không có quyền truy cập vào chức năng Quản lý sản phẩm!'); window.location.href='../ktnguoidung/index.php';</script>";
    exit();
}

require("../../model/database.php");
require("../../model/sanpham.php");
require("../../model/danhmuc.php");

$sp = new SANPHAM();
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
        $sanpham = $sp->laysanpham();
        include("main.php");
        break;

    case "them":
        $danhmuc = $dm->laydanhmuc();
        include("them.php");
        break;

    case "chitiet":
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            $s = $sp->laysanphamtheoid($id);
            include("chitiet.php");
        } else {
            header("Location: index.php");
        }
        break;

    case "sua":
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            $s = $sp->laysanphamtheoid($id);
            $danhmuc = $dm->laydanhmuc();
            include("sua.php");
        } else {
            header("Location: index.php");
        }
        break;

    default:
        $sanpham = $sp->laysanpham();
        include("main.php");
        break;
}
?>
