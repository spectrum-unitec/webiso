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

                    <?php if (empty($docs)) : ?>

                        <div class="col-12">
                            <div class="alert alert-warning text-center fw-bold">
                                <div><i class=" fa fa-file-alt fs-1 mb-1"></i></div>
                                Tidak ada dokumen
                            </div>
                        </div>

                    <?php else : ?>

                        <div class="position-relative mb-2">
                            <div class="input-group">
                                <span class="input-group-text" style="background-color: white;">
                                    <i class="fa fa-search"></i>
                                </span>
                                <input type="text" id="searchDoc" class="form-control" placeholder="Cari berdasarkan nama atau nomor dokumen..">
                            </div>

                            <div id="searchDropdown" class="list-group shadow position-absolute w-100 mt-1" style="z-index: 1000; display:none; max-height:380px; overflow:auto;"></div>
                        </div>

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
                                            <div class="badge bg-primary"><?= esc($doc->no_document); ?></div>
                                            <span class="fw-bold"><?= esc($doc->nama_document) ?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach ?>

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

<?= $this->endSection(); ?>

<?= $this->section('pageScripts'); ?>

<script>
    $(function() {

        let xhr = null;
        let timer;
        let offset = 0;
        let loading = false;
        let finished = false;

        let jenis = "<?= $segment1 ?>";
        let divisi = "<?= $segment2 ?>";

        function loadData(reset = false) {

            if (loading || finished) return;

            let query = $('#searchDoc').val();

            if (query.length < 2) {
                $('#searchDropdown').hide().html('');
                offset = 0;
                finished = false;
                return;
            }

            if (reset) {
                offset = 0;
                finished = false;
                $('#searchDropdown').html('');
            }

            loading = true;

            if (xhr) xhr.abort();

            xhr = $.ajax({
                url: "<?= base_url(route_to('search_doc')) ?>",
                method: "GET",
                dataType: "json",
                data: {
                    q: query,
                    jenis: jenis,
                    divisi: divisi,
                    offset: offset
                },
                success: function(res) {

                    let html = '';

                    if (res.length > 0) {
                        res.forEach(function(item) {
                            html += `
                            <a href="${item.url}" class="list-group-item list-group-item-action">
                                <div class="fw-bold">${item.no_document}</div>
                                <small>${item.nama_document}</small>
                            </a>
                        `;
                        });

                        $('#searchDropdown').append(html).show();
                        offset += res.length;

                    } else {
                        if (offset === 0) {
                            $('#searchDropdown')
                                .html(`<div class="list-group-item text-muted">Tidak ditemukan</div>`)
                                .show();
                        }
                        finished = true;
                    }
                },
                error: function() {
                    console.log('Request error');
                },
                complete: function() {
                    loading = false;
                }
            });
        }

        function debounce() {
            clearTimeout(timer);
            timer = setTimeout(() => loadData(true), 300);
        }

        $('#searchDoc').on('keyup', debounce);

        $('#searchDropdown').on('scroll', function() {

            let el = this;

            if (el.scrollTop + el.clientHeight >= el.scrollHeight - 10) {
                loadData();
            }
        });

    });
</script>

<?= $this->endSection(); ?>