<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->post('comments', ['as' => 'comments.comment.store', 'uses' => 'PublicController@store']);