<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Resources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->integer('author_id')->nullable(); // belongs to fetched_resource_author.id
            $table->string('ext_id')->nullable(); // id of resource on a source service
            $table->string('ext_source')->index('ext_source'); // reddit, tiktok, instagram, etc
            $table->string('url')->index('url'); // link to external resource
            $table->string('title')->nullable();
            $table->text('text')->nullable();
            $table->text('serialized_fetched_resource'); // serialized FetchedResource object
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
        Schema::dropIfExists('resources');
    }
}
