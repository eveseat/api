<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApiTokensTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('api_tokens', function (Blueprint $table) {

            $table->increments('id');
            $table->string('token');
            $table->string('allowed_src');
            $table->string('comment')->nullable();

            // Indexes
            $table->index('token');
            $table->index('allowed_src');
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

        Schema::drop('api_tokens');
    }
}
