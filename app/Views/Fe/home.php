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