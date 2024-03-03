<?php

/**
 * Register Custom Post Types
 */
foreach (glob(get_template_directory() . "/app/Types/*.php") as $filename) {
    include $filename;
}
