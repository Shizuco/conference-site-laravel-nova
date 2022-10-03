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
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('joins');
        });

        DB::table('plans')->where('slug', 'basic')->update([
            "joins" => 1,
        ]);

        DB::table('plans')->where('slug', 'junior')->update([
            "joins" => 5,
        ]);

        DB::table('plans')->where('slug', 'middle')->update([
            "joins" => 25,
        ]);

        DB::table('plans')->where('slug', 'senior')->update([
            "joins" => -1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
