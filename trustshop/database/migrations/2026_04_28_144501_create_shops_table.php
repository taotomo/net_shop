<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            //foreignIDは他のテーブルと紐付けるための外部キーを追加
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            //stringは255文字までの文字列を保存するためのカラムを追加
            $table->string('name');
            //textは長い文字列を保存するためのカラムを追加
            $table->text('description')->nullable();
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
        Schema::dropIfExists('shops');
    }
}
