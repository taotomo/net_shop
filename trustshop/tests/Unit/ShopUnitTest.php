<?php

namespace Tests\Unit;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * ショップ CRUD 単体テスト
 */
class ShopUnitTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────
    // Read（取得）
    // ─────────────────────────────────────────────

    /**
     * ショップ一覧が表示される
     */
    public function test_ショップ一覧が表示される()
    {
        $user = User::factory()->create();
        Shop::factory()->create(['user_id' => $user->id, 'name' => 'テストショップ']);

        $response = $this->get(route('shops.index'));

        $response->assertStatus(200);
        $response->assertSee('テストショップ');
    }

    /**
     * ショップ詳細が表示される
     */
    public function test_ショップ詳細が表示される()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id, 'name' => 'テストショップ']);

        $response = $this->get(route('shops.show', $shop));

        $response->assertStatus(200);
        $response->assertSee('テストショップ');
    }

    // ─────────────────────────────────────────────
    // Create（作成）
    // ─────────────────────────────────────────────

    /**
     * ログイン済みユーザーがショップを作成できる
     */
    public function test_ショップを作成できる()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('shops.store'), [
            'name'        => '新しいショップ',
            'description' => 'ショップの説明',
        ]);

        $this->assertDatabaseHas('shops', [
            'user_id' => $user->id,
            'name'    => '新しいショップ',
        ]);

        $response->assertRedirect(route('shops.index'));
    }

    /**
     * 画像付きでショップを作成できる
     */
    public function test_画像付きでショップを作成できる()
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('shops.store'), [
            'name'        => '画像付きショップ',
            'description' => '説明',
            'image'       => UploadedFile::fake()->create('shop.jpg', 100, 'image/jpeg'),
        ]);

        $this->assertDatabaseHas('shops', [
            'user_id' => $user->id,
            'name'    => '画像付きショップ',
        ]);
    }

    /**
     * ショップ名が空の場合バリデーションエラーになる
     */
    public function test_ショップ名が空の場合バリデーションエラーになる()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('shops.store'), [
            'name'        => '',
            'description' => '説明',
        ]);

        $response->assertSessionHasErrors('name');
    }

    // ─────────────────────────────────────────────
    // Update（更新）
    // ─────────────────────────────────────────────

    /**
     * 自分のショップを更新できる
     */
    public function test_自分のショップを更新できる()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id, 'name' => '旧名前']);

        $response = $this->actingAs($user)->put(route('shops.update', $shop), [
            'name'        => '新名前',
            'description' => '新説明',
        ]);

        $this->assertDatabaseHas('shops', ['id' => $shop->id, 'name' => '新名前']);
        $response->assertRedirect(route('shops.show', $shop));
    }

    /**
     * 他人のショップは更新できない（403）
     */
    public function test_他人のショップは更新できない()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $shop  = Shop::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($other)->put(route('shops.update', $shop), [
            'name'        => '書き換え',
            'description' => '説明',
        ]);

        $response->assertStatus(403);
    }

    // ─────────────────────────────────────────────
    // Delete（削除）
    // ─────────────────────────────────────────────

    /**
     * 自分のショップを削除できる
     */
    public function test_自分のショップを削除できる()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('shops.destroy', $shop));

        $this->assertDatabaseMissing('shops', ['id' => $shop->id]);
        $response->assertRedirect(route('shops.index'));
    }

    /**
     * 他人のショップは削除できない（403）
     */
    public function test_他人のショップは削除できない()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $shop  = Shop::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($other)->delete(route('shops.destroy', $shop));

        $response->assertStatus(403);
        $this->assertDatabaseHas('shops', ['id' => $shop->id]);
    }
}
