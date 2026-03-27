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
            ->edit('status', function ($row) {
                switch ($row->status) {
                    case 'approved':
                        return "<div class='badge text-bg-success'>{$row->status}</div>";
                        break;

                    case 'reject':
                        return "<div class='badge text-bg-danger'>{$row->status}</div>";
                        break;

                    default:
                        return "<div class='badge text-bg-warning'>{$row->status}</div>";
                        break;
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
}
