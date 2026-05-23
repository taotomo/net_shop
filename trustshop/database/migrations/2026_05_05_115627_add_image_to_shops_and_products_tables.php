<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageToShopsAndProductsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // shops テーブルに image 列を追加する
        Schema::table('shops', function (Blueprint $table) {
            // nullable = 画像なしでも登録できる
            $table->string('image')->nullable()->after('description');
        });

        // products テーブルに image 列を追加する
        Schema::table('products', function (Blueprint $table) {
            $table->string('image')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // shops テーブルから image 列を削除する
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        // products テーブルから image 列を削除する
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
}
