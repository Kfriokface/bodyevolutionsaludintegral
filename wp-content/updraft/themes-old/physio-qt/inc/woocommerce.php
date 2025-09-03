<?php
/**
 * WooCommerce Integration
 *
 * @package physio-qt
 */

/**
 * Remove the default WooCommerce wrappers
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Adding custom wrappers
 */
add_action( 'woocommerce_before_main_content', 'physio_qt_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'physio_qt_wrapper_end', 10 );

function physio_qt_wrapper_start() {
	$physio_sidebar = physio_qt_single_product_sidebar();
	get_template_part( 'template-parts/page-header' );

	$physio_get_the_id  = absint( get_option( 'woocommerce_shop_page_id' ) );

	// Set the sidebar pull to no pull
	$physio_sidebar_pull = get_field( 'pull_sidebar', $physio_get_the_id );
	if ( ! $physio_sidebar_pull || ( 'hide' === $physio_sidebar && 'pull' === $physio_sidebar_pull ) ) {
		$physio_sidebar_pull = 'no_pull';	
	}
	?>

	<?php if ( 'pull' !== $physio_sidebar_pull && 'hide' !== get_theme_mod( 'breadcrumbs', 'show' ) && 'hide' !== get_field( 'breadcrumbs', $physio_get_the_id ) ) { ?>
		<div class="breadcrumbs">
			<div class="container">
				<?php get_template_part( 'template-parts/breadcrumbs' ); ?>
			</div>
		</div>
	<?php } ?>
		
	<div id="primary" class="content-area">
		<div class="container">
			<div class="row">
				<main class="col-xs-12<?php echo ( is_active_sidebar( 'shop-sidebar' ) && 'left' === $physio_sidebar ) ? ' col-md-9 col-md-push-3' : ''; echo ( is_active_sidebar( 'shop-sidebar' ) && 'right' === $physio_sidebar ) ? ' col-md-9' : '';?>">

					<?php if ( 'no_pull' !== $physio_sidebar_pull && 'hide' !== get_theme_mod( 'breadcrumbs', 'show' ) && 'hide' !== get_field( 'breadcrumbs' ) ) : ?>

						<div class="breadcrumbs">
							<?php get_template_part( 'template-parts/breadcrumbs' ); ?>
						</div>

					<?php endif; ?>

					<?php
					}
					function physio_qt_wrapper_end() {
						$physio_get_the_id  = absint( get_option( 'woocommerce_shop_page_id' ) );
						$physio_sidebar_pull = get_field( 'pull_sidebar', $physio_get_the_id );
						$physio_sidebar = physio_qt_single_product_sidebar(); 
					?>
				</main>

				<?php if ( 'hide' != $physio_sidebar && is_active_sidebar( 'shop-sidebar' ) ) : ?>
					<div class="col-xs-12 col-md-3<?php echo 'left' === $physio_sidebar ? ' col-md-pull-9' : ''; ?>">
						<aside class="sidebar<?php echo 'pull' === $physio_sidebar_pull ? ' pull--sidebar' : ''; ?>">
							<?php dynamic_sidebar( 'shop-sidebar' ); ?>
						</aside>
					</div>
				<?php endif; ?>

				</div>
			</div>
		</div>
	</div>
<?php
}

/**
 * Show the page title in the main title area
 */
add_filter( 'woocommerce_show_page_title', '__return_false' );

/**
 * Set our own sidebar option
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Use own breadcrumbs
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/**
 * Change number or products per row to 3
 */
if ( !function_exists( 'physio_qt_loop_columns' ) ) {
	function physio_qt_loop_columns() {
		// 4 products per row
		return 4;
	}
	add_filter( 'loop_shop_columns', 'physio_qt_loop_columns' );
}

/**
 * Display number of products per page from the theme customizer
 */
function physio_qt_products_per_page() {
	return get_theme_mod( 'shop_products_per_page', 8 );
}
add_filter( 'loop_shop_per_page', 'physio_qt_products_per_page', 20 );

/**
 * The position of the sidebar for single product and shop base
 */
function physio_qt_single_product_sidebar() {

	$physio_sidebar = '';

	if ( is_product() ) {
		
		// Get the single product sidebar theme mod option
		$physio_sidebar = get_theme_mod( 'single_product_sidebar', 'right' );
	} else {
		
		// Get the sidebar option field for the WooCommerce page
		$physio_sidebar = get_field( 'sidebar', (int) get_option( 'woocommerce_shop_page_id' ) );
		
		// If Sidebar isn't set hide the sidebar
		if( ! $physio_sidebar ) { 
			$physio_sidebar = 'hide';
		}
	}

	// Return the sidebar
	return $physio_sidebar;
}

/**
 * Set the amount of related products shown at the bottom on product pages
 */
function physio_qt_related_products( $args ) {
	// 4 related products
	$args['posts_per_page'] = 4;
	// arranged in columns
	$args['columns'] = 4;

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'physio_qt_related_products' );