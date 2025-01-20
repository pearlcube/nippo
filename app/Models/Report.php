<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'report_date',
    ];

    /**
     * リレーション: このレポートに関連するタスク
     */
    public function tasks()
    {
        return $this->hasMany(Task::class)->with('masterTaskType');
    }

    /**
     * リレーション: このレポートを確認したユーザー
     */
    public function confirmations()
    {
        return $this->hasMany(ReportConfirmation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}

class MasterTaskType extends Model
{
    use HasFactory;

    protected $table = 'master_task_type';

    protected $fillable = [
        'type_code',
        'type_name',
    ];
}

class ReportConfirmation extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'user_id',
    ];

    /**
     * リレーション: 確認対象のレポート
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * リレーション: 確認したユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
