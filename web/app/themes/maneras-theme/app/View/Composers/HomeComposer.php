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

    /**
     * The EventController instance.
     *
     * @var EventController
     */
    protected $eventController;

    /**
     * The PostController instance.
     *
     * @var PostController
     */
    protected $postController;

    public function __construct()
    {
        $this->eventController = new EventController();
        $this->postController = new PostController();
    }

    public function with() : array
    {
        return [
            'events'        => $this->eventController->index(),
            'featuredPosts' => $this->postController->getFeaturedPosts(),
        ];
    }
}
