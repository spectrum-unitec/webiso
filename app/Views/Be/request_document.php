<?= $this->extend('Be/layouts/main'); ?>

<?= $this->section('pageStyles'); ?>
<link rel="stylesheet"
    href="https://cdn.datatables.net/2.3.5/css/dataTables.bootstrap5.min.css">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="row justify-content-center">
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
                            <option value="approved">Approve</option>
                            <option value="rejected">Reject</option>
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
                    data: 'status'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'dt-nowrap text-center',
                    render: id => `
                    <button class="btn btn-sm btn-primary btn-konfirm" data-id="${id}">Konfirmasi</button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="${id}">Delete</button>
                `
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
</script>

<?= $this->endSection(); ?>