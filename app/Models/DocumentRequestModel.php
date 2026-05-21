<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentRequestModel extends Model
{
    protected $table            = 'document_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_user', 'email', 'divisi_id', 'nama_doc', 'no_doc', 'revisi', 'tgl_pengajuan', 'jenis_pengajuan', 'alasan', 'usulan', 'file', 'status', 'note', 'created_at', 'updated_at'];

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
        return $this->from('document_requests dr')
            ->select('
            dr.id,
            dr.nama_user,
            dr.nama_doc,
            dr.no_doc,
            dr.revisi,
            dr.tgl_pengajuan,
            dr.jenis_pengajuan,
            dr.alasan,
            dr.usulan,
            dr.status,
            dr.email,
            dr.created_at,
            dr.updated_at,
            dv.nama_divisi,
            dv.kode_divisi
        ')
            ->join('divisis dv', 'dv.id = dr.divisi_id', 'left')
            ->groupBy('dr.id')
            ->orderBy('dr.created_at', 'DESC');
    }
}
