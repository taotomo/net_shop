<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * テスト仕様書 No.8〜9: プロフィール編集
 */
class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * No.8: ニックネームを空にしてプロフィールを更新するとエラーになる
     */
    public function test_ニックネームを空にしてプロフィールを更新するとエラーになる()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/profile', [
            'name' => '',
        ]);

        // nameのバリデーションエラーが発生すること
        $response->assertSessionHasErrors('name');
    }

    /**
     * No.9: 正常にプロフィールを更新できる
     * - ニックネームを変更して送信するとDBに保存される（avatarはnullableのため省略）
     */
    public function test_正常にプロフィールを更新できる()
    {
        $user = User::factory()->create(['name' => '旧ニックネーム']);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => '新ニックネーム',
        ]);

        // DBが更新されていること
        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'name' => '新ニックネーム',
        ]);

        // プロフィール編集ページにリダイレクトされること
        $response->assertRedirect(route('profile.edit'));
    }
}
