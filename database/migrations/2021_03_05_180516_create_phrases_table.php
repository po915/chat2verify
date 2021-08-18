<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhrasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phrases', function (Blueprint $table) {
            $table->id();
            $table->foreignId( 'user_id' )->nullable()->constrained( 'users' );
            $table->foreignId( 'team_id' )->nullable()->constrained( 'teams' );
            $table->string( 'shortcode' )->nullable();
            $table->string( 'friendly_name' )->nullable();
            $table->text( 'message' );
            $table->timestamp( 'publicized_at' )->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('phrases');
    }
}
