<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * ホーム画面（全商品一覧）を表示する
     * カテゴリが指定されていれば絞り込んで表示する
     */
    public function index(Request $request)
    {
        // クエリパラメータからカテゴリを取得する（例: ?category=ファッション）
        $category = $request->input('category');

        // 商品クエリを組み立てる
        $query = Product::with('shop');

        if ($category) {
            // カテゴリが指定されていれば絞り込む
            $query->where('category', $category);
        }

        $products   = $query->get();
        $categories = Product::CATEGORIES;

        return view('home.index', compact('products', 'categories', 'category'));
    }
}
