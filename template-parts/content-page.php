<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package hatchet
 */

?>

<section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<article>
		<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'hatchet' ),
					'after'  => '</div>',
				)
			);
		?>
	</article><!-- .entry-content -->
</section><!-- #post-<?php the_ID(); ?> -->
