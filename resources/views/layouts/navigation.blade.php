<nav class="bg-white border-bottom shadow py-3">
    <div class="container d-flex justify-content-between align-items-center">
        
        <!-- ロゴ -->
        <div class="flex-shrink-0">
            <a href="/">
                <x-application-logo class="w-10 h-10" />
            </a>
        </div>
        
        <!-- ログイン情報 -->
        <div class="text-center text-md-start">
            <div class="fw-bold text-lg">{{ Auth::user()->name }}</div>
            <div class="text-muted">{{ Auth::user()->email }}</div>
        </div>
        
        <!-- ログアウトリンク -->
        <div class="flex-shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button 
                    type="submit" 
                    class="btn btn-danger">
                    Log Out
                </button>
            </form>
        </div>

    </div>
</nav>
