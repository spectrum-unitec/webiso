<?= $this->extend('Fe/layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center">
    <!-- BEGIN col-10 -->
    <div class="col-xl-10">
        <!-- BEGIN row -->
        <div class="row">
            <!-- BEGIN col-9 -->
            <div class="col-xl-9">
                <h1 class="page-header">
                    #<?= $doc->no_document; ?> <small><?= $doc->nama_document; ?></small>
                </h1>

                <hr class="mb-4">

                <div id="pdfWrapper">
                    <iframe
                        src="<?= base_url('pdfjs/web/viewer.html') ?>?file=<?= base_url(route_to('pdf', $doc->id)) ?>"
                        sandbox="allow-scripts allow-same-origin"
                        width="100%"
                        height="600"
                        style="border:none">
                    </iframe>
                </div>

            </div>
            <!-- END col-9-->
            <!-- BEGIN col-3 -->
            <div class="col-xl-3">
                <!-- BEGIN #sidebar-bootstrap -->
                <nav id="sidebar-bootstrap" class="navbar navbar-sticky d-none d-xl-block">
                    <nav class="nav">
                        <?php
                        $uri = current_url(true);

                        $segment1 = $uri->getSegment(1, '');
                        $segment2 = $uri->getSegment(2, '');

                        $currentDoc = service('request')->getGet('doc');

                        $jenisSlug = $jenisDoc
                            ? $jenisDoc->slug
                            : $jenisOnly->slug;
                        ?>

                        <?php foreach ($docs as $doc) : ?>
                            <?php
                            if ($segment2 !== '') {
                                // route dengan divisi
                                $baseUrl = base_url(route_to(
                                    'home.menus.divisi',
                                    $jenisSlug,
                                    $segment2
                                ));
                            } else {
                                // route tanpa divisi
                                $baseUrl = base_url(route_to(
                                    'home.menus',
                                    $jenisSlug
                                ));
                            }

                            $url    = $baseUrl . '?doc=' . $doc->slug;
                            $active = ($currentDoc === $doc->slug) ? 'active' : '';
                            ?>

                            <a class="nav-link <?= $active ?>" href="<?= $url ?>">
                                <?= esc($doc->nama_document) ?>
                            </a>
                        <?php endforeach; ?>
                    </nav>
                </nav>

                <!-- END #sidebar-bootstrap -->
            </div>
            <!-- END col-3 -->
        </div>
        <!-- END row -->
    </div>
    <!-- END col-10 -->
</div>
<?= $this->endSection(); ?>