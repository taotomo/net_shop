@extends('layouts.app')

{{-- このページ専用のCSSファイルを読み込む --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')

    <h1 class="page-title">商品一覧</h1>

    {{-- カテゴリ絞り込みフォーム --}}
    <form action="{{ route('home') }}" method="GET" class="category-filter">
        <div class="category-filter__list">
            {{-- 「すべて」ボタン --}}
            <a href="{{ route('home') }}"
                class="category-btn {{ is_null($category) ? 'category-btn--active' : '' }}">
                すべて
            </a>
            {{-- 各カテゴリボタン --}}
            @foreach ($categories as $cat)
                <a href="{{ route('home', ['category' => $cat]) }}"
                    class="category-btn {{ $category === $cat ? 'category-btn--active' : '' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </form>

    {{-- 商品グリッド --}}
    @if ($products->isEmpty())
        <div class="empty">
            @if ($category)
                「{{ $category }}」の商品はまだありません。
            @else
                まだ商品が登録されていません。
            @endif
        </div>
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
                        <div class="grid-item__name">{{ $product->name }}</div>
                        <div class="grid-item__price">¥{{ number_format($product->price) }}</div>
                        @if ($product->category)
                            <span class="product-category-badge">{{ $product->category }}</span>
                        @endif
                        @if ($product->stock <= 0)
                            <span class="badge-soldout">SOLD OUT</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif

@endsection
