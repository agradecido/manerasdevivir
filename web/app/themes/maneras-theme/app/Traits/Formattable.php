<?php

namespace App\Traits;

use App\Constants;
use App\Utils\Utils;
use Illuminate\Support\Facades\URL;
use WP_Post;

trait Formattable
{
    public function formatEventCard(WP_Post $event) : array
    {
        return [
            'title' => get_the_title($event),
            'permalink' => get_permalink($event),
            'purchase_url' => $this->addAffiliateTag(get_post_meta($event->ID, 'purchase_url', true)),
            'start_date' => Utils::formatDateAndTime(get_post_meta($event->ID, 'start_date', true), $stripTime = false),
            'province' => ucwords(get_post_meta($event->ID, 'administrative_division', true)),
            'poster' => esc_url(get_the_post_thumbnail_url($event, 'full')),
            'city' => Utils::formatTitleCase(get_post_meta($event->ID, 'city', true)),
            'venue' => get_post_meta($event->ID, 'venue', true),
        ];
    }


    public function formatFeaturedPostCard(WP_Post $post) : array
    {
        return [
            'ID'        => get_the_ID(),
            'title'     => html_entity_decode((get_the_title($post))),
            'permalink' => get_permalink($post),
            'thumbnail' => get_the_post_thumbnail_url($post),
            'excerpt'   => get_the_excerpt($post),
            'author'    => get_post_meta($post->ID, 'sender_name', true),
            'date'      => Utils::formatDateAndTime(get_the_date('Y-m-d', $post)),
        ];
    }

    private function addAffiliateTag(string $url) : string
    {
        if (strpos($url, 'wegow.com') === false) {
            return $url;
        }
        return $url . Constants::WEGOW_TAG;
    }
}
