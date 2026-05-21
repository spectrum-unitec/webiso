<?php

namespace App\Controllers\Fe;

use App\Controllers\BaseController;
use App\Models\DepartmentModel;
use App\Models\DivisiModel;
use App\Models\DocumentRequestModel;
use App\Models\HistoryDocumentModel;
use App\Models\JenisDocumentModel;
use App\Models\MydocumentModel;
use App\Models\PivotDocumentTerkaitModel;
use App\Models\PivotRekamanMutuModel;
use App\Models\SubCategoryNonIsoModel;
use Config\Services;

class Home extends BaseController
{
    protected $divisiModel;
    protected $jenisModel;
    protected $departemenModel;
    protected $docModel;
    protected $historyModel;
    protected $subCategoryNonIsoModel;
    protected $pivotDocRekamanMutuModel;
    protected $pivotDocTerkaitModel;
    protected $documentReq;

    public function __construct()
    {
        $this->divisiModel = new DivisiModel();
        $this->jenisModel = new JenisDocumentModel();
        $this->departemenModel = new DepartmentModel();
        $this->docModel = new MydocumentModel();
        $this->historyModel = new HistoryDocumentModel();
        $this->subCategoryNonIsoModel = new SubCategoryNonIsoModel();
        $this->pivotDocRekamanMutuModel = new PivotRekamanMutuModel();
        $this->pivotDocTerkaitModel = new PivotDocumentTerkaitModel();
        $this->documentReq = new DocumentRequestModel();
    }

    public function index()
    {
        return view('Fe/home', [
            'navs' => $this->getNavs(),
            'countDocs' => $this->getCountDoc(),
            ...$this->getJenis(),
            ...$this->getHistoryDoc(),
            ...$this->getDivisi()
        ]);
    }

    public function menus(string $segment1, ?string $segment2 = null, ?string $segment3 = null)
    {
        $navs = $this->getNavs();
        $jenisData = $this->getJenis();

        $jenisSlug = $segment3 ? $segment2 : $segment1;

        $jenis = $this->resolveJenis($jenisSlug);
        $divisiId = $this->resolveDivisi($segment3);
        $subCategory = $this->resolveSubCategory($segment2);
        $docs = $this->getDocs($jenis->id, $divisiId, $subCategory);
        $docSlug = $this->request->getGet('doc');

        // ======================
        // VIEW PDF
        // ======================
        if ($docSlug) {
            $docQuery = $this->docModel->where('slug', $docSlug);

            if ($divisiId) {
                $docQuery->where('divisi_id', $divisiId);
            }

            $doc = $docQuery->first();

            $rekamanMutuList = $this->pivotDocRekamanMutuModel->getJoinData($doc->id)->findAll();
            $docTerkaitList = $this->pivotDocTerkaitModel->getJoinData($doc->id)->findAll();

            // dd($docTerkaitList);

            return view('Fe/view_pdf', [
                'navs' => $navs,
                'docs' => $docs,
                'doc'  => $doc,
                'jenisDoc' => $jenis,
                'segment1' => $segment1,
                'segment2' => $segment2,
                'segment3' => $segment3,
                'rekamanMutuList' => $rekamanMutuList,
                'docTerkaitList' => $docTerkaitList,
                'countDocs' => $this->getCountDoc(),
                ...$this->getHistoryDoc(),
                ...$jenisData,
                ...$this->getDivisi()
            ]);
        }

        // ======================
        // VIEW MENU
        // ======================

        //ini untuk view doc non iso

        return view('Fe/view_menus', [
            'navs' => $navs,
            'docs' => $docs,
            'jenisDoc' => $jenis,
            'segment1' => $segment1,
            'segment2' => $segment2,
            'segment3' => $segment3,
            'countDocs' => $this->getCountDoc(),
            ...$this->getHistoryDoc(),
            ...$jenisData,
            ...$this->getDivisi()
        ]);
    }

    private function getCountDoc(): array
    {
        $rows = $this->docModel
            ->select('jenis_id, divisi_id, sub_category_id, COUNT(*) as total')
            ->where('deleted_at', null)
            ->groupBy(['jenis_id', 'divisi_id', 'sub_category_id'])
            ->findAll();

        $map = [];

        foreach ($rows as $row) {

            // ISO
            if ($row->divisi_id) {
                $map['divisi'][$row->divisi_id][$row->jenis_id] = $row->total;
            } else {
                $map['manual-mutu'][$row->jenis_id] = $row->total;
            }
        }

        return $map;
    }

    private function getDivisi()
    {
        return [
            'divisis' => $this->divisiModel->getJoinData()->get()->getResultObject()
        ];
    }

    private function resolveJenis($slug)
    {
        $map = [
            'manual-mutu'      => 'Manual Mutu',
            'document-non-iso' => 'Document Non ISO',
        ];

        $jenis = $this->jenisModel
            ->where(isset($map[$slug]) ? 'jenis_document' : 'slug', $map[$slug] ?? $slug)
            ->first();

        if (!$jenis) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $jenis;
    }

    private function resolveDivisi($kode)
    {
        if (!$kode) return null;

        $divisi = $this->divisiModel
            ->select('id')
            ->where('kode_divisi', $kode)
            ->first();

        if (!$divisi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $divisi->id;
    }

    private function resolveSubCategory($slug)
    {
        if (!$slug) return null;

        $subCat = $this->subCategoryNonIsoModel
            ->select('id')
            ->where('slug', $slug)
            ->first();

        return $subCat->id ?? null;
    }

    private function getDocs($jenisId, $divisiId = null, $subCatId = null)
    {
        $builder = $this->docModel->builder();

        $builder->where('jenis_id', $jenisId);

        if (!empty($divisiId)) {
            $builder->where('divisi_id', $divisiId);
        }

        if (!empty($subCatId)) {
            $builder->where('sub_category_id', $subCatId);
        }

        return $builder
            ->orderBy("
            LEFT(no_document, LENGTH(no_document) - LENGTH(SUBSTRING_INDEX(no_document, '-', -1)) - 1)
        ", "", false)

            ->orderBy("
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no_document, '-', -1), '.', 1) AS UNSIGNED)
        ", "", false)

            ->orderBy("
            CAST(
                IF(LOCATE('.', no_document),
                    SUBSTRING_INDEX(no_document, '.', -1),
                    0
                ) AS UNSIGNED
            )
        ", "", false)

            ->get()
            ->getResult();
    }

    private function getNavs()
    {
        $rows = $this->divisiModel->getJoinData()->get()->getResult();

        $navs = [];
        foreach ($rows as $row) {
            $navs[$row->nama_dept]['nama_dept'] = $row->nama_dept;
            $navs[$row->nama_dept]['divisi'][] = $row;
        }

        return $navs;
    }

    private function getJenis()
    {
        return [
            'jenisAll' => $this->jenisModel
                ->whereNotIn('jenis_document', ['Manual Mutu', 'Document Non ISO'])
                ->findAll(),
            'jenisOnly' => $this->jenisModel->where('jenis_document', 'Manual Mutu')->first(),
            'nonIso'    => $this->jenisModel->where('jenis_document', 'Document Non ISO')->first(),
        ];
    }

    private function getHistoryDoc()
    {
        return [
            'histCreate' => $this->historyModel
                ->where('action', 'create')
                ->orderBy('id', 'DESC')
                ->findAll(5),
            'histUpdate' => $this->historyModel
                ->where('action', 'update')
                ->orderBy('id', 'DESC')
                ->findAll(5),
        ];
    }


    public function viewPdf($id)
    {

        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $doc = $this->docModel->find($id);

        if (!$doc) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $path = WRITEPATH . 'uploads/pdf/' . $doc->file;

        if (!is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $doc->file . '"')
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->setHeader('Pragma', 'no-cache')
            ->setHeader('Accept-Ranges', 'none')
            ->setBody(file_get_contents($path));
    }

    public function searchDoc()
    {
        $keyword    = trim($this->request->getGet('q') ?? '');
        $jenisSlug  = $this->request->getGet('jenis');
        $divisiKode = $this->request->getGet('divisi');
        $deptSlug   = $this->request->getGet('dept');
        $offset     = (int) ($this->request->getGet('offset') ?? 0);

        $limit = 10;

        if ($keyword === '') {
            return $this->response->setJSON([]);
        }

        $builder = $this->docModel->builder();

        // =========================
        // JOIN
        // =========================
        $builder->join('divisis', 'divisis.id = my_documents.divisi_id', 'left');
        $builder->join('departments', 'departments.id = divisis.department_id', 'left');

        // =========================
        // SEARCH
        // =========================
        $builder->groupStart()
            ->like('my_documents.nama_document', $keyword)
            ->orLike('my_documents.no_document', $keyword)
            ->groupEnd();

        // =========================
        // FILTER JENIS
        // =========================
        if (!empty($jenisSlug)) {
            $jenis = $this->jenisModel->where('slug', $jenisSlug)->first();
            if ($jenis) {
                $builder->where('my_documents.jenis_id', $jenis->id);
            }
        }

        // =========================
        // FILTER DIVISI
        // =========================
        if (!empty($divisiKode)) {
            $builder->where('divisis.kode_divisi', $divisiKode);
        }

        // =========================
        // FILTER DEPARTMENT (tanpa slug)
        // =========================
        if (!empty($deptSlug)) {
            $deptName = ucwords(str_replace('-', ' ', $deptSlug));

            // cocokkan ke nama_department
            $builder->like('departments.nama_dept', $deptName);
        }

        // =========================
        // EXECUTE
        // =========================
        $docs = $builder
            ->select('my_documents.*')
            ->orderBy('my_documents.nama_document', 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResult();

        $result = [];

        foreach ($docs as $doc) {

            if (!empty($divisiKode)) {
                $route = route_to('home.menus.divisi', $deptSlug, $jenisSlug, $divisiKode);
            } else {
                $route = route_to('home.menus', $jenisSlug);
            }

            $result[] = [
                'no_document'   => $doc->no_document,
                'nama_document' => $doc->nama_document,
                'url'           => base_url($route) . '?doc=' . $doc->slug
            ];
        }

        return $this->response->setJSON($result);
    }

    public function requestDoc()
    {
        // =========================
        // VALIDASI FILE
        // =========================

        $validation = Services::validation();

        $validation->setRules([
            'lampiran' => [
                'rules' => 'uploaded[lampiran]'
                    . '|max_size[lampiran,5120]'
                    . '|ext_in[lampiran,pdf,doc,docx,xls,xlsx]',
                'errors' => [
                    'uploaded' => 'File wajib diupload',
                    'max_size' => 'Ukuran file maksimal 5MB',
                    'ext_in'   => 'Format file tidak didukung'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $validation->getError('lampiran')
            ]);
        }

        // =========================
        // UPLOAD FILE
        // =========================

        $file = $this->request->getFile('lampiran');

        $namaFile = null;

        if ($file->isValid() && !$file->hasMoved()) {

            // nama random
            $namaFile = $file->getRandomName();

            // folder upload
            $file->move(ROOTPATH . 'public/uploads/request-doc/', $namaFile);
        }

        // =========================
        // SIMPAN DATABASE
        // =========================

        $data = [
            'nama_user'       => $this->request->getPost('nama_user'),
            'email'           => $this->request->getPost('email'),
            'divisi_id'       => $this->request->getPost('divisi_id'),
            'nama_doc'        => $this->request->getPost('nama_doc'),
            'no_doc'          => $this->request->getPost('no_doc'),
            'revisi'          => $this->request->getPost('revisi'),
            'tgl_pengajuan'   => $this->request->getPost('tgl_pengajuan'),
            'jenis_pengajuan' => $this->request->getPost('jenis_pengajuan'),
            'alasan'          => $this->request->getPost('alasan'),
            'usulan'          => $this->request->getPost('usulan'),

            // simpan nama file
            'file'        => $namaFile,
        ];

        $model = $this->documentReq;

        if ($model->insert($data)) {

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Pengajuan berhasil dikirim!'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Gagal menyimpan data'
        ]);
    }

    public function listSubCategories()
    {
        $listCategory = $this->subCategoryNonIsoModel->getDataJoin()->findAll();

        return view('Fe/view_sub_category', [
            'navs' => $this->getNavs(),
            'listCategory' => $listCategory,
            'countDocs' => $this->getCountDoc(),
            ...$this->getJenis(),
            ...$this->getHistoryDoc(),
            ...$this->getDivisi()
        ]);
    }
}
