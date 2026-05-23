@extends('layouts.app')

{{-- このページ専用のCSSファイルを読み込む --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/products-my.css') }}">
@endpush

@section('content')

    <h1 class="page-title">出品商品一覧</h1>

    @if ($products->isEmpty())
        <div class="empty">まだ商品を出品していません。</div>
    @else
        <div class="grid">
            @foreach ($products as $product)
                <a href="{{ route('shops.products.show', [$product->shop, $product]) }}" class="grid-item">
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
                        {{-- ショップ名のサブテキスト --}}
                        <div class="my-product__shop">{{ $product->shop->name }}</div>
                        <div class="grid-item__name">{{ $product->name }}</div>
                        <div class="grid-item__price">¥{{ number_format($product->price) }}</div>
                        @if ($product->category)
                            <span class="product-category-badge">{{ $product->category }}</span>
                        @endif
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

@endsection
