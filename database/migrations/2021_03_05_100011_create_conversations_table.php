<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId( 'starting_user_id' )->nullable()->constrained( 'users' );
            $table->foreignId( 'team_id' )->nullable()->constrained( 'teams' );
            $table->foreignId( 'contact_id' )->nullable()->constrained( 'contacts' );
            $table->foreignId( 'phone_number_id' )->nullable()->constrained( 'phone_numbers' );
            $table->string( 'phone_number' );
            $table->timestamp( 'read_at' )->nullable();
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
        Schema::dropIfExists('conversations');
    }
}
