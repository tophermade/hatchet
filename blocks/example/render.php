<?php

/**
 * Block Template.
 *
 */

// uses folder name for block. alternatively use a string for the name
$blockName = basename(__DIR__); 

// Create id attribute allowing for custom "anchor" value.
$id = 'builder-' . $blockName . '-' . $block['id'];
if( !empty($block['anchor']) ) {
	$id = $block['anchor'];
}

add_action('acf/init', 'setup_block');
// Load values and assign defaults.

?>

<section id="<?php echo esc_attr($id); ?>" class="builder-<?php echo esc_attr($blockName); ?>">
	<div class="inner">
		<?php the_field('content'); ?>
	</div>
</section>