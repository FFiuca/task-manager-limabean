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
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->foreignId('assign_user_id')->nullable()->constrained(table: 'users');
            $table->enum('priority', ['low', 'medium', 'high'])->nullable();
            $table->dateTime('due_date')->nullable();
            $table->foreignId('epic_id')->nullable()->constrained(table: 'epics');
            $table->foreignId('report_to_user_id')->nullable()->constrained(table: 'users');
            $table->foreignId('created_by')->constrained(table: 'users');
            $table->foreignId('deleted_by')->nullable()->constrained(table: 'users');
            $table->softDeletes();
            $table->foreignId('status_id')->constrained(table: 'statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
