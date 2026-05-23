<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateShopRequest extends FormRequest
{
    /**
     * 自分のショップのみ更新できる
     */
    public function authorize()
    {
        // ルートパラメータから $shop を取得して所有者チェック
        return Auth::id() === $this->route('shop')->user_id;
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
