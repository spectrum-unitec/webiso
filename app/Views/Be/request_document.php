<?= $this->extend('Be/layouts/main'); ?>

<?= $this->section('pageStyles'); ?>
<link rel="stylesheet"
    href="https://cdn.datatables.net/2.3.5/css/dataTables.bootstrap5.min.css">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<!-- toast -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">

    <div
        id="liveToast"
        class="toast align-items-center text-bg-success border-0"
        role="alert">

        <div class="d-flex">

            <div class="toast-body" id="toastMessage">
                Berhasil
            </div>

            <button
                type="button"
                class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast">
            </button>

        </div>

    </div>

</div>

<div class="container">
    <div class="row justify-content-center" style="margin-left: 5rem;">
        <div class="col-xl-12">
            <div class="mb-2">
                <h1 class="page-header">
                    Permintaan Dokumen
                </h1>
                <hr class="mb-4">
            </div>
            <div class="card">
                <div class="card-body">
                    <table id="datatableDefault" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pemohon</th>
                                <th>Bagian/Divisi</th>
                                <th>Nama Dokumen</th>
                                <th>No Dokumen</th>
                                <th>Revisi</th>
                                <th>Tgl Pengajuan</th>
                                <th>Jenis Pengajuan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKonfirmasi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="formKonfirmasi">

                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id" id="konfirmasi_id">

                    <div class="mb-3">
                        <label class="form-label">Alasan Perubahan/Pengadaan</label>
                        <textarea id="detail_alasan" class="form-control" readonly></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Usulan Perubahan yang diajukan</label>
                        <textarea id="detail_usulan" class="form-control" readonly></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="">--- Pilih ---</option>
                            <option value="approved">Approve</option>
                            <option value="reject">Reject</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>

            </form>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('pageScripts'); ?>
<script src="https://cdn.datatables.net/2.3.5/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = new DataTable('#datatableDefault', {
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url(route_to('admin.req_doc.data')) ?>",
            },
            // columnDefs: [{
            //         targets: 1,
            //         width: "2%"
            //     },
            //     {
            //         targets: 2,
            //         width: "2%"
            //     },
            //     {
            //         targets: 3,
            //         width: "20%"
            //     },
            //     {
            //         targets: 0,
            //         width: "3%"
            //     },
            //     {
            //         targets: 4,
            //         width: "20%"
            //     },
            //     {
            //         targets: 5,
            //         width: "85%"
            //     }
            // ],
            columns: [{
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row, meta) => {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'nama_user'
                },
                {
                    data: 'nama_divisi'
                },
                {
                    data: 'nama_doc'
                },
                {
                    data: 'no_doc'
                },
                {
                    data: 'revisi'
                },
                {
                    data: 'tgl_pengajuan'
                },
                {
                    data: 'jenis_pengajuan'
                },
                {
                    data: 'status_badge'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'dt-nowrap text-center',
                    render: (data, type, row) => {

                        if (row.status === 'pratinjau') {

                            return `
                                <a 
                                    href="<?= base_url('administrator/request-dokumen/preview') ?>/${data}"
                                    target="_blank"
                                    class="btn btn-sm btn-dark">
                                    Lampiran
                                </a>
                                
                                 <button 
                                    class="btn btn-sm btn-primary btn-konfirm" 
                                    data-id="${data}">
                                    Konfirmasi
                                </button>
                            `;

                        } else {

                            return `
                                <button 
                                    class="btn btn-sm btn-danger btn-hapus" 
                                    data-id="${data}">
                                    Hapus
                                </button>
                            `;
                        }
                    }
                }
            ]
        });
    })
</script>

<script>
    $(document).on('click', '.btn-konfirm', function() {
        let id = $(this).data('id');

        // set id ke hidden input
        $('#konfirmasi_id').val(id);

        // ambil data dari server
        $.ajax({
            url: "<?= base_url(route_to('admin.get_detail_modal')); ?>",
            type: "GET",
            data: {
                id: id
            },
            dataType: "json",

            success: function(res) {

                // isi modal
                $('#detail_alasan').val(res.alasan);
                $('#detail_usulan').val(res.usulan);

                // tampilkan modal
                $('#modalKonfirmasi').modal('show');
            },

            error: function() {
                alert('Gagal mengambil data');
            }
        });
    });

    $(document).on('click', '.btn-hapus', function() {
        let id = $(this).data('id');

        if (!confirm('Yakin ingin menghapus data ini?')) return;

        $.ajax({
            url: "<?= base_url(route_to('admin.req_doc.delete')); ?>",
            type: "POST",
            data: {
                id: id
            },
            dataType: "json",
            success: function(res) {
                if (res.status) {
                    alert(res.message);

                    // reload DataTable tanpa reset paging
                    $('#datatableDefault').DataTable().ajax.reload(null, false);
                } else {
                    alert(res.message);
                }
            },
            error: function() {
                alert('Gagal menghapus data');
            }
        });
    });
</script>

<script>
    $('#formKonfirmasi').on('submit', function(e) {

        e.preventDefault();

        let form = $(this);

        let formData = form.serialize();

        // button submit
        let btnSubmit = form.find('button[type="submit"]');

        // simpan text asli
        let btnText = btnSubmit.html();

        $.ajax({

            url: '<?= base_url(route_to('admin.req_doc.konfirmasi')) ?>',
            type: 'POST',
            data: formData,

            // =========================
            // BEFORE SEND
            // =========================

            beforeSend: function() {

                // disable button
                btnSubmit.prop('disabled', true);

                // ubah isi button
                btnSubmit.html(`
                <span 
                    class="spinner-border spinner-border-sm me-1" 
                    role="status">
                </span>
                Loading...
            `);
            },

            // =========================
            // SUCCESS
            // =========================

            success: function(res) {

                showToast('Berhasil dikonfirmasi', 'success');

                $('#modalKonfirmasi').modal('hide');

                $('#datatableDefault')
                    .DataTable()
                    .ajax
                    .reload(null, false);
            },

            // =========================
            // ERROR
            // =========================

            error: function(err) {

                alert('Terjadi error');
            },

            // =========================
            // COMPLETE
            // =========================

            complete: function() {

                // aktifkan kembali button
                btnSubmit.prop('disabled', false);

                // kembalikan text button
                btnSubmit.html(btnText);
            }
        });
    });
</script>

<script>
    function showToast(message, type = 'success') {
        let toast = $('#liveToast');

        // reset class
        toast.removeClass(
            'text-bg-success text-bg-danger text-bg-warning'
        );

        // set warna
        toast.addClass(`text-bg-${type}`);

        // set pesan
        $('#toastMessage').html(message);

        // tampilkan toast
        let bsToast = new bootstrap.Toast(
            document.getElementById('liveToast')
        );

        bsToast.show();
    }
</script>

<?= $this->endSection(); ?>