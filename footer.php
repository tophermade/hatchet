<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package hatchet
 */

?>

	<footer>
		<p>
			<?php bloginfo("name"); ?> - <?php echo date("Y"); ?> All Rights Reserved
		</p>
	</footer>
<?php wp_footer(); ?>