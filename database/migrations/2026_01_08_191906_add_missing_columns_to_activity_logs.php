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
        Schema::table('activity_logs', function (Blueprint $table) {
            // These are the columns the package is trying to find
            $table->string('causer_type')->nullable()->after('causer_id');
            $table->string('event')->nullable()->after('subject_type');
            $table->uuid('batch_uuid')->nullable()->after('properties');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            //
        });
    }
};
