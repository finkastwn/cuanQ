<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSourceMoneyToManualTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('manual_transactions', [
            'source_money' => [
                'type'       => 'ENUM',
                'constraint' => ['duit_pribadi', 'bank_account', 'shopee_pocket'],
                'default'    => 'bank_account',
                'after'      => 'type',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('manual_transactions', 'source_money');
    }
}