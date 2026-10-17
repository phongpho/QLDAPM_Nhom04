<?php
if (!isset($gsModule36)) {
    header('Location: ../index.php');
    exit();
}

$cohoso = ($gsGoiy && $gsGoiy['cohoso']);
$hoso = $cohoso ? $gsGoiy : null;
?>
<div class="gs-card gs-chiso">
    <h3 class="gs-chiso__title">
        <i class="fa-solid fa-calculator text-danger"></i>
        <?php echo $cohoso ? 'Cập nhật chỉ số cơ thể' : 'Nhập chỉ số cơ thể của bạn'; ?>
    </h3>
    <p class="gs-chiso__desc">
        Hệ thống tính BMI, tra bảng size theo chiều cao &amp; cân nặng và gợi ý sản phẩm, combo phù hợp với mục tiêu tập luyện.
    </p>

    <form method="<?php echo ($gsNguoidungId > 0) ? 'post' : 'get'; ?>" action="index.php" class="row g-3">
        <input type="hidden" name="action" value="<?php echo ($gsNguoidungId > 0) ? 'luuhoso' : 'goy'; ?>">

        <div class="col-6 col-lg-3">
            <label class="gs-form-label" for="gsChieuCao">Chiều cao (cm)</label>
            <input class="form-control bg-black text-white border-secondary" type="number" id="gsChieuCao"
                name="chieucao" min="100" max="250" step="0.1" placeholder="VD: 173" required
                value="<?php echo $hoso ? htmlspecialchars($hoso['chieucao']) : ''; ?>">
        </div>
        <div class="col-6 col-lg-3">
            <label class="gs-form-label" for="gsCanNang">Cân nặng (kg)</label>
            <input class="form-control bg-black text-white border-secondary" type="number" id="gsCanNang"
                name="cannang" min="25" max="300" step="0.1" placeholder="VD: 70" required
                value="<?php echo $hoso ? htmlspecialchars($hoso['cannang']) : ''; ?>">
        </div>
        <div class="col-6 col-lg-2">
            <label class="gs-form-label" for="gsGioiTinh">Giới tính</label>
            <select class="form-select bg-black text-white border-secondary" id="gsGioiTinh" name="gioitinh">
                <option value="nam" <?php echo ($hoso && $hoso['gioitinh'] === 'nam') ? 'selected' : ''; ?>>Nam</option>
                <option value="nu" <?php echo ($hoso && $hoso['gioitinh'] === 'nu') ? 'selected' : ''; ?>>Nữ</option>
            </select>
        </div>
        <div class="col-6 col-lg-2">
            <label class="gs-form-label" for="gsTuoi">Tuổi</label>
            <input class="form-control bg-black text-white border-secondary" type="number" id="gsTuoi"
                name="tuoi" min="10" max="100" placeholder="VD: 22" required
                value="<?php echo $hoso ? htmlspecialchars($hoso['tuoi']) : ''; ?>">
        </div>
        <div class="col-12 col-lg-2">
            <label class="gs-form-label" for="gsMucTieu">Mục tiêu</label>
            <select class="form-select bg-black text-white border-secondary" id="gsMucTieu" name="muctieu">
                <option value="giamcan" <?php echo ($hoso && $hoso['muctieu'] === 'giamcan') ? 'selected' : ''; ?>>Giảm cân / Giảm mỡ</option>
                <option value="tangcan" <?php echo ($hoso && $hoso['muctieu'] === 'tangcan') ? 'selected' : ''; ?>>Tăng cân / Tăng cơ</option>
                <option value="duytri" <?php echo ($hoso && $hoso['muctieu'] === 'duytri') ? 'selected' : ''; ?>>Duy trì vóc dáng</option>
            </select>
        </div>

        <div class="col-12 d-flex flex-wrap align-items-center gap-3">
            <button type="submit" class="btn-gs btn-gs--light">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <?php echo ($gsNguoidungId > 0) ? 'Lưu chỉ số & xem gợi ý' : 'Tính chỉ số & gợi ý ngay'; ?>
            </button>
            <?php if ($gsNguoidungId > 0) : ?>
                <a class="gs-chiso__link" href="index.php?action=goy">Xem trang gợi ý cá nhân hóa <i class="fa-solid fa-arrow-right-long"></i></a>
            <?php else : ?>
                <span class="small text-secondary">
                    <i class="fa-solid fa-circle-info"></i>
                    <a class="gs-chiso__link" href="../admin/ktnguoidung/index.php?action=dangnhap">Đăng nhập</a>
                    để lưu hồ sơ thể trạng và nhận gợi ý cho mọi lần mua sắm sau.
                </span>
            <?php endif; ?>
        </div>
    </form>
</div>
