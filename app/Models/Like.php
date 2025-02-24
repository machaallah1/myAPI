<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function post():BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user that owns the like.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User>
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comment that owns the like.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Comment>
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
