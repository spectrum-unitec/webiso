<?= $this->extend('Be/layouts/main'); ?>

<?= $this->section('pageStyles'); ?>
<link rel="stylesheet"
    href="https://cdn.datatables.net/2.3.5/css/dataTables.bootstrap5.min.css">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>



<div class="row justify-content-center">
    <div class="col-xl-12">
        <div class="mb-2">
            <h1 class="page-header">
                My Document
            </h1>
            <hr class="mb-4">
            <div class="d-flex justify-content-end gap-2">
                <button id="btnDeleteSelected" class="btn btn-danger">
                    <i class="fa fa-trash"></i> Bulk Delete
                </button>
                <button class="btn btn-primary" data-bs-target="#createDocument" data-bs-toggle="modal"><i class="far fa-plus"></i> Buat Dokumen</button>
            </div>

        </div>
        <div class="card">
            <div class="card-body">
                <table id="datatableDefault" class="table">
                    <thead>
                        <tr>
                            <th>Bulk</th>
                            <th>No</th>
                            <th>Level</th>
                            <th>No Dokumen</th>
                            <th>Jenis Dokumen</th>
                            <th>Nama Dokumen</th>
                            <th>Di Buat</th>
                            <th>Di Update</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal create document -->
<div class="modal fade" id="createDocument" tabindex="-1" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buat Dokumen</h5>
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
                        <label class="form-label" for="exampleFormControlInput1">Level Dokumen ISO</label>
                        <select name="level" class="form-select">
                            <option value="">Pilih Level Dokumen</option>
                            <?php foreach ($levelDoc as $row) : ?>
                                <option value="<?= $row->id; ?>"><?= $row->level; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="exampleFormControlInput1">Jenis Dokumen</label>
                        <select name="jenis" id="jenisDoc" class="form-select">
                            <option value="">Pilih Jenis Dokumen</option>
                            <?php foreach ($jenisDoc as $row) : ?>
                                <option value="<?= $row->id; ?>"><?= $row->jenis_document; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="exampleFormControlInput1">Kode Bagian</label>
                        <select name="divisi" id="divisi" class="form-select">
                            <option value="">Pilih Kode Bagian</option>
                            <?php foreach ($divisi as $row) : ?>
                                <option value="<?= $row->id; ?>">( <?= $row->kode_divisi; ?> ) <?= $row->nama_divisi; ?> </option>
                            <?php endforeach; ?>
                        </select>
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


<!-- Modal edit document -->
<div class="modal fade" id="editDocument" tabindex="-1" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditDocument" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="exampleFormControlInput1">NO Dokumen</label>
                        <input type="text" name="no_doc" id="edit_no_doc" class="form-control" placeholder="Masukan no dokumen">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="exampleFormControlInput1">Nama Dokumen</label>
                        <input type="text" name="nm_doc" id="edit_nm_doc" class="form-control" placeholder="Masukan nama dokumen">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="exampleFormControlInput1">Level Dokumen ISO</label>
                        <select name="level" id="edit_level" class="form-select">
                            <option value="">Pilih Level Dokumen</option>
                            <?php foreach ($levelDoc as $row) : ?>
                                <option value="<?= $row->id; ?>"><?= $row->level; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="exampleFormControlInput1">Jenis Dokumen</label>
                        <select name="jenis" id="jenisDoc" class="form-select edit_jenis">
                            <option value="">Pilih Jenis Dokumen</option>
                            <?php foreach ($jenisDoc as $row) : ?>
                                <option value="<?= $row->id; ?>"><?= $row->jenis_document; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="exampleFormControlInput1">Kode Bagian</label>
                        <select name="divisi" id="divisi" class="form-select edit_divisi">
                            <option value="">Pilih Kode Bagian</option>
                            <?php foreach ($divisi as $row) : ?>
                                <option value="<?= $row->id; ?>">( <?= $row->kode_divisi; ?> ) <?= $row->nama_divisi; ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="defaultFile">Upload file PDF</label>
                        <input type="file" name="pdf_file" id="edit_pdf" accept="application/pdf" class="form-control">
                        <small id="currentFile"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File PDF Saat Ini</label>
                        <iframe id="pdfViewer" style="width:100%; height:400px;" frameborder="0"></iframe>
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
<script src="https://cdn.datatables.net/2.3.5/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const jenisDoc = document.getElementById('jenisDoc');
        const divisi = document.getElementById('divisi')

        jenisDoc.addEventListener('change', function() {
            if (this.value == 1) {
                divisi.parentElement.style.display = 'none';
            } else {
                divisi.parentElement.style.display = 'block';
            }
        })

        const table = new DataTable('#datatableDefault', {
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url(route_to('admin.mydocument.data')) ?>",
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
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: id => `<input type="checkbox" class="row-check" value="${id}">`
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row, meta) => {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'level'
                },
                {
                    data: 'no_document'
                },
                {
                    data: 'jenis_document'
                },
                {
                    data: 'nama_document'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'dt-nowrap text-center',
                    render: id => `
                    <button class="btn btn-sm btn-warning btn-edit" data-id="${id}">Edit</button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="${id}">Delete</button>
                `
                }
            ]
        });

        //created form
        const formCreate = document.getElementById('formCreateDocument');
        formCreate.addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            try {
                const res = await fetch('<?= base_url(route_to('admin.mydocument.store')) ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData
                })

                const data = await res.json();

                if (data.status) {
                    form.reset();

                    //tutup modal
                    const modalEl = document.getElementById('createDocument')
                    const modal = bootstrap.Modal.getInstance(modalEl) ?? new bootstrap.Modal(modalEl);
                    modal.hide();
                    table.ajax.reload(false);
                }

            } catch (error) {
                console.error(error);
            }
        });

        //event delegation
        document.querySelector('#datatableDefault')
            .addEventListener('click', async (e) => {

                /* ================= EDIT ================= */
                const btnEdit = e.target.closest('.btn-edit');
                if (btnEdit) {
                    const id = btnEdit.dataset.id;

                    const baseEditUrl = "<?= site_url('administrator/mydocument/ajax-edit') ?>";
                    const res = await fetch(`${baseEditUrl}/${id}`);
                    const data = await res.json();

                    // cek Divisi
                    const editDivisi = document.querySelector('.edit_divisi');
                    if (editDivisi) {
                        editDivisi.value = data.divisi_id;
                        editDivisi.parentElement.style.display =
                            data.divisi_id !== null ? 'block' : 'none';
                    }

                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_no_doc').value = data.no_document;
                    document.getElementById('edit_nm_doc').value = data.nama_document;
                    document.getElementById('edit_level').value = data.level_id;
                    document.querySelector('.edit_jenis').value = data.jenis_id;
                    document.getElementById('edit_pdf').value = '';

                    // tampilkan PDF
                    const pdfViewer = document.getElementById('pdfViewer');
                    pdfViewer.src = data.file ?
                        `<?= base_url('administrator/mydocument/preview/') ?>${data.file}` :
                        '';

                    new bootstrap.Modal('#editDocument').show();
                    return;
                }

                /* ================= DELETE ================= */
                const btnDelete = e.target.closest('.btn-delete');
                if (!btnDelete) return;

                const id = btnDelete.dataset.id;

                if (!confirm('Yakin ingin menghapus dokumen ini?')) return;

                try {
                    const res = await fetch(
                        `<?= site_url('administrator/mydocument/ajax-delete') ?>/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    );

                    const result = await res.json();

                    if (result.status) {
                        alert('Data berhasil dihapus');
                        table.ajax.reload(null, false); // reload tanpa reset halaman
                    } else {
                        alert(result.message ?? 'Gagal menghapus data');
                    }

                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan');
                }
            });

        //update form
        document.getElementById('formEditDocument').addEventListener('submit', async e => {
            e.preventDefault();
            const formData = new FormData(e.target);

            try {
                const res = await fetch("<?= base_url(route_to('admin.mydocument.update')) ?>", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await res.json();

                if (data.success) {
                    // Tutup modal
                    bootstrap.Modal.getInstance(document.getElementById('editDocument')).hide();

                    // Reload DataTable
                    table.ajax.reload(null, false);
                }

            } catch (err) {
                console.error(err);
            }
        });

        document.getElementById('btnDeleteSelected').addEventListener('click', async () => {
            const ids = [...document.querySelectorAll('.row-check:checked')]
                .map(cb => cb.value);

            if (ids.length === 0) {
                alert('Pilih minimal 1 data selected');
                return;
            }

            if (!confirm(`Hapus ${ids.length} data?`)) return;

            try {
                const res = await fetch("<?= base_url(route_to('admin.mydocument.deleteBulk')) ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify({
                        ids
                    })
                });

                const result = await res.json();

                if (result.status) {
                    table.ajax.reload(null, false);
                } else {
                    alert(result.message);
                }
            } catch (err) {
                console.error(err);
                alert('Gagal menghapus data');
            }
        });

    })
</script>
<?= $this->endSection(); ?>