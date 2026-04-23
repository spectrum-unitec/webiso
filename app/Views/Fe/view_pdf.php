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
                        width="100%"
                        height="600"
                        style="border:none">
                    </iframe>
                </div>

            </div>
            <!-- END col-9-->
            <!-- BEGIN col-3 -->
            <div class="col-xl-3">
                <h5 class="d-flex align-items-end" style="padding: .1875rem 1.5rem; min-height: 107px;">List Dokumen</h5>
                <nav id="sidebar-bootstrap" class="navbar navbar-sticky d-none d-xl-block">
                    <nav class="nav">

                        <?php
                        $segments = current_url(true)->getSegments();

                        $segment1 = $segments[0] ?? '';
                        $segment2 = $segments[1] ?? '';
                        $segment3 = $segments[2] ?? '';

                        $currentDoc = service('request')->getGet('doc');

                        $jenisSlug = $jenisDoc->slug
                            ?? $jenisOnly->slug
                            ?? $nonIso->slug;

                        switch ($segment1) {
                            case 'manual-mutu':
                                $baseUrl = base_url(route_to('home.menus', $jenisSlug));
                                break;

                            case 'document-non-iso':
                                $baseUrl = base_url(route_to('home.menus.non.iso', $jenisSlug, $segment2));
                                break;

                            default:
                                $baseUrl = base_url(route_to('home.menus.divisi', $segment1, $jenisSlug, $segment3));
                                break;
                        }
                        ?>

                        <?php foreach ($docs as $doc) : ?>
                            <?php
                            $url    = $baseUrl . '?doc=' . $doc->slug;
                            $active = ($currentDoc === $doc->slug) ? 'active' : '';
                            ?>

                            <a class="nav-link <?= $active ?>" href="<?= esc($url) ?>">
                                <span class="badge bg-primary"><?= esc($doc->no_document); ?></span>
                                <?= esc($doc->nama_document) ?>
                            </a>
                        <?php endforeach; ?>

                    </nav>
                </nav>
            </div>
            <!-- END col-3 -->
        </div>
        <!-- END row -->
    </div>
    <!-- END col-10 -->
</div>
<?= $this->endSection(); ?>

<?= $this->section('pageScripts'); ?>
<script>
    const sidebar = document.getElementById('sidebar-bootstrap');

    sidebar.addEventListener('scroll', () => {
        localStorage.setItem('sidebarScrollTop', sidebar.scrollTop);
    });

    document.addEventListener('DOMContentLoaded', () => {
        const scrollTop = localStorage.getItem('sidebarScrollTop');

        if (scrollTop !== null) {
            sidebar.scrollTop = scrollTop;
        }
    });
</script>
<?= $this->endSection(); ?>