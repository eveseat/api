<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApiTokenLogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('api_token_logs', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('api_token_id')->nullable();
            $table->enum('action', ['allow', 'deny']);
            $table->string('request_path');
            $table->string('src_ip');

            // Indexes
            $table->index('api_token_id');
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

        Schema::drop('api_token_logs');
    }
}
