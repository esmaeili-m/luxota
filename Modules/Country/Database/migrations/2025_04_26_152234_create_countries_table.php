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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('en');
            $table->string('abb')->nullable();
            $table->string('fa')->nullable();
            $table->string('ar')->nullable();
            $table->string('ku')->nullable();
            $table->string('tr')->nullable();
            $table->string('phone_code')->nullable();
            $table->text('flag')->nullable();
            $table->integer('zone_id')->nullable();
            $table->integer('currency_id')->nullable();
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
        Schema::dropIfExists('countries');
    }
};
