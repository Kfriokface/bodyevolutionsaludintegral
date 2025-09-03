<?php
/**
 * Widget: Featured Page
 *
 * @package physio-toolkit
 */

if ( ! class_exists( 'QT_Feature_Page' ) ) {
	class QT_Feature_Page extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
		 		false,
				esc_html__( 'QT: Featured Page', 'physio-qt' ),
				array( 
					'description' => esc_html__( 'Featured page can be used to list your service pages', 'physio-qt' ),
					'classname'   => 'widget-featured-page',
				)
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {

			// Get post from page ID
			$page_id = $instance['page_id'];

			if ( $page_id ) :
				$page = (array) get_post( $page_id );
			endif;

			// Get the excerpt length setting
			$excerpt_length = ! isset( $instance['excerpt_length'] ) ? 180 : absint( $instance['excerpt_length'] );

			// If excerpt isset show, else show content
			$excerpt = ! empty( $page['post_excerpt'] ) ? strip_tags( $page['post_excerpt'] ) : strip_tags( $page['post_content'] );

			// If content is longer than excerpt length trim the content
			if ( strlen( $excerpt ) > $excerpt_length ) :
				$strpos  = strpos( $excerpt, ' ', $excerpt_length );
				if ( false != $strpos ) :
					$excerpt = substr( $excerpt, 0, $strpos ) . '...';
				endif;
			else :
				$excerpt;
			endif;

			// Get the treated content
			$page['post_excerpt']  = $excerpt;

			// Get permalink from page ID
			$page['url'] = get_permalink( $page_id );

			// Prepare Featured Image srcset
			$page_image_id         = get_post_thumbnail_id( $page_id );
			$page_image_data       = wp_get_attachment_image_src( $page_image_id, 'physio-qt-featured-s' );
			$page['image_url']     = $page_image_data[0];
			$page['image_width']   = $page_image_data[1];
			$page['image_height']  = $page_image_data[2];
			$page['srcset']        = physio_toolkit_srcset_sizes( $page_image_id, array( 'physio-qt-featured-s', 'physio-qt-featured-l' ) );
			$page_image_alt        = get_post_meta( $page_image_id, '_wp_attachment_image_alt', true );

            // Check if image alt text is added, else display title
            $page_image_alt        = ( '' != $page_image_alt ) ? $page_image_alt : $page['post_title'];

			echo $args['before_widget'];
			?>

			<div class="featured-page">
				<?php if ( $page_image_id ) : ?>
					<div class="featured-page--image">
						<a href="<?php echo esc_url( $page['url'] ); ?>">
							<?php echo wp_get_attachment_image( $page_image_id, 'physio-qt-featured-s', false, array( 'alt' => esc_attr( $page_image_alt ), 'srcset' => esc_html( $page['srcset'] ), 'sizes' => '(min-width: 781px) 360px, calc(100vw - 30px)' ) ); ?>
							<div class="featured-page--overlay">
								<?php if( $instance['btn_text'] ) : ?>
									<div class="overlay--center">
										<span><?php echo esc_html( $instance['btn_text'] ); ?></span>
									</div>
								<?php endif; ?>
							</div>
						</a>
					</div>
				<?php endif; ?>
				<div class="featured-page--content">
					<h4 class="featued-page--title">
						<a href="<?php echo esc_url( $page['url'] ); ?>"><?php echo esc_html( $page['post_title'] ); ?></a>
					</h4>
					<?php if ( $page['post_excerpt'] && $excerpt_length > 0 ) : ?>
						<p><?php echo esc_html( $page['post_excerpt'] ); ?></p>
					<?php endif; ?>
					<?php if( $instance['btn_text'] ) : ?>
						<a href="<?php echo esc_url( $page['url'] ); ?>" class="text-link"><?php echo esc_html( $instance['btn_text'] ); ?></a>
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

			$instance['page_id'] = absint( $new_instance['page_id'] );
			$instance['btn_text'] = sanitize_text_field( $new_instance['btn_text'] );
			$instance['excerpt_length'] = absint( $new_instance['excerpt_length'] );
			
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
			$page_id  = empty( $instance['page_id'] ) ? 0 : (int) $instance['page_id'];
			$btn_text = empty( $instance['btn_text'] ) ? esc_html__( 'Read More', 'physio-qt' ) : $instance['btn_text'];
			$excerpt_length = ! isset( $instance['excerpt_length'] ) ? 180 : $instance['excerpt_length'];
			?>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'page_id' ) ); ?>"><?php esc_html_e( 'Select page', 'physio-qt' ); ?></label><br />
				<?php
					wp_dropdown_pages( array(
						'selected' => $page_id,
						'id'       => $this->get_field_id( 'page_id' ),
						'name'     => $this->get_field_name( 'page_id' ),
					));
				?>
			</p>

			<div class="qt-widget-notice">
				<p>
					<i class="dashicons dashicons-info"></i>
					<strong><?php printf( esc_html__( 'The image and excerpt can be changed by editing the above selected page via the Pages tab. %s Please see %s for a screenshot how-to', 'physio-qt'  ), '<br>', '<a href="'. esc_url( 'https://qreativethemes.ticksy.com/article/5872/' ) .'" target="_blank">this link</a>' ); ?></strong>
				</p>
			</div>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'btn_text' ) ); ?>"><?php esc_html_e( 'Read more text', 'physio-qt' ); ?></label><br />
				<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'btn_text') ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'btn_text' ) ); ?>" value="<?php echo esc_attr( $btn_text ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>"><?php esc_html_e( 'Excerpt length', 'physio-qt' ); ?></label>
				<em><?php esc_html_e( '(180 = default | hide excerpt = 0)', 'physio-qt' ); ?></em><br />
				<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_length') ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>" value="<?php echo esc_attr( $excerpt_length ); ?>" />
			</p>

			<?php
		}
	}
}