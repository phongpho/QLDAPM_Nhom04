<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Phân quyền: Cả Admin (1) và Nhân viên (2) đều được quản lý sản phẩm
if (!isset($_SESSION["nguoidung"])) {
    header("Location: ../ktnguoidung/index.php?action=dangnhap");
    exit();
} elseif (!in_array((int)$_SESSION["nguoidung"]["loai"], [1, 2])) {
    echo "<script>alert('Bạn không có quyền truy cập vào chức năng Quản lý sản phẩm!'); window.location.href='../ktnguoidung/index.php';</script>";
    exit();
}

require_once("../../model/database.php");
require_once("../../model/sanpham.php");
require_once("../../model/danhmuc.php");

$sp = new SANPHAM();
$dm = new DANHMUC();

// 2. Xác định action
$action = $_REQUEST["action"] ?? "macdinh";

// 3. Điều hướng xử lý theo action
switch ($action) {
    case "macdinh":
        $sanpham = $sp->laysanphamquantri();
        $danhmuc = $dm->laydanhmuc();
        $thuonghieu = $sp->laytatcathuonghieu();

        // Tính toán các chỉ số thống kê kho
        $tongSanPham = count($sanpham);
        $demSapHetHang = 0;
        $demNoiBat = 0;
        $demTamAn = 0;

        foreach ($sanpham as $item) {
            if ((int)$item["soluongton"] <= 5) {
                $demSapHetHang++;
            }
            if ((int)$item["noibat"] === 1) {
                $demNoiBat++;
            }
            if ((int)$item["trangthai"] === 0) {
                $demTamAn++;
            }
        }

        // Bộ lọc dữ liệu
        $filter_tukhoa = trim($_GET["tukhoa"] ?? "");
        $filter_danhmuc = $_GET["danhmuc"] ?? "";
        $filter_tonkho = $_GET["tonkho"] ?? "";
        $filter_trangthai = $_GET["trangthai"] ?? "";
        $filter_noibat = $_GET["noibat"] ?? "";

        $danhsachHienThi = array_filter($sanpham, function ($item) use ($filter_tukhoa, $filter_danhmuc, $filter_tonkho, $filter_trangthai, $filter_noibat) {
            // Lọc theo từ khóa
            if (!empty($filter_tukhoa)) {
                $kw = mb_strtolower($filter_tukhoa, 'UTF-8');
                $ten = mb_strtolower($item["tensanpham"] ?? "", 'UTF-8');
                $id = (string)$item["id"];
                if (strpos($ten, $kw) === false && strpos($id, $kw) === false) {
                    return false;
                }
            }

            // Lọc theo danh mục
            if ($filter_danhmuc !== "" && (string)$item["danhmuc_id"] !== (string)$filter_danhmuc) {
                return false;
            }

            // Lọc theo tồn kho
            if ($filter_tonkho === "hethang" && (int)$item["soluongton"] > 0) {
                return false;
            }
            if ($filter_tonkho === "saphethang" && ((int)$item["soluongton"] <= 0 || (int)$item["soluongton"] > 5)) {
                return false;
            }
            if ($filter_tonkho === "conhang" && (int)$item["soluongton"] <= 5) {
                return false;
            }

            // Lọc theo trạng thái
            if ($filter_trangthai !== "" && (string)$item["trangthai"] !== (string)$filter_trangthai) {
                return false;
            }

            // Lọc theo nổi bật
            if ($filter_noibat !== "" && (string)$item["noibat"] !== (string)$filter_noibat) {
                return false;
            }

            return true;
        });

        include("main.php");
        break;

    case "xlthem":
        $tensanpham = trim($_POST["txtten"] ?? "");
        $danhmuc_id = (int)($_POST["optdanhmuc"] ?? 0);
        $thuonghieu_id = !empty($_POST["optthuonghieu"]) ? (int)$_POST["optthuonghieu"] : null;
        $giagoc = (float)($_POST["txtgiagoc"] ?? 0);
        $giaban = (float)($_POST["txtgiaban"] ?? 0);
        $soluongton = (int)($_POST["txtsoluongton"] ?? 0);
        $doituong = $_POST["optdoituong"] ?? "unisex";
        $sizes = trim($_POST["txtsizes"] ?? "");
        $mausac = trim($_POST["txtmausac"] ?? "");
        $chatlieu = trim($_POST["txtchatlieu"] ?? "");
        $thanhphan = trim($_POST["txtthanhphan"] ?? "");
        
        $muctieuArr = $_POST["chkmuctieu"] ?? [];
        $muctieu = !empty($muctieuArr) ? implode(',', $muctieuArr) : "giamcan,tangcan,duytri";
        
        $hansudung = !empty($_POST["txthansudung"]) ? $_POST["txthansudung"] : null;
        $noibat = isset($_POST["chknoibat"]) ? 1 : 0;
        $trangthai = isset($_POST["chktrangthai"]) ? 1 : 0;
        $mota = trim($_POST["txtmota"] ?? "");

        // Kiểm tra hợp lệ bắt buộc
        if (empty($tensanpham) || $danhmuc_id <= 0 || $giaban < 0) {
            $_SESSION["loi"] = "Vui lòng nhập đầy đủ tên sản phẩm, chọn danh mục và giá bán hợp lệ!";
            header("Location: index.php");
            exit();
        }

        // Xử lý upload hình ảnh
        $hinhanh = "product.png";
        $fileUpload = $_FILES["fhinh"] ?? null;
        if ($fileUpload && $fileUpload["error"] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($fileUpload["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $hinhanh = "sp_" . time() . "_" . uniqid() . "." . $ext;
                $uploadDir = "../../assets/images/products/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                move_uploaded_file($fileUpload["tmp_name"], $uploadDir . $hinhanh);
            }
        }

        if ($sp->themsanpham($tensanpham, $mota, $giagoc, $giaban, $soluongton, $hinhanh, $danhmuc_id, $thuonghieu_id, $doituong, $sizes, $mausac, $chatlieu, $thanhphan, $muctieu, $hansudung, $noibat, $trangthai)) {
            $_SESSION["thongbao"] = "Khởi tạo sản phẩm mới thành công!";
        } else {
            $_SESSION["loi"] = "Không thể thêm sản phẩm, vui lòng thử lại!";
        }
        header("Location: index.php");
        exit();

    case "sua":
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            $s = $sp->laysanphamchitiet($id);
            if (!$s) {
                $s = $sp->laysanphamtheoid($id);
            }
            if (!$s) {
                $_SESSION["loi"] = "Sản phẩm không tồn tại trong hệ thống!";
                header("Location: index.php");
                exit();
            }
            $danhmuc = $dm->laydanhmuc();
            $thuonghieu = $sp->laytatcathuonghieu();
            include("sua.php");
        } else {
            header("Location: index.php");
        }
        break;

    case "xlsua":
        $id = (int)($_POST["txtid"] ?? 0);
        $tensanpham = trim($_POST["txtten"] ?? "");
        $danhmuc_id = (int)($_POST["optdanhmuc"] ?? 0);
        $thuonghieu_id = !empty($_POST["optthuonghieu"]) ? (int)$_POST["optthuonghieu"] : null;
        $giagoc = (float)($_POST["txtgiagoc"] ?? 0);
        $giaban = (float)($_POST["txtgiaban"] ?? 0);
        $soluongton = (int)($_POST["txtsoluongton"] ?? 0);
        $doituong = $_POST["optdoituong"] ?? "unisex";
        $sizes = trim($_POST["txtsizes"] ?? "");
        $mausac = trim($_POST["txtmausac"] ?? "");
        $chatlieu = trim($_POST["txtchatlieu"] ?? "");
        $thanhphan = trim($_POST["txtthanhphan"] ?? "");
        
        $muctieuArr = $_POST["chkmuctieu"] ?? [];
        $muctieu = !empty($muctieuArr) ? implode(',', $muctieuArr) : "giamcan,tangcan,duytri";
        
        $hansudung = !empty($_POST["txthansudung"]) ? $_POST["txthansudung"] : null;
        $noibat = isset($_POST["chknoibat"]) ? 1 : 0;
        $trangthai = isset($_POST["chktrangthai"]) ? 1 : 0;
        $mota = trim($_POST["txtmota"] ?? "");

        $hinhanhcu = $_POST["txthinhanhcu"] ?? "";
        $hinhanh = $hinhanhcu;

        if (empty($tensanpham) || $danhmuc_id <= 0 || $giaban < 0) {
            $_SESSION["loi"] = "Vui lòng nhập đầy đủ tên sản phẩm, chọn danh mục và giá bán hợp lệ!";
            header("Location: index.php?action=sua&id=" . $id);
            exit();
        }

        // Xử lý upload hình ảnh mới nếu có
        $fileUpload = $_FILES["fhinh"] ?? null;
        if ($fileUpload && $fileUpload["error"] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($fileUpload["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $hinhanh = "sp_" . time() . "_" . uniqid() . "." . $ext;
                $uploadDir = "../../assets/images/products/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                if (move_uploaded_file($fileUpload["tmp_name"], $uploadDir . $hinhanh)) {
                    if (!empty($hinhanhcu) && !in_array($hinhanhcu, ['product.png', 'default.png', 'no-image.png'])) {
                        $oldFile = $uploadDir . $hinhanhcu;
                        if (file_exists($oldFile)) {
                            @unlink($oldFile);
                        }
                    }
                }
            }
        }

        if ($sp->capnhatsanpham($id, $tensanpham, $mota, $giagoc, $giaban, $soluongton, $hinhanh, $danhmuc_id, $thuonghieu_id, $doituong, $sizes, $mausac, $chatlieu, $thanhphan, $muctieu, $hansudung, $noibat, $trangthai)) {
            $_SESSION["thongbao"] = "Cập nhật sản phẩm thành công!";
        } else {
            $_SESSION["loi"] = "Không thể cập nhật sản phẩm, vui lòng thử lại!";
        }
        header("Location: index.php");
        exit();

    case "doitrangthai":
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            $trangthai = (int)($_GET["trangthai"] ?? 0);
            $sp->doitrangthaisanpham($id, $trangthai);
            $_SESSION["thongbao"] = ($trangthai === 1) ? "Đã bật hiển thị bán sản phẩm trên website!" : "Đã tạm ẩn sản phẩm khỏi website!";
        }
        header("Location: index.php");
        exit();

    case "doinoibat":
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            $noibat = (int)($_GET["noibat"] ?? 0);
            $sp->doinoibatsanpham($id, $noibat);
            $_SESSION["thongbao"] = ($noibat === 1) ? "Đã ghim sản phẩm vào danh sách nổi bật trang chủ!" : "Đã bỏ ghim sản phẩm nổi bật!";
        }
        header("Location: index.php");
        exit();

    case "xoa":
        // Chỉ duy nhất Quản trị viên (loai == 1) mới có quyền xóa
        if (!isset($_SESSION["nguoidung"]) || (int)$_SESSION["nguoidung"]["loai"] !== 1) {
            $_SESSION["loi"] = "Bạn không có quyền xóa sản phẩm. Hãy dùng chức năng Tạm ẩn!";
            header("Location: index.php");
            exit();
        }

        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            if ($sp->kiemtratudonhang($id)) {
                $_SESSION["loi"] = "Không thể xóa sản phẩm này do đã phát sinh giao dịch đơn hàng. Để bảo vệ dữ liệu kinh doanh, vui lòng chọn Tạm ẩn sản phẩm!";
            } else {
                $spItem = $sp->laysanphamtheoid($id);
                if ($sp->xoasanpham($id)) {
                    if (!empty($spItem["hinhanh"]) && !in_array($spItem["hinhanh"], ['product.png', 'default.png', 'no-image.png'])) {
                        $fPath = "../../assets/images/products/" . $spItem["hinhanh"];
                        if (file_exists($fPath)) {
                            @unlink($fPath);
                        }
                    }
                    $_SESSION["thongbao"] = "Đã xóa sản phẩm khỏi hệ thống thành công!";
                } else {
                    $_SESSION["loi"] = "Không thể xóa sản phẩm, vui lòng thử lại!";
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
