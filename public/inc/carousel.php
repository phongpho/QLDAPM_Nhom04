<?php
$v1 = file_exists(__DIR__ . '/../../assets/images/carousels/slide1.jpg') ? filemtime(__DIR__ . '/../../assets/images/carousels/slide1.jpg') : time();
$v2 = file_exists(__DIR__ . '/../../assets/images/carousels/slide2.jpg') ? filemtime(__DIR__ . '/../../assets/images/carousels/slide2.jpg') : time();
$v3 = file_exists(__DIR__ . '/../../assets/images/carousels/slide3.jpg') ? filemtime(__DIR__ . '/../../assets/images/carousels/slide3.jpg') : time();
?>
<div id="gymbroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#gymbroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#gymbroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#gymbroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>

    <div class="carousel-inner">

        <div class="carousel-item active" style="min-height: 520px; background: #141414;">
            <div class="gs-hero__bg position-absolute top-0 start-0 w-100 h-100" style="background-image:url('../assets/images/carousels/slide1.jpg?v=<?php echo $v1; ?>'); background-size: cover; background-position: center 22%;"></div>
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(0,0,0,0.34) 0%, rgba(0,0,0,0.05) 35%, transparent 60%);"></div>
            <div class="container position-relative h-100 d-flex flex-column justify-content-between py-4" style="min-height: 520px; z-index: 2;">
                <div class="pt-3" style="max-width: 390px;">
                    <div class="small fw-bold text-uppercase mb-1" style="color: #FF8FA3; letter-spacing: 0.08em; text-shadow: 0 1px 6px rgba(0,0,0,0.6);">GymBro • Elegant Swing</div>
                    <p class="mb-0 small fw-semibold text-white" style="line-height: 1.55; text-shadow: 0 1px 8px rgba(0,0,0,0.65);">
                        Thanh lịch trên sân tập cùng bộ sưu tập đồ thể thao nữ cao cấp chuẩn phom dáng, co giãn 4 chiều &amp; thoáng khí vượt trội.
                    </p>
                </div>
                <div class="text-center pb-3">
                    <a href="index.php?action=sanpham&loai=quanao&doituong=nu" class="btn-gs btn-gs--light px-4 py-2" style="background: #D02045; border-color: #D02045;">
                        Mua ngay
                    </a>
                </div>
            </div>
        </div>

        <div class="carousel-item" style="min-height: 520px; background: #F7F4EC;">
            <div class="gs-hero__bg position-absolute top-0 start-0 w-100 h-100" style="background-image:url('../assets/images/carousels/slide2.jpg?v=<?php echo $v2; ?>'); background-size: cover; background-position: center 25%;"></div>
            <div class="container position-relative h-100 d-flex flex-column justify-content-between py-4" style="min-height: 520px; z-index: 2;">
                <div class="pt-3" style="max-width: 390px;">
                    <div class="small fw-bold text-uppercase mb-1" style="color: #D02545; letter-spacing: 0.08em;">Bộ Sưu Tập Candy Pop</div>
                    <p class="mb-0 small fw-semibold" style="color: #1A1A1A; line-height: 1.55;">
                        Sắc màu pastel ngọt ngào cùng chất liệu Buttery Soft co giãn 4 chiều, thoáng khí tối ưu cho Yoga, Pilates &amp; Gym.
                    </p>
                </div>
                <div class="text-center pb-3">
                    <a href="index.php?action=sanpham&loai=quanao&doituong=nu" class="btn-gs btn-gs--light px-4 py-2" style="background: #D02045; border-color: #D02045;">
                        Mua ngay
                    </a>
                </div>
            </div>
        </div>

        <div class="carousel-item" style="min-height: 520px; background: #141414;">
            <div class="gs-hero__bg position-absolute top-0 start-0 w-100 h-100" style="background-image:url('../assets/images/carousels/slide3.jpg?v=<?php echo $v3; ?>'); background-size: cover; background-position: center 18%;"></div>
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(0,0,0,0.34) 0%, rgba(0,0,0,0.05) 35%, transparent 60%);"></div>
            <div class="container position-relative h-100 d-flex flex-column justify-content-between py-4" style="min-height: 520px; z-index: 2;">
                <div class="pt-3" style="max-width: 400px;">
                    <div class="small fw-bold text-uppercase mb-1" style="color: #FF8FA3; letter-spacing: 0.08em; text-shadow: 0 1px 6px rgba(0,0,0,0.6);">GymBro • Đồ Tập &amp; Dinh Dưỡng</div>
                    <p class="mb-0 small fw-semibold text-white" style="line-height: 1.55; text-shadow: 0 1px 8px rgba(0,0,0,0.65);">
                        Trang phục tập luyện, phụ kiện và thực phẩm bổ sung được cá nhân hóa theo chỉ số cơ thể của riêng bạn.
                    </p>
                </div>
                <div class="text-center pb-3">
                    <a href="index.php?action=sanpham&loai=quanao" class="btn-gs btn-gs--light px-4 py-2" style="background: #D02045; border-color: #D02045;">
                        Khám phá ngay
                    </a>
                </div>
            </div>
        </div>

    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#gymbroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#gymbroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
