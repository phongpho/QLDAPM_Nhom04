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
        $sodt = trim($_POST["txtsodt"] ?? $_POST["txtdienthoai"] ?? "");
        $hoten = trim($_POST["txthoten"]);
        $loai = (int)$_POST["optloai"]; // 1: Admin, 2: Nhân viên, 3: Khách

        // Xử lý upload hình ảnh nhân viên với tên file duy nhất (không trùng)
        $hinhanh = null;
        $fileUpload = $_FILES["hinhanh"] ?? $_FILES["fhinh"] ?? null;
        if ($fileUpload && $fileUpload["error"] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($fileUpload["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                // Đặt tên file duy nhất kết hợp tiền tố, thời gian và uniqid
                $hinhanh = "nv_" . time() . "_" . uniqid() . "." . $ext;
                $uploadDir = "../../assets/images/users/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                move_uploaded_file($fileUpload["tmp_name"], $uploadDir . $hinhanh);
            }
        }

        // Kiểm tra trùng email hoặc số điện thoại
        if ($nd->kiemtratrung($email, $sodt)) {
            $msg_error = "Email hoặc Số điện thoại đã tồn tại trong hệ thống!";
            $nguoidung = $nd->laydanhsachnguoidung();
            include("main.php");
        } else {
            $nd->themnguoidung($email, $matkhau, $sodt, $hoten, $loai, $hinhanh);
            header("Location: index.php");
            exit();
        }
        break;

    case "sua":
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            $nhanvien = $nd->laynguoidungtheoid($id);
            if ($nhanvien) {
                include("sua.php");
                break;
            }
        }
        header("Location: index.php");
        exit();

    case "xlsua":
        $id = (int)$_POST["txtid"];
        $email = trim($_POST["txtemail"]);
        $hoten = trim($_POST["txthoten"]);
        $sodt = trim($_POST["txtsodt"]);
        $trangthai = (int)$_POST["opttrangthai"];
        $matkhaumoi = trim($_POST["txtmatkhaumoi"] ?? "");
        $hinhanhcu = $_POST["txthinhanhcu"] ?? "";
        $hinhanh = $hinhanhcu;

        // Kiểm tra trùng email hoặc sđt với tài khoản khác
        if ($nd->kiemtratrungkhisua($id, $email, $sodt)) {
            $msg_error = "Email hoặc Số điện thoại đã tồn tại ở tài khoản khác!";
            $nhanvien = $nd->laynguoidungtheoid($id);
            include("sua.php");
            break;
        }

        // Kiểm tra nếu có upload ảnh mới
        $fileUpload = $_FILES["hinhanh"] ?? null;
        if ($fileUpload && $fileUpload["error"] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($fileUpload["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $hinhanh = "nv_" . time() . "_" . uniqid() . "." . $ext;
                $uploadDir = "../../assets/images/users/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                if (move_uploaded_file($fileUpload["tmp_name"], $uploadDir . $hinhanh)) {
                    // Xóa ảnh cũ nếu là ảnh riêng của nhân viên
                    if (!empty($hinhanhcu) && !in_array($hinhanhcu, ['user.png', 'image.png', 'admin.png'])) {
                        $oldFile = $uploadDir . $hinhanhcu;
                        if (file_exists($oldFile)) {
                            @unlink($oldFile);
                        }
                    }
                }
            }
        }

        $nd->suanhanvien($id, $email, $sodt, $hoten, $trangthai, $hinhanh, $matkhaumoi);
        header("Location: index.php");
        exit();

    case "xoa":
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            // Không được tự xóa tài khoản chính mình
            if ($id != $_SESSION["nguoidung"]["id"]) {
                $nv = $nd->laynguoidungtheoid($id);
                if ($nv) {
                    // Xóa file ảnh riêng nếu có
                    $hinh = $nv["hinhanh"] ?? "";
                    if (!empty($hinh) && !in_array($hinh, ['user.png', 'image.png', 'admin.png'])) {
                        $fileHinh = "../../assets/images/users/" . $hinh;
                        if (file_exists($fileHinh)) {
                            @unlink($fileHinh);
                        }
                    }
                    $nd->xoanguoidung($id);
                }
            }
        }
        header("Location: index.php");
        exit();

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
