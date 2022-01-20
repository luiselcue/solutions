<?php
/**
 * The template for displaying Hero Hover Slider.
 *
 * @package     Solutions
 * @author      QE <luiselcue@gmail.com>
 * @since       1.0.0
 */

$sinatra_hero_categories = ! empty( $sinatra_hero_categories ) ? implode( ', ', $sinatra_hero_categories ) : '';


$sinatra_hero_categories = sinatra_option( 'hero_hover_slider_category' );

$featured_posts = get_field('hero_slider_items');

if ( !$featured_posts ) {
	return;
}

$posts_per_page = sinatra_option( 'hero_hover_slider_post_number' );

$first_x_featured_posts = array_slice($featured_posts, 0, $posts_per_page);

$sinatra_hero_bgs_html   = '';
$sinatra_hero_items_html = '';

$sinatra_hero_elements = (array) sinatra_option( 'hero_hover_slider_elements' );
$sinatra_hero_readmore = isset( $sinatra_hero_elements['read_more'] ) && $sinatra_hero_elements['read_more'] ? ' si-hero-readmore' : '';

foreach( $first_x_featured_posts as $post ):
	setup_postdata($post);


	// Background images HTML markup.
	$sinatra_hero_bgs_html .= '<div class="hover-slide-bg" data-background="' . get_the_post_thumbnail_url( get_the_ID(), 'full' ) . '"></div>';

	// Post items HTML markup.
	ob_start();
	?>
	<div class="col-xs-<?php echo esc_attr( 12 / $posts_per_page ); ?> hover-slider-item-wrapper<?php echo esc_attr( $sinatra_hero_readmore ); ?>">
		<div class="hover-slide-item">
			<div class="slide-inner">
				<?php if ( isset( $sinatra_hero_elements['category'] ) && $sinatra_hero_elements['category'] ) { ?>
					<div class="post-category">
						<?php sinatra_entry_meta_category( ' ', false ); ?>
						<?php
							$terms = get_the_terms( $post->ID, 'programs' );
							if ($terms) :
								foreach($terms as $index => $term) : ?>

									<a href="<?php echo  get_term_link( $term ); ?>">
										<?php echo $term->name;?>
									</a>

								<?php endforeach ?>
							<?php endif ?>
					</div>
				<?php } ?>

				<?php if ( get_the_title() ) { ?>
					<h3><a href="<?php echo esc_url( sinatra_entry_get_permalink() ); ?>"><?php the_title(); ?></a></h3>
				<?php } ?>

				<?php if ( isset( $sinatra_hero_elements['meta'] ) && $sinatra_hero_elements['meta'] ) { ?>
					<div class="entry-meta">
						<div class="entry-meta-elements">
							<?php
							sinatra_entry_meta_author();

							sinatra_entry_meta_date(
								array(
									'show_modified'   => false,
									'published_label' => '',
								)
							);
							?>
						</div>
					</div><!-- END .entry-meta -->
				<?php } ?>

				<?php if ( $sinatra_hero_readmore ) { ?>
					<?php the_excerpt(); ?>
					<a href="<?php echo esc_url( sinatra_entry_get_permalink() ); ?>" class="read-more si-btn btn-small btn-outline btn-uppercase" role="button"><span><?php esc_html_e( 'Continue Reading', 'sinatra' ); ?></span></a>
				<?php } ?>

			</div><!-- END .slide-inner -->
		</div><!-- END .hover-slide-item -->
	</div><!-- END .hover-slider-item-wrapper -->
	<?php
	$sinatra_hero_items_html .= ob_get_clean();
endforeach;

// Restore original Post Data.
wp_reset_postdata();

// Hero container.
$sinatra_hero_container = sinatra_option( 'hero_hover_slider_container' );
$sinatra_hero_container = 'full-width' === $sinatra_hero_container ? 'si-container si-container__wide' : 'si-container';

// Hero overlay.
$sinatra_hero_overlay = absint( sinatra_option( 'hero_hover_slider_overlay' ) );
?>

<div class="si-hover-slider slider-overlay-<?php echo esc_attr( $sinatra_hero_overlay ); ?>">
	<div class="hover-slider-backgrounds">

		<?php echo wp_kses_post( $sinatra_hero_bgs_html ); ?>

	</div><!-- END .hover-slider-items -->

	<div class="si-hero-container <?php echo esc_attr( $sinatra_hero_container ); ?>">
		<div class="si-flex-row hover-slider-items">

			<?php echo wp_kses_post( $sinatra_hero_items_html ); ?>

		</div><!-- END .hover-slider-items -->
	</div>

	<div class="si-spinner visible">
		<div></div>
		<div></div>
	</div>
</div><!-- END .si-hover-slider -->
