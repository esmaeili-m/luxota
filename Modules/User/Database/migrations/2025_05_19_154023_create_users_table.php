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
            $table->string('phone')->unique();
            $table->text('description')->nullable();
            $table->text('avatar')->nullable();
            $table->json('websites')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('address')->nullable();
            $table->string('luxota_website')->nullable();
            $table->boolean('status')->default(true);
            $table->string('country_code')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('whatsapp_country_code')->nullable();
            $table->foreignId('role_id')->default(1)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('city_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('rank_id')->default(2)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('referrer_id')->default(1)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->default(1)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
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
