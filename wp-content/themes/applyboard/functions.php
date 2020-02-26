<?php
add_filter( 'pre_http_request', 'wp_update_check_short_circuit', 20, 3 );
function wp_update_check_short_circuit( $preempt = false, $args, $url ) {
    if ( stripos( $url, 'https://') === 0 ) {
        $url = substr( $url, 8 );
    }
    else {
        $url = substr( $url, 7 );
    }

    // stop other URL(s) requests as well (if you need to) in the same manner
    if ( stripos( $url, 'api.wordpress.org') === 0 ) {
        // WP is trying to get some info, short circuit it with a dummy response
        return array(
            'headers'   => null,
            'body'      => '',
            'response'  => array(
                'code'      => 503,
                'message'   => 'SERVICE_UNAVAILABLE'
                ),
            'cookies'   => array(),
            'filename'  => ''
            );
    }
    // returning false will let the normal procedure continue
    return false;
}

add_action('wp_head', 'dropdown_menu_scripts');
function dropdown_menu_scripts() {
    ?>
        <script>
          jQuery(document).ready(function ($) {
            $("#drop-nav").change( function() {
                    document.location.href =  $(this).val();
            });
          });
        </script>
    <?php
}
// Enable support for custom logo.
	add_theme_support( 'custom-logo', array(
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
	) );
// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'menu-1' => __( 'Primary', 'roku-theme' ),
			)
		);
function wpb_widgets_init() {
 
    register_sidebar( array(
        'name' => __( 'Main Sidebar', 'wpb' ),
        'id' => 'sidebar-1',
        'description' => __( 'The main sidebar appears on the right on each page except the front page template', 'wpb' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
 
   
	register_sidebar( array(
		'name'          => __( 'Footer First Column', 'wpb' ),
		'id'            => 'footer_1',
		'description'   => __( 'Footer First Column Sidebar', 'wpb' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h6 class="widget-title teo">',
		'after_title'   => '</h6>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Second Column', 'wpb' ),
		'id'            => 'footer_2',
		'description'   => __( 'Footer Second Column Sidebar', 'wpb' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h6 class="widget-title ">',
		'after_title'   => '</h6>',
	) );
	
     register_sidebar( array(
		'name'          => __('Footer five Column', 'wpb' ),
		'id'            => 'footer_5',
		'description'   => __( 'Footer Five Column Sidebar', 'wpb' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h6 class="widget-title ">',
		'after_title'   => '</h6>',
	) );
    }
 
add_action( 'widgets_init', 'wpb_widgets_init' );
function theme_prefix_setup() {
	
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 400,
		'flex-width' => true,
	) );

}
add_theme_support( 'post-thumbnails' );
add_filter( 'page_template', 'wpa3396_page_template' );
function wpa3396_page_template( $page_template )
{
    if ( is_page( 'my-custom-page-slug' ) ) {
        $page_template = dirname( __FILE__ ) . '/custom-page-template.php';
    }
    return $page_template;
}
?>