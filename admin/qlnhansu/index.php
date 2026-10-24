<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Kiểm tra đăng nhập và phân quyền: Chỉ duy nhất Admin (loai == 1) mới được vào
if (!isset($_SESSION["nguoidung"])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
} elseif ($_SESSION["nguoidung"]["loai"] != 1) {
    echo "<script>alert('Bạn không có quyền truy cập vào chức năng Quản lý nhân sự!'); window.location.href='../ktnguoidung/index.php';</script>";
    exit();
}

require_once("../../model/database.php");
require_once("../../model/nguoidung.php");

$nd = new NGUOIDUNG();

// 2. Xác định action
$action = $_REQUEST["action"] ?? "macdinh";

// 3. Điều hướng xử lý theo action
switch ($action) {
    case "macdinh":
        $nguoidung = $nd->laydanhsachnguoidung();

        // Thong ke nhan vien van hanh (loai == 2) va Admin (loai == 1)
        $tongNhanvien = 0;
        $demAdmin = 0;
        $demHoatdong = 0;
        $demKhoa = 0;

        foreach ($nguoidung as $u) {
            if ((int)$u["loai"] === 2) {
                $tongNhanvien++;
                if ((int)$u["trangthai"] === 1) $demHoatdong++;
                else $demKhoa++;
            } elseif ((int)$u["loai"] === 1) {
                $demAdmin++;
            }
        }
        $tongNhansu = $tongNhanvien;

        // Bộ lọc trạng thái và từ khóa cho danh sách nhân viên (loai == 2)
        $filter_trangthai = $_GET["trangthai"] ?? "";
        $filter_tukhoa = trim($_GET["tukhoa"] ?? "");

        $danhsachHienThi = array_filter($nguoidung, function ($item) use ($filter_trangthai, $filter_tukhoa) {
            // KHÔNG liệt kê admin (loai == 1), chỉ liệt kê nhân viên (loai == 2)
            if ((int)$item["loai"] !== 2) {
                return false;
            }

            if ($filter_trangthai !== "" && (string)$item["trangthai"] !== (string)$filter_trangthai) {
                return false;
            }

            if (!empty($filter_tukhoa)) {
                $tukhoa = mb_strtolower($filter_tukhoa, 'UTF-8');
                $hoten = mb_strtolower($item["hoten"] ?? "", 'UTF-8');
                $email = mb_strtolower($item["email"] ?? "", 'UTF-8');
                $sodt = mb_strtolower($item["sodienthoai"] ?? "", 'UTF-8');
                if (strpos($hoten, $tukhoa) === false && strpos($email, $tukhoa) === false && strpos($sodt, $tukhoa) === false) {
                    return false;
                }
            }

            return true;
        });

        include("main.php");
        break;

    case "xlthem":
        $email = trim($_POST["txtemail"] ?? "");
        $matkhau = $_POST["txtmatkhau"] ?? "";
        $sodt = trim($_POST["txtsodt"] ?? $_POST["txtdienthoai"] ?? "");
        $hoten = trim($_POST["txthoten"] ?? "");
        $loai = (int)($_POST["optloai"] ?? 2); // 1: Admin, 2: Nhân viên, 3: Khách

        // Xử lý upload hình ảnh nhân viên với tên file duy nhất
        $hinhanh = null;
        $fileUpload = $_FILES["hinhanh"] ?? $_FILES["fhinh"] ?? null;
        if ($fileUpload && $fileUpload["error"] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($fileUpload["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
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
            $_SESSION["loi"] = "Email hoặc Số điện thoại đã tồn tại trong hệ thống!";
        } else {
            $nd->themnguoidung($email, $matkhau, $sodt, $hoten, $loai, $hinhanh);
            $_SESSION["thongbao"] = "Khởi tạo tài khoản nhân sự mới thành công!";
        }
        header("Location: index.php");
        exit();

    case "sua":
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            $nhanvien = $nd->laynguoidungtheoid($id);
            if ($nhanvien) {
                include("sua.php");
                break;
            }
        }
        $_SESSION["loi"] = "Không tìm thấy thông tin nhân viên yêu cầu!";
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
            $_SESSION["loi"] = "Email hoặc Số điện thoại đã tồn tại ở tài khoản khác!";
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
        $_SESSION["thongbao"] = "Cập nhật thông tin nhân sự thành công!";
        header("Location: index.php");
        exit();

    case "xoa":
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            // Không được tự xóa tài khoản chính mình
            if ($id == $_SESSION["nguoidung"]["id"]) {
                $_SESSION["loi"] = "Không thể tự xóa tài khoản của chính mình đang đăng nhập!";
            } else {
                $nv = $nd->laynguoidungtheoid($id);
                if ($nv) {
                    $hinh = $nv["hinhanh"] ?? "";
                    if (!empty($hinh) && !in_array($hinh, ['user.png', 'image.png', 'admin.png'])) {
                        $fileHinh = "../../assets/images/users/" . $hinh;
                        if (file_exists($fileHinh)) {
                            @unlink($fileHinh);
                        }
                    }
                    $nd->xoanguoidung($id);
                    $_SESSION["thongbao"] = "Đã xóa tài khoản nhân sự thành công!";
                } else {
                    $_SESSION["loi"] = "Tài khoản nhân sự không tồn tại!";
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
            if ($id == $_SESSION["nguoidung"]["id"]) {
                $_SESSION["loi"] = "Không thể tự khóa tài khoản của chính mình đang đăng nhập!";
            } else {
                $nd->doitrangthai($id, $trangthai);
                if ($trangthai === 1) {
                    $_SESSION["thongbao"] = "Đã mở khóa tài khoản nhân sự thành công!";
                } else {
                    $_SESSION["thongbao"] = "Đã khóa tài khoản nhân sự thành công!";
                }
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
            $_SESSION["thongbao"] = "Đã cập nhật phân quyền tài khoản thành công!";
        }
        header("Location: index.php");
        exit();

    default:
        header("Location: index.php?action=macdinh");
        exit();
}
?>

