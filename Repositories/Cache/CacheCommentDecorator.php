<?php

namespace Modules\Comments\Repositories\Cache;

use Modules\Comments\Repositories\CommentRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheCommentDecorator extends BaseCacheDecorator implements CommentRepository
{
    public function __construct(CommentRepository $comment)
    {
        parent::__construct();
        $this->entityName = 'comments.comments';
        $this->repository = $comment;
    }

    /**
     * Count all comments
     * @return int
     */
    public function countAll()
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.countAll", $this->cacheTime,
                function () {
                    return $this->repository->countAll();
                }
            );
    }

    /**
     * Count unapproved comments
     * @return int
     */
    public function countUnapproved()
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.countUnapproved", $this->cacheTime,
                function () {
                    return $this->repository->countUnapproved();
                }
            );
    }

    /**
     * Count approved comments
     * @return int
     */
    public function countApproved()
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.countApproved", $this->cacheTime,
                function () {
                    return $this->repository->countApproved();
                }
            );
    }
}
