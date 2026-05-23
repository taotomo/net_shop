<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * テスト仕様書 No.19: 出品商品一覧
 */
class MyProductsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * No.19: 自分の出品商品が一覧で表示される
     * - 自分のショップの商品が表示され、他のユーザーの商品は表示されない
     */
    public function test_自分の出品商品が一覧で表示される()
    {
        // 自分のユーザー・ショップ・商品を作成する
        $myUser    = User::factory()->create();
        $myShop    = Shop::factory()->create(['user_id' => $myUser->id]);
        $myProduct = Product::factory()->create([
            'shop_id' => $myShop->id,
            'name'    => '自分の商品',
        ]);

        // 他のユーザーのショップ・商品を作成する
        $otherUser    = User::factory()->create();
        $otherShop    = Shop::factory()->create(['user_id' => $otherUser->id]);
        $otherProduct = Product::factory()->create([
            'shop_id' => $otherShop->id,
            'name'    => '他のユーザーの商品',
        ]);

        $response = $this->actingAs($myUser)->get(route('products.my'));

        $response->assertStatus(200);

        // 自分の商品が表示されていること
        $response->assertSee('自分の商品');

        // 他のユーザーの商品が表示されていないこと
        $response->assertDontSee('他のユーザーの商品');
    }
}
