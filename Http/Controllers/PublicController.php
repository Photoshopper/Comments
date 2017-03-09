<?php namespace Modules\Comments\Http\Controllers;

use Carbon\Carbon;
use Modules\Comments\Entities\Comment;
use Modules\Comments\Http\Requests\CreateCommentRequest;
use Modules\Comments\Repositories\CommentRepository;
use Modules\Core\Http\Controllers\BasePublicController;
use Modules\User\Contracts\Authentication;
use Modules\User\Entities\Sentinel\User;


class PublicController extends BasePublicController {

    private $comment;
    protected $auth;

    public function __construct(Authentication $auth, CommentRepository $commentRepository)
    {
        parent::__construct();

        $this->auth = $auth;
        $this->comment = $commentRepository;
    }

    /**
     * Store comments
     *
     * @param CreateCommentRequest $request
     * @return mixed
     */
    public function store(CreateCommentRequest $request)
    {
        $user_id = $this->auth->id();
        $user_id == 1 ? $status = true : $status = false;
        $user = User::where('id', $user_id)->first();

        $comment = Comment::create([
            'parent_id' => $request->input('parent_id'),
            'user_id' => $user_id,
            'commentable_id' => $request->input('commentable_id'),
            'commentable_type' => $request->input('commentable_type'),
            'comment' => $request->input('comment'),
            'locale' => locale(),
            'url' => parse_url(url()->previous())['path'],
            'status' => $status,
            'ip' => $request->server('REMOTE_ADDR'),
        ]);

        if($request->ajax()) {
            Carbon::setLocale(locale());

            $comment->username = $this->comment->getUsername($user_id);
            $comment->time_ago = $comment->created_at->diffForHumans();
            $comment->avatar = $this->comment->getAvatar($user);

            if($user_id == 1) {
                return response()->json(['comment' => $comment]);
            }

            return response()->json(['comment' => $comment, 'message' => trans('comments::comments.messages.comment sent')]);
        }

        if($user_id == 1) {
            return redirect()->back();
        }

        return redirect()->back()->withSuccess(trans('comments::comments.messages.comment sent'));
    }

}