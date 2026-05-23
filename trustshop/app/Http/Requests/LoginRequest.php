<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * ログインフォームは誰でも実行できる
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
            'email'    => 'required|email', // 必須・メール形式
            'password' => 'required',       // 必須
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => 'メールアドレスを入力してください。',
            'email.email'       => 'メールアドレスの形式が正しくありません。',
            'password.required' => 'パスワードを入力してください。',
        ];
    }
}
