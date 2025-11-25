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
        Schema::create('exam_forms', function (Blueprint $table) {
            $table->id();
            $table->foreign('user_id')
              ->references('id')->on('users')
              ->onDelete('cascade')
              ->onUpdate('cascade');
            $table->string('full_name');
            $table->string('email');
            $table->string('course');
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_forms');
    }
};
