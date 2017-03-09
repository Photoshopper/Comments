<?php

namespace Modules\Comments\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface CommentRepository extends BaseRepository
{
    /**
     * Return user's full name
     *
     * @param int $user_id
     * @return string
     */
    public function getUsername($user_id);

    /**
     * Return an avatar path string
     *
     * @param $user
     * @return string
     */
    public function getAvatar($user);

    /**
     * Count all comments
     * @return int
     */
    public function countAll();

    /**
     * Count unapproved comments
     * @return int
     */
    public function countUnapproved();
    
}
