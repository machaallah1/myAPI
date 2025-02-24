<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'content',
    ];

    /**
     * Get the user that owns the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User>
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the post that owns the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Post>
     */
    public function post():BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
