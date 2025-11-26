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
        Schema::table('payments', function (Blueprint $table) {
        $table->string('razorpay_order_id')->nullable();
        $table->string('razorpay_signature')->nullable();
        $table->string('pdf_path')->nullable()->after('status');
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
        $table->dropColumn(['razorpay_order_id', 'razorpay_signature', 'pdf_path']);
        });
    }
};
