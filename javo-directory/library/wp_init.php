<?php
	add_action("init", "setPostType");
	function setPostType(){

		global
			$javo_tso
			, $wp_taxonomies;

		add_theme_support( 'post-thumbnails' ); // Featuered Image
		add_theme_support( 'woocommerce' ); // Woocommerce

		// Image size define
		add_image_size( "javo-tiny"				, 80, 80, true );     // for img on widget
		add_image_size( "javo-avatar"			, 250, 250, true);  // User Picture size
		add_image_size( "javo-box"				, 288, 266, true );   // for blog
		add_image_size( "javo-map-thumbnail"	, 150, 165, true); // Map thumbnail size
		add_image_size( "javo-box-v"			, 400, 219, true );  // for long width blog
		add_image_size( "javo-large"			, 500, 400, true );  // extra large
		add_image_size( "javo-huge"				, 720, 367, true );  // the bigest blog
		add_image_size( "javo-item-detail"		, 823, 420, true );  // type2 detail page
		set_post_thumbnail_size( 132, 133, true );

		$labels = array(
			'name'               => __('Items', 'javo_fr'),
			'singular_name'      => __('Item', 'javo_fr'),
			'add_new'            => __('Add New', 'javo_fr'),
			'add_new_item'       => __('Add New Item', 'javo_fr'),
			'edit_item'          => __('Edit Item', 'javo_fr'),
			'new_item'           => __('New Item', 'javo_fr'),
			'all_items'          => __('All Items', 'javo_fr'),
			'view_item'          => __('View Item', 'javo_fr'),
			'search_items'       => __('Search Items', 'javo_fr'),
			'not_found'          => __('No Items Found', 'javo_fr'),
			'not_found_in_trash' => __('No Items Found in Trash', 'javo_fr'),
			'parent_item_colon'  => '',
			'menu_name'          => __('Item', 'javo_fr')
		);

		$javo_item_slug = $javo_tso->get('item_slug', 'item');

		// item
		register_post_type('item', Array(
			'public'				=> true,
			'menu_icon'				=> '',
			'labels'				=> $labels,
			'supports'				=> Array(
				'title', 'editor', 'thumbnail',
			), 'rewrite'			=> array('slug' => $javo_item_slug )
			, 'query_var'			=> true
			, 'taxonomies'			=> Array( 'post_tag')
		));
			// item > Category
			register_taxonomy('item_category', 'item', Array(
				'label'				=> __( 'Item Category', "javo_fr" )
				, 'labels'			=> Array(
					'menu_name'		=> __('Category', 'javo_fr')
				)
				, 'rewrite'			=> array( 'slug' => $javo_item_slug.'_category' )
				, 'hierarchical'	=> true
			));
			// item > location
			register_taxonomy('item_location', 'item', Array(
				'label'				=> __( 'Item Location', "javo_fr" )
				, 'labels'			=> Array(
					'menu_name'		=> __('Location', 'javo_fr')
				)
				, 'rewrite'			=> array( 'slug' => $javo_item_slug.'_location' ),
				'hierarchical'		=> true,
			));

			// Custom label for Post Tags
			$wp_taxonomies['post_tag']->labels = (object) Array(
				"name"							=> __('Keywords', 'javo_fr')
				, "singular_name"				=> __('Keyword', 'javo_fr')
				, "search_items"				=> __('Search Keywords', 'javo_fr')
				, "popular_items"				=> __('Popular Keywords', 'javo_fr')
				, "all_items"					=> __('All Keywords', 'javo_fr')
				, "parent_item"					=> NULL
				, "parent_item_colon"			=> NULL
				, "edit_item"					=> __('Edit Keyword', 'javo_fr')
				, "view_item"					=> __('View Keyword', 'javo_fr')
				, "update_item"					=> __('Update Keyword', 'javo_fr')
				, "add_new_item"				=> __('Add New Keyword', 'javo_fr')
				, "new_item_name"				=> __('New Keyword Name', 'javo_fr')
				, "separate_items_with_commas"	=> __('Separate keywords with commas', 'javo_fr')
				, "add_or_remove_items"			=> __('Add or remove keywords', 'javo_fr')
				, "choose_from_most_used"		=> __('Chose from the most used keywords', 'javo_fr')
				, "not_found"					=> __('No keywords found', 'javo_fr')
				, "menu_name"					=> __('Keywords', 'javo_fr')
				, "name_admin_bar"				=> "post_tag"
			);

		// claim
		register_post_type("jv_claim", Array(
			"public"					=> true,
			'menu_icon'					=> '',
			"label"						=> __('Claim', 'javo_fr'),
			'supports'					=> Array(
				'title', 'editor', 'thumbnail',
			),'capabilities'		=> array(
				'create_posts'		=> false
			), 'map_meta_cap'		=> true
			, 'show_in_nav_menus'	=> false
		));

		// Events
		register_post_type("jv_events", Array(
			'name'						=> __('Events', 'javo_fr')
			, 'singular_name'			=> __('event', 'javo_fr')
			, 'add_new'					=> __('Add New', 'javo_fr')
			, 'add_new_item'			=> __('Add New Event', 'javo_fr')
			, 'edit_item'				=> __('Edit Event', 'javo_fr')
			, 'new_item'				=> __('New Event', 'javo_fr')
			, 'all_items'				=> __('All Events', 'javo_fr')
			, 'view_item'				=> __('View Event', 'javo_fr')
			, 'search_items'			=> __('Search Events', 'javo_fr')
			, 'not_found'				=> __('No events found', 'javo_fr')
			, 'not_found_in_trash'		=> __('No events found in Trash', 'javo_fr')
			, 'parent_item_colon'		=> ''
			, 'menu_name'				=> __('Events', 'javo_fr')
			, "public"					=> true
			, 'menu_icon'				=> ''
			, "label"					=> __('Events', 'javo_fr')
			, 'supports'				=> Array(
				'title', 'editor', 'thumbnail',
			)
			, 'show_in_nav_menus'		=> false

		));
			// Events > Categories
			register_taxonomy('jv_events_category', 'jv_events', Array(
				'label'					=> __( 'Event Category', "javo_fr" )
				, 'rewrite'				=> array( 'slug' => 'jv_events_categpry' )
				, 'hierarchical'		=> true
				, 'labels'				=> Array(
					'menu_name'			=> __('Category', 'javo_fr')
				)
			));

		// Testimonials
		register_post_type("jv_testimonials", Array(
			'name'               => __('testimonial', 'javo_fr'),
			'singular_name'      => __('testimonials', 'javo_fr'),
			'add_new'            => __('Add New', 'javo_fr'),
			'add_new_item'       => __('Add New Testimonial', 'javo_fr'),
			'edit_item'          => __('Edit Testimonial', 'javo_fr'),
			'new_item'           => __('New Testimonial', 'javo_fr'),
			'all_items'          => __('All Testimonial', 'javo_fr'),
			'view_item'          => __('View Testimonial', 'javo_fr'),
			'search_items'       => __('Search Testimonial', 'javo_fr'),
			'not_found'          => __('No Testimonial found', 'javo_fr'),
			'not_found_in_trash' => __('No Testimonial found in Trash', 'javo_fr'),
			'parent_item_colon'  => '',
			'menu_name'          => __('Testimonial', 'javo_fr'),
			"public"=> true,
			'menu_icon' => '',
			"label"=> __('Testimonial', 'javo_fr'),
			'supports' => Array(
				'title', 'editor', 'thumbnail',
			)
		));
			// Testimonials > Categories
			register_taxonomy('jv_testimonials_category', 'jv_testimonials', Array(
				'label'					=> __( 'Testimonial Category', "javo_fr" )
				, 'rewrite'				=> array( 'slug' => 'jv_testimonials_categpry' )
				, 'hierarchical'		=> true
				, 'labels'				=> Array(
					'menu_name'			=> __('Category', 'javo_fr')
				)
			));

		// Team
		register_post_type("jv_team", Array(
			'name'               => __('Team', 'javo_fr'),
			'singular_name'      => __('Teams', 'javo_fr'),
			'add_new'            => __('Add New', 'javo_fr'),
			'add_new_item'       => __('Add New Team', 'javo_fr'),
			'edit_item'          => __('Edit Team', 'javo_fr'),
			'new_item'           => __('New Team', 'javo_fr'),
			'all_items'          => __('All Team', 'javo_fr'),
			'view_item'          => __('View Team', 'javo_fr'),
			'search_items'       => __('Search Team', 'javo_fr'),
			'not_found'          => __('No Team found', 'javo_fr'),
			'not_found_in_trash' => __('No Team found in Trash', 'javo_fr'),
			'parent_item_colon'  => '',
			'menu_name'          => __('Team', 'javo_fr'),
			"public"=> true,
			'menu_icon' => '',
			"label"=> __('Team', 'javo_fr'),
			'supports' => Array(
				'title', 'editor', 'thumbnail',
			)
		));

			// Team > Categories
			register_taxonomy('jv_team_category', 'jv_team', Array(
				'label'					=> __( 'Team Category', "javo_fr" )
				, 'rewrite'				=> array( 'slug' => 'jv_team_category' )
				, 'hierarchical'		=> true
				, 'labels'				=> Array(
						'menu_name'		=> __('Category', 'javo_fr')
					)
			));

		// Partners
		register_post_type("jv_partners", Array(
			'name'               => __('Partners', 'javo_fr'),
			'singular_name'      => __('Partner', 'javo_fr'),
			'add_new'            => __('Add New', 'javo_fr'),
			'add_new_item'       => __('Add New Partner', 'javo_fr'),
			'edit_item'          => __('Edit Partner', 'javo_fr'),
			'new_item'           => __('New Partner', 'javo_fr'),
			'all_items'          => __('All Partner', 'javo_fr'),
			'view_item'          => __('View Partner', 'javo_fr'),
			'search_items'       => __('Search Partner', 'javo_fr'),
			'not_found'          => __('No Partner found', 'javo_fr'),
			'not_found_in_trash' => __('No Partner found in Trash', 'javo_fr'),
			'parent_item_colon'  => '',
			'menu_name'          => __('Partner', 'javo_fr'),
			"public"=> true,
			'menu_icon' => '',
			"label"=> __('Partner', 'javo_fr'),
			'supports' => Array(
				'title', 'thumbnail',
			)
		));

			// Partners > Categories
			register_taxonomy('jv_partners_category', 'jv_partners', Array(
				'label'				=> __( 'Partner Category', "javo_fr" )
				, 'rewrite'			=> array( 'slug' => 'jv_partners_category' )
				, 'hierarchical'	=> true
				, 'labels'			=> Array(
					'menu_name'		=> __('Category', 'javo_fr')
				)
			));


		// faqs
		register_post_type("jv_faqs", Array(
			'name'               => __('FAQs', 'javo_fr'),
			'singular_name'      => __('FAQ', 'javo_fr'),
			'add_new'            => __('Add New', 'javo_fr'),
			'add_new_item'       => __('Add New FAQ', 'javo_fr'),
			'edit_item'          => __('Edit FAQ', 'javo_fr'),
			'new_item'           => __('New FAQ', 'javo_fr'),
			'all_items'          => __('All FAQ', 'javo_fr'),
			'view_item'          => __('View FAQ', 'javo_fr'),
			'search_items'       => __('Search FAQ', 'javo_fr'),
			'not_found'          => __('No FAQ found', 'javo_fr'),
			'not_found_in_trash' => __('No FAQ found in Trash', 'javo_fr'),
			'parent_item_colon'  => '',
			'menu_name'          => __('FAQs', 'javo_fr'),
			"public"=> true,
			'menu_icon' => '',
			"label"=> __('FAQs', 'javo_fr'),
			'supports' => Array(
				'title', 'editor', 'thumbnail',
			)
		));

			// Faq > Categories
			register_taxonomy('jv_faq_category', 'jv_faqs', Array(
				'label'					=> __( 'FaQs Category', "javo_fr" )
				, 'rewrite'				=> array( 'slug' => 'jv_faq_category' )
				, 'hierarchical'		=> true
				, 'labels'				=> Array(
					'menu_name'		=> __('Category', 'javo_fr')
				)
			));
	};

	/**
	 * Register Navigation Menus
	 */

	if ( ! function_exists( 'javo_nav_menus' ) ) :
		// Register wp_nav_menus
		function javo_nav_menus()
		{
			register_nav_menus( array(
				'primary' => __( 'Primary', 'javo_fr'),
				'footer_menu' => __( 'Footer Menu', 'javo_fr'),
				'canvas_menu' => __('Canvas Menu','javo_fr')
			) );
		}
		add_action( 'init', 'javo_nav_menus' );
	endif;

	add_filter( 'login_redirect', 'javo_login_redirect_callback', 10, 3);

	function javo_login_redirect_callback($orgin, $req, $user){
		global $javo_tso;

		if( empty( $user ) || is_wp_error( $user ) ){ return; }
		$javo_redirect = home_url( JAVO_DEF_LANG . JAVO_MEMBER_SLUG.'/'.$user->user_login );

		switch( $javo_tso->get('login_redirect', '') )
		{

			// Go to the Main Page
			case 'home'		: $javo_redirect = home_url(); break;

			// Everything no working
			case 'current'	:

				// This page is WP Login Page ?
				if(
					isset( $_POST['log'] ) &&
					isset( $_POST['pwd'] ) &&
					isset( $_POST['wp-submit'] )
				) {
					$javo_redirect = admin_url();
				}else{
					$javo_redirect = $req;
				}

			break;
			// Go to the Profile Page
			case 'admin'	: $javo_redirect = admin_url(); break;
		}
		return $javo_redirect;
	};

add_action( 'widgets_init', 'javo_register_sidebars' );

function javo_register_sidebars()
{
	$javo_sidebars = Array(
		Array(
			'name'				=> __( 'Default Sidebar', 'javo_fr' ),
			'id'				=> 'sidebar-1',
			'description'		=> __( 'Widgets in this area will be shown on the default pages.', 'javo_fr' ),
			'before_widget'		=> '<div class="row widgets-wraps">',
			'after_widget'		=> '</div>',
			'before_title'		=> '<div class="widgettitle_wrap col-md-12"><h2 class="widgettitle"><span>',
			'after_title'		=> '</span></h2></div>',
		)
		, Array(
			'name'				=> __( 'item Sidebar', 'javo_fr' ),
			'id'				=> 'sidebar-2',
			'description'		=> __( 'Widgets in this area will be shown on the item pages.', 'javo_fr' ),
			'before_widget'		=> '<div class="row widgets-wraps">',
			'after_widget'		=> '</div>',
			'before_title'		=> '<div class="widgettitle_wrap col-md-12"><h2 class="widgettitle"><span>',
			'after_title'		=> '</span></h2></div>',
		)
		, Array(
			'name'				=> __( 'Item Single Sidebar', 'javo_fr' )
			, 'id'				=> 'sidebar-item'
			, 'description'		=> __( 'Widgets in this area will be shown on the item pages.', 'javo_fr' )
			, 'before_widget'	=> '<div class="row widgets-wraps">'
			, 'after_widget'	=> '</div>'
			, 'before_title'	=> '<div class="widgettitle_wrap col-md-12"><h2 class="widgettitle"><span>'
			, 'after_title'		=> '</span></h2></div>'
		)
		, Array(
			'name'				=> __( 'Blog Sidebar', 'javo_fr' ),
			'id'				=> 'sidebar-3',
			'description'		=> __( 'Widgets in this area will be shown on the blog pages.', 'javo_fr' ),
			'before_widget'		=> '<div class="row widgets-wraps">',
			'after_widget'		=> '</div>',
			'before_title'		=> '<div class="widgettitle_wrap col-md-12"><h2 class="widgettitle"><span>',
			'after_title'		=> '</span></h2></div>',
		)
		, Array(
			'name'				=> __( 'Footer Sidebar1', 'javo_fr' ),
			'id'				=> 'sidebar-foot1',
			'description'		=> __( 'Widgets in this area will be shown on the footer side.', 'javo_fr' ),
			'before_widget'		=> '<div class="row widgets-wraps">',
			'after_widget'		=> '</div>',
			'before_title'		=> '<div class="widgettitle_wrap col-md-12"><h2 class="widgettitle"><span>',
			'after_title'		=> '</span></h2></div>',
		)
		, Array(
			'name'				=> __( 'Footer Sidebar2', 'javo_fr' ),
			'id'				=> 'sidebar-foot2',
			'description'		=> __( 'Widgets in this area will be shown on the footer side.', 'javo_fr' ),
			'before_widget'		=> '<div class="row widgets-wraps">',
			'after_widget'		=> '</div>',
			'before_title'		=> '<div class="widgettitle_wrap col-md-12"><h2 class="widgettitle"><span>',
			'after_title'		=> '</span></h2></div>',
		)
		, Array(
			'name'				=> __( 'Footer Sidebar3', 'javo_fr' ),
			'id'				=> 'sidebar-foot3',
			'description'		=> __( 'Widgets in this area will be shown on the footer side.', 'javo_fr' ),
			'before_widget'		=> '<div class="row widgets-wraps">',
			'after_widget'		=> '</div>',
			'before_title'		=> '<div class="widgettitle_wrap col-md-12"><h2 class="widgettitle"><span>',
			'after_title'		=> '</span></h2></div>',
		)
		, Array(
			'name'				=> __( 'Footer Sidebar4', 'javo_fr' ),
			'id'				=> 'sidebar-foot4',
			'description'		=> __( 'Widgets in this area will be shown on the footer side.', 'javo_fr' ),
			'before_widget'		=> '<div class="row widgets-wraps">',
			'after_widget'		=> '</div>',
			'before_title'		=> '<div class="widgettitle_wrap col-md-12"><h2 class="widgettitle"><span>',
			'after_title'		=> '</span></h2></div>',
		)
		, Array(
			'name'				=> 'Footer Level 1 - Widget 1',
			'id'				=> 'footer-level1-1',
			'before_widget'		=> '<div id="%1$s" class="widgets clearfix %2$s">',
			'after_widget'		=> '</div>',
			'before_title'		=> '<h5>',
			'after_title'		=> '</h5>',
		)
		, Array(
			'name'				=> 'Menu widget1',
			'id'				=> 'menu-widget-1',
			'before_widget'		=> '<div id="%1$s" class="widgets clearfix %2$s">',
			'after_widget'		=> '</div>',
			'before_title'		=> '<h5>',
			'after_title'		=> '</h5>',
		)
		, array(
			'name'				=> 'Canvas Menu',
			'id'				=> 'canvas-menu-widget',
			'before_widget'		=> '<div id="%1$s" class="widgets clearfix %2$s">',
			'after_widget'		=> '</div>',
			'before_title'		=> '<h5>',
			'after_title'		=> '</h5>',
		)
	);

	$javo_sidebars = apply_filters( 'javo_default_sidebars', $javo_sidebars );
	if( !empty( $javo_sidebars ) ) {
		foreach( $javo_sidebars as $sidebar )
			register_sidebar( $sidebar );
	}

}


// Set up the content width value based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 625;


function javo_drt_setup() {
	/*
	 * Makes Javo Themes available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Javo Themes, use a find and replace
	 * to change 'javo_fr' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'javo_fr', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	//add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );
}
add_action( 'after_setup_theme', 'javo_drt_setup' );

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since Javo Themes 1.0
 *
 * @return void
 */
function javo_drt_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Adds JavaScript for handling the navigation menu hide-and-show behavior.
	wp_enqueue_script( 'javothemes-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '1.0', true );

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'javothemes-ie', get_template_directory_uri() . '/css/ie.css', array( 'javothemes-style' ), '20121010' );
	$wp_styles->add_data( 'javothemes-ie', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'javo_drt_scripts_styles' );

/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since Javo Themes 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function javo_drt_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'javo_fr' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'javo_drt_wp_title', 10, 2 );

/**
 * Filter the page menu arguments.
 *
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Javo Themes 1.0
 */
function javo_drt_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'javo_drt_page_menu_args' );



if ( ! function_exists( 'javo_drt_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Javo Themes 1.0
 */
function javo_drt_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'javo_fr' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<i class="glyphicon glyphicon-chevron-left"></i> Older posts', 'javo_fr' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <i class="glyphicon glyphicon-chevron-right"></i>', 'javo_fr' ) ); ?></div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	<?php endif;
}
endif;

if ( ! function_exists( 'javo_drt_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own javo_drt_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Javo Themes 1.0
 *
 * @return void
 */
function javo_drt_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'javo_fr' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'javo_fr' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-inner-wrap">
				<header class="comment-meta comment-author vcard">
					<?php
						echo get_avatar( $comment, 44 );
						printf(
							'<div class="comment-title-wrap"><cite class="pull-left" ><b class="fn">%s</b> <small>%s</small></cite><div class="pull-right">%s</div></div>'
							, get_comment_author_link()
							, sprintf(
									__( '%s at %s', 'javo_fr' )
									, get_comment_date()
									, get_comment_time()
							)
							, sprintf(
									__( "<a href=\"%s\">Edit</a>", 'javo_fr' )
									, get_edit_comment_link( get_comment_ID() )
							)
						);
					?>
					<section class="comment-content comment">
					<?php comment_text(); ?>

					</section><!-- .comment-content -->
				</header><!-- .comment-meta -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'javo_fr' ); ?></p>
				<?php endif; ?>


			</div><!--comment-inner-wrap-->
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'javo_fr' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

if ( ! function_exists( 'javo_drt_entry_meta' ) ) :
/**
 * Set up post entry meta.
 *
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own javo_drt_entry_meta() to override in a child theme.
 *
 * @since Javo Themes 1.0
 *
 * @return void
 */
function javo_drt_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'javo_fr' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'javo_fr' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf('%s %s', 'javo_fr' ), __('View all posts by', 'javo_fr'), get_the_author() ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = sprintf('%s : %%1$s  %s: %%2$s %s : %%3$s<span class="by-author"> %s %%4$s</span>.', __('Category', 'javo_fr'), __('Tags', 'javo_fr'), __('Date', 'javo_fr'), __('by', 'javo_fr'));
	} elseif ( $categories_list ) {
		$utility_text = sprintf('%s %%1$s %s : %%3$s<span class="by-author"> %s %%4$s</span>.', __('Category', 'javo_fr'), __('Date', 'javo_fr'), __('by', 'javo_fr'));
	} else {
		$utility_text = sprintf('%s : %%3$s<span class="by-author"> %s %%4$s</span>.', __('Date', 'javo_fr'), __('by', 'javo_fr'));
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

/**
* Background Color or image setting.
* Since Javo Theme 1.0
**/

$defaults = array(
	'default-color'          => '',
	'default-image'          => '',
	'default-repeat'         => '',
	'default-position-x'     => '',
	'wp-head-callback'       => '_custom_background_cb',
	'admin-head-callback'    => '',
	'admin-preview-callback' => ''
);
add_theme_support( 'custom-background', $defaults );

/**
 * Extend the default WordPress body classes.
 *
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since Javo Themes 1.0
 *
 * @param array $classes Existing class values.
 * @return array Filtered class values.
 */
function javo_drt_body_class( $classes ) {

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'templates/full-width.php' ) )
		$classes[] = 'full-width';

	if ( is_page_template( 'templates/front-page.php' ) ) {
		$classes[] = 'template-front-page';
		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
		if ( is_active_sidebar( 'sidebar-2' ) && is_active_sidebar( 'sidebar-3' ) )
			$classes[] = 'two-sidebars';
	}

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'javothemes-fonts', 'queue' ) )
		$classes[] = 'custom-font-enabled';

	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'javo_drt_body_class' );

/**
 * Adjust content width in certain contexts.
 *
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Javo Themes 1.0
 *
 * @return void
 */
function javo_drt_content_width() {
	if ( is_page_template( 'templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'javo_drt_content_width' );

/**
 * Register postMessage support.
 *
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Javo Themes 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 * @return void
 */
function javo_drt_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'javo_drt_customize_register' );

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since Javo Themes 1.0
 *
 * @return void
 */
 add_action( 'customize_preview_init', 'javo_drt_customize_preview_js' );
function javo_drt_customize_preview_js() {
	wp_enqueue_script( 'javothemes-customizer', get_template_directory_uri() . '/assets/js/theme-customizer.js', array( 'customize-preview' ), '20130301', true );
}



// Recommendation for permalink
if ( '' == get_option( 'permalink_structure' ) ) {
    function wpse157069_permalink_warning() {
        echo '<div id="permalink_warning" class="error">';
		_e('<p>We strongly recommend adding a <a href="' . esc_url( admin_url( 'options-permalink.php' ) ) . '">permalink structure (Post name)</a> to your site when using Javo Themes.</p>', 'javo_fr');
        echo '</div>';
    }
    add_action( 'admin_footer', 'wpse157069_permalink_warning' );
}

//restrict authors to only being able to view media that they've uploaded
function ik_eyes_only( $wp_query ) {
	//are we looking at the Media Library or the Posts list?
	if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/upload.php' ) !== false
	|| strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/edit.php' ) !== false ) {
		//user level 5 converts to Editor
		if ( !current_user_can( 'level_5' ) ) {
			//restrict the query to current user
			global $current_user;
			$wp_query->set( 'author', $current_user->id );
		}
	}
}

//filter media library & posts list for authors
add_filter('parse_query', 'ik_eyes_only' );

add_action('init', 'javo_adminbar_onoff_callback');
function javo_adminbar_onoff_callback(){
	global $javo_tso;
	if(
		$javo_tso->get('adminbar_hidden', '') == 'use' &&
		!current_user_can('administrator')
	){
		show_admin_bar(false);
	}
}