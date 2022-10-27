<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumberAndDisallowedToCafeTableSlots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cafe_table_slots', function (Blueprint $table) {
            $table->integer('number');
            $table->integer('disallowed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cafe_table_slots', function (Blueprint $table) {
            $table->dropColumn(['number', 'disallowed']);
        });
    }
}
