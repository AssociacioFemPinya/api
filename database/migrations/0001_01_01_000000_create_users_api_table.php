<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        /* MANAGING API USERS */

        Schema::connection('mysql_api')->create('api_users', function (Blueprint $table) {
            $table->increments('id_api_user');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::connection('mysql_api')->create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        /* ONLY IF WANT TO STORE SESSIONS ON DB */

        Schema::connection('mysql_api')->create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('api_user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        /* MANAGING API USERS */

        Schema::connection('mysql_api')->dropIfExists('api_users');
        Schema::connection('mysql_api')->dropIfExists('password_reset_tokens');

        /* ONLY IF WANT TO STORE SESSIONS ON DB */

        Schema::connection('mysql_api')->dropIfExists('sessions');
    }
};
