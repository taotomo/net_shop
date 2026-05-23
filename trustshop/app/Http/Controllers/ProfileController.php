<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * プロフィール編集フォームを表示する
     */
    public function edit()
    {
        // ログイン中のユーザー情報をビューに渡す
        return view('profile.edit', ['user' => Auth::user()]);
    }

    /**
     * プロフィール更新処理
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        // 新しいアバター画像が送られてきた場合は保存する
        if ($request->hasFile('avatar')) {
            // 画像あり → storage/app/public/avatars に保存してパスを取得する
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        } else {
            // 画像なし → 既存のアバターパスをそのまま使う
            $avatarPath = $user->avatar;
        }

        // DBを更新する
        $user->update([
            'name'   => $request->name,
            'avatar' => $avatarPath,
        ]);

        // プロフィール編集ページにリダイレクトする
        return redirect()->route('profile.edit');
    }
}
