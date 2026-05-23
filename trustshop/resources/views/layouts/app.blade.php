<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustShop</title>
    {{-- 共通スタイル（全ページで使うクラス） --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- 各ページ専用のCSSをここに差し込む（@push('styles') で追加される） --}}
    @stack('styles')
</head>
<body>

    {{-- ヘッダー --}}
    <header class="header">
        <a href="{{ route('shops.index') }}" class="header__logo">TrustShop</a>
        <nav class="header__nav">
            @auth
                {{-- ログイン中のみ表示 --}}
                <a href="{{ route('home') }}">🏠 ホーム</a>
                <a href="{{ route('products.my') }}">🛒 出品商品</a>
                <a href="{{ route('profile.edit') }}">👤 プロフィール</a>
                <a href="{{ route('shops.create') }}" class="btn-outline">ショップ登録</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">ログアウト</button>
                </form>
            @else
                {{-- 未ログインのみ表示 --}}
                <a href="{{ route('home') }}">🏠 ホーム</a>
                <a href="{{ route('login') }}">🔑 ログイン</a>
                <a href="{{ route('register') }}" class="btn-outline">📝 新規登録</a>
            @endauth
        </nav>
    </header>

    <main class="main">

        {{-- バリデーションエラーがあれば表示 --}}
        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 各ページのコンテンツがここに流し込まれる --}}
        @yield('content')

    </main>

</body>
</html>
