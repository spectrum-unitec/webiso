<?= $this->extend('Fe/layouts/main'); ?>
<?= $this->section('content'); ?>


<!-- <ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">LAYOUT</a></li>
    <li class="breadcrumb-item active">TOP NAV</li>
</ul>

<h1 class="page-header">
    Top Nav <small>page header description goes here...</small>
</h1>

<hr class="mb-4"> -->

<section>
    <div class="container">
        <div class="row">
            <div class="col">
                <div id="img"></div>
                <div class="row mt-4">
                    <!-- BEGIN col-6 -->
                    <div class="col-xl-6 mb-3">
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
                                                <th class="ps-0">No</th>
                                                <th class="ps-0">No Dokumen</th>
                                                <th>Nama Dokumen</th>
                                                <th class="text-center">Waktu</th>
                                                <th class="text-center">Aksi</th>
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
                                                        <button class="btn btn-sm btn-primary">View</button>
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
                    <div class="col-xl-6 mb-3">
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
                                            <th class="ps-0">No</th>
                                            <th class="ps-0">No Dokumen</th>
                                            <th>Nama Dokumen</th>
                                            <th class="text-center">Waktu</th>
                                            <th class="text-center">Aksi</th>
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
                                                    <button class="btn btn-sm btn-primary">View</button>
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
            </div>
            <h4 class="text-center mt-3" style="color: #273349; font-weight:400; font-size:18px; line-height:1.4">Kebijakan Kami Adalah Menjadi Perusahaan Yang Sehat Dengan Menghasilkan Produk Yang Berkualitas <br> Sesuai Dengan Persyaratan Pelanggan</h4>
            <div class="logo justify-content-center d-flex">
                <img src="<?= base_url(); ?>assets/img/iso.png" width="150px">
            </div>
        </div>
    </div>
    </div>
</section>





<!-- /.main-wrappper -->
<?= $this->endSection(); ?>