<?php namespace Modules\Comments\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Modules\Comments\Entities\Comment;
use Modules\Comments\Services\CommentsListRenderer;
use Modules\User\Entities\Sentinel\User;

class CommentController extends Controller
{
    private $commentsListRenderer;

    public function __construct(CommentsListRenderer $commentsListRenderer)
    {
        $this->commentsListRenderer = $commentsListRenderer;
    }

    /**
     * Render comment form and comments list
     * @param $model
     * @param int $max_depth
     * @return mixed
     */
    public function render($model, $max_depth = 3)
    {
        $model_id = $model->id;
        $model_type = get_class($model);

        $comments = Comment::where('parent_id', null)
            ->where('commentable_type', $model_type)
            ->where('commentable_id', $model_id)
            ->where('locale', locale())
            ->where('status', 1)
            ->orderBy('created_at', 'desc')->paginate(10);

        $comments_list = $this->commentsListRenderer->renderCommentsList($comments, $model_type, $model_id, $max_depth);

        $view = View::make('comments::frontend.comments.comments')->with(['model' => $model, 'comments_list' => $comments_list]);

        return $view->render();
    }

    /**
     * Return user's full name
     *
     * @param int $user_id
     * @return string
     */
    public function getUsername($user_id) {
        $user = User::where('id', $user_id)->first();

        return $user->first_name . ' ' . $user->last_name;
    }

    /**
     * Return a number of comments in a model
     *
     * @param $model
     * @return int
     */
    public function count($model)
    {
        return $comments = Comment::where('commentable_type', get_class($model))
            ->where('commentable_id', $model->id)
            ->where('locale', locale())
            ->where('status', 1)->get()->count();
    }



}
