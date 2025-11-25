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
        Schema::create('payments', function (Blueprint $table) {
        $table->id();
        // Use the shorter, more readable method for foreign keys:
        $table->foreignId('exam_form_id')
            ->constrained('exam_forms') // Explicitly reference the 'exam_forms' table
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
        // Consider precision for financial data using 'decimal' or 'double'
        $table->decimal('amount', 10, 2); 

        // Use default/nullable values for status and softDeletes
        $table->string('payment_gateway')->nullable();
        $table->string('transaction_id')->unique()->nullable(); // Ensure uniqueness and allow null
        $table->string('status')->default('pending'); 

        $table->timestamps();
        $table->softDeletes();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
