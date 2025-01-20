<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'report_id' => Report::factory(), // レポートを関連付け
            'task_name' => $this->faker->sentence(3),
            'task_type' => $this->faker->randomElement(['type1', 'type2', 'type3']), // 適当なタスクタイプ
            'planned_hours' => $this->faker->numberBetween(1, 8),
            'actual_hours' => $this->faker->numberBetween(1, 8),
            'remarks' => $this->faker->sentence(10),
            'display_order' => $this->faker->numberBetween(1, 20),
        ];
    }
}
