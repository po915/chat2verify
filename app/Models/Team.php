<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

/**
 * Class Team
 * @package App\Models
 */
class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'personal_team' => 'boolean',
        'twilio_authentication_verified' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    protected $with = [ 'phoneNumbers' ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /**
     * @return HasMany
     */
    public function conversations(): HasMany {
        return $this->hasMany( Conversation::class );
    }

    public function phrases() : HasMany {
        return $this->hasMany( Phrase::class );
    }

    /**
     * @return HasMany
     */
    public function phoneNumbers(): HasMany {
        return $this->hasMany( PhoneNumber::class );
    }

    /**
     * @param $attribute
     * @return mixed|string
     */
    public function getTwilioAccountIdAttribute($attribute ) : string {
        try {
            return Crypt::decryptString( $attribute );
        } catch( DecryptException $exception ) {
            return '';
        }
    }


    /**
     * @param $attribute
     * @return mixed|string
     */
    public function getTwilioAuthTokenAttribute($attribute ) : string {
        try {
            return Crypt::decryptString( $attribute );
        } catch( DecryptException $exception ) {
            return '';
        }
    }
}
