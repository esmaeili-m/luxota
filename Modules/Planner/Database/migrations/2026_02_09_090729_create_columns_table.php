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
        Schema::create('columns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('board_id');
            $table->string('title');
            $table->string('key')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(1);
            $table->integer('wip_limit')->nullable(); // برای Kanban
            $table->boolean('is_start')->default(false);
            $table->boolean('is_end')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // FK به Boards
            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
            $table->index(['board_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::dropIfExists('columns');
    }
};
