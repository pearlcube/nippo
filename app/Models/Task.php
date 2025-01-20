<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'task_name',
        'task_type',
        'planned_hours',
        'actual_hours',
        'remarks',
        'display_order',
    ];

    /**
     * リレーション: このタスクが所属するレポート
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

     /**
     * リレーション: マスタの値と一緒に取得
     */
    public function masterTaskType()
    {
        return $this->belongsTo(MasterTaskType::class, 'task_type', 'type_code')->withDefault([
            'type_name' => '未定義',
        ]);
    }

}