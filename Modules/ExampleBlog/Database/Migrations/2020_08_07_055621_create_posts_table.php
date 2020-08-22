<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('example_blog_posts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('author_id');
            $table->string('unique_code');
            $table->morphs('postable');
            $table->string('title');
            $table->string('slug');
            $table->text('content')->nullable();
            $table->string('status')->default("draft");

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
        Schema::dropIfExists('posts');
    }
}
