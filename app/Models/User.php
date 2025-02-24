<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasApiTokens;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use InteractsWithMedia;

    protected $appends = [
        'image',
        'thumbnail',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'password',
        'role',
        'phone',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the posts that belong to the user.
     *
     * @return HasMany<Post, Comment>
     */
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the comments that belong to the user.
     *
     * @return HasMany<Comment>
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the likes that belong to the user.
     *
     * @return HasMany<Like>
     */

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * @return Attribute<string, never-return>
     */
    public function image(): Attribute
    {
        $media = $this->media->first();

        return Attribute::make(
            get: fn() => $media instanceof Media
                ? $media->getUrl()
                : null,
        );
    }


    public function registerMediaCollections(?Media $media = null): void
    {
        $this->addMediaCollection(name: 'users')
            ->singleFile();

        $this->addMediaConversion(name: 'thumb')
            ->fit(
                fit: Fit::Crop,
                desiredWidth: 80,
                desiredHeight: 80,
            )
            ->sharpen(amount: 10);
    }

    /**
     * @return Attribute<string, never-return>
     */
    public function thumbnail(): Attribute
    {
        $media = $this->media->first();

        return Attribute::make(
            get: fn() => $media instanceof Media
                ? $media->getUrl(
                    conversionName: 'thumb',
                )
                : null,
        );
    }
}
