<?php
$uri = service('uri'); // ini object CodeIgniter\HTTP\URI
$segments = $uri->getSegments();
$seg1 = $segments[0] ?? '';
$seg2 = $segments[1] ?? '';
?>



<!-- BEGIN #top-nav -->
<div data-bs-theme="dark">
    <div id="top-nav" class="app-top-nav">
        <!-- BEGIN menu -->
        <div class="menu">
            <div class="menu-item  <?= nav_active_route('home') ?>">
                <a href="<?= base_url(route_to('home')); ?>" class="menu-link">
                    <span class="menu-text">Home</span>
                </a>
            </div>
            <?php if ($jenisOnly) : ?>
                <div class="menu-item <?= nav_active_route('home.menus') ?>">
                    <a href="<?= base_url(route_to('home.menus', $jenisOnly->slug)); ?>" class="menu-link">
                        <span class="menu-text"><?= $jenisOnly->jenis_document; ?></span>
                    </a>
                </div>
            <?php endif; ?>
			
            <?php foreach ($navs as $row) : ?>
				<div class="menu-item has-sub <?= $seg2 === $row->kode_divisi ? 'active' : ''; ?>">
					<a href="#" class="menu-link">
						<span class="menu-text"><?= $row->nama_divisi; ?></span>
						<span class="menu-caret"><b class="caret"></b></span>
					</a>
					<div class="menu-submenu">
						<?php foreach ($jenisAll as $jenis) : ?>
							<div class="menu-item <?= $seg1 === $jenis->slug && $seg2 === $row->kode_divisi ? 'active' : ''; ?>">
<<<<<<< HEAD
								<a href="<?= route_to('home.menus.divisi', $jenis->slug, $row->kode_divisi); ?>" class="menu-link">
=======
								<a href="<?= base_url(route_to('home.menus.divisi', $jenis->slug, $row->kode_divisi)); ?>" class="menu-link">
>>>>>>> 4c4f00b (tes)
									<span class="menu-text"><?= $jenis->jenis_document; ?></span>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>

            <div class="menu-item menu-control menu-control-start">
                <a href="javascript:;" class="menu-link" data-toggle="top-nav-prev"><i class="fa fa-chevron-left"></i></a>
            </div>
            <div class="menu-item menu-control menu-control-end">
                <a href="javascript:;" class="menu-link" data-toggle="top-nav-next"><i class="fa fa-chevron-right"></i></a>
            </div>
        </div>
        <!-- END menu -->
    </div>
    <!-- END #top-nav -->
</div>