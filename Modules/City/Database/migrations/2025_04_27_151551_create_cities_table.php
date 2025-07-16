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
        Schema::create('cities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('country_id')->constrained('countries')->onUpdate('cascade')->onDelete('cascade');
                $table->string('en');
                $table->string('abb');
                $table->boolean('status')->default(true);
                $table->string('fa')->nullable();
                $table->string('ar')->nullable();
                $table->string('ku')->nullable();
                $table->string('tr')->nullable();
                $table->softDeletes();
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
