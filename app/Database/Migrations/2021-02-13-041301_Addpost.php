<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Addpost extends Migration
{
	public function up()
	{
		$this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'userId' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '500',
                'null' => false
            ],
            'body' => [
                'type' => 'TEXT',
                'null' => false
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
        'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('post');
	}

	public function down()
	{
		$this->forge->dropTable('post');
	}
}
