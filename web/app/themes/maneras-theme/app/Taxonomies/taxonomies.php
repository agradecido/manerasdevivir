<?php

add_action('add_meta_boxes', function () {
    add_meta_box(
        'article_info',
        'Article Information',
        'article_custom_box_html',
        'article',
    );
});

function article_custom_box_html($post) : void
{
    $sender_name = get_post_meta($post->ID, 'sender_name', true);
    $sender_email = get_post_meta($post->ID, 'sender_email', true);
    $sender_ip = get_post_meta($post->ID, 'sender_ip', true);
    $publish_date = get_post_meta($post->ID, 'publish_date', true);
    $source = get_post_meta($post->ID, 'source', true);
    $state = get_post_meta($post->ID, 'state', true);
    $canonical = get_post_meta($post->ID, 'canonical', true);
    $featured_image = get_post_meta($post->ID, 'featured_image', true);
    $tags = get_post_meta($post->ID, 'tags', true);

    // Preparar campos (Aquí deberías reemplazar esto con tu HTML correspondiente)
    echo 'Sender Name: <input type="text" name="sender_name" value="' . esc_attr($sender_name) . '" /><br />';
    echo 'Sender Email: <input type="email" name="sender_email" value="' . esc_attr($sender_email) . '" /><br />';
    echo 'Sender IP: <input type="text" name="sender_ip" value="' . esc_attr($sender_ip) . '" /><br />';
    echo 'Fecha: <input type="text" name="fecha" value="' . esc_attr($publish_date) . '" /><br />';
    echo 'Source: <input type="text" name="source">' . esc_textarea($source) . '<br />';
    echo 'State: <input type="text" name="state" value="' . esc_attr($state) . '" /><br />';
    echo 'Canonical: <input type="text" name="canonical" value="' . esc_attr($canonical) . '" /><br />';
    echo 'Featured Image: <input type="text" name="featured_image" value="' . esc_attr($featured_image) . '" /><br />';
    echo 'Tags: <textarea name="tags">' . esc_textarea($tags) . '</textarea><br />';
}

add_action('save_post', function ($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Verificar el nonce aquí si tienes uno como medida de seguridad

    // Verifica los permisos del usuario
    if (isset($_POST['post_type']) && 'article' == $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    }

    // A continuación, sanea y guarda cada campo
    if (array_key_exists('sender_name', $_POST)) {
        update_post_meta(
            $post_id,
            'sender_name',
            sanitize_text_field($_POST['sender_name'])
        );
    }

    if (array_key_exists('sender_email', $_POST)) {
        update_post_meta(
            $post_id,
            'sender_email',
            sanitize_email($_POST['sender_email'])
        );
    }

    if (array_key_exists('sender_ip', $_POST)) {
        update_post_meta(
            $post_id,
            'sender_name',
            $_POST['sender_name']
        );
    }

    if (array_key_exists('publish_date', $_POST)) {
        update_post_meta(
            $post_id,
            'fecha',
            sanitize_text_field($_POST['fecha']) // Asegúrate de que el formato de fecha sea el correcto
        );
    }

    if (array_key_exists('source', $_POST)) {
        update_post_meta(
            $post_id,
            'source',
            sanitize_textarea_field($_POST['source'])
        );
    }

    if (array_key_exists('state', $_POST)) {
        update_post_meta(
            $post_id,
            'state',
            sanitize_text_field($_POST['state'])
        );
    }

    if (array_key_exists('canonical', $_POST)) {
        update_post_meta(
            $post_id,
            'canonical',
            esc_url_raw($_POST['canonical'])
        );
    }

    if (array_key_exists('featured_image', $_POST)) {
        update_post_meta(
            $post_id,
            'featured_image',
            esc_url_raw($_POST['featured_image'])
        );
    }

    if (array_key_exists('tags', $_POST)) {
        update_post_meta(
            $post_id,
            'tags',
            sanitize_textarea_field($_POST['tags']) // Considera cómo se almacenan las etiquetas; podrías necesitar una lógica adicional si se espera un formato específico
        );
    }
});
