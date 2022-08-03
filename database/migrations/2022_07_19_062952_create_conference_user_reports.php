<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conferece_user_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('conference_id');
            $table->integer('user_id');
            $table->integer('category_id');
            $table->string('thema');
            $table->string('start_time');
            $table->string('end_time');
            $table->text('description');
            $table->string('presentation');
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
        Schema::dropIfExists('conferece_user_reports');
    }
};
