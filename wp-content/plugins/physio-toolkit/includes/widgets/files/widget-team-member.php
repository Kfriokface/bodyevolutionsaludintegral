<?php
/**
 * Widget: Team Member
 *
 * @package physio-toolkit
 */

if ( ! class_exists( 'QT_Team_Member' ) ) {
	class QT_Team_Member extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				false,
				esc_html__( 'QT: Team Member', 'physio-qt' ),
				array(
					'description' => esc_html__( 'Display a team member', 'physio-qt' ),
					'classname'   => 'widget-team-member',
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
				'fa fa-facebook',
				'fa fa-twitter',
				'fa fa-linkedin',
				'fa fa-instagram',
				'fa fa-envelope'
			);

			// Overwrite if Font Awesome version 5 is selected
			if ( '5' === get_theme_mod( 'qt_fontawesome_version' ) ) {
				$icon_classes = array(
					'fab fa-facebook-f',
					'fab fa-twitter',
					'fab fa-linkedin',
					'fab fa-instagram',
					'fas fa-envelope'
				);
			}

			echo $args['before_widget'];

			$instance['instagram'] = empty( $instance['instagram'] ) ? '' : $instance['instagram'];
		 	?>

			<div class="team-member">
				<?php if ( $instance['person_image'] ) : ?>
					<div class="team-member--image">
						<img src="<?php echo esc_url( $instance['person_image'] ); ?>" alt="<?php echo esc_attr( $instance['person_name'] ); ?>" loading="lazy">
						<?php if ( $instance['facebook'] || $instance['twitter'] || $instance['linkedin'] || $instance['instagram'] || $instance['email'] ) : ?>
						<div class="team-member--social">
							<div class="overlay--center">
								<?php if ( $instance['facebook'] ) : ?>
									<a href="<?php echo esc_url( $instance['facebook'] ); ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>><i class="<?php echo esc_attr( $icon_classes[0] ); ?>"></i></a>
								<?php endif; ?>
								<?php if ( $instance['twitter'] ) : ?>
									<a href="<?php echo esc_url( $instance['twitter'] ); ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>><i class="<?php echo esc_attr( $icon_classes[1] ); ?>"></i></a>
								<?php endif; ?>
								<?php if ( $instance['linkedin'] ) : ?>
									<a href="<?php echo esc_url( $instance['linkedin'] ); ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>><i class="<?php echo esc_attr( $icon_classes[2] ); ?>"></i></a>
								<?php endif; ?>
								<?php if ( $instance['instagram'] ) : ?>
									<a href="<?php echo esc_url( $instance['instagram'] ); ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>><i class="<?php echo esc_attr( $icon_classes[3] ); ?>"></i></a>
								<?php endif; ?>
								<?php if ( $instance['email'] ) : ?>
									<a href="mailto:<?php echo sanitize_email( $instance['email'] ); ?>"><i class="<?php echo esc_attr( $icon_classes[4] ); ?>"></i></a>
								<?php endif; ?>
							</div>
						</div>
						<?php endif; ?>
						<?php if ( $instance['person_link_url'] ) : ?>
							<a href="<?php echo esc_url( $instance['person_link_url'] ); ?>" class="link-overlay"></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="team-member--content">
					<h4 class="team-member--name">
						<?php if ( ! empty ( $instance['person_link_url'] ) ) : ?>
							<a href="<?php echo esc_url( $instance['person_link_url'] ); ?>">
						<?php endif; ?>
						<?php echo wp_kses_post( $instance['person_name'] ); ?>
						<?php echo empty ( $instance['person_link_url'] ) ? '' : '</a>'; ?>
					</h4>
					<?php if ( ! empty ( $instance['person_tag'] ) ) : ?>
						<span class="team-member--tag"><?php echo esc_html( $instance['person_tag'] ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty ( $instance['person_description'] ) ) : ?>
						<p class="team-member--description"><?php echo wp_kses_post( $instance['person_description'] ); ?></p>
					<?php endif; ?>
					<?php if ( $instance['person_link_text'] ) : ?>
						<a href="<?php echo esc_url( $instance['person_link_url'] ); ?>" class="text-link"><?php echo esc_html( $instance['person_link_text'] ); ?></a>
					<?php endif; ?>
				</div>
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

			$instance['person_image']		= esc_url( $new_instance['person_image'] );
			$instance['person_name']		= wp_kses_post( $new_instance['person_name'] );
			$instance['person_tag']			= sanitize_text_field( $new_instance['person_tag'] );
			$instance['person_description']	= wp_kses_post( $new_instance['person_description'] );
			$instance['person_link_url']	= sanitize_text_field( $new_instance['person_link_url'] );
			$instance['person_link_text']	= sanitize_text_field( $new_instance['person_link_text'] );

			$instance['facebook']			= sanitize_text_field( $new_instance['facebook'] );
			$instance['twitter']			= sanitize_text_field( $new_instance['twitter'] );
			$instance['linkedin']			= sanitize_text_field( $new_instance['linkedin'] );
			$instance['instagram']			= sanitize_text_field( $new_instance['instagram'] );
			$instance['email']				= sanitize_text_field( $new_instance['email'] );
			$instance['new_tab']			= ! empty( $new_instance['new_tab'] ) ? sanitize_key( $new_instance['new_tab'] ) : '';
			
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
			$person_image		= empty( $instance['person_image'] ) ? '' : $instance['person_image'];
			$person_name		= empty( $instance['person_name'] ) ? '' : $instance['person_name'];
			$person_tag			= empty( $instance['person_tag'] ) ? '' : $instance['person_tag'];
			$person_description	= empty( $instance['person_description'] ) ? '' : $instance['person_description'];
			$person_link_url	= empty( $instance['person_link_url'] ) ? '' : $instance['person_link_url'];
			$person_link_text	= empty( $instance['person_link_text'] ) ? '' : $instance['person_link_text'];

			$facebook 			= empty( $instance['facebook'] ) ? '' : $instance['facebook'];
			$twitter 			= empty( $instance['twitter'] ) ? '' : $instance['twitter'];
			$instagram 			= empty( $instance['instagram'] ) ? '' : $instance['instagram'];
			$linkedin 			= empty( $instance['linkedin'] ) ? '' : $instance['linkedin'];
			$email 				= empty( $instance['email'] ) ? '' : $instance['email'];
			$new_tab     		= empty( $instance['new_tab'] ) ? '' : $instance['new_tab'];
			?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'person_name' ) ); ?>"><?php esc_html_e( 'Name', 'physio-qt' ); ?>:</label><br>
				<input id="<?php echo esc_attr( $this->get_field_id( 'person_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'person_name' ) ); ?>" type="text" value="<?php echo esc_attr( $person_name ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'person_tag' ) ); ?>"><?php esc_html_e( 'Tag', 'physio-qt' ); ?>:</label><br>
				<input id="<?php echo esc_attr( $this->get_field_id( 'person_tag' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'person_tag' ) ); ?>" type="text" value="<?php echo esc_attr( $person_tag ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'person_image' ) ); ?>"><?php esc_html_e( 'Image url', 'physio-qt' ); ?>:</label><br />
				<input class="widefat upload-file-url" id="<?php echo esc_attr( $this->get_field_id( 'person_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'person_image' ) ); ?>" type="text" value="<?php echo esc_attr( $person_image ); ?>" style="margin-bottom: 5px;" />
				<input type="button" class="upload-file-button button" value="Upload File" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'person_description' ) ); ?>"><?php esc_html_e( 'Description', 'physio-qt' ); ?>:</label><br>
				<textarea class="widefat" rows="3" id="<?php echo esc_attr( $this->get_field_id( 'person_description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'person_description' ) ); ?>"><?php echo esc_attr( $person_description ); ?></textarea>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'person_link_url' ) ); ?>"><?php esc_html_e( 'Link to (optional)', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'person_link_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'person_link_url' ) ); ?>" type="text" value="<?php echo esc_attr( $person_link_url ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'person_link_text' ) ); ?>"><?php esc_html_e( 'Link to text (optional)', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'person_link_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'person_link_text' ) ); ?>" type="text" value="<?php echo esc_attr( $person_link_text ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>">Facebook <?php esc_html_e( 'URL', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" type="text" value="<?php echo esc_attr( $facebook ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>">Twitter <?php esc_html_e( 'URL', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" type="text" value="<?php echo esc_attr( $twitter ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>">LinkedIn <?php esc_html_e( 'URL', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" type="text" value="<?php echo esc_attr( $linkedin ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>">Instagram <?php esc_html_e( 'URL', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>" type="text" value="<?php echo esc_attr( $instagram ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_html_e( 'Email Address', 'physio-qt' ); ?>:</label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" type="text" value="<?php echo esc_attr( $email ); ?>" />
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $new_tab, 'on'); ?> id="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'new_tab' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>"><?php esc_html_e('Open social links in new browser tab?', 'physio-qt'   ); ?></label>
			</p>

			<?php 
		}
	}
}