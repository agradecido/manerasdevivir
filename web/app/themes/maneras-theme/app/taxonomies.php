<?php

/**
 * Register Custom Taxonomies
 */
foreach (glob(get_template_directory() . "/app/Taxonomies/*.php") as $filename) {
    include $filename;
}
