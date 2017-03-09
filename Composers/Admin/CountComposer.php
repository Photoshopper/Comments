<?php

namespace Modules\Comments\Composers\Admin;

use Illuminate\Contracts\View\View;
use Modules\Comments\Repositories\CommentRepository;


class Countcomposer
{
    /**
     * @var CommentRepository
     */
    private $comment;

    public function __construct(CommentRepository $comment)
    {
        $this->comment = $comment;
    }

    public function compose(View $view)
    {
        $view->with('countAll', $this->comment->countAll());
        $view->with('countUnapproved', $this->comment->countUnapproved());
    }

}