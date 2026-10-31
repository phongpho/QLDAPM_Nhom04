<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Phân quyền: Cả Admin (1) và Nhân viên (2) đều được truy cập
if (!isset($_SESSION["nguoidung"])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
} elseif (!in_array((int)$_SESSION["nguoidung"]["loai"], [1, 2])) {
    echo "<script>alert('Bạn không có quyền truy cập vào chức năng Quản lý voucher!'); window.location.href='../ktnguoidung/index.php';</script>";
    exit();
}

$isAdmin = ((int)$_SESSION["nguoidung"]["loai"] === 1);

require_once("../../model/database.php");
require_once("../../model/voucher.php");

$vc = new VOUCHER();
$action = $_REQUEST["action"] ?? "macdinh";

switch ($action) {
    case "macdinh":
        $vouchers = $vc->laydanhsachvoucheradmin();
        $thongke = $vc->thongkevoucheradmin();

        // Lọc theo loại, trạng thái & từ khóa
        $filter_loai = $_GET["loai"] ?? "";
        $filter_trangthai = $_GET["trangthai"] ?? "";
        $filter_tukhoa = trim($_GET["tukhoa"] ?? "");

        if (!empty($filter_loai) || $filter_trangthai !== "" || !empty($filter_tukhoa)) {
            $vouchers = array_filter($vouchers, function ($item) use ($filter_loai, $filter_trangthai, $filter_tukhoa) {
                if (!empty($filter_loai) && $item["loai"] !== $filter_loai) {
                    return false;
                }
                if ($filter_trangthai !== "") {
                    $isExpired = strtotime($item["ngayhethan"]) <= time();
                    $isOutOfStock = (int)$item["dasudung"] >= (int)$item["soluong"];
                    $isActive = ((int)$item["trangthai"] === 1 && !$isExpired && !$isOutOfStock);

                    if ($filter_trangthai === "active" && !$isActive) return false;
                    if ($filter_trangthai === "expired" && !$isExpired) return false;
                    if ($filter_trangthai === "outofstock" && !$isOutOfStock) return false;
                    if ($filter_trangthai === "disabled" && (int)$item["trangthai"] !== 0) return false;
                }
                if (!empty($filter_tukhoa)) {
                    $tukhoa = mb_strtolower($filter_tukhoa, 'UTF-8');
                    $ma = mb_strtolower($item["ma"], 'UTF-8');
                    $mota = mb_strtolower($item["mota"] ?? '', 'UTF-8');
                    if (mb_strpos($ma, $tukhoa) === false && mb_strpos($mota, $tukhoa) === false) {
                        return false;
                    }
                }
                return true;
            });
        }

        include("main.php");
        break;

    case "xulythem":
        $ma = strtoupper(trim($_POST["ma"] ?? ""));
        $mota = trim($_POST["mota"] ?? "");
        $loai = in_array($_POST["loai"] ?? '', ['phantram', 'sotien']) ? $_POST["loai"] : 'phantram';
        $giatri = (float)($_POST["giatri"] ?? 0);
        $donhang_toithieu = (float)($_POST["donhang_toithieu"] ?? 0);
        $giamtoida = (!empty($_POST["giamtoida"])) ? (float)$_POST["giamtoida"] : null;
        $ngayhethan = trim($_POST["ngayhethan"] ?? "");
        $soluong = (int)($_POST["soluong"] ?? 0);
        $trangthai = isset($_POST["trangthai"]) ? (int)$_POST["trangthai"] : 1;

        if (empty($ma) || $giatri <= 0 || empty($ngayhethan) || $soluong <= 0) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Vui lòng nhập đầy đủ các trường bắt buộc (Mã, Mức giảm, Hạn dùng, Số lượng)!"];
            header("Location: index.php");
            exit();
        }

        if ($loai === 'phantram' && ($giatri <= 0 || $giatri > 100)) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Mức giảm theo phần trăm phải nằm trong khoảng từ 1% đến 100%!"];
            header("Location: index.php");
            exit();
        }

        if ($vc->kiemtratrungma($ma)) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Mã voucher \"$ma\" đã tồn tại! Vui lòng chọn mã khác."];
            header("Location: index.php");
            exit();
        }

        // Định dạng datetime hợp lệ
        if (strlen($ngayhethan) === 10) {
            $ngayhethan .= " 23:59:59";
        }

        $res = $vc->themvoucher($ma, $mota, $loai, $giatri, $donhang_toithieu, $giamtoida, $ngayhethan, $soluong, $trangthai);
        if ($res) {
            $_SESSION["thongbao"] = ["loai" => "success", "noidung" => "Tạo mới voucher \"$ma\" thành công!"];
        } else {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Không thể tạo voucher. Vui lòng kiểm tra lại!"];
        }
        header("Location: index.php");
        exit();

    case "xulysua":
        $id = (int)($_POST["id"] ?? 0);
        $ma = strtoupper(trim($_POST["ma"] ?? ""));
        $mota = trim($_POST["mota"] ?? "");
        $loai = in_array($_POST["loai"] ?? '', ['phantram', 'sotien']) ? $_POST["loai"] : 'phantram';
        $giatri = (float)($_POST["giatri"] ?? 0);
        $donhang_toithieu = (float)($_POST["donhang_toithieu"] ?? 0);
        $giamtoida = (!empty($_POST["giamtoida"])) ? (float)$_POST["giamtoida"] : null;
        $ngayhethan = trim($_POST["ngayhethan"] ?? "");
        $soluong = (int)($_POST["soluong"] ?? 0);
        $trangthai = isset($_POST["trangthai"]) ? (int)$_POST["trangthai"] : 1;

        if ($id <= 0 || empty($ma) || $giatri <= 0 || empty($ngayhethan) || $soluong <= 0) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Dữ liệu voucher không hợp lệ!"];
            header("Location: index.php");
            exit();
        }

        if ($loai === 'phantram' && ($giatri <= 0 || $giatri > 100)) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Mức giảm theo phần trăm phải nằm trong khoảng từ 1% đến 100%!"];
            header("Location: index.php");
            exit();
        }

        if ($vc->kiemtratrungma($ma, $id)) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Mã voucher \"$ma\" đã trùng với voucher khác!"];
            header("Location: index.php");
            exit();
        }

        if (strlen($ngayhethan) === 10) {
            $ngayhethan .= " 23:59:59";
        }

        $res = $vc->capnhatvoucher($id, $ma, $mota, $loai, $giatri, $donhang_toithieu, $giamtoida, $ngayhethan, $soluong, $trangthai);
        if ($res) {
            $_SESSION["thongbao"] = ["loai" => "success", "noidung" => "Cập nhật mã voucher \"$ma\" thành công!"];
        } else {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Cập nhật voucher thất bại!"];
        }
        header("Location: index.php");
        exit();

    case "doitrangthai":
        $id = (int)($_GET["id"] ?? 0);
        $tt = (int)($_GET["tt"] ?? 0);
        $moi = ($tt === 1) ? 0 : 1;

        if ($id > 0) {
            $vc->doitrangthai($id, $moi);
            $msg = ($moi === 1) ? "Đã kích hoạt áp dụng voucher!" : "Đã tạm dừng phát hành voucher!";
            $_SESSION["thongbao"] = ["loai" => "info", "noidung" => $msg];
        }
        header("Location: index.php");
        exit();

    case "xoa":
        if (!$isAdmin) {
            $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Chỉ Quản trị viên cấp cao mới có quyền xóa mã giảm giá!"];
            header("Location: index.php");
            exit();
        }

        $id = (int)($_GET["id"] ?? 0);
        if ($id > 0) {
            $row = $vc->layvouchertheoid($id);
            if ($row && (int)$row["dasudung"] > 0) {
                $vc->doitrangthai($id, 0);
                $_SESSION["thongbao"] = [
                    "loai" => "warning", 
                    "noidung" => "Mã \"{$row['ma']}\" đã có {$row['dasudung']} lượt sử dụng trên các đơn hàng! Hệ thống đã tự động chuyển sang trạng thái \"Tạm dừng\" thay vì xóa cứng để bảo đảm lịch sử kế toán."
                ];
            } else {
                $res = $vc->xoavoucher($id);
                if ($res) {
                    $_SESSION["thongbao"] = ["loai" => "success", "noidung" => "Đã xóa voucher vĩnh viễn!"];
                } else {
                    $_SESSION["thongbao"] = ["loai" => "danger", "noidung" => "Lỗi trong quá trình xóa voucher!"];
                }
            }
        }
        header("Location: index.php");
        exit();

    default:
        header("Location: index.php");
        exit();
}
