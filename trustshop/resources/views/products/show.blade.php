@extends('layouts.app')

{{-- このページ専用のCSSファイルを読み込む --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/products-show.css') }}">
@endpush

@section('content')

    <div class="product-wrapper">
        <div class="card">
            {{-- 商品画像があれば表示、なければダミー --}}
            @if ($product->image)
                <div class="product-detail__image">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                </div>
            @else
                <div class="product-detail__image--empty">
                    <span class="product-detail__icon">🛍️</span>
                </div>
            @endif

            {{-- 商品名・説明 --}}
            <h1 class="product-detail__name">{{ $product->name }}</h1>
            <p class="product-detail__price">¥{{ number_format($product->price) }}</p>

            @if ($product->stock <= 0)
                <span class="badge-soldout">SOLD OUT</span>
            @else
                <span class="product-detail__stock">在庫: {{ $product->stock }}点</span>
            @endif

            <p class="product-detail__desc">{{ $product->description ?? '説明なし' }}</p>

            {{-- 購入ボタン --}}
            @if ($product->stock <= 0)
                <div class="product-detail__buy-form">
                    <button class="btn product-detail__buy-btn" disabled>SOLD OUT</button>
                </div>
            @else
                <form action="{{ route('shops.products.buy', [$shop, $product]) }}" method="POST"
                    class="product-detail__buy-form"
                    onsubmit="return confirm('購入しますか？')">
                    @csrf
                    <button type="submit" class="btn btn-primary product-detail__buy-btn">購入する</button>
                </form>
            @endif

            {{-- オーナー用：編集・削除 --}}
            @auth
                @if (Auth::id() === $shop->user_id)
                    <div class="action-bar product-detail__actions">
                        <a href="{{ route('shops.products.edit', [$shop, $product]) }}" class="btn btn-secondary">編集</a>
                        <form action="{{ route('shops.products.destroy', [$shop, $product]) }}" method="POST"
                            onsubmit="return confirm('本当に削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">削除</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>

        <div class="back-link-area">
            <a href="{{ route('shops.show', $shop) }}" class="link">← {{ $shop->name }} に戻る</a>
            <a href="{{ route('home') }}" class="link">← ホームに戻る</a>
        </div>
    </div>

@endsection
