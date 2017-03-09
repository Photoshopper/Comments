<?php

namespace Modules\Comments\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Comments\Composers\Admin\Countcomposer;
use Modules\Core\Traits\CanPublishConfiguration;

class CommentsServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();

        view()->composer('comments::admin.comments.index', Countcomposer::class);
    }

    public function boot()
    {
        $this->publishConfig('comments', 'permissions');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(
            'Modules\Comments\Repositories\CommentRepository',
            function () {
                $repository = new \Modules\Comments\Repositories\Eloquent\EloquentCommentRepository(new \Modules\Comments\Entities\Comment());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Comments\Repositories\Cache\CacheCommentDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Comments\Http\Controllers\Api\CommentController',
            function() {
                $repository = $this->app->make(\Modules\Comments\Services\CommentsListRenderer::class);

                return new \Modules\Comments\Http\Controllers\Api\CommentController($repository);
            }
        );

    }
}
