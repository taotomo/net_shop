<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreShopRequest extends FormRequest
{
    /**
     * ログイン済みのユーザーのみ実行できる
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * バリデーションルールを定義する
     */
    public function rules()
    {
        return [
            'name'        => 'required|string|max:255', // 必須・文字列・255文字以内
            'description' => 'nullable|string',         // 任意・文字列
            'image'       => 'nullable|image|max:2048', // 任意・画像ファイル・2MB以内
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ショップ名を入力してください。',
            'name.max'      => 'ショップ名は255文字以内で入力してください。',
            'image.image'   => '画像ファイルを選択してください。',
            'image.max'     => '画像は2MB以内にしてください。',
        ];
    }
}
