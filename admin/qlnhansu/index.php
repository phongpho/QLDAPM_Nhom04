<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Kiểm tra đăng nhập và phân quyền: Chỉ duy nhất Admin (loai == 1) mới được vào
if (!isset($_SESSION["nguoidung"])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
} elseif ($_SESSION["nguoidung"]["loai"] != 1) {
    // Không phải admin thì chặn truy cập
    echo "<script>alert('Bạn không có quyền truy cập vào chức năng Quản lý nhân sự!'); window.location.href='../ktnguoidung/index.php';</script>";
    exit();
}

require("../../model/database.php");
require("../../model/nguoidung.php");

$nd = new NGUOIDUNG();

// 2. Xác định action
if (isset($_REQUEST["action"])) {
    $action = $_REQUEST["action"];
} else {
    $action = "macdinh";
}

// 3. Điều hướng xử lý theo action
switch ($action) {
    case "macdinh":
        // Lấy danh sách tất cả người dùng (Admin, Nhân viên, Khách)
        $nguoidung = $nd->laydanhsachnguoidung();
        include("main.php");
        break;

    case "them":
        include("them.php");
        break;

    case "xlthem":
        $email = trim($_POST["txtemail"]);
        $matkhau = $_POST["txtmatkhau"];
        $sodt = trim($_POST["txtdienthoai"]);
        $hoten = trim($_POST["txthoten"]);
        $loai = (int)$_POST["optloai"]; // 1: Admin, 2: Nhân viên, 3: Khách

        // Kiểm tra trùng email hoặc số điện thoại
        if ($nd->kiemtratrung($email, $sodt)) {
            $msg_error = "Email hoặc Số điện thoại đã tồn tại trong hệ thống!";
            include("them.php");
        } else {
            $nd->themnguoidung($email, $matkhau, $sodt, $hoten, $loai);
            header("Location: index.php");
            exit();
        }
        break;

    case "khoa":
        // Khóa hoặc Mở khóa tài khoản
        if (isset($_GET["id"]) && isset($_GET["trangthai"])) {
            $id = (int)$_GET["id"];
            $trangthai = (int)$_GET["trangthai"]; // 1: Kích hoạt, 0: Khóa

            // Không cho phép tự khóa chính tài khoản admin đang đăng nhập
            if ($id != $_SESSION["nguoidung"]["id"]) {
                $nd->doitrangthai($id, $trangthai);
            }
        }
        header("Location: index.php");
        exit();

    case "doiloai":
        // Thay đổi quyền (Admin <-> Nhân viên <-> Khách)
        if (isset($_POST["email"]) && isset($_POST["loai"])) {
            $email = $_POST["email"];
            $loai = (int)$_POST["loai"];
            $nd->doiloainguoidung($email, $loai);
        }
        header("Location: index.php");
        exit();

    default:
        $nguoidung = $nd->laydanhsachnguoidung();
        include("main.php");
        break;
}
?>
