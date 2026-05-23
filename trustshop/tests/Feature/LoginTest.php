<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * テスト仕様書 No.4〜7: ログイン
 */
class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * No.4: 正常にログインできる
     * - 登録済みのメールアドレスと正しいパスワードでログインできる
     */
    public function test_正常にログインできる()
    {
        $user = User::factory()->create([
            'email'    => 'login@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'login@example.com',
            'password' => 'password123',
        ]);

        // ログイン状態になっていること
        $this->assertAuthenticatedAs($user);

        // ショップ一覧にリダイレクトされること
        $response->assertRedirect(route('shops.index'));
    }

    /**
     * No.5: 未登録のメールアドレスでログインするとエラーになる
     */
    public function test_未登録のメールアドレスでログインするとエラーになる()
    {
        $response = $this->post('/login', [
            'email'    => 'notregistered@example.com',
            'password' => 'password123',
        ]);

        // エラーメッセージが返ること
        $response->assertSessionHasErrors('email');

        // ログインしていないこと
        $this->assertGuest();
    }

    /**
     * No.6: 間違ったパスワードでログインするとエラーになる
     */
    public function test_間違ったパスワードでログインするとエラーになる()
    {
        User::factory()->create([
            'email'    => 'login@example.com',
            'password' => Hash::make('correctpassword'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'login@example.com',
            'password' => 'wrongpassword',
        ]);

        // エラーメッセージが返ること
        $response->assertSessionHasErrors('email');

        // ログインしていないこと
        $this->assertGuest();
    }

    /**
     * No.7: メールアドレスを入力しないでログインするとエラーになる
     */
    public function test_メールアドレスを入力しないでログインするとエラーになる()
    {
        $response = $this->post('/login', [
            'email'    => '',
            'password' => 'password123',
        ]);

        // emailのバリデーションエラーが発生すること
        $response->assertSessionHasErrors('email');
    }
}
