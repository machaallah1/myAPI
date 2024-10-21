<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * Get the posts that belong to the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Post>
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
