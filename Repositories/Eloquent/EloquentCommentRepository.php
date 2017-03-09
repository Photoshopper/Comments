<?php

namespace Modules\Comments\Repositories\Eloquent;

use Modules\Comments\Entities\Comment;
use Modules\Comments\Repositories\CommentRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\User\Entities\Sentinel\User;

class EloquentCommentRepository extends EloquentBaseRepository implements CommentRepository
{
    /**
     * Delete comments with answers
     *
     * @param object $comment
     * @return mixed
     */
    public function destroy($comment)
    {
        $childs = Comment::where('parent_id', $comment->id)->get();

        if($childs->count() > 0) {
            $this->destroyChilds($childs);
        }

        return $comment->delete();
    }

    /**
     * Recursive function for deleting child comments
     *
     * @param object $comments
     */
    private function destroyChilds ($comments)
    {
        foreach ($comments as $comment) {
            $childs = Comment::where('parent_id', $comment->id)->get();

            if($childs->count() > 0) {
                $this->destroyChilds($childs);
            }

            $comment->delete();
        }
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
     * Return an avatar path string
     *
     * @param $user
     * @return string
     */
    public function getAvatar($user)
    {
        if(isset($user->profile()->avatar) && !empty($user->profile()->avatar)) {
            return asset('storage/avatars/'.$user->profile()->avatar);
        }

        return asset('modules/profile/images/noavatar.png');
    }

    /**
     * Count all comments
     * @return int
     */
    public function countAll()
    {
        return $this->model->count();
    }

    /**
     * Count unapproved comments
     * @return int
     */
    public function countUnapproved()
    {
        return Comment::where('status', 0)->get()->count();
    }
    
}
