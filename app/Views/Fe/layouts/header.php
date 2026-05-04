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
    <div class="d-flex">
        <div style="margin-right: 10px;">
            <button class="btn btn-outline-primary " data-bs-target="#historyPerubahanDoc" data-bs-toggle="modal"><i class="fas fa-history"></i> History Perubahan Dokumen</button>
        </div>
        <div style="margin-right: 10px;">
            <button class="btn btn-primary" data-bs-target="#createDocument" data-bs-toggle="modal"><i class="fas fa-file-alt"></i> Form Usulan Dokumen</button>
        </div>
    </div>
</div>
<!-- END #header -->


<!-- Modal create document -->
<div class="modal fade" id="createDocument" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form id="formReqDocument" enctype="multipart/form-data">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title">Form Usulan Perubahan/Pengadaan Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">
                    <div class="row">

                        <!-- LEFT -->
                        <div class="col-12 col-md-6">

                            <div class="mb-3">
                                <label class="form-label">Nama Pemohon</label>
                                <input type="text" name="nama_user" class="form-control" placeholder="Masukan nama pemohon">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Divisi</label>
                                <select name="divisi_id" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($divisis as $row) : ?>
                                        <option value="<?= $row->id; ?>"><?= $row->nama_dept; ?> - <?= $row->nama_divisi; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Perusahaan</label>
                                <input type="text" name="email" class="form-control" placeholder="Masukan email">
                            </div>



                            <div class="mb-3">
                                <label class="form-label">Nama Dokumen</label>
                                <input type="text" name="nama_doc" class="form-control" placeholder="Masukan nama dokumen">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">No Dokumen</label>
                                <input type="text" name="no_doc" class="form-control" placeholder="Masukan no dokumen">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">No Revisi</label>
                                <input type="text" name="revisi" class="form-control" placeholder="Masukan revisi">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Pengajuan</label>
                                <input type="date" name="tgl_pengajuan" class="form-control" placeholder="Masukan tanggal pengajuan">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis Pengajuan</label>
                                <select name="jenis_pengajuan" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="baru">Dokumen Baru</option>
                                    <option value="revisi">Revisi</option>
                                    <option value="penghapusan">Penghapusan</option>
                                </select>
                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="col-12 col-md-6">

                            <div class="mb-3">
                                <label class="form-label">Alasan Perubahan/Pengadaan</label>
                                <textarea name="alasan" id="" cols="55" rows="12"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Usulan Perubahan yang diajukan</label>
                                <textarea name="usulan" id="" cols="55" rows="12"></textarea>
                            </div>

                            <!-- <div class="mb-3">
                                <label class="form-label">Upload Dokumen (PDF)</label>
                                <input type="file" name="file_pdf" accept="application/pdf" class="form-control">
                            </div> -->

                        </div>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim & Ajukan</button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- Modal view -->
<div class="modal fade" id="historyPerubahanDoc" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title">History Perubahan Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">
                <div class="row">
                    <!-- BEGIN col-6 -->
                    <div class="col-xl-12 mb-3">
                        <!-- BEGIN card -->
                        <div class="card h-100 mb-3 overflow-hidden fs-13px border-0 bg-gradient-custom-orange">
                            <!-- BEGIN card-body -->
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">Terakhir ditambah</h5>
                                        <div class="fs-13px">Latest history add document</div>
                                    </div>
                                </div>

                                <!-- BEGIN table-responsive -->
                                <div class="table-responsive mb-n2">
                                    <table class="table table-borderless mb-0">
                                        <thead>
                                            <tr class="text-body">
                                                <th>No</th>
                                                <th>No Dokumen</th>
                                                <th>Nama Dokumen</th>
                                                <th>Waktu</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php foreach ($histCreate as $row) : ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?= $row->no_document; ?></td>
                                                    <td><?= substr($row->nama_document, 0, 32); ?></td>
                                                    <td><?= $row->created_at; ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary">Klik View</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- END table-responsive -->
                            </div>
                            <!-- END card-body -->
                        </div>
                        <!-- END card -->
                    </div>
                    <!-- END col-6 -->

                    <!-- BEGIN col-6 -->
                    <div class="col-xl-12 mb-3">
                        <!-- BEGIN card -->
                        <div class="card h-100 mb-3 overflow-hidden fs-13px border-0 bg-gradient-custom-orange">
                            <!-- BEGIN card-body -->
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">Terakhir diubah</h5>
                                        <div class="fs-13px">Latest history edit document</div>
                                    </div>
                                </div>

                                <!-- BEGIN table-responsive -->
                                <table class="table table-borderless mb-0">
                                    <thead>
                                        <tr class="text-body">
                                            <th>No</th>
                                            <th>No Dokumen</th>
                                            <th>Nama Dokumen</th>
                                            <th>Waktu</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php foreach ($histUpdate as $row) : ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $row->no_document; ?></td>
                                                <td><?= substr($row->nama_document, 0, 32); ?></td>
                                                <td><?= $row->created_at; ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary">Klik View</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- END table-responsive -->
                        </div>
                        <!-- END card-body -->
                    </div>
                    <!-- END card -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>