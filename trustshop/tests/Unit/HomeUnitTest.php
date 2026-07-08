<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * 単体テスト仕様書 No.2: TOP画面（/home）
 */
class HomeUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * No.2-1: TOP画面が表示される
     */
    public function test_2_1_TOP画面が表示される()
    {
        $response = $this->get('/home');
        $response->assertStatus(200);
    }

    /**
     * No.2-2: 商品一覧が表示される
     */
    public function test_2_2_商品一覧が表示される()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);
        Product::factory()->create(['shop_id' => $shop->id, 'name' => 'テスト商品']);

        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
    }

    /**
     * No.2-3 & 2-4: カテゴリで絞り込みができ、該当カテゴリの商品のみ表示される
     */
    public function test_2_3_4_カテゴリで絞り込むと該当商品のみ表示される()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);
        Product::factory()->create(['shop_id' => $shop->id, 'name' => 'ファッション商品', 'category' => 'ファッション']);
        Product::factory()->create(['shop_id' => $shop->id, 'name' => 'ゲーム商品',       'category' => 'ゲーム']);

        $response = $this->get('/home?category=ファッション');

        $response->assertStatus(200);
        $response->assertSee('ファッション商品');
        $response->assertDontSee('ゲーム商品');
    }

    /**
     * No.2-5: 存在しないカテゴリで絞り込んだ場合、商品が表示されない
     */
    public function test_2_5_存在しないカテゴリで絞り込むと商品が表示されない()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);
        Product::factory()->create(['shop_id' => $shop->id, 'name' => 'テスト商品', 'category' => 'ファッション']);

        $response = $this->get('/home?category=存在しないカテゴリ');

        $response->assertStatus(200);
        $response->assertDontSee('テスト商品');
    }

    /**
     * No.2-6: 未ログインでもTOP画面にアクセスできる
     */
    public function test_2_6_未ログインでもTOP画面にアクセスできる()
    {
        $response = $this->get('/home');
        $response->assertStatus(200);
    }
}
