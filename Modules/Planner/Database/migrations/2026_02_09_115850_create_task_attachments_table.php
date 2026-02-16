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
        Schema::create('task_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->unsignedBigInteger('uploaded_by');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');

            $table->index(['task_id', 'uploaded_by']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_attachments');
    }
};
