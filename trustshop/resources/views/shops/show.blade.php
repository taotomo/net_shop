@extends('layouts.app')

{{-- このページ専用のCSSファイルを読み込む --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/shops-show.css') }}">
@endpush

@section('content')

    {{-- ショップ情報 --}}
    <div class="card shop-card">
        <div class="shop-info-header">
            <div>
                <h1 class="shop-name">{{ $shop->name }}</h1>
                <p class="shop-sub"></p>
            </div>

            {{-- 自分のショップのみ編集・削除・商品登録ボタンを表示 --}}
            @auth
                @if (Auth::id() === $shop->user_id)
                    <div class="action-bar">
                        <a href="{{ route('shops.products.create', $shop) }}" class="btn btn-primary">＋ 商品を登録</a>
                        <a href="{{ route('shops.edit', $shop) }}" class="btn btn-secondary">編集</a>
                        <form action="{{ route('shops.destroy', $shop) }}" method="POST"
                            onsubmit="return confirm('本当に削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">削除</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    {{-- 商品一覧 --}}
    <h2 class="section-title">商品一覧</h2>

    @if ($products->isEmpty())
        <div class="empty">まだ商品が登録されていません。</div>
    @else
        <div class="grid">
            @foreach ($products as $product)
                <a href="{{ route('shops.products.show', [$shop, $product]) }}" class="grid-item">
                    {{-- 商品画像があれば表示、なければ絵文字のダミー --}}
                    @if ($product->image)
                        <div class="product-thumb">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        </div>
                    @else
                        <div class="product-thumb--empty">
                            <span class="product-thumb__icon">🛍️</span>
                        </div>
                    @endif
                    <div class="grid-item__body">
                        <div class="grid-item__name">{{ $product->name }}</div>
                        <div class="grid-item__price">¥{{ number_format($product->price) }}</div>
                        @if ($product->stock <= 0)
                            <span class="badge-soldout">SOLD OUT</span>
                        @else
                        <div class="stock-text">在庫: {{ $product->stock }}</div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    <div class="back-link-area">
        <a href="{{ route('shops.index') }}" class="link">← ショップ一覧に戻る</a>
</div>

@endsection
