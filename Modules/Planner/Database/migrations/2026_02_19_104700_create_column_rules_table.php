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
        Schema::create('column_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('column_id');
            $table->boolean('track_time')->default(false);
            $table->integer('max_tasks_per_user')->nullable();
            $table->json('rules_json')->nullable();
            $table->softDeletes();

            $table->foreign('column_id')->references('id')->on('columns')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('column_rules');
    }
};
