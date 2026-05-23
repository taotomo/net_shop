<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'description',
        'category',
        'price',
        'stock',
        'image',
    ];

    protected $casts = [
        'shop_id' => 'integer',
        'price'   => 'integer',
        'stock'   => 'integer',
    ];

    // 選択できるカテゴリの一覧
    public const CATEGORIES = [
        'ファッション',
        '電子機器',
        'スポーツ',
        'インテリア',
        '食品',
        'ゲーム',
        '本',
        'その他',
    ];

    //shopsテーブルとのリレーションを定義
    public function shop()
    {
        //この商品は1つのショップに属している 多数対1の関係
        return $this->belongsTo(Shop::class);
    }
}
