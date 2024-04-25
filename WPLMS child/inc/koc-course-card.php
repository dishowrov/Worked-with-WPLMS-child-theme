<?php


function koc_card_shortcode($atts)
{

    $atts = shortcode_atts(
        array(
            'id' => '',
        ),
        $atts
    );
    ob_start();

    $course_id = $atts['id'];
    if (!empty($course_id)) {
        $course_ids = $course_id;
        $course_ids = (explode(",", $course_ids));
        $cid = array();
        if ($course_ids) {
            foreach ($course_ids as $course_id) {
                $cid[] = $course_id;
            }
        }

        $arg = array(
            "post_type" => "course",
            "posts_per_page" => 8,
            "post__in" => $cid,
            "post_status" => "published",
        );
    }

    $loop = new WP_Query($arg);
    if ($loop->have_posts()) {
        ?>
        <div class="r4h-courses__container">
            <?php
            while ($loop->have_posts()) {
                $loop->the_post();

                $course_ID = get_the_ID();
                $course_img = get_the_post_thumbnail_url($course_ID, "medium");
                $average_rating = get_post_meta($course_ID, 'average_rating', true);
                $countRating = get_post_meta($course_ID, 'rating_count', true);
                $course_title = get_the_title($course_ID);
                $units = bp_course_get_curriculum_units($course_ID);
                $courseStudents = get_post_meta($course_ID, 'vibe_students', true);
                $courseLink = get_the_permalink($course_ID);
                ?>
                <div class="r4h_course-card">
                    <div class="r4h-thumb">
                        <img src="<?php echo $course_img ?>" alt="" />
                    </div>
                    <div class="r4h-course-details">
                        <p class="r4h-post_date"><i class="fas fa-clock"></i>
                            <?php
                            echo get_the_date('d M, Y');
                            ?>
                        </p>
                        <p class="r4h-curriculum"><i class="fas fa-book"></i><?php
                        echo count($units);
                        ?> Curriculum</p>
                        <p class="r4h-started-users"><i class="fas fa-user"></i><?php
                        echo $courseStudents;
                        ?> Students</p>
                    </div>
                    <div class="r4h-course__contents">
                        <div class="r4h-details-inner">
                            <div class="r4h-metadata-holder">
                                <h5>
                                    <a href="<?php echo $courseLink ?>">
                                        <?php
                                        echo $course_title;
                                        ?>
                                    </a>
                                </h5>
                                <p class="r4h-description">
                                    <?php the_excerpt(); ?>
                                </p>
                            </div>
                        </div>
                        <div class="r4h-bottom-data">
                            <div class="r4h-coursedetail-price-details">
                                <span class="r4h-course__price">
                                    <!-- price replace start  -->
                                    <?php
                                    bp_course_credits();
                                    ?>
                                    <!-- price replace end  -->
                                </span>
                            </div>
                            <div class="r4h-ratings-container">
                                <p class="r4h-ratings">
                                    <?php
                                    if (is_numeric($average_rating)) {
                                        $percentage = ($average_rating / 5) * 100;


                                        ?>
                                        <svg viewBox="0 0 1000 200" class="rating">
                                            <defs>
                                                <polygon id="star"
                                                    points="100,0 131,66 200,76 150,128 162,200 100,166 38,200 50,128 0,76 69,66 ">
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
                                </p>
                                <p class="r4h-overall-ratings">

                                    <?php
                                    echo $countRating;
                                    ?>

                                </p>
                            </div>
                            <div class="r4h-coursedetail-cart-details">
                                <?php
                                $product_ID = get_post_meta($course_ID, 'vibe_product', true);
                                $add_to_cart_url = wc_get_cart_url() . '?add-to-cart=' . $product_ID;
                                ?>
                                <a href="<?php echo $add_to_cart_url ?>" class="r4h-button">Add to Cart</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
        wp_reset_query();
    } else {
        echo "No Course Found";
    }

    ?>




    <?php
    return ob_get_clean();
}

add_shortcode('koc_card', 'koc_card_shortcode');
?>