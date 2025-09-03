<?php
/**
 * Widget: Single Testimonial
 *
 * @package thelandscaper-toolkit
 */

if ( ! class_exists( 'QT_Single_Testimonial' ) ) {
	class QT_Single_Testimonial extends WP_Widget {

		/**
		* Register widget with WordPress.
		*/
		public function __construct() {
			parent::__construct(
			false,
				esc_html__( 'QT: Single Testimonial', 'physio-qt' ),
				array(
					'description' => esc_html__( 'Add a single testimonial', 'physio-qt' ),
					'classname'   => 'widget-single-testimonial',
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
			echo $args['before_widget'];

			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'] );
			$quote = empty( $instance['quote'] ) ? '' : $instance['quote'];
			$author = empty( $instance['author'] ) ? '' : $instance['author'];
			$description = empty( $instance['description'] ) ? '' : $instance['description'];
			$author_image = empty( $instance['author_image'] ) ? '' : $instance['author_image'];
			$layout = empty( $instance['layout'] ) ? '' : $instance['layout'];
			$alignment = empty( $instance['alignment'] ) ? '' : $instance['alignment'];
			$font_style = empty( $instance['font_style'] ) ? '' : $instance['font_style'];
			?>

			<?php if( $title ) : ?>
				<h3 class="widget-title"><?php echo wp_kses_post( $title ); ?></h3>
			<?php endif; ?>

			<div class="testimonials style-<?php echo esc_attr( $layout ); ?> text-<?php echo esc_attr( $alignment ); ?> font-<?php echo esc_attr( $font_style ); ?>">
				<?php if ( $layout == 'simple' && $author_image != '' ) : ?>
					<div class="testimonial--image">
						<img src="<?php echo esc_url( $instance['author_image'] ); ?>" loading="lazy">
					</div>
				<?php endif; ?>
				<blockquote class="testimonial--quote"><?php echo wp_kses_post( $quote ); ?></blockquote>
				<div class="testimonial--person">
					<?php if ( $layout == 'boxed' && $author_image != '' ) : ?>
						<div class="testimonial--image">
							<img src="<?php echo esc_url( $instance['author_image'] ); ?>" loading="lazy">
						</div>
					<?php endif; ?>
					<div class="testimonial--names">
						<cite class="testimonial--author"><?php echo wp_kses_post( $author ); ?></cite>
						<span class="testimonial--description"><?php echo wp_kses_post( $description ); ?></span>
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

			$instance['title'] = wp_kses_post( $new_instance['title'] );
			$instance['quote'] = wp_kses_post( $new_instance['quote'] );
			$instance['author'] = wp_kses_post( $new_instance['author'] );
			$instance['description'] = wp_kses_post( $new_instance['description'] );
			$instance['author_image'] = esc_url( $new_instance['author_image'] );
			$instance['layout'] = sanitize_key( $new_instance['layout'] );
			$instance['alignment'] = sanitize_key( $new_instance['alignment'] );
			$instance['font_style'] = sanitize_key( $new_instance['font_style'] );

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

			$title = empty( $instance['title'] ) ? '' : $instance['title'];
			$quote = empty( $instance['quote'] ) ? '' : $instance['quote'];
			$author = empty( $instance['author'] ) ? '' : $instance['author'];
			$description = empty( $instance['description'] ) ? '' : $instance['description'];
			$author_image = empty( $instance['author_image'] ) ? '' : $instance['author_image'];
			$layout = empty( $instance['layout'] ) ? '' : $instance['layout'];
			$alignment = empty( $instance['alignment'] ) ? '' : $instance['alignment'];
			$font_style = empty( $instance['font_style'] ) ? '' : $instance['font_style'];
			?>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title', 'physio-qt'  ); ?>:</label><br>
				<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'author_image' ) ); ?>"><?php esc_html_e( 'Author Image (optional)', 'physio-qt' ); ?>:</label><br />
				<input class="widefat upload-file-url" id="<?php echo esc_attr( $this->get_field_id( 'author_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'author_image' ) ); ?>" type="text" value="<?php echo esc_attr( $author_image ); ?>" />
				<input type="button" class="upload-file-button button" value="Add Image" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'author' ) ); ?>"><?php esc_html_e( 'Author Name', 'physio-qt'  ); ?>:</label><br>
				<input id="<?php echo esc_attr( $this->get_field_id( 'author' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'author' ) ); ?>" type="text" value="<?php echo esc_attr( $author ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Author Description', 'physio-qt'  ); ?>:</label><br>
				<input id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" type="text" value="<?php echo esc_attr( $description ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'quote' ) ); ?>"><?php esc_html_e( 'Quote', 'physio-qt' ); ?>:</label>
				<textarea class="widefat" rows="3" id="<?php echo esc_attr( $this->get_field_id( 'quote' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'quote' ) ); ?>"><?php echo esc_attr( $quote ); ?></textarea>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php esc_html_e( 'Testimonial Style', 'physio-qt' ); ?>:</label> <br>
				<select id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>">
					<option value="simple" <?php selected( $layout, 'simple' ); ?>><?php esc_html_e( 'Simple', 'physio-qt' ); ?></option>
					<option value="boxed" <?php selected( $layout, 'boxed' ); ?>><?php esc_html_e( 'Boxed', 'physio-qt' ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'alignment' ) ); ?>"><?php esc_html_e( 'Alignment', 'physio-qt' ); ?>:</label> <br>
				<select id="<?php echo esc_attr( $this->get_field_id( 'alignment' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'alignment' ) ); ?>">
					<option value="left" <?php selected( $alignment, 'left' ); ?>><?php esc_html_e( 'Left', 'physio-qt' ); ?></option>
					<option value="center" <?php selected( $alignment, 'center' ); ?>><?php esc_html_e( 'Center', 'physio-qt' ); ?></option>
					<option value="right" <?php selected( $alignment, 'right' ); ?>><?php esc_html_e( 'Right', 'physio-qt' ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'font_style' ) ); ?>"><?php esc_html_e( 'Quote Font Style', 'physio-qt' ); ?>:</label> <br>
				<select id="<?php echo esc_attr( $this->get_field_id( 'font_style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'font_style' ) ); ?>">
					<option value="italic" <?php selected( $font_style, 'italic' ); ?>><?php esc_html_e( 'Italic', 'physio-qt' ); ?></option>
					<option value="normal" <?php selected( $font_style, 'normal' ); ?>><?php esc_html_e( 'Normal', 'physio-qt' ); ?></option>
				</select>
			</p>
	 
	    <?php
		}
	}
}