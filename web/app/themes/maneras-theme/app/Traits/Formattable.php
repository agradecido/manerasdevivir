<?php

namespace App\Traits;

use App\Constants;
use App\Utils\Utils;
use Illuminate\Support\Facades\URL;

trait Formattable
{
    /**
     * @param $event
     * @return array
     */
    public function formatEvent($event)
    {
        return [
            'title' => get_the_title($event),
            'permalink' => get_permalink($event),
            'purchase_url' => $this->addAffiliateTag(get_post_meta($event->ID, 'purchase_url', true)),
            'start_date' => Utils::formatDateAndTime(get_post_meta($event->ID, 'start_date', true)),
            'province' => ucwords(get_post_meta($event->ID, 'administrative_division', true)),
            'poster' => esc_url(get_the_post_thumbnail_url($event, 'full')),
            'city' => Utils::formatTitleCase(get_post_meta($event->ID, 'city', true)),
            'venue' => get_post_meta($event->ID, 'venue', true),
        ];
    }

    /**
     * @param $url
     * @return string
     */
    private function addAffiliateTag($url)
    {
        if (strpos($url, 'wegow.com') === false) {
            return $url;
        }
        return $url . Constants::WEGOW_TAG;
    }
}
