<?= $this->extend('Fe/Layouts/main'); ?>

<?php $this->section('pageStyles'); ?>
<style>
    .list-group a .list-group-item {
        border-radius: 6px;
    }

    .list-group a {
        text-decoration: auto;
    }

    .list-group a :hover {
        background-color: #a0c1ff;
    }
</style>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<!-- BEGIN row -->
<div class="row justify-content-center">

    <!-- BEGIN col-10 -->
    <div class="col-xl-10">
        <!-- BEGIN row -->
        <div class="row justify-content-center">
            <!-- BEGIN col-9 -->
            <div class="col-xl-10">

                <h1 class="page-header">
                    Dokumen Non ISO
                </h1>

                <hr class="mb-4">

                <div class="row">

                    <?php if (empty($listCategory)) : ?>

                        <div class="col-12">
                            <div class="alert alert-warning text-center fw-bold">
                                <div><i class=" fa fa-file-alt fs-1 mb-1"></i></div>
                                Tidak ada data
                            </div>
                        </div>

                    <?php else : ?>
                        <?php foreach ($listCategory as $list) : ?>
                            <div class="list-group mb-3 col-3">
                                <a href="<?= base_url(route_to('home.menus.non.iso', $list->slug_jenis_doc, $list->slug)); ?>">
                                    <div class="list-group-item d-flex align-items-center">
                                        <div class="flex-fill px-3 py-1">
                                            <div class="fw-semibold"><?= $list->nama; ?></div>
                                            <div class="small text-body text-opacity-50"><?= $list->total_doc; ?> dokumen</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
<?php $this->endSection(); ?>

<?php $this->section('pageScipts'); ?>
<?php $this->endSection(); ?>