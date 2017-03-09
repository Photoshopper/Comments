<?php

namespace Modules\Comments\Sidebar;

use Maatwebsite\Sidebar\Badge;
use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Comments\Repositories\CommentRepository;
use Modules\User\Contracts\Authentication;

class SidebarExtender implements \Maatwebsite\Sidebar\SidebarExtender
{
    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     *
     * @internal param Guard $guard
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Menu $menu
     *
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->item(trans('comments::comments.title.comments'), function (Item $item) {
                $item->icon('fa fa-comment');
                $item->weight(2);
                $item->route('admin.comments.comment.index');
                $item->badge(function (Badge $badge, CommentRepository $comment) {
                    if($comment->countUnapproved() > 0) {
                        $badge->setClass('bg-orange');
                        $badge->setValue($comment->countUnapproved());
                    } else {
                        $badge->setClass('bg-green');
                        $badge->setValue($comment->countAll());
                    }
                });
                $item->authorize(
                    $this->auth->hasAccess('comments.comments.index')
                );
            });
        });

        return $menu;
    }
}
