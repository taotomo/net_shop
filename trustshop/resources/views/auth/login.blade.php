@extends('layouts.app')

{{-- このページ専用のCSSファイルを読み込む --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')

    <div class="form-wrapper--auth">
        <div class="card">
            <h1 class="page-title page-title--center">ログイン</h1>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>メールアドレス</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="example@mail.com"
                        class="@error('email') is-invalid @enderror">
                    @error('email')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>パスワード</label>
                    <input type="password" name="password" placeholder="••••••••"
                        class="@error('password') is-invalid @enderror">
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn--full">ログイン</button>
            </form>

            <p class="auth-note">
                アカウントをお持ちでない方は
                <a href="{{ route('register') }}" class="link">新規登録</a>
            </p>
        </div>
    </div>

@endsection
