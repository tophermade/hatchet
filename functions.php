<?php
/**
 * hatchet functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package hatchet
 */

$envLines = new SPLFileObject(__DIR__ ."/.env");
$_ENV = [];
foreach($envLines as $line) {
	$_ENV[strtok($line, '=')] = substr($line, strpos($line, "=") + 1);
}
$mode = trim($_ENV['MODE']);


if ( ! defined( '_hatchet_VERSION' ) ) {
	// Replace the version number of the theme on each notable release.
	define( '_hatchet_VERSION', '1.0.0' );
}

if ( ! function_exists( 'hatchet_setup' ) ) :
	//
	//Sets up theme defaults and registers support for various WordPress features.
	//
	//Note that this function is hooked into the after_setup_theme hook, which
	//runs before the init hook. The init hook is too late for some features, such
	//as indicating support for post thumbnails.
	//
	
	function hatchet_setup() {

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// 
		// Let WordPress manage the document title.
		// By adding theme support, we declare that this theme does not use a
		// hard-coded <title> tag in the document head, and expect WordPress to
		// provide it for us.
		//  
		add_theme_support( 'title-tag' );

		// 
		// Enable support for Post Thumbnails on posts and pages.
		// https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		// 
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'hatchet' ),
			)
		);

		// 
		// Switch default core markup for search form, comment form, and comments
		// to output valid HTML5.
		// 
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		// custom backgroud exampled commented out below
		// add_theme_support(
		// 	'custom-background',
		// 	apply_filters(
		// 		'hatchet_custom_background_args',
		// 		array(
		// 			'default-color' => 'ffffff',
		// 			'default-image' => '',
		// 		)
		// 	)
		// );
	}
endif;
add_action( 'after_setup_theme', 'hatchet_setup' );



// 
// Register widget area.
// https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
// 
function hatchet_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'hatchet' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'hatchet' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'hatchet_widgets_init' );



//
// Blocks 
//
$dir = new DirectoryIterator(__DIR__ ."/blocks/");
foreach ($dir as $item) {
	if ($item->isDir() && !$item->isDot()) {
		// include_once('blocks/'.$item->getFilename().'/setup.php');
	}
}
include_once('blocks/example/setup.php');


// 
// Default gutenberg content lacks wrappers. add some so we can style and control
//
function wporg_block_wrapper( $block_content, $block ) {
	if ( $block['blockName'] === 'core/paragraph' ) {
		$content = '<section class="wp-block-paragraph"><div class="inner">';
		$content .= $block_content;
		$content .= '</div></section>';
		return $content;
	} elseif ( $block['blockName'] === 'core/heading' ) {
		$content = '<section class="wp-block-heading"><div class="inner">';
		$content .= $block_content;
		$content .= '</div></section>';
		return $content;
	} elseif ( $block['blockName'] === 'core/list' ) {
		$content = '<section class="wp-block-list"><div class="inner">';
		$content .= $block_content;
		$content .= '</div></section>';
		return $content;
	}
	return $block_content;
}

add_filter( 'render_block', 'wporg_block_wrapper', 10, 2 );


function enable_page_excerpt() {
	add_post_type_support('page', array('excerpt'));
}
add_action('init', 'enable_page_excerpt');

// 
// Manual block registration example
//
// add_action('acf/init', 'my_acf_init_block_types');
// function my_acf_init_block_types() {
// 	if( function_exists('acf_register_block_type') ) {
		
// 		// register intro block.
// 		acf_register_block_type(array(
// 			'name'              => 'intro',
// 			'title'             => __('Introduction'),
// 			'description'       => __('Page intriduction block.'),
// 			'render_template'   => 'blocks/example/render.php',
// 			'category'          => 'formatting',
// 			'keywords'          => array( 'intro', 'introduction' ),
// 		));

// 	}
// }



// 
// Enqueue scripts and styles.
// 
function hatchet_enq() {
	$mode = trim($_ENV['MODE']);
	
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), null, true);
	
	// customize your dev env or staging env script and sheet inclusions
	if($mode == "dev"){
		function add_less() {
			echo '<link id="site-less" rel="stylesheet/less" href="'. get_template_directory_uri(). '/src/less/style.less" media="screen" />';
		}
		add_action( 'wp_head', 'add_less' );
		
		function add_less_js() {
			echo '<script>less = { env: "development"};</script><script src="'. get_template_directory_uri().'/lib/less.js"></script><script>less.watch();</script>';
		}
		add_action( 'wp_footer', 'add_less_js' );
		
		// add scripts this theme uses here. in dev they will be loaded directly,
		// in production they'll be loaded in a single compiled file
		wp_enqueue_script( 'script', get_template_directory_uri() . '/src/js/scripts.js', array(), _hatchet_VERSION, true );
	} else if($mode == "mvp") {
		// not dev mode (production or staging)
		// so we load the MVP css file
		echo '<link rel="stylesheet" href="https://unpkg.com/mvp.css@1.12/mvp.css">';
		
		// then the compiled js and css
		wp_enqueue_script( 'hatchet-script', get_template_directory_uri() . '/assets/scripts.js', array(), _hatchet_VERSION, true );
		wp_enqueue_style( 'hatchet-style', get_template_directory_uri() . '/assets/style.css', array(), _hatchet_VERSION );
	} else {
		// not dev mode (production or staging)
		// so we load the compiled js and css
		wp_enqueue_script( 'hatchet-script', get_template_directory_uri() . '/assets/scripts.js', array(), _hatchet_VERSION, true );
		wp_enqueue_style( 'hatchet-style', get_template_directory_uri() . '/assets/style.css', array(), _hatchet_VERSION );
	}	
		
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'hatchet_enq' );


function enqueuing_admin_scripts(){
	wp_enqueue_style('hatchet-editor', get_template_directory_uri().'/assets/editor-style.css');
}
 
add_action( 'admin_enqueue_scripts', 'enqueuing_admin_scripts' );


add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );
function wps_deregister_styles() {
	wp_dequeue_style( 'wp-block-library' );
}


// 
// Dev Functions
// 
// if($mode == 'dev'){ 
// 	this modifies the LESS rel= if a LESS stylesheet is included.
// 	function my_style_loader_tag_filter($html, $handle) {
// 		if ($handle === 'less-style') {
// 			return str_replace("rel='stylesheet'",
// 				"rel='stylesheet/less'", $html);
// 		}
// 		return $html;
// 	}
// 	add_filter('style_loader_tag', 'my_style_loader_tag_filter', 10, 2);

// 	this parses the HTML right before it is sent to the client,
// 	adding the live site's url to image tags as a fallback, 
// 	if that image isn't found on the build. it will also log a 
// 	notice to the browser in the console for each image it 
// 	replaces. this is useful when a massive media library is 
// 	impractical to store locally
// 	this is a heavy load, so uncomment only if necessary
// 	function callback($buffer){
// 		$page = new DOMDocument();
// 		$page->loadHTML($buffer);
// 		$tags = $page->getElementsByTagName('img');
// 		foreach ($tags as $tag) {
// 			$oldSrc = $tag->getAttribute('src');
// 			$liveUrl = preg_replace("/(http|https):\/\/(?:.*?)\/wp-content\//i", "$1://". LIVEDOMAIN ."/wp-content/", $oldSrc);
// 			$tag->setAttribute('onerror', "this.onerror=null; console.log('Replaced a missing local src with a live src: ". $liveUrl ." '); this.src='". $liveUrl ."';");
// 		}
// 		return $page->saveHTML();
// 	}
// 	function buffer_start() { ob_start("callback"); }
// 	function buffer_end() { ob_end_flush(); }
// 	add_action('wp_head', 'buffer_start');
// 	add_action('wp_footer', 'buffer_end');
// }