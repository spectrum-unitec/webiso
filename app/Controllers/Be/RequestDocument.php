<?php

namespace App\Controllers\Be;

use App\Controllers\BaseController;
use App\Models\DocumentRequestModel;
use CodeIgniter\I18n\Time;
use Hermawan\DataTables\DataTable;

class RequestDocument extends BaseController
{
    public function index()
    {
        return view('Be/request_document');
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

        $model = new DocumentRequestModel();

        return DataTable::of($model->getDataJoin())
            ->add('action', function ($row) {
                return $row->id;
            })

            ->add('status_badge', function ($row) {
                switch ($row->status) {
                    case 'approved':
                        return "<div class='badge text-bg-success'>{$row->status}</div>";

                    case 'reject':
                        return "<div class='badge text-bg-danger'>{$row->status}</div>";

                    default:
                        return "<div class='badge text-bg-warning'>{$row->status}</div>";
                }
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

    public function getDetailModal()
    {
        $id = $this->request->getGet('id');

        $model = new \App\Models\DocumentRequestModel();

        $data = $model->find($id);

        return $this->response->setJSON([
            'alasan' => $data->alasan,
            'usulan' => $data->usulan
        ]);
    }

    public function ajaxKonfirmasi()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => false,
                'message' => 'Forbidden'
            ]);
        }

        $id      = $this->request->getPost('id');
        $status  = $this->request->getPost('status');
        $catatan = $this->request->getPost('catatan');

        // validasi sederhana
        if (!$id || !in_array($status, ['approved', 'reject'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data tidak valid'
            ]);
        }

        $model = new DocumentRequestModel();

        // cek data ada
        $data = $model->find($id);
        if (!$data) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // update
        $model->update($id, [
            'status'     => $status,
            'catatan'    => $catatan,
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Berhasil dikonfirmasi'
        ]);
    }

    public function ajaxDelete()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => false,
                'message' => 'Forbidden'
            ]);
        }

        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'ID tidak valid'
            ]);
        }

        $model = new DocumentRequestModel();

        $data = $model->find($id);
        if (!$data) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // Hapus data
        $model->delete($id);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
