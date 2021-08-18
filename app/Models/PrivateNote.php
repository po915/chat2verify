<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PrivateNote
 * @package App\Models
 */
class PrivateNote extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [ 'user_id', 'conversation_id', 'note' ];

    /**
     * @var string[]
     */
    protected $with = [ 'user' ];

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo {
        return $this->belongsTo( User::class );
    }

    /**
     * @return BelongsTo
     */
    public function conversation() : BelongsTo {
        $this->belongsTo( Conversation::class );
    }

    public function getCreatedAtAttribute( $value ) {
    	return Carbon::parse( $value )->getTimestamp();
    }

    public function getUpdatedAtAttribute( $value ) {
    	return Carbon::parse( $value )->getTimestamp();
    }
}
