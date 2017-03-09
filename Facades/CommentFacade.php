<?php

namespace Modules\Comments\Facades;

use Illuminate\Support\Facades\Facade;

class CommentFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Modules\Comments\Http\Controllers\Api\CommentController';
    }
}