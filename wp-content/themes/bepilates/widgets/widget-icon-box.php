<?php
/**
 * Widget: Icon Box
 *
 * @package physio-toolkit
 */


class BEP_Icon_Box extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			false,
			esc_html__( 'BEP: Icon Box', 'physio-qt' ),
			array(
				'description' => esc_html__( 'A box with an icon, heading and 2 text lines', 'physio-qt' ),
				'classname'   => 'widget-icon-box',
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

		// Open Link in new tab option
		$link_new_tab = empty( $instance['new_tab'] ) ? true : false;

		if ( ! empty ( $instance['link'] ) ) :
		?>
			<a class="icon-box" href="<?php echo esc_url( $instance['link'] ); ?>" <?php echo empty ( $instance['new_tab'] ) ? '' : 'target="_blank"'; ?>>
		<?php else : ?>
			<div class="icon-box">
		<?php endif; ?>
			<?php if ( $instance['icon'] != '' ) { ?>
				<div class="icon-box--icon">
					<i class="fa <?php echo esc_attr( $instance['icon'] ); ?>"></i>
				</div>
			<?php } ?>
			<div class="icon-box--text">
				<?php if ( $instance['title'] != '' ) { ?>
					<h6 class="icon-box--title"><?php echo wp_kses_post( $instance['title'] ); ?></h6>
				<?php } ?>
				<?php if ( $instance['text'] != '' ) { ?>
					<span class="icon-box--description"><?php echo wp_kses_post( $instance['text'] ); ?></span><br/>
				<?php } ?>
				<?php if ( $instance['text2'] != '' ) { ?>
					<span class="icon-box--description"><?php echo wp_kses_post( $instance['text2'] ); ?></span>
				<?php } ?>
			</div>
		</<?php echo empty ( $instance['link'] ) ? 'div' : 'a'; ?>>

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

		$instance['title']   = wp_kses_post( $new_instance['title'] );
		$instance['text']    = wp_kses_post( $new_instance['text'] );
		$instance['text2']    = wp_kses_post( $new_instance['text2'] );
		$instance['link']    = esc_url( $new_instance['link'] );
		$instance['icon']    = wp_kses_post( $new_instance['icon'] );
		$instance['new_tab'] = ! empty( $new_instance['new_tab'] ) ? sanitize_key( $new_instance['new_tab'] ) : '';

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
		$title  = empty( $instance['title'] ) ? '' : $instance['title'];
		$text   = empty( $instance['text'] ) ? '' : $instance['text'];
		$text2   = empty( $instance['text2'] ) ? '' : $instance['text2'];
		$link   = empty( $instance['link'] ) ? '' : $instance['link'];
		$icon   = empty( $instance['icon'] ) ? '' : $instance['icon'];
		$new_tab = empty( $instance['new_tab'] ) ? '' : $instance['new_tab'];
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'physio-qt' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Text', 'physio-qt' ); ?>:</label> <br />
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" type="text" value="<?php echo esc_attr( $text ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text2' ) ); ?>"><?php esc_html_e( 'Text2', 'physio-qt' ); ?>:</label> <br />
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text2' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text2' ) ); ?>" type="text" value="<?php echo esc_attr( $text2 ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php esc_html_e( 'Link', 'physio-qt' ); ?>: <small><em><?php esc_html_e( 'Optional', 'physio-qt' ); ?></em></small></label> <br>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" type="text" value="<?php echo esc_url( $link ); ?>" />
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $new_tab, 'on'); ?> id="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'new_tab' ) ); ?>" value="on" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>"><?php esc_html_e( 'Open link in new browser tab?', 'physio-qt' ); ?></label>
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


function bep_widget_register() {
    register_widget( 'BEP_Icon_Box' );
}
add_action( 'widgets_init', 'bep_widget_register' );
	
