<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package hatchet
 */

?>
<section >
	<header>
		<h1><?php esc_html_e( 'Nothing Found', 'hatchet' ); ?></h1>
	</header>
	<article>
	<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) :
			printf(
				'<p>' . wp_kses(
					/* translators: 1: link to WP admin new post page. */
					__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'hatchet' ),
					array(
						'a' => array(
							'href' => array(),
						),
					)
				) . '</p>',
				esc_url( admin_url( 'post-new.php' ) )
			);
		elseif ( is_search() ) :
			?>
			
			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'hatchet' ); ?></p>
			<?php
			get_search_form();
		else :
			?>
			
			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'hatchet' ); ?></p>
			<?php
			get_search_form();
		endif;
		?>
	</article>
</section><!-- #post-<?php the_ID(); ?> -->

