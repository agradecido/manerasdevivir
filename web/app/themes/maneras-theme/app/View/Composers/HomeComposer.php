<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PostController;

class HomeComposer extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var string[]
     */
    protected static $views = [
        'home',
    ];

    public function with()
    {
        $eventController = new EventController();
        $postController = new PostController();

        return [
            'events' => $eventController->index(),
            'featuredPosts' => $postController->getFeaturedPosts(),
        ];
    }
}
