<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyColumnTableMyDocument extends Migration
{
    public function up()
    {
        // 1. DROP semua FK yang perlu diubah
        $this->forge->dropForeignKey('my_documents', 'my_documents_level_id_foreign');
        $this->forge->dropForeignKey('my_documents', 'my_documents_divisi_id_foreign');

        // 2. MODIFY column jadi nullable
        $this->forge->modifyColumn('my_documents', [
            'level_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'divisi_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
        ]);

        // 3. ADD FK ulang dengan rule yang benar

        // level_id → SET NULL
        $this->forge->addForeignKey(
            'level_id',
            'document_level_iso',
            'id',
            'SET NULL',
            'CASCADE'
        );

        // divisi_id → SET NULL
        $this->forge->addForeignKey(
            'divisi_id',
            'divisis',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->forge->processIndexes('my_documents');
    }

    public function down()
    {
        //
    }
}
