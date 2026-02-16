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
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('key')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['kanban', 'scrum'])->default('kanban');
            $table->boolean('status')->default(true);
            $table->string('owner_type');
            $table->bigInteger('order')->default(1);
            $table->foreignId('parent_id')->nullable()->constrained('boards')->onDelete('cascade');
            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('visibility', ['private','team','public'])->default('team');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['owner_type','owner_id']);
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::dropIfExists('boards');
    }
};
