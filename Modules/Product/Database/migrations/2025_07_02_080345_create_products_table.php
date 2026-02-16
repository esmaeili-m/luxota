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

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedBigInteger('product_code')->default(10);

            $table->timestamp('last_version_update_date')->useCurrent();

            $table->float('version')->nullable();
            $table->text('image')->nullable();
            $table->text('video_script')->nullable();

            $table->string('slug');
            $table->integer('order')->default(1);

            $table->boolean('show_price')->default(true);
            $table->boolean('payment_type')->default(true);
            $table->boolean('status')->default(true);

            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('products');

    }
};
