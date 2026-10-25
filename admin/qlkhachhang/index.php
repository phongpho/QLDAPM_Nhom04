<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Phân quyền: Cả Admin (1) và Nhân viên (2) đều được quản lý khách hàng
if (!isset($_SESSION["nguoidung"])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
} elseif (!in_array((int)$_SESSION["nguoidung"]["loai"], [1, 2])) {
    echo "<script>alert('Bạn không có quyền truy cập vào chức năng Quản lý khách hàng!'); window.location.href='../ktnguoidung/index.php';</script>";
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
        $khachhang = $nd->laydanhsachkhachhangquantri();

        // Tính toán các chỉ số thống kê KPI khách hàng
        $tongKhachHang = count($khachhang);
        $demHoatDong = 0;
        $demKhoa = 0;
        $demCoDonHang = 0;
        $tongDoanhThuKhach = 0;

        foreach ($khachhang as $item) {
            if ((int)$item["trangthai"] === 1) {
                $demHoatDong++;
            } else {
                $demKhoa++;
            }
            if ((int)$item["tong_donhang"] > 0) {
                $demCoDonHang++;
                $tongDoanhThuKhach += (float)$item["tong_chitieu"];
            }
        }

        // Bộ lọc dữ liệu
        $filter_tukhoa = trim($_GET["tukhoa"] ?? "");
        $filter_trangthai = $_GET["trangthai"] ?? "";
        $filter_donhang = $_GET["donhang"] ?? "";

        $danhsachHienThi = array_filter($khachhang, function ($item) use ($filter_tukhoa, $filter_trangthai, $filter_donhang) {
            // Lọc theo từ khóa (tên, email, sdt hoặc ID)
            if (!empty($filter_tukhoa)) {
                $kw = mb_strtolower($filter_tukhoa, 'UTF-8');
                $hoten = mb_strtolower($item["hoten"] ?? "", 'UTF-8');
                $email = mb_strtolower($item["email"] ?? "", 'UTF-8');
                $sodt = mb_strtolower($item["sodienthoai"] ?? "", 'UTF-8');
                $id = (string)$item["id"];
                if (strpos($hoten, $kw) === false && strpos($email, $kw) === false && strpos($sodt, $kw) === false && strpos($id, $kw) === false) {
                    return false;
                }
            }

            // Lọc theo trạng thái
            if ($filter_trangthai !== "" && (string)$item["trangthai"] !== (string)$filter_trangthai) {
                return false;
            }

            // Lọc theo lịch sử đơn hàng
            if ($filter_donhang === "codon" && (int)$item["tong_donhang"] <= 0) {
                return false;
            }
            if ($filter_donhang === "chuadon" && (int)$item["tong_donhang"] > 0) {
                return false;
            }

            return true;
        });

        include("main.php");
        break;

    case "xlthem":
        $hoten = trim($_POST["txthoten"] ?? "");
        $email = trim($_POST["txtemail"] ?? "");
        $sodt = trim($_POST["txtsodt"] ?? "");
        $matkhau = trim($_POST["txtmatkhau"] ?? "");
        $trangthai = (int)($_POST["opttrangthai"] ?? 1);

        // Kiểm tra hợp lệ bắt buộc
        if (empty($hoten) || empty($email) || empty($sodt) || empty($matkhau)) {
            $_SESSION["loi"] = "Vui lòng nhập đầy đủ các trường thông tin bắt buộc!";
            header("Location: index.php");
            exit();
        }

        if (strlen($matkhau) < 6) {
            $_SESSION["loi"] = "Mật khẩu khởi tạo phải có tối thiểu 6 ký tự!";
            header("Location: index.php");
            exit();
        }

        // Kiểm tra trùng email hoặc số điện thoại
        if ($nd->kiemtratrung($email, $sodt)) {
            $_SESSION["loi"] = "Địa chỉ email hoặc số điện thoại đã được đăng ký bởi tài khoản khác!";
            header("Location: index.php");
            exit();
        }

        // Xử lý upload hình ảnh đại diện nếu có
        $hinhanh = "user.png";
        $fileUpload = $_FILES["fhinh"] ?? null;
        if ($fileUpload && $fileUpload["error"] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($fileUpload["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $hinhanh = "kh_" . time() . "_" . uniqid() . "." . $ext;
                $uploadDir = "../../assets/images/users/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                move_uploaded_file($fileUpload["tmp_name"], $uploadDir . $hinhanh);
            }
        }

        if ($nd->themnguoidung($email, $matkhau, $sodt, $hoten, 3, $hinhanh)) {
            $_SESSION["thongbao"] = "Thêm tài khoản khách hàng mới thành công!";
        } else {
            $_SESSION["loi"] = "Không thể thêm tài khoản khách hàng, vui lòng thử lại!";
        }
        header("Location: index.php");
        exit();

    case "sua":
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            $k = $nd->laynguoidungtheoid($id);
            if (!$k || (int)$k["loai"] !== 3) {
                $_SESSION["loi"] = "Khách hàng không tồn tại trong hệ thống!";
                header("Location: index.php");
                exit();
            }
            include("sua.php");
        } else {
            header("Location: index.php");
        }
        break;

    case "xlsua":
        $id = (int)($_POST["txtid"] ?? 0);
        $hoten = trim($_POST["txthoten"] ?? "");
        $email = trim($_POST["txtemail"] ?? "");
        $sodt = trim($_POST["txtsodt"] ?? "");
        $trangthai = (int)($_POST["opttrangthai"] ?? 1);
        $matkhaumoi = trim($_POST["txtmatkhaumoi"] ?? "");
        $hinhanhcu = $_POST["txthinhanhcu"] ?? "user.png";
        $hinhanh = $hinhanhcu;

        if (empty($hoten) || empty($email) || empty($sodt)) {
            $_SESSION["loi"] = "Vui lòng nhập đầy đủ họ tên, email và số điện thoại!";
            header("Location: index.php?action=sua&id=" . $id);
            exit();
        }

        if (!empty($matkhaumoi) && strlen($matkhaumoi) < 6) {
            $_SESSION["loi"] = "Mật khẩu mới phải có tối thiểu 6 ký tự!";
            header("Location: index.php?action=sua&id=" . $id);
            exit();
        }

        if ($nd->kiemtratrungkhisua($id, $email, $sodt)) {
            $_SESSION["loi"] = "Email hoặc số điện thoại đã được sử dụng bởi một tài khoản khác!";
            header("Location: index.php?action=sua&id=" . $id);
            exit();
        }

        // Xử lý upload ảnh đại diện mới
        $fileUpload = $_FILES["fhinh"] ?? null;
        if ($fileUpload && $fileUpload["error"] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($fileUpload["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $hinhanh = "kh_" . time() . "_" . uniqid() . "." . $ext;
                $uploadDir = "../../assets/images/users/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                if (move_uploaded_file($fileUpload["tmp_name"], $uploadDir . $hinhanh)) {
                    if (!empty($hinhanhcu) && !in_array($hinhanhcu, ['user.png', 'admin.png', 'image.png'])) {
                        $oldPath = $uploadDir . $hinhanhcu;
                        if (file_exists($oldPath)) {
                            @unlink($oldPath);
                        }
                    }
                }
            }
        }

        $res = $nd->suanhanvien($id, $email, $sodt, $hoten, $trangthai, $hinhanh, !empty($matkhaumoi) ? $matkhaumoi : null);
        if ($res) {
            $_SESSION["thongbao"] = "Cập nhật thông tin khách hàng thành công!";
        } else {
            $_SESSION["loi"] = "Không thể cập nhật thông tin khách hàng!";
        }
        header("Location: index.php");
        exit();

    case "chitiet":
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            $k = $nd->laychitietkhachhang($id);
            if (!$k) {
                $_SESSION["loi"] = "Khách hàng không tồn tại trong hệ thống!";
                header("Location: index.php");
                exit();
            }
            include("chitiet.php");
        } else {
            header("Location: index.php");
        }
        break;

    case "doitrangthai":
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            $trangthai = (int)($_GET["trangthai"] ?? 0);
            $nd->doitrangthai($id, $trangthai);
            $_SESSION["thongbao"] = ($trangthai === 1) ? "Đã mở khóa tài khoản khách hàng!" : "Đã tạm khóa tài khoản khách hàng!";
        }
        header("Location: index.php");
        exit();

    case "xoa":
        // Chỉ duy nhất Quản trị viên (loai == 1) mới có quyền xóa
        if (!isset($_SESSION["nguoidung"]) || (int)$_SESSION["nguoidung"]["loai"] !== 1) {
            $_SESSION["loi"] = "Bạn không có quyền xóa khách hàng. Hãy dùng chức năng Khóa tài khoản!";
            header("Location: index.php");
            exit();
        }

        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            if ($nd->kiemtradonhangkhachhang($id)) {
                $_SESSION["loi"] = "Không thể xóa khách hàng này do đã phát sinh giao dịch đơn hàng. Để bảo vệ dữ liệu lịch sử và kế toán, vui lòng chọn Khóa tài khoản!";
            } else {
                $userItem = $nd->laynguoidungtheoid($id);
                if ($nd->xoakhachhang($id)) {
                    if (!empty($userItem["hinhanh"]) && !in_array($userItem["hinhanh"], ['user.png', 'admin.png', 'image.png'])) {
                        $fPath = "../../assets/images/users/" . $userItem["hinhanh"];
                        if (file_exists($fPath)) {
                            @unlink($fPath);
                        }
                    }
                    $_SESSION["thongbao"] = "Đã xóa tài khoản khách hàng khỏi hệ thống thành công!";
                } else {
                    $_SESSION["loi"] = "Không thể xóa khách hàng, vui lòng thử lại!";
                }
            }
        }
        header("Location: index.php");
        exit();

    default:
        header("Location: index.php");
        break;
}
?>
