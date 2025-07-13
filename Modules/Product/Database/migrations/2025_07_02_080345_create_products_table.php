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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description');
            $table->bigInteger('product_code')->default(10);
            $table->dateTime('last_version_update_date');
            $table->float('version')->nullable();
            $table->text('image')->nullable();
            $table->text('video_script')->nullable();
            $table->string('slug');
            $table->integer('order')->default(1);
            $table->boolean('show_price')->default(1);
            $table->boolean('payment_type')->default(1);
            $table->boolean('status')->default(1);
            $table->foreignId('category_id')
                ->constrained()
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        Schema::dropIfExists('products');
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

    }
};
