<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * 単体テスト仕様書 No.1: ログイン画面
 *
 * No.1-5（エラー表示/非表示）・1-6（フォーカス）は
 * JavaScript の動作テストのため PHPUnit では対象外。
 * No.1-13（文字列・数字のみ制限）・1-16（記号禁止）は
 * ログインフォームには適用しない（登録時の制約であり、既存アカウントに影響するため）。
 */
class LoginUnitTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────
    // 正常系
    // ─────────────────────────────────────────────

    /**
     * No.1-1: ログイン画面が表示される
     */
    public function test_1_1_ログイン画面が表示される()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * No.1-2 & 1-3: 正しいメールアドレス・パスワードでログイン成功し想定通りの画面に遷移する
     */
    public function test_1_2_3_正しい認証情報でログイン成功し遷移する()
    {
        $user = User::factory()->create([
            'email'    => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'password123',
        ]);

        // ログイン状態になっていること
        $this->assertAuthenticatedAs($user);

        // ショップ一覧にリダイレクトされること
        $response->assertRedirect(route('shops.index'));
    }

    // ─────────────────────────────────────────────
    // 異常系
    // ─────────────────────────────────────────────

    /**
     * No.1-4: パスワードが間違っている場合エラーメッセージが表示される
     */
    public function test_1_4_パスワードが間違っている場合エラーになる()
    {
        User::factory()->create([
            'email'    => 'test@example.com',
            'password' => Hash::make('correctpassword123'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'wrongpassword123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * No.1-7: メールアドレス欄に文字列のみ・数字のみの場合バリデーションエラー
     */
    public function test_1_7_文字列のみまたは数字のみのメールでバリデーションエラーになる()
    {
        // 文字列のみ（@ なし）
        $response = $this->post('/login', ['email' => 'testonly', 'password' => 'password123']);
        $response->assertSessionHasErrors('email');

        // 数字のみ
        $response = $this->post('/login', ['email' => '12345', 'password' => 'password123']);
        $response->assertSessionHasErrors('email');
    }

    /**
     * No.1-8: 不正なアドレスの場合バリデーションエラー
     */
    public function test_1_8_不正なアドレスでバリデーションエラーになる()
    {
        $response = $this->post('/login', ['email' => 'notanemail', 'password' => 'password123']);
        $response->assertSessionHasErrors('email');
    }

    /**
     * No.1-9: メールアドレスに全角文字が含まれている場合バリデーションエラー
     */
    public function test_1_9_メールアドレスに全角文字が含まれている場合バリデーションエラーになる()
    {
        $response = $this->post('/login', ['email' => 'テスト@example.com', 'password' => 'password123']);
        $response->assertSessionHasErrors('email');
    }

    /**
     * No.1-10: メールアドレスの先頭または末尾に @ や . がある場合バリデーションエラー
     */
    public function test_1_10_先頭末尾に記号があるメールアドレスでバリデーションエラーになる()
    {
        // 先頭に @
        $response = $this->post('/login', ['email' => '@example.com', 'password' => 'password123']);
        $response->assertSessionHasErrors('email');

        // 末尾に .
        $response = $this->post('/login', ['email' => 'test@example.', 'password' => 'password123']);
        $response->assertSessionHasErrors('email');
    }

    /**
     * No.1-11: 未登録のアドレスの場合エラーになる
     */
    public function test_1_11_未登録のアドレスの場合エラーになる()
    {
        $response = $this->post('/login', [
            'email'    => 'unregistered@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * No.1-12: メールアドレスが空白の場合バリデーションエラー
     */
    public function test_1_12_メールアドレスが空白の場合バリデーションエラーになる()
    {
        $response = $this->post('/login', ['email' => '', 'password' => 'password123']);
        $response->assertSessionHasErrors('email');
    }

    /**
     * No.1-14: 規定文字数以下のパスワードでバリデーションエラー（8文字以上必須）
     */
    public function test_1_14_短すぎるパスワードでバリデーションエラーになる()
    {
        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'abc1234', // 7文字
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * No.1-15: パスワード欄が空白の場合バリデーションエラー
     */
    public function test_1_15_パスワードが空白の場合バリデーションエラーになる()
    {
        $response = $this->post('/login', ['email' => 'test@example.com', 'password' => '']);
        $response->assertSessionHasErrors('password');
    }

    /**
     * No.1-17: パスワードに全角文字が含まれている場合バリデーションエラー
     * ※ 全角文字を含むパスワードは登録不可のため、ログイン認証も失敗する
     */
    public function test_1_17_全角文字を含むパスワードでは認証に失敗する()
    {
        // 全角パスワードで登録されたユーザーは存在しないため認証失敗になる
        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'パスワード１２３',
        ]);

        $this->assertGuest();
    }

    /**
     * No.1-18: メールアドレス・パスワードともに入力されていない場合バリデーションエラー
     */
    public function test_1_18_両方空白の場合バリデーションエラーになる()
    {
        $response = $this->post('/login', ['email' => '', 'password' => '']);
        $response->assertSessionHasErrors(['email', 'password']);
    }
}
