<?php

namespace App\Models;

use CodeIgniter\Model;

class SubCategoryNonIsoModel extends Model
{
    protected $table            = 'sub_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['jenis_id', 'slug', 'nama', 'created_at', 'updated_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
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


    public function getDataJoin()
    {
        return $this->from('sub_categories sc')
            ->select('
            sc.*,
            dj.slug AS slug_jenis_doc,
            COUNT(DISTINCT d.id) AS total_doc
        ')
            ->join('document_jenis dj', 'dj.id = sc.jenis_id', 'left')
            ->join('my_documents d', 'd.sub_category_id = sc.id', 'left')
            ->groupBy('sc.id')
            ->orderBy('sc.id', 'DESC');
    }
}
