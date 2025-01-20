<nav class="bg-white border-b border-gray-100 shadow py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <!-- ログイン情報 -->
        <div class="text-gray-600">
            <div class="font-medium text-lg">{{ Auth::user()->name }}</div>
            <div class="text-sm">{{ Auth::user()->email }}</div>
        </div>

        <!-- ログアウトリンク -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button 
                type="submit" 
                class="bg-red-500 text-blue px-4 py-2 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400">
                Log Out
            </button>
        </form>
    </div>
</nav>


