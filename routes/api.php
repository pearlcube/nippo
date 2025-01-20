<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//ユーザー一覧取得(ユーザ選択コンボボックス用)
Route::get('/users', function () {
    return User::all(); // 全ユーザーを取得して返す
});

//日報データ取得 API
Route::get('/reports', function (Request $request) {
    $userId = $request->query('user'); // クエリパラメータからユーザーID取得
    $month = $request->query('month'); // クエリパラメータから年月取得

    $reports = Report::with(['tasks.masterTaskType', 'user'])
        ->where('user_id', $userId)
        ->whereMonth('report_date', substr($month, 5, 2))
        ->whereYear('report_date', substr($month, 0, 4))
        ->get();

    // 日付ごとにグループ化
    return $reports->groupBy(function ($report) {
        return (int) \Carbon\Carbon::parse($report->report_date)->format('d');
    });
});

//日報データ保存
Route::post('/api/reports/save/{day}', function (Request $request, $day) {
    // 保存ロジック
    return response()->json(['success' => true]);
});
