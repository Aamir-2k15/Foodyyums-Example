<?php
/**
 * Foodbakery functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package foodbakery
 */
require_once trailingslashit(get_template_directory()) . 'assets/frontend/translate/cs-strings.php';
require_once trailingslashit(get_template_directory()) . 'include/cs-global-functions.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-global-variables.php';
include(get_template_directory() . '/include/cs-theme-functions.php');
require_once(get_template_directory() . '/include/cs-helpers.php');

if (isset($_REQUEST['location'])) {
    $_REQUEST['location'] = filter_var($_REQUEST['location'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $_GET['location'] = filter_var($_REQUEST['location'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}
/**
 * Sets up theme defaults and registers support for various WordPress features.     *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
if (!function_exists('foodbakery_setup')) {

    function foodbakery_setup() {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ foodbakery.
         * If you're building a theme based on foodbakery, use a find and replace
         * to change 'foodbakery' to the name of your theme in all the template files.
         */
        load_theme_textdomain('foodbakery', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'primary' => esc_html__('Primary', 'foodbakery'),
            'strip_menu' => esc_html__('Top Strip Menu', 'foodbakery'),
            'fancy_left_menu' => esc_html__('Fancy Left Menu', 'foodbakery'),
            'fancy_right_menu' => esc_html__('Fancy Right Menu', 'foodbakery'),
            'header_mobile_menu' => esc_html__('Header Mobile Menu', 'foodbakery'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('foodbakery_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));
        /*
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, icons, and column width.
         */
        add_editor_style('assets/backend/css/editor-style.css');
        add_filter('the_password_form', 'foodbakery_password_form');

        // theme unique identifier
        if (class_exists('wp_foodbakery_framework')) {
            wp_foodbakery_framework::$theme_identify = 'wp-foodbakery-theme';
        }
        add_theme_support('woocommerce');
    }

    add_action('after_setup_theme', 'foodbakery_setup');
}

add_filter('comment_form_field_comment', 'foodbakery_form_field_comment', 10, 1);
add_action('comment_form_logged_in_after', 'foodbakery_comment_tut_fields');
add_action('comment_form_after_fields', 'foodbakery_comment_tut_fields');

function foodbakery_form_field_comment($field) {

    return '';
}

function foodbakery_comment_tut_fields() {

    $cs_msg_class = ' cs-message';
    if (is_user_logged_in()) {
        $cs_msg_class = ' cs-message';
    }
    $comment_field = '<textarea name="comment" class="commenttextarea" rows="55" cols="15"></textarea>';
    $html = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12' . $cs_msg_class . '"><div class="field-holder"><label>' . $comment_field . '</label></div></div>';
    echo foodbakery_allow_special_char($html);
}

/*
 * Include file function
 */
if (!function_exists('foodbakery_include_file')) {

    function foodbakery_include_file($file_path = '', $inc = false) {
        if ($file_path != '') {
            if ($inc == true) {
                include $file_path;
            } else {
                require_once $file_path;
            }
        }
    }

}

/*
 * Add images sizes for complete site
 *
 */

add_image_size('foodbakery_media_1', 750, 422, true); //Blog Large / Blog Detail
add_image_size('foodbakery_media_2', 213, 143, true); //Blog medium 16 x 9
/* Thumb size On Blogs On slider, blogs on restaurant, Candidate Detail Portfolio */
add_image_size('foodbakery_media_3', 236, 168, true);
add_image_size('foodbakery_media_4', 200, 200, true);
/* Thumb size On BEmployer Restaurant, Employer Restaurant View 2,Candidate Detail ,User Resume, company profile */
add_image_size('foodbakery_media_5', 180, 135, true);

if (function_exists('foodbakery_plugin_image_sizes')) {
    foodbakery_plugin_image_sizes();
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
if (!function_exists('foodbakery_content_width')) {

    function foodbakery_content_width() {
        $GLOBALS['content_width'] = apply_filters('foodbakery_content_width', 640);
    }

    add_action('after_setup_theme', 'foodbakery_content_width', 0);
}

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Auto Mobile 1.0
 */
if (!function_exists('foodbakery_widgets_init')) {

    function foodbakery_widgets_init() {

        global $foodbakery_var_options, $foodbakery_var_static_text;

        /**
         * @If Theme Activated
         */
        if (get_option('foodbakery_var_options')) {
            if (isset($foodbakery_var_options['foodbakery_var_sidebar']) && !empty($foodbakery_var_options['foodbakery_var_sidebar'])) {
                foreach ($foodbakery_var_options['foodbakery_var_sidebar'] as $sidebar) {
                    $sidebar_id = sanitize_title($sidebar);

                    $foodbakery_widget_start = '<div class="widget %2$s">';
                    $foodbakery_widget_end = '</div>';
                    if (isset($foodbakery_var_options['foodbakery_var_footer_widget_sidebar']) && $foodbakery_var_options['foodbakery_var_footer_widget_sidebar'] == $sidebar) {

                        $foodbakery_widget_start = '<aside class="widget col-lg-4 col-md-4 col-sm-6 col-xs-12 %2$s">';
                        $foodbakery_widget_end = '</aside>';
                    }
                    register_sidebar(array(
                        'name' => $sidebar,
                        'id' => $sidebar_id,
                        'description' => esc_html(foodbakery_var_theme_text_srt('foodbakery_var_widget_display_text')),
                        'before_widget' => $foodbakery_widget_start,
                        'after_widget' => $foodbakery_widget_end,
                        'before_title' => '<div class="widget-title"><h5>',
                        'after_title' => '</h5></div>'
                    ));
                }
            }

            $sidebar_name = '';
            if (isset($foodbakery_var_options['foodbakery_var_footer_sidebar']) && !empty($foodbakery_var_options['foodbakery_var_footer_sidebar'])) {
                $i = 0;
                foreach ($foodbakery_var_options['foodbakery_var_footer_sidebar'] as $foodbakery_var_footer_sidebar) {

                    $footer_sidebar_id = sanitize_title($foodbakery_var_footer_sidebar);
                    $sidebar_name = isset($foodbakery_var_options['foodbakery_var_footer_width']) ? $foodbakery_var_options['foodbakery_var_footer_width'] : '';
                    $foodbakery_sidebar_name = isset($sidebar_name[$i]) ? $sidebar_name[$i] : '';
                    $custom_width = str_replace('(', ' - ', $foodbakery_sidebar_name);
                    $foodbakery_widget_start = '<div class="widget %2$s">';
                    $foodbakery_widget_end = '</div>';

                    if (isset($foodbakery_var_options['foodbakery_var_footer_widget_sidebar']) && $foodbakery_var_options['foodbakery_var_footer_widget_sidebar'] == $foodbakery_var_footer_sidebar) {

                        $foodbakery_widget_start = '<aside class="widget col-lg-4 col-md-4 col-sm-6 col-xs-12 %2$s">';
                        $foodbakery_widget_end = '</aside>';
                    }

                    register_sidebar(array(
                        'name' => foodbakery_var_theme_text_srt('foodbakery_var_footer') . $foodbakery_var_footer_sidebar . ' ' . '(' . $custom_width . ' ',
                        'id' => $footer_sidebar_id,
                        'description' => foodbakery_var_theme_text_srt('foodbakery_var_widget_display_text'),
                        'before_widget' => $foodbakery_widget_start,
                        'after_widget' => $foodbakery_widget_end,
                        'before_title' => '<div class="widget-title"><h5>',
                        'after_title' => '</h5></div>'
                    ));
                    $i ++;
                }
            }
        } else {
            register_sidebar(array(
                'name' => foodbakery_var_theme_text_srt('foodbakery_var_widgets'),
                'id' => 'sidebar-1',
                'description' => foodbakery_var_theme_text_srt('foodbakery_var_widget_display_right_text'),
                'before_widget' => '<div class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="widget-title"><h6>',
                'after_title' => '</h6></div>'
            ));
        }
    }

    add_action('widgets_init', 'foodbakery_widgets_init');
}
/**
 * Add meta tag in head.
 */
if (!function_exists('foodbakery_add_meta_tags')) {

    function foodbakery_add_meta_tags() {

        echo '<meta http-equiv="X-UA-Compatible" content="IE=edge" />';
    }

    add_action('wp_head', 'foodbakery_add_meta_tags', 2);
}

/**
 * Enqueue scripts and styles.
 */
if (!function_exists('foodbakery_scripts_1')) {

    function foodbakery_scripts_1() {
        global $foodbakery_var_options;
        $foodbakery_responsive_site = isset($foodbakery_var_options['foodbakery_var_responsive']) ? $foodbakery_var_options['foodbakery_var_responsive'] : '';

        $theme_version = foodbakery_get_theme_version();
        wp_enqueue_style('iconmoon', trailingslashit(get_template_directory_uri()) . 'assets/common/icomoon/css/iconmoon.css');
        wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/frontend/css/bootstrap.css');
        wp_enqueue_style('bootstrap-theme', get_template_directory_uri() . '/assets/frontend/css/bootstrap-theme.css');
        wp_enqueue_style('chosen', trailingslashit(get_template_directory_uri()) . 'assets/frontend/css/chosen.css');
        wp_enqueue_style('swiper', trailingslashit(get_template_directory_uri()) . 'assets/frontend/css/swiper.css');
        wp_enqueue_style('animate', trailingslashit(get_template_directory_uri()) . 'assets/frontend/css/animate.css');
        wp_enqueue_style('foodbakery-style', get_stylesheet_uri());
        wp_enqueue_style('foodbakery-widget', get_template_directory_uri() . '/assets/frontend/css/widget.css');
        wp_enqueue_style('foodbakery-google-font', 'https://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600&subset=latin,cyrillic-ext');
        wp_enqueue_style('foodbakery-social-network', get_template_directory_uri() . '/assets/backend/css/social-network.css');

        wp_register_style('inline-style-functions', trailingslashit(get_template_directory_uri()) . 'assets/frontend/css/inline-style-functions.css');
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
        wp_enqueue_script('bootstrap-min', get_template_directory_uri() . '/assets/common/js/bootstrap.min.js', array('jquery'), $theme_version);
        wp_enqueue_script('modernizr', get_template_directory_uri() . '/assets/frontend/js/modernizr.js', array('jquery'), $theme_version, true);
        if ($foodbakery_responsive_site == 'on') {
            wp_enqueue_script('responsive-menu', get_template_directory_uri() . '/assets/frontend/js/responsive.menu.js', '', $theme_version, true);
        }
        //wp_enqueue_script( 'wow', get_template_directory_uri() . '/assets/frontend/js/wow.js', '', $theme_version, true );
        wp_enqueue_script('chosen', get_template_directory_uri() . '/assets/common/js/chosen.select.js', '', $theme_version);
        wp_enqueue_script('swiper', get_template_directory_uri() . '/assets/frontend/js/swiper.min.js', '', $theme_version, true);
        wp_enqueue_script('counter', get_template_directory_uri() . '/assets/frontend/js/counter.js', '', $theme_version, true);
        wp_enqueue_script('fliters', get_template_directory_uri() . '/assets/frontend/js/fliters.js', '', $theme_version, true);
        wp_enqueue_script('foodbakery-maps-styles', trailingslashit(get_template_directory_uri()) . 'assets/backend/js/cs-map_styles.js', '', $theme_version, true);
        wp_enqueue_script('fitvids', get_template_directory_uri() . '/assets/frontend/js/jquery.fitvids.js', '', $theme_version, true);
        wp_enqueue_script('matchHeight', get_template_directory_uri() . '/assets/frontend/js/jquery.matchHeight-min.js', '', $theme_version, true);
        wp_enqueue_script('foodbakery-functions', get_template_directory_uri() . '/assets/frontend/js/functions.js', '', $theme_version, true);
        wp_enqueue_script('skills-progress', get_template_directory_uri() . '/assets/frontend/js/skills-progress.js', '', $theme_version, true);
        wp_enqueue_script('masonry', get_template_directory_uri() . '/assets/frontend/js/masonry.pkgd.js', '', $theme_version, true);
        wp_register_script('growls', get_template_directory_uri() . '/assets/frontend/js/jquery.growl.js', '', $theme_version, true);
        if (class_exists('WooCommerce')) {
            if (is_woocommerce() || is_cart() || is_checkout()) {
                wp_enqueue_style('custom-woocommerce', trailingslashit(get_template_directory_uri()) . 'assets/frontend/css/woocommerce.css');
            }
        }

        if (!function_exists('foodbakery_enqueue_google_map')) {

            function foodbakery_enqueue_google_map() {
                wp_enqueue_script('foodbakery-google-map-script', 'https://maps.googleapis.com/maps/api/js', '', '');
            }

        }

        if (!function_exists('foodbakery_addthis_script_init_method')) {

            function foodbakery_addthis_script_init_method() {
                wp_enqueue_script('addthis', 'https://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e4412d954dccc64', '', '', true);
            }

        }

        if (!function_exists('foodbakery_inline_enqueue_script')) {

            function foodbakery_inline_enqueue_script($script = '', $script_handler = 'foodbakery-custom-inline') {
                wp_register_script('foodbakery-custom-inline', trailingslashit(get_template_directory_uri()) . 'assets/common/js/custom-inline.js', '', '', true);
                wp_enqueue_script($script_handler);
                wp_add_inline_script($script_handler, $script);
            }

        }

        if (!function_exists('foodbakery_var_dynamic_scripts')) {

            function foodbakery_var_dynamic_scripts($foodbakery_js_key, $foodbakery_arr_key, $foodbakery_js_code) {
                // Register the script
                wp_register_script('foodbakery-dynamic-scripts', trailingslashit(get_template_directory_uri()) . 'assets/frontend/js/inline-scripts-functions.js', '', '', true);
                // Localize the script
                $foodbakery_code_array = array(
                    $foodbakery_arr_key => $foodbakery_js_code
                );
                wp_localize_script('foodbakery-dynamic-scripts', $foodbakery_js_key, $foodbakery_code_array);
                wp_enqueue_script('foodbakery-dynamic-scripts');
            }

        }
    }

    add_action('wp_enqueue_scripts', 'foodbakery_scripts_1', 1);
}

/**
 * Enqueue Google Fonts.
 */
if (!function_exists('foodbakery_var_load_google_font_families')) {

    function foodbakery_var_load_google_font_families() {
        if (foodbakery_var_is_fonts_loaded()) {
            $fonts_array = foodbakery_var_is_fonts_loaded();
            $fonts_url = foodbakery_var_get_font_families($fonts_array);
            if ($fonts_url) {
                $font_url = add_query_arg('family', urlencode($fonts_url), "//fonts.googleapis.com/css");
                wp_enqueue_style('foodbakery-google-fonts', $font_url, array(), '');
            }
        }
    }

    add_action('wp_enqueue_scripts', 'foodbakery_var_load_google_font_families', 0);
}

if (!function_exists('foodbakery_inline_enqueue_style')) {

    function foodbakery_inline_enqueue_style($script = '', $script_handler = 'inline-style-functions') {
        wp_enqueue_style($script_handler);
        wp_add_inline_style($script_handler, $script);
    }

}

if (!function_exists('foodbakery_header_color_styles')) {

    function foodbakery_header_color_styles() {
        global $foodbakery_var_options;
        $custom_style_ver = (isset($foodbakery_var_options['foodbakery_var_theme_option_save_flag'])) ? $foodbakery_var_options['foodbakery_var_theme_option_save_flag'] : '';
        wp_enqueue_style('foodbakery-default-element-style', trailingslashit(get_template_directory_uri()) . '/assets/frontend/css/default-element.css', '', $custom_style_ver);
    }

    add_action('wp_enqueue_scripts', 'foodbakery_header_color_styles', 9);
}

/**
 * Enqueue scripts and styles.
 */
if (!function_exists('foodbakery_scripts_10')) {

    function foodbakery_scripts_10() {
        global $foodbakery_var_options;
        $foodbakery_responsive_site = isset($foodbakery_var_options['foodbakery_var_responsive']) ? $foodbakery_var_options['foodbakery_var_responsive'] : '';
        if (is_rtl()) {
            wp_enqueue_style('foodbakery-rtl', get_template_directory_uri() . '/assets/frontend/css/rtl.css');
        }
        if ($foodbakery_responsive_site == 'on') {
            $theme_version = foodbakery_get_theme_version();
            wp_enqueue_style('foodbakery_responsive_css', get_template_directory_uri() . '/assets/frontend/css/responsive.css', '', $theme_version);
        }
    }

    add_action('wp_enqueue_scripts', 'foodbakery_scripts_10', 10);
}

if (!function_exists('foodbakery_google_fonts')) {

    function foodbakery_google_fonts() {
        $query_args = array(
            'family' => 'Montserrat:400,700|Open+Sans:300,400,600,700,800',
            'subset' => '',
        );
        wp_register_style('foodbakery-google-fonts', add_query_arg($query_args, "//fonts.googleapis.com/css"), array(), null);
    }

    add_action('wp_enqueue_scripts', 'foodbakery_google_fonts');
}

/**
 * Add Admin Page for
 * Theme Options Menu
 */
if (!function_exists('foodbakery_var_options')) {

    add_action('admin_menu', 'foodbakery_var_options');

    function foodbakery_var_options() {
        global $foodbakery_var_static_text;
        $foodbakery_var_theme_options = foodbakery_var_theme_text_srt('foodbakery_var_theme_options');
        if (current_user_can('administrator')) {
            add_theme_page($foodbakery_var_theme_options, $foodbakery_var_theme_options, 'read', 'foodbakery_var_settings_page', 'foodbakery_var_settings_page');
        }
    }

}
/*
 * admin Enque Scripts
 */
if (!function_exists('foodbakery_var_admin_scripts_enqueue')) {

    function foodbakery_var_admin_scripts_enqueue() {
        $theme_version = foodbakery_get_theme_version();
        if (is_admin()) {
            global $foodbakery_var_template_path;
            $foodbakery_var_template_path = trailingslashit(get_template_directory_uri()) . 'assets/backend/js/cs-media-upload.js';
            wp_enqueue_style('fonticonpicker', trailingslashit(get_template_directory_uri()) . 'assets/common/icomoon/css/jquery.fonticonpicker.min.css', array(), $theme_version);
            wp_enqueue_style('fonticonpicker');
            wp_enqueue_style('iconmoon', trailingslashit(get_template_directory_uri()) . 'assets/common/icomoon/css/iconmoon.css');
            wp_enqueue_style('fonticonpicker-bootstrap', trailingslashit(get_template_directory_uri()) . 'assets/common/icomoon/theme/bootstrap-theme/jquery.fonticonpicker.bootstrap.css');
            wp_enqueue_style('chosen', trailingslashit(get_template_directory_uri()) . 'assets/backend/css/chosen.css');
            wp_enqueue_style('bootstrap', trailingslashit(get_template_directory_uri()) . 'assets/backend/css/bootstrap.css');
            wp_enqueue_style('foodbakery-admin-lightbox', trailingslashit(get_template_directory_uri()) . 'assets/backend/css/lightbox.css');
            wp_enqueue_style('foodbakery-admin-style', trailingslashit(get_template_directory_uri()) . 'assets/backend/css/admin-style.css');
            wp_enqueue_style('wp-color-picker');

            // all js script
            wp_enqueue_media();
            wp_enqueue_script('admin-upload', $foodbakery_var_template_path, array('jquery', 'media-upload', 'thickbox', 'jquery-ui-droppable', 'jquery-ui-datepicker', 'jquery-ui-slider', 'wp-color-picker'));
            wp_enqueue_script('fonticonpicker', trailingslashit(get_template_directory_uri()) . 'assets/common/icomoon/js/jquery.fonticonpicker.min.js');
            wp_enqueue_script('bootstrap', trailingslashit(get_template_directory_uri()) . 'assets/common/js/bootstrap.min.js', '', '', true);
            wp_enqueue_style('jquery_datetimepicker', trailingslashit(get_template_directory_uri()) . 'assets/backend/css/jquery_datetimepicker.css');
            wp_enqueue_style('datepicker', trailingslashit(get_template_directory_uri()) . 'assets/backend/css/datepicker.css');
            wp_enqueue_style('jquery_ui_datepicker_theme', trailingslashit(get_template_directory_uri()) . 'assets/backend/css/jquery_ui_datepicker_theme.css');
            wp_enqueue_script('jquery_datetimepicker', trailingslashit(get_template_directory_uri()) . 'assets/backend/js/jquery_datetimepicker.js');
            wp_enqueue_script('foodbakery-light-box-js', trailingslashit(get_template_directory_uri()) . 'assets/backend/js/light-box.js', '', '', true);
            wp_enqueue_script('foodbakery-theme-options', trailingslashit(get_template_directory_uri()) . 'assets/backend/js/cs-theme-option-fucntions.js', '', '', true);
            $foodbakery_theme_options_vars = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'theme_url' => get_template_directory_uri(),
            );
            wp_localize_script('foodbakery-theme-options', 'foodbakery_theme_options_vars', $foodbakery_theme_options_vars);
            wp_enqueue_script('chosen', trailingslashit(get_template_directory_uri()) . 'assets/common/js/chosen.select.js', '', '');
            wp_enqueue_script('foodbakery-custom-functions', trailingslashit(get_template_directory_uri()) . 'assets/backend/js/cs-fucntions.js', '', $theme_version, true);

            ////editor script
            wp_enqueue_script('jquery-te', trailingslashit(get_template_directory_uri()) . 'assets/backend/editor/js/jquery-te-1.4.0.min.js');
            wp_enqueue_style('jquery-te', trailingslashit(get_template_directory_uri()) . 'assets/backend/editor/css/jquery-te-1.4.0.css');

            if (!function_exists('foodbakery_admin_inline_enqueue_script')) {

                function foodbakery_admin_inline_enqueue_script($script = '', $script_handler = 'custom') {
                    wp_enqueue_script($script_handler);
                    wp_add_inline_script($script_handler, $script);
                }

            }
        }
    }

    add_action('admin_enqueue_scripts', 'foodbakery_var_admin_scripts_enqueue');
}

if (!function_exists('foodbakery_var_date_picker')) {

    function foodbakery_var_date_picker() {
        global $foodbakery_var_template_path;
        wp_enqueue_script('foodbakery-admin-upload', $foodbakery_var_template_path, array('jquery', 'media-upload'));
    }

}

/*
 * Get current theme version
 */
if (!function_exists('foodbakery_get_theme_version')) {

    function foodbakery_get_theme_version() {
        $my_theme = wp_get_theme();
        $theme_version = $my_theme->get('Version');
        return $theme_version;
    }

}

/**
 * Default Pages title.
 *
 * @since Auto Mobile 1.0
 */
if (!function_exists('foodbakery_post_page_title')) {

    function foodbakery_post_page_title() {

        $foodbakery_var_search_result = foodbakery_var_theme_text_srt('foodbakery_var_search_result');
        $foodbakery_var_author = foodbakery_var_theme_text_srt('foodbakery_var_author');
        $foodbakery_var_archives = foodbakery_var_theme_text_srt('foodbakery_var_archives');
        $foodbakery_var_daily_archives = foodbakery_var_theme_text_srt('foodbakery_var_daily_archives');
        $foodbakery_var_monthly_archives = foodbakery_var_theme_text_srt('foodbakery_var_monthly_archives');
        $foodbakery_var_yearly_archives = foodbakery_var_theme_text_srt('foodbakery_var_yearly_archives');
        $foodbakery_var_tags = foodbakery_var_theme_text_srt('foodbakery_var_tags');
        $foodbakery_var_category = foodbakery_var_theme_text_srt('foodbakery_var_category');
        $foodbakery_var_error_404 = foodbakery_var_theme_text_srt('foodbakery_var_error_404');
        $foodbakery_var_home = foodbakery_var_theme_text_srt('foodbakery_var_home');

        if (!is_page() && !is_singular() && !is_search() && !is_404() && !is_front_page()) {
            if (function_exists('is_shop') && !is_shop()) {
                the_archive_title();
            } else {
                the_archive_title();
            }
        } elseif (is_search()) {
            printf($foodbakery_var_search_result, '<span>' . get_search_query() . '</span>');
        } elseif (is_404()) {
            echo esc_attr($foodbakery_var_error_404);
        } elseif (is_home()) {
            echo esc_html($foodbakery_var_home);
        } elseif (is_page() || is_singular()) {
            echo get_the_title();
        } elseif (function_exists('is_shop') && is_shop()) {
            $foodbakery_var_post_ID = wc_get_page_id('shop');
            echo get_the_title($foodbakery_var_post_ID);
        }
    }

}

/**
 * @Breadcrumb Function
 *
 */
if (!function_exists('foodbakery_breadcrumbs')) {

    function foodbakery_breadcrumbs($foodbakery_border = '') {
        global $wp_query, $foodbakery_var_options, $post, $foodbakery_var_static_text;
        /* === OPTIONS === */
        $foodbakery_var_current_page = foodbakery_var_theme_text_srt('foodbakery_var_current_page');
        $foodbakery_var_error_404 = foodbakery_var_theme_text_srt('foodbakery_var_error_404');
        $foodbakery_var_home = foodbakery_var_theme_text_srt('foodbakery_var_home');
        $text['home'] = esc_html($foodbakery_var_home); // text for the 'Home' link
        $text['category'] = '%s'; // text for a category page
        $text['search'] = '%s'; // text for a search results page
        $text['tag'] = '%s'; // text for a tag page
        $text['author'] = '%s'; // text for an author page
        $text['404'] = esc_attr($foodbakery_var_error_404); // text for the 404 page
        $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
        $showOnHome = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
        $delimiter = ''; // delimiter between crumbs
        $before = '<li class="active">'; // tag before the current crumb
        $after = '</li>'; // tag after the current crumb
        /* === END OF OPTIONS === */
        $current_page = $foodbakery_var_current_page;
        $homeLink = home_url() . '/';
        $linkBefore = '<li>';
        $linkAfter = '</li>';
        $linkAttr = '';
        $link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;
        $linkhome = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;
        $foodbakery_border_style = $foodbakery_border != '' ? ' style="border-top: 1px solid ' . $foodbakery_border . ';"' : '';
        if (is_home() || is_front_page()) {
            if ($showOnHome == "1")
                echo '<div' . foodbakery_allow_special_char($foodbakery_border_style) . ' class="breadcrumbs align-left"><div class="container"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><ul>' . foodbakery_allow_special_char($before) . '<a href="' . esc_url($homeLink) . '">' . esc_html($text['home']) . '</a>' . foodbakery_allow_special_char($after) . '</ul></div></div></div></div>';
        } else {
            echo '<div' . foodbakery_allow_special_char($foodbakery_border_style) . ' class="breadcrumbs align-left"><div class="container"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <ul>' . sprintf($linkhome, $homeLink, $text['home']) . foodbakery_allow_special_char($delimiter);
            if (is_category()) {
                $thisCat = get_category(get_query_var('cat'), false);
                if ($thisCat->parent != 0) {
                    $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                    $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                    $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                    echo foodbakery_allow_special_char($cats);
                }
                echo foodbakery_allow_special_char($before) . sprintf($text['category'], single_cat_title('', false)) . foodbakery_allow_special_char($after);
            } elseif (is_search()) {
                echo foodbakery_allow_special_char($before) . sprintf($text['search'], get_search_query()) . foodbakery_allow_special_char($after);
            } elseif (is_day()) {
                echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . foodbakery_allow_special_char($delimiter);
                echo sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F')) . foodbakery_allow_special_char($delimiter);
                echo foodbakery_allow_special_char($before) . get_the_time('d') . foodbakery_allow_special_char($after);
            } elseif (is_month()) {
                echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . foodbakery_allow_special_char($delimiter);
                echo foodbakery_allow_special_char($before) . get_the_time('F') . foodbakery_allow_special_char($after);
            } elseif (is_year()) {
                echo foodbakery_allow_special_char($before) . get_the_time('Y') . foodbakery_allow_special_char($after);
            } elseif (is_single() && !is_attachment()) {
                if (function_exists("is_shop") && get_post_type() == 'product') {
                    $foodbakery_shop_page_id = wc_get_page_id('shop');
                    $current_page = get_the_title(get_the_id());
                    $foodbakery_shop_page = "<li><a href='" . esc_url(get_permalink($foodbakery_shop_page_id)) . "'>" . get_the_title($foodbakery_shop_page_id) . "</a></li>";
                    echo foodbakery_allow_special_char($foodbakery_shop_page);
                    if ($showCurrent == 1)
                        echo foodbakery_allow_special_char($before) . esc_html($current_page) . foodbakery_allow_special_char($after);
                }
                else if (get_post_type() != 'post') {
                    $post_type = get_post_type_object(get_post_type());
                    $slug = $post_type->rewrite;
                    printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
                    if ($showCurrent == 1)
                        echo foodbakery_allow_special_char($delimiter) . foodbakery_allow_special_char($before) . esc_html($current_page) . foodbakery_allow_special_char($after);
                } else {
                    $cat = get_the_category();
                    $cat = $cat[0];
                    $cats = get_category_parents($cat, TRUE, $delimiter);
                    if ($showCurrent == 0)
                        $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                    $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                    $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                    echo foodbakery_allow_special_char($cats);
                    if ($showCurrent == 1)
                        echo foodbakery_allow_special_char($before) . esc_html($current_page) . foodbakery_allow_special_char($after);
                }
            } elseif (!is_single() && !is_page() && get_post_type() <> '' && get_post_type() != 'post' && !is_404()) {
                $post_type = get_post_type_object(get_post_type());
                echo foodbakery_allow_special_char($before) . $post_type->labels->singular_name . foodbakery_allow_special_char($after);
            } elseif (isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy'])) {
                $taxonomy = $taxonomy_category = '';
                $taxonomy = $wp_query->query_vars['taxonomy'];
                echo foodbakery_allow_special_char($before) . esc_html($taxonomy) . foodbakery_allow_special_char($after);
            } elseif (is_page() && !$post->post_parent) {
                if ($showCurrent == 1)
                    echo foodbakery_allow_special_char($before) . get_the_title() . foodbakery_allow_special_char($after);
            } elseif (is_page() && $post->post_parent) {
                $parent_id = $post->post_parent;
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i ++) {
                    echo foodbakery_allow_special_char($breadcrumbs[$i]);
                    if ($i != count($breadcrumbs) - 1)
                        echo foodbakery_allow_special_char($delimiter);
                }
                if ($showCurrent == 1)
                    echo foodbakery_allow_special_char($delimiter . $before . get_the_title() . $after);
            } elseif (is_tag()) {

                echo foodbakery_allow_special_char($before) . sprintf($text['tag'], single_tag_title('', false)) . foodbakery_allow_special_char($after);
            } elseif (is_author()) {
                global $author;
                $userdata = get_userdata($author);
                echo foodbakery_allow_special_char($before) . sprintf($text['author'], $userdata->display_name) . foodbakery_allow_special_char($after);
            } elseif (is_404()) {
                echo foodbakery_allow_special_char($before) . esc_html($text['404']) . foodbakery_allow_special_char($after);
            }
            echo '</ul></div></div></div></div>';
        }
    }

}

/**
 * Including the required files
 *
 * @since Foodbakery 1.0
 */
if (!function_exists('foodbakery_require_theme_files')) {

    function foodbakery_require_theme_files($foodbakery_path = '') {
        global $wp_filesystem;
        $backup_url = '';
        if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {
            return true;
        }
        if (!WP_Filesystem($creds)) {
            request_filesystem_credentials($backup_url, '', true, false, array());
            return true;
        }
        $foodbakery_sh_front_dir = trailingslashit(get_template_directory()) . $foodbakery_path;
        $foodbakery_sh_front_dir = str_replace(ABSPATH, $wp_filesystem->abspath(), $foodbakery_sh_front_dir);
        $foodbakery_all_f_list = $wp_filesystem->dirlist($foodbakery_sh_front_dir);
        if (is_array($foodbakery_all_f_list) && sizeof($foodbakery_all_f_list) > 0) {
            foreach ($foodbakery_all_f_list as $file_key => $file_val) {
                if (isset($file_val['name'])) {
                    $foodbakery_file_name = $file_val['name'];
                    $foodbakery_ext = pathinfo($foodbakery_file_name, PATHINFO_EXTENSION);
                    if ($foodbakery_ext == 'php') {
                        require_once trailingslashit(get_template_directory()) . $foodbakery_path . $foodbakery_file_name;
                    }
                }
            }
        }
    }

}
/**
 * @Custom CSS
 *
 */
if (!function_exists('foodbakery_write_stylesheet_content')) {

    function foodbakery_write_stylesheet_content() {
        global $wp_filesystem, $foodbakery_var_options;
        require_once get_template_directory() . '/include/frontend/cs-theme-styles.php';
        $foodbakery_export_options = foodbakery_var_custom_style_theme_options();
        $fileStr = $foodbakery_export_options;
        $regex = array(
            "`^([\t\s]+)`ism" => '',
            "`^\/\*(.+?)\*\/`ism" => "",
            //"`([\n\A;]+)\/\*(.+?)\*\/`ism"=>"$1",
            //"`([\n\A;\s]+)\/(.+?)[\n\r]`ism"=>"$1\n",
            "`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism" => "\n"
        );
        $newStr = preg_replace(array_keys($regex), $regex, $fileStr);

        $foodbakery_option_fields = $newStr;
        $backup_url = wp_nonce_url('themes.php?page=foodbakery_var_settings_page');
        if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {
            return true;
        }
        if (!WP_Filesystem($creds)) {
            request_filesystem_credentials($backup_url, '', true, false, array());
            return true;
        }
        $foodbakery_upload_dir = get_template_directory() . '/assets/frontend/css/';
        $foodbakery_filename = trailingslashit($foodbakery_upload_dir) . 'default-element.css';
        if (!$wp_filesystem->put_contents($foodbakery_filename, $foodbakery_option_fields, FS_CHMOD_FILE)) {
            
        }
    }

}




/**
 * stripslashes string
 *
 * @since Auto Mobile 1.0
 */
if (!function_exists('foodbakery_var_stripslashes_htmlspecialchars')) {

    function foodbakery_var_stripslashes_htmlspecialchars($value) {
        $value = is_array($value) ? array_map('foodbakery_var_stripslashes_htmlspecialchars', $value) : stripslashes(htmlspecialchars($value));
        return $value;
    }

}

require_once ABSPATH . '/wp-admin/includes/file.php';

/**
 * Mega Menu.
 */
require_once trailingslashit(get_template_directory()) . 'include/mega-menu/custom-walker.php';
require_once trailingslashit(get_template_directory()) . 'include/mega-menu/edit-custom-walker.php';
require_once trailingslashit(get_template_directory()) . 'include/mega-menu/menu-functions.php';


/**
 * Implement the Custom Header feature.
 */
require_once trailingslashit(get_template_directory()) . 'include/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require_once trailingslashit(get_template_directory()) . 'include/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require_once trailingslashit(get_template_directory()) . 'include/extras.php';

/*
 * Inlcude themes required files for theme options
 */
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-custom-fields/cs-form-fields.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-custom-fields/cs-html-fields.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-admin-functions.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/importer-hooks.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-googlefont/cs-fonts-array.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-googlefont/cs-fonts.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-googlefont/cs-fonts-functions.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/import/cs-class-widget-data.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-theme-options/cs-theme-options.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-theme-options/cs-theme-options-functions.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-theme-options/cs-theme-options-fields.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-theme-options/cs-theme-options-arrays.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-activation-plugins/cs-require-plugins.php';
require_once trailingslashit(get_template_directory()) . 'include/cs-class-parse.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/theme-config.php';
require_once trailingslashit(get_template_directory()) . 'include/frontend/cs-header.php';
require_once trailingslashit(get_template_directory()) . 'include/frontend/class-pagination.php';
require_once trailingslashit(get_template_directory()) . 'template-parts/blog/blog_element.php';
require_once trailingslashit(get_template_directory()) . 'template-parts/blog/blog_functions.php';
/*
 * Inlcude theme classes files
 */
require_once trailingslashit(get_template_directory()) . 'include/backend/classes/class-category-meta.php';
/*
 * Inlcude theme required files for widgets
 */
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-custom-menu.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-flickr.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-author.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-ads.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-mailchimp.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-twitter.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-facebook.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-recent-posts.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-contact-info.php';
if (class_exists('woocommerce')) {
    require_once trailingslashit(get_template_directory()) . 'include/backend/cs-woocommerce/cs-config.php';
}

/*
 * Include Top Strip File
 */
require_once trailingslashit(get_template_directory()) . 'include/frontend/cs-top-strip.php';
if (!function_exists('foodbakery_include_shortcodes')) {

    /**
     * Include shortcodes backend and frontend as well.
     */
    function foodbakery_include_shortcodes() {
        /* shortcodes files */
        foodbakery_require_theme_files('include/backend/cs-shortcodes/');
        foodbakery_require_theme_files('include/frontend/cs-shortcodes/');
    }

    add_action('init', 'foodbakery_include_shortcodes', 1);
}


/**
 * Social Networks Detail
 *
 * @param string $icon_type Icon Size.
 * @param string $tooltip Description.
 *
 */
if (!function_exists('foodbakery_social_network')) {

    function foodbakery_social_network($header9, $icon_type = '', $tooltip = '', $ul_class = '', $no_title = true) {
        global $foodbakery_var_options;
        $html = '';
        $tooltip_data = '';
        if ($icon_type == 'large') {
            $icon = 'icon-2x';
        } else {

            $icon = '';
        }
        if (isset($tooltip) && $tooltip <> '') {
            $tooltip_data = 'data-placement-tooltip="tooltip"';
        }
        if (isset($foodbakery_var_options['foodbakery_var_social_net_url']) and count($foodbakery_var_options['foodbakery_var_social_net_url']) > 0) {
            $i = 0;

            $html .= '<ul class="' . $ul_class . '">';
            if (is_array($foodbakery_var_options['foodbakery_var_social_net_url'])):
                foreach ($foodbakery_var_options['foodbakery_var_social_net_url'] as $val) {
                    if ('' !== $val) {
                        if ($no_title == false) {
                            $data_original_title = '';
                        } else {
                            $data_original_title = $foodbakery_var_options['foodbakery_var_social_net_tooltip'][$i];
                        }
                        $html .= '<li>';
                        $html .= '<a href="' . $val . '" data-original-title="' . $data_original_title . '" data-placement="top" ' . foodbakery_allow_special_char($tooltip_data, false) . ' class="colrhover"  target="_blank">';
                        if ($foodbakery_var_options['foodbakery_var_social_net_awesome'][$i] <> '' && isset($foodbakery_var_options['foodbakery_var_social_net_awesome'][$i])) {
                            $html .= '<i class="' . $foodbakery_var_options['foodbakery_var_social_net_awesome'][$i] . $icon . '"></i>';
                        } else {
                            $html .= '<img title="' . $foodbakery_var_options['foodbakery_var_social_net_tooltip'][$i] . '" src="' . $foodbakery_var_options['foodbakery_var_social_icon_path_array'][$i] . '" alt="' . $foodbakery_var_options['foodbakery_var_social_net_tooltip'][$i] . '" />';
                        }
                        $html .= '</a>
                            </li>';
                    }
                    $i ++;
                }
            endif;
            $html .= '</ul>';
        }
        if ($header9 == 1) {
            return $html;
        } else {
            echo foodbakery_allow_special_char($html);
        }
    }

}
/**
 * @Get sidebar name id
 *
 */
if (!function_exists('foodbakery_get_sidebar_id')) {

    function foodbakery_get_sidebar_id($foodbakery_page_sidebar_left = '') {

        return sanitize_title($foodbakery_page_sidebar_left);
    }

}
//if (class_exists('RevSlider')) {
//
//    class foodbakery_var_RevSlider extends RevSlider {
//	/*
//	 * Get sliders alias, Title, ID
//	 */
//	public function getAllSliderAliases() {
//	    $where = "";
//	    $response = $this->db->fetch(GlobalsRevSlider::$table_sliders, $where, "id");
//	    $arrAliases = array();
//	    $slider_array = array();
//	    foreach ($response as $arrSlider) {
//		$arrAliases['id'] = $arrSlider["id"];
//		$arrAliases['title'] = $arrSlider["title"];
//		$arrAliases['alias'] = $arrSlider["alias"];
//		$slider_array[] = $arrAliases;
//	    }
//	    return($slider_array);
//	}
//
//    }
//
//}

/* Start function for RevSlider Extend Class
 */
if (class_exists('RevSlider')) {

    class foodbakery_var_RevSlider extends RevSlider {
        /*
         * Get sliders alias, Title, ID
         */

        public function getAllSliderAliases() {
            $arrAliases = array();
            $slider_array = array();

            $slider = new RevSlider();

            if (method_exists($slider, "get_sliders")) {
                $slider = new RevSlider();
                $objSliders = $slider->get_sliders();

                foreach ($objSliders as $arrSlider) {
                    $arrAliases['id'] = $arrSlider->id;
                    $arrAliases['title'] = $arrSlider->title;
                    $arrAliases['alias'] = $arrSlider->alias;
                    $slider_array[] = $arrAliases;
                }
            } else {
                $where = "";
                $response = $this->db->fetch(GlobalsRevSlider::$table_sliders, $where, "id");
                foreach ($response as $arrSlider) {
                    $arrAliases['id'] = $arrSlider["id"];
                    $arrAliases['title'] = $arrSlider["title"];
                    $arrAliases['alias'] = $arrSlider["alias"];
                    $slider_array[] = $arrAliases;
                }
            }
            return($slider_array);
        }

    }

}

/**
 * Start Function Allow Special Character
 */
if (!function_exists('foodbakery_allow_special_char')) {

    function foodbakery_allow_special_char($input = '') {
        $output = $input;
        return $output;
    }

}

if (!function_exists('foodbakery_get_excerpt')) {

    function foodbakery_get_excerpt($wordslength = '', $readmore = 'true', $readmore_text = 'Read More') {
        global $post, $foodbakery_var_options;
        if ($wordslength == '') {
            $wordslength = $foodbakery_var_options['foodbakery_var_excerpt_length'] ? $foodbakery_var_options['foodbakery_var_excerpt_length'] : '30';
        }
        $excerpt = trim(preg_replace('/<a[^>]*>(.*)<\/a>/iU', '', get_the_content()));

        if ($readmore == 'true') {
            $more = ' <a href="' . esc_url(get_permalink($post->ID)) . '">' . esc_html($readmore_text) . '</a>';
        } else {
            $more = '...';
        }
        $excerpt_new = wp_trim_words($excerpt, $wordslength, $more);
        return $excerpt_new;
    }

}

//Posts title limit count

if (!function_exists('foodbakery_get_post_excerpt')) {

    function foodbakery_get_post_excerpt($string, $wordslength = '') {
        global $post;
        if ($wordslength == '') {
            $wordslength = '500';
        }
        $excerpt = trim(preg_replace('/<a[^>]*>(.*)<\/a>/iU', '', $string));
        $excerpt_new = wp_trim_words($excerpt, $wordslength, ' ...');
        return $excerpt_new;
    }

}

if (!function_exists('foodbakery_var_get_pagination')) {

    function foodbakery_var_get_pagination($total_pages = 1, $page = 1, $shortcode_paging = '') {
        global $foodbakery_var_static_text;
        $strings = new foodbakery_theme_all_strings;
        $strings->foodbakery_short_code_strings();
        $query_string = cs_get_server_data('QUERY_STRING');
        $base = get_permalink() . '?' . remove_query_arg($shortcode_paging, $query_string) . '%_%';
        $foodbakery_var_pagination = paginate_links(array(
            'base' => @add_query_arg($shortcode_paging, '%#%'),
            'format' => '&' . $shortcode_paging . '=%#%', // this defines the query parameter that will be used, in this case "p"
            'prev_text' => '<i class="icon-long-arrow-left"></i> ' . esc_html(foodbakery_var_theme_text_srt('foodbakery_var_prev')), // text for previous page
            'next_text' => esc_html(foodbakery_var_theme_text_srt('foodbakery_var_next')) . ' <i class="icon-long-arrow-right"></i>', // text for next page
            'total' => $total_pages, // the total number of pages we have
            'current' => $page, // the current page
            'end_size' => 1,
            'mid_size' => 2,
            'type' => 'array',
        ));
        $foodbakery_var_pages = '';
        if (is_array($foodbakery_var_pagination) && sizeof($foodbakery_var_pagination) > 0) {
            $foodbakery_var_pages .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            $foodbakery_var_pages .= '<nav>';
            $foodbakery_var_pages .= '<ul class="pagination">';
            foreach ($foodbakery_var_pagination as $foodbakery_var_link) {
                if (strpos($foodbakery_var_link, 'current') !== false) {
                    $foodbakery_var_pages .= '<li><a class="active">' . preg_replace("/[^0-9]/", "", $foodbakery_var_link) . '</a></li>';
                } else {
                    $foodbakery_var_pages .= '<li>' . $foodbakery_var_link . '</li>';
                }
            }
            $foodbakery_var_pages .= '</ul>';
            $foodbakery_var_pages .= ' </nav>';
            $foodbakery_var_pages .= '</div>';
        }
        echo foodbakery_allow_special_char($foodbakery_var_pages);
    }

}

if (!function_exists('foodbakery_get_posts_ajax_callback')) {

    function foodbakery_get_posts_ajax_callback() {
        $category = isset($_POST['category']) ? $_POST['category'] : '';
        $posts = array();
        if ($category != '') {
            
        }
        echo json_encode(array('status' => true, 'posts' => $posts));
        wp_die();
    }

    add_action("wp_ajax_foodbakery_get_posts", 'foodbakery_get_posts_ajax_callback');
}

function foodbakery_var_get_attachment_id($attachment_url) {
    global $wpdb;
    $attachment_id = false;
    // If there is no url, return.
    if ('' == $attachment_url)
        return;
    // Get the upload foodbakery paths
    $upload_dir_paths = wp_upload_dir();
    if (false !== strpos($attachment_url, $upload_dir_paths['baseurl'])) {
        // If this is the URL of an auto-generated thumbnail, get the URL of the original image
        $attachment_url = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url);
        // Remove the upload path base foodbakery from the attachment URL
        $attachment_url = str_replace($upload_dir_paths['baseurl'] . '/', '', $attachment_url);

        $attachment_id = $wpdb->get_var($wpdb->prepare("SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url));
    }
    return $attachment_id;
}

/*
 * Wordpress default gallery customization
 */
if (!function_exists('foodbakery_custom_format_gallery')) {
    add_filter('post_gallery', 'foodbakery_custom_format_gallery', 10, 2);

    function foodbakery_custom_format_gallery($string, $attr) {
        $output = "";
        if (isset($attr['ids'])) {
            $output = "<div class='post-gallery'>";
            $posts = get_posts(array('include' => $attr['ids'], 'post_type' => 'attachment'));
            foreach ($posts as $imagePost) {
                $output .= '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"><div class="media-holder"><figure><img src="' . wp_get_attachment_image_src($imagePost->ID, 'foodbakery_media_1')[0] . '" alt="foodbakery_media_1"></figure></div></div>';
            }
            $output .= "</div>";
        }
        return $output;
    }

}

if (function_exists('foodbakery_var_short_code')) {
    foodbakery_var_short_code('widget', 'foodbakery_widget_shortcode');
}

if (!function_exists('foodbakery_widget_shortcode')) {

    function foodbakery_widget_shortcode($atts) {
        $a = shortcode_atts(array(
            'name' => 'something',
                ), $atts);

        echo esc_html($a['name']);
        $params = array($a['name']);
        dynamic_sidebar($a['name']);
        the_widget('WP_Widget_Archives');
    }

}
/*
  password form
 */
if (!function_exists('foodbakery_password_form')) {

    function foodbakery_password_form() {
        global $post, $foodbakery_var_options, $foodbakery_var_form_fields;
        $cs_password_opt_array = array(
            'std' => '',
            'id' => '',
            'classes' => '',
            'extra_atr' => ' size="20"',
            'cust_id' => 'password_field',
            'cust_name' => 'post_password',
            'return' => true,
            'required' => false,
            'cust_type' => 'password',
        );

        $cs_submit_opt_array = array(
            'std' => esc_html__("Submit", 'foodbakery'),
            'id' => '',
            'classes' => 'bgcolr',
            'extra_atr' => '',
            'cust_id' => '',
            'cust_name' => 'Submit',
            'return' => true,
            'required' => false,
            'cust_type' => 'submit',
        );
        $label = 'pwbox-' . ( empty($post->ID) ? rand() : $post->ID );
        $o = '<div class="password_protected">
                <div class="protected-icon"><a href="#"><i class="icon-unlock-alt icon-4x"></i></a></div>
                <h3>' . esc_html__("This post is password protected. To view it please enter your password below:", 'foodbakery') . '</h3>';
        $o .= '<form action="' . esc_url(site_url('wp-login.php?action=postpass', 'login_post')) . '" method="post"><label>'
                . $foodbakery_var_form_fields->foodbakery_var_form_text_render($cs_password_opt_array)
                . '</label>'
                . $foodbakery_var_form_fields->foodbakery_var_form_text_render($cs_submit_opt_array)
                . '</form>
            </div>';
        return $o;
    }

}


/*
 * default pagination
 */
/*
 * start function for custom pagination
 */


if (!function_exists('foodbakery_default_pagination')) :

    /**
     * Display navigation to next/previous set of posts when applicable.
     * Based on paging nav function from Twenty Fourteen
     */
    function foodbakery_default_pagination() {
        // Don't print empty markup if there's only one page.
        if ($GLOBALS['wp_query']->max_num_pages < 2) {
            return;
        }

        $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
        $pagenum_link = html_entity_decode(get_pagenum_link());
        $query_args = array();
        $url_parts = explode('?', $pagenum_link);

        if (isset($url_parts[1])) {
            wp_parse_str($url_parts[1], $query_args);
        }

        $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
        $pagenum_link = trailingslashit($pagenum_link) . '%_%';

        $format = $GLOBALS['wp_rewrite']->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
        $format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit('page/%#%', 'paged') : '?paged=%#%';

        // Set up paginated links.
        $links = paginate_links(array(
            'base' => $pagenum_link,
            'format' => $format,
            'total' => $GLOBALS['wp_query']->max_num_pages,
            'current' => $paged,
            'mid_size' => 3,
            'add_args' => array_map('urlencode', $query_args),
            'prev_text' => esc_html__('Prev', 'foodbakery'),
            'next_text' => esc_html__('Next', 'foodbakery'),
            'type' => 'list',
        ));
        if ($links) :
            ?>
            <nav class="default-pagination" role="navigation">
                <?php echo foodbakery_allow_special_char($links); ?>
            </nav><!-- navigation -->
            <?php
        endif;
    }

endif;

/*
 * For SVG  images upload
 */

function foodbakery_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

add_filter('upload_mimes', 'foodbakery_mime_types');


if (!function_exists('pre')) {

    function pre($data, $is_exit = true) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        if ($is_exit == true) {
            exit;
        }
    }

}

if (!function_exists('tags_balnce_func_theme')) {

    function tags_balnce_func_theme($return = '') {
        if (function_exists('tags_balnce_func')) {
            return tags_balnce_func($return, true);
        } else {
            return $return;
        }
    }

}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///                                                     Code Section
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

add_action("wp_ajax_ez_check_email_existance", "ez_check_email_existance");

add_action("wp_ajax_nopriv_ez_check_email_existance", "ez_check_email_existance");

function ez_check_email_existance() {
//    print_r($_REQUEST);
//    die();
    if (email_exists($_REQUEST['Email']) != false) {
        echo 1;
        exit();
    } else {
        echo 0;
        exit();
    }
}

function ez_scripts() {
    ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="<?= get_template_directory_uri() ?>/wcfm/products-manager/ez_js_functions.js"></script>
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/wcfm/food-menu.css"/>
    <?php
}

function loading() {
    echo '<div class="ml-auto mr-auto text-center"><div class="spinner-border text-info"></div><h2 class="mt-4 text-info">Please wait...</h2></div>';
}

/* * * * * * * * * * * * *
 * || ADDING CATEGORY || *
 * * * * * * * * * * * * */
add_action("wp_ajax_EZ_add_category_of_menu", "EZ_add_category_of_menu");

//add_action("wp_ajax_nopriv_EZ_add_category_of_menu", "EZ_add_category_of_menu");

function EZ_add_category_of_menu() {
    echo json_encode(array('Status' => true, 'MSG' => 'Category added successfully', 'Request' => $_REQUEST));

    wp_insert_term($_REQUEST['category-name'], 'product_cat', array(
        'description' => $_REQUEST['category-description'],
        'parent' => 0, // optional
    ));

    exit;
}

/* * * * * * * * * * * * * *
 * || UPDATING CATEGORY || *
 * * * * * * * * * * * * * */
add_action("wp_ajax_EZ_update_category_of_menu", "EZ_update_category_of_menu");

function EZ_update_category_of_menu() {

    $term = get_term($_REQUEST['term_id']);
    $args = array(
        'name' => $_REQUEST['name'],
        'description' => $_REQUEST['description']
    );

    $response = wp_update_term($term->term_id, $term->taxonomy, $args);

    echo json_encode(array('Status' => true, 'MSG' => 'Category updated successfully'));
    exit;
}

/* * * * * * * * * * * * * *
 * || LISTING CATEGORIES ||*
 * * * * * * * * * * * * * */

include_once('wcfm/inc/view_categories.php');

/* * * * * * * * * * * * * *
 * ||DELETING A CATEGORY ||*
 * * * * * * * * * * * * * */
add_action("wp_ajax_EZ_delete_category_of_menu", "EZ_delete_category_of_menu");

function EZ_delete_category_of_menu() {
//    print_r($_REQUEST);    die();
    $term = get_term($_REQUEST['id']);

    wp_delete_term($term->term_id, $term->taxonomy);
//   wp_delete_category($term ->term_id);
    $msg = "Deleted from the menu";
    echo json_encode(array('Status' => true, 'MSG' => $msg, 'SHtml' => $html));
    exit();
}

/* * * * * * * * * * * * * * * * * * * * * *
 * || LISTING CATEGORIES WITH MENU ITEMS|| *
 * * * * * * * * * * * * * * * * * * * * * */

include_once('wcfm/inc/view_menu_items.php');

/* * * * * * * * * * * * *
 * || ADDING MENU ITEM || *
 * * * * * * * * * * * * */
include_once('wcfm/inc/add_menu_item.php');

/* * * * * * * * * * * * * *
 * ||DELETING A MENU ITEM ||*
 * * * * * * * * * * * * * */
add_action("wp_ajax_EZ_delete_menu_item", "EZ_delete_menu_item");

function EZ_delete_menu_item() {
//    print_r($_REQUEST);    die();
    $the_id = $_REQUEST['id'];
    wp_delete_post($the_id);
//   wp_delete_category($term ->term_id);
    $msg = "Deleted menu item " . $_REQUEST['name'];
    echo json_encode(array('Status' => true, 'MSG' => $msg, 'SHtml' => $html));
    exit();
}

/* * * * * * * * * * * * * * *
 * ||UPDATING A MENU ITEM || *
 * * * * * * * * * * * * * * */

include_once('wcfm/inc/update_menu_item.php');
/* * * * * * * * * * * * * * * *
 * || ADDING MENU ITEM EXRAS ||*
 * * * * * * * * * * * * * * * */

include_once('wcfm/inc/menu_item_extra.php');
include_once('wcfm/shortcodes.php');
/* ?>
  <script>
  jQuery(function () {
  jQuery('.attachment-woocommerce_thumbnail.size-woocommerce_thumbnail').wrap('<div class="ez_prod_img_wrap"></div>');
  });</script>
  <?php */

/* * *
 * CUSTOMIZING THE CUSTOMER MYACCOUNT PAGE
 * * */

//add_action('woocommerce_before_my_account', 'customize_myAccount_page');

function customize_myAccount_page() {
    ob_start();
    if (is_account_page()) {
        ?>
        <style>
            .woocommerce-MyAccount-navigation  ul{
                background: #FFF;
                margin-right: 50px;
            }
            .woocommerce-MyAccount-navigation ul li {
                list-style-type: none !important;
                padding: 6px 40px 6px 20px;
                border-bottom: 1px solid #DEDEDE;
                width:100%;
            }

            .woocommerce-MyAccount-content {
                background: #FFF;
                padding: 50px;
                margin: 0;
                position: relative;
            }
        </style>
        <script>
            jQuery(function () {
                jQuery('.woocommerce').addClass('row');
                jQuery('.woocommerce-MyAccount-navigation').addClass('col-md-3');
                jQuery('.woocommerce-MyAccount-content').addClass('col-md-9');
            });
        </script>
    <?php } ?>
    <script>
        jQuery(function () {
            let site_url = "<?php echo site_url(); ?>/";
            //            jQuery('.user-dashboard-menu>ul>li>a').attr('href',site_url+'/my-account');
            let the_list = '<li class=" dashboard"> <a href="' + site_url + '/my-account/">Dashboard</a></li><li class=" orders"> <a href="' + site_url + '/my-account/orders/">Orders</a></li><li class=" downloads"> <a href="' + site_url + 'my-account/downloads/">Downloads</a></li><li class=" edit-address"> <a href="' + site_url + 'my-account/edit-address/">Addresses</a></li><li class=" followings"> <a href="' + site_url + 'my-account/followings/">Followings</a></li><li class=" support-tickets"> <a href="' + site_url + 'my-account/support-tickets/">Support Tickets</a></li><li class=" inquiry"> <a href="' + site_url + 'my-account/inquiry/">Inquiries</a></li><li class=" edit-account"> <a href="' + site_url + 'my-account/edit-account/">Account details</a></li><li class=" customer-logout"> <a href="' + site_url + 'my-account/customer-logout">Logout</a></li>';
            //            jQuery('.user-dashboard-menu>ul>li>ul').prepend('<li><a href=' + site_url + '/my-account>Dashboard</a></li>');
            jQuery('.user-dashboard-menu>ul>li>ul').html(the_list);
        });
    </script>
    <?php
    $scripts = ob_get_clean();
    echo $scripts;
}

/* * ***GET USER BY user_nicename**** */

function get_user_id_by_user_nicename($user_nicename) {
    global $wpdb;

    if (!$user = $wpdb->get_row($wpdb->prepare(
                    "SELECT ID FROM $wpdb->users WHERE user_nicename = %s", $user_nicename
            )))
        return false;

    return $user->ID;
}

/* * ****** menu item ajax cb******* */

//function register_session() {
//    if (!session_id())
//        session_start();
//}
//
//add_action('init', 'register_session');
////UNCOMMENT AND FIX TO ADD FEE AND TAX
//function ez_add_custom_fee_to_cart($order_fee, $tax_amount) {
//    global $order_fee, $tax_amount;
//    add_action('woocommerce_cart_calculate_fees', function ($tax_amount, $order_fee) {
//        global $woocommerce;
////        $woocommerce->cart->add_fee(__($_REQUEST['order_fee_type'], 'woocommerce'), $order_fee_amount);
////        $woocommerce->cart->add_fee(__('VAT 13%', 'woocommerce'), $order_vat_cal_price);
//        $woocommerce->cart->add_fee(__('Order Fee', 'woocommerce'), $order_fee);
//        $woocommerce->cart->add_fee(__('VAT 13%', 'woocommerce'), $tax_amount);
//    });
//}

add_action('woocommerce_cart_calculate_fees', function () {

    global $woocommerce;

    $woocommerce->cart->add_fee(__('Order Fee', 'woocommerce'), get_option('_order_fee'));
    $woocommerce->cart->add_fee(__('VAT 13%', 'woocommerce'), get_option('_order_vat'));
});

add_action("wp_ajax_add_menu_item_to_cart", "add_menu_item_to_cart");

add_action("wp_ajax_nopriv_add_menu_item_to_cart", "add_menu_item_to_cart");

function add_menu_item_to_cart() {
//    register_session();
    $sub_total_amount = $_REQUEST['sub_total_amount'];
    $order_fee_amount = $_REQUEST['order_fee_amount'];
    $total = $_REQUEST['total_amount'];
//        print_r($_REQUEST);
    global $woocommerce;
    WC()->cart->add_to_cart($_REQUEST['order_product_id']);
    ob_start();
    ?>
    <li class="menu-added" data-val="<?php echo $_REQUEST['order_price']; ?>" 
        id="<?php echo $_REQUEST['order_product_id']; ?>"
        data-id="<?php echo $_REQUEST['order_product_id']; ?>">
        <a href="javascript:void(0)" data-product_id="<?php echo $_REQUEST['order_product_id']; ?>" class="remove btn-cross remove_menu_item_from_cart" onclick="remove_item_from_cart(this);">
            <i class="remove_this_item icon-cross3"></i>
        </a>
        <a><?php echo ucfirst($_REQUEST['order_title']); ?></a>
        <span class="category-price">£<?php echo $_REQUEST['order_price']; ?></span>
        <ul class="exrtas_here"></ul>
    </li>
    <?php
    $item_to_cart = ob_get_clean();
//    $sub_total = $_SESSION['MENU']['subtotal'] + $_REQUEST['order_price'];
//    $sub_total = round($sub_total, 2);
    $sub_total = WC()->cart->subtotal_ex_tax;
//    $sub_total = 0;
//    $sub_total_amount = isset($sub_total_amount) ? $sub_total_amount: '';
//$_SESSION['MENU']['CART']['item'] .= $item_to_cart;
//$sub_total += floatval($sub_total_amount);
//    $_SESSION['MENU']['subtotal'] = $sub_total;
    $order_vat_cal_price = (13 / 100) * $sub_total;
    $order_vat_cal_price = round($order_vat_cal_price, 2);

    add_option('_order_fee', $order_fee_amount);
//    add_option('_order_fee', $order_fee_amount);
    add_option('_order_vat', $order_vat_cal_price);

    $total = WC()->cart->total;

    /* ob_start();
      ?>
      <script>
      jQuery(document).ready(function ($) {
      jQuery('.remove_menu_item_from_cart').on('click', function () {
      console.log('clicked');
      });
      });
      </script>
      <?php
      $the_script = ob_get_clean(); */

    $total = round($total, 2);
    echo json_encode(array('Status' => true, 'item_to_cart' => $item_to_cart, 'order_vat_cal_price' => $order_vat_cal_price, 'sub_total' => $sub_total, 'total' => $total));
    exit();
}

//print_r($_SESSION);

/* * *=APPLYING 13% ON TOTALS=* * 

  function toatls_with_tax() {

  global $woocommerce;

  // Grab current total number of products
  $totals = $woocommerce->cart->get_cart_total();

  $tax_percentage = 20;

  $total_tax = ($tax_percentage / 100) * $totals;

  //    return $totals;
  return $totals + $total_tax;
  }
 */
//add_action('woocommerce_calculate_totals', 'toatls_with_tax');


/* * ****** menu item extra******* */
add_action("wp_ajax_add_menu_item_extra_to_cart", "add_menu_item_extra_to_cart");

add_action("wp_ajax_nopriv_add_menu_item_extra_to_cart", "add_menu_item_extra_to_cart");

function add_menu_item_extra_to_cart() {
//    echo 'Hello from the function!';
//    print_r($_REQUEST);
    $the_id = $_REQUEST['the_id'];
    ob_start();
    ?>
    <?php
    $ext_arr = get_post_meta($the_id, 'menu_item_extra', true);
    $number = 0;
    if ($ext_arr != "" || !empty($ext_arr) || $ext_arr != 0):
        foreach ($ext_arr as $single):
//            print_r($single);
            ?>
            <div class="row">
                <div class="col-md-12"><h4><?php echo $single['heading'][0]; ?></h4></div>
            </div>
            <?php for ($number = 0; $number < count($single['sub_title']); $number++): ?>
                <div class="row menu_exta_response">
                    <input class="allowed" type="hidden" name="items_allowed" id="items_allowed" value="<?php echo $req = $single['req'][0]; ?>">
                    <label onclick="check_n_calc(this);" class="col-md-10" id="<?php echo $single['heading'][0] ?>_<?php echo $single['sub_title'][$number]; ?>">
                        <input class="add_this_extra" type="checkbox" name="<?php echo $the_id ?>_extra[]" 
                               data-parent-id="<?php echo $the_id; ?>"
                               data-name="<?php echo $single['sub_title'][$number]; ?>"
                               data-val="<?php echo $single['sub_price'][$number]; ?>"
                               data-id="<?php echo $single['product_id'][$number]; ?>"
                               data-product_id="<?php echo $single['product_id'][$number]; ?>"/>
                        <span class="extra-title"><?php echo $single['sub_title'][$number]; ?></span>
                    </label>
                    <label class="col-md-2" for="<?php echo $single['heading'][0] ?>_<?php echo ucfirst($single['sub_title'][$number]); ?>">
                        <span class="extra-price">£<?php echo $single['sub_price'][$number]; ?></span>
                    </label> 

                </div>  
                <?php
            endfor;
//        endforeach;
        endforeach;
        $number++;
        ?>
        <div class="row">                                                                                                                                                                                                                                                                                <div class="col-md-6">                                                                                                                       <!--href="?add-to-cart=<?php // echo $the_id;                                                                                                                  ?>"-->
                <button id="add_extra_to_cart" class="mt-4 btn btn-info add_menu_extra_to_cart" 
                        onclick="add_ext_crt(this)"
                        data-product_id="<?php echo $the_id; ?>" data-id="<?php echo $the_id; ?>">
                    ADD TO MENU
                </button></div>
            <div class="col-md-6">   
                <button type="button" class="mt-4 btn btn-primary pull-right" onclick="close_modal(this);">
                    Cancel
                </button>

            </div>
        </div>
        <?php
        $modal_content = ob_get_clean();
        echo json_encode(array('Status' => true, 'modal_content' => $modal_content, 'req' => $_REQUEST));
//    else:
//        echo json_encode(array('Status' => false, 'modal_content' => 0, 'req' => $_REQUEST));
    endif;
    exit();
}

/////////////////////////////////////////// Filters added by imdad for add/update extras////////////////////////////////////////////////////
//----------------------------------------------- Update  --------------------------------------------
add_filter("cp_add_extra_item_product", "cp_add_extra_item_product_callback", 10, 4);

function cp_add_extra_item_product_callback($restorant_id, $parent_id, $title, $price) {
    $post = array(
        'post_author' => $restorant_id,
        'post_parent' => $parent_id,
        //'post_content' => $_REQUEST['excerpt'],
        //'post_excerpt' => $_REQUEST['excerpt'],
        'post_status' => "publish",
        'post_title' => $title,
        'post_type' => "product",
    );
    $post_id = wp_insert_post($post, $wp_error);
    //wp_set_object_terms($post_id, $_REQUEST['restaurant_menu'], 'product_cat');
    wp_set_object_terms($post_id, 'simple', 'product_type');
//wp_set_object_terms( $post_id, $_REQUEST['excerpt'], 'post_excerpt');
    wc_delete_product_transients($post_id);
    add_post_meta($post_id, '_visibility', 'visible');
    add_post_meta($post_id, '_stock_status', 'instock');
    add_post_meta($post_id, 'total_sales', '0');
    add_post_meta($post_id, '_downloadable', 'no');
    add_post_meta($post_id, '_virtual', 'no');
    add_post_meta($post_id, '_regular_price', $price);
//    add_post_meta($post_id, '_sale_price', $_REQUEST['_regular_price']);
    add_post_meta($post_id, '_purchase_note', "");
    add_post_meta($post_id, '_featured', "no");
    add_post_meta($post_id, '_weight', "");
    add_post_meta($post_id, '_length', "");
    add_post_meta($post_id, '_width', "");
    add_post_meta($post_id, '_height', "");
    add_post_meta($post_id, '_sku', "");
    add_post_meta($post_id, '_product_attributes', array());
    add_post_meta($post_id, '_sale_price_dates_from', "");
    add_post_meta($post_id, '_sale_price_dates_to', "");
    add_post_meta($post_id, '_price', $price);
    add_post_meta($post_id, '_sold_individually', "");
    add_post_meta($post_id, '_manage_stock', "no");
    add_post_meta($post_id, '_backorders', "no");
    add_post_meta($post_id, '_stock', "");
    return $post_id;
}

//----------------------------------------------- Update  --------------------------------------------
add_filter("cp_update_extra_item_product", "cp_update_extra_item_product_callback", 10, 5);

function cp_update_extra_item_product_callback($restorant_id, $product_id, $parent_id, $title, $price) {
    $post = array(
        'post_author' => $restorant_id,
        'post_title' => $title,
        'post_parent' => $parent_id,
        'post_type' => "product",
        'ID' => $product_id
    );
//update post
    $post_id = wp_update_post($post, $wp_error);
    wp_set_object_terms($post_id, 'simple', 'product_type');
//wp_set_object_terms( $post_id, $_REQUEST['excerpt'], 'post_excerpt');
    wc_delete_product_transients($post_id);
    update_post_meta($post_id, '_visibility', 'visible');
    update_post_meta($post_id, '_stock_status', 'instock');
    update_post_meta($post_id, 'total_sales', '0');
    update_post_meta($post_id, '_downloadable', 'no');
    update_post_meta($post_id, '_virtual', 'no');
    update_post_meta($post_id, '_regular_price', $price);
//    update_post_meta($post_id, '_sale_price', $_REQUEST['_regular_price']);
    update_post_meta($post_id, '_purchase_note', "");
    update_post_meta($post_id, '_featured', "no");
    update_post_meta($post_id, '_weight', "");
    update_post_meta($post_id, '_length', "");
    update_post_meta($post_id, '_width', "");
    update_post_meta($post_id, '_height', "");
    update_post_meta($post_id, '_sku', "");
    update_post_meta($post_id, '_product_attributes', array());
    update_post_meta($post_id, '_sale_price_dates_from', "");
    update_post_meta($post_id, '_sale_price_dates_to', "");
    update_post_meta($post_id, '_price', $price);
    update_post_meta($post_id, '_sold_individually', "");
    update_post_meta($post_id, '_manage_stock', "no");
    update_post_meta($post_id, '_backorders', "no");
    update_post_meta($post_id, '_stock', "");
}

/* * ****** menu item extra******* */
add_action("wp_ajax_add_items_to_woo_cart", "add_items_to_woo_cart");

add_action("wp_ajax_nopriv_add_items_to_woo_cart", "add_items_to_woo_cart");

function add_items_to_woo_cart($id) {
//    print_r($_REQUEST);
    global $woocommerce;
    $parent_id = $_REQUEST['parent_id'];
    $sub_ids;
//    WC()->cart->add_to_cart($parent_id);
    if ($_REQUEST['sub_ids'] != "") {
        $sub_ids = rtrim($_REQUEST['sub_ids'], ',');
        $sub_ids = explode(',', $sub_ids);
        if (is_array($sub_ids)) {
            foreach ($sub_ids as $single_extra) {
//            print_r($single_extra);
//            echo 'single extra :' . $single_extra;

                WC()->cart->add_to_cart($single_extra);
            }
        }
        ob_start();
        ?>
        Subtotal:
        <span class="price">
            <?php
            if (WC()->cart->get_cart_contents_count() > 0) {
                echo '£ <em class="menu-subtotal" id="sub_total">' . WC()->cart->subtotal_ex_tax . '</em>';
            } else {
                echo '<em class="menu-subtotal" id="sub_total"> 0</em>';
            }
            ?>

        </span>
        <?php
        $subtotal_price = ob_get_clean();
        ob_start();
        ?>Total:
        <span class="price"> 
            <em class="menu-grtotal" data-grant_total="">
                <?php
                if (WC()->cart->get_cart_contents_count() > 0) {
                    echo '£' . WC()->cart->total;
                } else {
                    echo "0";
                }
                ?>
            </em>
        </span> 
        <?php
        $total_price = ob_get_clean();
    } else {
        WC()->cart->add_to_cart($sub_ids);
    }
    echo json_encode(array('Status' => true, 'parent_id' => $parent_id, 'sub_ids' => $sub_ids, 'subtotal_price' => $subtotal_price, 'total_price' => $total_price));
    exit();
}

//////////////////////////////////////////////
///////////////RIGHT SIDEBAR/////////////////
/////////////////////////////////////////////

add_action('woocommerce_after_checkout_form', 'add_sidebar_checkout');

function add_sidebar_checkout() {
    ob_start();
    ?>
    <style>a.remove.btn-cross.remove_menu_item_from_cart {display: none !important;}</style>
    <div id="menu_items_sidebar" class="rgt right_sidebar widget-area sidebar sticky-sidebar" style="position: relative; overflow: visible; box-sizing: border-box; min-height: 1px;">
        <?php ?>
        <div class="theiaStickySidebar" style="padding-top: 0px; padding-bottom: 1px; position: static; transform: none; top: 0px; left: 1098.27px;"><div class="user-order-holder">
                <div class="user-order">
                    <h6><i class="icon-shopping-basket"></i>Your Order</h6>
                    <span class="error-message pre-order-msg" style="display: none;">This restaurant allows Pre orders.</span>
                    <span class="discount-info" style="display: none;">If you have a discount code,<br> you will be able to input it<br> at the payments stage.</span>
                    <div class="select-option dev-select-fee-option">

                        <ul>
                            <?php
//                            $segments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
//                            $restaurant_name = $segments[1];
//                            $author_id = get_user_id_by_user_nicename($restaurant_name);
//                            $restaurant_delivery_or_pickup = get_user_meta($author_id, 'restaurant_delivery_or_pickup', true);
                            global $woocommerce;
//                        session_destroy();
                            ?>

                        </ul>
                    </div>

                    <!--MENU ORDERS LIST-->
                    <div class="dev-menu-orders-list" style="">
                        <!--THE LIST-->
                        <ul class="categories-order"></ul>
                        <!--/THE LIST-->
                        <div class="price-area dev-menu-price-con" <?php if (WC()->cart->get_cart_contents_count() == 0): ?>style="display:none;"<?php endif; ?>>
                            <ul>
                                <?php // print_r($_SESSION)     ?>
                                <li>Subtotal
                                    <span class="price">£<em class="menu-subtotal" id="sub_total">
                                            <?php
                                            if (WC()->cart->get_cart_contents_count() > 0) {
                                                echo WC()->cart->subtotal_ex_tax;
                                            } else {
                                                echo "";
                                            }
                                            ?>
                                        </em>
                                    </span>
                                </li>

                                <li class="restaurant-fee-con"><span class="fee-title">Order fee</span>
                                    <span class="price">£<em class="menu-charges" id="fee" data-fee="<?php
                                        echo get_option('_order_fee');
                                        ?>"><?php echo get_option('_order_fee'); ?></em>  
                                    </span>    
                                </li>

                                <li>VAT (13%) 
                                    <span class="price">£<em class="vtax"><?php echo get_option('_order_vat'); ?></em></span>
                                </li>
                            </ul>
                            <p class="total-price">Total 
                                <span class="price">
                                    £<em class="menu-grtotal" data-grant_total="">
                                        <?php
                                        if (WC()->cart->get_cart_contents_count() > 0) {
                                            echo WC()->cart->total;
                                        } else {
                                            echo "";
                                        }
                                        ?>
                                    </em>
                                </span>
                            </p>
                        </div>
                    </div>
                    <script>
                        jQuery(function () {
                            jQuery('.page-title h1').css('color', '#000000 !important');
                        });//docread

                        //
                    </script>
                    <!--/MENU ORDERS LIST-->

                    <div id="no-menu-orders" style="">
                        <span class="success-message" <?php if (WC()->cart->get_cart_contents_count() > 0): ?>style="display:none;"<?php endif; ?>>There are no items in your basket.</span>                                                        </div>
                    <div class="pay-option dev-order-pay-options">
                    </div>


                    <span class="menu-loader"></span>
                </div>
            </div></div>
        <?php ?>
    </div>

    <?php
    $html = ob_get_clean();
    echo $html;
}

function reduce_product_from_cart($id) {

    global $woocommerce;
    $cartId = $woocommerce->cart->generate_cart_id($id);
    $cartItemKey = $woocommerce->cart->find_product_in_cart($cartId);
    $items = $woocommerce->cart->get_cart();
//    echo '<pre>';
//    print_r($items);
//    echo '</pre>';

    foreach ($items as $item => $values) {
//        echo '<pre>';
//        print_r($values);
        if ($values['key'] == $cartItemKey) {
            $new_qty = ($values['quantity'] - 1);
            $woocommerce->cart->set_quantity($cartItemKey, $new_qty);
        }
    }
}

/* * ****** remove menu item from cart ******* */
add_action("wp_ajax_ez_remove_menu_item_from_cart", "ez_remove_menu_item_from_cart");

add_action("wp_ajax_nopriv_ez_remove_menu_item_from_cart", "ez_remove_menu_item_from_cart");

function ez_remove_menu_item_from_cart() {

    reduce_product_from_cart($_REQUEST['the_id']);

    $child_ids = explode(',', $_REQUEST['child_ids']);

    if ($child_ids != "" || !empty($child_ids)):
        if (is_array($child_ids)) {
            foreach ($child_ids as $child_id) {

                if (empty($child_id) || $child_id == "") {
                    unlink($child_id);
                }
//                echo $child_id.'  ';
//                exit;
                reduce_product_from_cart($child_id);
            }
        } else {
            reduce_product_from_cart($child_ids);
        }
    endif;

    ob_start();
    ?>
    Subtotal:
    <span class="price">
        <?php
        if (WC()->cart->get_cart_contents_count() > 0) {
            echo '£ <em class="menu-subtotal" id="sub_total">' . WC()->cart->subtotal_ex_tax . '</em>';
        } else {
            echo '<em class="menu-subtotal" id="sub_total"> 0</em>';
        }
        ?>

    </span>
    <?php
    $subtotal_price = ob_get_clean();
    ob_start();
    ?>Total:
    <span class="price"> 
        <em class="menu-grtotal" data-grant_total="">
            <?php
            if (WC()->cart->get_cart_contents_count() > 0) {
                echo '£' . WC()->cart->total;
            } else {
                echo "0";
            }
            ?>
        </em>
    </span> 
    <?php
    $total_price = ob_get_clean();
    echo json_encode(array('Status' => true, 'subtotal_price' => $subtotal_price, 'total_price' => $total_price));
    exit();
}

function toggle_sidebar_upon_cart() {
    global $woocommerce;
    if ($woocommerce->cart->cart_contents_count != 0) {
        ob_start();
        ?>
        <script>
            jQuery(function () {
                var menu_items_sidebar = sessionStorage.getItem("menu_items_sidebar");
                console.log(menu_items_sidebar);
                if (menu_items_sidebar != "" || menu_items_sidebar != null || !empty(menu_items_sidebar)) {
                    jQuery('.categories-order').html(menu_items_sidebar);
                }
            });
        </script> 
        <?php
        $menu_items_sidebar_logic = ob_get_clean();
    } else {
        ob_start();
        ?>
        <script> sessionStorage.removeItem("menu_items_sidebar");</script> 
        <?php
        $menu_items_sidebar_logic = ob_get_clean();
    }
    echo $menu_items_sidebar_logic;
}

add_action('wp_footer', 'toggle_sidebar_upon_cart', 15);

/**
 * UPDATE CART BASED ON FEE
 * ** */
add_action("wp_ajax_ez_update_cart_based_on_fee", "ez_update_cart_based_on_fee");

add_action("wp_ajax_nopriv_ez_update_cart_based_on_fee", "ez_update_cart_based_on_fee");

function ez_update_cart_based_on_fee() {
//    print_r($_REQUEST['the_fee']);
//die();
    update_option('_order_fee', $_REQUEST['the_fee']);

    add_action('woocommerce_cart_calculate_fees', function () {

        global $woocommerce;

        $woocommerce->cart->add_fee(__('Order Fee', 'woocommerce'), get_option('_order_fee'));
    });

    ob_start();
    ?>
    Subtotal:
    <span class="price">
        <?php
        if (WC()->cart->get_cart_contents_count() > 0) {
            echo '£ <em class="menu-subtotal" id="sub_total">' . WC()->cart->subtotal_ex_tax . '</em>';
        } else {
            echo '<em class="menu-subtotal" id="sub_total"> 0</em>';
        }
        ?>
    </span>
    <?php
    $subtotal_price = ob_get_clean();
    ob_start();
    ?>Total:
    <span class="price"> 
        <em class="menu-grtotal" data-grant_total="">
            <?php
            if (WC()->cart->get_cart_contents_count() > 0) {
                echo '£' . WC()->cart->total;
            } else {
                echo "0";
            }
            ?>
        </em>
    </span> 
    <?php
    $total_price = ob_get_clean();
    echo json_encode(array('Status' => true, 'subtotal_price' => $subtotal_price, 'total_price' => $total_price, 'the_fee_now' => get_option('_order_fee')));
    exit();
}

add_action('wp_footer', 'modify_styles', 15);

function modify_styles() {
    ob_start();
    ?>
    <script>
        jQuery(function () {
            let login_link = '<a class="cs-color cs-popup-joinus-btn login-popup" href="<?php echo site_url(); ?>/my-account/">LOGIN </a>';
            jQuery('a.cs-color.cs-popup-joinus-btn.login-popup').replaceWith(login_link);
    <?php if (!is_user_logged_in()) { ?>
                jQuery('body.woocommerce-account.woocommerce-page .main-section .col-lg-8.col-md-8.col-sm-12.col-xs-12').removeClass('col-lg-8').addClass('col-lg-5').removeClass('col-md-8').addClass('col-md-5').addClass('center-aligned').addClass('align-center');
    <?php } else { ?>
                jQuery('body.woocommerce-account.woocommerce-page.logged-in .main-section .col-lg-8.col-md-8.col-sm-12.col-xs-12').removeClass('col-lg-8').addClass('col-lg-12').removeClass('col-md-8').addClass('col-md-12');
    <?php } ?>
                jQuery('body.page-id-15967 .main-section .col-lg-8.col-md-8.col-sm-12.col-xs-12').removeClass('col-lg-8').addClass('col-lg-12').removeClass('col-md-8').addClass('col-md-12');
        });</script>
    <style>.center-aligned {margin: 0 auto;display: block;}</style>
   
    <?php
    $link_login = ob_get_clean();
    echo $link_login;
}
