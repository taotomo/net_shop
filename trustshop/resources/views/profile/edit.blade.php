@extends('layouts.app')

{{-- このページ専用のCSSファイルを読み込む --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile-edit.css') }}">
@endpush

@section('content')

    <div class="form-wrapper">
        <div class="card">
            <h1 class="page-title">プロフィール編集</h1>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- アバター画像の表示・変更 --}}
                <div class="form-group">
                    <label>プロフィール画像（任意）</label>
                    @if ($user->avatar)
                        <div class="current-image">
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="現在の画像"
                                class="current-image__img">
                            <p class="current-image__label">現在の画像</p>
                        </div>
                    @endif
                    <input type="file" name="avatar" accept="image/*" id="avatar-input"
                        class="@error('avatar') is-invalid @enderror">
                    @error('avatar')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                    <img id="avatar-preview" src="" alt="" class="image-preview">
                </div>

                {{-- ニックネーム --}}
                <div class="form-group">
                    <label>ニックネーム</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="@error('name') is-invalid @enderror">
                    @error('name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('shops.index') }}" class="btn btn-secondary">キャンセル</a>
                    <button type="submit" class="btn btn-primary">変更する</button>
                </div>
            </form>
        </div>
    </div>

@endsection

<script>
// ファイル選択欄と、プレビュー用の <img> タグを取得する
const imageInput = document.getElementById('avatar-input');
const preview    = document.getElementById('avatar-preview');

// ファイルが選択されたときに実行する処理を登録する
imageInput.addEventListener('change', function () {
    const file = imageInput.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
});
</script>
