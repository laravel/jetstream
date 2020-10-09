<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_providers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('provider_name');
            $table->string('provider_id');
            $table->string('access_token');
            $table->string('secret')->nullable(); // OAuth1
            $table->string('refresh_token')->nullable(); // OAuth2
            $table->dateTime('expires_at')->nullable(); // OAuth2
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
        Schema::drop('social_providers');
    }
}
