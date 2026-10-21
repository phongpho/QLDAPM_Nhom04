<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require("../../model/database.php");
require("../../model/nguoidung.php");
$isLogin = isset($_SESSION["nguoidung"]);
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
        if (!isset($_SESSION["nguoidung"])) {
            header("Location: index.php?action=dangnhap");
            exit();
        }

        $mand = (int)$_POST["txtid"];
        $email = trim($_POST["txtemail"]);
        $sodt = trim($_POST["txtdienthoai"]);
        $hoten = trim($_POST["txthoten"]);
        $hinhanhcu = $_POST["txthinhanh"] ?? "";
        $hinhanh = $hinhanhcu;

        if (empty($email) || empty($sodt) || empty($hoten)) {
            $msg_error = "Vui lòng nhập đầy đủ các thông tin bắt buộc!";
            include("hoso.php");
            break;
        }

        if ($nd->kiemtratrungkhisua($mand, $email, $sodt)) {
            $msg_error = "Email hoặc Số điện thoại đã được sử dụng bởi một tài khoản khác!";
            include("hoso.php");
            break;
        }

        $fileUpload = $_FILES["fhinh"] ?? null;
        if ($fileUpload && $fileUpload["error"] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($fileUpload["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $prefix = ($_SESSION["nguoidung"]["loai"] == 1) ? "admin_" : "nv_";
                $hinhanh = $prefix . time() . "_" . uniqid() . "." . $ext;
                $uploadDir = "../../assets/images/users/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                if (move_uploaded_file($fileUpload["tmp_name"], $uploadDir . $hinhanh)) {
                    if (!empty($hinhanhcu) && !in_array($hinhanhcu, ['user.png', 'image.png', 'admin.png'])) {
                        $oldFile = $uploadDir . $hinhanhcu;
                        if (file_exists($oldFile)) {
                            @unlink($oldFile);
                        }
                    }
                }
            } else {
                $msg_error = "Định dạng file không hợp lệ! Vui lòng chỉ chọn ảnh JPG, PNG, GIF hoặc WEBP.";
                include("hoso.php");
                break;
            }
        }

        // 4. Cập nhật thông tin vào CSDL
        $nd->capnhatnguoidung($mand, $email, $sodt, $hoten, $hinhanh);

        // 5. Cập nhật lại Session và hiển thị lại trang hồ sơ kèm thông báo thành công
        $_SESSION["nguoidung"] = $nd->laythongtinnguoidung($email);
        $msg_success = "Cập nhật thông tin hồ sơ thành công!";
        include("hoso.php");
        break;
    case "matkhau":
        include("doimatkhau.php");
        break;
    case "xldoimatkhau":
        if (!isset($_SESSION["nguoidung"])) {
            header("Location: index.php?action=dangnhap");
            exit();
        }
        $email = $_SESSION["nguoidung"]["email"];
        $matkhaucu = trim($_POST["txtmatkhaucu"] ?? "");
        $matkhaumoi = trim($_POST["txtmatkhaumoi"] ?? "");
        $xacnhanmatkhau = trim($_POST["txtxacnhanmatkhau"] ?? "");

        if (empty($matkhaucu) || empty($matkhaumoi) || empty($xacnhanmatkhau)) {
            $msg_error = "Vui lòng nhập đầy đủ các thông tin!";
            include("doimatkhau.php");
        } elseif ($nd->kiemtranguoidunghople($email, $matkhaucu) == FALSE) {
            $msg_error = "Mật khẩu hiện tại không chính xác!";
            include("doimatkhau.php");
        } elseif (strlen($matkhaumoi) < 6) {
            $msg_error = "Mật khẩu mới phải có tối thiểu 6 ký tự!";
            include("doimatkhau.php");
        } elseif ($matkhaucu === $matkhaumoi) {
            $msg_error = "Mật khẩu mới không được trùng với mật khẩu hiện tại!";
            include("doimatkhau.php");
        } elseif ($matkhaumoi !== $xacnhanmatkhau) {
            $msg_error = "Mật khẩu mới và mật khẩu xác nhận không trùng khớp!";
            include("doimatkhau.php");
        } else {
            $nd->doimatkhau($email, $matkhaumoi);
            $msg_success = "Đổi mật khẩu thành công!";
            include("doimatkhau.php");
        }
        break;
    default:
        break;
}
