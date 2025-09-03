<?php
/**
 * Widget: Testimonials
 *
 * @package physio-toolkit
 */

if ( ! class_exists( 'QT_Testimonials' ) ) {
	class QT_Testimonials extends WP_Widget {

		private $allowed_html_in_textareas;
		private $current_widget_id;

		/**
		* Register widget with WordPress.
		*/
		public function __construct() {
			parent::__construct(
			false,
				esc_html__( 'QT: Testimonials', 'physio-qt' ),
				array(
					'description' => esc_html__( 'Display a testimonial carousel', 'physio-qt' ),
					'classname'   => 'widget-testimonials',
				)
			);

			$this->allowed_html_in_textareas = array(
				'a' 	 => array(
					'href'   => array(),
					'target' => array(),
					'class'  => array(),
				),
				'strong' => array(),
				'br'     => array(),
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
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$autocycle = empty( $instance['autocycle'] ) ? false : 'yes' === $instance['autocycle'];
			$interval = empty( $instance['interval'] ) ? 6000 : absint( $instance['interval'] );
			$touch_support = empty( $instance['touch_support'] ) ? false : 'yes' === $instance['touch_support'];

			$columns = empty( $instance['columns'] ) ? '3' : round( 12 / $instance['columns'] ); // Calculate columns based on Bootstrap grid
			$amount = empty( $instance['columns'] ) ? 2 : absint( $instance['columns'] );

			if ( isset( $instance['quote'] ) ) :
				$testimonials = array( array(
					'quote' => $instance['quote'], $this->allowed_html_in_textareas,
					'author' => $instance['author'],
					'description' => $instance['description'],
				) );
			else :
				$testimonials = array_values( $instance['testimonials'] );
			endif;

			
			echo $args['before_widget']; ?>

			<div class="testimonials">
				<?php if( $title ) : ?>
					<h3 class="widget-title"><?php echo wp_kses_post( $title ); ?></h3>
				<?php endif; ?>

				<?php if ( count( $testimonials ) > $amount ) : ?>
					<div class="testimonial-controls">
						<a class="left testimonial-control" href="#testimonials-carousel-<?php echo esc_attr( $args['widget_id'] ); ?>" role="button" data-slide="prev">
							<i class="fa fa-angle-left" aria-hidden="true"></i>
							<span class="sr-only">Previous</span>
						</a>
						<a class="right testimonial-control" href="#testimonials-carousel-<?php echo esc_attr( $args['widget_id'] ); ?>" role="button" data-slide="next">
							<i class="fa fa-angle-right" aria-hidden="true"></i>
							<span class="sr-only">Next</span>
						</a>
					</div>
				<?php endif; ?>
				
				<div id="testimonials-carousel-<?php echo esc_attr( $args['widget_id'] ); ?>" class="testimonial-carousel<?php echo esc_attr( $touch_support ) ? ' carousel-touch' : ''; ?> carousel slide" <?php echo esc_attr( $autocycle ) ? 'data-ride="carousel" data-interval="' . esc_attr( $interval ) . '"' : ''; ?>>
					<div class="carousel-inner" role="listbox">
						<div class="item active">
							<div class="row">
								
								<?php
								foreach ( $testimonials as $index => $testimonial ) : ?>
									<?php echo ( 0 !== $index && 0 === $index % $amount ) ? '</div></div><div class="item"><div class="row">' : ''; ?>
									<div class="col-xs-12 col-sm-6 col-md-<?php echo esc_attr( $columns ); ?>">
										<blockquote class="testimonial--quote">
											<?php echo wp_kses_post( $testimonial['quote'] ); ?>
										</blockquote>
										<cite class="testimonial--author">
											<?php echo esc_html( $testimonial['author'] ); ?>
										</cite>
										<span class="testimonial--description">
											<?php echo esc_html( $testimonial['description'] ); ?>
										</span>
									</div>
									<?php
								endforeach;
								?>

							</div>
						</div>
					</div>	
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

			$instance['title']     = wp_kses_post( $new_instance['title'] );
			$instance['columns']   = sanitize_key( $new_instance['columns'] );
			$instance['autocycle'] = sanitize_key( $new_instance['autocycle'] );
			$instance['interval']  = absint( $new_instance['interval'] );
			$instance['touch_support'] = sanitize_key( $new_instance['touch_support'] );

			foreach ( $new_instance['testimonials'] as $key => $testimonial ) {
				$instance['testimonials'][$key]['id'] = sanitize_key( $testimonial['id'] );
				$instance['testimonials'][$key]['quote'] = wp_kses( $testimonial['quote'], $this->allowed_html_in_textareas );
				$instance['testimonials'][$key]['author'] = sanitize_text_field( $testimonial['author'] );
				$instance['testimonials'][$key]['description'] = sanitize_text_field( $testimonial['description'] );
			}

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

			$title     = empty( $instance['title'] ) ? '' : $instance['title'];
			$columns   = empty( $instance['columns'] ) ? 2 : $instance['columns'];
			$autocycle = empty( $instance['autocycle'] ) ? 'no' : $instance['autocycle'];
			$interval  = empty( $instance['interval'] ) ? 6000 : $instance['interval'];
			$touch_support = empty( $instance['touch_support'] ) ? 'no' : $instance['touch_support'];

			if ( isset( $instance['quote'] ) ) {
				$testimonials = array( array(
					'id'       => 1,
					'quote'    => $instance['quote'],
					'author'   => $instance['author'],
					'location' => $instance['location'],
				) );
			}
			else {
				$testimonials = isset( $instance['testimonials'] ) ? array_values( $instance['testimonials'] ) : array(
					array(
						'id'       => 1,
						'quote'    => '',
						'author'   => '',
						'location' => '',
					)
				);
			}

			if ( 'temp' === $this->id ) {
				$this->current_widget_id = $this->number;
			}
			else {
				$this->current_widget_id = $this->id;
			}
			
			?>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title', 'physio-qt'  ); ?>:</label><br>
				<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php esc_html_e( 'Show amount of testimonials in a row', 'physio-qt' ); ?>:</label><br>
				<select name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>">
					<option value="1"<?php selected( $columns, '1' ) ?>><?php esc_html_e( '1', 'physio-qt'  ); ?></option>
					<option value="2"<?php selected( $columns, '2' ) ?>><?php esc_html_e( '2', 'physio-qt'  ); ?></option>
					<option value="3"<?php selected( $columns, '3' ) ?>><?php esc_html_e( '3', 'physio-qt'  ); ?></option>
					<option value="4"<?php selected( $columns, '4' ) ?>><?php esc_html_e( '4', 'physio-qt'  ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'autocycle' ) ); ?>"><?php esc_html_e( 'Auto cycle carousel', 'physio-qt' ); ?>:</label><br>
				<select name="<?php echo esc_attr( $this->get_field_name( 'autocycle' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'autocycle' ) ); ?>">
					<option value="yes"<?php selected( $autocycle, 'yes' ) ?>><?php esc_html_e( 'Yes', 'physio-qt'  ); ?></option>
					<option value="no"<?php selected( $autocycle, 'no' ) ?>><?php esc_html_e( 'No', 'physio-qt'  ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'interval' ) ); ?>"><?php esc_html_e( 'Interval (in miliseconds)', 'physio-qt' ); ?>:</label><br>
				<input id="<?php echo esc_attr( $this->get_field_id( 'interval' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'interval' ) ); ?>" type="number" min="0" step="500" value="<?php echo esc_attr( $interval ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'touch_support' ) ); ?>"><?php esc_html_e( 'Enable touch support (for mouse, table and mobile)', 'physio-qt' ); ?>:</label><br>
				<select name="<?php echo esc_attr( $this->get_field_name( 'touch_support' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'touch_support' ) ); ?>">
					<option value="yes"<?php selected( $touch_support, 'yes' ) ?>><?php esc_html_e( 'Yes', 'physio-qt'  ); ?></option>
					<option value="no"<?php selected( $touch_support, 'no' ) ?>><?php esc_html_e( 'No', 'physio-qt'  ); ?></option>
				</select>
			</p>

			<hr>

			<h3><?php esc_html_e( 'Testimonials', 'physio-qt'  ); ?>:</h3>

			<div class="physio-single-testimonial">
				<script type="text/template" id="js-testimonial-<?php echo esc_attr( $this->current_widget_id ); ?>">
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'testimonials' ) ); ?>-{{id}}-quote"><?php esc_html_e( 'Quote', 'physio-qt' ); ?>:</label>
						<textarea rows="4" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'testimonials' ) ); ?>-{{id}}-quote" name="<?php echo esc_attr( $this->get_field_name( 'testimonials' ) ); ?>[{{id}}][quote]">{{quote}}</textarea>
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'testimonials' ) ); ?>-{{id}}-author"><?php esc_html_e( 'Author', 'physio-qt' ); ?>:</label>
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'testimonials' ) ); ?>-{{id}}-author" name="<?php echo esc_attr( $this->get_field_name( 'testimonials' ) ); ?>[{{id}}][author]" type="text" value="{{author}}" />
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( 'testimonials' ) ); ?>-{{id}}-description"><?php esc_html_e( 'Author Description', 'physio-qt' ); ?>:</label>
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'testimonials' ) ); ?>-{{id}}-description" name="<?php echo esc_attr( $this->get_field_name( 'testimonials' ) ); ?>[{{id}}][description]" type="text" value="{{description}}" />
					</p>
					<p>
						<input name="<?php echo esc_attr( $this->get_field_name( 'testimonials' ) ); ?>[{{id}}][id]" type="hidden" value="{{id}}" />
						<a href="#" class="js-remove-testimonial"><span class="dashicons dashicons-dismiss"></span><?php esc_html_e( 'Remove Testimonial', 'physio-qt' ); ?></a>
					</p>
				</script>

				<div id="js-testimonials-<?php echo esc_attr( $this->current_widget_id ); ?>">
					<div id="js-testimonials-list"></div>
					<p><a href="#" class="button" id="js-testimonials-add"><?php esc_html_e( 'Add New Testimonial', 'physio-qt' ); ?></a></p>
				</div>
			</div>
		 
			<script type="text/javascript">
				var testimonialsJSON = <?php echo wp_json_encode( $testimonials ) ?>;
				QTTestimonials.repopulateTestimonials( '<?php echo esc_js( $this->current_widget_id ); ?>', testimonialsJSON );
			</script>
	 
	    <?php
		}
	}
}