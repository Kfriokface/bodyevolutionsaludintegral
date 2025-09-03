<?php
/**
 * Widget: Social Icons
 *
 * @package physio-toolkit
 */

if ( ! class_exists( 'QT_Social_Icons' ) ) {
	class QT_Social_Icons extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				false,
				esc_html__( 'QT: Social Icons', 'physio-qt' ),
				array(
					'description' => esc_html__( 'Icons to link to your social media profiles', 'physio-qt' ),
					'classname'   => 'widget-social-icons',
				)
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

			// Define Font Awesome 4 social icon classes
			$icon_classes = array(
				'fa fa-facebook-square',
				'fa fa-twitter-square',
				'fa fa-google-plus-square',
				'fa fa-linkedin-square',
				'fa fa-youtube-square',
				'fa fa-instagram',
				'fa fa-tumblr-square',
				'fa fa-pinterest-square',
				'fa fa-yelp',
				'fa fa-houzz'
			);

			// Overwrite if Font Awesome version 5 is selected
			if ( '5' === get_theme_mod( 'qt_fontawesome_version' ) ) {
				$icon_classes = array(
					'fab fa-facebook-square',
					'fab fa-twitter-square',
					'fab fa-google-plus-square',
					'fab fa-linkedin',
					'fab fa-youtube-square',
					'fab fa-instagram',
					'fab fa-tumblr-square',
					'fab fa-pinterest-square',
					'fab fa-yelp',
					'fab fa-houzz'
				);
			}

			$instance['yelp']  = empty( $instance['yelp'] ) ? '' : $instance['yelp'];
			$instance['houzz'] = empty( $instance['houzz'] ) ? '' : $instance['houzz'];

			echo $args['before_widget'];
		 	?>

			<div class="social-icons">
				<?php if( $instance['facebook'] ) : ?>
					<a href="<?php echo esc_url( $instance['facebook'] ); ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>>
						<i class="<?php echo esc_attr( $icon_classes[0] ); ?>"></i>
					</a>
				<?php endif; ?>

				<?php if( $instance['twitter'] ) : ?>
					<a href="<?php echo esc_url( $instance['twitter'] ); ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>>
						<i class="<?php echo esc_attr( $icon_classes[1] ); ?>"></i>
					</a>
				<?php endif; ?>

				<?php if( $instance['google_plus'] ) : ?>
					<a href="<?php echo esc_url( $instance['google_plus'] ); ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>>
						<i class="<?php echo esc_attr( $icon_classes[2] ); ?>"></i>
					</a>
				<?php endif; ?>

				<?php if( $instance['linkedin'] ) : ?>
					<a href="<?php echo esc_url( $instance['linkedin'] ); ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>>
						<i class="<?php echo esc_attr( $icon_classes[3] ); ?>"></i>
					</a>
				<?php endif; ?>
				
				<?php if( $instance['youtube'] ) : ?>
					<a href="<?php echo esc_url( $instance['youtube'] ); ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>>
						<i class="<?php echo esc_attr( $icon_classes[4] ); ?>"></i>
					</a>
				<?php endif; ?>
				
				<?php if( $instance['instagram'] ) : ?>
					<a href="<?php echo esc_url( $instance['instagram'] ); ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>>
						<i class="<?php echo esc_attr( $icon_classes[5] ); ?>"></i>
					</a>
				<?php endif; ?>
				
				<?php if( $instance['tumblr'] ) : ?>
					<a href="<?php echo esc_url( $instance['tumblr'] ); ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>>
						<i class="<?php echo esc_attr( $icon_classes[6] ); ?>"></i>
					</a>
				<?php endif; ?>

				<?php if( $instance['pinterest'] ) : ?>
					<a href="<?php echo esc_url( $instance['pinterest'] ); ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>>
						<i class="<?php echo esc_attr( $icon_classes[7] ); ?>"></i>
					</a>
				<?php endif; ?>

				<?php if( $instance['yelp'] ) : ?>
					<a href="<?php echo esc_url( $instance['yelp'] ); ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>>
						<i class="<?php echo esc_attr( $icon_classes[8] ); ?>"></i>
					</a>
				<?php endif; ?>

				<?php if( $instance['houzz'] ) : ?>
					<a href="<?php echo esc_url( $instance['houzz'] ); ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>>
						<i class="<?php echo esc_attr( $icon_classes[9] ); ?>"></i>
					</a>
				<?php endif; ?>
			</div>

			<?php
			echo $args['after_widget'];
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

			$instance['facebook']	 = sanitize_text_field( $new_instance['facebook'] );
			$instance['twitter']	 = sanitize_text_field( $new_instance['twitter'] );
			$instance['google_plus'] = sanitize_text_field( $new_instance['google_plus'] );
			$instance['linkedin']	 = sanitize_text_field( $new_instance['linkedin'] );
			$instance['youtube']	 = sanitize_text_field( $new_instance['youtube'] );
			$instance['instagram']	 = sanitize_text_field( $new_instance['instagram'] );
			$instance['tumblr']		 = sanitize_text_field( $new_instance['tumblr'] );
			$instance['pinterest']	 = sanitize_text_field( $new_instance['pinterest'] );
			$instance['yelp']	 	 = sanitize_text_field( $new_instance['yelp'] );
			$instance['houzz']	 	 = sanitize_text_field( $new_instance['houzz'] );
			$instance['new_tab']	 = ! empty( $new_instance['new_tab'] ) ? sanitize_key( $new_instance['new_tab'] ) : '';
			
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
			$new_tab     = empty( $instance['new_tab'] ) ? '' : $instance['new_tab'];
			$facebook 	 = empty( $instance['facebook'] ) ? '' : $instance['facebook'];
			$twitter 	 = empty( $instance['twitter'] ) ? '' : $instance['twitter'];
			$google_plus = empty( $instance['google_plus'] ) ? '' : $instance['google_plus'];
			$linkedin    = empty( $instance['linkedin'] ) ? '' : $instance['linkedin'];
			$instagram   = empty( $instance['instagram'] ) ? '' : $instance['instagram'];
			$tumblr 	 = empty( $instance['tumblr'] ) ? '' : $instance['tumblr'];
			$pinterest 	 = empty( $instance['pinterest'] ) ? '' : $instance['pinterest'];
			$youtube 	 = empty( $instance['youtube'] ) ? '' : $instance['youtube'];
			$yelp	 	 = empty( $instance['yelp'] ) ? '' : $instance['yelp'];
			$houzz	 	 = empty( $instance['houzz'] ) ? '' : $instance['houzz'];
			?>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $new_tab, 'on'); ?> id="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'new_tab' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>"><?php esc_html_e('Open link in new browser tab?', 'physio-qt' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>">Facebook <?php esc_html_e( 'Link', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" type="text" value="<?php echo esc_attr( $facebook ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>">Twitter <?php esc_html_e( 'Link', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" type="text" value="<?php echo esc_attr( $twitter ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'google_plus' ) ); ?>">Google+ <?php esc_html_e( 'Link', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'google_plus' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'google_plus' ) ); ?>" type="text" value="<?php echo esc_attr( $google_plus ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>">LinkedIn <?php esc_html_e( 'Link', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" type="text" value="<?php echo esc_attr( $linkedin ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>">Youtube <?php esc_html_e( 'Link', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'youtube ' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'youtube' ) ); ?>" type="text" value="<?php echo esc_attr( $youtube ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>">Instagram <?php esc_html_e( 'Link', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>" type="text" value="<?php echo esc_attr( $instagram ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'tumblr' ) ); ?>">Tumblr <?php esc_html_e( 'Link', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tumblr') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tumblr' ) ); ?>" type="text" value="<?php echo esc_attr( $tumblr ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>">Pinterest <?php esc_html_e( 'Link', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pinterest' ) ); ?>" type="text" value="<?php echo esc_attr( $pinterest ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'yelp' ) ); ?>">Yelp <?php esc_html_e( 'Link', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'yelp' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'yelp' ) ); ?>" type="text" value="<?php echo esc_attr( $yelp ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'houzz' ) ); ?>">Houzz <?php esc_html_e( 'Link', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'houzz' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'houzz' ) ); ?>" type="text" value="<?php echo esc_attr( $houzz ); ?>" />
			</p>
			<?php 
		}
	}
}