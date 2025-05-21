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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('role_id')->default(1);
            $table->string('phone')->unique();
            $table->integer('zone_id');
            $table->text('description')->nullable();
            $table->bigInteger('city_id');
            $table->text('avatar')->nullable();
            $table->json('website')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('address')->nullable();
            $table->integer('rank_id')->default(2);
            $table->integer('referrer_id')->default(1);
            $table->integer('branch_id');
            $table->string('luxota_website')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->boolean('status')->default(true);
            $table->string('country_code')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
