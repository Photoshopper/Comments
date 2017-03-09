<?php namespace Modules\Comments\Services;

use Carbon\Carbon;
use Modules\Comments\Entities\Comment;
use Modules\Comments\Repositories\CommentRepository;
use Modules\Profile\Presenters\ProfilePresenter;
use Modules\User\Contracts\Authentication;
use Modules\User\Entities\Sentinel\User;

class CommentsListRenderer
{
    private $list;
    protected $auth;
    private $comment;

    public function __construct(Authentication $auth, CommentRepository $comment)
    {
        $this->auth = $auth;
        $this->comment = $comment;
    }

    /**
     * Render comments tree
     *
     * @param object $comments
     * @param string $model_type
     * @param int $model_id
     * @param int $max_depth
     * @return string
     */
    public function renderCommentsList($comments, $model_type, $model_id, $max_depth)
    {
        if($comments->count() > 0) {
            $this->list .= '<ul class="comment-list">';
            $this->generateCommentsList($comments, $model_type, $model_id, 1, $max_depth, $reply_to = null);
            $this->list .= '</ul>';
            $this->list .= $comments->links();
        }

        return $this->list;
    }


    /**
     * Generate html for comments tree
     *
     * @param object $comments
     * @param string $model_type
     * @param int $model_id
     * @param int $depth
     * @param int $max_depth
     * @param string $reply_to
     */
    private function generateCommentsList($comments, $model_type, $model_id, $depth = 1, $max_depth, $reply_to)
    {
        Carbon::setLocale(locale());

        foreach ($comments as $key => $comment) {
            $user = User::where('id', $comment->user_id)->first();
            $username = $this->comment->getUsername($comment->user_id);

            $children = Comment::where('parent_id', $comment->id)
                ->where('commentable_type', $model_type)
                ->where('commentable_id', $model_id)
                ->where('status', 1)->get();

            $this->list .= '<li>';
            $this->list .= '<div class="comment-content clearfix">';
            $this->list .= '<div class="avatar"><img src="'. $this->comment->getAvatar($user) .'" alt=""></div>';

            $this->list .= '<div class="comment-body">';

            $this->list .= '<header>';
            $this->list .= '<span class="author">' . $username . '</span>';
            isset($reply_to) ? $this->list .= ' <span class="glyphicon glyphicon-share-alt"></span> ' . $reply_to : null;
            $this->list .= '<span class="time-ago">' . $comment->created_at->diffForHumans() . '</span>';
            $this->list .= '</header>';

            $this->list .= '<div class="comment-message">' . $comment->comment . '</div>';
            $this->auth->check() ? $this->list .= '<div class="reply"><a href="#" data-id="' . $comment->id . '">'. trans('comments::comments.button.reply') .'</a></div>' : null;

            $this->list .= '</div>';
            $this->list .= '</div>';

            if($children->count() > 0) {
                if($depth > $max_depth) {
                    $this->list .= '<ul class="comment-list children last">';
                } else {
                    $this->list .= '<ul class="comment-list children">';
                }
                $this->list .= $this->generateCommentsList($children, $model_type, $model_id, $depth + 1, $max_depth, $username);
                $this->list .= '</ul>';
            }

            $this->list .= '</li>';
        }
    }
    
}
