<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFailedAttachmentDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('failed_attachment_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId( 'conversation_id' )->nullable()->constrained( 'conversations' );
            $table->foreignId( 'message_id' )->nullable()->constrained( 'messages' );
            $table->string( 'type' )->default( 'FATAL' );
            $table->string( 'url' )->nullable();
            $table->text( 'exception' )->nullable();
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
        Schema::dropIfExists('failed_attachment_downloads');
    }
}
