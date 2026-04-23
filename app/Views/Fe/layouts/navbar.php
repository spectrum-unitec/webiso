<?php
$uri = service('uri'); // ini object CodeIgniter\HTTP\URI
$segments = $uri->getSegments();
$seg1 = $segments[0] ?? '';
$seg2 = $segments[1] ?? '';
$seg3 = $segments[2] ?? '';
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

                <?php
                $total = $countDocs['manual-mutu'][$jenisOnly->id] ?? 0;
                ?>

                <div class="menu-item <?= ($seg1 === $jenisOnly->slug) ? 'active' : '' ?>">
                    <a href="<?= base_url(route_to('home.menus', $jenisOnly->slug)); ?>" class="menu-link">
                        <span class="menu-text"><?= $jenisOnly->jenis_document . ' (' . $total . ')' ?></span>
                    </a>
                </div>
            <?php endif; ?>


            <?php foreach ($navs as $dept) : ?>

                <?php if (!empty($dept['nama_dept'])) : ?>
                    <!-- DEPARTEMEN -->
                    <div class="menu-item has-sub  <?= $seg1 === url_title($dept['nama_dept'], '-', true) ? 'active' : ''; ?>">
                        <a href="#" class="menu-link">
                            <span class="menu-text"><?= $dept['nama_dept']; ?></span>
                            <span class="menu-caret"><b class="caret"></b></span>
                        </a>

                        <div class="menu-submenu">
                            <?php foreach ($dept['divisi'] as $row) : ?>
                                <!-- DIVISI -->
                                <div class="menu-item has-sub <?= $seg3 === $row->kode_divisi ? 'active' : ''; ?>">
                                    <a href="#" class="menu-link">
                                        <span class="menu-text"><?= $row->nama_divisi; ?></span>
                                        <span class="menu-caret"><b class="caret"></b></span>
                                    </a>

                                    <div class="menu-submenu submenu-right">

                                        <?php foreach ($jenisAll as $jenis) : ?>

                                            <?php
                                            $total = $countDocs['iso'][$row->id][$jenis->id] ?? 0;
                                            ?>

                                            <!-- JENIS -->
                                            <div class="menu-item <?= ($seg2 === $jenis->slug && $seg3 === $row->kode_divisi) ? 'active' : ''; ?>">
                                                <a href="<?= base_url(route_to('home.menus.divisi', url_title($dept['nama_dept'], '-', true), $jenis->slug, $row->kode_divisi)); ?>" class="menu-link">
                                                    <span class="menu-text"><?= $jenis->jenis_document; ?> (<?= $total; ?>)</span>
                                                </a>
                                            </div>

                                        <?php endforeach; ?>

                                    </div>
                                </div>

                            <?php endforeach; ?>

                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if ($nonIso) : ?>
                <div class="menu-item <?= ($seg1 === $nonIso->slug) ? 'active' : '' ?>">
                    <a href="<?= base_url(route_to('home.doc.non.iso')); ?>" class="menu-link">
                        <span class="menu-text"><?= $nonIso->jenis_document; ?></span>
                    </a>
                </div>
            <?php endif; ?>

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