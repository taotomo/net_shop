@extends('layouts.app')

{{-- このページ専用のCSSファイルを読み込む --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/products-edit.css') }}">
@endpush

@section('content')

    <div class="form-wrapper">
        <div class="card">
            <h1 class="page-title">商品編集</h1>

            <form action="{{ route('shops.products.update', [$shop, $product]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>商品名</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}"
                        class="@error('name') is-invalid @enderror">
                    @error('name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>商品説明</label>
                    <textarea name="description"
                        class="@error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>カテゴリ（任意）</label>
                    <select name="category" class="@error('category') is-invalid @enderror">
                        <option value="">— 選択してください —</option>
                        @foreach (\App\Models\Product::CATEGORIES as $cat)
                            <option value="{{ $cat }}"
                                {{ old('category', $product->category) === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0"
                        class="@error('price') is-invalid @enderror">
                    @error('price')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>在庫数</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
                        class="@error('stock') is-invalid @enderror">
                    @error('stock')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>商品画像（任意）</label>
                    @if ($product->image)
                        <div class="current-image">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="現在の画像"
                                class="current-image__img">
                            <p class="current-image__label">現在の画像</p>
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" id="product-image-input"
                        class="@error('image') is-invalid @enderror">
                    @error('image') <p class="field-error">{{ $message }}</p> @enderror
                    <img id="product-image-preview" src="" alt="" class="image-preview">
                </div>

                <div class="form-actions">
                    <a href="{{ route('shops.products.show', [$shop, $product]) }}" class="btn btn-secondary">キャンセル</a>
                    <button type="submit" class="btn btn-primary">更新する</button>
                </div>
            </form>
        </div>
    </div>

@endsection

<script>
// ファイル選択欄と、プレビュー用の <img> タグを取得する
const imageInput = document.getElementById('product-image-input');
const preview    = document.getElementById('product-image-preview');

// ファイルが選択されたときに実行する処理を登録する
imageInput.addEventListener('change', function () {

    // 選ばれたファイルを取得する（1枚目）
    const file = imageInput.files[0];

    if (file) {
        // ファイルが選ばれていた場合

        // FileReader = ブラウザ内でファイルを読み込む機能
        const reader = new FileReader();

        // 読み込みが完了したときに実行する処理を定義する
        reader.onload = function (e) {
            // 読み込んだ画像データを <img> の src にセットする
            preview.src = e.target.result;
            // <img> を表示する（最初は display:none で非表示になっている）
            preview.style.display = 'block';
        };

        // ここで読み込みを開始する（終わると onload が自動で呼ばれる）
        reader.readAsDataURL(file);

    } else {
        // ファイル選択をキャンセルした場合

        // <img> の内容を空にして非表示に戻す
        preview.src = '';
        preview.style.display = 'none';
    }
});
</script>
