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
        Schema::create('subtask_checklists', function (Blueprint $table) {
            $table->id();

            // ارتباط با Subtask
            $table->foreignId('subtask_id')
                ->constrained('subtasks')
                ->cascadeOnDelete();

            // عنوان و توضیح آیتم
            $table->string('title');
            $table->text('description')->nullable();

            // وضعیت آیتم
            $table->enum('status', ['pending','done','blocked'])->default('pending');

            // ترتیب نمایش آیتم‌ها در چک‌لیست
            $table->integer('order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['subtask_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subtask_checklists');
    }
};
