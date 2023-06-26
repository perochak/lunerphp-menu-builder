<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoleIdToMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(config('menu.table_prefix') . config('menu.table_name_items'), function ($table) {
            $table->string('object_type',100)->default('link');
            $table->integer('object_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config('menu.table_prefix') . config('menu.table_name_items'), function ($table) {
            $table->dropColumn('object_type');
            $table->dropColumn('object_id');
        });
    }
}
