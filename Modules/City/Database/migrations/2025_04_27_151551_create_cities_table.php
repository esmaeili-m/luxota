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
                $table->string('en')->nullable();
                $table->string('abb')->nullable();
                $table->boolean('status')->default(true);
                $table->string('fa')->nullable();
                $table->string('ar')->nullable();
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

        Schema::dropIfExists('cities');
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

    }
};
