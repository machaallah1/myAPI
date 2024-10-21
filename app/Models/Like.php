<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    /** @use HasFactory<\Database\Factories\LikeFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'post_id', 'comment_id', 'liked'];

    /**
     * Get the post that owns the like.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Post>
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user that owns the like.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
