<?php 

if (@$_GET['debug'] == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(1);
}

add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles');
function salient_child_enqueue_styles() {
	
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array('font-awesome'));

    if ( is_rtl() ) 
     wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );
}

if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Theme Options',
        'menu_title' => 'Theme Options',
        'menu_slug' => 'theme-pptions',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}

// remove wp version param from any enqueued scripts
function vc_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'vc_remove_wp_ver_css_js', 9999 );

add_shortcode('daily_menus', 'codex_daily_menus');
function codex_daily_menus() {
    ob_start();
    // wp_reset_postdata();

    $today = date('l');
    $today = strtolower($today);
    echo 'today:'.$today;
    // GET DRINKS

    // if( have_rows('content', $pid) ):
    // while ( have_rows( 'content', $pid ) ) : the_row();
    //     echo 't3';
    //     echo get_row_layout();

    // endwhile;
    // else :
    //     echo 't6';
    // endif;
    $repeaterWeeks = get_field('page_builder', 348);

    foreach($repeaterWeeks as $week) { the_row();
        $layout = $week['acf_fc_layout'];
        if ($layout == 'featured_wines'){
            $heading = $week['main_title'];
            $repeater = $week['repeater'];
            //repeater
            // Check rows existexists.
            if($repeater): ?>    
                <?php
                foreach($repeater as $repeaters) { the_row(); 

        // Load sub field value.
                    $subtitle = $repeaters['subtitle'];
                    $days = $repeaters['days'];

                    $child_repeater = $repeaters['child_repeater'];
                    if ($days == 'Monday')
                        foreach($child_repeater as $child_repeaters) { the_row(); 

                           $main_title = $child_repeaters['main_title'];
                           $title = $child_repeaters['title'];
                           $price = $child_repeaters['price'];
                           $content = $child_repeaters['content'];

                    }// end child_repeatersloop
                }// end repeater foreach

// No value.
            else :
               echo 'repeater not found';
           endif;//end repeater
       }//end layout if

   }// end featured wine(repeaterWeeks)

    // GET DRINKS
   $daily_promo = get_field('daily_promo', 'option');
   echo '<section class="promo-wrapper">';
   if($daily_promo):
    foreach ($daily_promo as $key => $value) {
        $select_days    = $value['select_days']; 
        $main_title     = $value['main_title'];
        $subtitle       = $value['subtitle'];
        $subtitle_two   = $value['subtitle_two'];
        $price          = $value['price'];
        $child_repeater = $value['child_repeater'];

        if ($select_days) {
            if ($select_days == $today) {
                if($main_title):
                    echo '<div class="heading">';
                    printf('<h1>%s</h1>', $main_title);
                        echo '</div>'; //heading
                    endif; //$main_title End Variable

                    echo '<div class="content">';
                    if($subtitle && $subtitle_two):
                        echo '<div class="top-text">';
                        printf('<h4>%s</h4>', $subtitle);
                        printf('<h2>%s</h2>', $subtitle_two);
                            echo '</div>'; //top-text
                        endif; //$subtitle && $subtitle_two End Variable

                        if($price):
                            echo '<div class="price">';
                            printf('<h3>%s</h3>', $price);
                            echo '</div>'; //price
                        endif; //$price End Variable

                        if($child_repeater):
                            echo '<div class="bottom-text">';
                            foreach ($child_repeater as $key => $child) {
                                $title = $child['main_title'];
                                $child = $child['child'];
                                echo '<div class="box">';
                                if($title){ printf('<h2 class="box-title">%s</h2>', $title); }
                                if($child):
                                    foreach ($child as $key => $v) {
                                        $title_c = $v['title'];
                                        $content = $v['content'];
                                        echo '<div class="inner-area">';
                                        if($title_c){ printf('<h3>%s</h3>', $title_c); }
                                        echo $content;
                                        echo '</div>';
                                    }
                                        endif; //$child End Variable
                                    echo '</div>'; //box
                                }


    // GET DRINKS

                                $repeaterWeeks = get_field('page_builder', 348);

                                foreach($repeaterWeeks as $week) { the_row();
                                    $layout = $week['acf_fc_layout'];
                                    if ($layout == 'featured_wines'){
                                        $heading = $week['main_title'];
                                        $drink_repeater = $week['repeater'];
            //repeater
            // Check rows existexists.
                                        if($repeater): ?>    
                                            <?php
                                            foreach($drink_repeater as $repeaters) { the_row(); 

        // Load sub field value.
                                                $subtitle = $repeaters['subtitle'];
                                                $days = $repeaters['days'];
                                                echo '<br>days:'.$days;

                                                $drink_child_repeater = $repeaters['child_repeater'];
                                                if (strtolower($days) == strtolower($today)){
                                                    echo '<div class="box">';
                                                    if($heading){ printf('<h2 class="box-title">%s</h2>', $subtitle); }
                                                    foreach($drink_child_repeater as $drink_child_repeaters) { the_row(); 

                                                       $drink_main_title = $drink_child_repeaters['main_title'];
                                                       $drink_title = $drink_child_repeaters['title'];
                                                       $drink_price = $drink_child_repeaters['price'];
                                                       $drink_content = $drink_child_repeaters['content'];
                                                       echo '<div class="inner-area">';
                                                       printf( '<h3>%s %s %u</h3>', $drink_main_title, $drink_title, $drink_price );
                                                       echo $content;
                                                       echo '</div>';

                    }// end drink_child_repeatersloop
                    echo '</div>'; //box
                }// end days if
                }// end repeater foreach

// No value.
            else :
               echo 'repeater not found';
           endif;//end repeater
       }//end layout if

   }// end featured wine(repeaterWeeks)

    // GET DRINKS


                            echo '</div>'; //bottom-text
                        endif; //$child_repeater End Variable

                    echo '</div>'; //content

    }  // endif selected days == $today
    else if(end($select_days) && $select_days != $today)

        echo $today.'does not match any of the select_days';
            }//endif selected_days

            else
                echo 'select_days was not found';


        }
            endif; //$daily_promo End Variable
            echo '</section>';
            wp_reset_postdata();
            return ''.ob_get_clean();

        }

        function acf_load_select_days($field) {
            global $post;
    // reset choices
            $field['choices'] = array();

    // if has rows
            if (have_rows('week_days', 'option')) {
                if (!empty(get_post_meta($post->ID, 'select_days', true))) $field['default_value'] = array(
                    get_post_meta($post->ID, 'select_days', true)
                );

                    $field['choices'][''] = 'Select Days';

        // while has rows
                    while (have_rows('week_days', 'option')) {
            // instantiate row
                        the_row();

            // vars
                        $value = str_replace(' ', '_', get_sub_field('day'));
                        $value = sanitize_key($value);
                        $label = get_sub_field('day');

            // append to choices
                        $field['choices'][$value] = $label;
                    }
                }

    // return the field
                return $field;
            }

            add_filter('acf/load_field/name=select_days', 'acf_load_select_days', 999);
        ?>