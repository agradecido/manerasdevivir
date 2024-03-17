<?php

namespace App\View\Components;

use App\Utils\Utils;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use WP_Post;
use App\Constants;

class PostCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(WP_Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.post-card', [
            'post' => $this->preparePost(),
        ]);
    }

    /**
     * Prepare the post data for the view
     */
    private function preparePost() : array
    {
        return [
            'title'     => html_entity_decode(get_the_title($this->post)),
            'permalink' => get_the_permalink($this->post),
            'excerpt'   => $this->getCustomExcerpt(),
            'thumbnail' => get_the_post_thumbnail_url($this->post, 'thumbnail'),
            'featured'  => get_the_post_thumbnail_url($this->post, 'large'),
            //'date'      => Utils::formatDateAndTime(get_the_date($this->>post)),
            'date'      => get_the_date('', $this->post),
            'author'    => get_post_meta($this->post->ID, 'sender_name', true),
        ];
    }

    /**
     * Generate a custom excerpt for the post.
     */
    private function getCustomExcerpt(int $wordCount = Constants::EXCERPT_WORDS_NUMBER) : string
    {
        $excerpt = wp_trim_words(get_the_excerpt($this->post), $wordCount);

        if (empty($excerpt)) {
            $excerpt = wp_trim_words($this->post->post_content, $wordCount);
        }
        return $excerpt;
    }
}
