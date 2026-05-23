@extends('layouts.app')

{{-- このページ専用のCSSファイルを読み込む --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('content')

    <div class="form-wrapper--auth">
        <div class="card">
            <h1 class="page-title page-title--center">新規会員登録</h1>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>名前</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="例）山田 太郎"
                        class="@error('name') is-invalid @enderror">
                    @error('name')
                        <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label>メールアドレス</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="example@mail.com"
                        class="@error('email') is-invalid @enderror">
                    @error('email')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>パスワード（8文字以上）</label>
                    <input type="password" name="password" placeholder="••••••••"
                        class="@error('password') is-invalid @enderror">
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>パスワード（確認）</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-primary btn--full">登録する</button>
            </form>

            <p class="auth-note">
                すでにアカウントをお持ちの方は
                <a href="{{ route('login') }}" class="link">こちら</a>
            </p>
        </div>
    </div>

@endsection
