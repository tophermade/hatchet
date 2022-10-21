<?php

/**
 * Block ACF Setup.
 *
 */
// setup block for ACF use
add_action('acf/init', 
	function () {
		// uses folder name for block by default. 
		// alternatively you may use a string for the name
		$blockName = "" . basename(__DIR__); 
		
		// check function exists
		if( function_exists('acf_register_block') ) {
			// register block
			acf_register_block(array(
				'name'				=> $blockName . '-item',
				'title'				=> __($blockName . ''),
				'description'		=> __('Custom ' . $blockName . ' block.'),
				'render_template'	=> 'blocks/' . $blockName . '/render.php',
				'category'			=> 'layout',
				'icon'				=> 'excerpt-view',
				'keywords'			=> array( $blockName, "chop" ),
			));
		}
		
		acf_add_local_field_group(array(
			'key' => $blockName . '-group',
			'title' => $blockName . ' Group',
			'fields' => array (),
			'location' => array (
				array (
					array (
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/'. strtolower($blockName) .'-item',
					),
				),
			),
		));

		acf_add_local_field(array(
			'key' => 'content',
			'label' => 'Content',
			'name' => 'content',
			'type' => 'wysiwyg',
			'parent' => $blockName . '-group',
		));
	}
);