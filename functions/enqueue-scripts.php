<?php
/**
 * Load scripts
 *
 * @param string $handle
 * @param string $rel_src
 * @param array  $deps
 * @param bool   $in_footer
 */
function zume_enqueue_script( $handle, $rel_src, $deps = array(), $in_footer = false ) {
    if ( $rel_src[0] === "/" ) {
        throw new Error( "zume_enqueue_script took \$rel_src argument which unexpectedly started with /" );
    }
    wp_enqueue_script( $handle, get_template_directory_uri() . "/$rel_src", $deps, filemtime( get_template_directory() . "/$rel_src" ), $in_footer );
}

/**
 * Load styles
 *
 * @param string $handle
 * @param string $rel_src
 * @param array  $deps
 * @param string $media
 */
function zume_enqueue_style( $handle, $rel_src, $deps = array(), $media = 'all' ) {
    if ( $rel_src[0] === "/" ) {
        throw new Error( "zume_enqueue_style took \$rel_src argument which unexpectedly started with /" );
    }
    wp_enqueue_style( $handle, get_template_directory_uri() . "/$rel_src", $deps, filemtime( get_template_directory() . "/$rel_src" ), $media );
}

function site_scripts() {
  global $wp_styles; // Call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

    // Adding scripts file in the footer
    wp_enqueue_script( 'site-js', get_template_directory_uri() . '/assets/scripts/scripts.js', array( 'jquery' ), filemtime(get_template_directory() . '/assets/scripts/js'), true );

    // Register main stylesheet
    wp_enqueue_style( 'site-css', get_template_directory_uri() . '/assets/styles/style.css', array(), filemtime(get_template_directory() . '/assets/styles/scss'), 'all' );
    wp_style_add_data( 'site-css', 'rtl', 'replace' );

    // Comment reply script for threaded comments
    if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
      wp_enqueue_script( 'comment-reply' );
    }

    wp_enqueue_script( 'zume', get_template_directory_uri() . '/assets/scripts/zume.js', array( 'jquery' ), 1.1, true );
    wp_localize_script(
        "zume", "zumeMaps", array(
            'root' => esc_url_raw( rest_url() ),
            'nonce' => wp_create_nonce( 'wp_rest' ),
            'current_user_login' => wp_get_current_user()->user_login,
            'current_user_id' => get_current_user_id(),
            'theme_uri' => get_stylesheet_directory_uri(),
            "translations" => [
                "delete" => esc_html__( 'Delete', 'zume' ),
                "failed_to_remove" => esc_html__( 'Failed to remove item.', 'zume' ),
                "failed_to_change" => esc_html__( 'Failed to change item.', 'zume' ),
                "print_copyright" => esc_html__( 'Three Month Plan - Zúme Project', 'zume' ),
                "we_got_it" => esc_html__( 'We got it!', 'zume' ),
                "we_got_it_message" => esc_html__( 'We\'re a volunteer network, so give us a few days. We\'ll reach out to you soon as possible!', 'zume' )
            ]
        )
    );

    zume_enqueue_style( 'zume-course', 'assets/styles/zume-course.css', array(), 'all' ); // Relocated into the _main.scss theme file
    wp_style_add_data( 'zume-course', 'rtl', 'replace' );

    zume_enqueue_style( 'zume_dashboard_style', 'assets/styles/zume-dashboard.css' ); // Relocated to the _main.scss in the theme
    wp_style_add_data( 'zume_dashboard_style', 'rtl', 'replace' );

    wp_enqueue_style( 'foundations-icons', get_template_directory_uri() .'/assets/styles/foundation-icons/foundation-icons.css', array(), '3' );

}
add_action('wp_enqueue_scripts', 'site_scripts', 999);
