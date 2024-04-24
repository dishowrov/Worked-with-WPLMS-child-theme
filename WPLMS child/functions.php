<?php

function child_theme_assets() {
    wp_enque_style();
}
add_action("wp_enqueue_scripts", "child_theme_assets");

include get_stylesheet_directory() . '/inc/cards.php';
include get_stylesheet_directory() . '/inc/koc-course-card.php';
?>