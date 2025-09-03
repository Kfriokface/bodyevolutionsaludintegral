<?php
/**
 *	Plugin Name: Physio Toolkit
 *	Plugin URI: http://www.qreativethemes.com
 *  Description: All plugin territory functionalities for the Physio WordPress Theme, by QreativeThemes
 *	Version: 1.5.3
 *	Author: QreativeThemes
 *	Author URI: http://www.qreativethemes.com
 *	Text Domain: thelandscaper-toolkit
 *	Domain Path: /languages/
 *
 *	@package physio-toolkit
 *	@author QreativeThemes
 *
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The core plugin class.
 */
if ( ! class_exists( 'PhysioToolkit' ) ) {
	class PhysioToolkit {

		public function __construct() {

			// Get all required files
			$this->physio_toolkit_include_files();
			
			// Load the translation file
			$this->physio_toolkit_load_textdomain();

			// Deactivate QreativeShortcodes plugin if this plugin is activated
			add_action( 'admin_init', array( $this, 'physio_toolkit_on_activation' ) );

			// Initialize the scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'physio_toolkit_enqueue_scripts' ) );

			// Initialize the admin scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'physio_toolkit_admin_enqueue_scripts' ) );
		}

		/**
		 * Include all required files
		 */
		private function physio_toolkit_include_files() {

			// Load the custom ACF PRO fields
			require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/acf/acf-fields.php' );

			// Load the custom QT widgets
			require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/widgets/widget-init.php' );
			require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/widgets/widget-functions.php' );

			// Load the text shortcodes
			require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/shortcodes/shortcodes.php' );
		}

		/**
		 * Include all required scripts and styles
		 */
		public function physio_toolkit_enqueue_scripts() {

			// Waypoints
			wp_register_script( 'waypoints', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.waypoints.min.js', array( 
				'jquery'
			), '3.1.1', true );

			// Countbox
			wp_register_script( 'physio-toolkit-countbox', plugin_dir_url( __FILE__ ) . 'assets/js/countbox.js', array( 
				'jquery'
			), '', true );
		}

		/**
		 * Include all required admin scripts and styles
		 */
		public function physio_toolkit_admin_enqueue_scripts( $hook ) {

			wp_enqueue_script( 'mustache', plugin_dir_url( __FILE__ ) . 'assets/js/mustache.min.js', array(), '3.0', true );

			// Only load these files if Font Awesome 5 is selected in theme settings
			if ( '5' === get_theme_mod( 'qt_fontawesome_version' ) ) {

				// Icon picker library for Font Awesome 5
				wp_enqueue_script( 'font-awesome-iconpicker-js', plugin_dir_url( __FILE__ ) . 'assets/js/fontawesome-iconpicker.min.js', array( 'jquery' ) );
			}

			// Only include the follow scripts on the post, page and widget pages
			if ( in_array( $hook, array( 'post-new.php', 'post.php', 'widgets.php' ) ) ) {
				
				wp_enqueue_script( 'physio-toolkit-admin-js', plugin_dir_url( __FILE__ ) . 'assets/js/admin.js',
					array( 
						'jquery',
						'underscore',
						'backbone',
						'mustache'
					)
				);
			}

			// Theme admin styles
			wp_enqueue_style( 'physio-toolkit-admin-style', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css', array(), '', null );
		}


		/**
		 * Load text domain for translation
		 */
		public function physio_toolkit_load_textdomain() {
			
			load_plugin_textdomain( 'physio-toolkit', false, trailingslashit( plugin_dir_path( __FILE__ ) ) . 'languages/' ); 
		}

		/**
		 * De-activate the QreativeShortcodes plugin on activation of Physio Toolkit plugin
		 * QreativeShortcodes files moved to this plugin so that plugin is not necessary anymore
		 */
		public function physio_toolkit_on_activation() {

			if ( class_exists( 'QreativeShortcodes' ) ) {
				deactivate_plugins( 'qreativeshortcodes/qreativeshortcodes.php' );
			}
		}
	}
	new PhysioToolkit();
}