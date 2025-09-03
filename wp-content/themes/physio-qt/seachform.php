<?php
/**
 * Template for displaying search forms
 *
 * @package physio-qt
 */
?>

<form role="search" method="get" class="search-form" autocomplete="off" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html_e( 'Search for:', 'physio-qt' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php esc_html_e( 'Search...', 'physio-qt' ); ?>" value="" name="s">
	</label>
	<button type="submit" class="search-submit"><span class="screen-reader-text"><?php echo esc_html_e( 'Search', 'physio-qt' ); ?></span></button>
</form>