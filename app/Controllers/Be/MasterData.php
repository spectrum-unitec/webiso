<?php

namespace App\Controllers\Be;

use App\Controllers\BaseController;
use App\Models\DivisiModel;
use App\Models\JenisDocumentModel;
use App\Models\LevelDocument;

class MasterData extends BaseController
{
    protected $modalLevelDoc;
    protected $modalJenisDoc;
    protected $modalDivisi;

    public function __construct()
    {
        $this->modalLevelDoc = new LevelDocument();
        $this->modalJenisDoc = new JenisDocumentModel();
        $this->modalDivisi = new DivisiModel();
    }

    public function index()
    {
        $data = [
            'levelDoc' => $this->modalLevelDoc->findAll(),
            'jenisDoc' => $this->modalJenisDoc->findAll(),
            'divisi' => $this->modalDivisi->findAll()
        ];
        return view('Be/master_data', $data);
    }

    public function store()
    {
        if ($this->request->getPost('level')) {
            $data = [
                'level' => $this->request->getPost('level')
            ];
            $this->modalLevelDoc->insert($data, true);
        }
        if ($this->request->getPost('jenis')) {
            $data = [
                'slug' => url_title($this->request->getPost('jenis'), '-', true),
                'jenis_document' => $this->request->getPost('jenis')
            ];
            $this->modalJenisDoc->insert($data, true);
        }
        if ($this->request->getPost('kd_divisi')) {
            $data = [
                'kode_divisi' => $this->request->getPost('kd_divisi'),
                'nama_divisi' => $this->request->getPost('nm_divisi'),
            ];
            $this->modalDivisi->insert($data, true);
        }
        return redirect()->route('admin.masterdata');
    }
}
