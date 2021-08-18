<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MessageLog
 * @package App\Models
 */
class MessageLog extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [ 'payload' ];

    /**
     * @var string[]
     */
    protected $casts = [
        'payload' => 'array',
    ];
}
