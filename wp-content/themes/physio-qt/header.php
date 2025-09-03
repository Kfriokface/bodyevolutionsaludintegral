<?php
/**
 * The header for our theme.
 *
 * @package physio-qt
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php
	$featured_button_text = get_theme_mod( 'featured_button_text' );
	$featured_button_url = get_theme_mod( 'featured_button_url' );
	$featured_button_target = get_theme_mod( 'featured_button_target', 'self' );
?>

<div class="layout-boxed">

<header class="header">
	
	<?php if ( 'hide' !== get_theme_mod( 'show_topbar', 'show' ) && 'hide' !== get_field( 'topbar' ) ) : ?>
		<div class="header-topbar<?php echo 'hide_mobile' === get_theme_mod( 'show_topbar', 'show' ) ? ' hidden-xs' : '';?>">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-md-4">
						<div class="header-topbar-sidebar topbar-left">
							<?php if ( is_active_sidebar( 'topbar-sidebar-left' ) ) : ?>
								<?php dynamic_sidebar( 'topbar-sidebar-left' ); ?>
							<?php else : ?>
								<div class="header-description"><?php bloginfo( 'description' ); ?></div>
							<?php endif; ?>
						</div>
					</div>
					<div class="col-xs-12 col-md-8">
						<div class="header-topbar-sidebar topbar-right">
							<?php if ( is_active_sidebar( 'topbar-sidebar' ) ) : ?>
								<?php dynamic_sidebar( 'topbar-sidebar' ); ?>
							<?php elseif ( has_nav_menu( 'top-nav' ) ) : ?>
								<?php
									wp_nav_menu( array(
										'theme_location' => 'top-nav',
										'container'		 => false,
										'menu_class'	 => 'menu',
										'walker'		 => new Aria_Walker_Nav_Menu(),
										'item_wraps'	 => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
									) );
								?>
							<?php endif; ?>
						</div>
					</div>
			    </div>
			</div>
		</div>
	<?php endif; ?>

	<div class="header-wrapper">
		<div class="container">

			<?php if ( 'hide' !== get_theme_mod( 'featured_button_mobile', 'show' ) && get_theme_mod( 'featured_button_text' ) ) : ?>
				<div class="featured-button hidden-md hidden-lg">
					<a href="<?php echo esc_url( $featured_button_url ); ?>" class="btn" target="_<?php echo esc_attr( $featured_button_target ); ?>"><?php echo wp_kses_post( $featured_button_text ); ?></a>
				</div>
			<?php endif; ?>
			
			<div class="header-logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( bloginfo( 'name' ) ); ?>">
					<?php
						$physio_logo = get_theme_mod( 'logo' );
						$physio_retina_logo = get_theme_mod( 'retina_logo' );
						
						if ( ! empty( $physio_logo ) ) : ?>
							<img src="<?php echo esc_url( $physio_logo ); ?>" srcset="<?php echo esc_attr( $physio_logo ); ?><?php echo empty ( $physio_retina_logo ) ? '' : ', ' . esc_url( $physio_retina_logo ) . ' 2x'; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php else : ?>
						<h1><?php bloginfo( 'name' ); ?></h1>
					<?php endif; ?>
				</a>
			</div>

			<div class="header-navigation" aria-label="Main Navigation">

				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="navbar-toggle-icon">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</span>
				</button>

				<nav id="navbar" class="collapse navbar-collapse">
					<?php if ( has_nav_menu( 'primary' ) ) :
						wp_nav_menu( array(
							'theme_location' => 'primary',
							'container'		 => false,
							'menu_class'	 => 'main-navigation',
							'walker'		 => new Aria_Walker_Nav_Menu(),
							'item_wraps'	 => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
						) );
					endif; ?>
				</nav>

				<?php if ( 'hide' !== get_theme_mod( 'featured_button', 'show' ) && get_theme_mod( 'featured_button_text' ) ) : ?>
					<div class="featured-button hidden-xs hidden-sm">
						<a href="<?php echo esc_url( $featured_button_url ); ?>" class="btn" target="_<?php echo esc_attr( $featured_button_target ); ?>"><?php echo wp_kses_post( $featured_button_text ); ?></a>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>

	<?php if ( 'sticky' === get_theme_mod( 'nav_position', 'static' ) ) : ?>
		<div class="sticky-offset"></div>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'header-sidebar' ) && 'hide' !== get_field( 'widget_bar', physio_qt_get_the_ID() ) && 'hide' != get_theme_mod( 'header_widgets' ) ) : ?>
        <div class="header-widgets">
            <div class="container">
                <?php dynamic_sidebar( 'header-sidebar' ); ?>
            </div>
        </div>
    <?php endif; ?>
</header>