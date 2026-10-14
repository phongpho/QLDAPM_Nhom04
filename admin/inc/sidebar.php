<nav id="sidebar" class="sidebar js-sidebar">
	<div class="sidebar-content js-simplebar">
		<a class="sidebar-brand" href="">
			<span class="align-middle">GymShop</span>
		</a>

		<ul class="sidebar-nav">
			<li class="sidebar-header text-info">
				HỆ THỐNG
			</li>

			<?php if (isset($_SESSION["nguoidung"]) && $_SESSION["nguoidung"]["loai"] == 1) { ?>
				<li class="sidebar-item <?php if (strpos($_SERVER['REQUEST_URI'], "qlnguoidung") !== false) echo "active"; ?>">
					<a class="sidebar-link" href="../qlnguoidung/index.php">
						<i class="align-middle" data-feather="users"></i>
						<span class="align-middle">Quản lý nhân sự</span>
					</a>
				</li>
			<?php } ?>

			<li class="sidebar-header text-info">
				DANH MỤC
			</li>

			<li class="sidebar-item">
				<a class="sidebar-link" href="../qldanhmuc/index.php">
					<i class="align-middle" data-feather="grid"></i> <span class="align-middle">Quản lý danh mục</span>
				</a>
			</li>

			<li class="sidebar-item">
				<a class="sidebar-link" href="../qlmathang/index.php">
					<i class="align-middle" data-feather="package"></i> <span class="align-middle">Quản lý hàng hóa</span>
				</a>
			</li>

			<li class="sidebar-item">
				<a class="sidebar-link" href="">
					<i class="align-middle" data-feather="users"></i> <span class="align-middle">Quản lý khách hàng</span>
				</a>
			</li>

			<li class="sidebar-item">
				<a class="sidebar-link" href="">
					<i class="align-middle" data-feather="truck"></i> <span class="align-middle">Quản lý đơn hàng</span>
				</a>
			</li>

			<li class="sidebar-item">
				<a class="sidebar-link" href="">
					<i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Quản lý doanh thu</span>
				</a>
			</li>

			<li class="sidebar-item">
				<a class="sidebar-link" href="">
					<i class="align-middle" data-feather="shopping-bag"></i> <span class="align-middle">Chương trình khuyến mãi</span>
				</a>
			</li>
		</ul>
	</div>
</nav>