<?php

namespace Modules\Comments\Entities;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments__comments';
    protected $fillable = [
        'parent_id',
        'user_id',
        'commentable_id',
        'commentable_type',
        'comment',
        'locale',
        'url',
        'status',
        'ip'
    ];
}
