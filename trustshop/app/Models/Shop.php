<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model 
{
    use HasFactory;

    //fillableは、モデルのどの属性が一括割り当て可能かを指定するためのプロパティ
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'image',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    //usersテーブルとのリレーションを定義
    public function user()
    {
        //このショップは1人のユーザーに属している 多数対1の関係
        return $this->belongsTo(User::class);
    }

    //productsテーブルとのリレーションを定義
    public function products()
    {
        //このショップは複数のプロダクトを持つことができる 1対多数の関係
        return $this->hasMany(Product::class);
    }
}
