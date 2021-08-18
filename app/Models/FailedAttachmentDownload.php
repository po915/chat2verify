<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FailedAttachmentDownload extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = [ 'conversation_id', 'message_id', 'type', 'url', 'exception' ];
}
