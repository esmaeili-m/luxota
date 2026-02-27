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
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();

            $table->enum('task_type', ['task','subtask','checklist_item']);
            $table->unsignedBigInteger('task_id');

            $table->unsignedBigInteger('from_board_id')->nullable();
            $table->unsignedBigInteger('to_board_id')->nullable();
            $table->unsignedBigInteger('from_column_id')->nullable();
            $table->unsignedBigInteger('to_column_id')->nullable();

            $table->unsignedBigInteger('user_id'); // کاربر مسئول تغییر

            $table->timestamp('started_at'); // شروع حضور در ستون
            $table->timestamp('ended_at')->nullable(); // پایان حضور در ستون
            $table->integer('duration_minutes')->nullable(); // زمان حضور بر حسب دقیقه

            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['task_type','task_id']);
            $table->index(['from_column_id','to_column_id']);
            $table->index(['from_board_id','to_board_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_logs');
    }
};
