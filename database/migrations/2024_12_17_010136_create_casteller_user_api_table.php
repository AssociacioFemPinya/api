<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql_api')->create('casteller_api_user', function (Blueprint $table) {
            $table->increments('id_casteller_api_user');
            $table->unsignedInteger('api_user_id');
            $table->unsignedInteger('casteller_id');
            $table->timestamps();

            /*

            IT has NO constraint because I couldn't make it work for an external DB

            I tried adding:

            $table->foreignId('casteller_id');
            ->constrained(
                table: 'mysql.castellers', indexName: 'casteller_id'
            );

            Also using fireng method after definig the field:

            $table->foreign('casteller_id')
                ->references('id_casteller')
                ->on(env('DB_DATABASE'). '.castellers');
            */

            $table->foreign('api_user_id')
                ->references('id_api_user')
                ->on(env('DB_DATABASE_API').'.api_users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_api')->dropIfExists('casteller_api_user');
    }
};
