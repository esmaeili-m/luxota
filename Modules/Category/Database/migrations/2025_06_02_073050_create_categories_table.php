<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('subtitle');
            $table->string('slug')->unique();
            $table->bigInteger('category_code')->default(10);
            $table->text('image')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('order')->default(1);
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->softDeletes();
            $table->timestamps();
        });
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE categories AUTO_INCREMENT = 11');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('categories');
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

    }
};
