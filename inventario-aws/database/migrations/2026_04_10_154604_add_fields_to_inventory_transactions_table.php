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
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('product_id');
            $table->integer('before_quantity')->default(0)->after('quantity');
            $table->integer('after_quantity')->default(0)->after('before_quantity');
            $table->string('reason')->nullable()->after('after_quantity');
            $table->text('description')->nullable()->after('reason');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'before_quantity', 'after_quantity', 'reason', 'description']);
        });
    }
};
