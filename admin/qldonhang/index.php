<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Phân quyền: Cả Admin (loai == 1) và Nhân viên (loai == 2) đều được truy cập Quản lý đơn hàng
if (!isset($_SESSION["nguoidung"])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
} elseif (!in_array((int)$_SESSION["nguoidung"]["loai"], [1, 2])) {
    echo "<script>alert('Bạn không có quyền truy cập vào chức năng Quản lý đơn hàng!'); window.location.href='../ktnguoidung/index.php';</script>";
    exit();
}

require_once("../../model/database.php");
require_once("../../model/donhang.php");
require_once("../../model/nguoidung.php");

$dhModel = new DONHANG();
$ndModel = new NGUOIDUNG();

// 2. Xác định action
$action = $_REQUEST["action"] ?? "macdinh";

// 3. Điều hướng xử lý
switch ($action) {
    case "macdinh":
        $tukhoa = trim($_GET["tukhoa"] ?? "");
        $trangthai = $_GET["trangthai"] ?? "";
        $phuongthuc = trim($_GET["phuongthuc"] ?? "");
        $datt = $_GET["datt"] ?? "";
        $sapxep = trim($_GET["sapxep"] ?? "moinhat");

        // Lấy danh sách đơn hàng theo điều kiện lọc
        $danhsach = $dhModel->laydanhsachdonhangquantri($tukhoa, $trangthai, $phuongthuc, $datt, $sapxep);

        // Lấy dữ liệu thống kê phục vụ 4 thẻ KPI Metrics
        $thongke = $dhModel->thongkedonhangquantri();

        include("main.php");
        break;

    case "chitiet":
        $id = (int)($_GET["id"] ?? 0);
        if ($id <= 0) {
            $_SESSION["loi"] = "Mã đơn hàng không hợp lệ!";
            header("Location: index.php");
            exit();
        }

        $donhang = $dhModel->laydonhangtheoid($id);
        if (!$donhang) {
            $_SESSION["loi"] = "Không tìm thấy đơn hàng yêu cầu trong hệ thống!";
            header("Location: index.php");
            exit();
        }

        // Lấy chi tiết các mặt hàng trong đơn
        $chitiet = $dhModel->laychitietdonhang($id);

        // Lấy thông tin tài khoản người đặt (nếu có)
        $khachhang = null;
        if (!empty($donhang["nguoidung_id"])) {
            $khachhang = $ndModel->laynguoidungtheoid($donhang["nguoidung_id"]);
        }

        include("chitiet.php");
        break;

    case "capnhattrangthai":
        $id = (int)($_POST["txtid"] ?? ($_GET["id"] ?? 0));
        $trangthai = (int)($_POST["opttrangthai"] ?? ($_GET["trangthai"] ?? 0));

        if ($id <= 0 || !in_array($trangthai, [1, 2, 3, 4, 5, 6])) {
            $_SESSION["loi"] = "Dữ liệu cập nhật trạng thái đơn hàng không hợp lệ!";
        } else {
            if ($dhModel->capnhattrangthai($id, $trangthai)) {
                $tenTrangThai = $dhModel->laytentrangthai($trangthai);
                $_SESSION["thongbao"] = "Đã cập nhật trạng thái đơn hàng #{$id} thành: {$tenTrangThai}";
            } else {
                $_SESSION["loi"] = "Không thể cập nhật trạng thái đơn hàng, vui lòng thử lại!";
            }
        }

        $redirect = !empty($_REQUEST["redirect"]) ? $_REQUEST["redirect"] : "index.php";
        header("Location: " . $redirect);
        exit();

    case "capnhatdatt":
        $id = (int)($_GET["id"] ?? 0);
        $datt = (int)($_GET["datt"] ?? 1);

        if ($id <= 0) {
            $_SESSION["loi"] = "Mã đơn hàng không hợp lệ!";
        } else {
            if ($dhModel->capnhatdatt($id, $datt)) {
                $_SESSION["thongbao"] = ($datt === 1) ? "Đã ghi nhận đơn hàng #{$id} ĐÃ THANH TOÁN!" : "Đã chuyển đơn hàng #{$id} về CHƯA THANH TOÁN!";
            } else {
                $_SESSION["loi"] = "Không thể cập nhật trạng thái thanh toán!";
            }
        }

        $redirect = !empty($_GET["redirect"]) ? $_GET["redirect"] : "index.php";
        header("Location: " . $redirect);
        exit();

    case "capnhatvanchuyen":
        $id = (int)($_POST["txtid"] ?? 0);
        $doitac = trim($_POST["txtdoitac"] ?? "");
        $ghichu = trim($_POST["txtghichu"] ?? "");

        if ($id <= 0) {
            $_SESSION["loi"] = "Mã đơn hàng không hợp lệ!";
            header("Location: index.php");
            exit();
        }

        if ($dhModel->capnhatvanchuyen($id, $doitac, $ghichu)) {
            $_SESSION["thongbao"] = "Cập nhật thông tin vận chuyển & ghi chú cho đơn hàng #{$id} thành công!";
        } else {
            $_SESSION["loi"] = "Không thể cập nhật thông tin vận chuyển!";
        }

        header("Location: index.php?action=chitiet&id={$id}");
        exit();

    case "xoa":
        // Chỉ DUY NHẤT Quản trị viên (loai == 1) mới có quyền xóa đơn hàng
        if ((int)$_SESSION["nguoidung"]["loai"] !== 1) {
            $_SESSION["loi"] = "Bạn không có quyền xóa đơn hàng! Chỉ Quản trị viên mới được thực hiện thao tác này.";
            header("Location: index.php");
            exit();
        }

        $id = (int)($_GET["id"] ?? 0);
        if ($id <= 0) {
            $_SESSION["loi"] = "Mã đơn hàng không hợp lệ!";
            header("Location: index.php");
            exit();
        }

        $ketqua = $dhModel->xoadonhang($id);
        if ($ketqua["ok"]) {
            $_SESSION["thongbao"] = $ketqua["message"];
        } else {
            $_SESSION["loi"] = $ketqua["message"];
        }

        header("Location: index.php");
        exit();

    default:
        header("Location: index.php?action=macdinh");
        exit();
}
?>
