<?php

/**
* Add custom rewrite rules
*/
add_action('init', function () {
    add_rewrite_rule('^noticias/([0-9]+)/([^/]+)?$', 'index.php?name=$matches[2]', 'top');
});
