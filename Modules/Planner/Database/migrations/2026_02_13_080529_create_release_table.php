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
        Schema::create('release', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();

            $table->date('release_date')->nullable();
            $table->enum('status', ['planned', 'in_progress', 'released', 'cancelled'])
                ->default('planned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('release');
    }
};
