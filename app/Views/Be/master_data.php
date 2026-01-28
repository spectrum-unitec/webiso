<?= $this->extend('Be/layouts/main'); ?>
<?= $this->section('pageStyles'); ?>
<link rel="stylesheet"
    href="https://cdn.datatables.net/2.3.5/css/dataTables.bootstrap5.min.css">
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<div class="container">
    <!-- BEGIN row -->
    <div class="row justify-content-center">
        <!-- BEGIN col-10 -->
        <div class="col-xl-10">
            <!-- BEGIN row -->
            <div class="row">
                <!-- BEGIN col-9 -->
                <div class="col-xl-9">
                    <h1 class="page-header">
                        Master Data
                    </h1>
                    <hr class="mb-4">

                    <!-- BEGIN #cardWidget -->
                    <div id="levelDocument" class="mb-5">
                        <div class="row">
                            <h4>Level Dokumen ISO</h4>
                            <div class="col-12">
                                <div class="mb-2">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-primary" data-bs-target="#createModalLevel" data-bs-toggle="modal"><i class="far fa-plus"></i> Tambah</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <table id="datatableLevel" class="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Level</th>
                                                    <th>Dibuat</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                foreach ($levelDoc as $row) : ?>
                                                    <tr>
                                                        <td><?= $no++; ?></td>
                                                        <td><?= $row->level; ?></td>
                                                        <td><?= $row->created_at; ?></td>
                                                        <td width="120px">
                                                            <a href="" class="btn btn-sm btn-warning">Edit</a>
                                                            <a href="" class="btn btn-sm btn-danger">Hapus</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END #cardWidget -->

                    <!-- BEGIN #listWidget -->
                    <div id="jenisDocument" class="mb-5">
                        <div class="row">
                            <h4>Jenis Dokumen</h4>
                            <div class="col-12">
                                <div class="mb-2">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-primary" data-bs-target="#createModalJenis" data-bs-toggle="modal"><i class="far fa-plus"></i> Tambah</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <table id="datatableDefault" class="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Jenis Dokumen</th>
                                                    <th>Dibuat</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                foreach ($jenisDoc as $row) : ?>
                                                    <tr>
                                                        <td><?= $no++; ?></td>
                                                        <td><?= $row->jenis_document; ?></td>
                                                        <td><?= $row->created_at; ?></td>
                                                        <td width="120px">
                                                            <a href="" class="btn btn-sm btn-warning">Edit</a>
                                                            <a href="" class="btn btn-sm btn-danger">Hapus</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- END #listWidget -->

                    <!-- BEGIN #statsWidget -->
                    <div id="kodeBagian" class="mb-5">
                        <div class="row">
                            <h4>Divisi</h4>
                            <div class="col-12">
                                <div class="mb-2">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-primary" data-bs-target="#createModalDivisi" data-bs-toggle="modal"><i class="far fa-plus"></i> Tambah</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <table id="datatableDefault" class="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Divisi</th>
                                                    <th>Nama Divisi</th>
                                                    <th>Dibuat</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                foreach ($divisi as $row) : ?>
                                                    <tr>
                                                        <td><?= $no++; ?></td>
                                                        <td width="120px"><?= $row->kode_divisi; ?></td>
                                                        <td><?= $row->nama_divisi; ?></td>
                                                        <td width="170px"><?= $row->created_at; ?></td>
                                                        <td width="120px">
                                                            <a href="" class="btn btn-sm btn-warning">Edit</a>
                                                            <a href="" class="btn btn-sm btn-danger">Hapus</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END #statsWidget -->
                </div>
                <!-- END col-9 -->

                <!-- BEGIN col-3 -->
                <div class="col-xl-3">
                    <!-- BEGIN #sidebarBootstrap -->
                    <nav id="sidebar-bootstrap" class="navbar navbar-sticky d-none d-xl-block">
                        <nav class="nav">
                            <a class="nav-link" href="#levelDocument" data-toggle="scroll-to">Level Dokumen ISO</a>
                            <a class="nav-link" href="#jenisDocument" data-toggle="scroll-to">Jenis Dokumen</a>
                            <a class="nav-link" href="#kodeBagian" data-toggle="scroll-to">Divisi</a>
                        </nav>
                    </nav>
                    <!-- END #sidebarBootstrap -->
                </div>
                <!-- END col-3 -->
            </div>
            <!-- END row -->
        </div>
        <!-- END col-10 -->
    </div>
    <!-- END row -->
</div>

<!-- Create Modal Level-->
<div class="modal fade" id="createModalLevel" tabindex="-1" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Level Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url(route_to('admin.masterdata.store')); ?>" method="POST">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="exampleFormControlInput1">Level</label>
                        <input type="text" name="level" class="form-control" placeholder="Masukan level dokumen" required>
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

<!-- Create Modal Jenis-->
<div class="modal fade" id="createModalJenis" tabindex="-1" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Jenis Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url(route_to('admin.masterdata.store')); ?>" method="POST">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="exampleFormControlInput1">Jenis Dokumen</label>
                        <input type="text" name="jenis" class="form-control" placeholder="Masukan jenis dokumen" required>
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

<!-- Create Modal Divisi-->
<div class="modal fade" id="createModalDivisi" tabindex="-1" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Jenis Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url(route_to('admin.masterdata.store')); ?>" method="POST">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="exampleFormControlInput1">Kode Divisi</label>
                        <input type="text" name="kd_divisi" class="form-control" placeholder="Masukan kode divisi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="exampleFormControlInput1">Nama Divis</label>
                        <input type="text" name="nm_divisi" class="form-control" placeholder="Masukan nama divisi" required>
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

<?= $this->endSection(); ?>

<?= $this->section('pageScripts'); ?>
<!-- ================== BEGIN page-js ================== -->
<script src="<?= base_url(); ?>assets/js/demo/sidebar-scrollspy.demo.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.min.js"></script>
<!-- ================== END page-js ================== -->




<?= $this->endSection(); ?>