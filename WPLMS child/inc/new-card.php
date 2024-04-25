<?php
// Create shortcode for displaying courses with IDs
function display_courses_func( $atts ) {
    // Extract shortcode attributes (course IDs)
    $atts = shortcode_atts( array(
        'ids' => '',
    ), $atts );

    // Convert comma-separated list of IDs into an array
    $course_ids = explode( ',', $atts['ids'] );

    // Query and display courses based on the provided IDs
    $output = '<ul>';
    foreach ( $course_ids as $course_id ) {
        $course = get_post( $course_id );
        if ( $course ) {
            $output .= '<li>' . $course->post_title . '</li>'; // Display course title
        }
    }
    $output .= '</ul>';

    return $output;
}
add_shortcode( 'the_dis_courses', 'display_courses_func' );
