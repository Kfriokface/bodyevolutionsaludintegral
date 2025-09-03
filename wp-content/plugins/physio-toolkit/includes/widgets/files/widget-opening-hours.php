<?php
/**
 * Widget: Opening Hours
 *
 * @package physio-toolkit
 */

if ( ! class_exists( 'QT_Opening_Hours' ) ) {
	class QT_Opening_Hours extends WP_Widget {

		// Set variable so we only need to declare the $weekdays in the __construct
		private $weekdays;

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				false,
				esc_html__( "QT: Opening Hours" , 'physio-qt' ),
				array(
					'description' => esc_html__( 'Show your opening hours', 'physio-qt' ),
					'classname' => 'widget-opening-hours'
				)
			);

			// Get the customizer settings for weekday translation
			$qt_monday 		= get_theme_mod( 'qt_weekday_monday' );
			$qt_tuesday 	= get_theme_mod( 'qt_weekday_tuesday' );
			$qt_wednesday 	= get_theme_mod( 'qt_weekday_wednesday' );
			$qt_thursday  	= get_theme_mod( 'qt_weekday_thursday' );
			$qt_friday 		= get_theme_mod( 'qt_weekday_friday' );
			$qt_saturday 	= get_theme_mod( 'qt_weekday_saturday' );
			$qt_sunday 		= get_theme_mod( 'qt_weekday_sunday' );

			// Check if translation is added else show English default
			$monday 	= ( '' == $qt_monday ) ? esc_html__( 'Monday', 'physio-qt' ) : $qt_monday;
			$tuesday 	= ( '' == $qt_tuesday ) ? esc_html__( 'Tuesday', 'physio-qt' ) : $qt_tuesday;
			$wednesday 	= ( '' == $qt_wednesday ) ? esc_html__( 'Wednesday', 'physio-qt' ) : $qt_wednesday;
			$thursday 	= ( '' == $qt_thursday ) ? esc_html__( 'Thursday', 'physio-qt' ) : $qt_thursday;
			$friday 	= ( '' == $qt_friday ) ? esc_html__( 'Friday', 'physio-qt' ) : $qt_friday;
			$saturday 	= ( '' == $qt_saturday ) ? esc_html__( 'Saturday', 'physio-qt' ) : $qt_saturday;
			$sunday 	= ( '' == $qt_sunday ) ? esc_html__( 'Sunday', 'physio-qt' ) : $qt_sunday;

			// Define all weekdays
			$this->weekdays = array(
				'Monday'	=> 'Monday',
				'Tuesday'	=> 'Tuesday',
				'Wednesday'	=> 'Wednesday',
				'Thursday'	=> 'Thursday',
				'Friday'	=> 'Friday',
				'Saturday'	=> 'Saturday',
				'Sunday'	=> 'Sunday'
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			extract( $args );
			echo $before_widget;

			$qt_closed_text = get_theme_mod( 'qt_opening_closed_text' );
			$qt_separator = get_theme_mod( 'qt_opening_separator' );
			$qt_extra_info = get_theme_mod( 'qt_opening_extra_info' );

			// Show widget title if isset
			$instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

			// Show closed text
			$closed_text = ( isset( $qt_closed_text ) ) ? $qt_closed_text : $instance['closed'];

			// Show separator
			$separator = ( isset( $qt_closed_text ) ) ? $qt_separator : $instance['separator'];

			// Show extra info
			$extra_info = ( isset( $qt_extra_info ) ) ? $qt_extra_info : $instance['extra_info'];

			// Get the current time
			$get_the_time = current_time( 'timestamp' ) + get_option( 'gmt_offset' ) * 3600;

			if ( ! empty( $instance['title'] ) ) {
				echo $before_title . wp_kses_post( $instance['title'] ) . $after_title;
			}

			$return = '';
			$return .= '<div class="opening-hours">';
			$return .= '<ul>';
				
				foreach ( $this->weekdays as $key ) {

					// Get the weekday translation settings
					$day_name = get_theme_mod( 'qt_weekday_' . strtolower( $key ) );

					// Check if translation isset else display default
					$weekday_name = ( ! empty( $day_name ) ) ? $day_name : $key;

					// Get the opening times from the customizer
					$qt_from = get_theme_mod( 'qt_weekday_'. strtolower( $key ) .'_from' );
					$qt_to = get_theme_mod( 'qt_weekday_'. strtolower( $key ) .'_to' );

					// Check if customizer times are entered - if not display from widget
					$weekday_from = ( '' != $qt_from ) ? $qt_from : $instance[$key . '_from'];
					$weekday_to = ( '' != $qt_to ) ? $qt_to : $instance[$key . '_to'];
					
					// Add today class if it is current day
					$is_today = date( 'l', $get_the_time ) == $key ? ' today' : '';

					// Add label class if highlight is checked
					$class = empty( $instance['highlight'] ) ? '' : ' label';

					$return .= '<li class="weekday'. esc_attr( $is_today ) .'">';
					$return .= esc_html( $weekday_name );

					if ( '' != $weekday_from || '' != $weekday_to ) {
						
						// Display opening time from, time seperator, to
						$return .= '<span class="right">';
						$return .= esc_html( $weekday_from );

						if ( '' != $separator ) {
							$return .= esc_html( $separator );
						}

						$return .= esc_html( $weekday_to );

					} elseif ( '' != $closed_text ) {

						$return .= '<span class="right'. esc_attr( $class ) .'">';
						$return .= esc_html( $closed_text );
					}

					$return .= '</span>';
					$return .= '</li>';
				}

			$return .= '</ul>';

			// Extra information
			if ( '' != $extra_info ) {
				$return .= '<span class="extra">';
				$return .= esc_html( $extra_info );
				$return .= '</span>';
			}

			$return .= '</div>';

			echo wp_kses_post( $return );
			echo $after_widget;
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();

			$instance['title'] = wp_kses_post( $new_instance['title'] );

			foreach ( $this->weekdays as $key => $day ) {
				$instance[$key . '_from'] = sanitize_text_field( $new_instance[$key . '_from'] );
				$instance[$key . '_to']   = sanitize_text_field( $new_instance[$key . '_to'] );
			}

			$instance['separator']  = esc_html( $new_instance['separator'] );
			$instance['closed']	    = sanitize_text_field( $new_instance['closed'] );
			$instance['highlight']  = sanitize_key( $new_instance['highlight'] );
			$instance['extra_info'] = sanitize_text_field( $new_instance['extra_info'] );

			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			
			$title = isset( $instance['title'] ) ?  $instance['title'] : esc_html__( 'Opening Hours', 'physio-qt' );

			foreach ( $this->weekdays as $key => $day ) {
				$from[$key] = isset( $instance[$key . '_from'] ) ? $instance[$key . '_from'] : '';
				$to[$key]   = isset( $instance[$key . '_to'] ) ? $instance[$key . '_to'] : '';
			}

			$separator  = isset( $instance['separator'] ) ?  $instance['separator'] : '';
			$closed     = isset( $instance['closed'] ) ?  $instance['closed'] : '';
			$highlight  = empty( $instance['highlight'] ) ? '' : $instance['highlight'];
			$extra_info = isset( $instance['extra_info'] ) ?  $instance['extra_info'] : '';
			?>

			<div class="qt-widget-notice">
				<p>
					<i class="dashicons dashicons-info"></i>
					<strong><?php esc_html_e( "By default the opening hours under Appearance → Customize → Theme Options → Opening Hours are displayed. There are cases where you need to show different opening hours (e.g. multiple locations). Leave all opening hours in the theme customizer empty and add them below to the fields to show different hours", 'physio-qt' ); ?></strong>
				</p>
			</div>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title', 'physio-qt' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<?php foreach ( $this->weekdays as $key => $day ) { ?>

				<table style="border-spacing:10px;background-color:#fafafa;border: 1px solid #e9e9e9;margin-bottom:10px;padding:0 5px 5px 5px;">

					<tr>
						<td style="width: 40%;">
							<strong><label for="<?php echo esc_attr( $this->get_field_id( $key . '_opened' ) ); ?>"><?php echo esc_html( $day ); ?></label></strong>
						</td>
						<td>
							<label for="<?php echo esc_attr( $this->get_field_id( $key . '_from' ) ); ?>"><?php esc_html_e( 'From', 'physio-qt' ); ?>:</label>
							<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( $key . '_from' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key . '_from' ) ); ?>" value="<?php echo esc_attr( $from[$key] ); ?>" size="10" />
						</td>
						<td>
							<label for="<?php echo esc_attr( $this->get_field_id( $key . '_to' ) ); ?>"><?php esc_html_e( 'To', 'physio-qt' ); ?>:</label>
							<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( $key . '_to' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key . '_to' ) ); ?>" value="<?php echo esc_attr( $to[$key] ) ?>" size="10" />
						</td>
					</tr>

				</table>

			<?php } ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'separator' ) ); ?>"><?php esc_html_e( 'Time Separator', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'separator' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'separator' ) ); ?>" type="text" value="<?php echo esc_attr( $separator ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'closed' ) ); ?>"><?php esc_html_e( 'Closed Text', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'closed' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'closed' ) ); ?>" type="text" value="<?php echo esc_attr( $closed ); ?>" /><br>
				<small><?php esc_html_e( "Leave both fields empty to display the closed message", 'physio-qt' ); ?></small>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $highlight, 'on'); ?> id="<?php echo esc_attr( $this->get_field_id( 'highlight' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'highlight' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'highlight' ) ); ?>"><?php esc_html_e( 'Highlight closed text?', 'physio-qt' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'extra_info' ) ); ?>"><?php esc_html_e( 'Extra Information (lunchbreaks, vacations)', 'physio-qt' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'extra_info' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'extra_info' ) ); ?>" type="text" value="<?php echo esc_attr( $extra_info ); ?>" />
			</p>
			
			<?php
		}
	}
}