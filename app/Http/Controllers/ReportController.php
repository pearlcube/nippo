<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\MasterTaskType;
use App\Models\ReportConfirmation;
use App\Rules\MultipleOfQuarter;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class ReportController extends Controller
{
    public function index(Request $request)
{
    // デフォルト値を設定
    $userId = $request->input('user_id', 'default'); // デフォルト値として 'default' を使用
    $currentMonth = $request->input('indexmonth', now()->format('Y-m')); // 現在の年月

     // currentMonth のみバリデーション
     $validator = \Validator::make(['indexmonth' => $currentMonth], [
        'indexmonth' => ['required', 'date_format:Y-m', 'before:2055-12'],
    ], [
        'indexmonth.required' => '年月は必須です。',
        'indexmonth.date_format' => '年月はYYYY-MM形式で入力してください。',
        'indexmonth.before' => '年月は2025年12月以前の日付を指定してください。',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }
    
    // レポートのクエリを構築
    $query = Report::with(['tasks.masterTaskType', 'user']);

    // ユーザーのフィルタリング
    if ($userId === 'default') {
        $query->where('user_id', auth()->id()); // ログインユーザー
    } elseif ($userId !== 'all') {
        $query->where('user_id', $userId); // 指定されたユーザー
    }
    // 「すべて（all）」の場合は絞り込みなし

    // 月のフィルタリング
    $query->whereYear('report_date', substr($currentMonth, 0, 4)) // 年を抽出
          ->whereMonth('report_date', substr($currentMonth, 5, 2)); // 月を抽出

          // 並び替えを追加（日付順、次にユーザーID順）
    $query->orderBy('user_id') // 日付順
    ->orderBy('report_date');    // ユーザーID順

    // データを取得
    $reports = $query->get();

    // ユーザーリストを取得（「すべて」を含む）
    $users = \App\Models\User::all();
    $users->prepend((object)['id' => 'all', 'name' => 'すべて']); // 先頭に「すべて」を追加

    // ビューにデータを渡す
    return view('reports.index', compact('reports', 'users', 'userId', 'currentMonth'));
}

public function edit($id)
{
    $report = Report::with('tasks')->findOrFail($id);
    $masterTaskTypes = MasterTaskType::all(); // タスク種類を取得

    dump(session('errors')); // セッション内のエラーを確認

    return view('reports.edit', compact('report', 'masterTaskTypes'));
}

public function update(Request $request, $id)
{
    $tasks = collect($request->input('tasks', []))
    ->filter(function ($task) {
        // タスク名が空でない行だけを残す
        return !empty($task['task_name']);
    });

$errors = new MessageBag();
$validatedTasks = $tasks->map(function ($task, $index) use ($errors) {
    $validator = Validator::make($task, [
        'task_name' => 'required|string|max:255',
        'task_type' => 'required|string|exists:master_task_type,type_code',
        'planned_hours' => ['required', 'numeric', new MultipleOfQuarter],
        'actual_hours' => ['required', 'numeric', new MultipleOfQuarter],
        'remarks' => 'nullable|string',
        'display_order' => 'present',
    ]);


    if ($validator->fails()) {
        foreach ($validator->errors()->messages() as $field => $messages) {
            // $errors->put("tasks.$index.$field", $messages);
            $errors->merge(["tasks.$index.$field" => $messages]); // 正しい形式で追加
        }
    }

    return $validator->fails() ? null : $validator->validated();

});


// Nullを除外してバリデーション済みのタスクを取得
$validatedTasks = $validatedTasks->filter(function ($value) {
    return $value !== null; // null の値を除外
});


// エラーがある場合はリダイレクト
if ($errors->isNotEmpty()) {
    return redirect()->back()
        ->withErrors($errors)
        ->withInput();
}

     // レポート更新
     $report = Report::findOrFail($id);
     $report->tasks()->delete(); // 古いタスクを削除
     foreach ($validatedTasks as $task) {
         $report->tasks()->create($task); // 新しいタスクを登録
     }
     return redirect()->route('reports.index')->with('success', '日報を更新しました。');


}


    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
         // バリデーション
    $validated = $request->validate([
        'report_date' => 'required|date',
        'tasks' => 'required|array', // tasksは配列であることを想定
        'tasks.*.task_name' => 'required|string',
        'tasks.*.task_type' => 'required|string',
        'tasks.*.planned_hours' => 'required|integer',
        'tasks.*.actual_hours' => 'required|integer',
    ]);

    DB::transaction(function () use ($validated) {
        // Reportを作成
        $report = Report::create([
            'user_id' => auth()->id(),
            'report_date' => $validated['report_date'],
        ]);

        // Tasksを作成
        foreach ($validated['tasks'] as $task) {
            $report->tasks()->create($task);
        }
    });

        return redirect()->route('reports.index')->with('success', 'Report created successfully.');
    }

    public function show($id)
    {
        $report = Report::with('tasks.masterTaskType')->findOrFail($id);
        return view('reports.show', compact('report'));
    }


    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Report deleted successfully.');
    }
}
