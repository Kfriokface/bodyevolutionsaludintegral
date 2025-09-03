<?php
/**
 * Widget: Brochure
 *
 * @package physio-toolkit
 */

if ( ! class_exists( 'QT_Brochure' ) ) {
	class QT_Brochure extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */
	    public function __construct() {
			parent::__construct(
				false,
				esc_html__( 'QT: Brochure', 'physio-qt' ),
				array(
					'description' => esc_html__( 'Add a brochure or PDF file to your website', 'physio-qt' ),
					'classname'   => 'widget-brochure',
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
	    	$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'] );

	    	echo $args['before_widget'];

			if ( ! empty ( $instance['title'] ) ) : ?>
				<h6 class="widget-title"><?php echo wp_kses_post( $instance['title'] ); ?></h6>
			<?php endif; ?>

			<a href="<?php echo esc_url( $instance['brochure_url'] ); ?>" class="brochure" <?php echo empty( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>>
				<?php if ( $instance['brochure_icon'] != '' ) : ?>
					<i class="fa <?php echo esc_attr( $instance['brochure_icon'] ); ?>"></i>
				<?php endif; ?>
				<span><?php echo esc_html( $instance['brochure_btn'] ); ?></span>
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
			
			$instance['title']         = wp_kses_post( $new_instance['title'] );
			$instance['brochure_icon'] = wp_kses_post( $new_instance['brochure_icon'] );
			$instance['brochure_url']  = esc_url( $new_instance['brochure_url'] );
			$instance['brochure_btn']  = wp_kses_post( $new_instance['brochure_btn'] );
			$instance['new_tab']	   = ! empty( $new_instance['new_tab'] ) ? sanitize_key( $new_instance['new_tab'] ) : '';
			
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
			$title         = empty( $instance['title'] ) ? '' : $instance['title'];
			$brochure_icon = empty( $instance['brochure_icon'] ) ? 'fa-file-pdf-o' : $instance['brochure_icon'];
			$brochure_url  = empty( $instance['brochure_url'] ) ? '' : $instance['brochure_url'];
			$brochure_btn  = empty( $instance['brochure_btn'] ) ? 'Download Brochure' : $instance['brochure_btn'];
			$new_tab	   = empty( $instance['new_tab'] ) ? '' : $instance['new_tab'];
			?>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title', 'physio-qt' ); ?>:</label><br />
				<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
			</p>

			<?php if ( '5' === get_theme_mod( 'qt_fontawesome_version' ) ) { ?>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'brochure_icon' ) ); ?>"><?php esc_html_e( 'Icon', 'physio-qt' ); ?>:</label><br>
					<input class="icp-auto" id="<?php echo esc_attr( $this->get_field_id( 'brochure_icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'brochure_icon' ) ); ?>" type="text" value="<?php echo esc_attr( $brochure_icon ); ?>" /><br>
					<em><?php echo wp_kses_post( physio_toolkit_fa_example_text() ); ?></em>
				</p>

			<?php } else { ?>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'brochure_icon' ) ); ?>"><?php esc_html_e( 'Icon', 'physio-qt' ); ?>:</label> <em><?php echo wp_kses_post( physio_toolkit_fa_example_text() ); ?></em>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'brochure_icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'brochure_icon' ) ); ?>" type="text" value="<?php echo esc_attr( $brochure_icon ); ?>" />
				</p>

			<?php } ?>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'brochure_url' ) ); ?>"><?php esc_html_e( 'Upload File', 'physio-qt' ); ?>:</label><br />
				<input class="widefat upload-file-url" id="<?php echo esc_attr( $this->get_field_id( 'brochure_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'brochure_url' ) ); ?>" type="text" value="<?php echo esc_attr( $brochure_url ); ?>" />
				<input type="button" class="upload-file-button button" value="Upload File" />
			</p>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'brochure_btn' ) ); ?>"><?php esc_html_e( 'Text on Button', 'physio-qt' ); ?>:</label><br />
				<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'brochure_btn' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'brochure_btn' ) ); ?>" value="<?php echo esc_attr( $brochure_btn ); ?>" class="widefat" />
			</p>
			
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $new_tab, 'on'); ?> id="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'new_tab' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>"><?php esc_html_e( 'Open link in new browser tab', 'physio-qt' ); ?></label>
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