<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * テスト仕様書 No.1〜3: 会員登録
 */
class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * No.1: 正常に会員登録できる
     * - 必要な入力をすべて入力して送信すると、DBに保存されログイン状態になる
     */
    public function test_正常に会員登録できる()
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // DBにユーザーが保存されていること
        $this->assertDatabaseHas('users', [
            'name'  => 'テストユーザー',
            'email' => 'test@example.com',
        ]);

        // ログイン状態になっていること
        $this->assertAuthenticated();

        // ショップ一覧にリダイレクトされること
        $response->assertRedirect(route('shops.index'));
    }

    /**
     * No.2: 登録済みのメールアドレスで会員登録するとエラーになる
     */
    public function test_登録済みメールアドレスで会員登録するとエラーになる()
    {
        // 既存ユーザーを作成する
        User::factory()->create(['email' => 'duplicate@example.com']);

        $response = $this->post('/register', [
            'name'                  => '新規ユーザー',
            'email'                 => 'duplicate@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // メールアドレスのバリデーションエラーが発生すること
        $response->assertSessionHasErrors('email');
    }

    /**
     * No.3: ニックネームを入力しないで会員登録するとエラーになる
     */
    public function test_ニックネームを入力しないで会員登録するとエラーになる()
    {
        $response = $this->post('/register', [
            'name'                  => '',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // nameのバリデーションエラーが発生すること
        $response->assertSessionHasErrors('name');
    }
}
