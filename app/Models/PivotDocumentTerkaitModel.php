<?php

namespace App\Models;

use CodeIgniter\Model;

class PivotDocumentTerkaitModel extends Model
{
    protected $table            = 'pivot_document_terkait';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'document_id'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getJoinData($id)
    {
        return $this->from('pivot_document_terkait AS pdt')
            ->select(
                ' 
                md.nama_document,
                md.no_document,
                md.slug AS doc_slug, 
                dj.slug,
                d.kode_divisi,
                dept.nama_dept
                '
            )
            ->join('my_documents AS md', 'md.id = pdt.id')
            ->join('document_jenis AS dj', 'dj.id = md.jenis_id')
            ->join('divisis AS d', 'd.id = md.divisi_id')
            ->join('departments AS dept', 'dept.id = department_id')
            ->where('pdt.document_id', $id)
            ->groupBy('pdt.id');
    }
}
