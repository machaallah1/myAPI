<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * Get the posts that belong to the tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<App\Models\Post>
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

}
