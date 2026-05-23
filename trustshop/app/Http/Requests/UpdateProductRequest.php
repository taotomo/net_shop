<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProductRequest extends FormRequest
{
    /**
     * 自分のショップの商品のみ更新できる
     */
    public function authorize()
    {
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
            'price'       => 'required|integer|min:0',  // 必須・整数・0以上
            'stock'       => 'required|integer|min:0',  // 必須・整数・0以上
            'image'       => 'nullable|image|max:2048', // 任意・画像ファイル・2MB以内
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => '商品名を入力してください。',
            'name.max'       => '商品名は255文字以内で入力してください。',
            'price.required' => '価格を入力してください。',
            'price.integer'  => '価格は整数で入力してください。',
            'price.min'      => '価格は0以上で入力してください。',
            'stock.required' => '在庫数を入力してください。',
            'stock.integer'  => '在庫数は整数で入力してください。',
            'stock.min'      => '在庫数は0以上で入力してください。',
        ];
    }
}
