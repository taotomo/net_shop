<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // ログイン済みユーザーのみ許可する
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // ニックネームは必須・文字列・最大255文字
            'name'   => ['required', 'string', 'max:255'],
            // アバター画像は任意・画像ファイルのみ・最大2MB
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
