<?php
/**
 * The template for displaying all single posts
 *
 * This is the template that displays all single posts by default.
 * Please note that this is the WordPress construct of posts
 * and that other 'posts' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package hatchet
 */

get_header();
?>

<main>
	<?php 	
		while ( have_posts() ) :
		the_post(); 
	?>

	<header>
		<h1>
			<?php the_title(); ?>
		</h1>
	</header><!-- e: hero -->
	
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php the_content(); ?>
		
		<?php 
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
		?>
	</article><!-- #post-<?php the_ID(); ?> -->
	<?php 
		endwhile; // End of the loop.
	?>
	
</main><!-- #main -->


<?php
get_footer();
