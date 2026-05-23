@extends('layouts.app')

{{-- このページ専用のCSSファイルを読み込む --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/shops-index.css') }}">
@endpush

@section('content')

    <h1 class="page-title">ショップ一覧</h1>

    @if ($shops->isEmpty())
        <div class="empty">まだショップが登録されていません。</div>
    @else
        <div class="grid">
            @foreach ($shops as $shop)
                <a href="{{ route('shops.show', $shop) }}" class="grid-item">
                    {{-- ショップアイコン代わりの色ブロック --}}
                    <div class="shop-thumb">
                        <span class="shop-thumb__icon">🏣</span>
                    </div>
                    <div class="grid-item__body">
                        <div class="grid-item__name">{{ $shop->name }}</div>
                        <div class="grid-item__desc">{{ $shop->description ?? '説明なし' }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

@endsection
