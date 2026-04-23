<?php

namespace App\Controllers\Be;

use App\Controllers\BaseController;
use App\Models\DepartmentModel;
use App\Models\DivisiModel;
use App\Models\JenisDocumentModel;
use App\Models\LevelDocument;
use App\Models\SubCategoryNonIsoModel;

class MasterData extends BaseController
{
    protected $modelLevelDoc;
    protected $modelJenisDoc;
    protected $modelDivisi;
    protected $modelDept;
    protected $subCategoryNonIsoModel;

    public function __construct()
    {
        $this->modelLevelDoc = new LevelDocument();
        $this->modelJenisDoc = new JenisDocumentModel();
        $this->modelDivisi = new DivisiModel();
        $this->modelDept = new DepartmentModel();
        $this->subCategoryNonIsoModel = new SubCategoryNonIsoModel();
    }

    public function index()
    {
        $data = [
            'levelDoc' => $this->modelLevelDoc->findAll(),
            'jenisDoc' => $this->modelJenisDoc->findAll(),
            'department' => $this->modelDept->findAll(),
            'divisi' => $this->modelDivisi->getJoinData()->get()->getResult(),
            'subCategoryNonIso' => $this->subCategoryNonIsoModel->findAll()
        ];

        // dd($data['divisi']);
        return view('Be/master_data', $data);
    }

    public function store()
    {
        //simpan level
        if ($this->request->getPost('level')) {
            $data = [
                'level' => $this->request->getPost('level')
            ];
            $this->modelLevelDoc->insert($data, true);
        }

        //simpan jenis
        if ($this->request->getPost('jenis')) {
            $data = [
                'slug' => url_title($this->request->getPost('jenis'), '-', true),
                'jenis_document' => $this->request->getPost('jenis')
            ];
            $this->modelJenisDoc->insert($data, true);
        }

        // simpan divisi
        if ($this->request->getPost('kd_divisi')) {
            $data = [
                'kode_divisi' => $this->request->getPost('kd_divisi'),
                'nama_divisi' => $this->request->getPost('nm_divisi'),
                'department_id' => $this->request->getPost('dept'),
            ];
            $this->modelDivisi->insert($data, true);
        }

        //simpan department
        if ($this->request->getPost('nama_dept')) {
            $data = [
                'nama_dept' => $this->request->getPost('nama_dept'),
            ];
            $this->modelDept->insert($data, true);
        }

        //simpan sub category non iso
        if ($this->request->getPost('sub')) {
            
            //ambil jenis_id dari db table document_jenis
            $jenis = $this->modelJenisDoc->where('slug', 'document-non-iso')->first();

            $data = [
                'jenis_id' => $jenis->id,
                'slug' => url_title($this->request->getPost('sub'), '-', true),
                'nama' => $this->request->getPost('sub')
            ];
            $this->subCategoryNonIsoModel->insert($data, true);
        }

        return redirect()->back()->with('success', 'Data berhasil ditambah');
    }

    public function update($id)
    {
        $data = [
            'kode_divisi' => $this->request->getPost('kd_divisi'),
            'nama_divisi' => $this->request->getPost('nm_divisi'),
            'department_id' => $this->request->getPost('dept'),
        ];

        $this->modelDivisi->update($id, $data);

        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    public function delete($id)
    {
        $this->modelDivisi->delete($id);
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
