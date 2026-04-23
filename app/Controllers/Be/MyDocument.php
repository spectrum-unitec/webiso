<?php

namespace App\Controllers\Be;

use App\Controllers\BaseController;
use App\Libraries\Breadcrumb;
use App\Models\DivisiModel;
use App\Models\HistoryDocumentModel;
use App\Models\JenisDocumentModel;
use App\Models\LevelDocument;
use App\Models\MydocumentModel;
use App\Models\SubCategoryNonIsoModel;
use CodeIgniter\I18n\Time;
use Hermawan\DataTables\DataTable;

class MyDocument extends BaseController
{
    protected $docModel;
    protected $jenisDocModel;
    protected $historyDocModel;
    protected $subCategoryModel;

    public function __construct()
    {
        $this->docModel = new MydocumentModel();
        $this->jenisDocModel = new JenisDocumentModel();
        $this->historyDocModel = new HistoryDocumentModel();
        $this->subCategoryModel = new SubCategoryNonIsoModel();
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

        $modelJenisDoc = new JenisDocumentModel();
        $jenisDoc = $modelJenisDoc->where('jenis_document !=', 'Document Non Iso')->findAll();

        $modelDivisi = new DivisiModel();
        $divisi = $modelDivisi->findAll();

        $subCategories = $this->subCategoryModel
            ->findAll();

        return view('Be/my_document', compact('bread', 'levelDoc', 'jenisDoc', 'divisi', 'subCategories'));
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
        return $this->response->setJSON(
            $this->docModel->find($id)
        );
    }

    public function ajaxUpdate()
    {
        if (!$this->request->isAJAX()) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Forbidden access'
                ]);
        }

        $id  = $this->request->getPost('id');
        $doc = $this->docModel->find($id);
        $jenisId = $this->request->getPost('jenis');


        if (!$doc) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // 🔥 DETEKSI TIPE
        $isIso = !empty($doc->level_id);

        $isManualMutu = $this->jenisDocModel->find($jenisId);

        // ================= DATA =================
        $data = [
            'no_document'   => $this->request->getPost('no_doc'),
            'nama_document' => $this->request->getPost('nm_doc'),
        ];

        if ($isIso) {
            $data['level_id']  = $this->request->getPost('level');
            $data['jenis_id']  = $this->request->getPost('jenis');
            $data['divisi_id'] = $this->request->getPost('divisi');

            if ($isManualMutu->slug === 'manual-mutu') {
                $data['divisi_id'] = null;
            } else {
                $data['divisi_id'] = $this->request->getPost('divisi');
            }
        } else {
            $data['level_id']  = null;
            $data['divisi_id'] = null;
            $data['jenis_id']  = $doc->jenis_id; // ✅ FIX
        }

        // ================= FILE =================
        $file = $this->request->getFile('pdf_file');

        // 🔥 DEBUG 1: cek file masuk atau tidak
        // if (!$file) {
        //     dd('FILE NULL');
        // }

        // 🔥 DEBUG 2: info dasar file
        // var_dump([
        //     'name'  => $file->getName(),
        //     'size'  => $file->getSize(),
        //     'error' => $file->getError(),
        // ]);
        // die;

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

            // hapus file lama
            if (!empty($doc->file) && file_exists($path . '/' . $doc->file)) {
                unlink($path . '/' . $doc->file);
            }
        }

        // ================= CEK PERUBAHAN =================
        $isChanged = false;
        foreach ($data as $key => $value) {
            if ($doc->$key != $value) { // ✅ FIX
                $isChanged = true;
                break;
            }
        }

        if (!$isChanged) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Tidak ada perubahan data'
            ]);
        }

        // ================= UPDATE =================
        $this->docModel->update($id, $data);

        // ================= HISTORY =================
        $this->historyDocModel->insert([
            'document_id' => $id,
            'action' => 'update',
            'no_document' => $data['no_document'],
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
