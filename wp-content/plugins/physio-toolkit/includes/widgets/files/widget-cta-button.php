<?php
/**
 * Widget: Button
 *
 * @package physio-toolkit
 */

if ( ! class_exists( 'QT_CTA_Button' ) ) {
	class QT_CTA_Button extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				false,
				esc_html__( 'QT: Button', 'physio-qt' ),
				array(
					'description' => esc_html__( 'Default theme button', 'physio-qt' ),
					'classname'   => 'widget-cta-button',
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

			$instance['title'] 	= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'] );
			$style 	= empty( $instance['style'] ) ? 'primary' : $instance['style'];

			// Set right class for the dark outline style button
			if ( 'outline-dark' == $style ) {
				$style = 'outline outline-dark';
			}

			echo $args['before_widget'];
			?>
			
			<a class="btn btn-<?php echo esc_attr( $style ); ?><?php echo empty ( $instance['fullwidth'] ) ? '' : ' fullwidth'; ?>" href="<?php echo esc_url( $instance['link'] ); ?>"<?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>>
				<i class="fa <?php echo esc_attr( $instance['icon'] ); ?>"></i>
				<?php echo esc_html( $instance['btn_title'] ); ?>
			</a>

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

			$instance['title'] 		= wp_kses_post( $new_instance['title'] );
			$instance['btn_title'] 	= wp_kses_post( $new_instance['btn_title'] );
			$instance['icon'] 		= wp_kses_post( $new_instance['icon'] );
			$instance['link'] 		= esc_url( $new_instance['link'] );
			$instance['new_tab'] 	= ! empty( $new_instance['new_tab'] ) ? sanitize_key( $new_instance['new_tab'] ) : '';
			$instance['style'] 		= sanitize_key( $new_instance['style'] );
			$instance['fullwidth'] 	= ! empty( $new_instance['fullwidth'] ) ? sanitize_key( $new_instance['fullwidth'] ) : '';

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
			$title      = empty( $instance['title'] ) ? '' : $instance['title'];
			$btn_title  = empty( $instance['btn_title'] ) ? '' : $instance['btn_title'];
			$icon       = empty( $instance['icon'] ) ? '' : $instance['icon'];
			$link       = empty( $instance['link'] ) ? '' : $instance['link'];
			$new_tab    = empty( $instance['new_tab'] ) ? '' : $instance['new_tab'];
			$style 		= empty( $instance['style'] ) ? '' : $instance['style'];
			$fullwidth  = empty( $instance['fullwidth'] ) ? '' : $instance['fullwidth'];
			?>

			<?php if ( '5' === get_theme_mod( 'qt_fontawesome_version' ) ) { ?>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'Icon', 'the-landscaper-wp' ); ?>:</label><br>
					<input class="icp-auto" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" type="text" value="<?php echo esc_attr( $icon ); ?>" /><br>
					<em><?php echo wp_kses_post( physio_toolkit_fa_example_text() ); ?></em>
				</p>

			<?php } else { ?>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'Icon', 'physio-qt' ); ?>:</label> <em><?php echo wp_kses_post( physio_toolkit_fa_example_text() ); ?></em>
	                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" type="text" value="<?php echo esc_attr( $icon ); ?>" />
				</p>

			<?php } ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'btn_title' ) ); ?>"><?php esc_html_e( 'Text', 'physio-qt' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'btn_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn_title' ) ); ?>" type="text" value="<?php echo esc_attr( $btn_title ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php esc_html_e( 'Link', 'physio-qt' ); ?>:</label> <br />
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" type="text" value="<?php echo esc_url( $link ); ?>" />
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $new_tab, 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'new_tab' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>"><?php esc_html_e( 'Open link in new browser tab?', 'physio-qt' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Button style', 'physio-qt' ); ?>:</label> <br>
				<select id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
					<option value="primary" <?php selected( $style, 'primary' ); ?>><?php esc_html_e( 'Primary', 'physio-qt' ); ?></option>
					<option value="outline-dark" <?php selected( $style, 'outline-dark' ); ?>><?php esc_html_e( 'Outline Dark', 'physio-qt' ); ?></option>
					<option value="outline-light" <?php selected( $style, 'outline-light' ); ?>><?php esc_html_e( 'Outline Light', 'physio-qt' ); ?></option>
				</select>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $fullwidth, 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'fullwidth' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'fullwidth' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'fullwidth' ) ); ?>"><?php esc_html_e( 'Make button full width?', 'physio-qt' ); ?></label>
			</p>

			<script type="text/javascript">
				jQuery( document ).ready( function() {
					if ( jQuery( '.widget-liquid-right .icp-auto, .so-content .icp-auto' ).length ) {
						jQuery( '.widget-liquid-right .icp-auto, .so-content .icp-auto' ).fontIconPicker({
							source: <?php echo wp_json_encode( physio_toolkit_fa_icon_array() ); ?>,
							emptyIcon: true,
							hasSearch: true,
							iconsPerPage: 250,
						});
					}
				});
			</script>

			<?php
		}
	}
}