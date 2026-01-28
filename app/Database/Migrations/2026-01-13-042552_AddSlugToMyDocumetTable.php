<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSlugToMyDocumetTable extends Migration
{
    public function up()
    {

        $fields = [
            'slug' => ['type' => 'VARCHAR', 'constraint' => 200, 'after' => 'no_document', 'unique' => true]
        ];

        $this->forge->addColumn('my_documents', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('my_documents', 'slug');
    }
}
