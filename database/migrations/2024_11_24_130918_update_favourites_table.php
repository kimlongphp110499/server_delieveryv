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
        Schema::table('favourites', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->change();
            $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->foreignId('product_id')->constrained()->onDelete('cascade')->change();

        $table->dropForeign(['vendor_id']);
        $table->dropColumn('vendor_id');
    }
};
