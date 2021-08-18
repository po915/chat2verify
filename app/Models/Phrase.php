<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Phrase
 * @package App\Models
 */
class Phrase extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [ 'user_id', 'team_id', 'shortcode', 'friendly_name', 'message', 'publicized_at' ];

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo {
        return $this->belongsTo( User::class );
    }

    /**
     * @return BelongsTo
     */
    public function team() : BelongsTo {
        return $this->belongsto( Team::class );
    }
}
