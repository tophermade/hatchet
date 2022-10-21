<?php
/**
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package hatchet
 * 
 * Template Name: Home Page
 */

get_header();
?>

<main>
	<?php the_content();?>
</main><!-- #main -->

<?php
get_footer();