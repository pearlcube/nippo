@extends('layouts.app')

@section('header')
<h1>日報一覧</h1>
@endsection

@section('content')
<div class="container">

    <!-- 成功メッセージ表示 -->
    {{-- @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif --}}
     <!-- 成功メッセージが存在する場合にトースト通知 -->
     @if (session('success'))
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             Swal.fire({
                 toast: true,
                 icon: 'success',
                 title: '{{ session('success') }}',
                 position: 'top-end',
                 showConfirmButton: false,
                 timer: 3000,
                 timerProgressBar: true,
             });
         });
     </script>
 @endif


    <!-- 検索フォーム -->
    <form method="GET" action="{{ route('reports.index') }}" class="mb-4">
        <!-- ユーザー選択 -->
        <div class="form-group">
            <label for="user_id">ユーザー:</label>
            <select name="user_id" id="user_id" class="form-control">
                <option value="default" {{ $userId === 'default' ? 'selected' : '' }}>ログインユーザー</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- 月選択 -->
        <div class="form-group">
            <label for="indexmonth">年月:</label>
            <input type="month" name="indexmonth" id="indexmonth" class="form-control" value="{{ $currentMonth }}">
            <small class="form-text text-muted">YYYY-MM形式で入力してください。</small>
        </div>

        <!-- 検索ボタン -->
        <button type="submit" class="btn btn-primary">検索</button>
    </form>

    <!-- レポート一覧表示 -->
    @if ($reports->isEmpty())
        <p>該当する日報はありません。</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>作成者</th>
                    <th>タスク数</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <td>{{ $report->report_date }}</td>
                        <td>{{ $report->user->name ?? '不明' }}</td>
                        <td>{{ $report->tasks->count() }}</td>
                        <td>
                            <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-info btn-sm">編集</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
