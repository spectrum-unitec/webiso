<?= $this->extend('Fe/layouts/main') ?>

<?= $this->section('content'); ?>

<!-- BEGIN row -->
<div class="row justify-content-center">
    <!-- BEGIN col-10 -->
    <div class="col-xl-10">
        <!-- BEGIN row -->
        <div class="row justify-content-center">
            <!-- BEGIN col-9 -->
            <div class="col-xl-10">
                <h1 class="page-header">
                    Daftar Dokumen <small><?= empty($jenisDoc) ? $jenisOnly->jenis_document : $jenisDoc->jenis_document ?></small>
                </h1>

                <hr class="mb-4">

                <div class="row">
                    <?php
                    $segment1 = current_url(true)->getSegment(1, '');
                    $segment2 = current_url(true)->getSegment(2, '');

                    $jenisSlug = $jenisDoc
                        ? $jenisDoc->slug
                        : $jenisOnly->slug;
                    ?>

                    <?php foreach ($docs as $doc) : ?>
                        <div class="col-6">
                            <?php
                            $url = ($segment1 === 'manual-mutu')
                                ? base_url(route_to('home.menus', $jenisSlug))
                                : base_url(route_to('home.menus.divisi', $jenisSlug, $segment2));

                            $url .= '?doc=' . $doc->slug;
                            ?>

                            <a href="<?= esc($url) ?>" class="link-doc">
                                <div class="card mb-2">
                                    <div class="card-body d-flex align-items-center gap-2">
                                        <img src="<?= base_url('assets/img/pdf.png') ?>" width="35" alt="PDF">
                                        <span><?= esc($doc->nama_document) ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach ?>
                </div>

            </div>
            <!-- END col-9-->
            <!-- BEGIN col-3 -->

            <!-- END col-3 -->
        </div>
        <!-- END row -->
    </div>
    <!-- END col-10 -->
</div>
<!-- END row -->

<?= $this->endSection(); ?>