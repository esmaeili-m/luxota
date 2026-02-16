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
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();

            // ارتباط با تسک اصلی
            $table->foreignId('task_id')
                ->constrained('tasks')
                ->cascadeOnDelete();

            // برد و ستون مستقل (یا ارث‌بری)
            $table->foreignId('board_id')
                ->constrained('boards')
                ->cascadeOnDelete();

            $table->foreignId('column_id')
                ->constrained('columns')
                ->cascadeOnDelete();

            // اطلاعات اصلی
            $table->string('title');
            $table->text('description')->nullable();

            // مسئول
            $table->foreignId('assignee_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // وضعیت کلی (منبع حقیقت ستون است)
            $table->string('status')->default('open');

            // ترتیب در ستون (drag & drop)
            $table->bigInteger('order')->default(0);


            $table->timestamps();
            $table->softDeletes();

            // ایندکس‌ها برای پرفورمنس
            $table->index(['task_id']);
            $table->index(['board_id', 'column_id']);
        });    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('subtasks');
    }
};
