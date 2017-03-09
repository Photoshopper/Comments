<?php

namespace Modules\Comments\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Comments\Entities\Comment;
use Modules\Comments\Http\Requests\BulkActionRequest;
use Modules\Comments\Repositories\CommentRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class CommentController extends AdminBaseController
{
    /**
     * @var CommentRepository
     */
    private $comment;

    public function __construct(CommentRepository $comment)
    {
        parent::__construct();

        $this->comment = $comment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $comments = $this->comment->all();

        return view('comments::admin.comments.index', compact('comments'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  Comment $comment
     * @return Response
     */
    public function edit(Comment $comment)
    {
        return view('comments::admin.comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Comment $comment
     * @param  Request $request
     * @return Response
     */
    public function update(Comment $comment, Request $request)
    {
        $this->comment->update($comment, $request->all());

        return redirect()->route('admin.comments.comment.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('comments::comments.title.comments')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Comment $comment
     * @return Response
     */
    public function destroy(Comment $comment)
    {
        $this->comment->destroy($comment);

        return redirect()->route('admin.comments.comment.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('comments::comments.title.comments')]));
    }

    /**
     * Bulk actions for comments
     *
     * @param BulkActionRequest $request
     * @return mixed
     */
    public function bulkAction(BulkActionRequest $request)
    {
        if($request->input('bulk_action') == 'approve') {
            Comment::whereIn('id', $request->input('comments'))->update(['status' => 1]);

            return redirect()->route('admin.comments.comment.index')
                ->withSuccess(trans('comments::comments.messages.comments approved'));
        }

        if($request->input('bulk_action') == 'unapprove') {
            Comment::whereIn('id', $request->input('comments'))->update(['status' => 0]);

            return redirect()->route('admin.comments.comment.index')
                ->withSuccess(trans('comments::comments.messages.comments unapproved'));
        }

        if($request->input('bulk_action') == 'delete') {
            $comments = Comment::whereIn('id', $request->input('comments'))->get();

            foreach ($comments as $comment) {
                $this->comment->destroy($comment);
            }

            return redirect()->route('admin.comments.comment.index')
                ->withSuccess(trans('comments::comments.messages.comments deleted'));
        }

        return redirect()->route('admin.comments.comment.index');
    }

    /**
     * List of unapproved comments
     *
     * @return Response
     */
    public function unapproved()
    {
        $comments = Comment::where('status', 0)->get();

        return view('comments::admin.comments.index', compact('comments'));
    }

    /**
     * List of approved comments
     *
     * @return Response
     */
    public function approved()
    {
        $comments = Comment::where('status', 1)->get();
        
        return view('comments::admin.comments.index', compact('comments'));
    }

}
