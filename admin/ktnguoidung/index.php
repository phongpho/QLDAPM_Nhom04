<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require("../../model/database.php");
require("../../model/nguoidung.php");
// Biến $isLogin cho biết người dùng đăng nhập chưa
$isLogin = isset($_SESSION["nguoidung"]);
// Kiểm tra hành động $action: yêu cầu đăng nhập nếu chưa xác thực
if (isset($_REQUEST["action"])) {
    $action = $_REQUEST["action"];
} elseif ($isLogin == FALSE) {
    $action = "dangnhap";
} else {
    $action = "macdinh";
}
$nd = new NGUOIDUNG();
switch ($action) {
    case "macdinh":
        include("main.php");
        break;
    case "dangnhap":
        include("dangnhap.php");
        break;
    case "xldangnhap":
        $email = $_POST["txtemail"];
        $matkhau = $_POST["txtmatkhau"];

        if ($nd->kiemtranguoidunghople($email, $matkhau) == TRUE) {
            $user = $nd->laythongtinnguoidung($email);
            $_SESSION["nguoidung"] = $user;

            if ($user["loai"] == 3) {
                header("Location: ../../public/index.php");
                exit();
            } else {
                include("main.php");
            }
        } else {
            $thongbao = "Đăng nhập không thành công!";
            include("dangnhap.php");
        }
        break;
    case "dangxuat":
        unset($_SESSION["nguoidung"]);
        include("dangnhap.php");
        break;
    case "hoso":
        include("hoso.php");
        break;
    case "xlhoso":
        $mand = $_POST["txtid"];
        $email = $_POST["txtemail"];
        $sodt = $_POST["txtdienthoai"];
        $hoten = $_POST["txthoten"];
        $hinhanh = $_POST["txthinhanh"];

        if (isset($_FILES["fhinh"]) && $_FILES["fhinh"]["name"] != null) {
            $hinhanh = basename($_FILES["fhinh"]["name"]);
            $duongdan = "../../images/users/" . $hinhanh;
            move_uploaded_file($_FILES["fhinh"]["tmp_name"], $duongdan);
        }

        $nd->capnhatnguoidung($mand, $email, $sodt, $hoten, $hinhanh);
        $_SESSION["nguoidung"] = $nd->laythongtinnguoidung($email);

        include("main.php");
        break;
    case "matkhau":
        include("doimatkhau.php");
        break;
    case "xldoimatkhau":
        $email = $_SESSION["nguoidung"]["email"];
        $matkhaucu = $_POST["txtmatkhaucu"];
        $matkhaumoi = $_POST["txtmatkhaumoi"];
        $xacnhanmatkhau = $_POST["txtxacnhanmatkhau"];

        // kiểm tra lại mk cũ
        if ($nd->kiemtranguoidunghople($email, $matkhaucu) == FALSE) {
            $msg_error = "Mật khẩu hiện tại không chính xác!";
            include("doimatkhau.php");
        }
        // xác nhận mk mới
        elseif ($matkhaumoi !== $xacnhanmatkhau) {
            $msg_error = "Mật khẩu mới và mật khẩu xác nhận không trùng khớp!";
            include("changepassword.php");
        }
        // 3. Tiến hành đổi mật khẩu
        else {
            $nd->doimatkhau($email, $matkhaumoi);
            $msg_success = "Đổi mật khẩu thành công!";
            include("doimatkhau.php");
        }
        break;
    default:
        break;
}
