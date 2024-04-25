<?php

function child_theme_assets() {
    wp_enqueue_style("my-cards", get_stylesheet_directory_uri() . "/assets/my-cards.css");
    wp_enqueue_style("koc-cards", get_stylesheet_directory_uri() . "/assets/koc-cards.css");
}
add_action("wp_enqueue_scripts", "child_theme_assets");

include get_stylesheet_directory() . '/inc/cards.php';
include get_stylesheet_directory() . '/inc/koc-course-card.php';
?>