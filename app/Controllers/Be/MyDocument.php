<?php

namespace App\Controllers\Be;

use App\Controllers\BaseController;
use App\Libraries\Breadcrumb;
use App\Models\DivisiModel;
use App\Models\JenisDocumentModel;
use App\Models\LevelDocument;
use App\Models\MydocumentModel;
use CodeIgniter\I18n\Time;
use Hermawan\DataTables\DataTable;

class MyDocument extends BaseController
{
    protected $docModel;

    public function __construct()
    {
        $this->docModel = new MydocumentModel();
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
        $jenisDoc = $modelJenisDoc->findAll();

        $modelDivisi = new DivisiModel();
        $divisi = $modelDivisi->findAll();

        return view('Be/my_document', compact('bread', 'levelDoc', 'jenisDoc', 'divisi'));
    }

    public function ajaxStore()
    {

        if (!$this->request->isAJAX()) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Forbidden access'
                ]);
        }

        $divisiId = $this->request->getPost('divisi');
        $pdfFile = $this->request->getFile('pdf_file');
        $newName = $pdfFile->getRandomName();

        $pdfFile->move(WRITEPATH . 'uploads/pdf', $newName);

        $this->docModel->insert([
            'no_document' => $this->request->getPost('no_doc'),
            'slug' => url_title($this->request->getPost('nm_doc'), '-', true),
            'nama_document' => $this->request->getPost('nm_doc'),
            'level_id' => $this->request->getPost('level'),
            'jenis_id' => $this->request->getPost('jenis'),
            'divisi_id' => empty($divisiId) ? null : $divisiId,
            'file' => $newName
        ], true);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'PDF berhasil diupload'
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

        $id = $this->request->getPost('id');
        $doc = $this->docModel->find($id);

        $data = [
            'no_document' => $this->request->getPost('no_doc'),
            'nama_document' => $this->request->getPost('nm_doc'),
            'level_id' => $this->request->getPost('level'),
            'jenis_id' => $this->request->getPost('jenis'),
            'divisi_id' => $this->request->getPost('divisi'),
        ];

        // File upload baru
        $file = $this->request->getFile('pdf_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Pindahkan ke writable/uploads
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/pdf', $newName);

            $data['file'] = $newName;

            // Opsional: hapus file lama
            if (!empty($doc->file) && file_exists(WRITEPATH . 'uploads/pdf/' . $doc->file)) {
                unlink(WRITEPATH . 'uploads/pdf/' . $doc->file);
            }
        }

        $this->docModel->update($id, $data);

        return $this->response->setJSON(['success' => true]);
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
