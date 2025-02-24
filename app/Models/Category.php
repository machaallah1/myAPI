<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = ['name', 'slug'];

    /**
     * Get the posts that belong to the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Post>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class,);
    }

    public function registerMediaCollections(?Media $media = null): void
    {
        $this->addMediaCollection('categories')
            ->singleFile();

        $this->addMediaConversion(name: 'thumb')
            ->fit(
                fit: Fit::Crop,
                desiredWidth: 80,
                desiredHeight: 80,
            )
            ->sharpen(amount: 10);
    }
}
