<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamPhoneNumber extends Model
{
    use HasFactory;

    protected $fillable = [ 'team_id', 'phone_number', 'friendly_name', 'description' ];
}
