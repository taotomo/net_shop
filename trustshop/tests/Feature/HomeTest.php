<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * テスト仕様書 No.10〜11: ホーム画面・カテゴリ検索
 */
class HomeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * No.10: ホーム画面にすべての商品が表示される
     */
    public function test_ホーム画面にすべての商品が表示される()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);

        Product::factory()->create(['shop_id' => $shop->id, 'name' => 'テスト商品A']);
        Product::factory()->create(['shop_id' => $shop->id, 'name' => 'テスト商品B']);

        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertSee('テスト商品A');
        $response->assertSee('テスト商品B');
    }

    /**
     * No.11: カテゴリで絞り込んで商品を表示できる
     * - 「電子機器」を選ぶと電子機器の商品だけ表示され、他カテゴリは表示されない
     */
    public function test_カテゴリで絞り込んで商品を表示できる()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);

        Product::factory()->create([
            'shop_id'  => $shop->id,
            'name'     => '電子機器の商品',
            'category' => '電子機器',
        ]);
        Product::factory()->create([
            'shop_id'  => $shop->id,
            'name'     => 'ファッションの商品',
            'category' => 'ファッション',
        ]);

        $response = $this->get('/home?category=電子機器');

        $response->assertStatus(200);
        $response->assertSee('電子機器の商品');
        $response->assertDontSee('ファッションの商品');
    }
}
