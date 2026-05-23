<?php

// ─────────────────────────────────────────────
// 使うコントローラーをここで読み、「どのコントローラーを使うか」を宣言している
// ─────────────────────────────────────────────
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController; // 会員登録
use App\Http\Controllers\Auth\LoginController;    // ログイン・ログアウト
use App\Http\Controllers\HomeController;          // ホーム画面
use App\Http\Controllers\ShopController;          // ショップ
use App\Http\Controllers\ProductController;       // 商品
use App\Http\Controllers\ProfileController;       // プロフィール

Route::get('/', function () {
    return view('welcome');
});


// ─────────────────────────────────────────────
// 会員登録・ログイン・ログアウト
// 誰でもアクセスできる（ログイン不要）
// ─────────────────────────────────────────────

// 会員登録フォームを表示する  GET  /register
Route::get('/register', [RegisterController::class, 'create'])->name('register');
// 会員登録フォームを送信する  POST /register
Route::post('/register', [RegisterController::class, 'store']);

// ログインフォームを表示する  GET  /login
Route::get('/login', [LoginController::class, 'create'])->name('login');
// ログインフォームを送信する  POST /login
Route::post('/login', [LoginController::class, 'store']);
// ログアウトする              POST /logout
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');


// ─────────────────────────────────────────────
// ホーム画面
// 全商品一覧・カテゴリ絞り込み
// 誰でもアクセスできる（ログイン不要）
// ─────────────────────────────────────────────

// ホーム画面を表示する  GET /home
Route::get('/home', [HomeController::class, 'index'])->name('home');


// ─────────────────────────────────────────────
// ショップ（CRUD）
// Route::resource が以下の7つのルートをまとめて作ってくれる
//   GET    /shops           → 一覧
//   GET    /shops/create    → 新規作成フォーム
//   POST   /shops           → 新規作成処理
//   GET    /shops/{shop}    → 詳細
//   GET    /shops/{shop}/edit → 編集フォーム
//   PUT    /shops/{shop}    → 更新処理
//   DELETE /shops/{shop}    → 削除処理
// ─────────────────────────────────────────────
Route::resource('shops', ShopController::class);


// ─────────────────────────────────────────────
// 商品（CRUD） ※ショップの中に商品がある
// Route::resource が以下の7つのルートをまとめて作ってくれる
//   GET    /shops/{shop}/products                → 一覧
//   GET    /shops/{shop}/products/create         → 新規作成フォーム
//   POST   /shops/{shop}/products                → 新規作成処理
//   GET    /shops/{shop}/products/{product}      → 詳細
//   GET    /shops/{shop}/products/{product}/edit → 編集フォーム
//   PUT    /shops/{shop}/products/{product}      → 更新処理
//   DELETE /shops/{shop}/products/{product}      → 削除処理
// ─────────────────────────────────────────────
Route::resource('shops.products', ProductController::class);

// 商品購入（在庫を1つ減らす）  POST /shops/{shop}/products/{product}/buy
Route::post('shops/{shop}/products/{product}/buy', [ProductController::class, 'buy'])->name('shops.products.buy');


// ─────────────────────────────────────────────
// ログインが必要なページ
// middleware('auth') → ログインしていないと /login にリダイレクトされる
// ─────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // プロフィール編集フォームを表示する  GET /profile/edit
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    // プロフィールを更新する              PUT /profile
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // 自分の出品商品一覧を表示する  GET /my-products
    Route::get('/my-products', [ProductController::class, 'myProducts'])->name('products.my');

});
