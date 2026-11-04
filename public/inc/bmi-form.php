<?php
if (!isset($gsModule36)) {
    header('Location: ../index.php');
    exit();
}

$cohoso = ($gsGoiy && $gsGoiy['cohoso']);
$hoso = $cohoso ? $gsGoiy : null;
?>
<div class="gs-card gs-chiso p-0 border-0 bg-transparent">
    <div class="row g-3">
        <form method="<?php echo ($gsNguoidungId > 0) ? 'post' : 'get'; ?>" action="index.php" class="row g-3 m-0 p-0">
            <input type="hidden" name="action" value="<?php echo ($gsNguoidungId > 0) ? 'luuhoso' : 'goy'; ?>">

            <div class="col-6 col-lg-3">
                <label class="gs-form-label" for="gsChieuCao">Chiều cao (cm)</label>
                <input class="form-control gs-input" type="number" id="gsChieuCao"
                    name="chieucao" min="100" max="250" step="0.1" placeholder="VD: 173" required
                    value="<?php echo $hoso ? htmlspecialchars($hoso['chieucao']) : ''; ?>">
            </div>
            <div class="col-6 col-lg-3">
                <label class="gs-form-label" for="gsCanNang">Cân nặng (kg)</label>
                <input class="form-control gs-input" type="number" id="gsCanNang"
                    name="cannang" min="25" max="300" step="0.1" placeholder="VD: 70" required
                    value="<?php echo $hoso ? htmlspecialchars($hoso['cannang']) : ''; ?>">
            </div>
            <div class="col-6 col-lg-2">
                <label class="gs-form-label" for="gsGioiTinh">Giới tính</label>
                <select class="form-select gs-input" id="gsGioiTinh" name="gioitinh">
                    <option value="nam" <?php echo ($hoso && $hoso['gioitinh'] === 'nam') ? 'selected' : ''; ?>>Nam</option>
                    <option value="nu" <?php echo ($hoso && $hoso['gioitinh'] === 'nu') ? 'selected' : ''; ?>>Nữ</option>
                </select>
            </div>
            <div class="col-6 col-lg-2">
                <label class="gs-form-label" for="gsTuoi">Tuổi</label>
                <input class="form-control gs-input" type="number" id="gsTuoi"
                    name="tuoi" min="10" max="100" placeholder="VD: 22" required
                    value="<?php echo $hoso ? htmlspecialchars($hoso['tuoi']) : ''; ?>">
            </div>
            <div class="col-12 col-lg-2">
                <label class="gs-form-label" for="gsMucTieu">Mục tiêu</label>
                <select class="form-select gs-input" id="gsMucTieu" name="muctieu">
                    <option value="giamcan" <?php echo ($hoso && $hoso['muctieu'] === 'giamcan') ? 'selected' : ''; ?>>Giảm mỡ</option>
                    <option value="tangcan" <?php echo ($hoso && $hoso['muctieu'] === 'tangcan') ? 'selected' : ''; ?>>Tăng cơ</option>
                    <option value="duytri" <?php echo ($hoso && $hoso['muctieu'] === 'duytri') ? 'selected' : ''; ?>>Duy trì</option>
                </select>
            </div>

            <div class="col-12 d-flex flex-wrap align-items-center gap-2 mt-3">
                <button type="submit" class="btn-gs btn-gs--primary btn-gs--md">
                    <?php echo ($gsNguoidungId > 0) ? 'LƯU CHỈ SỐ THỂ TRẠNG' : 'TÍNH CHỈ SỐ NGAY'; ?>
                </button>
                <a class="btn-gs btn-gs--outline btn-gs--sm" href="index.php">
                    Bỏ qua và về trang chủ
                </a>
            </div>
        </form>
    </div>
</div>
