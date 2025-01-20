<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterTaskTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('master_task_type')->insert([
            ['type_code' => 'design', 'type_name' => '設計', 'created_at' => now(), 'updated_at' => now()],
            ['type_code' => 'development', 'type_name' => '開発', 'created_at' => now(), 'updated_at' => now()],
            ['type_code' => 'testing', 'type_name' => 'テスト', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
