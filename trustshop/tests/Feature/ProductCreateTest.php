<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * テスト仕様書 No.17〜18: 商品出品
 */
class ProductCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * No.17: 正常に商品を出品できる
     * - 必要な情報を入力して出品するとDBに商品が保存される
     */
    public function test_正常に商品を出品できる()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->post(route('shops.products.store', $shop), [
                'name'        => '新商品テスト',
                'description' => '商品の説明文です',
                'category'    => 'その他',
                'price'       => 3000,
                'stock'       => 10,
            ]);

        // DBに商品が保存されていること
        $this->assertDatabaseHas('products', [
            'shop_id' => $shop->id,
            'name'    => '新商品テスト',
            'price'   => 3000,
            'stock'   => 10,
        ]);

        $response->assertRedirect(route('shops.show', $shop));
    }

    /**
     * No.18: 商品名を入力しないで出品するとエラーになる
     */
    public function test_商品名を入力しないで出品するとエラーになる()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->post(route('shops.products.store', $shop), [
                'name'  => '',
                'price' => 3000,
                'stock' => 10,
            ]);

        // nameのバリデーションエラーが発生すること
        $response->assertSessionHasErrors('name');
    }
}
