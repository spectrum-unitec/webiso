<?php

namespace App\Controllers\Be;

use App\Controllers\BaseController;
use App\Models\DocumentRequestModel;
use CodeIgniter\I18n\Time;
use Config\Services;
use Hermawan\DataTables\DataTable;

class RequestDocument extends BaseController
{
    protected $docReq;

    public function __construct()
    {
        $this->docReq = new DocumentRequestModel();
    }

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

        return DataTable::of($this->docReq->getDataJoin())
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

        $data = $this->docReq->find($id);

        return $this->response->setJSON([
            'alasan' => $data->alasan,
            'usulan' => $data->usulan
        ]);
    }

    public function ajaxKonfirmasi()
    {
        // =========================
        // VALIDASI AJAX
        // =========================

        if (!$this->request->isAJAX()) {

            return $this->response->setStatusCode(403)->setJSON([
                'status'  => false,
                'message' => 'Forbidden'
            ]);
        }

        // =========================
        // AMBIL INPUT
        // =========================

        $id       = $this->request->getPost('id');
        $status   = $this->request->getPost('status');
        $catatan  = $this->request->getPost('catatan');

        // =========================
        // VALIDASI INPUT
        // =========================

        if (!$id || !in_array($status, ['approved', 'reject'])) {

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data tidak valid'
            ]);
        }

        // =========================
        // CEK DATA
        // =========================

        $data = $this->docReq->find($id);

        if (!$data) {

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // =========================
        // UPDATE STATUS
        // =========================

        $update = [
            'status'  => $status,
            'note' => $catatan,
        ];

        $this->docReq->update($id, $update);

        // =========================
        // KIRIM EMAIL
        // =========================

        try {

            $email = Services::email();

            $email->setFrom(
                env('email.fromEmail'),
                env('email.fromName')
            );

            // email user pengaju
            $email->setTo($data->email);

            // status label
            $statusLabel = $status === 'approved'
                ? 'DISETUJUI'
                : 'DITOLAK';

            $email->setSubject(
                'Status Pengajuan Dokumen'
            );

            // warna badge
            $badgeColor = $status === 'approved'
                ? '#198754'
                : '#dc3545';


            $message = "
            <div style='font-family:Arial,sans-serif'>

                <h2>Status Pengajuan Dokumen</h2>

                <p>
                    Halo <b>{$data->nama_user}</b>,
                </p>

                <p>
                    Pengajuan dokumen Anda telah diproses.
                </p>

                <table 
                    cellpadding='3' 
                    cellspacing='0' 
                    border='0'
                    width='100%'
                    style='border-collapse:collapse'>

                    <tr>
                        <td width='200'><b>Nama Dokumen</b></td>
                        <td>: {$data->nama_doc}</td>
                    </tr>

                    <tr>
                        <td><b>No Dokumen</b></td>
                        <td>: {$data->no_doc}</td>
                    </tr>

                    <tr>
                        <td><b>Jenis Pengajuan Dokumen</b></td>
                        <td>: {$data->jenis_pengajuan}</td>
                    </tr>

                    <tr>
                        <td><b>Status</b></td>
                        <td>: 
                            <span style='color:#fff;
                                         background:{$badgeColor};
                                         padding:5px 10px;
                                         border-radius:4px;'>
                                {$statusLabel}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td><b>Catatan</b></td>
                        <td>: {$catatan}</td>
                    </tr>
                </table>

                <br>

                <!-- FOOTER NOTE -->
                <div style='
                    margin-top:30px;
                    padding:18px;
                    background:#f8fafc;
                    border-left:4px solid #0d6efd;
                    border-radius:6px;
                    font-size:13px;
                    color:#555;
                    line-height:1.8;
                '>

                    Email ini dikirim otomatis oleh sistem.
                    Mohon tidak membalas email ini.

                </div>

                <p style='
                margin-top:30px;
                font-size:14px;
                color:#555;
            '>
                Terima kasih,<br>
                <b>Admin Web ISO</b>
            </p>

            </div>
        ";

            $email->setMessage($message);

            // lampiran optional
            if (!empty($data->file)) {

                $path = ROOTPATH . 'public/uploads/request-doc/' . $data->file;

                if (file_exists($path)) {
                    $email->attach($path);
                }
            }

            $email->send();
        } catch (\Throwable $e) {

            log_message('error', $e->getMessage());
        }

        // =========================
        // RESPONSE
        // =========================

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Berhasil dikonfirmasi'
        ]);
    }

    public function ajaxDelete()
    {
        // =========================
        // VALIDASI AJAX
        // =========================

        if (!$this->request->isAJAX()) {

            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status'  => false,
                    'message' => 'Forbidden'
                ]);
        }

        // =========================
        // AMBIL ID
        // =========================

        $id = $this->request->getPost('id');

        if (!$id) {

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'ID tidak valid'
            ]);
        }

        // =========================
        // CEK DATA
        // =========================

        $data = $this->docReq->find($id);

        if (!$data) {

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // =========================
        // HAPUS FILE LAMPIRAN
        // =========================

        if (!empty($data->file)) {

            $filePath = ROOTPATH .
                'public/uploads/request-doc/' .
                $data->file;

            if (file_exists($filePath)) {

                unlink($filePath);
            }
        }

        // =========================
        // HAPUS DATA
        // =========================

        $this->docReq->delete($id);

        // =========================
        // RESPONSE
        // =========================

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function preview($id)
    {
        $data = $this->docReq->find($id);

        if (!$data) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $file = ROOTPATH . 'public/uploads/request-doc/' . $data->file;

        if (!file_exists($file)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        // =========================
        // PDF PREVIEW
        // =========================

        if ($extension === 'pdf') {

            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setBody(file_get_contents($file));
        }

        // =========================
        // WORD / EXCEL DOWNLOAD
        // =========================

        return $this->response->download($file, null);
    }
}
