<?= $this->extend('Be/layouts/main'); ?>

<?= $this->section('pageStyles'); ?>
<script src="https://unpkg.com/slim-select@latest/dist/slimselect.js"></script>
<link href="https://unpkg.com/slim-select@latest/dist/slimselect.css" rel="stylesheet">
</link>
<link rel="stylesheet"
    href="https://cdn.datatables.net/2.3.5/css/dataTables.bootstrap5.min.css">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>


<div class="container">
    <div class="row justify-content-center " style="margin-left: 5rem;">
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
</div>

<!-- Modal create document -->
<div class="modal fade" id="createDocument" tabindex="-1" data-bs-focus="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formCreateDocument" enctype="multipart/form-data">
                <div class="modal-body">

                    <!-- TOGGLE -->
                    <div class="mb-3">
                        <label class="form-label">Tipe Dokumen</label>
                        <select id="tipeDoc" name="tipe" class="form-select">
                            <option value="iso">ISO</option>
                            <option value="non_iso">Non ISO</option>
                        </select>
                    </div>

                    <!-- NON ISO ONLY -->
                    <div id="nonIsoFields" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label">Sub Kategori</label>
                            <select name="sub" id="subCategory" class="form-select">
                                <option value="">Pilih kategori</option>
                                <?php foreach ($subCategories as $row) : ?>
                                    <option value="<?= $row->id; ?>">
                                        <?= $row->nama; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- NO -->
                    <div class="mb-3">
                        <label class="form-label">NO Dokumen</label>
                        <input type="text" name="no_doc" id="no_doc" class="form-control"
                            placeholder="Masukan no dokumen">
                    </div>

                    <!-- NAMA -->
                    <div class="mb-3">
                        <label class="form-label">Nama Dokumen</label>
                        <input type="text" name="nm_doc" id="nm_doc" class="form-control"
                            placeholder="Masukan nama dokumen">
                    </div>

                    <!-- ISO ONLY -->
                    <div id="isoFields">

                        <div class="mb-3">
                            <label class="form-label">Level Dokumen ISO</label>
                            <select name="level" class="form-select">
                                <option value="">Pilih level dokumen</option>
                                <?php foreach ($levelDoc as $row) : ?>
                                    <option value="<?= $row->id; ?>"><?= $row->level; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Dokumen</label>
                            <select name="jenis" id="jenisDoc" class="form-select">
                                <option value="">Pilih jenis dokumen</option>
                                <?php foreach ($jenisDoc as $row) : ?>
                                    <option value="<?= $row->id; ?>">
                                        <?= $row->jenis_document; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kode Bagian</label>
                            <select name="divisi" id="divisi" class="form-select">
                                <option value="">Pilih kode bagian</option>
                                <?php foreach ($divisi as $row) : ?>
                                    <option value="<?= $row->id; ?>">
                                        [<?= $row->kode_divisi; ?>] <?= $row->nama_divisi; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="prosedurFields" style="display:none;">

                            <!-- REKAMAN MUTU -->
                            <div class="mb-3">
                                <label class="form-label">Link Rekaman Mutu</label>
                                <select id="rekaman_mutu" name="rekaman_mutu[]" multiple>

                                </select>
                            </div>

                            <!-- DOKUMEN TERKAIT -->
                            <div class="mb-3">
                                <label class="form-label">Dokumen Terkait</label>
                                <select name="dokumen_terkait[]" id="dokumen_terkait" multiple>

                                </select>
                            </div>

                        </div>

                    </div>

                    <!-- UPLOAD (UNTUK SEMUA) -->
                    <div class="mb-3">
                        <label class="form-label">Upload file PDF</label>
                        <input type="file" name="pdf_file" accept="application/pdf"
                            class="form-control" id="pdfFile">
                    </div>
                    <!-- END ISO -->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal edit document -->
<div class="modal fade" id="editDocument" tabindex="-1" data-bs-focus="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formEditDocument" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">

                <div class="modal-body">

                    <!-- NO -->
                    <div class="mb-3">
                        <label class="form-label">NO Dokumen</label>
                        <input type="text" name="no_doc" id="edit_no_doc" class="form-control">
                    </div>

                    <!-- NAMA -->
                    <div class="mb-3">
                        <label class="form-label">Nama Dokumen</label>
                        <input type="text" name="nm_doc" id="edit_nm_doc" class="form-control">
                    </div>

                    <!-- ================= ISO ================= -->
                    <div id="editIsoFields">

                        <div class="mb-3">
                            <label class="form-label">Level Dokumen ISO</label>
                            <select name="level" id="edit_level" class="form-select">
                                <option value="">Pilih level dokumen</option>
                                <?php foreach ($levelDoc as $row) : ?>
                                    <option value="<?= $row->id; ?>"><?= $row->level; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Dokumen</label>
                            <select name="jenis" id="edit_jenis" class="form-select">
                                <option value="">Pilih jenis dokumen</option>
                                <?php foreach ($jenisDoc as $row) : ?>
                                    <option value="<?= $row->id; ?>">
                                        <?= $row->jenis_document; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kode Bagian</label>
                            <select name="divisi" id="edit_divisi" class="form-select">
                                <option value="">Pilih kode bagian</option>
                                <?php foreach ($divisi as $row) : ?>
                                    <option value="<?= $row->id; ?>">
                                        [<?= $row->kode_divisi; ?>] <?= $row->nama_divisi; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                    <!-- ================= NON ISO ================= -->
                    <div id="editNonIsoFields" style="display:none;">

                        <div class="mb-3">
                            <label class="form-label">Kategori Non ISO</label>
                            <select name="sub_category" id="edit_subCategory" class="form-select">
                                <option value="">Pilih kategori</option>
                                <?php foreach ($subCategories as $row) : ?>
                                    <option value="<?= $row->id; ?>">
                                        <?= $row->nama; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                    <!-- ================= PROSEDUR MUTU ================= -->
                    <div id="editProsedurFields" style="display:none;">

                        <!-- REKAMAN MUTU -->
                        <div class="mb-3">
                            <label class="form-label">Link Rekaman Mutu</label>
                            <select id="edit_rekaman_mutu" name="rekaman_mutu[]" multiple>

                            </select>
                        </div>

                        <!-- Dokumen Terkait -->
                        <div class="mb-3">
                            <label class="form-label">Dokumen Terkait</label>
                            <select name="dokumen_terkait[]" id="edit_dokumen_terkait" multiple>

                            </select>
                        </div>

                    </div>

                    <!-- FILE -->
                    <div class="mb-3">
                        <label class="form-label">Upload file PDF</label>
                        <input type="file" name="pdf_file" id="edit_pdf" accept="application/pdf" class="form-control">
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

        // =========================
        // GLOBAL STATE (1 SUMBER)
        // =========================
        if (!window.slims) {
            window.slims = {
                createRekaman: null,
                createTerkait: null,
                editRekaman: null,
                editTerkait: null
            };
        }

        const slims = window.slims;

        const createModal = document.getElementById('createDocument');
        const editModal = document.getElementById('editDocument');

        const jenisDoc = document.getElementById('jenisDoc');
        const prosedurFields = document.getElementById('prosedurFields');

        // =========================
        // INIT SLIMSELECT (GENERIC)
        // =========================
        function initSlim(selector, instance) {

            if (instance) instance.destroy();

            return new SlimSelect({
                select: selector,
                settings: {
                    placeholderText: 'Pilih dokumen',
                    searchPlaceholder: 'Cari dokumen...',
                    closeOnSelect: false,
                    allowDeselect: true
                },
                events: {
                    search: async (search, currentData) => {

                        if (search.length < 2) {
                            return Promise.reject('Minimal 2 karakter');
                        }

                        const url = "<?= base_url(route_to('ajax.link.doc')) ?>?search=" + search;
                        const res = await fetch(url);
                        const json = await res.json();
                        const data = json?.results || [];

                        return [{
                            label: 'Hasil',
                            options: data
                                .filter(d => !currentData.some(c => String(c.value) === String(d.id)))
                                .map(d => ({
                                    text: d.namaDoc,
                                    value: String(d.id)
                                }))
                        }];
                    }
                }
            });
        }

        // =========================
        // CREATE - TOGGLE PROSEDUR
        // =========================
        function toggleProsedurCreate() {

            if (!jenisDoc) return;

            const text = jenisDoc.options[jenisDoc.selectedIndex]?.text || '';
            const isProsedur = text.toLowerCase().includes('prosedur mutu');

            prosedurFields.style.display = isProsedur ? 'block' : 'none';

            if (isProsedur) {
                // 🔥 INIT SEKALI DI SINI (AMAN)
                slims.createRekaman = initSlim('#rekaman_mutu', slims.createRekaman);
                slims.createTerkait = initSlim('#dokumen_terkait', slims.createTerkait);
            } else {
                if (slims.createRekaman) slims.createRekaman.setSelected([]);
                if (slims.createTerkait) slims.createTerkait.setSelected([]);
            }
        }

        if (jenisDoc) {
            jenisDoc.addEventListener('change', toggleProsedurCreate);
        }

        // =========================
        // RESET CREATE MODAL
        // =========================
        if (createModal) {
            createModal.addEventListener('show.bs.modal', function() {

                prosedurFields.style.display = 'none';

                if (slims.createRekaman) slims.createRekaman.setSelected([]);
                if (slims.createTerkait) slims.createTerkait.setSelected([]);
            });
        }

        // =========================
        // EDIT SECTION
        // =========================
        let currentEditData = null;

        function loadSlim(instance, dataList) {
            if (!instance || !dataList) return;

            instance.setData(
                dataList.map(item => ({
                    text: `${item.no_document} - ${item.nama_document}`,
                    value: String(item.id),
                    selected: true
                }))
            );
        }

        if (editModal) {
            editModal.addEventListener('shown.bs.modal', function() {

                slims.editRekaman = initSlim('#edit_rekaman_mutu', slims.editRekaman);
                slims.editTerkait = initSlim('#edit_dokumen_terkait', slims.editTerkait);

                if (currentEditData) {
                    loadSlim(slims.editRekaman, currentEditData.rekaman_mutu_detail);
                    loadSlim(slims.editTerkait, currentEditData.dokumen_terkait_detail);
                }
            });
        }

        // =========================
        // SET DATA EDIT
        // =========================
        window.setEditData = function(data) {
            currentEditData = data;

            if (editModal && editModal.classList.contains('show')) {
                loadSlim(slims.editRekaman, data.rekaman_mutu_detail);
                loadSlim(slims.editTerkait, data.dokumen_terkait_detail);
            }
        };

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const jenisDoc = document.getElementById('jenisDoc');
        const divisi = document.getElementById('divisi')

        jenisDoc.addEventListener('change', function() {
            const selectedText = this.options[this.selectedIndex].text;

            if (selectedText === 'Manual Mutu') {
                divisi.parentElement.style.display = 'none';
            } else {
                divisi.parentElement.style.display = 'block';
            }
        });

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

            const formData = new FormData(this);

            const tipeDoc = document.getElementById('tipeDoc');
            const subCategory = document.getElementById('subCategory');
            const fileInput = document.getElementById('pdfFile');

            // ======================
            // ISO / NON ISO
            // ======================
            if (tipeDoc.value === 'non_iso') {

                formData.set('tipe', 'non_iso');
                formData.delete('level');
                formData.delete('jenis');
                formData.delete('divisi');

                formData.set('sub_category', subCategory.value);

            } else {

                formData.set('tipe', 'iso');
                formData.delete('sub_category');
            }

            // ======================
            // SYNC SLIMSELECT → FORM DATA
            // ======================
            if (window.slims?.createRekaman) {

                const rekaman = slims.createRekaman.getSelected();

                formData.delete('rekaman_mutu[]');

                rekaman.forEach(val => {
                    formData.append('rekaman_mutu[]', val);
                });
            }

            if (window.slims?.createTerkait) {

                const terkait = slims.createTerkait.getSelected();

                formData.delete('dokumen_terkait[]');

                terkait.forEach(val => {
                    formData.append('dokumen_terkait[]', val);
                });
            }

            // ======================
            // DEBUG DI SINI
            // ======================
            for (let pair of formData.entries()) {
                console.log(pair[0], pair[1]);
            }

            try {
                const res = await fetch('<?= base_url(route_to('admin.mydocument.store')) ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await res.json();

                if (data.status) {

                    // ======================
                    // RESET FORM
                    // ======================
                    this.reset();
                    fileInput.value = '';

                    // RESET SLIMSELECT
                    if (window.slims?.createRekaman) {
                        slims.createRekaman.setSelected([]);
                    }
                    if (window.slims?.createTerkait) {
                        slims.createTerkait.setSelected([]);
                    }

                    // ======================
                    // RESET UI
                    // ======================
                    document.getElementById('tipeDoc').value = 'iso';
                    document.getElementById('isoFields').style.display = 'block';
                    document.getElementById('nonIsoFields').style.display = 'none';

                    // ======================
                    // TUTUP MODAL (BENAR)
                    // ======================
                    const modal = bootstrap.Modal.getOrCreateInstance(
                        document.getElementById('createDocument')
                    );
                    modal.hide();

                    // ======================
                    // RELOAD TABLE
                    // ======================
                    table.ajax.reload(null, false);
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

                    try {
                        const baseEditUrl = "<?= site_url('administrator/mydocument/ajax-edit') ?>";
                        const res = await fetch(`${baseEditUrl}/${id}`);

                        if (!res.ok) throw new Error('Response tidak OK');

                        const resData = await res.json();

                        const data = {
                            ...resData.data,
                            rekaman_mutu: resData.rekaman_mutu,
                            rekaman_mutu_detail: resData.rekaman_mutu_detail,
                            dokumen_terkait: resData.dokumen_terkait,
                            dokumen_terkait_detail: resData.dokumen_terkait_detail
                        };

                        // ======================
                        // BASIC DATA
                        // ======================
                        document.getElementById('edit_id').value = data.id ?? '';
                        document.getElementById('edit_no_doc').value = data.no_document ?? '';
                        document.getElementById('edit_nm_doc').value = data.nama_document ?? '';

                        const isoFields = document.getElementById('editIsoFields');
                        const nonIsoFields = document.getElementById('editNonIsoFields');
                        const prosedurFields = document.getElementById('editProsedurFields');

                        const editLevel = document.getElementById('edit_level');
                        const editJenis = document.getElementById('edit_jenis');
                        const editDivisi = document.getElementById('edit_divisi');
                        const subCategory = document.getElementById('edit_subCategory');

                        // ======================
                        // DETECT ISO / NON ISO
                        // ======================
                        const isIso = data.level_id !== null && data.level_id !== '';

                        // ======================
                        // TOGGLE ISO / NON ISO
                        // ======================
                        if (isoFields) isoFields.style.display = isIso ? 'block' : 'none';
                        if (nonIsoFields) nonIsoFields.style.display = isIso ? 'none' : 'block';

                        // ======================
                        // SET VALUE
                        // ======================
                        if (isIso) {
                            if (editLevel) editLevel.value = data.level_id ?? '';
                            if (editJenis) editJenis.value = data.jenis_id ?? '';
                            if (editDivisi) editDivisi.value = data.divisi_id ?? '';
                            if (subCategory) subCategory.value = '';
                        } else {
                            if (editLevel) editLevel.value = '';
                            if (editJenis) editJenis.value = '';
                            if (editDivisi) editDivisi.value = '';
                            if (subCategory) subCategory.value = data.sub_category_id ?? '';
                        }

                        // ======================
                        // DETECT PROSEDUR MUTU
                        // ======================
                        let isProsedur = false;

                        if (editJenis && editJenis.options.length) {
                            const selectedText = editJenis.options[editJenis.selectedIndex]?.text || '';
                            isProsedur = selectedText.toLowerCase().includes('prosedur mutu');
                        }

                        if (prosedurFields) {
                            prosedurFields.style.display = isProsedur ? 'block' : 'none';
                        }


                        // ======================
                        // RESET FILE INPUT
                        // ======================
                        const fileInput = document.getElementById('edit_pdf');
                        if (fileInput) fileInput.value = '';

                        // ======================
                        // PDF VIEWER
                        // ======================
                        const pdfViewer = document.getElementById('pdfViewer');
                        if (pdfViewer) {
                            pdfViewer.src = data.file ?
                                `<?= base_url('administrator/mydocument/preview/') ?>${data.file}?t=${Date.now()}` :
                                '';
                        }

                        // ======================
                        // SET DATA KE SLIMSELECT
                        // ======================
                        setEditData(data);

                        // ======================
                        // SHOW MODAL
                        // ======================
                        const modalEl = document.getElementById('editDocument');
                        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        modal.show();

                    } catch (error) {
                        console.error('Gagal load data:', error);
                        alert('Gagal mengambil data dokumen');
                    }
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
        document.getElementById('formEditDocument').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');

            try {
                // 🔒 disable tombol biar tidak double submit
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerText = 'Menyimpan...';
                }
                const res = await fetch("<?= base_url(route_to('admin.mydocument.update')) ?>", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                // ❗ handle HTTP error
                if (!res.ok) {
                    throw new Error('Server error: ' + res.status);
                }

                const data = await res.json();

                // ======================
                // SUCCESS
                // ======================
                if (data.status) {

                    const modalEl = document.getElementById('editDocument');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);

                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // reload datatable tanpa reset paging
                    if (typeof table !== 'undefined') {
                        table.ajax.reload(null, false);
                    }




                } else {
                    // ❗ tampilkan pesan error dari server
                    alert(data.message || 'Gagal menyimpan data');
                }

            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan saat menyimpan data');
            } finally {
                // 🔓 aktifkan kembali tombol
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Simpan';
                }
            }
        });


        const editJenis = document.getElementById('edit_jenis');

        if (editJenis) {
            editJenis.addEventListener('change', handleJenisChange);
        }

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

<script>
    function handleJenisChange() {
        const editJenis = document.getElementById('edit_jenis');
        const editDivisi = document.getElementById('edit_divisi');

        if (!editJenis || !editDivisi) return;

        const selectedText = editJenis.options[editJenis.selectedIndex]?.text;

        if (selectedText === 'Manual Mutu') {
            editDivisi.closest('.mb-3').style.display = 'none';
            editDivisi.value = ''; // reset
        } else {
            editDivisi.closest('.mb-3').style.display = 'block';
        }
    }
</script>

<?= $this->endSection(); ?>