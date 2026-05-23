<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * 商品一覧を表示する（ショップ詳細画面で使用）
     */
    public function index(Shop $shop)
    {
        // そのショップに紐付いている商品を取得する
        $products = $shop->products;

        // resources/views/products/index.blade.php を表示する
        return view('products.index', compact('shop', 'products'));
    }

    /**
     * 商品登録フォームを表示する
     */
    public function create(Shop $shop)
    {
        // 自分のショップ以外には商品を登録できないようにする
        if (Auth::id() !== $shop->user_id) {
            abort(403);
        }

        // resources/views/products/create.blade.php を表示する
        return view('products.create', compact('shop'));
    }

    /**
     * 商品登録処理
     */
    public function store(StoreProductRequest $request, Shop $shop)
    {
        // 画像が送られてきた場合は storage/app/public/products に保存する
        if ($request->hasFile('image')) {
            // 画像あり → storage に保存してパスを取得する
            $imagePath = $request->file('image')->store('products', 'public');
        } else {
            // 画像なし → null をセットする
            $imagePath = null;
        }

        // そのショップの商品として保存する
        $shop->products()->create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'image'       => $imagePath,
        ]);

        // ショップ詳細ページにリダイレクトする
        return redirect()->route('shops.show', $shop);
    }

    /**
     * 商品詳細を表示する（購入ボタンもここに表示）
     */
    public function show(Shop $shop, Product $product)
    {
        // resources/views/products/show.blade.php を表示する
        return view('products.show', compact('shop', 'product'));
    }

    /**
     * 商品編集フォームを表示する
     */
    public function edit(Shop $shop, Product $product)
    {
        // 自分のショップの商品以外は編集できないようにする
        if (Auth::id() !== $shop->user_id) {
            abort(403);
        }

        // resources/views/products/edit.blade.php を表示する
        return view('products.edit', compact('shop', 'product'));
    }

    /**
     * 商品更新処理
     */
    public function update(UpdateProductRequest $request, Shop $shop, Product $product)
    {
        // 新しい画像が送られてきた場合は保存する
        if ($request->hasFile('image')) {
            // 新しい画像あり → storage に保存してパスを取得する
            $imagePath = $request->file('image')->store('products', 'public');
        } else {
            // 新しい画像なし → 既存の画像パスをそのまま使う
            $imagePath = $product->image;
        }

        // DBを更新する
        $product->update([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'image'       => $imagePath,
        ]);

        // 商品詳細ページにリダイレクトする
        return redirect()->route('shops.products.show', [$shop, $product]);
    }

    /**
     * 商品削除処理
     */
    public function destroy(Shop $shop, Product $product)
    {
        // 自分のショップの商品以外は削除できないようにする
        if (Auth::id() !== $shop->user_id) {
            abort(403);
        }

        // DBから削除する
        $product->delete();

        // ショップ詳細ページにリダイレクトする
        return redirect()->route('shops.show', $shop);
    }

    /**
     * 商品購入処理（在庫を1つ減らす）
     */
    public function buy(Shop $shop, Product $product)
    {
        // 在庫が0の場合は購入できない
        if ($product->stock <= 0) {
            return back()->withErrors(['stock' => '在庫がありません。']);
        }

        // 在庫を1つ減らしてDBを更新する
        $product->decrement('stock');

        // 商品詳細ページに戻る
        return redirect()->route('shops.products.show', [$shop, $product]);
    }

    /**
     * 自分が出品した商品の一覧を表示する
     */
    public function myProducts()
    {
        // ログインユーザーの全ショップに紐付いている商品を取得する
        $products = Product::whereHas('shop', function ($q) {
            $q->where('user_id', Auth::id());
        })->with('shop')->get();

        return view('products.my', compact('products'));
    }
}
