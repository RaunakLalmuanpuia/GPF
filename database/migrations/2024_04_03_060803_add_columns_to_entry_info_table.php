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
        Schema::table('entry_infos', function (Blueprint $table) {
            $table->string('from_designation')->nullable();
            $table->string('from_deparment')->nullable();
            $table->string('gpf_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entry_infos', function (Blueprint $table) {
            $table->dropColumn('from_designation');
            $table->dropColumn('from_deparment');
            $table->dropColumn('gpf_name');
            
        });
    }
};
