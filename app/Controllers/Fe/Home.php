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

        $docs = $docBuilder->findAll();

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
            'jenisDoc'
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
            ->setHeader('Accept-Ranges', 'none') // ⛔ penting untuk IDM
            ->setBody(file_get_contents($path));
    }
}
