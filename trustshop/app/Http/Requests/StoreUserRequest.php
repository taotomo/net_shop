<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * このリクエストを実行する権限があるか
     * trueにすると全員が実行できる（登録フォームは誰でも使える）
     */
    public function authorize()
    {
        return true;
    }

    /**
     * バリデーションルールを定義する
     */
    public function rules()
    {
        return [
            'name'     => 'required|string|max:255',            // 必須・文字列・255文字以内
            'email'    => 'required|email|unique:users|max:255', // 必須・メール形式・重複NG
            'password' => 'required|min:8|confirmed',            // 必須・8文字以上・確認欄と一致
        ];
    }

    public function messages()
    {
        return [
            'name.required'      => '名前を入力してください。',
            'name.max'           => '名前は255文字以内で入力してください。',
            'email.required'     => 'メールアドレスを入力してください。',
            'email.email'        => 'メールアドレスの形式が正しくありません。',
            'email.unique'       => 'このメールアドレスはすでに登録されています。',
            'email.max'          => 'メールアドレスは255文字以内で入力してください。',
            'password.required'  => 'パスワードを入力してください。',
            'password.min'       => 'パスワードは8文字以上で入力してください。',
            'password.confirmed' => 'パスワードが一致しません。',
        ];
    }
}
