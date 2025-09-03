<?php
/**
 * Widget: Brand Logo
 *
 * @package physio-toolkit
 */

if ( ! class_exists( 'QT_Brand_Image' ) ) {
	class QT_Brand_Image extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */
	    public function __construct() {
			parent::__construct(
				false,
				esc_html__( 'QT: Brand Logo', 'physio-qt' ),
				array(
					'description' => esc_html__( 'Add a small logo of a company or brand', 'physio-qt' ),
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

	    	// Alt text option
			$image_alt = empty( $instance['image_alt'] ) ? '' : 'alt="'. esc_html( $instance['image_alt'] ) .'"';

	    	echo $args['before_widget'];

	    	if ( ! empty ( $instance['image_link'] ) ) : ?>
				<a href="<?php echo esc_url( $instance['image_link'] ); ?>" class="brand-image<?php echo empty ( $instance['image_border'] ) ? '' : ' brand-border'; ?><?php echo empty ( $instance['image_hover'] ) ? '' : ' brand-hover'; ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?><?php echo empty ( $instance['image_tooltip'] ) ? '' : 'data-toggle="tooltip" data-original-title="'. esc_attr( $instance['image_tooltip'] ) .'"'; ?>>
			<?php else : ?>
				<div class="brand-image<?php echo empty ( $instance['image_border'] ) ? '' : ' brand-border'; ?><?php echo empty ( $instance['image_hover'] ) ? '' : ' brand-hover'; ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?><?php echo empty ( $instance['image_tooltip'] ) ? '' : 'data-toggle="tooltip" data-original-title="'. esc_attr( $instance['image_tooltip'] ) .'"'; ?>>
			<?php endif; ?>
				<img src="<?php echo esc_url( $instance['image_url'] ); ?>"<?php echo wp_kses_post( $image_alt );?> loading="lazy">
	    	</<?php echo empty ( $instance['image_link'] ) ? 'div' : 'a'; ?>>
				
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
			
			$instance['image_url'] 		= esc_url( $new_instance['image_url'] );
			$instance['image_link']		= esc_url( $new_instance['image_link'] );
			$instance['new_tab'] 		= ! empty( $new_instance['new_tab'] ) ? sanitize_key( $new_instance['new_tab'] ) : '';
			$instance['image_tooltip']	= esc_html( $new_instance['image_tooltip'] );
			$instance['image_alt']		= esc_html( $new_instance['image_alt'] );
			$instance['image_border'] 	= ! empty( $new_instance['image_border'] ) ? sanitize_key( $new_instance['image_border'] ) : '';
			$instance['image_hover'] 	= ! empty( $new_instance['image_hover'] ) ? sanitize_key( $new_instance['image_hover'] ) : '';
			
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
			$image_url 		= empty( $instance['image_url'] ) ? '' : $instance['image_url'];
			$image_link 	= empty( $instance['image_link'] ) ? '' : $instance['image_link'];
			$new_tab 		= empty( $instance['new_tab'] ) ? '' : $instance['new_tab'];
			$image_tooltip 	= empty( $instance['image_tooltip'] ) ? '' : $instance['image_tooltip'];
			$image_alt 		= empty( $instance['image_alt'] ) ? '' : $instance['image_alt'];
			$image_border 	= empty( $instance['image_border'] ) ? '' : $instance['image_border'];
			$image_hover 	= empty( $instance['image_hover'] ) ? '' : $instance['image_hover'];
			?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'image_url' ) ); ?>"><?php esc_html_e( 'Upload Image', 'physio-qt' ); ?>:</label><br />
				<input class="widefat upload-file-url" id="<?php echo esc_attr( $this->get_field_id( 'brochure_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_url' ) ); ?>" type="text" value="<?php echo esc_attr( $image_url ); ?>" />
				<input type="button" class="upload-file-button button" value="Add Image" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'image_link' ) ); ?>"><?php esc_html_e( 'Add Link (optional)', 'physio-qt' ); ?>:</label><br />
				<input class="widefat" type="text" name="<?php echo esc_attr( $this->get_field_name( 'image_link' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'image_link' ) ); ?>" value="<?php echo esc_attr( $image_link ); ?>" />
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $new_tab, 'on'); ?> id="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'new_tab' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>"><?php esc_html_e( 'Open link in new browser tab', 'physio-qt' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'image_tooltip' ) ); ?>"><?php esc_html_e( 'Tooltip text (optional)', 'physio-qt' ); ?>:</label><br />
				<input class="widefat" type="text" name="<?php echo esc_attr( $this->get_field_name( 'image_tooltip' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'image_tooltip' ) ); ?>" value="<?php echo esc_attr( $image_tooltip ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'image_alt' ) ); ?>"><?php esc_html_e( 'Image alt text (optional)', 'physio-qt' ); ?>:</label><br />
				<input class="widefat" type="text" name="<?php echo esc_attr( $this->get_field_name( 'image_alt' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'image_alt' ) ); ?>" value="<?php echo esc_attr( $image_alt ); ?>" />
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $image_border, 'on'); ?> id="<?php echo esc_attr( $this->get_field_id( 'image_border' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_border' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'image_border' ) ); ?>"><?php esc_html_e( 'Add border around image?', 'physio-qt' ); ?></label>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $image_hover, 'on'); ?> id="<?php echo esc_attr( $this->get_field_id( 'image_hover' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_hover' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'image_hover' ) ); ?>"><?php esc_html_e( 'Enable opacity hover effect?', 'physio-qt' ); ?></label>
			</p>

			<?php
		}
	}
}