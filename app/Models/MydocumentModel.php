<?php

namespace App\Models;

use CodeIgniter\Model;

class MydocumentModel extends Model
{
    protected $table            = 'my_documents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['no_document', 'slug', 'nama_document', 'level_id', 'jenis_id', 'divisi_id', 'file', 'created_at', 'updated_at', 'deleted_at'];

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
        return $this->select('my_documents.id,my_documents.no_document,my_documents.nama_document,my_documents.created_at,my_documents.updated_at,dl.level,dj.jenis_document')
            ->join('document_level_iso dl', 'dl.id = my_documents.level_id', 'left')
            ->join('document_jenis dj', 'dj.id = my_documents.jenis_id', 'left')
            ->orderBy('my_documents.created_at', 'DESC');
    }
}
