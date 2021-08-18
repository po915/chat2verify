<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Conversation
 * @package App\Models
 */
class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [ 'starting_user_id', 'contact_id', 'team_id', 'phone_number', 'phone_number_id', 'deleted_at', 'read_at' ];

    /**
     * @var string[]
     */
    protected $with = [ 'startedBy', 'contact', 'notes' ];

    /**
     * @var string[]
     */
    protected $appends = [ 'preview_message' ];

    /**
     * @var string[]
     */
    public $timestamps = [ 'created_at', 'updated_at', 'archived_at', 'read_at' ];

    /**
     * @return HasMany
     */
    public function messages() : HasMany {
        return $this->hasMany( Message::class );
    }

    /**
     * @return string
     */
    public function getPreviewMessageAttribute() : string {
        $last_message = $this->messages()->limit( 1 )->orderByDesc( 'id' )->first();

        if ( ! $last_message ) {
            return '';
        }

        return substr( $last_message->message, 0, 40 );
    }

    /**
     * @return BelongsTo
     */
    public function contact() : BelongsTo {
        return $this->belongsTo( Contact::class );
    }

    /**
     * @return HasMany
     */
    public function notes() : HasMany {
        return $this->hasMany( PrivateNote::class )->orderByDesc( 'updated_at' );
    }

    /**
     * @return HasMany
     */
    public function previewMessages() : HasMany {
        return $this->messages()->limit( 15 )->orderByDesc( 'id' );
    }

    /**
     * @return BelongsTo
     */
    public function phoneNumber() {
        return $this->belongsTo( PhoneNumber::class );
    }

    /**
     * @return BelongsTo
     */
    public function startedBy() : BelongsTo {
        return $this->belongsTo( User::class, 'starting_user_id' );
    }

    /**
     * @return BelongsTo
     */
    public function team() : BelongsTo {
        return $this->belongsTo( Team::class );
    }


    /**
     * @param $value
     * @return int|null
     */
    public function getUpdatedAtAttribute($value) {
        if ( ! $value ) {
            return null;
        }

        return Carbon::parse($value)->getTimestamp();
    }

    /**
     * @param $value
     * @return int|null
     */
    public function getCreatedAtAttribute($value) {
        if ( ! $value ) {
            return null;
        }

        return Carbon::parse($value)->getTimestamp();
    }
}
