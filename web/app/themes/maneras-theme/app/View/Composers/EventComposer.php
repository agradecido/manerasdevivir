<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FeaturedPostController;

class EventComposer extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var string[]
     */
    protected static $views = [
        'event',
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
     * @var FeaturedPostController
     */
    protected $postController;

    public function __construct()
    {
        $this->eventController = new EventController();
    }

    public function with() : array
    {
        return [
            'events'        => $this->eventController->index(),
        ];
    }
}
