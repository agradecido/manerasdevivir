<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FeaturedPostCard extends Component
{
    // private array $post; // Not needed in PHP 8

    /**
     * Create a new component instance.
     */
    public function __construct(array $post)
    {
        $this->post = $post;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.featured-post-card', [
            'post' => $this->post,
        ]);
    }
}
