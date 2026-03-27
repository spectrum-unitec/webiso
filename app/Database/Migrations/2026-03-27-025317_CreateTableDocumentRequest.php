<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableDocumentRequest extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true, 'constraint' => 11],
            'nama_user' => ['type' => 'VARCHAR', 'constraint' => 155],
            'divisi_id' => ['type' => 'INT', 'unsigned' => true],
            'nama_doc' => ['type' => 'VARCHAR', 'constraint' => 200],
            'no_doc' => ['type' => 'VARCHAR', 'constraint' => 155],
            'revisi' => ['type' => 'VARCHAR', 'constraint' => 155],
            'tgl_pengajuan' => ['type' => 'DATE'],
            'jenis_pengajuan' => ['type' => 'ENUM', 'constraint' => ['baru', 'revisi', 'penghapusan']],
            'alasan' => ['type' => 'TEXT', 'null' => true],
            'usulan' => ['type' => 'TEXT', 'null' => true],
            'created_at'        => ['type' => 'DATETIME'],
            'updated_at'        => ['type' => 'DATETIME']
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('divisi_id', 'divisis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('document_requests');
    }

    public function down()
    {
        $this->forge->dropTable('document_requests', true);
    }
}
