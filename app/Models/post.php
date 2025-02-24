<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;
    use InteractsWithMedia;

    protected $appends = [
        'image',
        'thumbnail',
    ];

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'category_id',
        'slug',
        'status',
    ];

    protected $with = [
        'user',
        'category'
    ];

    /**
     * Get the category that owns the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Category>
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user that owns the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments that belong to the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Comment>
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the tags that belong to the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Tag>
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get the likes that belong to the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Like>
     */
    public function likes()
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
        $this->addMediaCollection(name: 'posts')
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
