<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLevelToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('level')->after('parent_id')->default(1);
        });

         // Set the level for existing categories
         $categories = \App\Models\Category::all();

        foreach ($categories as $category) {
            if ($category->parent_id === null) {
                $category->level = 1;
            } else {
                $parent = \App\Models\Category::find($category->parent_id);
                if ($parent && $parent->parent_id === null) {
                    $category->level = 2;
                } else {
                    $category->level = 3;
                }
            }
            $category->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
}
