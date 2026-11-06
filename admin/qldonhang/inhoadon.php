<?php
// Ngăn truy cập trái phép trực tiếp
if (!isset($donhang) || !is_array($donhang)) {
    die("Dữ liệu đơn hàng không hợp lệ!");
}

// Hàm đọc tiền Việt Nam đồng thành chữ
function docSoThanhChu($so) {
    $so = (int)round($so);
    if ($so <= 0) return 'Không đồng';
    $chuSo = array('không', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín');
    $donVi = array('', 'nghìn', 'triệu', 'tỷ', 'nghìn tỷ', 'triệu tỷ');

    $doc3So = function($n, $dayDu = false) use ($chuSo) {
        $tram = (int)($n / 100);
        $chuc = (int)(($n % 100) / 10);
        $dv   = (int)($n % 10);
        $res = '';

        if ($tram > 0 || $dayDu) {
            $res .= $chuSo[$tram] . ' trăm ';
            if ($chuc === 0 && $dv > 0) $res .= 'lẻ ';
        }
        if ($chuc > 1) {
            $res .= $chuSo[$chuc] . ' mươi ';
            if ($dv === 1) $res .= 'mốt ';
        } elseif ($chuc === 1) {
            $res .= 'mười ';
            if ($dv === 1) $res .= 'một ';
        }
        if ($chuc !== 1 && $dv === 5 && ($tram > 0 || $chuc > 0)) {
            $res .= 'lăm ';
        } elseif ($dv > 0 && ($chuc === 0 || ($chuc > 1 && $dv !== 1) || $chuc === 1 && $dv !== 1)) {
            $res .= $chuSo[$dv] . ' ';
        }
        return trim($res);
    };

    $blocks = [];
    $temp = $so;
    while ($temp > 0) {
        $blocks[] = $temp % 1000;
        $temp = (int)($temp / 1000);
    }

    $chu = '';
    for ($i = count($blocks) - 1; $i >= 0; $i--) {
        if ($blocks[$i] > 0) {
            $dayDu = ($i < count($blocks) - 1);
            $part = $doc3So($blocks[$i], $dayDu);
            $chu .= $part . ' ' . $donVi[$i] . ' ';
        }
    }
    $chu = trim($chu) . ' đồng';
    return mb_strtoupper(mb_substr($chu, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($chu, 1, null, 'UTF-8');
}

$maDonHang = htmlspecialchars($donhang["madonhang"] ?: ('#' . $donhang["id"]));
$isDaThanhToan = ((int)$donhang["datt"] === 1);
$isCOD = ($donhang["phuongthuc_tt"] === "cod");
$tongTienPhaiThu = $isDaThanhToan ? 0 : (float)$donhang["tongtien"];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn & Phiếu gửi hàng - <?= $maDonHang ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0f172a;
            --border: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --highlight: #b91c1c;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
            font-size: 13px;
            line-height: 1.5;
            color: var(--text-dark);
            background-color: #f1f5f9;
        }

        /* Thanh công cụ điều khiển trên màn hình (Ẩn khi in) */
        .no-print-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #1e293b;
            color: #ffffff;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
        }

        .no-print-toolbar h1 {
            font-size: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-tb {
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-tb-primary {
            background-color: #ff5500;
            color: #ffffff;
        }
        .btn-tb-primary:hover {
            background-color: #e04b00;
        }

        .btn-tb-secondary {
            background-color: #334155;
            color: #f8fafc;
        }
        .btn-tb-secondary:hover {
            background-color: #475569;
        }

        /* Container của tờ hóa đơn */
        .invoice-wrapper {
            margin: 60px auto 40px;
            max-width: 800px;
            background: #ffffff;
            padding: 32px 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border-radius: 8px;
        }

        /* Header hóa đơn */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 16px;
            margin-bottom: 20px;
        }

        .shop-info h2 {
            font-size: 22px;
            font-weight: 900;
            letter-spacing: 0.5px;
            color: #ff5500;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .shop-info p {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 2px;
        }

        .shop-info p strong {
            color: var(--text-dark);
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h3 {
            font-size: 20px;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--primary);
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .order-code-badge {
            font-size: 14px;
            font-weight: 700;
            background: #f8fafc;
            border: 1px dashed #94a3b8;
            padding: 4px 10px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 4px;
        }

        .invoice-date {
            font-size: 11.5px;
            color: var(--text-muted);
        }

        /* Khối thông tin người gửi & người nhận (2 cột giao hàng) */
        .shipping-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        .shipping-box {
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 12px 16px;
            background-color: #fafafa;
        }

        .shipping-box-title {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            padding-bottom: 6px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .shipping-box p {
            font-size: 12.5px;
            margin-bottom: 4px;
            color: var(--text-dark);
        }

        .recipient-name {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        .recipient-phone {
            font-size: 14px;
            font-weight: 700;
            color: #ff5500;
        }

        /* Thẻ trạng thái thanh toán & Thu hộ COD */
        .payment-banner {
            border: 2px dashed #cbd5e1;
            border-radius: 6px;
            padding: 10px 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .payment-banner.is-cod {
            background-color: #fff1f2;
            border-color: #fda4af;
        }

        .payment-banner.is-paid {
            background-color: #f0fdf4;
            border-color: #86efac;
        }

        .payment-banner-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .payment-banner-val {
            font-size: 16px;
            font-weight: 800;
        }

        /* Bảng sản phẩm */
        .table-products {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .table-products th {
            background-color: #f8fafc;
            color: #334155;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid var(--border);
            padding: 8px 10px;
            text-align: left;
        }

        .table-products td {
            border: 1px solid var(--border);
            padding: 8px 10px;
            font-size: 12.5px;
            vertical-align: middle;
        }

        .table-products tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        .td-center { text-align: center; }
        .td-right  { text-align: right; }

        .product-name {
            font-weight: 600;
            color: #0f172a;
        }

        .product-meta {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* Tổng kết tiền */
        .summary-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .summary-table {
            width: 320px;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 4px 8px;
            font-size: 12.5px;
        }

        .summary-table .label {
            color: var(--text-muted);
            text-align: right;
        }

        .summary-table .value {
            text-align: right;
            font-weight: 600;
            color: var(--text-dark);
        }

        .summary-table .total-row td {
            border-top: 2px solid var(--primary);
            padding-top: 8px;
            font-size: 14px;
        }

        .summary-table .total-value {
            font-size: 16px;
            font-weight: 900;
            color: #ff5500;
        }

        .words-total {
            font-size: 12px;
            font-style: italic;
            color: #475569;
            margin-bottom: 20px;
            padding: 8px 12px;
            background: #f8fafc;
            border-left: 3px solid #ff5500;
            border-radius: 0 4px 4px 0;
        }

        /* Khối lưu ý & Chữ ký */
        .shipping-notes {
            font-size: 11px;
            color: #64748b;
            border-top: 1px dashed var(--border);
            padding-top: 10px;
            margin-bottom: 24px;
        }

        .shipping-notes strong {
            color: var(--text-dark);
        }

        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 30px;
        }

        .signature-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 4px;
            color: var(--text-dark);
        }

        .signature-sub {
            font-size: 11px;
            color: var(--text-muted);
            font-style: italic;
        }

        .signature-space {
            height: 65px;
        }

        /* Bản quyền / Footer */
        .invoice-footer {
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid var(--border);
            padding-top: 10px;
        }

        /* --- QUY TẮC IN (PRINT MEDIA) --- */
        @media print {
            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-size: 12px;
            }

            .no-print-toolbar {
                display: none !important;
            }

            .invoice-wrapper {
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            .shipping-box {
                background-color: #ffffff !important;
                border: 1px solid #000000 !important;
            }

            .table-products th {
                background-color: #f1f5f9 !important;
                color: #000000 !important;
                border: 1px solid #000000 !important;
            }

            .table-products td {
                border: 1px solid #000000 !important;
            }

            .payment-banner {
                border: 1px dashed #000000 !important;
            }

            .words-total {
                background: #f8fafc !important;
                border-left-color: #000000 !important;
            }

            @page {
                size: A4 portrait;
                margin: 12mm 15mm;
            }
        }
    </style>
</head>
<body>

    <!-- Thanh công cụ màn hình (Ẩn khi in ấn) -->
    <div class="no-print-toolbar">
        <h1>
            <i class="fa fa-print text-warning"></i> Phiếu In Đơn Hàng & Hóa Đơn Bán Lẻ
        </h1>
        <div class="toolbar-actions">
            <button onclick="window.print()" class="btn-tb btn-tb-primary">
                <i class="fa fa-print"></i> In ngay (Ctrl + P)
            </button>
            <a href="index.php?action=chitiet&id=<?= $donhang["id"] ?>" class="btn-tb btn-tb-secondary">
                <i class="fa fa-arrow-left"></i> Quay lại chi tiết
            </a>
            <button onclick="window.close()" class="btn-tb btn-tb-secondary">
                <i class="fa fa-times"></i> Đóng tab
            </button>
        </div>
    </div>

    <!-- Tờ Hóa Đơn -->
    <div class="invoice-wrapper">

        <!-- Header -->
        <div class="invoice-header">
            <div class="shop-info">
                <h2>GymBro Fitness</h2>
                <p><strong>Cửa hàng Phụ kiện & Dinh dưỡng thể hình GymBro</strong></p>
                <p><i class="fa fa-map-marker-alt" style="width: 14px;"></i> 18 Ung Văn Khiêm, P. Đông Xuyên, TP. Long Xuyên, An Giang</p>
                <p><i class="fa fa-phone" style="width: 14px;"></i> Hotline: <strong>0987.654.321 - 0123.456.789</strong></p>
                <p><i class="fa fa-globe" style="width: 14px;"></i> Website: gymbro.vn | Email: support@gymbro.vn</p>
            </div>
            <div class="invoice-title">
                <h3>HÓA ĐƠN BÁN HÀNG</h3>
                <div class="order-code-badge">MÃ ĐƠN: <?= $maDonHang ?></div>
                <div class="invoice-date">Ngày đặt: <?= date("d/m/Y H:i", strtotime($donhang["ngay"])) ?></div>
                <div class="invoice-date">Ngày in: <?= date("d/m/Y H:i") ?></div>
            </div>
        </div>

        <!-- Khối Thông Tin Giao Nhận -->
        <div class="shipping-grid">
            <!-- Bên gửi -->
            <div class="shipping-box">
                <div class="shipping-box-title">
                    <i class="fa fa-store"></i> Đơn vị gửi hàng
                </div>
                <p><strong>Kho vận GymBro Official Store</strong></p>
                <p>Hotline hỗ trợ giao nhận: <strong>0987.654.321</strong></p>
                <p>Đơn vị vận chuyển: <strong><?= !empty($donhang["doitacgiaohang"]) ? htmlspecialchars($donhang["doitacgiaohang"]) : "Giao hàng tiêu chuẩn" ?></strong></p>
            </div>

            <!-- Bên nhận -->
            <div class="shipping-box">
                <div class="shipping-box-title">
                    <i class="fa fa-user"></i> Người nhận hàng
                </div>
                <p class="recipient-name"><?= htmlspecialchars($donhang["tennguoinhan"]) ?></p>
                <p class="recipient-phone"><i class="fa fa-phone me-1"></i><?= htmlspecialchars($donhang["sdtnhan"]) ?></p>
                <p><i class="fa fa-map-pin me-1"></i><?= htmlspecialchars($donhang["diachinhan"]) ?></p>
                <?php if (!empty($donhang["ghichu"])): ?>
                    <p style="color: #b91c1c; font-weight: 500;">
                        <i class="fa fa-comment-dots me-1"></i>Ghi chú: <?= htmlspecialchars($donhang["ghichu"]) ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Banner Thanh toán / Thu COD -->
        <div class="payment-banner <?= $isDaThanhToan ? 'is-paid' : 'is-cod' ?>">
            <div>
                <div class="payment-banner-label">
                    Phương thức: <strong><?= htmlspecialchars($dhModel->laytenphuongthuc($donhang["phuongthuc_tt"])) ?></strong>
                </div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                    <?php if ($isDaThanhToan): ?>
                        <span style="color: #16a34a; font-weight: 700;"><i class="fa fa-check-circle me-1"></i> ĐÃ THANH TOÁN TOÀN BỘ TRƯỚC - KHÔNG THU THÊM TIỀN</span>
                    <?php else: ?>
                        <span style="color: #dc2626; font-weight: 700;"><i class="fa fa-money-bill-wave me-1"></i> THU TIỀN KHI GIAO HÀNG (TIỀN MẶT / COD)</span>
                    <?php endif; ?>
                </div>
            </div>
            <div style="text-align: right;">
                <div class="payment-banner-label">TIỀN THU NGƯỜI NHẬN:</div>
                <div class="payment-banner-val" style="color: <?= $isDaThanhToan ? '#16a34a' : '#b91c1c' ?>;">
                    <?= number_format($tongTienPhaiThu, 0, ',', '.') ?> đ
                </div>
            </div>
        </div>

        <!-- Bảng Sản Phẩm Trong Đơn -->
        <table class="table-products">
            <thead>
                <tr>
                    <th class="td-center" style="width: 45px;">STT</th>
                    <th>Tên Sản Phẩm / Quy Cách</th>
                    <th class="td-center" style="width: 80px;">Size</th>
                    <th class="td-center" style="width: 70px;">SL</th>
                    <th class="td-right" style="width: 110px;">Đơn Giá</th>
                    <th class="td-right" style="width: 120px;">Thành Tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $stt = 1;
                foreach ($chitiet as $item): 
                    $sizeName = !empty($item["size"]) ? htmlspecialchars($item["size"]) : "Freesize";
                ?>
                    <tr>
                        <td class="td-center"><?= $stt++ ?></td>
                        <td>
                            <div class="product-name"><?= htmlspecialchars($item["tensanpham"]) ?></div>
                            <div class="product-meta">Mã SP: #<?= (int)$item["sanpham_id"] ?></div>
                        </td>
                        <td class="td-center"><strong><?= $sizeName ?></strong></td>
                        <td class="td-center"><strong><?= (int)$item["soluong"] ?></strong></td>
                        <td class="td-right"><?= number_format((float)$item["dongia"], 0, ',', '.') ?> đ</td>
                        <td class="td-right"><strong><?= number_format((float)$item["thanhtien"], 0, ',', '.') ?> đ</strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Bảng Tổng Tiền -->
        <div class="summary-wrapper">
            <table class="summary-table">
                <tr>
                    <td class="label">Tiền hàng (Tạm tính):</td>
                    <td class="value"><?= number_format((float)$donhang["tamtinh"], 0, ',', '.') ?> đ</td>
                </tr>
                <?php if ((float)$donhang["giamgia"] > 0): ?>
                    <tr>
                        <td class="label">Giảm giá Voucher:</td>
                        <td class="value" style="color: #16a34a;">-<?= number_format((float)$donhang["giamgia"], 0, ',', '.') ?> đ</td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td class="label">Phí vận chuyển:</td>
                    <td class="value">
                        <?= ((float)$donhang["phivanchuyen"] > 0) ? (number_format((float)$donhang["phivanchuyen"], 0, ',', '.') . ' đ') : 'Miễn phí' ?>
                    </td>
                </tr>
                <tr class="total-row">
                    <td class="label"><strong>TỔNG TIỀN ĐƠN HÀNG:</strong></td>
                    <td class="value total-value"><?= number_format((float)$donhang["tongtien"], 0, ',', '.') ?> đ</td>
                </tr>
            </table>
        </div>

        <!-- Số tiền bằng chữ -->
        <div class="words-total">
            <strong>Số tiền bằng chữ:</strong> <?= docSoThanhChu($donhang["tongtien"]) ?>.
        </div>

        <!-- Ghi chú cho Shipper & Khách -->
        <div class="shipping-notes">
            <p><strong>Lưu ý giao hàng:</strong> Cho khách kiểm tra số lượng và quy cách sản phẩm trước khi thanh toán. Không bóc tem niêm phong sản phẩm dinh dưỡng / thực phẩm bổ sung. Nếu có vấn đề phát sinh, vui lòng liên hệ hotline <strong>0987.654.321</strong> trước khi hoàn hàng.</p>
        </div>

        <!-- Chữ ký -->
        <div class="signature-grid">
            <div>
                <div class="signature-title">Người Lập Phiếu</div>
                <div class="signature-sub">(Ký & ghi rõ họ tên)</div>
                <div class="signature-space"></div>
                <div style="font-weight: 600; font-size: 12px;"><?= htmlspecialchars($_SESSION["nguoidung"]["hoten"] ?? "Quản trị viên") ?></div>
            </div>
            <div>
                <div class="signature-title">Nhân Viên Giao Hàng</div>
                <div class="signature-sub">(Ký & ghi rõ họ tên)</div>
                <div class="signature-space"></div>
            </div>
            <div>
                <div class="signature-title">Người Nhận Hàng</div>
                <div class="signature-sub">(Xác nhận đã nhận đủ hàng)</div>
                <div class="signature-space"></div>
            </div>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            Cảm ơn Quý khách đã tin tưởng và đồng hành cùng GymBro! Mọi thắc mắc xin liên hệ website gymbro.vn hoặc hotline 0987.654.321
        </div>

    </div>

</body>
</html>

