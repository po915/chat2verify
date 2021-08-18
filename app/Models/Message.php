<?php

namespace App\Models;

use Carbon\Carbon;
use ForceUTF8\Encoding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Message
 * @package App\Models
 */
class Message extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * @var string[]
     */
    protected $appends = [ 'attachments' ];

    /**
     * @var string[]
     */
    protected $with = [ 'user', 'media' ];

    /**
     * @var string[]
     */
    protected $hidden = [ 'media' ];

    /**
     * @var string[]
     */
    protected $fillable = [ 'user_id', 'message', 'from', ];

    /**
     * @var string[]
     */
    protected $touches = [ 'conversation' ];

    /**
     * @return BelongsTo
     */
    public function conversation() : BelongsTo {
        return $this->belongsTo( Conversation::class );
    }

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo {
        return $this->belongsTo( User::class );
    }

    /**
     * @return \Illuminate\Support\Collection|\Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection
     */
    public function getAttachmentsAttribute() {
        $media = $this->getMedia( 'images' );

        return $media->map( function( $item ) {
            return [
                'thumbnail' => $item->getFullUrl('thumbnail'),
                'full' => $item->getFullUrl(),
            ];
        });
    }

    /**
     *
     */
    public function registerMediaCollections(): void {
        $this->addMediaCollection('images');
    }

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->crop( Manipulations::CROP_CENTER, 384, 384 )
            ->width(384)
            ->height(384)
            ->sharpen(10);
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

    public function getMessageAttribute($value) {
        if ( ! empty( $value ) ) {
            // Should prevent encoding errors when serialising the JSON response.
            return Encoding::toUTF8( Encoding::toISO8859( $value ) );
        }

        return $value;
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
