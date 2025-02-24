<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class,'post_tag','post_id','tag_id');
    }

}
