<?php



function dis_courses_shortcode($atts)
{
    $atts = shortcode_atts(
        array(
            'id' => '',
        ),
        $atts
    );

    ob_start();

    $dis_course_id = $atts['id'];
    if (!empty($dis_course_id)) {
        $dis_course_ids = $dis_course_id;
        $dis_course_ids = (explode(",", $dis_course_ids));
        $course_id = array();
        if ($dis_course_ids) {
            foreach ($dis_course_ids as $dis_course_id) {
                $course_id[] = $dis_course_id;
            }
        }

        $args = array(
            'post_type' => 'course',
            'posts_per_page' => -1,
            'post__in' => $course_id,
            'post_status' => 'published',
        );
    }

    $fetch = new WP_Query($args);

    if ($fetch->have_posts()) {
        while ($fetch->have_posts()) {
            $fetch->the_post();

            $course_ID = get_the_ID();
            $course_title = get_the_title($course_ID);
            $course_img = get_the_post_thumbnail_url($course_ID, "large");
            $course_link = get_the_permalink($course_ID);
            $course_excerpt = get_the_excerpt();

            $average_rating = get_post_meta($course_ID, 'average_rating', true);
            $countRating = get_post_meta($course_ID, 'rating_count', true);

            $units = bp_course_get_curriculum_units($course_ID);

            $taxonomy = 'course-cat';
            $terms = wp_get_post_terms($course_ID, $taxonomy, array('fields' => 'all'));

            $author_id = get_the_author_meta('ID');
            $author_name = get_the_author_meta('display_name', $author_id);
            $author_avatar_url = get_avatar_url($author_id, array('size' => 100));


            $duration = $total_duration = 0;

            foreach ($units as $unit) {
                $duration = get_post_meta($unit, 'vibe_duration', true);
                if (empty($duration)) {
                    $duration = 0;
                }
                if (get_post_type($unit) == 'unit') {
                    $unit_duration_parameter = apply_filters('vibe_unit_duration_parameter', 60, $unit);
                } elseif (get_post_type($unit) == 'quiz') {
                    $unit_duration_parameter = apply_filters('vibe_quiz_duration_parameter', 60, $unit);
                }
                $total_duration = $total_duration + $duration * $unit_duration_parameter;
            }

            if (!function_exists('convert_seconds_to_hours_minutes')) {
                function convert_seconds_to_hours_minutes($seconds)
                {
                    $hours = floor($seconds / 3600);
                    return sprintf('%02dh', $hours);
                }
            }
            $course_duration = convert_seconds_to_hours_minutes($total_duration);

?>

            <div class="dis-course-card">
                <div class="dis-course-card-normal-contents">

                    <a href="<?php echo esc_attr($course_link); ?>" class="dis-course-img">
                        <img src="<?php echo $course_img ?>" alt="Course Thumbnail">
                    </a>

                    <h6 class="dis-course-category">
                        <a href="<?php echo esc_url(get_term_link($terms[0])); ?>">
                            <?php echo esc_html($terms[0]->name); ?>
                        </a>
                    </h6>

                    <h3 class="dis-course-title">
                        <a href="<?php echo esc_attr($course_link); ?>">
                            <?php
                            echo esc_html($course_title);
                            ?>
                        </a>
                    </h3>

                    <h4 class="dis-course-author">
                        <?php echo esc_html($author_name) ?>
                    </h4>

                    <ul class="dis-course-features">
                        <li>
                            <i aria-hidden="true" class="fas fa-file-excel"></i>
                            <span class="dis-course-no-count">
                                <?php
                                echo count($units);
                                ?>
                            </span> Lessons
                        </li>

                        <li>
                            <i aria-hidden="true" class="fas fa-briefcase"></i>
                            Online Class
                        </li>
                    </ul>

                    <div class="dis-course-card-footer">
                        <p class="dis-course-fee">
                            <?php
                            bp_course_credits();
                            ?>
                        </p>

                        <ul class="dis-course-ratings">
                            <li class="dis-course-ratings-stars">

                                <?php
                                if (is_numeric($average_rating)) {
                                    $percentage = ($average_rating / 5) * 100;
                                    echo '<div class="star-ratings">

                                    <div class="fill-ratings" style="width:' . $percentage . '%;">
                                        <span>★★★★★</span>
                                    </div>

                                    <div class="empty-ratings">
                                        <span>★★★★★</span>
                                    </div>

                                </div>';

                                ?>

                                    <svg viewBox="0 0 1000 200" class="rating">
                                        <defs>
                                            <polygon id="star" points="100,0 131,66 200,76 150,128 162,200 100,166 38,200 50,128 0,76 69,66 ">
                                            </polygon>
                                            <clipPath id="stars">
                                                <use xlink:href="#star"></use>
                                                <use xlink:href="#star" x="20%"></use>
                                                <use xlink:href="#star" x="40%"></use>
                                                <use xlink:href="#star" x="60%"></use>
                                                <use xlink:href="#star" x="80%"></use>
                                            </clipPath>
                                        </defs>
                                        <rect class="rating__background" clip-path="url(#stars)"></rect>
                                        <rect width="<?php echo $percentage ?>%" class="rating__value" clip-path="url(#stars)">
                                        </rect>
                                    </svg>

                                <?php
                                }
                                ?>

                            </li>

                            <li class="dis-course-ratings-count">
                                (<?php
                                    echo $countRating;
                                    ?>)
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="dis-course-card-hover-contents">
                    <div class="dis-course-card-hover-contents-inner">

                        <div class="dis-course-wishlist">
                            <a href="javascript:void">
                                <i aria-hidden="true" class="far fa-heart"></i>
                            </a>
                        </div>

                        <h6 class="dis-course-category">
                            <a href="<?php echo esc_url(get_term_link($terms[0])); ?>">
                                <?php echo esc_html($terms[0]->name); ?>
                            </a>
                        </h6>

                        <h3 class="dis-course-title">
                            <a href="<?php echo esc_attr($course_link); ?>">
                                <?php
                                echo esc_html($course_title);
                                ?>
                            </a>
                        </h3>

                        <ul class="dis-course-author-and-ratings">
                            <li class="dis-course-author">
                                <div class="dis-course-author-img">
                                    <img src="<?php echo esc_url($author_avatar_url); ?>" alt="Course author">
                                </div>

                                <h4 class="dis-course-author-name">
                                    <?php echo esc_html($author_name) ?>
                                </h4>
                            </li>

                            <li class="dis-course-ratings">
                                <ul>
                                    <li class="dis-course-ratings-stars">

                                        <?php
                                        if (is_numeric($average_rating)) {
                                            $percentage = ($average_rating / 5) * 100;
                                            echo '<div class="star-ratings">

                                            <div class="fill-ratings" style="width:' . $percentage . '%;">
                                                <span>★★★★★</span>
                                            </div>

                                            <div class="empty-ratings">
                                                <span>★★★★★</span>
                                            </div>

                                            </div>';
                                        }
                                        ?>
                                    </li>

                                    <li class="dis-course-ratings-count">
                                        ( <?php
                                            echo $countRating;
                                            ?>)
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <p class="dis-course-details">
                            <?php echo $course_excerpt ?>
                        </p>

                        <ul class="dis-course-features">
                            <li>
                                <i class="fas fa-grip-vertical"></i>
                                All Levels
                            </li>

                            <li>
                                <i class="fas fa-bars"></i>
                                <span class="dis-course-no-count">
                                    <?php
                                    echo count($units);
                                    ?>
                                </span> Lessons
                            </li>

                            <li>
                                <i class="far fa-clock"></i>
                                <span class="dis-course-duration-count">
                                    <?php
                                    echo $course_duration;
                                    ?>
                                </span> Hours
                            </li>

                        </ul>

                        <div class="dis-course-card-footer">
                            <a href="<?php echo esc_attr($course_link); ?>" class="dis-course-btn">
                                See Details
                            </a>

                            <p class="dis-course-fee">

                                <?php
                                bp_course_credits();
                                ?>
                            </p>
                        </div>

                    </div>
                </div>
            </div>

<?php
        }
        wp_reset_query();
    } else {
        echo "no course found";
    }
    return ob_get_clean();
}
add_shortcode('dis_courses', 'dis_courses_shortcode');
