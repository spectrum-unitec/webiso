<?= $this->extend('Fe/layouts/main'); ?>

<?php
$uri = service('uri');
$segment2 = $uri->getSegment(2);
?>

<?= $this->section('pageStyles'); ?>
<style>
    .accordion-body {
        max-height: 300px;
        overflow-y: scroll;
    }

    .active {
        color: #212837;
        font-weight: 600;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center">
    <!-- BEGIN col-10 -->
    <div class="col-xl-10">
        <!-- BEGIN row -->
        <div class="row">
            <!-- BEGIN col-9 -->
            <div class="col-xl-9">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="page-header">
                        #<?= $doc->no_document; ?> <small><?= $doc->nama_document; ?></small>
                    </h1>
    
                    <a href="<?= previous_url() ?>" class="btn btn-outline-danger">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

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
                <?php if ($segment2 !== 'prosedur-mutu') : ?>
                    <div class="accordion" style="margin-top: 85px;" id="accordionSidebar">

                        <?php
                        $segments = current_url(true)->getSegments();

                        $segment1 = $segments[0] ?? '';
                        $segment2 = $segments[1] ?? '';
                        $segment3 = $segments[2] ?? '';

                        $currentDoc = service('request')->getGet('doc');

                        $jenisSlug = $jenisDoc->slug
                            ?? $jenisOnly->slug
                            ?? $nonIso->slug;

                        // 🔥 BASE URL hanya untuk LIST DOC
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

                        $sections = [
                            'list-doc' => 'List Dokumen',
                        ];
                        ?>

                        <?php $i = 0;
                        foreach ($sections as $id => $title) : ?>

                            <?php
                            // 🔥 Tentukan data
                            if ($id === 'list-doc') {
                                $dataList = $docs ?? [];
                            } elseif ($id === 'rekaman') {
                                $dataList = $rekamanMutuList ?? [];
                            } else {
                                $dataList = $docTerkaitList ?? [];
                            }
                            ?>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?= $i ?>">
                                    <button class="accordion-button <?= $i !== 0 ? 'collapsed' : '' ?>"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse<?= $i ?>">
                                        <?= $title ?>
                                    </button>
                                </h2>

                                <div id="collapse<?= $i ?>"
                                    class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>"
                                    data-bs-parent="#accordionSidebar">

                                    <div class="accordion-body p-2">
                                        <nav class="nav flex-column">

                                            <?php if (!empty($dataList)) : ?>


                                                <?php foreach ($dataList as $item) : ?>

                                                    <?php
                                                    // 🔥 DETEKSI RELASI (rekaman + terkait)
                                                    $isRelasi = in_array($id, ['rekaman', 'terkait']);

                                                    // 🔥 ambil slug document
                                                    $slugDoc = $isRelasi
                                                        ? ($item->doc_slug ?? $item->slug)
                                                        : $item->slug;

                                                    $no   = $item->no_document;
                                                    $nama = $item->nama_document;

                                                    // 🔥 URL LOGIC
                                                    if ($isRelasi) {

                                                        if (!empty($item->kode_divisi)) {

                                                            $url = base_url(route_to(
                                                                'home.menus.divisi',
                                                                url_title($item->nama_dept, '-', true),
                                                                $item->slug,
                                                                $item->kode_divisi
                                                            )) . '?doc=' . $slugDoc;
                                                        } else {

                                                            $url = base_url(route_to(
                                                                'home.menus',
                                                                $item->slug
                                                            )) . '?doc=' . $slugDoc;
                                                        }
                                                    } else {

                                                        $url = $baseUrl . '?doc=' . $slugDoc;
                                                    }

                                                    $active = ($currentDoc === $slugDoc) ? 'active' : '';
                                                    ?>

                                                    <a class="nav-link <?= $active ?>" href="<?= esc($url) ?>">
                                                        <span class="badge bg-primary me-2">
                                                            <?= esc($no); ?>
                                                        </span>
                                                        <?= esc($nama) ?>
                                                    </a>

                                                <?php endforeach; ?>

                                            <?php else : ?>
                                                <span class="text-muted small px-2">Tidak ada data</span>
                                            <?php endif; ?>

                                        </nav>
                                    </div>
                                </div>
                            </div>

                        <?php $i++;
                        endforeach; ?>

                    </div>
                <?php else : ?>
                    <div class="accordion" style="margin-top: 85px;" id="accordionSidebar">

                        <?php
                        $segments = current_url(true)->getSegments();

                        $segment1 = $segments[0] ?? '';
                        $segment2 = $segments[1] ?? '';
                        $segment3 = $segments[2] ?? '';

                        $currentDoc = service('request')->getGet('doc');

                        $jenisSlug = $jenisDoc->slug
                            ?? $jenisOnly->slug
                            ?? $nonIso->slug;

                        // 🔥 BASE URL hanya untuk LIST DOC
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

                        $sections = [
                            'list-doc' => 'List Dokumen',
                            'rekaman'  => 'Link Rekaman Mutu',
                            'terkait'  => 'Link Dokumen Terkait',
                        ];
                        ?>

                        <?php $i = 0;
                        foreach ($sections as $id => $title) : ?>

                            <?php
                            // 🔥 Tentukan data
                            if ($id === 'list-doc') {
                                $dataList = $docs ?? [];
                            } elseif ($id === 'rekaman') {
                                $dataList = $rekamanMutuList ?? [];
                            } else {
                                $dataList = $docTerkaitList ?? [];
                            }
                            ?>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?= $i ?>">
                                    <button class="accordion-button 
                                        <?= $i !== 0 ? 'collapsed' : '' ?> 
                                        <?= $id === 'rekaman' ? 'bg-primary text-white' : '' ?>
                                        <?= $id === 'terkait' ? 'bg-primary text-white' : '' ?>"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse<?= $i ?>">
                                        <?= $title ?>
                                    </button>
                                </h2>

                                <div id="collapse<?= $i ?>"
                                    class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>"
                                    data-bs-parent="#accordionSidebar">

                                    <div class="accordion-body p-2">
                                        <nav class="nav flex-column">

                                            <?php if (!empty($dataList)) : ?>


                                                <?php foreach ($dataList as $item) : ?>

                                                    <?php
                                                    // 🔥 DETEKSI RELASI (rekaman + terkait)
                                                    $isRelasi = in_array($id, ['rekaman', 'terkait']);

                                                    // 🔥 ambil slug document
                                                    $slugDoc = $isRelasi
                                                        ? ($item->doc_slug ?? $item->slug)
                                                        : $item->slug;

                                                    $no   = $item->no_document;
                                                    $nama = $item->nama_document;

                                                    // 🔥 URL LOGIC
                                                    if ($isRelasi) {

                                                        if (!empty($item->kode_divisi)) {

                                                            $url = base_url(route_to(
                                                                'home.menus.divisi',
                                                                url_title($item->nama_dept, '-', true),
                                                                $item->slug,
                                                                $item->kode_divisi
                                                            )) . '?doc=' . $slugDoc;
                                                        } else {

                                                            $url = base_url(route_to(
                                                                'home.menus',
                                                                $item->slug
                                                            )) . '?doc=' . $slugDoc;
                                                        }
                                                    } else {

                                                        $url = $baseUrl . '?doc=' . $slugDoc;
                                                    }

                                                    $active = ($currentDoc === $slugDoc) ? 'active' : '';
                                                    ?>

                                                    <a class="nav-link <?= $active ?>" href="<?= esc($url) ?>">
                                                        <span class="badge bg-primary me-2">
                                                            <?= esc($no); ?>
                                                        </span>
                                                        <?= esc($nama) ?>
                                                    </a>

                                                <?php endforeach; ?>

                                            <?php else : ?>
                                                <span class="text-muted small px-2">Tidak ada data</span>
                                            <?php endif; ?>

                                        </nav>
                                    </div>
                                </div>
                            </div>

                        <?php $i++;
                        endforeach; ?>

                    </div>
                <?php endif; ?>
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