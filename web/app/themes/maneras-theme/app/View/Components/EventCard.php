<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EventCard extends Component
{
    // private array $event; // Not needed in PHP 8

    /**
     * Create a new component instance.
     */
    public function __construct(array $event)
    {
        $this->event = $event;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.event-card', [
            'event' => $this->event,
        ]);
    }
}
