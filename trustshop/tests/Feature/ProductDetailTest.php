<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * テスト仕様書 No.12〜16: 商品詳細・購入・編集・削除
 */
class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * No.12: 商品詳細画面に商品の情報が表示される
     */
    public function test_商品詳細画面に商品情報が表示される()
    {
        $user    = User::factory()->create();
        $shop    = Shop::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'name'    => '詳細テスト商品',
            'price'   => 1500,
            'stock'   => 10,
        ]);

        $response = $this->actingAs($user)
            ->get(route('shops.products.show', [$shop, $product]));

        $response->assertStatus(200);
        $response->assertSee('詳細テスト商品');
        $response->assertSee('1,500');
        $response->assertSee('10');
    }

    /**
     * No.13: 在庫がある商品を購入すると在庫が1つ減る
     */
    public function test_在庫がある商品を購入すると在庫が1つ減る()
    {
        $user    = User::factory()->create();
        $shop    = Shop::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'stock'   => 5,
        ]);

        $this->actingAs($user)
            ->post(route('shops.products.buy', [$shop, $product]));

        // 在庫が4に減っていること
        $this->assertDatabaseHas('products', [
            'id'    => $product->id,
            'stock' => 4,
        ]);
    }

    /**
     * No.14: 商品詳細画面からホーム画面に遷移できる
     * - 商品詳細画面にホームへのリンクが表示されていること
     */
    public function test_商品詳細画面にホームへのリンクが表示されている()
    {
        $user    = User::factory()->create();
        $shop    = Shop::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['shop_id' => $shop->id]);

        $response = $this->actingAs($user)
            ->get(route('shops.products.show', [$shop, $product]));

        $response->assertStatus(200);
        // ホームへのリンクが含まれていること
        $response->assertSee(route('home'));
    }

    /**
     * No.15: 自分の商品を編集できる
     * - 商品名・価格などを変更して送信するとDBが更新される
     */
    public function test_自分の商品を編集できる()
    {
        Storage::fake('public');

        $user    = User::factory()->create();
        $shop    = Shop::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'name'    => '変更前の商品名',
            'price'   => 1000,
            'stock'   => 5,
        ]);

        $response = $this->actingAs($user)
            ->put(route('shops.products.update', [$shop, $product]), [
                'name'        => '変更後の商品名',
                'description' => 'テスト説明',
                'price'       => 2000,
                'stock'       => 3,
            ]);

        // DBが更新されていること
        $this->assertDatabaseHas('products', [
            'id'    => $product->id,
            'name'  => '変更後の商品名',
            'price' => 2000,
            'stock' => 3,
        ]);

        $response->assertRedirect(route('shops.products.show', [$shop, $product]));
    }

    /**
     * No.16: 自分の商品を削除できる
     * - 削除ボタンを押すとDBから商品が削除される
     */
    public function test_自分の商品を削除できる()
    {
        $user    = User::factory()->create();
        $shop    = Shop::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['shop_id' => $shop->id]);

        $response = $this->actingAs($user)
            ->delete(route('shops.products.destroy', [$shop, $product]));

        // DBから削除されていること
        $this->assertDatabaseMissing('products', ['id' => $product->id]);

        $response->assertRedirect(route('shops.show', $shop));
    }
}
