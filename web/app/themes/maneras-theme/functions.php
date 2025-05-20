<?php

use Timber\Timber;
use ManerasTheme\Theme;

require_once __DIR__ . '/vendor/autoload.php';

Timber::init();

Timber::$dirname = array( 'templates' );

// Load theme logic.
new Theme();
