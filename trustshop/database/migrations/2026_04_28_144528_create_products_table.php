<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            //foreignIDは他のテーブルと紐付けるための外部キーを追加
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            //stringは255文字までの文字列を保存するためのカラムを追加
            $table->string('name');
            //textは長い文字列を保存するためのカラムを追加
            $table->text('description')->nullable();
            //integerは整数を保存するためのカラムを追加
            $table->integer('price');
            //defaultはカラムのデフォルト値を設定するためのメソッド
            $table->integer('stock')->default(0);
            //timestampsはcreated_atとupdated_atの2つのカラムを追加
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}

