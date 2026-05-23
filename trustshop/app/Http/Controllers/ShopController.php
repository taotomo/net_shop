<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * ショップ一覧を表示する（トップページ）
     */
    public function index()
    {
        // すべてのショップをDBから取得する
        $shops = Shop::all();

        // resources/views/shops/index.blade.php を表示する
        // $shops というデータをビューに渡す
        return view('shops.index', compact('shops'));
    }

    /**
     * ショップ登録フォームを表示する
     */
    public function create()
    {
        // resources/views/shops/create.blade.php を表示する
        return view('shops.create');
    }

    /**
     * ショップ登録処理
     */
    public function store(StoreShopRequest $request)
    {
        // 画像が送られてきた場合は storage/app/public/shops に保存する
        if ($request->hasFile('image')) {
            // 画像あり → storage に保存してパスを取得する
            $imagePath = $request->file('image')->store('shops', 'public');
        } else {
            // 画像なし → null をセットする
            $imagePath = null;
        }

        // ログイン中のユーザーのショップとして保存する
        Auth::user()->shops()->create([
            'name'        => $request->name,
            'description' => $request->description,
            'image'       => $imagePath,
        ]);

        // ショップ一覧ページにリダイレクトする
        return redirect()->route('shops.index');
    }

    /**
     * ショップ詳細を表示する
     */
    public function show(Shop $shop)
    {
        // そのショップに紐付いている商品も一緒に取得する
        $products = $shop->products;

        // resources/views/shops/show.blade.php を表示する
        return view('shops.show', compact('shop', 'products'));
    }

    /**
     * ショップ編集フォームを表示する
     */
    public function edit(Shop $shop)
    {
        // 自分のショップ以外は編集できないようにする
        if (Auth::id() !== $shop->user_id) {
            abort(403); // 権限なしエラーを返す
        }

        // resources/views/shops/edit.blade.php を表示する
        return view('shops.edit', compact('shop'));
    }

    /**
     * ショップ更新処理
     */
    public function update(UpdateShopRequest $request, Shop $shop)
    {
        // 新しい画像が送られてきた場合は保存する
        if ($request->hasFile('image')) {
            // 新しい画像あり → storage に保存してパスを取得する
            $imagePath = $request->file('image')->store('shops', 'public');
        } else {
            // 新しい画像なし → 既存の画像パスをそのまま使う
            $imagePath = $shop->image;
        }

        // DBを更新する
        $shop->update([
            'name'        => $request->name,
            'description' => $request->description,
            'image'       => $imagePath,
        ]);

        // ショップ詳細ページにリダイレクトする
        return redirect()->route('shops.show', $shop);
    }

    /**
     * ショップ削除処理
     */
    public function destroy(Shop $shop)
    {
        // 自分のショップ以外は削除できないようにする
        if (Auth::id() !== $shop->user_id) {
            abort(403);
        }

        // DBから削除する
        $shop->delete();

        // ショップ一覧ページにリダイレクトする
        return redirect()->route('shops.index');
    }
}
