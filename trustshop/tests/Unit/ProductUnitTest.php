<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * 商品 CRUD 単体テスト
 */
class ProductUnitTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────
    // Read（取得）
    // ─────────────────────────────────────────────

    /**
     * 商品詳細が表示される
     */
    public function test_商品詳細が表示される()
    {
        $user    = User::factory()->create();
        $shop    = Shop::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['shop_id' => $shop->id, 'name' => 'テスト商品']);

        $response = $this->get(route('shops.products.show', [$shop, $product]));

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
    }

    // ─────────────────────────────────────────────
    // Create（作成）
    // ─────────────────────────────────────────────

    /**
     * 商品を作成できる
     */
    public function test_商品を作成できる()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('shops.products.store', $shop), [
            'name'        => 'テスト商品',
            'description' => '商品の説明',
            'category'    => 'その他',
            'price'       => 1000,
            'stock'       => 5,
        ]);

        $this->assertDatabaseHas('products', [
            'shop_id' => $shop->id,
            'name'    => 'テスト商品',
            'price'   => 1000,
        ]);
        $response->assertRedirect(route('shops.show', $shop));
    }

    /**
     * 画像付きで商品を作成できる
     */
    public function test_画像付きで商品を作成できる()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->post(route('shops.products.store', $shop), [
            'name'        => '画像付き商品',
            'description' => '説明',
            'category'    => 'その他',
            'price'       => 2000,
            'stock'       => 3,
            'image'       => UploadedFile::fake()->create('product.jpg', 100, 'image/jpeg'),
        ]);

        $product = Product::where('name', '画像付き商品')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);
    }

    /**
     * 商品名が空の場合バリデーションエラーになる
     */
    public function test_商品名が空の場合バリデーションエラーになる()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('shops.products.store', $shop), [
            'name'        => '',
            'description' => '説明',
            'category'    => 'その他',
            'price'       => 1000,
            'stock'       => 5,
        ]);

        $response->assertSessionHasErrors('name');
    }

    // ─────────────────────────────────────────────
    // Update（更新）
    // ─────────────────────────────────────────────

    /**
     * 自分のショップの商品を更新できる
     */
    public function test_自分のショップの商品を更新できる()
    {
        $user    = User::factory()->create();
        $shop    = Shop::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['shop_id' => $shop->id, 'name' => '旧商品名']);

        $response = $this->actingAs($user)->put(route('shops.products.update', [$shop, $product]), [
            'name'        => '新商品名',
            'description' => '新説明',
            'category'    => 'その他',
            'price'       => 2000,
            'stock'       => 3,
        ]);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => '新商品名']);
        $response->assertRedirect(route('shops.products.show', [$shop, $product]));
    }

    /**
     * 他人のショップの商品は更新できない（403）
     */
    public function test_他人のショップの商品は更新できない()
    {
        $owner   = User::factory()->create();
        $other   = User::factory()->create();
        $shop    = Shop::factory()->create(['user_id' => $owner->id]);
        $product = Product::factory()->create(['shop_id' => $shop->id]);

        $response = $this->actingAs($other)->put(route('shops.products.update', [$shop, $product]), [
            'name'        => '書き換え',
            'description' => '説明',
            'category'    => 'その他',
            'price'       => 1000,
            'stock'       => 1,
        ]);

        $response->assertStatus(403);
    }

    // ─────────────────────────────────────────────
    // Delete（削除）
    // ─────────────────────────────────────────────

    /**
     * 自分のショップの商品を削除できる
     */
    public function test_自分のショップの商品を削除できる()
    {
        $user    = User::factory()->create();
        $shop    = Shop::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['shop_id' => $shop->id]);

        $response = $this->actingAs($user)->delete(route('shops.products.destroy', [$shop, $product]));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $response->assertRedirect(route('shops.show', $shop));
    }

    /**
     * 他人のショップの商品は削除できない（403）
     */
    public function test_他人のショップの商品は削除できない()
    {
        $owner   = User::factory()->create();
        $other   = User::factory()->create();
        $shop    = Shop::factory()->create(['user_id' => $owner->id]);
        $product = Product::factory()->create(['shop_id' => $shop->id]);

        $response = $this->actingAs($other)->delete(route('shops.products.destroy', [$shop, $product]));

        $response->assertStatus(403);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    // ─────────────────────────────────────────────
    // 購入機能
    // ─────────────────────────────────────────────

    /**
     * 在庫がある商品を購入すると在庫が1つ減る
     */
    public function test_商品を購入すると在庫が減る()
    {
        $user    = User::factory()->create();
        $shop    = Shop::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['shop_id' => $shop->id, 'stock' => 5]);

        $this->actingAs($user)->post(route('shops.products.buy', [$shop, $product]));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 4]);
    }

    /**
     * 在庫が0の商品は購入できない
     */
    public function test_在庫が0の商品は購入できない()
    {
        $user    = User::factory()->create();
        $shop    = Shop::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['shop_id' => $shop->id, 'stock' => 0]);

        $response = $this->actingAs($user)->post(route('shops.products.buy', [$shop, $product]));

        $response->assertSessionHasErrors('stock');
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 0]);
    }
}
