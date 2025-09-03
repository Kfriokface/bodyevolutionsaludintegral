<?php
/**
 * Physio-qt functions and definitions.
 *
 * @package physio-qt
 * @author QreativeThemes
 */

/**
 * Define the version of the theme css and js files
 *
 */
define( 'PHYSIO_THEME_VERSION', wp_get_theme()->get( 'Version' ) );

/**
 * Set the content width in pixels
 *
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1140;
}

if ( ! function_exists( 'physio_qt_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function physio_qt_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on physio-qt, use a find and replace
		 * to change 'physio-qt' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'physio-qt', get_theme_file_path( '/languages' ) );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * WooCommerce Support
		 */
		add_theme_support( 'woocommerce' );
		
		if ( 'disable' !== get_theme_mod( 'shop_product_zoom', 'enable' ) ) {
			add_theme_support( 'wc-product-gallery-zoom' );
		}

		if ( 'disable' !== get_theme_mod( 'shop_product_lightbox', 'enable' ) ) {
			add_theme_support( 'wc-product-gallery-lightbox' );
		}

		if ( 'disable' !== get_theme_mod( 'shop_product_slider', 'enable' ) ) {
			add_theme_support( 'wc-product-gallery-slider' );
		}

		/*
		 * Add Gutenberg support
		 */
		add_theme_support( 'align-wide' );
		add_theme_support( 'align-full' );
		add_theme_support( 'wp-block-styles' );

		/*
		 * Add Gutenberg color palette
		 */
		add_theme_support( 'editor-color-palette', array(
			array(
			    'name' 	=> esc_html__( 'Theme blue', 'physio-qt' ),
			    'slug' 	=> 'theme-blue',
			    'color' => '#56afd5',
			),
			array(
			    'name' 	=> esc_html__( 'Theme purple', 'physio-qt' ),
			    'slug' 	=> 'theme-purple',
			    'color' => '#9A65A5',
			),
			array(
			    'name' 	=> esc_html__( 'Theme text color', 'physio-qt' ),
			    'slug' 	=> 'theme-text-color',
			    'color' => '#999999',
			),
		) );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 */
		add_theme_support( 'post-thumbnails' );

		// Jumbotron
		add_image_size( 'physio-qt-slider-l', 1920, 715, true );
		add_image_size( 'physio-qt-slider-m', 960, 358, true );
		add_image_size( 'physio-qt-slider-s', 480, 179, true );

		// Featured Page
		add_image_size( 'physio-qt-featured-s', 360, 240, true );
		add_image_size( 'physio-qt-featured-l', 850, 567, true );

		// News Widget
		add_image_size( 'physio-qt-news-l', 848, 448, true );
		add_image_size( 'physio-qt-news-s', 360, 180, true );

		/*
		 * This theme uses wp_nav_menu() in one location.
		 */
		register_nav_menu( 'primary', esc_html__( 'Main Navigation', 'physio-qt' ) );
		register_nav_menu( 'top-nav', esc_html__( 'Topbar Navigation', 'physio-qt' ) );
		register_nav_menu( 'footer-nav', esc_html__( 'Footer Navigation', 'physio-qt' ) );
		register_nav_menu( 'service-nav', esc_html__( 'Services Navigation', 'physio-qt' ) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'physio_qt_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		/*
		 * Add excerpt support for pages
		 */
		add_post_type_support( 'page', 'excerpt' );

		/*
		 * Support CSS for TinyMCE
		 */
		add_editor_style();
	}
	add_action( 'after_setup_theme', 'physio_qt_setup' );
}

/**
 * Enqueue CSS files
 */
if ( ! function_exists( 'physio_qt_enqueue_styles' ) ) {
	function physio_qt_enqueue_styles() {

		// Font Awesome
		if ( '5' === get_theme_mod( 'qt_fontawesome_version' ) ) {

			wp_enqueue_style( 'font-awesome', get_theme_file_uri( 'assets/icons/fontawesome/css/all.min.css' ), '5.12', true );

			if ( 'enable' == get_theme_mod( 'qt_fontawesome_fallback', 'enable' ) ) {
				wp_enqueue_style( 'font-awesome-v4-shims', get_theme_file_uri( '/assets/icons/fontawesome/css/v4-shims.css' ), '5.12', true );
			}

		} else {

			wp_enqueue_style( 'font-awesome', get_theme_file_uri( 'assets/icons/fontawesome-v4/css/font-awesome.min.css' ), '4.7.0', true );
		}

		// Bootstrap
		wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/assets/css/bootstrap.css' ), '3.4.1', true );

		// Main CSS stylesheet
		wp_enqueue_style( 'physio-qt-style', get_stylesheet_uri(), array(), PHYSIO_THEME_VERSION, null );

		// If WooCommerce is active enqueue custom CSS stylesheet
		if ( physio_qt_woocommerce_active() ) {
			wp_enqueue_style( 'physio-qt-woocommerce', get_theme_file_uri( '/woocommerce.css' ), array( 'physio-qt-style' ), PHYSIO_THEME_VERSION );
		}
	}
	add_action( 'wp_enqueue_scripts', 'physio_qt_enqueue_styles' );
}

/**
 * Enqueue Google Fonts
 * 
 */
if ( ! function_exists( 'physio_qt_google_font' ) ) {
	function physio_qt_google_font() {
		wp_enqueue_style( 'physio-qt-fonts', esc_url( physio_qt_font_slug() ), array(), null );
	}
	add_action( 'wp_enqueue_scripts', 'physio_qt_google_font' );
}

/**
 * Enqueue JS files
 */
if ( ! function_exists( 'physio_qt_enqueue_scripts' ) ) {
	function physio_qt_enqueue_scripts() {

		wp_enqueue_script( 'physio-qt-modernizr', get_theme_file_uri( '/assets/js/modernizr-custom.js' ), array(), '', true );

		wp_enqueue_script( 'picturefill', get_theme_file_uri( '/bower_components/picturefill/dist/picturefill.min.js' ), array(), '', true );

		wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/assets/js/bootstrap.min.js' ), array(), '3.4.1', true );

		wp_enqueue_script( 'physio-qt-main', get_theme_file_uri( '/assets/js/main.min.js' ), array( 'jquery', 'underscore' ), PHYSIO_THEME_VERSION, true );

		// Get Theme path, used for requirejs
		wp_localize_script( 'physio-qt-main', 'physio_qt', array(
			'themePath'  => get_theme_file_uri(),
		) );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	add_action( 'wp_enqueue_scripts', 'physio_qt_enqueue_scripts' );
}

/**
 * Enqueue admin CSS & JS files
 */
if ( ! function_exists( 'physio_qt_admin_enqueue_scripts' ) ) {
	function physio_qt_admin_enqueue_scripts( $hook ) {

		// Font Awesome
		if ( '5' === get_theme_mod( 'qt_fontawesome_version' ) ) {

			wp_enqueue_style( 'font-awesome', get_theme_file_uri( 'assets/icons/fontawesome/css/all.min.css' ), '5.12', true );

			if ( 'enable' == get_theme_mod( 'qt_fontawesome_fallback', 'enable' ) ) {
				wp_enqueue_style( 'font-awesome-v4-shims', get_theme_file_uri( '/assets/icons/fontawesome/css/v4-shims.css' ), '5.12', true );
			}

		} else {

			wp_enqueue_style( 'font-awesome', get_theme_file_uri( 'assets/icons/fontawesome-v4/css/font-awesome.min.css' ), '4.7.0', true );
		}
		
		// Theme admin CSS
		wp_enqueue_style( 'physio-qt-admin', get_theme_file_uri( '/assets/css/admin.css' ) );
	}
	add_action( 'admin_enqueue_scripts', 'physio_qt_admin_enqueue_scripts' );
}

/**
 * Load Gutenberg stylesheet for in editor
 */
if ( ! function_exists( 'physio_qt_add_gutenberg_styles' ) ) {
	function physio_qt_add_gutenberg_styles() {

		// Gutenberg editor stylesheet
		wp_enqueue_style( 'physio-qt-gutenberg', get_theme_file_uri( '/assets/css/gutenberg-editor.css' ), false );

		// Enqueue font for Gutenberg editor
		wp_enqueue_style( 'google-fonts', esc_url( physio_qt_font_slug() ), array(), null );
	}
	add_action( 'enqueue_block_editor_assets', 'physio_qt_add_gutenberg_styles' );
}

/**
 * Get all the theme files from the /inc folder
 */

/* Include the ACF functions */
require_once( get_theme_file_path( '/inc/acf.php' ) );

/* Theme add_filter functions */
require_once( get_theme_file_path( '/inc/theme-filters.php' ) );

/* Theme add_action functions  */
require_once( get_theme_file_path( '/inc/theme-actions.php' ) );

/* Theme Sidebars init */
require_once( get_theme_file_path( '/inc/theme-sidebars.php' ) );

/* Theme Customizer */
require_once( get_theme_file_path( '/inc/theme-customizer.php' ) );

/* Theme Custom Comments  */
require_once( get_theme_file_path( '/inc/theme-custom-comments.php' ) );

/* Aria Walker Menu  */
require_once( get_theme_file_path( '/inc/aria_walker_nav_menu.php' ) );

/* WooCommerce Integration  */
if ( physio_qt_woocommerce_active() ) {
	require_once( get_theme_file_path( '/inc/woocommerce.php' ) );
}

// Following files only gets included in the admin area
if ( is_admin() ) {

	/* One Click Demo Installer Init */
	require_once( get_theme_file_path( '/inc/demo-import.php' ) );

	/* Class TGM Plugin Activation */
	require_once( get_theme_file_path( '/inc/tgmpa/class-tgm-plugin-activation.php' ) );

	/* TGM Plugin Init */
	require_once( get_theme_file_path( '/inc/tgm-plugin-activation.php' ) );
}

/**
 * Migrate custom css from theme field to native WordPress custom css field
 */
function physio_qt_custom_css_migrate() {

    if ( function_exists( 'wp_update_custom_css_post' ) ) {

        $custom_css = get_theme_mod( 'custom_css' );
        
        if ( $custom_css ) {

            $core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
            $return = wp_update_custom_css_post( $core_css . $custom_css );

            if ( ! is_wp_error( $return ) ) {
                // Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
                remove_theme_mod( 'custom_css' );
            }
        }
    }
}
add_action( 'after_setup_theme', 'physio_qt_custom_css_migrate' );