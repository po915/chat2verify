<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PhoneNumber
 * @package App\Models
 */
class PhoneNumber extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [ 'user_id', 'team_id', 'phone_number', 'friendly_name', 'description', 'sid' ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conversations() {
        return $this->hasMany( Conversation::class );
    }

    public function team() {
        return $this->belongsTo( Team::class );
    }
}
