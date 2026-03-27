<?php

namespace App\Controllers\Fe;

use App\Controllers\BaseController;
use App\Models\DivisiModel;
use App\Models\JenisDocumentModel;
use App\Models\MydocumentModel;

class Home extends BaseController
{
    protected $divisiModel;
    protected $jenisModel;
    protected $docModel;

    public function __construct()
    {
        $this->divisiModel = new DivisiModel();
        $this->jenisModel = new JenisDocumentModel();
        $this->docModel = new MydocumentModel();
    }

    public function index()
    {
        $title = 'Home Page';
        $navs = $this->divisiModel->findAll();
        $jenisAll = $this->jenisModel->where('jenis_document !=', 'Manual Mutu')->findAll();
        $jenisOnly = $this->jenisModel->where('jenis_document', 'Manual Mutu')->first();

        return view('Fe/home', compact('navs', 'jenisAll', 'jenisOnly'));
    }

    public function menus(string $segment1, ?string $segment2 = null)
    {
        // ======================
        // Common data (navbar)
        // ======================
        $navs      = $this->divisiModel->findAll();
        $jenisAll  = $this->jenisModel
            ->where('jenis_document !=', 'Manual Mutu')
            ->findAll();
        $jenisOnly = $this->jenisModel
            ->where('jenis_document', 'Manual Mutu')
            ->first();

        // ======================
        // Jenis document
        // ======================
        $jenisSlug = $segment1 === 'manual-mutu' ? null : $segment1;
        $jenisDoc  = $jenisSlug
            ? $this->jenisModel->where('slug', $jenisSlug)->first()
            : null;

        $jenis = $this->jenisModel
            ->select('id')
            ->where('slug', $segment1)
            ->first();

        if (!$jenis) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // ======================
        // Documents query
        // ======================
        $docBuilder = $this->docModel->where('jenis_id', $jenis->id);

        $divisiId = null;
        if ($segment2) {
            $divisi = $this->divisiModel
                ->select('id')
                ->where('kode_divisi', $segment2)
                ->first();

            if ($divisi) {
                $divisiId = $divisi->id;
                $docBuilder->where('divisi_id', $divisiId);
            }
        }

        $docs = $docBuilder
            ->orderBy("
        LEFT(no_document, LENGTH(no_document) - LENGTH(SUBSTRING_INDEX(no_document, '-', -1)) - 1)
    ", "ASC", false)
            ->orderBy("
        CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no_document, '-', -1), '.', 1) AS UNSIGNED)
    ", "ASC", false)
            ->orderBy("
        CAST(
            IF(LOCATE('.', no_document),
                SUBSTRING_INDEX(no_document, '.', -1),
                0
            ) AS UNSIGNED
        )
    ", "ASC", false)
            ->findAll();

        // ======================
        // View PDF
        // ======================
        $docSlug = $this->request->getGet('doc');
        if ($docSlug) {

            $docQuery = $this->docModel->where('slug', $docSlug);

            if ($divisiId) {
                $docQuery->where('divisi_id', $divisiId);
            }

            $doc = $docQuery->first();

            return view('Fe/view_pdf', compact(
                'navs',
                'jenisAll',
                'jenisOnly',
                'docs',
                'doc',
                'jenisDoc'
            ));
        }

        // ======================
        // View menus
        // ======================
        return view('Fe/view_menus', compact(
            'navs',
            'jenisAll',
            'jenisOnly',
            'docs',
            'jenisDoc',
            'segment1',
            'segment2'
        ));
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
        $keyword    = $this->request->getGet('q');
        $jenisSlug  = $this->request->getGet('jenis');
        $divisiKode = $this->request->getGet('divisi');
        $offset     = (int) ($this->request->getGet('offset') ?? 0);

        $limit = 10;

        if (!$keyword) {
            return $this->response->setJSON([]);
        }

        $builder = $this->docModel;

        $builder->groupStart()
            ->like('nama_document', $keyword)
            ->orLike('no_document', $keyword)
            ->groupEnd();

        // jenis
        if ($jenisSlug) {
            $jenis = $this->jenisModel->where('slug', $jenisSlug)->first();
            if ($jenis) {
                $builder->where('jenis_id', $jenis->id);
            }
        }

        // divisi
        if ($divisiKode) {
            $divisi = $this->divisiModel->where('kode_divisi', $divisiKode)->first();
            if ($divisi) {
                $builder->where('divisi_id', $divisi->id);
            }
        }

        $docs = $builder
            ->orderBy('nama_document', 'ASC')
            ->findAll($limit, $offset);

        $result = [];

        foreach ($docs as $doc) {

            $route = !empty($divisiKode)
                ? route_to('home.menus.divisi', $jenisSlug, $divisiKode)
                : route_to('home.menus', $jenisSlug);

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
        $data = [
            'nama_user'        => $this->request->getPost('nama_user'),
            'email'        => $this->request->getPost('email'),
            'divisi_id'        => $this->request->getPost('divisi'),
            'nama_doc'         => $this->request->getPost('nama_doc'),
            'no_doc'           => $this->request->getPost('no_doc'),
            'revisi'           => $this->request->getPost('revisi'),
            'tgl_pengajuan'    => $this->request->getPost('tgl_pengajuan'),
            'jenis_pengajuan'  => $this->request->getPost('jenis_pengajuan'),
            'alasan'           => $this->request->getPost('alasan'),
            'usulan'           => $this->request->getPost('usulan'),
        ];

        $model = new \App\Models\DocumentRequestModel();

        if ($model->insert($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Pengajuan berhasil dikirim!'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal menyimpan data'
        ]);
    }
}
