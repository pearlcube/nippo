@extends('layouts.app')

@section('header')
    <h1>日報編集</h1>
@endsection

@section('content')
    <form action="{{ route('reports.update', $report->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- 日付コントロール -->
        <div class="form-group">
            <label for="report_date">日付</label>
            <input type="date" id="report_date" name="report_date" value="{{ $report->report_date }}" class="form-control" disabled>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->getMessages() as $key => $messages)
                <p>{{ $key }}</p> <!-- キーを直接出力 -->
                <ul>
                    @foreach ($messages as $message)
                        <li>{{ $message }}</li> <!-- メッセージを直接出力 -->
                    @endforeach
                </ul>
            @endforeach
        </div>
    @endif
    




        <!-- タスクテーブル -->
<table class="table">
    <thead>
        <tr>
            <th>タスク名</th>
            <th>種類</th>
            <th>予定工数</th>
            <th>実績工数</th>
            <th>備考</th>
        </tr>
    </thead>
    <tbody>
        @for ($i = 0; $i < 20; $i++)
            <tr>
                <td>
                    <input 
                        type="text" 
                        name="tasks[{{ $i }}][task_name]" 
                        class="form-control @error('tasks.' . $i . '.task_name') is-invalid @enderror" 
                        value="{{ old('tasks.' . $i . '.task_name', $report->tasks[$i]->task_name ?? '') }}">
                    @error('tasks.' . $i . '.task_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </td>
                <td>
                    <select 
                        name="tasks[{{ $i }}][task_type]" 
                        class="form-control @error('tasks.' . $i . '.task_type') is-invalid @enderror">
                        <option value="">--選択--</option>
                        @foreach ($masterTaskTypes as $type)
                            <option value="{{ $type->type_code }}" 
                                {{ old('tasks.' . $i . '.task_type', $report->tasks[$i]->task_type ?? '') == $type->type_code ? 'selected' : '' }}>
                                {{ $type->type_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('tasks.' . $i . '.task_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </td>
                <td>
                    <input 
                        type="number" 
                        step="0.25" 
                        min="0" 
                        max="99.99" 
                        name="tasks[{{ $i }}][planned_hours]" 
                        class="form-control @error('tasks.' . $i . '.planned_hours') is-invalid @enderror" 
                        value="{{ old('tasks.' . $i . '.planned_hours', $report->tasks[$i]->planned_hours ?? '') }}">
                    @error('tasks.' . $i . '.planned_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </td>
                <td>
                    <input 
                        type="number" 
                        step="0.25" 
                        min="0" 
                        max="99.99" 
                        name="tasks[{{ $i }}][actual_hours]" 
                        class="form-control @error('tasks.' . $i . '.actual_hours') is-invalid @enderror" 
                        value="{{ old('tasks.' . $i . '.actual_hours', $report->tasks[$i]->actual_hours ?? '') }}">
                    @error('tasks.' . $i . '.actual_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </td>
                <td>
                    <textarea 
                        name="tasks[{{ $i }}][remarks]" 
                        class="form-control @error('tasks.' . $i . '.remarks') is-invalid @enderror">{{ old('tasks.' . $i . '.remarks', $report->tasks[$i]->remarks ?? '') }}</textarea>
                    @error('tasks.' . $i . '.remarks')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </td>
                <!-- display_order を隠しフィールドとして追加 -->
                <input type="hidden" name="tasks[{{ $i }}][display_order]" value="{{ $i + 1 }}">
            </tr>
        @endfor
    </tbody>
</table>


        <!-- 保存ボタン -->
        <button type="submit" class="btn btn-primary">保存</button>
    </form>
@endsection
