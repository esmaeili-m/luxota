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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('board_id');
            $table->unsignedBigInteger('column_id');
            $table->unsignedBigInteger('sprint_id')->nullable();
            $table->unsignedBigInteger('ticket_id')->nullable();

            $table->string('task_key')->unique()->nullable();  // شناسه انسانی مثل DEV-102
            $table->string('task_code')->unique(); // کد داخلی مثل TS10000

            $table->string('title_fa');
            $table->string('title_en')->nullable();
            $table->text('description')->nullable();

            $table->string('type')->default('task'); // task/story
            $table->string('priority')->default('low');
            $table->string('task_category')->nullable(); // bug / improvement / feature
            $table->boolean('urgent')->default(false);

            $table->unsignedBigInteger('parent_task_id')->nullable(); // زیرمجموعه Task دیگری
            $table->string('business_status')->nullable(); // client_request / internal / business
            $table->string('has_invoice')->nullable();

            $table->string('implementation')->nullable();

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('assigned_to')->nullable(); // می‌تونه pivot team هم باشه
            $table->date('due_date')->nullable();

            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
            $table->foreign('column_id')->references('id')->on('columns')->onDelete('cascade');
            $table->foreign('sprint_id')->references('id')->on('sprints')->onDelete('set null');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('set null');
            $table->foreign('parent_task_id')->references('id')->on('tasks')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['board_id', 'column_id']);
            $table->index(['task_key', 'task_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('tasks');
    }
};
