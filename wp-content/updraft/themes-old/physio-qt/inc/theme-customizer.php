<?php
/**
*
* Contains methods for customizing the theme customization screen.
* 
* @package physio-qt
* @link http://codex.wordpress.org/Theme_Customization_API
*/
class Physio_QT_Customizer {

	/**
	 * Holds the instance of this class.
	 *
	 * @access private
	 * @var    object
	 */
	private static $instance;

	public function __construct() {

		// Enqueue live preview javascript in Theme Customizer admin screen
		add_action( 'customize_preview_init', array( $this, 'physio_qt_live_preview' ) );

		// Add options to the theme customizer.
		add_action( 'customize_register', array( $this, 'physio_qt_customize_register' ) );

		// Add selective refresh to custom settings
		add_action( 'customize_register', array( $this, 'physio_qt_customize_refresh' ) );

		// Output Customizer CSS & Custom CSS to WP Head
		add_action( 'wp_head', array( $this, 'physio_qt_customizer_css' ) );

		// Delete the cached data for this feature.
		add_action( 'customize_save_after' . get_stylesheet(), array( $this, 'physio_qt_cache_delete' ) );

		// Flush the rewrite rules after saving the customizer
		add_action( 'customize_save_after', 'flush_rewrite_rules' );
	}

	/**
	 * Add selective refresh to elements
	 */
	public function physio_qt_customize_refresh( $wp_customize ) {

		// Abort if selective refresh is not available.
		if ( ! isset( $wp_customize->selective_refresh ) ) {
			return;
		}

		$wp_customize->selective_refresh->add_partial( 'featured_button_text', array(
			'selector' 				=> '.header .header-wrapper .featured-button a',
			'render_callback' 		=> function() {
				return get_theme_mod( 'featured_button_text' );
			},
		) );

		$wp_customize->selective_refresh->add_partial( 'bottom_footer_left', array(
			'selector' 				=> '.footer .footer--bottom .bottom-left',
			'render_callback' 		=> function() {
				return get_theme_mod( 'bottom_footer_left' );
			},
		) );
	}

	/**
	* This hooks into 'customize_register' (available as of WP 3.4) and allows
	* you to add new sections and controls to the Theme Customize screen.
	* 
	* Note: To enable instant preview, we have to actually write a bit of custom
	* javascript. See live_preview() for more.
	*  
	* @see add_action('customize_register',$func)
	*/
	public function physio_qt_customize_register( $wp_customize ) {

		// Add Custom Panel to Live Customizer for Theme Options
		$wp_customize->add_panel( 'theme_panel', array(
			'title'       			=> esc_html__( 'Theme Options', 'physio-qt' ),
			'priority'    			=> 10,
		) );

		// Add Custom Sections to the Theme Panel
		$wp_customize->add_section( 'section_logo', array(
			'title'       			=> esc_html__( 'Logo', 'physio-qt' ),
			'priority'    			=> 10,
			'panel'       			=> 'theme_panel',
		) );
		
		$wp_customize->add_section( 'section_topbar', array(
			'title' 	  			=> esc_html__( 'Topbar', 'physio-qt' ),
			'priority'    			=> 15,
			'panel'       			=> 'theme_panel',
		) );
		
		$wp_customize->add_section( 'section_header', array(
			'title' 	  			=> esc_html__( 'Header', 'physio-qt' ),
			'priority'    			=> 16,
			'panel'       			=> 'theme_panel',
		) );
	    
	    $wp_customize->add_section( 'section_navigation', array(
			'title' 	  			=> esc_html__( 'Navigation', 'physio-qt' ),
			'priority' 	  			=> 20,
			'panel'       			=> 'theme_panel',
		) );
		
		$wp_customize->add_section( 'section_mobile_navigation', array(
			'title' 	  			=> esc_html__( 'Mobile Navigation', 'physio-qt' ),
			'description' 			=> esc_html__( 'Resize the browser or click the mobile icon at the bottom to enable mobile view', 'physio-qt' ),
			'priority' 	  			=> 25,
			'panel'       			=> 'theme_panel',
		) );
		
		$wp_customize->add_section( 'section_slider', array(
			'title' 	  			=> esc_html__( 'Slider', 'physio-qt' ),
			'description' 			=> esc_html__( 'Color settings for the homepage slider', 'physio-qt' ),
			'priority' 	  			=> 30,
			'panel'       			=> 'theme_panel',
		) );
	    
	    $wp_customize->add_section( 'section_page_header', array(
			'title' 	  			=> esc_html__( 'Page Header', 'physio-qt' ),
			'priority' 	  			=> 35,
			'panel'       			=> 'theme_panel',
		) );
	    
	    $wp_customize->add_section( 'section_breadcrumbs', array(
			'title'		  			=> esc_html__( 'Breadcrumbs', 'physio-qt' ),
			'priority' 	  			=> 40,
			'panel'       			=> 'theme_panel',
		) );
		
		$wp_customize->add_section( 'section_theme_colors', array(
			'title'		  			=> esc_html__( 'Layout &amp; Colors', 'physio-qt' ),
			'priority' 	  			=> 45,
			'panel'       			=> 'theme_panel',
		) );
		
		$wp_customize->add_section( 'section_blog', array(
			'title'		  			=> esc_html__( 'Blog', 'physio-qt' ),
			'priority' 	  			=> 50,
			'panel'       			=> 'theme_panel',
		) );

	    if ( physio_qt_woocommerce_active() ) {
	        $wp_customize->add_section( 'section_shop', array(
	            'title'		  		=> esc_html__( 'Shop', 'physio-qt' ),
	            'priority' 	  		=> 55,
	            'panel'       		=> 'theme_panel',
	        ) );
		}
	    
	    $wp_customize->add_section( 'section_footer', array(
			'title'		  			=> esc_html__( 'Footer', 'physio-qt' ),
			'priority' 	  			=> 60,
			'panel'       			=> 'theme_panel',
		) );
		
		$wp_customize->add_section( 'section_bottom_footer', array(
			'title'		  			=> esc_html__( 'Bottom Footer', 'physio-qt' ),
			'priority' 	  			=> 65,
			'panel'       			=> 'theme_panel',
		) );

		$wp_customize->add_section( 'section_typography', array(
			'title' 				=> esc_html__( 'Typography', 'physio-qt' ),
			'description' 			=> sprintf( esc_html__( 'Change the default theme font to another with the settings below. If you\'re looking for more advanced font options please install the %s plugin or use the child theme to add custom fonts', 'physio-qt'  ), '<a href="'. esc_url( '//wordpress.org/plugins/easy-google-fonts/' ) .'" target="_blank">Easy Google Fonts</a>' ),
			'priority' 				=> 70,
			'panel' 				=> 'theme_panel',
		) );

		$wp_customize->add_section( 'section_other', array(
			'title'		  			=> esc_html__( '404 Page', 'physio-qt' ),
			'priority' 	  			=> 75,
			'panel'       			=> 'theme_panel',
		) );

	    $wp_customize->add_section( 'section_opening_hours', array(
            'title'		  			=> esc_html__( 'Opening Hours & Translation', 'physio-qt' ),
            'description' 			=> esc_html__( 'Set the global opening hours and translate the weekdays of the opening hour widget', 'physio-qt' ),
            'panel'       			=> 'theme_panel',
	    ) );

	    $wp_customize->add_section( 'section_fontawesome', array(
            'title'		  			=> esc_html__( 'Font Awesome', 'physio-qt' ),
            'description' 			=> esc_html__( 'Please only use this to upgrade from Font Awesome 4 to version 5.', 'physio-qt' ),
            'panel'       			=> 'theme_panel',
	    ) );

	    $wp_customize->add_section( 'section_acf', array(
            'title'		  			=> esc_html__( 'ACF', 'physio-qt' ),
            'panel'       			=> 'theme_panel',
	    ) );
	    

		// Section Settings: Logo
		$wp_customize->add_setting( 'logo', array(
			'default' 				=> get_theme_file_uri( '/assets/images/logo.png' ),
			'transport' 			=> 'refresh',
			'sanitize_callback' 	=> 'esc_url',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo', array(
            'label' 				=> esc_html__( 'Logo', 'physio-qt'),
            'description' 			=> esc_html__( 'Recommended height is not higher than 90 pixels', 'physio-qt' ),
            'section' 				=> 'section_logo',
            'settings' 				=> 'logo',
			'priority' 				=> 5,
		) ) );

		$wp_customize->add_setting( 'retina_logo', array(
			'transport' 			=> 'refresh',
			'sanitize_callback' 	=> 'esc_url',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'retina_logo', array(
        	'label' 				=> esc_html__('Logo retina (2x)', 'physio-qt' ),
            'description' 			=> esc_html__( 'Please do not upload the regular logo here. Only add a logo that is exact 2x the size of the regular logo', 'physio-qt' ),
            'section' 				=> 'section_logo',
            'settings' 				=> 'retina_logo',
			'priority' 				=> 10,
		) ) );

		$wp_customize->add_setting( 'logo_margin_top', array(
	    	'transport' 			=> 'refresh',
	    	'sanitize_callback' 	=> 'wp_kses_post',
		) );
		$wp_customize->add_control( 'logo_margin_top', array(
		    'label' 				=> esc_html__( 'Logo margin top', 'physio-qt'),
		    'description' 			=> esc_html__( 'Change the logo top margin. Please don\'t include px ', 'physio-qt' ),
	    	'type' 					=> 'number',
		    'section' 				=> 'section_logo',
		    'settings' 				=> 'logo_margin_top',
		    'priority' 				=> 15,
		    'input_attrs'			=> array(
				'min'  				=> 0,
				'max'  				=> 100,
				'step' 				=> 5,
			),
		) );

		$wp_customize->add_setting( 'logo_width', array(
	    	'transport' 			=> 'refresh',
	    	'sanitize_callback' 	=> 'wp_kses_post',
		) );
		$wp_customize->add_control( 'logo_width', array(
		    'label' 				=> esc_html__( 'Logo width', 'physio-qt'),
		    'description' 			=> esc_html__( 'Change the logo width. Please don\'t include px ', 'physio-qt' ),
	    	'type' 					=> 'number',
		    'section' 				=> 'section_logo',
		    'settings' 				=> 'logo_width',
		    'priority' 				=> 20,
		    'input_attrs'			=> array(
				'min'  				=> 1,
				'max'  				=> 500,
				'step' 				=> 10,
			),
		) );


		// Section Settings: Topbar
		$wp_customize->add_setting( 'show_topbar', array(
        	'default' 				=> 'show',
        	'transport' 			=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );
		$wp_customize->add_control( 'show_topbar', array(
			'label' 				=> esc_html__( 'Topbar', 'physio-qt' ),
			'description' 			=> esc_html__( 'Show or hide the topbar', 'physio-qt' ),
			'section' 				=> 'section_topbar',
			'settings' 				=> 'show_topbar',
			'type' 					=> 'select',
			'choices' 				=> array(
				'show' 				=> esc_html__( 'Show', 'physio-qt' ),
				'hide' 				=> esc_html__( 'Hide all', 'physio-qt' ),
				'hide_mobile' 		=> esc_html__( 'Hide mobile only', 'physio-qt' ),
			),
			'priority' 				=> 5,
		) );

		$wp_customize->add_setting( 'topbar_bg', array( 
			'default' 				=> '#707780',
		    'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'topbar_bg', array(
			'label' 				=> esc_html__( 'Background color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the background color of the topbar', 'physio-qt' ),
			'section' 				=> 'section_topbar',
			'settings' 				=> 'topbar_bg',
			'priority' 				=> 10,
		) ) );

		$wp_customize->add_setting( 'topbar_text_color', array(
		    'default' 				=> '#C1C7CE',
	        'transport' 			=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color'
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'topbar_text_color', array(
			'label' 				=> esc_html__( 'Text color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the text color of the topbar', 'physio-qt' ),
			'section' 				=> 'section_topbar',
			'settings' 				=> 'topbar_text_color',
			'priority' 				=> 20,
		) ) );

		$wp_customize->add_setting( 'topbar_link_color', array(
		    'default' 				=> '#C1C7CE',
	        'transport' 			=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color'
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'topbar_link_color', array(
			'label' 				=> esc_html__( 'Link color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the link color of the topbar', 'physio-qt' ),
			'section' 				=> 'section_topbar',
			'settings' 				=> 'topbar_link_color',
			'priority' 				=> 25,
		) ) );

		$wp_customize->add_setting( 'topbar_link_hover_color', array(
		    'default' 				=> '#ffffff',
	        'transport' 			=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color'
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'topbar_link_hover_color', array(
			'label' 				=> esc_html__( 'Link hover color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the link hover color of the topbar', 'physio-qt' ),
			'section' 				=> 'section_topbar',
			'settings' 				=> 'topbar_link_hover_color',
			'priority' 				=> 30,
		) ) );

		$wp_customize->add_setting( 'topbar_submenu_background_color', array(
		    'default' 				=> '#56afd5',
	        'transport' 			=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color'
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'topbar_submenu_background_color', array(
			'label' 				=> esc_html__( 'Submenu background color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the submenu background color from menu\'s in the topbar', 'physio-qt' ),
			'section' 				=> 'section_topbar',
			'settings' 				=> 'topbar_submenu_background_color',
			'priority' 				=> 35,
		) ) );

		$wp_customize->add_setting( 'topbar_submenu_text_color', array(
		    'default' 				=> '#B5DCED',
	        'transport' 			=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color'
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'topbar_submenu_text_color', array(
			'label' 				=> esc_html__( 'Submenu text color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the submenu text color from menu\'s in the topbar', 'physio-qt' ),
			'section' 				=> 'section_topbar',
			'settings' 				=> 'topbar_submenu_text_color',
			'priority' 				=> 40,
		) ) );

		// Section Settings: Header
		$wp_customize->add_setting( 'header_mobile_background_color', array(
		    'default' 				=> '#ffffff',
		    'transport' 			=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_mobile_background_color', array(
			'label' 				=> esc_html__( 'Background color mobile', 'physio-qt' ),
			'section' 				=> 'section_header',
			'settings' 				=> 'header_mobile_background_color',
		) ) );

		$wp_customize->add_setting( 'header_desktop_background_color', array(
		    'default' 				=> '#ffffff',
	        'transport' 			=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_desktop_background_color', array(
			'label' 				=> esc_html__( 'Background color desktop', 'physio-qt' ),
			'section' 				=> 'section_header',
			'settings' 				=> 'header_desktop_background_color',
		) ) );

		$wp_customize->add_setting( 'featured_button_text', array(
	    	'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'wp_kses_post',
		) );
		$wp_customize->add_control( 'featured_button_text', array(
		    'label' 				=> esc_html__( 'Button text', 'physio-qt' ),
		    'description'    		=> esc_html__( 'Add a cta button to the header next to the menu', 'physio-qt' ),
		    'section' 				=> 'section_header',
		    'settings' 				=> 'featured_button_text',
		    'type' 					=> 'text',
		    'active_callback' 		=> array( $this, 'featured_button_isset_to_hide' ),
		) );

		$wp_customize->add_setting( 'featured_button_url', array(
	    	'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'wp_kses_post',
		) );

		$wp_customize->add_control( 'featured_button_url', array(
		    'label' 				=> esc_html__( 'Button link', 'physio-qt' ),
		    'description' 			=> esc_html__( 'Add the full link (e.g. http://yourdomain.com)', 'physio-qt' ),
		    'section' 				=> 'section_header',
		    'settings' 				=> 'featured_button_url',
		    'type' 					=> 'text',
		    'active_callback' 		=> array( $this, 'featured_button_isset_to_hide' ),
		) );

		$wp_customize->add_setting( 'featured_button_target', array(
			'default'  				=> 'self',
			'transport'				=> 'refresh',
			'sanitize_callback' 	=> 'wp_kses_post',
		) );

		$wp_customize->add_control( 'featured_button_target', array(
			'label'       			=> esc_html__( 'Button target', 'physio-qt' ),
			'description' 			=> esc_html__( 'Open link in a new browser window?', 'physio-qt' ),
			'section'				=> 'section_header',
			'settings'				=> 'featured_button_target',
			'type'					=> 'select',
			'choices'	  			=> array(
				'self'					=> esc_html__( 'Same window', 'physio-qt' ),
				'blank'					=> esc_html__( 'New window', 'physio-qt' ),
			),
		) );

		$wp_customize->add_setting( 'featured_button_background_color', array(
		    'default'     			=> '#A175AA',
	        'transport'				=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'featured_button_background_color', array(
			'label'       			=> esc_html__( 'Button background color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the background color of the button', 'physio-qt' ),
			'section'     			=> 'section_header',
			'settings'    			=> 'featured_button_background_color',
			'active_callback' 		=> array( $this, 'featured_button_isset_to_hide' ),
		) ) );

		$wp_customize->add_setting( 'featured_button_text_color', array(
		    'default'     			=> '#ffffff',
	        'transport'				=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'featured_button_text_color', array(
			'label'       			=> esc_html__( 'Button text color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the text color of the button', 'physio-qt' ),
			'section'     			=> 'section_header',
			'settings'    			=> 'featured_button_text_color',
			'active_callback' 		=> array( $this, 'featured_button_isset_to_hide' ),
		) ) );

		$wp_customize->add_setting( 'featured_button', array(
        	'default'  				=> 'show',
        	'transport'				=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );

		$wp_customize->add_control( 'featured_button', array(
			'label'    				=> esc_html__( 'Show button on desktop', 'physio-qt' ),
			'section'  				=> 'section_header',
			'settings' 				=> 'featured_button',
			'type'     				=> 'select',
			'choices'  				=> array(
				'show'				=> esc_html__( 'Yes', 'physio-qt' ),
				'hide'				=> esc_html__( 'No', 'physio-qt' ),
			),
		) );

		$wp_customize->add_setting( 'featured_button_mobile', array(
        	'default'  				=> 'show',
        	'transport'				=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );

		$wp_customize->add_control( 'featured_button_mobile', array(
			'label'    				=> esc_html__( 'Show button on mobile', 'physio-qt' ),
			'section'  				=> 'section_header',
			'settings' 				=> 'featured_button_mobile',
			'type'     				=> 'select',
			'choices'  				=> array(
				'show'				=> esc_html__( 'Yes', 'physio-qt' ),
				'hide'				=> esc_html__( 'No', 'physio-qt' ),
			),
		) );

		$wp_customize->add_setting( 'header_widgets', array(
        	'default'  				=> 'show',
        	'transport'				=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );

		$wp_customize->add_control( 'header_widgets', array(
			'label'    				=> esc_html__( 'Bottom header', 'physio-qt' ),
			'description'    		=> esc_html__( 'Display the bottom header with contact details', 'physio-qt' ),
			'section'  				=> 'section_header',
			'settings' 				=> 'header_widgets',
			'type'     				=> 'select',
			'choices'  				=> array(
				'show'  			=> esc_html__( 'Show', 'physio-qt' ),
				'hide' 				=> esc_html__( 'Hide all', 'physio-qt' ),
				'hide-desktop' 		=> esc_html__( 'Hide desktop only', 'physio-qt' ),
				'hide-mobile' 		=> esc_html__( 'Hide mobile only', 'physio-qt' ),
			),
		) );

		$wp_customize->add_setting( 'header_widgets_absolute', array(
        	'default'  				=> 'overlay',
        	'transport'				=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );

		$wp_customize->add_control( 'header_widgets_absolute', array(
			'label'    				=> esc_html__( 'Bottom header background type', 'physio-qt' ),
			'description'    		=> esc_html__( 'Make the bottom header overlay the slider / page heading for transparent background', 'physio-qt' ),
			'section'  				=> 'section_header',
			'settings' 				=> 'header_widgets_absolute',
			'type'     				=> 'select',
			'choices'  				=> array(
				'overlay' 	 		=> esc_html__( 'Transparent background', 'physio-qt' ),
				'no_overlay' 		=> esc_html__( 'No transparent background', 'physio-qt' ),
			),
		) );

		$wp_customize->add_setting( 'header_widgets_background_opacity', array(
	    	'default' 				=> '0.8',
	    	'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'wp_kses_post',
		) );

		$wp_customize->add_control( 'header_widgets_background_opacity', array(
		    'label' 				=> esc_html__( 'Bottom header opacity', 'physio-qt' ),
		    'description' 			=> esc_html__( 'Opacity range is from 0 to 1 (example: 0.6)', 'physio-qt' ),
		    'section' 				=> 'section_header',
		    'settings' 				=> 'header_widgets_background_opacity',
		    'active_callback' 		=> array( $this, 'header_widgets_isset_to_overlay' ),
		) );

		$wp_customize->add_setting( 'header_widgets_background_color', array(
		    'default'     			=> '#ffffff',
	        'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_widgets_background_color', array(
			'label'       			=> esc_html__( 'Bottom header background color', 'physio-qt' ),
			'section'     			=> 'section_header',
			'settings'    			=> 'header_widgets_background_color',
		) ) );

		$wp_customize->add_setting( 'header_widgets_text_color', array(
		    'default' 				=> '#7F7B77',
	        'transport' 			=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_widgets_text_color', array(
			'label'       			=> esc_html__( 'Bottom header text color', 'physio-qt' ),
			'section'     			=> 'section_header',
			'settings'    			=> 'header_widgets_text_color',
		) ) );

		$wp_customize->add_setting( 'header_widgets_margin_desktop_large', array(
			'default' 				=> '93',
	    	'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'wp_kses_post',
		) );

		$wp_customize->add_control( 'header_widgets_margin_desktop_large', array(
		    'label' 				=> esc_html__( 'Bottom header space (desktop)', 'physio-qt' ),
		    'description' 			=> esc_html__( 'Change the space between the icon boxes in the bottom header for desktop screen. Default is 93', 'physio-qt' ),
		    'section' 				=> 'section_header',
		    'settings' 				=> 'header_widgets_margin_desktop_large',
		) );

		$wp_customize->add_setting( 'header_widgets_margin_desktop_small', array(
			'default' 				=> '20',
	    	'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'wp_kses_post',
		) );

		$wp_customize->add_control( 'header_widgets_margin_desktop_small', array(
		    'label' 				=> esc_html__( 'Bottom header space (tablet)', 'physio-qt' ),
		    'description' 			=> esc_html__( 'Change the space between the icon boxes in the bottom header for tablet screen. Default is 20', 'physio-qt' ),
		    'section' 				=> 'section_header',
		    'settings' 				=> 'header_widgets_margin_desktop_small',
		) );


		// Section Settings: Navigation
		$wp_customize->add_setting( 'nav_position', array(
        	'default'  				=> 'static',
        	'transport'				=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );

		$wp_customize->add_control( 'nav_position', array(
			'label'    				=> esc_html__( 'Sticky navigation', 'physio-qt' ),
			'description'    		=> esc_html__( 'Enable the sticky navigation', 'physio-qt' ),
			'section'  				=> 'section_navigation',
			'settings' 				=> 'nav_position',
			'type'     				=> 'select',
			'choices'  				=> array(
				'static'  			=> esc_html__( 'Static', 'physio-qt' ),
				'sticky' 			=> esc_html__( 'Sticky', 'physio-qt' ),
			),
		) );

		$wp_customize->add_setting( 'nav_link_color', array(
		    'default'    			=> '#828282',
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_link_color', array(
			'label'      			=> esc_html__( 'Link color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the color of the parent links', 'physio-qt' ),
			'section'     			=> 'section_navigation',
			'settings'    			=> 'nav_link_color',
		) ) );

		$wp_customize->add_setting( 'nav_link_hover_color', array(
		    'default'    			=> '#56afd5',
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_link_hover_color', array(
			'label'      			=> esc_html__( 'Link hover color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the hover color of the parent links', 'physio-qt' ),
			'section'     			=> 'section_navigation',
			'settings'    			=> 'nav_link_hover_color',
		) ) );

		$wp_customize->add_setting( 'nav_link_active_color', array(
		    'default'    			=> '#56afd5',
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_link_active_color', array(
			'label'      			=> esc_html__( 'Link active color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the color of the active parent link', 'physio-qt' ),
			'section'     			=> 'section_navigation',
			'settings'    			=> 'nav_link_active_color',
		) ) );

		$wp_customize->add_setting( 'nav_submenu_bg', array(
			'default' 				=> '#9A65A5',
			'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_submenu_bg', array(
			'label'       			=> esc_html__( 'Submenu background color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the background color of the submenu\'s', 'physio-qt' ),
			'section'     			=> 'section_navigation',
			'settings'    			=> 'nav_submenu_bg',
		) ) );

		$wp_customize->add_setting( 'nav_submenu_link_color', array(
	    	'default'     			=> '#ffffff',
	    	'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_submenu_link_color', array(
			'label'       			=> esc_html__( 'Submenu link color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the link color of the submenu\'s', 'physio-qt' ),
			'section'     			=> 'section_navigation',
			'settings'    			=> 'nav_submenu_link_color',
		) ) );

		$wp_customize->add_setting( 'nav_submenu_bg_hover', array(
			'default' 				=> Physio_QT_Customizer::adjust_color( get_theme_mod( 'nav_submenu_bg', '#9A65A5' ), -10 ),
			'transport'				=> 'refresh',
			'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_submenu_bg_hover', array(
			'label'       			=> esc_html__( 'Submenu background color hover', 'physio-qt' ),
			'description'			=> esc_html__( 'Change the hover background color of the submenu\'s', 'physio-qt' ),
			'section'     			=> 'section_navigation',
			'settings'    			=> 'nav_submenu_bg_hover',
		) ) );

		$wp_customize->add_setting( 'nav_submenu_link_color_hover', array(
			'default'     			=> '#fff',
			'transport'				=> 'refresh',
			'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_submenu_link_color_hover', array(
			'label'       			=> esc_html__( 'Submenu link color hover', 'physio-qt' ),
			'description'			=> esc_html__( 'Change the link hover color of the submenu\'s', 'physio-qt' ),
			'section'     			=> 'section_navigation',
			'settings'    			=> 'nav_submenu_link_color_hover',
		) ) );

		$wp_customize->add_setting( 'nav_double_tap', array(
        	'default'  				=> 'yes',
        	'transport'				=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );

		$wp_customize->add_control( 'nav_double_tap', array(
			'label'       			=> esc_html__( 'Enable doubletap touch laptop', 'physio-qt' ),
			'description' 			=> esc_html__( 'When using a touch laptop the main navigation parent links will open the submenu on the first tab, the second tab will go to the actual page', 'physio-qt' ),
			'section'  				=> 'section_navigation',
			'settings' 				=> 'nav_double_tap',
			'type'     				=> 'select',
			'choices'  				=> array(
				'yes'					=> esc_html__( 'Yes', 'physio-qt' ),
				'no' 					=> esc_html__( 'No', 'physio-qt' ),
			),
		) );


		// Section Settings: Mobile Navigation
		$wp_customize->add_setting( 'nav_mobile_link_color', array(
		    'default'    			=> '#ffffff',
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_mobile_link_color', array(
			'label'      			=> esc_html__( 'Mobile link color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the color of the parent links', 'physio-qt' ),
			'section'     			=> 'section_mobile_navigation',
			'settings'    			=> 'nav_mobile_link_color',
		) ) );

		$wp_customize->add_setting( 'nav_mobile_link_active_color', array(
		    'default'    			=> '#ffffff',
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_mobile_link_active_color', array(
			'label'      			=> esc_html__( 'Mobile link active color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the color of the active parent link', 'physio-qt' ),
			'section'     			=> 'section_mobile_navigation',
			'settings'    			=> 'nav_mobile_link_active_color',
		) ) );

		$wp_customize->add_setting( 'nav_mobile_link_background_color', array(
		    'default'    			=> '#9A65A5',
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_mobile_link_background_color', array(
			'label'      			=> esc_html__( 'Mobile link background color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the background color of the parent links', 'physio-qt' ),
			'section'     			=> 'section_mobile_navigation',
			'settings'    			=> 'nav_mobile_link_background_color',
		) ) );

		$wp_customize->add_setting( 'nav_mobile_submenu_bg', array(
		    'default'    			=> '#935c9e',
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_mobile_submenu_bg', array(
			'label'      			=> esc_html__( 'Mobile submenu background color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the background color of the submenu links', 'physio-qt' ),
			'section'     			=> 'section_mobile_navigation',
			'settings'    			=> 'nav_mobile_submenu_bg',
		) ) );

		$wp_customize->add_setting( 'nav_mobile_submenu_link_color', array(
		    'default'    			=> '#ffffff',
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'wp_kses_post',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_mobile_submenu_link_color', array(
			'label'      			=> esc_html__( 'Mobile submenu link color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the text color of the submenu links', 'physio-qt' ),
			'section'     			=> 'section_mobile_navigation',
			'settings'    			=> 'nav_mobile_submenu_link_color',
		) ) );

		$wp_customize->add_setting( 'nav_mobile_submenu_active_link_color', array(
		    'default'    			=> '#ffffff',
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'wp_kses_post',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_mobile_submenu_active_link_color', array(
			'label'      			=> esc_html__( 'Mobile submenu active link color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the color of the active submenu link', 'physio-qt' ),
			'section'     			=> 'section_mobile_navigation',
			'settings'    			=> 'nav_mobile_submenu_active_link_color',
		) ) );

		$wp_customize->add_setting( 'nav_mobile_menu_bg', array(
		    'default'    			=> '#56afd5',
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_mobile_menu_bg', array(
			'label'      			=> esc_html__( 'Menu toggle background', 'physio-qt' ),
			'description'			=> esc_html__( 'Change the background color of the mobile navigation toggle button', 'physio-qt' ),
			'section'     			=> 'section_mobile_navigation',
			'settings'    			=> 'nav_mobile_menu_bg',
		) ) );

		$wp_customize->add_setting( 'nav_mobile_menu_text', array(
		    'default'    			=> '#ffffff',
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_mobile_menu_text', array(
			'label'      			=> esc_html__( 'Menu toggle icon', 'physio-qt' ),
			'description'			=> esc_html__( 'Change the color of the mobile navigation toggle button', 'physio-qt' ),
			'section'     			=> 'section_mobile_navigation',
			'settings'    			=> 'nav_mobile_menu_text',
		) ) );

		$wp_customize->add_setting( 'nav_mobile_submenu_toggle_bg', array(
		    'default'    			=> 'transparent',
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_mobile_submenu_toggle_bg', array(
			'label'      			=> esc_html__( 'Submenu toggle button', 'physio-qt' ),
			'description'			=> esc_html__( 'Change the background color of the submenu toggle icon', 'physio-qt' ),
			'section'     			=> 'section_mobile_navigation',
			'settings'    			=> 'nav_mobile_submenu_toggle_bg',
		) ) );

		$wp_customize->add_setting( 'nav_mobile_submenu_toggle_bg_active', array(
		    'default'    			=> '#935c9e',
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_mobile_submenu_toggle_bg_active', array(
			'label'      			=> esc_html__( 'Submenu toggle button active', 'physio-qt' ),
			'description'			=> esc_html__( 'Change the background color of the active submenu toggle', 'physio-qt' ),
			'section'     			=> 'section_mobile_navigation',
			'settings'    			=> 'nav_mobile_submenu_toggle_bg_active',
		) ) );

		$wp_customize->add_setting( 'nav_mobile_submenu_toggle_icon', array(
		    'default'    			=> '#ffffff',
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_mobile_submenu_toggle_icon', array(
			'label'      			=> esc_html__( 'Submenu toggle icon', 'physio-qt' ),
			'description'			=> esc_html__( 'Change the color of the submenu toggle icon', 'physio-qt' ),
			'section'     			=> 'section_mobile_navigation',
			'settings'    			=> 'nav_mobile_submenu_toggle_icon',
		) ) );


		// Section Settings: Slider
		$wp_customize->add_setting( 'slider_small_heading_color', array(
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'slider_small_heading_color', array(
			'label'      			=> esc_html__( 'Top heading color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the color of the small top heading', 'physio-qt' ),
			'section'     			=> 'section_slider',
			'settings'    			=> 'slider_small_heading_color',
			'priority'    			=> 10,
		) ) );

		$wp_customize->add_setting( 'slider_heading_color', array(
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'slider_heading_color', array(
			'label'      			=> esc_html__( 'Heading color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the color of the heading', 'physio-qt' ),
			'section'     			=> 'section_slider',
			'settings'    			=> 'slider_heading_color',
			'priority'    			=> 15,
		) ) );

		$wp_customize->add_setting( 'slider_content_color', array(
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'slider_content_color', array(
			'label'      			=> esc_html__( 'Content color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the color of the content text', 'physio-qt' ),
			'section'     			=> 'section_slider',
			'settings'    			=> 'slider_content_color',
			'priority'    			=> 20,
		) ) );

		$wp_customize->add_setting( 'slider_primary_button_background_color', array(
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'slider_primary_button_background_color', array(
			'label'      			=> esc_html__( 'Primary button background color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the background color of the primary button', 'physio-qt' ),
			'section'     			=> 'section_slider',
			'settings'    			=> 'slider_primary_button_background_color',
			'priority'    			=> 25,
		) ) );

		$wp_customize->add_setting( 'slider_primary_button_color', array(
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'slider_primary_button_color', array(
			'label'      			=> esc_html__( 'Primary button color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the text color of the primary button', 'physio-qt' ),
			'section'     			=> 'section_slider',
			'settings'    			=> 'slider_primary_button_color',
			'priority'    			=> 30,
		) ) );

		$wp_customize->add_setting( 'slider_control_background_color', array(
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'slider_control_background_color', array(
			'label'      			=> esc_html__( 'Controls background color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the background color of the slider controls', 'physio-qt' ),
			'section'     			=> 'section_slider',
			'settings'    			=> 'slider_control_background_color',
			'priority'    			=> 35,
		) ) );

		$wp_customize->add_setting( 'slider_control_color', array(
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'slider_control_color', array(
			'label'      			=> esc_html__( 'Controls arrow color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the color of the slider controls icon', 'physio-qt' ),
			'section'     			=> 'section_slider',
			'settings'    			=> 'slider_control_color',
			'priority'    			=> 40,
		) ) );

		$wp_customize->add_setting( 'slider_mobile_background_color', array(
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'slider_mobile_background_color', array(
			'label'      			=> esc_html__( '(Mobile) Caption background color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the background color of the slider captions on mobile', 'physio-qt' ),
			'section'     			=> 'section_slider',
			'settings'    			=> 'slider_mobile_background_color',
			'priority'    			=> 45,
		) ) );


		// Section Settings: Page Header Area
		$wp_customize->add_setting( 'page_header', array(
        	'default'  				=> 'show',
        	'transport'				=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );

		$wp_customize->add_control( 'page_header', array(
			'label'    				=> esc_html__( 'Page header', 'physio-qt' ),
			'description'    		=> esc_html__( 'Show or hide the page header on all pages', 'physio-qt' ),
			'section'  				=> 'section_page_header',
			'settings' 				=> 'page_header',
			'type'     				=> 'select',
			'choices'  				=> array(
				'show'				=> esc_html__( 'Show', 'physio-qt' ),
				'hide'				=> esc_html__( 'Hide', 'physio-qt' ),
			),
		) );

		$wp_customize->add_setting( 'page_header_title_align', array(
        	'default'  				=> 'left',
        	'transport'				=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );

		$wp_customize->add_control( 'page_header_title_align', array(
			'label'    				=> esc_html__( 'Page header alignment', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the alignment of the page header titles', 'physio-qt' ),
			'section'  				=> 'section_page_header',
			'settings' 				=> 'page_header_title_align',
			'type'    				=> 'select',
			'choices'  				=> array(
				'left'  			=> esc_html__( 'Left', 'physio-qt'),
				'center' 			=> esc_html__( 'Center', 'physio-qt'),
				'right' 	    	=> esc_html__( 'Right', 'physio-qt'),
			),
		) );

		$wp_customize->add_setting( 'page_header_title_color', array(
	    	'default'     			=> '#333333',
	    	'transport'				=> 'postMessage',
	    	'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'page_header_title_color', array(
			'label'      			=> esc_html__( 'Page title color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the color of the page title', 'physio-qt' ),
			'section'    			=> 'section_page_header',
			'settings'   			=> 'page_header_title_color',
		) ) );

		$wp_customize->add_setting( 'page_header_subtitle_color', array(
	    	'default'     			=> '#999999',
	    	'transport'				=> 'postMessage',
	    	'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'page_header_subtitle_color', array(
			'label'      			=> esc_html__( 'Subtitle color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the color of the subtitle', 'physio-qt' ),
			'section'    			=> 'section_page_header',
			'settings'   			=> 'page_header_subtitle_color',
		) ) );

		$wp_customize->add_setting( 'page_header_background_color', array(
	    	'default'     			=> '#F5F8FB',
	    	'transport'				=> 'postMessage',
	    	'sanitize_callback' 	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'page_header_background_color', array(
			'label'      			=> esc_html__( 'Background color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the background color of the page header', 'physio-qt' ),
			'section'    			=> 'section_page_header',
			'settings'   			=> 'page_header_background_color',
		) ) );

		$wp_customize->add_setting( 'page_header_background_image', array(
			'transport'				=> 'refresh',
			'sanitize_callback' 	=> 'esc_url',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'page_header_background_image', array(
            'label' 	 			=> esc_html__( 'Background image', 'physio-qt' ),
            'description'    		=> esc_html__( 'Add a background image to the page header', 'physio-qt' ),
            'section' 	 			=> 'section_page_header',
            'settings' 	 			=> 'page_header_background_image',
		) ) );

		$wp_customize->add_setting( 'page_header_bg_position', array(
			'default'  				=> 'left center',
			'transport'				=> 'refresh',
			'sanitize_callback' 	=> 'sanitize_text_field',
		) );

		$wp_customize->add_control( 'page_header_bg_position', array(
			'label'       			=> esc_html__( 'Background position', 'physio-qt' ),
			'section'				=> 'section_page_header',
			'settings'				=> 'page_header_bg_position',
			'type'					=> 'select',
			'choices'				=> array(
				'left top' 				=> esc_html__( 'Left Top', 'physio-qt' ),
				'left center' 			=> esc_html__( 'Left Center', 'physio-qt' ),
				'left bottom' 			=> esc_html__( 'Left Bottom', 'physio-qt' ),
				'right top' 			=> esc_html__( 'Right Top', 'physio-qt' ),
				'right center' 			=> esc_html__( 'Right Center', 'physio-qt' ),
				'right bottom' 			=> esc_html__( 'Right Bottom', 'physio-qt' ),
				'center top' 			=> esc_html__( 'Center Top', 'physio-qt' ),
				'center center' 		=> esc_html__( 'Center Center', 'physio-qt' ),
				'center bottom' 		=> esc_html__( 'Center Bottom', 'physio-qt' ),
			),
		) );

		// Add page header setting: page header background image repeat
		$wp_customize->add_setting( 'page_header_bg_repeat', array(
			'default'  				=> 'repeat',
			'transport'				=> 'refresh',
			'sanitize_callback' 	=> 'sanitize_text_field',
		) );

		$wp_customize->add_control( 'page_header_bg_repeat', array(
			'label'      		 	=> esc_html__( 'Background repeat', 'physio-qt' ),
			'section'				=> 'section_page_header',
			'settings'				=> 'page_header_bg_repeat',
			'type'					=> 'select',
			'choices'				=> array(
				'no-repeat'  			=> esc_html__( 'No repeat', 'physio-qt' ),
				'repeat' 	 			=> esc_html__( 'Repeat', 'physio-qt' ),
			),
		) );

		// Add page header setting: page header background image size
		$wp_customize->add_setting( 'page_header_bg_size', array(
			'default'  				=> 'auto',
			'transport'				=> 'refresh',
			'sanitize_callback' 	=> 'sanitize_text_field',
		) );

		$wp_customize->add_control( 'page_header_bg_size', array(
			'label'       			=> esc_html__( 'Background size', 'physio-qt' ),
			'section'				=> 'section_page_header',
			'settings'				=> 'page_header_bg_size',
			'type'					=> 'select',
			'choices'				=> array(
				'auto' 					=> esc_html__( 'Default', 'physio-qt' ),
				'cover' 				=> esc_html__( 'Cover', 'physio-qt' ),
			),
		) );



		// Section Settings: Breadcrumbs
		$wp_customize->add_setting( 'breadcrumbs', array(
        	'default'  				=> 'show',
        	'transport'				=> 'refresh',
        	'sanitize_callback'	 => 'sanitize_text_field',
	    ) );
		$wp_customize->add_control( 'breadcrumbs', array(
			'label'    				=> esc_html__( 'Breadcrumbs', 'physio-qt' ),
			'description'    		=> esc_html__( 'Show or hide the breadcrumbs on all pages', 'physio-qt' ),
			'section'  				=> 'section_breadcrumbs',
			'settings' 				=> 'breadcrumbs',
			'type'    				=> 'select',
			'choices'  				=> array(
				'show'  			=> esc_html__( 'Show', 'physio-qt' ),
				'hide' 				=> esc_html__( 'Hide', 'physio-qt' ),
			),
			'priority' 				=> 1,
		) );

		$wp_customize->add_setting( 'breadcrumbs_background_color', array(
		    'default'    			=> '#ffffff',
		    'transport'	  			=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'breadcrumbs_background_color', array(
			'label'      			=> esc_html__( 'Background color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the background color of the breadcrumbs', 'physio-qt' ),
			'section'    			=> 'section_breadcrumbs',
			'settings'   			=> 'breadcrumbs_background_color',
			'priority'   			=> 5,
		) ) );

		$wp_customize->add_setting( 'breadcrumbs_text_color', array(
		    'default'    			=> '#cccccc',
		    'transport'	  			=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'breadcrumbs_text_color', array(
			'label'      			=> esc_html__( 'Text color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the color of the breadcrumb links', 'physio-qt' ),
			'section'    			=> 'section_breadcrumbs',
			'settings'   			=> 'breadcrumbs_text_color',
			'priority'   			=> 10,
		) ) );

		$wp_customize->add_setting( 'breadcrumbs_active_color', array(
		    'default'    			=> '#9A65A5',
		    'transport'	  			=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'breadcrumbs_active_color', array(
			'label'      			=> esc_html__( 'Active color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the color of the active breadcrumb link', 'physio-qt' ),
			'section'    			=> 'section_breadcrumbs',
			'settings'   			=> 'breadcrumbs_active_color',
			'priority'   			=> 15,
		) ) );

		// Section Settings: Theme Layout & Colors
		$wp_customize->add_setting( 'boxed_background', array(
			'default' 				=> '#ffffff',
			'transport'				=> 'postMessage',
			'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'boxed_background', array(
			'label'       			=> esc_html__( 'Content background color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the background color of the theme', 'physio-qt' ),
			'section'     			=> 'section_theme_colors',
			'settings'	  			=> 'boxed_background',
		) ) );

		$wp_customize->add_setting( 'text_color', array(
		    'default'     			=> '#999999',
		    'transport'	  			=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_color', array(
			'label'       			=> esc_html__( 'Text color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the color of the content texts', 'physio-qt' ),
			'section'     			=> 'section_theme_colors',
			'settings'    			=> 'text_color',
		) ) );

		$wp_customize->add_setting( 'widgettitle_color', array(
	    	'default'     			=> '#464646',
	    	'transport'				=> 'postMessage',
	    	'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'widgettitle_color', array(
			'label'       			=> esc_html__( 'Widget title color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the color of the widget titles', 'physio-qt' ),
			'section'     			=> 'section_theme_colors',
			'settings'    			=> 'widgettitle_color',
		) ) );

		$wp_customize->add_setting( 'primary_color', array(
	    	'default'     			=> '#56afd5',
	    	'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_color', array(
			'label'       			=> esc_html__( 'Primary color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the primary color scheme', 'physio-qt' ),
			'section'     			=> 'section_theme_colors',
			'settings'    			=> 'primary_color',
		) ) );

		$wp_customize->add_setting( 'secondary_color', array(
	    	'default'     			=> '#9A65A5',
	    	'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'secondary_color', array(
			'label'       			=> esc_html__( 'Secondary color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the secondary color scheme', 'physio-qt' ),
			'section'     			=> 'section_theme_colors',
			'settings'    			=> 'secondary_color',
		) ) );

		$wp_customize->add_setting( 'button_color', array(
	     	'default'     			=> '#9A65A5',
	     	'transport'				=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'button_color', array(
			'label'       			=> esc_html__( 'Button background color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the background color of the button', 'physio-qt' ),
			'section'     			=> 'section_theme_colors',
			'settings'    			=> 'button_color',
		) ) );

		$wp_customize->add_setting( 'button_color_hover', array(
	     	'default'     			=> '#935c9e',
	     	'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'button_color_hover', array(
				'label'       		=> esc_html__( 'Button hover background color', 'physio-qt' ),
				'description'		=> esc_html__( 'Change the background color of the button on hover', 'physio-qt' ),
				'section'     		=> 'section_theme_colors',
				'settings'    		=> 'button_color_hover',
		) ) );

		$wp_customize->add_setting( 'button_text_color', array(
	     	'default'     			=> '#ffffff',
	     	'transport'				=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'button_text_color', array(
			'label'       			=> esc_html__( 'Button text color', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the text color of the button', 'physio-qt' ),
			'section'     			=> 'section_theme_colors',
			'settings'    			=> 'button_text_color',
		) ) );

		// Add colors section setting: button layout
		$wp_customize->add_setting( 'button_layout', array(
			'default'  				=> '',
			'transport'				=> 'refresh',
			'sanitize_callback' 	=> 'sanitize_text_field',
		) );

		$wp_customize->add_control( 'button_layout', array(
			'label'       			=> esc_html__( 'Button layout', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the layout of the button', 'physio-qt' ),
			'section'				=> 'section_theme_colors',
			'settings'				=> 'button_layout',
			'type'					=> 'select',
			'choices' 				=> array(
				'' 						=> esc_html__( 'Rounded (default)', 'physio-qt' ),
				'btn-square' 			=> esc_html__( 'Square', 'physio-qt' ),
			),
		) );

		$wp_customize->add_setting( 'boxed_layout', array(
        	'default'  				=> 'wide',
        	'transport'				=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );
		$wp_customize->add_control( 'boxed_layout', array(
			'label'    				=> esc_html__( 'Boxed layout', 'physio-qt' ),
			'description'    		=> esc_html__( 'Enable the boxed layout', 'physio-qt' ),
			'section'  				=> 'section_theme_colors',
			'settings' 				=> 'boxed_layout',
			'type'    				=> 'select',
			'choices'  				=> array(
				'wide'  			=> esc_html__( 'Disable', 'physio-qt' ),
				'boxed' 			=> esc_html__( 'Enable', 'physio-qt' ),
			),
		) );

		$wp_customize->add_setting( 'scroll_to_top_button', array(
			'default'				=> 'show',
        	'transport'				=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );
		$wp_customize->add_control( 'scroll_to_top_button', array(
			'label'    				=> esc_html__( 'Scroll to top button', 'physio-qt' ),
			'description'    		=> esc_html__( 'Enable the scroll to top button', 'physio-qt' ),
			'section'  				=> 'section_theme_colors',
			'settings' 				=> 'scroll_to_top_button',
			'type'    				=> 'select',
			'choices' 				=> array(
				'show'  			=> esc_html__( 'Show', 'physio-qt' ),
				'hide'  			=> esc_html__( 'Hide', 'physio-qt' ),
			),
		) );


		// Section Settings: Blog
		$wp_customize->add_setting( 'blog_layout', array(
        	'default' 				=> 'default',
        	'transport' 			=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );
		$wp_customize->add_control( 'blog_layout', array(
			'label' 				=> esc_html__( 'Blog layout', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the layout of the blog post page', 'physio-qt' ),
			'section' 				=> 'section_blog',
			'settings' 				=> 'blog_layout',
			'type' 					=> 'select',
			'choices' 				=> array(
				'default' 				=> esc_html__( 'List (default)', 'physio-qt' ),
				'grid' 					=> esc_html__( 'Grid', 'physio-qt' ),
			),
		) );

		$wp_customize->add_setting( 'blog_columns', array(
        	'default' 				=> '2',
        	'transport' 			=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );
		$wp_customize->add_control( 'blog_columns', array(
			'label' 				=> esc_html__( 'Blog grid columns', 'physio-qt' ),
			'description'    		=> esc_html__( 'Change the amount of columns', 'physio-qt' ),
			'section' 				=> 'section_blog',
			'settings' 				=> 'blog_columns',
			'type' 					=> 'select',
			'choices' 				=> array(
				'2' 					=> esc_html__( '2 columns', 'physio-qt' ),
				'3' 					=> esc_html__( '3 columns', 'physio-qt' ),
				'4' 					=> esc_html__( '4 columns', 'physio-qt' ),
				'5' 					=> esc_html__( '5 columns', 'physio-qt' ),
				'6' 					=> esc_html__( '6 columns', 'physio-qt' ),
			),
			'active_callback' 		=> array( $this, 'physio_qt_blog_grid' ),
		) );

		$wp_customize->add_setting( 'blog_date_label', array(
        	'default' 				=> 'yes',
        	'transport' 			=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );
		$wp_customize->add_control( 'blog_date_label', array(
			'label' 				=> esc_html__( 'Display date on image', 'physio-qt' ),
			'description'    		=> esc_html__( 'Display the post date on the image', 'physio-qt' ),
			'section' 				=> 'section_blog',
			'settings' 				=> 'blog_date_label',
			'type' 					=> 'select',
			'choices' 				=> array(
				'yes' 				=> esc_html__( 'Show', 'physio-qt' ),
				'no' 				=> esc_html__( 'Hide', 'physio-qt' ),
			),
		) );

		$wp_customize->add_setting( 'blog_metadata', array(
        	'default' 				=> 'show',
        	'transport' 			=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );
		$wp_customize->add_control( 'blog_metadata', array(
			'label' 				=> esc_html__( 'Display post metadata', 'physio-qt' ),
			'description'    		=> esc_html__( 'Show or hide the blog post metadata', 'physio-qt' ),
			'section' 				=> 'section_blog',
			'settings' 				=> 'blog_metadata',
			'type' 					=> 'select',
			'choices' 				=> array(
				'show' 				=> esc_html__( 'Show', 'physio-qt' ),
				'hide' 				=> esc_html__( 'Hide', 'physio-qt' ),
			),
		) );

		$wp_customize->add_setting( 'blog_comments', array(
        	'default' 				=> 'show',
        	'transport' 			=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );
		$wp_customize->add_control( 'blog_comments', array(
			'label' 				=> esc_html__( 'Display comment section', 'physio-qt' ),
			'description'    		=> esc_html__( 'Show or hide the comment section on all blog posts', 'physio-qt' ),
			'section' 				=> 'section_blog',
			'settings' 				=> 'blog_comments',
			'type' 					=> 'select',
			'choices' 				=> array(
				'show' 				=> esc_html__( 'Show', 'physio-qt' ),
				'hide' 				=> esc_html__( 'Hide', 'physio-qt' ),
			),
		) );

		$wp_customize->add_setting( 'blog_written_by', array(
			'default' 				=> esc_html__( 'By ', 'physio-qt' ),
	    	'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'wp_kses_post',
		) );
		$wp_customize->add_control( 'blog_written_by', array(
	    	'label' 				=> esc_html__( 'Written by text', 'physio-qt' ),
	    	'description' 			=> esc_html__( 'Change the \'by\' text in the post meta', 'physio-qt' ),
	    	'section' 				=> 'section_blog',
	    	'settings' 				=> 'blog_written_by',
	    	'type' 					=> 'text',
		) );

		$wp_customize->add_setting( 'blog_read_more', array(
	    	'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'wp_kses_post',
		) );
		$wp_customize->add_control( 'blog_read_more', array(
	    	'label' 				=> esc_html__( 'Read more text', 'physio-qt' ),
	    	'description' 			=> esc_html__( 'Change the read more link text', 'physio-qt' ),
	    	'section' 				=> 'section_blog',
	    	'settings' 				=> 'blog_read_more',
	    	'type' 					=> 'text',
		) );

		$wp_customize->add_setting( 'blog_excerpt_length', array(
	    	'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'wp_kses_post',
		) );
		$wp_customize->add_control( 'blog_excerpt_length', array(
	    	'label' 				=> esc_html__( 'Custom excerpt length', 'physio-qt' ),
	    	'description' 			=> esc_html__( 'Change the excerpt length for the blog page posts (e.g. 80)', 'physio-qt' ),
	    	'section' 				=> 'section_blog',
	    	'settings' 				=> 'blog_excerpt_length',
	    	'type' 					=> 'text',
		) );



		// Section Settings: Shop
		if( physio_qt_woocommerce_active() ) {
			$wp_customize->add_setting( 'shop_products_per_page', array(
		    	'default' 				=> '8',
		    	'transport'				=> 'refresh',
		    	'sanitize_callback' 	=> 'wp_kses_post',
			) );
			$wp_customize->add_control( 'shop_products_per_page', array(
			    'label' 				=> esc_html__( 'Products per page', 'physio-qt'),
			    'section' 				=> 'section_shop',
			    'settings' 				=> 'shop_products_per_page',
			    'priority' 				=> 5,
			) );

			$wp_customize->add_setting( 'single_product_sidebar', array(
				'default'				=> 'right',
	        	'transport'				=> 'refresh',
	        	'sanitize_callback' 	=> 'wp_kses_post',
		    ) );
			$wp_customize->add_control( 'single_product_sidebar', array(
				'label'    				=> esc_html__( 'Sidebar on single product pages', 'physio-qt' ),
				'section'  				=> 'section_shop',
				'settings' 				=> 'single_product_sidebar',
				'type'    				=> 'select',
				'choices' 				=> array(
					'hide'  			=> esc_html__( 'Hide', 'physio-qt'),
					'left'  			=> esc_html__( 'Left', 'physio-qt'),
					'right' 			=> esc_html__( 'Right', 'physio-qt'),
				),
				'priority' 				=> 10,
			) );
			$wp_customize->add_setting( 'shop_product_zoom', array(
				'default'  				=> 'enable',
				'transport'				=> 'refresh',
				'sanitize_callback' 	=> 'wp_kses_post',
			) );

			$wp_customize->add_control( 'shop_product_zoom', array(
				'label'       			=> esc_html__( 'Single product image zoom', 'physio-qt' ),
				'description' 			=> esc_html__( 'Use the WooCommerce product image zoom. Changes are visible on the front-end', 'physio-qt' ),
				'section'				=> 'section_shop',
				'settings'				=> 'shop_product_zoom',
				'type'					=> 'select',
				'choices'	  			=> array(
					'enable'				=> esc_html__( 'Enable', 'physio-qt' ),
					'disable'				=> esc_html__( 'Disable', 'physio-qt' ),
				),
			) );
			$wp_customize->add_setting( 'shop_product_lightbox', array(
				'default'  				=> 'enable',
				'transport'				=> 'refresh',
				'sanitize_callback' 	=> 'wp_kses_post',
			) );

			$wp_customize->add_control( 'shop_product_lightbox', array(
				'label'       			=> esc_html__( 'Single product image lightbox', 'physio-qt' ),
				'description' 			=> esc_html__( 'Use the WooCommerce product image lightbox. Changes are visible on the front-end', 'physio-qt' ),
				'section'				=> 'section_shop',
				'settings'				=> 'shop_product_lightbox',
				'type'					=> 'select',
				'choices'	  			=> array(
					'enable'				=> esc_html__( 'Enable', 'physio-qt' ),
					'disable'				=> esc_html__( 'Disable', 'physio-qt' ),
				),
			) );
			$wp_customize->add_setting( 'shop_product_slider', array(
				'default'  				=> 'enable',
				'transport'				=> 'refresh',
				'sanitize_callback' 	=> 'wp_kses_post',
			) );

			$wp_customize->add_control( 'shop_product_slider', array(
				'label'       			=> esc_html__( 'Single product image gallery slider', 'physio-qt' ),
				'description' 			=> esc_html__( 'Use the WooCommerce product image gallery slider. Changes are visible on the front-end', 'physio-qt' ),
				'section'				=> 'section_shop',
				'settings'				=> 'shop_product_slider',
				'type'					=> 'select',
				'choices'	  			=> array(
					'enable'				=> esc_html__( 'Enable', 'physio-qt' ),
					'disable'				=> esc_html__( 'Disable', 'physio-qt' ),
				),
			) );
		}


		// Section Settings: Footer
		$wp_customize->add_setting( 'top_footer_columns', array( 
			'default' 				=> 4,
			'transport'				=> 'refresh',
			'sanitize_callback' 	=> 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'top_footer_columns', array(
			'type'        			=> 'select',
			'label'       			=> esc_html__( 'Top footer columns', 'physio-qt' ),
			'description' 			=> esc_html__( 'Select how many columns you want to display in the top footer', 'physio-qt' ),
			'section'     			=> 'section_footer',
			'settings'    			=> 'top_footer_columns',
			'choices' 				=> array(
				'0'						=> esc_html__( 'Hide top footer', 'physio-qt' ),
				'1'						=> esc_html__( '1', 'physio-qt' ),
				'2'						=> esc_html__( '2', 'physio-qt' ),
				'3'						=> esc_html__( '3', 'physio-qt' ),
				'4'						=> esc_html__( '4', 'physio-qt' ),
			),
		) );

		// Text Color
		$wp_customize->add_setting( 'top_footer_text_color', array(
			'default'				=> '#C1C7CE',
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'top_footer_text_color', array(
			'label'       			=> esc_html__( 'Top footer text color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the text color of the top footer', 'physio-qt' ),
			'section'     			=> 'section_footer',
			'settings'    			=> 'top_footer_text_color',
		) ) );

		// Background Color
		$wp_customize->add_setting( 'top_footer_background_color', array(
		    'transport'				=> 'refresh',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'top_footer_background_color',  array(
			'label'       			=> esc_html__( 'Top footer background color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the background color of the top footer', 'physio-qt' ),
			'section'     			=> 'section_footer',
			'settings'    			=> 'top_footer_background_color',
		) ) );

		$wp_customize->add_setting( 'main_footer_columns', array( 
			'default' 				=> 4,
			'transport'				=> 'refresh',
			'sanitize_callback' 	=> 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'main_footer_columns', array(
			'type'        			=> 'select',
			'label'       			=> esc_html__( 'Main footer columns', 'physio-qt' ),
			'description' 			=> esc_html__( 'Select how many columns you want to display in the middle footer', 'physio-qt' ),
			'section'     			=> 'section_footer',
			'settings'    			=> 'main_footer_columns',
			'choices' 				=> array(
				'0'						=> esc_html__( 'Hide main footer', 'physio-qt' ),
				'1'						=> esc_html__( '1', 'physio-qt' ),
				'2'						=> esc_html__( '2', 'physio-qt' ),
				'3'						=> esc_html__( '3', 'physio-qt' ),
				'4'						=> esc_html__( '4', 'physio-qt' ),
			),
		) );

		// Widget Title Color
		$wp_customize->add_setting( 'main_footer_widget_title_color', array(
		    'default'     			=> '#ffffff',
		    'transport'	  			=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'main_footer_widget_title_color', array(
			'label'       			=> esc_html__( 'Main footer widget title color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the widget title color of the main footer', 'physio-qt' ),
			'section'     			=> 'section_footer',
			'settings'    			=> 'main_footer_widget_title_color',
		) ) );

		// Text Color
		$wp_customize->add_setting( 'main_footer_text_color', array(
	    	'default'     			=> '#C1C7CE',
	    	'transport'	  			=> 'postMessage',
	    	'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'main_footer_text_color', array(
			'label'       			=> esc_html__( 'Main footer text color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the text color of the main footer', 'physio-qt' ),
			'section'     			=> 'section_footer',
			'settings'    			=> 'main_footer_text_color',
		) ) );

		$wp_customize->add_setting( 'main_footer_link_color', array(
	    	'default'     			=> '#C1C7CE',
	    	'transport'	  			=> 'postMessage',
	    	'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'main_footer_link_color', array(
			'label'       			=> esc_html__( 'Main footer link color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the link color of the main footer', 'physio-qt' ),
			'section'     			=> 'section_footer',
			'settings'    			=> 'main_footer_link_color',
		) ) );

		$wp_customize->add_setting( 'main_footer_link_color_hover', array(
	    	'default'     			=> '#ffffff',
	    	'transport'	  			=> 'postMessage',
	    	'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'main_footer_link_color_hover', array(
			'label'       			=> esc_html__( 'Main footer link color hover', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the link hover color of the main footer', 'physio-qt' ),
			'section'     			=> 'section_footer',
			'settings'    			=> 'main_footer_link_color_hover',
		) ) );

		// Background Color
		$wp_customize->add_setting( 'main_footer_background_color', array(
		    'transport'				=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'main_footer_background_color', array(
			'label'       			=> esc_html__( 'Main footer background color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the background color of the main footer', 'physio-qt' ),
			'section'     			=> 'section_footer',
			'settings'    			=> 'main_footer_background_color',
		) ) );

		// Background Image
		$wp_customize->add_setting( 'main_footer_background_image', array(
			'default' 				=> get_theme_file_uri( '/assets/images/footer_pattern.png' ),
			'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'esc_url',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'main_footer_background_image', array(
            'label' 	 			=> esc_html__( 'Footer background pattern', 'physio-qt' ), 
            'description' 			=> esc_html__( 'Change the background image of the footer', 'physio-qt' ),
            'section' 	 			=> 'section_footer',
            'settings' 				=> 'main_footer_background_image',
		) ) );


		// Section Settings: Bottom Footer
		$wp_customize->add_setting( 'bottom_footer_background_color', array(
			'default'				=> '#555A5F',
		    'transport'				=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bottom_footer_background_color', array(
			'label'       			=> esc_html__( 'Bottom footer background color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the background color of the bottom footer', 'physio-qt' ),
			'section'     			=> 'section_footer',
			'settings'    			=> 'bottom_footer_background_color',
		) ) );

		$wp_customize->add_setting( 'bottom_footer_text_color', array(
		    'default'     			=> '#909BA2',
		    'transport'	  			=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bottom_footer_text_color', array(
			'label'       			=> esc_html__( 'Bottom footer text color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the text color of the bottom footer', 'physio-qt' ),
			'section'     			=> 'section_footer',
			'settings'    			=> 'bottom_footer_text_color',
		) ) );

		$wp_customize->add_setting( 'bottom_footer_link_color', array(
		    'default'     			=> '#ffffff',
		    'transport'	  			=> 'postMessage',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bottom_footer_link_color', array(
			'label'       			=> esc_html__( 'Bottom footer link color', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the link color of the bottom footer', 'physio-qt' ),
			'section'     			=> 'section_footer',
			'settings'    			=> 'bottom_footer_link_color',
		) ) );

		$wp_customize->add_setting( 'bottom_footer_left', array(
	    	'default' 				=> 'Copyright 2018 Physio WP by Qreativethemes',
	    	'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'wp_kses_post',
		) );
		$wp_customize->add_control( 'bottom_footer_left', array(
		    'label' 				=> esc_html__( 'Bottom footer left text', 'physio-qt' ),
		    'description' 			=> esc_html__( 'Add a text to the left side of the bottom footer', 'physio-qt' ),
		    'section' 				=> 'section_footer',
		    'settings' 				=> 'bottom_footer_left',
		    'type' 					=> 'textarea',
		) );

		$wp_customize->add_setting( 'bottom_footer_right', array(
	    	'default' 				=> 'Schedule your appointment',
	    	'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'wp_kses_post',
		) );
		$wp_customize->add_control( 'bottom_footer_right', array(
	    	'label' 				=> esc_html__( 'Bottom footer right text', 'physio-qt' ),
	    	'description' 			=> esc_html__( 'Add a text to the right side of the bottom footer', 'physio-qt' ),
	    	'section' 				=> 'section_footer',
	    	'settings' 				=> 'bottom_footer_right',
	    	'type' 					=> 'textarea',
		) );

		// Get the Google Fonts for the font control
		require get_theme_file_path( '/inc/customizer-settings/google-font-settings.php' );

		// Section Settings: Typography
		$wp_customize->add_setting( 'theme_primary_font', array(
			'default' 			=> 'Open Sans',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'esc_attr',
		) );
		$wp_customize->add_control( 'theme_primary_font', array(
			'label'    			=> esc_html__( 'Primary font', 'physio-qt' ),
			'description'    	=> esc_html__( 'Change the content text font', 'physio-qt' ),
			'section'  			=> 'section_typography',
			'settings' 			=> 'theme_primary_font',
			'type'     			=> 'select',
			'choices' 			=> physio_qt_list_google_fonts(),
		) );

		$wp_customize->add_setting( 'theme_primary_font_size', array(
			'default' 			=> '14',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'theme_primary_font_size', array(
		    'label' 			=> esc_html__( 'Primary font size', 'physio-qt'),
		    'description'    	=> esc_html__( 'Please don\'t include px in your string', 'physio-qt' ),
	    	'type'		        => 'number',
		    'section' 			=> 'section_typography',
		    'settings' 			=> 'theme_primary_font_size',
		    'input_attrs' 		=> array(
				'min' 		=> 0,
				'max'  		=> 100,
				'step' 		=> 2,
			),
		) );

		$wp_customize->add_setting( 'theme_secondary_font', array(
			'default' 			=> 'Nunito',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'esc_attr',
		) );
		$wp_customize->add_control( 'theme_secondary_font', array(
			'label'    			=> esc_html__( 'Secondary font', 'physio-qt' ),
			'description'    	=> esc_html__( 'Change the heading and title font', 'physio-qt' ),
			'section'  			=> 'section_typography',
			'settings' 			=> 'theme_secondary_font',
			'type'     			=> 'select',
			'choices' 			=> physio_qt_list_google_fonts(),
		) );

		$wp_customize->add_setting( 'title_span_font_weight', array(
        	'default' 				=> 'normal',
        	'transport' 			=> 'refresh',
        	'sanitize_callback' 	=> 'sanitize_text_field',
	    ) );
		$wp_customize->add_control( 'title_span_font_weight', array(
			'label' 				=> esc_html__( 'First word widget title', 'physio-qt' ),
			'description' 			=> esc_html__( 'Change the font weight of the first widget title word', 'physio-qt' ),
			'section' 				=> 'section_typography',
			'settings' 				=> 'title_span_font_weight',
			'type' 					=> 'select',
			'choices' 				=> array(
				'normal' 			=> esc_html__( 'Normal weight', 'physio-qt' ),
				'bold' 				=> esc_html__( 'Bold weight', 'physio-qt' ),
			),
		) );

		$wp_customize->add_setting( 'theme_widget_title_size_large', array(
			'default' 			=> '',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'theme_widget_title_size_large', array(
		    'label' 			=> esc_html__( 'Large widget title size', 'physio-qt' ),
		    'description' 		=> esc_html__( 'Please don\'t include px in your string', 'physio-qt' ),
	    	'type'		        => 'number',
		    'section' 			=> 'section_typography',
		    'settings' 			=> 'theme_widget_title_size_large',
		    'input_attrs' 		=> array(
				'min' 		=> 0,
				'max'  		=> 100,
				'step' 		=> 2,
			),
		) );

		$wp_customize->add_setting( 'theme_widget_title_size', array(
			'default' 			=> '',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'theme_widget_title_size', array(
		    'label' 			=> esc_html__( 'Normal widget title size', 'physio-qt' ),
		    'description' 		=> esc_html__( 'Please don\'t include px in your string', 'physio-qt' ),
	    	'type'		        => 'number',
		    'section' 			=> 'section_typography',
		    'settings' 			=> 'theme_widget_title_size',
		    'input_attrs' 		=> array(
				'min' 		=> 0,
				'max'  		=> 100,
				'step' 		=> 2,
			),
		) );

		$wp_customize->add_setting( 'theme_navigation_link_size', array(
			'default' 			=> '',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'theme_navigation_link_size', array(
		    'label' 			=> esc_html__( 'Navigation link size', 'physio-qt' ),
		    'description' 		=> esc_html__( 'Please don\'t include px in your string', 'physio-qt' ),
	    	'type'		        => 'number',
		    'section' 			=> 'section_typography',
		    'settings' 			=> 'theme_navigation_link_size',
		    'input_attrs' 		=> array(
				'min' 		=> 0,
				'max'  		=> 100,
				'step' 		=> 2,
			),
		) );

		$wp_customize->add_setting( 'theme_navigation_submenu_link_size', array(
			'default' 			=> '',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'theme_navigation_submenu_link_size', array(
		    'label' 			=> esc_html__( 'Navigation submenu link size', 'physio-qt' ),
		    'description' 		=> esc_html__( 'Please don\'t include px in your string', 'physio-qt' ),
	    	'type'		        => 'number',
		    'section' 			=> 'section_typography',
		    'settings' 			=> 'theme_navigation_submenu_link_size',
		    'input_attrs' 		=> array(
				'min' 		=> 0,
				'max'  		=> 100,
				'step' 		=> 2,
			),
		) );

		$wp_customize->add_setting( 'theme_page_heading_title_size', array(
			'default' 			=> '',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'theme_page_heading_title_size', array(
		    'label' 			=> esc_html__( 'Page header title size', 'physio-qt' ),
		    'description' 		=> esc_html__( 'Please don\'t include px in your string', 'physio-qt' ),
	    	'type'		        => 'number',
		    'section' 			=> 'section_typography',
		    'settings' 			=> 'theme_page_heading_title_size',
		    'input_attrs' 		=> array(
				'min' 		=> 0,
				'max'  		=> 100,
				'step' 		=> 2,
			),
		) );

		$wp_customize->add_setting( 'theme_footer_widget_title_size', array(
			'default' 			=> '',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'theme_footer_widget_title_size', array(
		    'label' 			=> esc_html__( 'Footer widget title size', 'physio-qt' ),
		    'description' 		=> esc_html__( 'Please don\'t include px in your string', 'physio-qt' ),
	    	'type'		        => 'number',
		    'section' 			=> 'section_typography',
		    'settings' 			=> 'theme_footer_widget_title_size',
		    'input_attrs' 		=> array(
				'min' 		=> 0,
				'max'  		=> 100,
				'step' 		=> 2,
			),
		) );

		$wp_customize->add_setting( 'theme_heading_one_size', array(
			'default' 			=> '',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'theme_heading_one_size', array(
		    'label' 			=> esc_html__( 'Header 1 (H1) size', 'physio-qt' ),
		    'description' 		=> esc_html__( 'Please don\'t include px in your string', 'physio-qt' ),
	    	'type'		        => 'number',
		    'section' 			=> 'section_typography',
		    'settings' 			=> 'theme_heading_one_size',
		    'input_attrs' 		=> array(
				'min' 		=> 0,
				'max'  		=> 100,
				'step' 		=> 2,
			),
		) );

		$wp_customize->add_setting( 'theme_heading_two_size', array(
			'default' 			=> '',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'theme_heading_two_size', array(
		    'label' 			=> esc_html__( 'Header 2 (H2) size', 'physio-qt' ),
		    'description' 		=> esc_html__( 'Please don\'t include px in your string', 'physio-qt' ),
	    	'type'		        => 'number',
		    'section' 			=> 'section_typography',
		    'settings' 			=> 'theme_heading_two_size',
		    'input_attrs' 		=> array(
				'min' 		=> 0,
				'max'  		=> 100,
				'step' 		=> 2,
			),
		) );

		$wp_customize->add_setting( 'theme_heading_three_size', array(
			'default' 			=> '',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'theme_heading_three_size', array(
		    'label' 			=> esc_html__( 'Header 3 (H3) size', 'physio-qt' ),
		    'description' 		=> esc_html__( 'Please don\'t include px in your string', 'physio-qt' ),
	    	'type'		        => 'number',
		    'section' 			=> 'section_typography',
		    'settings' 			=> 'theme_heading_three_size',
		    'input_attrs' 		=> array(
				'min' 		=> 0,
				'max'  		=> 100,
				'step' 		=> 2,
			),
		) );

		$wp_customize->add_setting( 'theme_heading_four_size', array(
			'default' 			=> '',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'theme_heading_four_size', array(
		    'label' 			=> esc_html__( 'Header 4 (H4) size', 'physio-qt' ),
		    'description' 		=> esc_html__( 'Please don\'t include px in your string', 'physio-qt' ),
	    	'type'		        => 'number',
		    'section' 			=> 'section_typography',
		    'settings' 			=> 'theme_heading_four_size',
		    'input_attrs' 		=> array(
				'min' 		=> 0,
				'max'  		=> 100,
				'step' 		=> 2,
			),
		) );

		$wp_customize->add_setting( 'theme_heading_five_size', array(
			'default' 			=> '',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'theme_heading_five_size', array(
		    'label' 			=> esc_html__( 'Header 5 (H5) size', 'physio-qt' ),
		    'description' 		=> esc_html__( 'Please don\'t include px in your string', 'physio-qt' ),
	    	'type'		        => 'number',
		    'section' 			=> 'section_typography',
		    'settings' 			=> 'theme_heading_five_size',
		    'input_attrs' 		=> array(
				'min' 		=> 0,
				'max'  		=> 100,
				'step' 		=> 2,
			),
		) );

		$wp_customize->add_setting( 'theme_heading_six_size', array(
			'default' 			=> '',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'theme_heading_six_size', array(
		    'label' 			=> esc_html__( 'Header 6 (H6) size', 'physio-qt' ),
		    'description' 		=> esc_html__( 'Please don\'t include px in your string', 'physio-qt' ),
	    	'type'		        => 'number',
		    'section' 			=> 'section_typography',
		    'settings' 			=> 'theme_heading_six_size',
		    'input_attrs' 		=> array(
				'min' 		=> 0,
				'max'  		=> 100,
				'step' 		=> 2,
			),
		) );

		// Section Settings: Other
		$wp_customize->add_setting( '404_page_image', array(
			'transport' 			=> 'refresh',
			'sanitize_callback' 	=> 'esc_url',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, '404_page_image', array(
            'label' 				=> esc_html__( '404 page logo', 'physio-qt' ),
            'description' 			=> esc_html__( 'To reach the 404 page navigate to a page that don\'t exisint via the browser domain bar', 'physio-qt' ),
            'section' 				=> 'section_other',
            'settings' 				=> '404_page_image',
			'priority' 				=> 10,
		) ) );

		$wp_customize->add_setting( '404_page_text_title', 
			array(
		    	'default' 			=> 'Oops! That page can\'t be found',
		    	'transport'			=> 'refresh',
		    	'sanitize_callback' => 'wp_kses_post',
		) );
		$wp_customize->add_control( '404_page_text_title', 
			array(
		    	'label' 			=> esc_html__( '404 page text title', 'physio-qt' ),
		    	'section' 			=> 'section_other',
		    	'settings' 			=> '404_page_text_title',
		    	'type' 				=> 'text',
		    	'priority' 			=> 15,
		) );

		$wp_customize->add_setting( '404_page_text', array(
	    	'default' 				=> 'It looks like nothing was found at this location. Maybe try a search below?',
	    	'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'wp_kses_post',
		) );
		$wp_customize->add_control( '404_page_text', array(
	    	'label' 				=> esc_html__( '404 page text', 'physio-qt' ),
	    	'section' 				=> 'section_other',
	    	'settings' 				=> '404_page_text',
	    	'type' 					=> 'text',
	    	'priority' 				=> 20,
		) );

		$wp_customize->add_setting( '404_page_search', array(
	    	'default'  				=> 'show',
	    	'transport'				=> 'refresh',
	    	'sanitize_callback' 	=> 'sanitize_text_field',
		) );
		$wp_customize->add_control( '404_page_search', array(
			'label'    				=> esc_html__( '404 page search bar', 'physio-qt' ),
			'section'  				=> 'section_other',
			'settings' 				=> '404_page_search',
			'type'     				=> 'select',
			'choices'  				=> array(
				'show' => esc_html__( 'Show', 'physio-qt' ),
				'hide' => esc_html__( 'Hide', 'physio-qt' ),
			),
			'priority' 				=> 25,
		) );

		/** 
		 * Panel: Opening Hour Translation
		 */

		$physio_qt_weekdays = array(
			'monday',
			'tuesday',
			'wednesday',
			'thursday',
			'friday',
			'saturday',
			'sunday'
		);

		foreach ( $physio_qt_weekdays as $day ) {

			// Captialize the weekday for the labels
			$day_name = ucfirst( $day );

			// Create the controls for all weekdays
			$wp_customize->add_setting( 'qt_weekday_' . $day, array(
				'transport'				=> 'refresh',
				'sanitize_callback' 	=> 'wp_kses_post',
			) );

			$wp_customize->add_control( 'qt_weekday_' . $day, array(
				'type' 					=> 'text',
				'section' 				=> 'section_opening_hours',
			    'settings' 				=> 'qt_weekday_' . $day,
			    'label' 				=> $day_name . ' ' . esc_html__( '(Translate)', 'physio-qt' ),
			    'description' 			=> esc_html__( 'Translate the weekday', 'physio-qt' ),
			) );

			$wp_customize->add_setting( 'qt_weekday_' . $day . '_from', array(
				'transport'				=> 'refresh',
				'sanitize_callback' 	=> 'wp_kses_post',
			) );

			$wp_customize->add_control( 'qt_weekday_' . $day . '_from', array(
				'type' 					=> 'text',
				'section' 				=> 'section_opening_hours',
			    'settings' 				=> 'qt_weekday_' . $day . '_from',
			    'label' 				=> $day_name . ' ' . esc_html__( '(From)', 'physio-qt' ),
			    'description' 			=> esc_html__( 'Example: 7:00 AM or 08:00', 'physio-qt' ),
			) );

			$wp_customize->add_setting( 'qt_weekday_' . $day . '_to', array(
				'transport'				=> 'refresh',
				'sanitize_callback' 	=> 'wp_kses_post',
			) );

			$wp_customize->add_control( 'qt_weekday_' . $day . '_to', array(
				'type' 					=> 'text',
				'section' 				=> 'section_opening_hours',
			    'settings' 				=> 'qt_weekday_' . $day . '_to',
			    'label' 				=> $day_name . ' ' . esc_html__( '(To)', 'physio-qt' ),
			    'description' 			=> esc_html__( 'Example: 5:00 PM or 17:00', 'physio-qt' ),
			) );
		}

		$wp_customize->add_setting( 'qt_opening_closed_text', array(
			'transport'				=> 'refresh',
			'sanitize_callback' 	=> 'wp_kses_post',
		) );

		$wp_customize->add_control( 'qt_opening_closed_text', array(
			'type' 					=> 'text',
			'section' 				=> 'section_opening_hours',
		    'settings' 				=> 'qt_opening_closed_text',
		    'label' 				=> esc_html__( 'Closed Text', 'physio-qt' ),
		    'description' 			=> esc_html__( 'Leave "From" and "To" fields empty to display the closed message', 'physio-qt' ),
		) );

		$wp_customize->add_setting( 'qt_opening_separator', array(
			'transport'				=> 'refresh',
			'sanitize_callback' 	=> 'wp_kses_post',
		) );

		$wp_customize->add_control( 'qt_opening_separator', array(
			'type' 					=> 'text',
			'section' 				=> 'section_opening_hours',
		    'settings' 				=> 'qt_opening_separator',
		    'label' 				=> esc_html__( 'Separator', 'physio-qt' ),
		    'description' 			=> esc_html__( 'Separator symbol between opening hours', 'physio-qt' ),
		) );

		$wp_customize->add_setting( 'qt_opening_extra_info', array(
			'transport'				=> 'refresh',
			'sanitize_callback' 	=> 'wp_kses_post',
		) );

		$wp_customize->add_control( 'qt_opening_extra_info', array(
			'type' 					=> 'text',
			'section' 				=> 'section_opening_hours',
		    'settings' 				=> 'qt_opening_extra_info',
		    'label' 				=> esc_html__( 'Extra info', 'physio-qt' ),
		    'description' 			=> esc_html__( 'Define lunchbreaks or vacations', 'physio-qt' ),
		) );

		/**
		 * Panel: Font Awesome
		 */
		$wp_customize->add_setting( 'qt_fontawesome_version', array(
	    	'default'  			=> '4',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'qt_fontawesome_version', array(
			'label'    			=> esc_html__( 'Font Awesome version', 'physio-qt' ),
			'description'       => esc_html__( 'Change the version of the Font Awesome icon library. Please note that v5 has different icon names and the design of some icons are slightly different', 'physio-qt' ),
			'section'  			=> 'section_fontawesome',
			'settings' 			=> 'qt_fontawesome_version',
			'type'     			=> 'select',
			'choices'  			=> array(
				'4'  				=> esc_html__( 'Version 4', 'physio-qt' ),
				'5' 				=> esc_html__( 'Version 5', 'physio-qt' ),
			),
		) );

		$wp_customize->add_setting( 'qt_fontawesome_fallback', array(
	    	'default'  			=> 'enable',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'qt_fontawesome_fallback', array(
			'label'    			=> esc_html__( 'Icon fallback support', 'physio-qt' ),
			'description'       => esc_html__( 'An extra stylesheet and script file will be loaded to your website to ensure the icons with old names (v4) are displayed as icons from v5. However, it is recommend to go through your website and replace all old icon names with names from v5 so you don\'t have to enable this option', 'physio-qt' ),
			'section'  			=> 'section_fontawesome',
			'settings' 			=> 'qt_fontawesome_fallback',
			'type'     			=> 'select',
			'choices'  			=> array(
				'enable'  			=> esc_html__( 'Enable', 'physio-qt' ),
				'disable' 			=> esc_html__( 'Disable', 'physio-qt' ),
			),
			'active_callback' 	=> array( $this, 'physio_qt_show_setting_fallback_support' ),
		) );

		/**
		 * Panel: ACF (PRO)
		 */
		$wp_customize->add_setting( 'qt_show_acf', array(
	    	'default'  			=> 'no',
	    	'transport'			=> 'refresh',
	    	'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'qt_show_acf', array(
			'label'    			=> esc_html__( 'Show ACF admin tab', 'physio-qt' ),
			'description'       => esc_html__( 'If you want to use the ACF plugin and make the admin tab visible set this to "Yes". Please do not change if you don\'t know what you\'re doing.', 'physio-qt' ),
			'section'  			=> 'section_acf',
			'settings' 			=> 'qt_show_acf',
			'type'     			=> 'select',
			'choices'  			=> array(
				'no'  				=> esc_html__( 'No', 'physio-qt' ),
				'yes' 				=> esc_html__( 'Yes', 'physio-qt' ),
			),
		) );

		// Section Settings: Site Identity
		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	}

	/**
	 * Returns if the Header Widget Bar is set to overlay
	 * used by the header_widgets_absolute control
	 *
	 * @return boolean
	 */
	public function header_widgets_isset_to_overlay() {

		if ( 'no_overlay' === get_theme_mod( 'header_widgets_absolute', 'no_overlay' ) ) {
			return false;
		}
		else {
			return true;
		}
	}

	/**
	 * Returns if featued button isset to display on dekstop & mobile
	 * used by the featured_button control
	 *
	 * @return boolean
	 */
	public function featured_button_isset_to_hide() {

		if ( 'hide' === get_theme_mod( 'featured_button', 'hide' ) && 'hide' === get_theme_mod( 'featured_button_mobile', 'hide' ) ) {
			return false;
		}
		else {
			return true;
		}
	}

	// Return if blog layout grid is selected
	public function physio_qt_blog_grid() {

		if ( 'grid' === get_theme_mod( 'blog_layout', 'grid' ) ) {
			return true;
		}
		else {
			return false;
		}
	}

	// Return option if icon fallback support isset to enable
	public function physio_qt_show_setting_fallback_support() {

		$fontawesome_version = get_theme_mod( 'qt_fontawesome_version' );

		if ( '5' == $fontawesome_version ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Formats the primary styles for output.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_primary_styles() {

		// Logo
		$top_margin_logo 							= get_theme_mod( 'logo_margin_top' );
		$logo_width 								= get_theme_mod( 'logo_width' );

		// Topbar
		$topbar_background_color 		 			= get_theme_mod( 'topbar_bg', '#707780' );
		$topbar_text_color 		 					= get_theme_mod( 'topbar_text_color', '#C1C7CE' );
		$topbar_link_color 		 					= get_theme_mod( 'topbar_link_color', '#C1C7CE' );
		$topbar_link_hover_color 		 			= get_theme_mod( 'topbar_link_hover_color', '#ffffff' );
		$topbar_submenu_background_color 		 	= get_theme_mod( 'topbar_submenu_background_color', '#56afd5' );
		$topbar_submenu_text_color 		 			= get_theme_mod( 'topbar_submenu_text_color', '#ffffff' );

		// Header
		$header_desktop_background_color 			= get_theme_mod( 'header_desktop_background_color', '#ffffff' );
		$header_mobile_background_color 			= get_theme_mod( 'header_mobile_background_color', '#ffffff' );

		$header_widgets_background_color 			= get_theme_mod( 'header_widgets_background_color', '#ffffff' );
		$header_widgets_background_opacity 			= get_theme_mod( 'header_widgets_background_opacity', '0.8' );
		$header_widgets_margin_desktop_small 	    = get_theme_mod( 'header_widgets_margin_desktop_small' );
		$header_widgets_margin_desktop_large 	    = get_theme_mod( 'header_widgets_margin_desktop_large' );

		if ( 'overlay' === get_theme_mod( 'header_widgets_absolute', 'overlay' ) ) {
			$header_widgets_background_color_rgba	= Physio_QT_Customizer::hex2rgba( $header_widgets_background_color, $header_widgets_background_opacity );
		} else {
			$header_widgets_background_color_rgba	= $header_widgets_background_color;
		}

		$header_widgets_text_color 					= get_theme_mod( 'header_widgets_text_color', '#838383' );
		$header_widgets_text_color_lighter 			= Physio_QT_Customizer::adjust_color( $header_widgets_text_color, 60 );
		$header_widgets_text_color_hover 			= Physio_QT_Customizer::adjust_color( $header_widgets_text_color, 30 );

		$featured_button_text_color 				= get_theme_mod( 'featured_button_text_color', '#ffffff' );
		$featured_button_background_color 			= get_theme_mod( 'featured_button_background_color', '#A175AA' );
		$featured_button_background_color_hover 	= Physio_QT_Customizer::adjust_color( $featured_button_background_color, -5 );

		// Navigation
		$nav_link_color 							= get_theme_mod( 'nav_link_color', '#828282' );
		$nav_link_hover_color 						= get_theme_mod( 'nav_link_hover_color', '#56afd5' );
		$nav_link_active_color 						= get_theme_mod( 'nav_link_active_color', '#56afd5' );
		$nav_submenu_bg 							= get_theme_mod( 'nav_submenu_bg', '#9A65A5' );
		$nav_submenu_link_color 					= get_theme_mod( 'nav_submenu_link_color', '#ffffff' );

		$nav_submenu_bg_adjust 						= Physio_QT_Customizer::adjust_color( $nav_submenu_bg, -10 ); // only used for default value
		$nav_submenu_bg_hover 						= get_theme_mod( 'nav_submenu_bg_hover', $nav_submenu_bg_adjust );
		$nav_submenu_link_color_hover 				= get_theme_mod( 'nav_submenu_link_color_hover', '#ffffff' );

		$nav_mobile_link_color 						= get_theme_mod( 'nav_mobile_link_color', '#ffffff' );
		$nav_mobile_link_background_color 			= get_theme_mod( 'nav_mobile_link_background_color', '#9A65A5' );
		$nav_mobile_link_active_color 				= get_theme_mod( 'nav_mobile_link_active_color', '#ffffff' );
		$nav_mobile_submenu_bg 						= get_theme_mod( 'nav_mobile_submenu_bg', '#935c9e' );
		$nav_mobile_submenu_link_color 				= get_theme_mod( 'nav_mobile_submenu_link_color', '#ffffff' );
		$nav_mobile_submenu_active_link_color 		= get_theme_mod( 'nav_mobile_submenu_active_link_color', '#ffffff' );

		$nav_mobile_menu_bg 						= get_theme_mod( 'nav_mobile_menu_bg', $primary_color );
		$nav_mobile_menu_text 						= get_theme_mod( 'nav_mobile_menu_text', '#fff' );
		$nav_mobile_submenu_toggle_bg 				= get_theme_mod( 'nav_mobile_submenu_toggle_bg', 'transparent' );
		$nav_mobile_submenu_toggle_bg_active 		= get_theme_mod( 'nav_mobile_submenu_toggle_bg_active', '#935c9e' );
		$nav_mobile_submenu_toggle_icon 			= get_theme_mod( 'nav_mobile_submenu_toggle_icon', '#fff' );

		// Slider
		$slider_small_heading_color 				= get_theme_mod( 'slider_small_heading_color' );
		$slider_heading_color 						= get_theme_mod( 'slider_heading_color' );
		$slider_content_color 						= get_theme_mod( 'slider_content_color' );
		$slider_primary_button_background_color 	= get_theme_mod( 'slider_primary_button_background_color' );
		$slider_primary_button_color 				= get_theme_mod( 'slider_primary_button_color' );
		$slider_control_background_color 			= get_theme_mod( 'slider_control_background_color' );
		$slider_control_color 						= get_theme_mod( 'slider_control_color' );
		$slider_mobile_background_color 			= get_theme_mod( 'slider_mobile_background_color' );

		// Page Header (Title area)
		$page_header_title_align 					= get_theme_mod( 'page_header_title_align', 'left' );
		$page_header_title_color 					= get_theme_mod( 'page_header_title_color', '#464646' );
		$page_header_subtitle_color 				= get_theme_mod( 'page_header_subtitle_color', '#999999' );
		$page_header_background_color 				= get_theme_mod( 'page_header_background_color', '#F5F8FB' );
		$page_header_background_image 				= get_theme_mod( 'page_header_background_image', false );

		$page_header_bg_position					= get_theme_mod( 'page_header_bg_position', 'left center' );
		$page_header_bg_repeat						= get_theme_mod( 'page_header_bg_repeat', 'repeat' );
		$page_header_bg_size						= get_theme_mod( 'page_header_bg_size', 'auto' );

		// Breadcrumbs
		$breadcrumbs_background_color 				= get_theme_mod( 'breadcrumbs_background_color', '#ffffff' );
		$breadcrumbs_text_color 					= get_theme_mod( 'breadcrumbs_text_color', '#cccccc' );
		$breadcrumbs_active_color 					= get_theme_mod( 'breadcrumbs_active_color', '#9A65A5' );

		// Theme Colors
		$boxed_background 							= get_theme_mod( 'boxed_background', '#ffffff' );
		$text_color 								= get_theme_mod( 'text_color', '#999999' );
		$widgettitle_color 							= get_theme_mod( 'widgettitle_color', '#464646' );
		$primary_color 								= get_theme_mod( 'primary_color', '#56afd5' );
		$primary_color_hover 						= Physio_QT_Customizer::adjust_color( $primary_color, -5 );
		$secondary_color 							= get_theme_mod( 'secondary_color', '#9A65A5' );
		$secondary_color_hover 						= Physio_QT_Customizer::adjust_color( $secondary_color, -5 );
		
		$button_color 								= get_theme_mod( 'button_color', '#9A65A5' );
		$button_color_hover 						= ( '' == get_theme_mod( 'button_color_hover' ) ) ? Physio_QT_Customizer::adjust_color( $button_color, -5 ) : get_theme_mod( 'button_color_hover' );
		
		$button_text_color 							= get_theme_mod( 'button_text_color', '#ffffff' );
		$title_span_weight 							= get_theme_mod( 'title_span_font_weight', 'normal' );

		// Top Footer
		$top_footer_background_color 				= get_theme_mod( 'top_footer_background_color', 'transparent' );
		$top_footer_text_color 						= get_theme_mod( 'top_footer_text_color', '#C1C7CE' );
		$top_footer_text_color_lighter 				= Physio_QT_Customizer::adjust_color( $top_footer_text_color, 60 );
		$top_footer_text_color_hover 				= Physio_QT_Customizer::adjust_color( $top_footer_text_color, 30 );

		// Main Footer
		$main_footer_text_color 					= get_theme_mod( 'main_footer_text_color', '#C1C7CE' );
		$main_footer_link_color 					= get_theme_mod( 'main_footer_link_color', '#C1C7CE' );
		$main_footer_link_lighter 					= ( '' == get_theme_mod( 'main_footer_link_color_hover' ) ) ? Physio_QT_Customizer::adjust_color( $button_color, 250 ) : get_theme_mod( 'main_footer_link_color_hover' );
		$main_footer_widget_title_color 			= get_theme_mod( 'main_footer_widget_title_color', '#ffffff' );
		$main_footer_background_color 				= get_theme_mod( 'main_footer_background_color', 'transparent' );
		$main_footer_background_image 				= get_theme_mod( 'main_footer_background_image', get_theme_file_uri( '/assets/images/footer_pattern.png' ) );

		// Bottom Footer
		$bottom_footer_background_color 			= get_theme_mod( 'bottom_footer_background_color', '#555A5F' );
		$bottom_footer_text_color 					= get_theme_mod( 'bottom_footer_text_color', '#909BA2' );
		$bottom_footer_link_color 					= get_theme_mod( 'bottom_footer_link_color', '#ffffff' );
		$bottom_footer_link_color_hover 			= Physio_QT_Customizer::adjust_color( $bottom_footer_link_color, -5 );

		// Font Settings
		$theme_primary_font 		   				= get_theme_mod( 'theme_primary_font', 'Open Sans' );
		$theme_secondary_font 		   				= get_theme_mod( 'theme_secondary_font', 'Nunito' );
		$theme_primary_font_size 	   				= get_theme_mod( 'theme_primary_font_size' );

		$theme_custom_heading_sizes    				= get_theme_mod( 'theme_custom_heading_sizes', 'no' );
		$theme_widget_title_size_large 				= get_theme_mod( 'theme_widget_title_size_large' );
		$theme_widget_title_size 	   				= get_theme_mod( 'theme_widget_title_size' );
		$theme_navigation_link_size    				= get_theme_mod( 'theme_navigation_link_size' );
		$theme_navigation_submenu_link_size 		= get_theme_mod( 'theme_navigation_submenu_link_size' );
		$theme_page_heading_title_size 				= get_theme_mod( 'theme_page_heading_title_size' );
		$theme_footer_widget_title_size 			= get_theme_mod( 'theme_footer_widget_title_size' );
		$theme_heading_one_size 	   				= get_theme_mod( 'theme_heading_one_size' );
		$theme_heading_two_size 	   				= get_theme_mod( 'theme_heading_two_size' );
		$theme_heading_three_size 	   				= get_theme_mod( 'theme_heading_three_size' );
		$theme_heading_four_size 	   				= get_theme_mod( 'theme_heading_four_size' );
		$theme_heading_five_size 	   				= get_theme_mod( 'theme_heading_fice_size' );
		$theme_heading_six_size 	   				= get_theme_mod( 'theme_heading_six_size' );

		/**  
		 * Build Up the Styles
		 *
		 **/
		$physio_qt_style = "";

		// Primary font
		if ( $theme_primary_font ) {

			// Primary font type
			$physio_qt_style .= "
				body,
				button,
				input,
				select,
				textarea,
				.btn,
				.main-navigation .sub-menu li a,
				.jumbotron .jumbotron-caption .caption-small-heading,
				.page-header--subtitle,
				.featured-page .featured-page--image .featured-page--overlay .overlay--center span,
				.brochure span,
				.news-posts-block .widget-title a,
				.panel-group .panel .panel-heading .panel-title a {
					font-family: '{$theme_primary_font}';
				}
			";

			// Primary font size
			if ( $theme_primary_font_size != '' ) {
				
				$physio_qt_style .= "
					body {
						font-size: {$theme_primary_font_size}px;
					}
				";
			}
		}

		// Secondary font
		if ( $theme_secondary_font ) {

			$physio_qt_style .= "
				h1,
				h2,
				h3,
				h4,
				h5,
				h6,
				.main-navigation > li > a,
				.brochure,
				.testimonials .testimonial--author,
				.call-to-action .call-to-action--content .call-to-action--title,
				.counter .counter--text .counter--number {
					font-family: '{$theme_secondary_font}';
				}
			";
		}

		// Widget title size large
		if ( $theme_widget_title_size_large != '' ) {
			
			$physio_qt_style .= "
				.bigger-widget-title .widget-title {
					font-size: {$theme_widget_title_size_large}px;
				}
			";
		}

		// Widget title size normal
		if ( $theme_widget_title_size != '' ) {
			
			$physio_qt_style .= "
				.widget-title {
					font-size: {$theme_widget_title_size}px;
				}
			";
		}

		// Navigation link size
		if ( $theme_navigation_link_size != '' ) {

			$physio_qt_style .= "
				@media (min-width: 992px) {
					.main-navigation > li > a {
						font-size: {$theme_navigation_link_size}px;
					}
				}
			";
		}

		// Navigation submneu link size
		if ( $theme_navigation_submenu_link_size != '' ) {

			$physio_qt_style .= "
				.main-navigation .sub-menu > li > a {
					font-size: {$theme_navigation_submenu_link_size}px;
				}
			";
		}

		// Page heading title size
		if ( $theme_page_heading_title_size != '' ) {

			$physio_qt_style .= "
				@media (min-wdith: 992px) {
					.page-header--title {
						font-size: {$theme_page_heading_title_size}px;
					}
				}
			";
		}

		// Footer widget title size
		if ( $theme_footer_widget_title_size != '' ) {

			$physio_qt_style .= "
				.footer .widget-title {
					font-size: {$theme_footer_widget_title_size}px;
				}
			";
		}

		// Heading 1
		if ( $theme_heading_one_size != '' ) {
			
			$physio_qt_style .= "
				.content-area h1 {
					font-size: {$theme_heading_one_size}px;
				}
			";
		}

		// Heading 2
		if ( $theme_heading_two_size != '' ) {
			
			$physio_qt_style .= "
				.content-area h2 {
					font-size: {$theme_heading_two_size}px;
				}
			";
		}

		// Heading 3
		if ( $theme_heading_three_size != '' ) {
			
			$physio_qt_style .= "
				.content-area h3 {
					font-size: {$theme_heading_three_size}px;
				}
			";
		}

		// Heading 4
		if ( $theme_heading_four_size != '' ) {
			
			$physio_qt_style .= "
				.content-area h4 {
					font-size: {$theme_heading_four_size}px;
				}
			";
		}

		// Heading 5
		if ( $theme_heading_five_size != '' ) {
			
			$physio_qt_style .= "
				.content-area h5 {
					font-size: {$theme_heading_five_size}px;
				}
			";
		}

		// Heading 6
		if ( $theme_heading_six_size != '' ) {
			
			$physio_qt_style .= "
				.content-area h6 {
					font-size: {$theme_heading_six_size}px;
				}
			";
		}


		if ( $logo_width != '' || $top_margin_logo != '' ) {

			$physio_qt_style .= "
				.header-wrapper .header-logo img {
			";

				if ( $logo_width != '') {

					$physio_qt_style .= "
						width: {$logo_width}px;
					";
				}

				if ( $top_margin_logo != '') {

					$physio_qt_style .= "
						margin-top: {$top_margin_logo}px;
					";
				}

			$physio_qt_style .= "
				}
			";
		}

		$physio_qt_style .= "
			
			.header .header-topbar {
				background: {$topbar_background_color};	
			}

			.header .header-topbar,
			.header .header-topbar .header-topbar-sidebar .icon-box--title {
				color: {$topbar_text_color};
			}

			.header .header-topbar a,
			.header .header-topbar .header-topbar-sidebar a.icon-box .icon-box--title {
				color: {$topbar_link_color};
			}
			.header .header-topbar a:hover,
			.header-topbar-sidebar .menu > li:hover > a,
			.header .header-topbar .header-topbar-sidebar a.icon-box:hover .icon-box--title {
				color: {$topbar_link_hover_color};
			}
			.header-topbar-sidebar .menu .sub-menu > li > a {
				color: {$topbar_submenu_text_color};
				background: {$topbar_submenu_background_color};
			}
			.header-topbar-sidebar .menu .sub-menu:after {
				border-bottom-color: {$topbar_submenu_background_color};
			}

			.header-wrapper {
				background-color: {$header_mobile_background_color};
			}
			.header-widgets {
				color: {$header_widgets_text_color};
				background: {$header_widgets_background_color};
			}
			.header-widgets .icon-box--title {
				color: {$header_widgets_text_color};
			}
			.header-widgets .icon-box--icon i,
			.header-widgets .icon-box--description,
			.header-widgets .social-icons a {
				color: {$header_widgets_text_color_lighter};
			}
			.header-widgets .icon-box:hover .icon-box--icon i,
			.header-widgets .social-icons a:hover {
				color: {$header_widgets_text_color_hover};
			}

			@media(min-width: 992px) {
				.header-wrapper {
					background-color: {$header_desktop_background_color};
				}
				.header-widgets {
					background: {$header_widgets_background_color_rgba};
				}
			}
		";

		if ( $header_widgets_margin_desktop_small != '' ) {

			$physio_qt_style .= "

				@media(min-width: 992px) {
					.header-widgets .widget { 
						margin-right: {$header_widgets_margin_desktop_small}px;
					}
				}
			";
		}

		if ( $header_widgets_margin_desktop_large != '' ) {

			$physio_qt_style .= "

				@media(min-width: 1200px) {
					.header-widgets .widget {
						margin-right: {$header_widgets_margin_desktop_large}px;
					}
				}
			";
		}


		$physio_qt_style .= "

			.header .header-wrapper .featured-button a {
				color: {$featured_button_text_color};
				background: {$featured_button_background_color};
			}

			.header .header-wrapper .featured-button a:hover {
				background: {$featured_button_background_color_hover};
			}

			@media (max-width: 992px) {

				.navbar-toggle,
				.navbar-toggle:hover {
					background-color: {$nav_mobile_menu_bg};
				}

				.navbar-toggle .icon-bar {
					background-color: {$nav_mobile_menu_text};
				}

				.main-navigation .nav-toggle-mobile-submenu {
					color: {$nav_mobile_submenu_toggle_icon};
					background-color: {$nav_mobile_submenu_toggle_bg};
				}

				.main-navigation > li.nav-toggle-dropdown .nav-toggle-mobile-submenu {
					background-color: {$nav_mobile_submenu_toggle_bg_active};
				}

				.main-navigation > li > a {
					color: {$nav_mobile_link_color};
					background: {$nav_mobile_link_background_color};
				}
				.main-navigation > li.current-menu-item > a,
				.main-navigation > li.current_page_parent a {
					color: {$nav_mobile_link_active_color};
				}
				.main-navigation .sub-menu > li > a {
					color: {$nav_mobile_submenu_link_color};
					background: {$nav_mobile_submenu_bg};
				}
				.main-navigation .sub-menu > li.current-menu-item > a {
					color: {$nav_mobile_submenu_active_link_color};
				}
			}

			@media (min-width: 992px) {

				.main-navigation > li > a {
					color: {$nav_link_color};
					background: none;
				}

				.main-navigation > li.current-menu-item > a,
				.main-navigation > li.current-menu-ancestor > a,
				.main-navigation > li.menu-item-has-children::after {
					color: {$nav_link_active_color};
				}

				.main-navigation > li:hover > a,
				.main-navigation > li.current-menu-item:hover > a,
				.main-navigation > li.menu-item-has-children:hover::after {
					color: {$nav_link_hover_color};
				}

				.main-navigation .sub-menu > li > a {
					color: {$nav_submenu_link_color};
					background-color: {$nav_submenu_bg};
				}

				.main-navigation .sub-menu > li:hover > a {
					color: {$nav_submenu_link_color_hover};
					border-top-color: {$nav_submenu_bg_hover};
					background-color: {$nav_submenu_bg_hover};
				}
			}

		";

		if ( $page_header_background_image != '' ) {
			
			$physio_qt_style .= "

				.page-header {
					text-align: {$page_header_title_align};
					background-color: {$page_header_background_color};
					background-image: url({$page_header_background_image});
					background-position: {$page_header_bg_position};
					background-repeat: {$page_header_bg_repeat};
					background-size: {$page_header_bg_size};
				}

			";

		} else {
			
			$physio_qt_style .= "

				.page-header {
					text-align: {$page_header_title_align};
					background-color: {$page_header_background_color};
				}

			";
		}

		$physio_qt_style .= "

			.page-header--title {
				color: {$page_header_title_color};
			}

			.page-header--subtitle {
				color: {$page_header_subtitle_color};
			}


			.breadcrumbs {
				background: {$breadcrumbs_background_color};
			}

			.breadcrumbs a,
			.breadcrumbs a:hover {
				color: {$breadcrumbs_text_color};
			}

			.breadcrumbs span > span {
				color: {$breadcrumbs_active_color};
			}


			.widget-title {
				color: {$widgettitle_color};
			}

			.content-area span.normal {
				font-weight: {$title_span_weight};
			}

			body .layout-boxed { 
				background: {$boxed_background};
			}

			body,
			.content-area .icon-box--description,
			.has-theme-text-color-color {
				color: {$text_color};
			}

			.has-theme-text-color-background-color {
				background-color: {$text_color};
			}

			a,
			.jumbotron .jumbotron-caption .caption-small-heading,
			.featured-page .featured-page--content .featued-page--title:hover a,
			.news-posts-block .news-post--title a:hover,
			.content-area .icon-box--icon i,
			.team-member--name a:hover,
			.testimonials .testimonial--description,
			.content-area .opening-hours ul li.today,
			.has-theme-blue-color {
				color: {$primary_color};
			}

			a:hover {
				color: {$primary_color_hover};
			}

			.search-submit,
			.content-area .icon-box:hover .icon-box--icon,
			.content-area .opening-hours ul li span.label,
			.team-member--social .overlay--center a:hover,
			.counter:hover .counter--icon,
			.has-theme-blue-background-color {
				background: {$primary_color};
			}

			.search-submit:hover {
				background: {$primary_color_hover};
			}

			.team-member--tag,
			.brochure:hover i,
			.has-theme-purple-color {
				color: {$secondary_color};
			}


			table thead th,
			.news-posts-block .news-post--date,
			.testimonials .testimonial-control,
			.custom-table thead td,
			.content-area .widget_nav_menu,
			.hentry--post-thumbnail .meta-data--date,
			.content-area .featured-box,
			.panel-group .panel .panel-heading .panel-title a[aria-expanded=".'true'."],
			.featured-page .featured-page--image .featured-page--overlay .overlay--center span:hover,
			.has-theme-purple-background-color {
				background: {$secondary_color};
			}

			.news-posts-block .news-post--date:hover,
			.testimonials .testimonial-control:hover {
				background: {$secondary_color_hover};
			}
			
			.client-logos img:hover,
			.brand-border:hover {
				border-color: {$secondary_color};
			}
			
			.btn.btn-primary,
			.pagination span.current,
			.pagination a:hover,
			body.page .pagination > span:not(.page-links-title),
			.comments-area .comment-respond .comment-form .form-submit .submit,
			.wp-block-button:not(.is-style-outline) .wp-block-button__link:not(.has-background) {
				color: {$button_text_color};
				background: {$button_color};
			}

			.btn.btn-primary:hover,
			.btn.btn-primary:active:focus,
			.pagination span.current,
			.pagination a:hover,
			.comments-area .comment-respond .comment-form .form-submit .submit:hover,
			.wp-block-button:not(.is-style-outline) .wp-block-button__link:not(.has-background):hover {
				background: {$button_color_hover};	
			}

			
			.footer .footer--top {
				color: {$top_footer_text_color};
				background: {$top_footer_background_color};
			}

			.footer .icon-box--title {
				color: {$top_footer_text_color_lighter};
			}

			.footer .icon-box--icon i,
			.footer .icon-box--description,
			.footer .social-icons a,
			.footer .icon-box:hover .icon-box--icon i,
			.footer .social-icons a:hover {
				color: {$top_footer_text_color};
			}
			
			.footer--main-container {
				background-image: url({$main_footer_background_image});
			}

			.footer .footer--middle {
				color: {$main_footer_text_color};
				background-color: {$main_footer_background_color};
			}
			
			.footer .footer--middle .widget_nav_menu ul.menu li > a {
				color: {$main_footer_link_color};
			}

			.footer .footer--middle .widget_nav_menu ul.menu li > a:hover,
			.footer .footer--middle .widget_nav_menu ul.menu li.current-menu-item > a {
				color: {$main_footer_link_lighter};
			}

			.footer .widget-title {
				color: {$main_footer_widget_title_color};
			}

			
			.footer .footer--bottom {
				color: {$bottom_footer_text_color};
				background: {$bottom_footer_background_color};
			}
			.footer .footer--bottom a {
				color: {$bottom_footer_link_color};
			}
			.footer .footer--bottom a:hover {
				color: {$bottom_footer_link_color_hover};
			}
		";

		if ( $slider_small_heading_color != '' ) {

			$physio_qt_style .= "
				.jumbotron .jumbotron-caption .caption-small-heading {
					color: {$slider_small_heading_color};
				}
			";
		}

		if ( $slider_heading_color != '' ) {

			$physio_qt_style .= "
				.jumbotron .jumbotron-caption .caption-heading .caption-heading-tag {
					color: {$slider_heading_color};
				}
			";
		}

		if ( $slider_content_color != '' ) {

			$physio_qt_style .= "
				.jumbotron .jumbotron-caption .caption-content p {
					color: {$slider_content_color};
				}
			";
		}

		if ( $slider_primary_button_color != '' || $slider_primary_button_background_color != '' ) {

			$physio_qt_style .= "
				.jumbotron .btn.btn-primary {
					color: {$slider_primary_button_color};
					background-color: {$slider_primary_button_background_color};
				}
			";
		}

		if ( $slider_control_color != '' || $slider_control_background_color != '' ) {

			$physio_qt_style .= "
				.jumbotron .carousel-control {
					color: {$slider_control_color};
					background-color: {$slider_control_background_color};
				}
				.jumbotron .carousel-control:hover {
					background-color: {$slider_control_background_color};
				}
			";
		}

		if ( $slider_mobile_background_color != '' ) {

			$physio_qt_style .= "
				@media(max-width: 992px) {
					.jumbotron .item {
						background-color: {$slider_mobile_background_color};
					}
				}
			";
		}

		// Only print these style is WooCommerce is active
		if ( physio_qt_woocommerce_active() ) {

			$physio_qt_style .= "
				.woocommerce .star-rating span:before,
				.woocommerce div.product .star-rating::before,
				.woocommerce div.product p.price,
				.woocommerce ul.products li.product h3:hover,
				.woocommerce-page .woocommerce-error::before,
				.woocommerce-page .woocommerce-info::before,
				.woocommerce-page .woocommerce-message::before {
					color: {$primary_color};
				}

				.woocommerce a.button,
				.woocommerce input.button,
				.woocommerce input.button.alt,
				.woocommerce button.button,
				.woocommerce #respond input#submit,
				.woocommerce .widget_product_categories ul.product-categories li a,
				.woocommerce-MyAccount-navigation ul li.is-active a,
				.woocommerce-MyAccount-navigation ul li a:hover {
					background: {$primary_color};
				}

				.woocommerce a.button:hover,
				.woocommerce input.button:hover,
				.woocommerce input.button.alt:hover,
				.woocommerce button.button:hover,
				.woocommerce #respond input#submit:hover,
				.woocommerce-page .woocommerce-error a.button:hover,
				.woocommerce-page .woocommerce-info a.button:hover,
				.woocommerce-page .woocommerce-message a.button:hover,
				.woocommerce .widget_product_categories ul.product-categories li a:hover {
					background: {$primary_color_hover};
				}

				.woocommerce ul.products li.product a:hover img {
					outline-color: {$primary_color};
				}


				.woocommerce div.product .woocommerce-tabs ul.tabs li.active,
				.woocommerce nav.woocommerce-pagination ul li span.current,
				.woocommerce nav.woocommerce-pagination ul li a:focus,
				.woocommerce nav.woocommerce-pagination ul li a:hover {
					background: {$secondary_color};
				}

				.woocommerce div.product .woocommerce-tabs ul.tabs li:hover {
					background: {$secondary_color_hover};
				}


				.woocommerce div.product form.cart .button.single_add_to_cart_button,
				.woocommerce-cart .wc-proceed-to-checkout a.checkout-button {
					color: {$button_text_color};
					background: {$button_color};
				}
				.woocommerce div.product form.cart .button.single_add_to_cart_button:hover,
				.woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover {
					background: {$button_color_hover};
				}

				.woocommerce div.product #reviews .comment-reply-title {
					font-family: {$theme_secondary_font};
				}
			";
		}

		$physio_qt_style .= physio_qt_inline_styles();

		return str_replace( array( "\r", "\n", "\t" ), '', $physio_qt_style );
	}

	/**
	 * Get all the CSS parts, combine them and add as inline style
	 *
	 * @since  1.0.0
	 */
	public function physio_qt_customizer_css() {

		$physio_qt_style = $this->get_primary_styles();
		$physio_qt_style = '<style id="physio-inline-customizer-css" type="text/css">'. $physio_qt_style .'</style>' . PHP_EOL;

		// Output the customizer styles
		echo trim( $physio_qt_style );

		// Add wp inline style
		wp_add_inline_style( 'custom-css', 'physio_customizer_css', 30 );
	}

	/**
	 * Deletes the cached style CSS that's output into the header.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function physio_qt_cache_delete() {
		wp_cache_delete( get_stylesheet() . '_custom_colors' );
	}

	/**
	* This outputs the javascript needed to automate the live settings preview.
	* Also keep in mind that this function isn't necessary unless your settings 
	* are using 'transport'=>'postMessage' instead of the default 'transport'
	* => 'refresh'
	* 
	* Used by hook: 'customize_preview_init'
	* 
	* @see add_action('customize_preview_init',$func)
	* @since Version 1.0
	*/
	public function physio_qt_live_preview() {
		wp_enqueue_script( 'physio-qt-customizer-js', get_theme_file_uri( '/assets/js/theme-customizer.js' ), array( 'customize-preview' ), '', true );
	}

	/**
	 * Generate a lighter/darker color from HEX color
	 *
	 * used for hover colors
	 * @since  1.0.0
	 * @return string
	 */
	public function adjust_color($hex, $steps) {

	    $steps = max( -255, min( 255, $steps ) );

	    $hex = str_replace( '#', '', $hex );

	    if ( strlen( $hex ) == 3 ) {
	        $hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
	    }

	    $color_parts = str_split( $hex, 2 );
	    $return = '#';

	    foreach ( $color_parts as $color ) {
	        $color   = hexdec( $color );
	        $color   = max( 0, min( 255, $color + $steps ) );
	        $return .= str_pad( dechex( $color ), 2, '0', STR_PAD_LEFT );
	    }

	    return $return;
	}

	/**
	 * Generate HEX color to rgba
	 *
	 * used for header widgets bar background color / opacity
	 * @since  1.0.0
	 * @return string
	 */
	public function hex2rgba($color, $opacity = false) {

		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		$rgb =  array_map( 'hexdec', $hex );

		$output = 'rgba('.implode( ",", $rgb ).','.$opacity.')';

		return $output;
	}
}

new Physio_QT_Customizer();