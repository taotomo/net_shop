@extends('layouts.app')

{{-- このページ専用のCSSファイルを読み込む --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/shops-edit.css') }}">
@endpush

@section('content')

    <div class="form-wrapper">
        <div class="card">
            <h1 class="page-title">ショップ編集</h1>

            <form action="{{ route('shops.update', $shop) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>ショップ名</label>
                    <input type="text" name="name" value="{{ old('name', $shop->name) }}"
                        class="@error('name') is-invalid @enderror">
                    @error('name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>説明</label>
                    <textarea name="description"
                        class="@error('description') is-invalid @enderror">{{ old('description', $shop->description) }}</textarea>
                    @error('description')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>ショップ画像（任意）</label>
                    {{-- 登録済み画像があれば現在の画像を表示する --}}
                    @if ($shop->image)
                        <div class="current-image">
                            <img src="{{ asset('storage/' . $shop->image) }}" alt="現在の画像"
                                class="current-image__img">
                            <p class="current-image__label">現在の画像</p>
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" id="shop-image-input"
                        class="@error('image') is-invalid @enderror">
                    @error('image') <p class="field-error">{{ $message }}</p> @enderror
                    <img id="shop-image-preview" src="" alt="" class="image-preview">
                </div>

                <div class="form-actions">
                    <a href="{{ route('shops.show', $shop) }}" class="btn btn-secondary">キャンセル</a>
                    <button type="submit" class="btn btn-primary">更新する</button>
                </div>
            </form>
        </div>
    </div>

@endsection

<script>
// ファイル選択欄と、プレビュー用の <img> タグを取得する
const imageInput = document.getElementById('shop-image-input');
const preview    = document.getElementById('shop-image-preview');

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
