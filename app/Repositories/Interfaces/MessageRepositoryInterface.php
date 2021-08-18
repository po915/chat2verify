<?php

namespace App\Repositories\Interfaces;

use App\Models\Team;

interface MessageRepositoryInterface
{
    public function compose( $to, $from, $message );

    public function createConversation( Team $team );

    public function getConversations();

    public function getLatestConversation();
}
