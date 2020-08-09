<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('example_blog_comments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('parent_id')->default("0");
            $table->text('content')->nullable();
            $table->boolean('is_approved')->default("1");
            $table->string('status')->default("published");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
