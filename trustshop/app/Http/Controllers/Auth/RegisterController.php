<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * ユーザー登録フォームを表示する
     */
    public function create()
    {
        // resources/views/auth/register.blade.php を表示する
        return view('auth.register');
    }

    /**
     * ユーザー登録処理
     */
    public function store(StoreUserRequest $request)
    {
        // StoreUserRequest でバリデーション済みのため、ここでは処理のみ書く

        // バリデーションを通過したらユーザーをDBに保存する
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // パスワードを暗号化して保存
        ]);

        // 登録後、自動的にログインさせる
        Auth::login($user);

        // ショップ一覧ページにリダイレクトする
        return redirect()->route('shops.index');
    }
}
