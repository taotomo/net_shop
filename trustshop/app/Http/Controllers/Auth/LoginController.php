<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * ログインフォームを表示する
     */
    public function create()
    {
        // resources/views/auth/login.blade.php を表示する
        return view('auth.login');
    }

    /**
     * ログイン処理
     */
    public function store(LoginRequest $request)
    {
        // LoginRequest でバリデーション済みのため、ここでは処理のみ書く

        // メールとパスワードが一致するか確認してログインする
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // ログイン成功 → セッションを再生成してセキュリティを高める
            $request->session()->regenerate();

            // ショップ一覧ページにリダイレクトする
            return redirect()->route('shops.index');
        }

        // ログイン失敗 → エラーメッセージとともにフォームに戻る
        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが間違っています。',
        ]);
    }

    /**
     * ログアウト処理
     */
    public function destroy(Request $request)
    {
        // ログアウトする
        Auth::logout();

        // セッションを削除してセキュリティを高める
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ログインページにリダイレクトする
        return redirect()->route('login');
    }
}
