<?php
/**
 * Widget: Google Map Iframe Embedded (free)
 *
 * @package physio-toolkit
 */

if ( ! class_exists( 'QT_Google_Map_Iframe' ) ) {
	class QT_Google_Map_Iframe extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */
	    public function __construct() {
			parent::__construct(
				false,
				esc_html__( 'QT: Google Maps (Free)', 'physio' ),
				array(
					'description' => esc_html__( 'Display iframe embedded Google Map (free and no setup required)', 'physio-qt' ),
					'classname'   => 'widget-google-map-iframe',
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

	    	$address = empty( $instance['address'] ) ? 'Central Park New York' : $instance['address'];
	    	$zoom 	 = empty( $instance['zoom'] ) ? 12 : $instance['zoom'];
	    	$width = empty( $instance['width'] ) ? 100 : $instance['width'];
	    	$w_unit = empty( $instance['width_unit'] ) ? '%' : $instance['width_unit'];
	    	$height = empty( $instance['height'] ) ? 400 : $instance['height'];

	    	if ( $w_unit == 'percentage' ) {
	    		$w_unit = '%';
	    	} elseif ( $w_unit == 'pixels' ) {
	    		$w_unit = 'px';
	    	}

	    	echo $args['before_widget'];
	    	?>

	    	<div class="qt-map-iframe">
				<iframe frameborder="0" src="https://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=<?php echo str_replace(",", "", str_replace(" ", "+", esc_html( $address ) ) ); ?>&z=<?php echo absint( $zoom ); ?>&output=embed" width="<?php echo esc_attr( $width ); ?><?php echo esc_attr( $w_unit ); ?>" height="<?php echo esc_attr( $height ); ?>px;"></iframe>
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

			$instance['address'] 	= sanitize_text_field( $new_instance['address'] );
			$instance['zoom'] 		= absint( $new_instance['zoom'] );
			$instance['width'] 		= absint( $new_instance['width'] );
			$instance['width_unit'] = sanitize_key( $new_instance['width_unit'] );
			$instance['height'] 	= absint( $new_instance['height'] );
			
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
			$address = isset( $instance['address'] ) ? $instance['address'] : 'Central Park New York';
			$zoom 	 = isset( $instance['zoom'] ) ? $instance['zoom'] : 12;
			$width 	 = isset( $instance['width'] ) ? $instance['width'] : 100;
			$height  = isset( $instance['height'] ) ? $instance['height'] : 400;
			?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_html_e( 'Address', 'physio-qt' ); ?></label><br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>" value="<?php echo esc_attr( $address ); ?>" type="text" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'zoom' ) ); ?>"><?php esc_html_e( 'Map Zoom:', 'physio-qt' ); ?></label><br>
				<select id="<?php echo esc_attr( $this->get_field_id( 'zoom' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'zoom' ) ); ?>">
				<?php for ( $i=1; $i < 25; $i++ ) : ?>
					<option value="<?php echo esc_attr( $i ); ?>" <?php selected( $zoom, $i ); ?>><?php echo esc_html( $i ); ?></option>
				<?php endfor; ?>
				</select>
			</p>

			<div style="clear:both;">

				<p style="float:left; width: 80%; padding: 0 5px 0 0;">
					<label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"><?php esc_html_e( 'Width', 'physio-qt' ); ?></label> <small><?php esc_html_e( '(Default is 100)', 'physio-qt' ); ?></small> <br>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'width' ) ); ?>" value="<?php echo esc_attr( $width ); ?>" type="number" />
				</p>
				<p style="float:left; width: 19%; padding: 0 0 0 5px;">
					<label for="<?php echo esc_attr( $this->get_field_id( 'width_unit' ) ); ?>"><?php esc_html_e( 'Unit', 'physio-qt' ); ?></label> <small><?php esc_html_e( '(Default is %)', 'physio-qt' ); ?></small>
					<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'width_unit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'width_unit' ) ); ?>">
						<option <?php selected( $instance['width_unit'], 'percentage' ); ?> value="percentage">%</option>
						<option <?php selected( $instance['width_unit'], 'pixels' ); ?> value="pixels">px</option>
					</select>
				</p>

			</div>

			<div style="clear:both;">

				<p style="float:left; width: 100%; padding: 0 5px 0 0;">
					<label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_html_e( 'Height', 'physio-qt' ); ?></label> <small><?php esc_html_e( '(Default is 450)', 'physio-qt' ); ?></small> <br>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" value="<?php echo esc_attr( $height ); ?>" type="number" />
				</p>

			</div>
			
			<?php
		}
	}
}