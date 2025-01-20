<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\Task;

class ReportTaskSeeder extends Seeder
{
    public function run()
    {
        // 10件のレポートを作成し、それぞれにタスクを紐づける
        Task::factory()
            ->count(3)
            ->create();
    }
}
