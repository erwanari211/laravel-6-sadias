<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOwnerableColumnToTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('example_blog_tags', function (Blueprint $table) {
            // $table->string('ownerable_type')->nullable(true);
            // $table->unsignedBigInteger('ownerable_id')->nullable(true);
            $table->nullableMorphs('ownerable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('example_blog_tags', function (Blueprint $table) {
            $table->dropMorphs('ownerable');
        });
    }
}
