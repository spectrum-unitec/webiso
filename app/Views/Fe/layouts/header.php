<!-- BEGIN #header -->
<div id="header" class="app-header">
    <!-- BEGIN mobile-toggler -->
    <div class="mobile-toggler">
        <button type="button" class="menu-toggler" data-toggle="top-nav-mobile">
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </div>
    <!-- END mobile-toggler -->

    <!-- BEGIN brand -->

    <div class="brand">
        <img src="<?= base_url(); ?>assets/img/spectrum-logo.png" width="80px">
        <!-- <a href="index.html" class="brand-logo">
                <h4>WebIso</h4>
             </a> -->
    </div>


    <!-- END brand -->
    <div style="margin-right: 10px;">
        <button class="btn btn-primary" data-bs-target="#createDocument" data-bs-toggle="modal"><i class="fas fa-file-alt"></i> Form Usulan Dokumen</button>
    </div>
</div>
<!-- END #header -->

<!-- Modal create document -->
<div class="modal fade" id="createDocument" tabindex="-1" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Usulan Perubahan/Pengadaan Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCreateDocument" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="exampleFormControlInput1">NO Dokumen</label>
                        <input type="text" name="no_doc" id="title" class="form-control" placeholder="Masukan no dokumen">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="exampleFormControlInput1">Nama Dokumen</label>
                        <input type="text" name="nm_doc" id="title" class="form-control" placeholder="Masukan nama dokumen">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="defaultFile">Upload file PDF</label>
                        <input type="file" name="pdf_file" accept="application/pdf" class="form-control" id="pdfFile">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>