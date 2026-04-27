<?php

namespace App\Controllers\Be;

use App\Controllers\BaseController;
use App\Libraries\Breadcrumb;
use App\Models\DivisiModel;
use App\Models\HistoryDocumentModel;
use App\Models\JenisDocumentModel;
use App\Models\LevelDocument;
use App\Models\MydocumentModel;
use App\Models\PivotDocumentTerkaitModel;
use App\Models\PivotRekamanMutuModel;
use App\Models\SubCategoryNonIsoModel;
use CodeIgniter\I18n\Time;
use Hermawan\DataTables\DataTable;

class MyDocument extends BaseController
{
    protected $docModel;
    protected $jenisDocModel;
    protected $historyDocModel;
    protected $subCategoryModel;
    protected $pivotDocRekamanMutuModel;
    protected $pivotDocTerkaitModel;

    public function __construct()
    {
        $this->docModel = new MydocumentModel();
        $this->jenisDocModel = new JenisDocumentModel();
        $this->historyDocModel = new HistoryDocumentModel();
        $this->subCategoryModel = new SubCategoryNonIsoModel();
        $this->pivotDocRekamanMutuModel = new PivotRekamanMutuModel();
        $this->pivotDocTerkaitModel = new PivotDocumentTerkaitModel();
    }

    public function index()
    {
        $bread = [
            'page_title' => 'My Document',
            // 'page_desc'  => 'page header description goes here...',
            // 'breadcrumbs' => Breadcrumb::generate()
        ];

        $modelLevelDoc = new LevelDocument();
        $levelDoc = $modelLevelDoc->findAll();

        $jenisDoc = $this->jenisDocModel->where('jenis_document !=', 'Document Non Iso')->findAll();

        $modelDivisi = new DivisiModel();
        $divisi = $modelDivisi->findAll();

        $subCategories = $this->subCategoryModel
            ->findAll();

        $rekamanMutu = $this->docModel->where('jenis_id', 5)->findAll();

        return view('Be/my_document', compact('bread', 'levelDoc', 'jenisDoc', 'divisi', 'subCategories', 'rekamanMutu'));
    }

    public function ajaxStore()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => false,
                'message' => 'Forbidden access'
            ]);
        }

        $tipe        = $this->request->getPost('tipe');
        $divisiId    = $this->request->getPost('divisi');
        $subCategory = $this->request->getPost('sub_category');

        $pdfFile  = $this->request->getFile('pdf_file');
        $fileName = null;

        // ================= FILE =================
        if ($pdfFile && $pdfFile->isValid() && !$pdfFile->hasMoved()) {

            if ($pdfFile->getClientMimeType() !== 'application/pdf') {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'File harus berupa PDF'
                ]);
            }

            $path = WRITEPATH . 'uploads/pdf';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $fileName = $pdfFile->getRandomName();
            $pdfFile->move($path, $fileName);
        }

        // ================= VALIDASI =================
        if (!$this->request->getPost('no_doc') || !$this->request->getPost('nm_doc')) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No dokumen dan nama wajib diisi'
            ]);
        }

        if ($tipe === 'iso') {
            if (!$this->request->getPost('level') || !$this->request->getPost('jenis')) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Field ISO wajib diisi'
                ]);
            }
        }

        if ($tipe === 'non_iso') {
            if (!$subCategory) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Kategori Non ISO wajib dipilih'
                ]);
            }
        }

        // ================= AMBIL JENIS NON ISO =================
        $jenisNonIso = $this->jenisDocModel
            ->where('slug', 'document-non-iso') // ✅ lebih aman
            ->first();

        $jenisNonIsoId = $jenisNonIso->id ?? null;

        // ================= INSERT =================
        $insertData = [
            'no_document'   => $this->request->getPost('no_doc'),
            'slug'          => url_title($this->request->getPost('nm_doc'), '-', true),
            'nama_document' => $this->request->getPost('nm_doc'),
            'file'          => $fileName
        ];

        if ($tipe === 'iso') {

            $insertData['level_id']  = $this->request->getPost('level');
            $insertData['jenis_id']  = $this->request->getPost('jenis');
            $insertData['divisi_id'] = empty($divisiId) ? null : $divisiId;
            $insertData['sub_category_id'] = null;
        } else {

            $insertData['level_id']  = null;
            $insertData['jenis_id']  = $jenisNonIsoId;
            $insertData['divisi_id'] = null;
            $insertData['sub_category_id'] = $subCategory; // 🔥 penting
        }

        $this->docModel->insert($insertData);
        $documentId = $this->docModel->getInsertID();

        // ================= REKAMAN MUTU =================
        $rekaman = $this->request->getPost('rekaman_mutu') ?? [];
        $rekaman = is_array($rekaman) ? $rekaman : [$rekaman];

        // dd($rekaman);
        if (!empty($rekaman)) {

            $batch = [];

            foreach ($rekaman as $rekamanId) {
                $batch[] = [
                    'document_id' => $documentId,
                    'id'  => $rekamanId 
                ];
            }

            $this->pivotDocRekamanMutuModel->insertBatch($batch);
        }


        // ================= DOKUMEN TERKAIT =================
        $dokumenTerkait = $this->request->getPost('dokumen_terkait') ?? [];
        $dokumenTerkait = is_array($dokumenTerkait) ? $dokumenTerkait : [$dokumenTerkait];

        if (!empty($dokumenTerkait)) {

            $batch = [];

            foreach ($dokumenTerkait as $terkaitId) {
                $batch[] = [
                    'document_id' => $documentId,
                    'id'  => $terkaitId
                ];
            }

            $this->pivotDocTerkaitModel->insertBatch($batch);
        }


        // ================= HISTORY =================
        $this->historyDocModel->insert([
            'document_id'   => $documentId,
            'action'        => 'create',
            'no_document'   => $insertData['no_document'],
            'nama_document' => $insertData['nama_document'],
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Dokumen berhasil disimpan'
        ]);
    }

    public function ajaxData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Forbidden access'
                ]);
        }

        $model = new MydocumentModel();

        return DataTable::of($model->getDataJoin())
            ->add('action', function ($row) {
                return $row->id;
            })
            ->edit('created_at', function ($row) {
                $time = Time::parse($row->created_at, 'Asia/Jakarta');
                return $time->toLocalizedString("dd-MM-yy, HH:mm 'WIB'");
            })
            ->edit('updated_at', function ($row) {
                $time = Time::parse($row->updated_at, 'Asia/Jakarta');
                return $time->toLocalizedString("dd-MM-yy, HH:mm 'WIB'");
            })
            ->toJson(true);
    }

    public function viewPdf($filename)
    {
        $path = WRITEPATH . 'uploads/pdf/' . $filename;

        if (!is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan');
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody(file_get_contents($path));
    }


    public function ajaxEdit($id)
    {
        $doc = $this->docModel->find($id);

        if (!$doc) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // ======================
        // REKAMAN MUTU
        // ======================
        $rekamanIds = $this->pivotDocRekamanMutuModel
            ->where('document_id', $id)
            ->findColumn('id');

        $rekamanDetail = [];

        if (!empty($rekamanIds)) {
            $rekamanDetail = $this->docModel
                ->select('id, no_document, nama_document')
                ->whereIn('id', $rekamanIds)
                ->findAll();
        }

        // ======================
        // DOKUMEN TERKAIT
        // ======================
        $terkaitIds = $this->pivotDocTerkaitModel
            ->where('document_id', $id)
            ->findColumn('id');

        $terkaitDetail = [];

        if (!empty($terkaitIds)) {
            $terkaitDetail = $this->docModel
                ->select('id, no_document, nama_document')
                ->whereIn('id', $terkaitIds)
                ->findAll();
        }


        return $this->response->setJSON(
            [
                'id' => $doc->id,
                'data' => $doc,
                'nama_document' => $doc->nama_document,

                // rekaman mutu
                'rekaman_mutu'         => $rekamanIds ?? [],
                'rekaman_mutu_detail'  => $rekamanDetail ?? [],

                // dokumen terkait
                'dokumen_terkait'        => $terkaitIds ?? [],
                'dokumen_terkait_detail' => $terkaitDetail ?? []
            ]
        );
    }

    public function ajaxUpdate()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => false,
                'message' => 'Forbidden access'
            ]);
        }

        $id  = $this->request->getPost('id');
        $doc = $this->docModel->find($id);

        if (!$doc) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // ======================
        // INPUT
        // ======================
        $jenisId = $this->request->getPost('jenis');
        $jenis   = $this->jenisDocModel->find($jenisId);

        $isIso = !empty($doc->level_id);

        // ======================
        // DATA
        // ======================
        $data = [
            'no_document'   => $this->request->getPost('no_doc'),
            'nama_document' => $this->request->getPost('nm_doc'),
        ];

        if ($isIso) {
            $data['level_id'] = $this->request->getPost('level');
            $data['jenis_id'] = $jenisId;

            $data['divisi_id'] = ($jenis && $jenis->slug === 'manual-mutu')
                ? null
                : $this->request->getPost('divisi');
        } else {
            $data['level_id']  = null;
            $data['divisi_id'] = null;
            $data['jenis_id']  = $doc->jenis_id;
        }

        // ======================
        // FILE
        // ======================
        $file = $this->request->getFile('pdf_file');

        if ($file && $file->isValid() && !$file->hasMoved()) {

            if ($file->getClientMimeType() !== 'application/pdf') {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'File harus berupa PDF'
                ]);
            }

            $path = WRITEPATH . 'uploads/pdf';

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $newName = $file->getRandomName();
            $file->move($path, $newName);

            $data['file'] = $newName;

            if (!empty($doc->file) && file_exists($path . '/' . $doc->file)) {
                unlink($path . '/' . $doc->file);
            }
        }

        // ======================
        // CEK PERUBAHAN FIELD
        // ======================
        $isChanged = false;

        foreach ($data as $key => $value) {
            if ((string)$doc->$key !== (string)$value) {
                $isChanged = true;
                break;
            }
        }

        // ======================
        // REKAMAN MUTU
        // ======================
        $rekaman = $this->request->getPost('rekaman_mutu') ?? [];
        $rekaman = is_array($rekaman) ? $rekaman : [$rekaman];

        $oldRekaman = $this->pivotDocRekamanMutuModel
            ->where('document_id', $id)
            ->findAll();

        $oldRekamanIds = array_column($oldRekaman, 'id');

        sort($rekaman);
        sort($oldRekamanIds);

        if ($rekaman !== $oldRekamanIds) {
            $isChanged = true;
        }

        // ======================
        // DOKUMEN TERKAIT
        // ======================
        $terkait = $this->request->getPost('dokumen_terkait') ?? [];
        $terkait = is_array($terkait) ? $terkait : [$terkait];

        $oldTerkait = $this->pivotDocTerkaitModel
            ->where('document_id', $id)
            ->findAll();

        $oldTerkaitIds = array_column($oldTerkait, 'id');

        sort($terkait);
        sort($oldTerkaitIds);

        if ($terkait !== $oldTerkaitIds) {
            $isChanged = true;
        }

        // ======================
        // STOP
        // ======================
        if (!$isChanged) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Tidak ada perubahan data'
            ]);
        }

        // ======================
        // UPDATE
        // ======================
        $this->docModel->update($id, $data);

        // ======================
        // SYNC REKAMAN
        // ======================
        $this->pivotDocRekamanMutuModel
            ->where('document_id', $id)
            ->delete();

        foreach ($rekaman as $rekamanId) {
            $this->pivotDocRekamanMutuModel->insert([
                'document_id' => $id,
                'id'  => $rekamanId
            ]);
        }

        // ======================
        // SYNC TERKAIT
        // ======================
        $this->pivotDocTerkaitModel
            ->where('document_id', $id)
            ->delete();

        foreach ($terkait as $terkaitId) {
            $this->pivotDocTerkaitModel->insert([
                'document_id' => $id,
                'id'  => $terkaitId
            ]);
        }

        // ======================
        // HISTORY
        // ======================
        $this->historyDocModel->insert([
            'document_id'   => $id,
            'action'        => 'update',
            'no_document'   => $data['no_document'],
            'nama_document' => $data['nama_document'],
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Dokumen berhasil diupdate'
        ]);
    }

    public function ajaxDelete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request'
            ]);
        }

        $doc = $this->docModel->find($id);
        if (!$doc) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // hapus file PDF
        if (!empty($doc->file)) {
            $path = WRITEPATH . 'uploads/pdf/' . $doc->file;
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->docModel->delete($id);

        return $this->response->setJSON([
            'status' => true
        ]);
    }

    public function ajaxGetLinkDoc()
    {

        $search = $this->request->getGet('search');


        $limit  = (int) ($this->request->getGet('limit') ?? 20);
        $skip   = (int) ($this->request->getGet('skip') ?? 0);

        $builder = $this->docModel;

        // SEARCH ke nama_document + no_document
        if ($search) {
            $builder = $builder->groupStart()
                ->like('nama_document', $search)
                ->orLike('no_document', $search)
                ->groupEnd();
        }

        // total (pakai clone biar aman)
        $total = $builder->countAllResults(false);

        // ambil data
        $data = $builder
            ->select('id, no_document, nama_document')
            ->orderBy('created_at', 'DESC')
            ->findAll($limit, $skip);

        // format untuk SlimSelect
        $results = array_map(function ($row) {
            return [
                'id'   => $row->id,
                'namaDoc' => $row->no_document . ' - ' . $row->nama_document
            ];
        }, $data);

        return $this->response->setJSON([
            'results' => $results,
            'total'   => $total,
            'skip'    => $skip,
            'limit'   => $limit
        ]);
    }

    public function deleteBulk()
    {
        $request = $this->request->getJSON(true);
        $ids = $request['ids'] ?? [];

        if (empty($ids)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Tidak ada data dipilih'
            ]);
        }

        // Ambil data dokumen dulu
        $docs = $this->docModel->whereIn('id', $ids)->findAll();

        foreach ($docs as $doc) {
            if (!empty($doc->file)) {
                $path = WRITEPATH . 'uploads/pdf/' . $doc->file;
                if (file_exists($path)) {
                    @unlink($path); // hapus file, @ untuk menekan warning
                }
            }
        }

        // Hapus data di database
        $this->docModel->whereIn('id', $ids)->delete();

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
