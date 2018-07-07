<?php
class javo_vb_shortcodes{
	public function __construct(){
		add_action('init', Array($this, 'javo_vb_shortcodes_callback'), 11);
		add_action('init', Array($this, 'javo_vb_basic_shortcodes_trash_callback'), 11);
		add_filter('javo_get_stc_search_result_pages', Array(__class__, 'javo_get_stc_search_result_pages_callback'));
		add_filter('javo_get_categories_value', Array(__class__, 'javo_get_categories_value_callback'));
		add_filter('javo_get_categories_value_with_child', Array(__class__, 'javo_get_categories_value_with_child_callback'));
		add_filter('javo_get_locations_value_with_child', Array(__class__, 'javo_get_locations_value_with_child_callback'));
		add_filter('javo_get_fetured_item', Array(__class__, 'javo_get_fetured_item_callback'));
		add_filter('javo_get_map_templates', Array(__class__, 'javo_get_map_templates_callback'));
		add_filter('javo_mail_chimp_get_lists', Array( __CLASS__, 'javo_mail_chimp_get_lists_callback') );
	}
	public function javo_vb_shortcodes_callback()
	{
		// Undefined vc_row ?
		if( !function_exists( 'vc_map') ) return;

		// javo-banner
		vc_map(array(
			'base'=> 'javo_banner'
			, 'name'=> __('Javo Banner', 'javo_fr')
		    , 'icon' => 'javo-vc-icon'
			, 'category'=> __('Javo', 'javo_fr')
			, 'description' => __('Create your own banner.', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'attach_image'
					, 'heading'=> __('Select an Image', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'attachment_id'
					, 'value' => ''
				),Array(
					'type'=>'textfield'
					, 'heading'=> __('Link', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'link'
					, 'value' => '#'
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Width', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'width'
					, 'value' => '500'
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Height', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'height'
					, 'value' => '400'
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Border - Weight', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'bdweight'
					, 'value' => '1'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Border - color', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'bdcolor'
					, 'value' => '#efefef'
				)
			)
		));

		// javo_events
		vc_map(array(
			'base'=> 'javo_events'
			, 'name'=> __('Javo Events', 'javo_fr')
		    , 'icon' => 'javo-vc-icon'
			, 'category'=> __('Javo', 'javo_fr')
			, 'description' => __('Display events by categories.', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => ''
				),Array(
					'type'=>'textfield'
					, 'heading'=> __('Sub Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'sub_title'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Sub Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'sub_title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Header Linear Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'line_color'
					, 'value' => '#fff'
				), Array(
					'type'=>'dropdown'
					, 'heading'=> __('Listing Style', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'type'
					, 'value' => Array(
						__('Single Slide', 'javo_fr')=> 'single'
						, __('2 Cols Slide', 'javo_fr')=> 'two-cols'
						, __('3 Cols Slide', 'javo_fr')=> 'three-cols'
						, __('4 Cols Slide', 'javo_fr')=> 'four-cols'
					)
				), Array(
					'type'=>'dropdown'
					, 'heading'=> __('Category', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'category'
					, 'value' => $this->__cate('jv_events_category')
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Show Lists count', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'page'
					, 'value' => ''
				), Array(
					'type'=>'dropdown'
					, 'heading'=> __('Order by', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'order'
					, 'value' => Array(
						__('DESC','javo_fr')=> 'DESC'
						, __('ASC','javo_fr')=> 'ASC'
					)
				)
			)
		));



		// Javo Search Form
		vc_map(
			array(
				'base'					=> 'javo_search_form'
				, 'name'				=> __('Javo Item Search Form (Full-Width)', 'javo_fr')
				, 'icon'				=> 'javo-vc-icon'
				, 'category'			=> __('Javo', 'javo_fr')
				, 'description'			=> __('For archives in horizontal view, full-width layout', 'javo_fr')
				, 'params'				=> Array(
					Array(
						'type'			=> 'dropdown'
						, 'description'	=> __( "You can setup a map page ( Theme Setting > Item Pages > Search Result ). if there is no map pages, it goes to default searching page.", 'javo_fr' )
						, 'heading'		=> __('Please select search result page', 'javo_fr')
						, 'holder'		=> 'div'
						, 'class'		=> ''
						, 'param_name'	=> 'action'
						, 'value'		=> apply_filters(
							'javo_get_map_templates'
							, Array( __("Default Search Page", 'javo_fr') => '' )
						)
					)
					, Array(
						'type'			=> 'checkbox'
						, 'heading'		=> __( "Hide Search Fields : Check to Disable", 'javo_fr')
						, 'holder'		=> 'div'
						, 'class'		=> ''
						, 'param_name'	=> 'hide_field'
						, 'value'		=> Array(
							__( "Category", 'javo_fr' )					=> 'category'
							, __( "Location", 'javo_fr' )				=> 'location'
							, __( "Keyword", 'javo_fr' )				=> 'keyword'
							, __( "Google Autocomplete", 'javo_fr' )	=> 'google'
						)
					)
					, Array(
						'type'			=> 'dropdown'
						, 'heading'		=> __( "Display Border", 'javo_fr')
						, 'holder'		=> 'div'
						, 'class'		=> ''
						, 'param_name'	=> 'display_border'
						, 'value'		=> Array(
							__( "Enable", 'javo_fr')				=> ''
							, __( "Disabled", 'javo_fr')			=> 'hide'
						)
					)
				)
			)
		);

		// Javo Single Search Form
		vc_map(array(
			'base'					=> 'javo_single_search_form'
			, 'name'				=> __('Javo Single Search Form', 'javo_fr')
		    , 'icon'				=> 'javo-vc-icon'
			, 'category'			=> __('Javo', 'javo_fr')
			, 'description'			=> __('For archives in horizontal view, full-width layout', 'javo_fr')
			, 'params'				=> Array(
				Array(
					'type'			=> 'dropdown'
					, 'heading'		=> __('Please select search result page', 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'action'
					, 'description'	=> __('You can setup a map page ( Theme Setting > Item Pages > Search Result ). if there is no map pages, it goes to default searching page.', 'javo_fr')
					, 'value'		=> apply_filters( 'javo_get_map_templates', Array( __("Default Search Page", 'javo_fr') => '' ) )
				), Array(
					'type'=>'textfield'
					, 'heading'		=> __('Place Holder', 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'place_holder'
					, 'value'		=> __('Search', 'javo_fr')

				), Array(
					'type'			=> 'dropdown'
					, 'heading'		=> __( "Search Type", 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'input_field'
					, 'description'	=> __( "Where do you want to search?", 'javo_fr')
					, 'value'		=> Array(
						__('Location', 'javo_fr')				=> 'location'
						, __('Keyword', 'javo_fr')				=> 'keyword'

					)
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Button Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'single_search_button_color'
					, 'value' => '#7f7f7f'
				)
			)
		));






		// Javo Slide Search
		vc_map(array(
			'base'=> 'javo_slide_search'
			, 'name'=> __('Javo Slide with search bar (Full-width)', 'javo_fr')
		    , 'icon' => 'javo-vc-icon'
			, 'category'=> __('Javo', 'javo_fr')
			, 'description' => __('Use with Item Slide and Search bar.', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Subject', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => __('Title', 'javo_fr')
				), Array(
					'type'=>'dropdown'
					, 'heading'=> __('Search Type', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'search_type'
					, 'value' => Array(
						__('Vertical', 'javo_fr')		=> 'vertical'
						, __('Horizontal', 'javo_fr')	=> 'horizontal'
					)
				), Array(
					'type'=>'dropdown'
					, 'heading'=> __('Background Size', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'background_size'
					, 'value' => Array(
						__('Normal', 'javo_fr')			=> 'auto'
						, __('Cover', 'javo_fr')		=> 'cover'
					)
				), Array(
					'type'=>'dropdown'
					, 'heading'=> __('Background Position Horizontal', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'background_position_x'
					, 'value' => Array(
						__('Left', 'javo_fr')			=> 'left'
						, __('Center', 'javo_fr')		=> 'center'
						, __('Right', 'javo_fr')		=> 'right'
					)
				), Array(
					'type'=>'dropdown'
					, 'heading'=> __('Background Position Vertical', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'background_position_y'
					, 'value' => Array(
						__('Top', 'javo_fr')			=> 'top'
						, __('Center', 'javo_fr')		=> 'center'
						, __('Bottom', 'javo_fr')		=> 'bottom'
					)
				), Array(
					'type'=>'dropdown'
					, 'heading'=> __('Background Repeat', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'background_repeat'
					, 'value' => Array(
						__('No Repeat', 'javo_fr')		=> 'no-repeat'
						, __('Repeat-x', 'javo_fr')		=> 'repeat-x'
						, __('Repeat-y', 'javo_fr')		=> 'repeat-y'
						, __('Repeat', 'javo_fr')		=> 'repeat'
					)
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Height', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'height'
					, 'value' => '300'
				), Array(
					'type'								=> 'checkbox'
					, 'heading'							=> __('Hide Search Form', 'javo_fr')
					, 'holder'							=> 'div'
					, 'class'							=> ''
					, 'param_name'						=> 'hidden_form'
					, 'value'							=> Array(
						__('Hide Search Form', 'javo_fr')			=> 'hidden'
					)
				), Array(
					'type'								=> 'checkbox'
					, 'heading'							=> __('Hide options', 'javo_fr')
					, 'holder'							=> 'div'
					, 'class'							=> ''
					, 'param_name'						=> 'hidden_elements'
					, 'value'							=> Array(
						__('Keyword Textfield', 'javo_fr')			=> 'txt_keyword'
						, __('Category Drop-Down Box', 'javo_fr')	=> 'sel_category'
						, __('Location Drop-Down Box', 'javo_fr')	=> 'sel_location'
						, __('Rating Circle', 'javo_fr')			=> 'cle_rating'
						, __('Category CIrcle', 'javo_fr')			=> 'cle_category'
						, __('Event Tag Circle', 'javo_fr')			=> 'cle_event'
					)
				), Array(
					'type'=>'textfield'
					,'heading' => __('Title Size','javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=>'title_size'
					,'value'=>'25'
				), Array(
					'type'=>'textfield'
					,'heading' => __('Category & Location Font Size','javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=>'cat_loc_size'
					,'value'=>'20'
				)
			)
		));

		// Javo item_price
		vc_map(array(
			'base'=> 'javo_item_price'
			, 'name'=> __('Item Price Table', 'javo_fr')
		    , 'icon' => 'javo-vc-icon'
			, 'category'=> __('Javo', 'javo_fr')
			, 'group'		=> __('Javo', 'javo_fr')
			, 'description' => __('Must first be set-up in Theme Settings > Price Plan.', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => ''
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Sub Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'sub_title'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Sub Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'sub_title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Header Linear Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'line_color'
					, 'value' => '#fff'
				)
			)
		));

		// Javo Event Masonry
		vc_map(array(
			'base'=> 'javo_events_masonry'
			, 'name'=> __('Javo Events Masonry (Full-width)', 'javo_fr')
			, 'category'=> __('Javo', 'javo_fr')
			, 'icon' => 'javo-vc-icon'
			, 'description' => __('Display your events in grid style. (Full-width layout only)', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => ''
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Sub Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'sub_title'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Sub Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'sub_title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Header Linear Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'line_color'
					, 'value' => '#fff'
				)
			)
		));


		// Javo FaQs
		vc_map(array(
			'base'=> 'javo_faq'
			, 'name'=> __('Javo FAQ Accordion Tabs', 'javo_fr')
			, 'category'=> __('Javo', 'javo_fr')
			, 'icon' => 'javo-vc-icon'
			, 'description' => __('Displays FAQ posts from Dashboard > FAQs.', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => ''
				),Array(
					'type'=>'textfield'
					, 'heading'=> __('Sub Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'sub_title'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Sub Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'sub_title_text_color'
					, 'value' => '#000'
				),Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Content Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'text_color'
					, 'value' => '#aaa'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Header Linear Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'line_color'
					, 'value' => '#fff'
				)
			)
		));


		// Javo grid open
		vc_map(array(
			'base'=> 'javo_grid_open'
			, 'name'=> __('Javo Item Grid - Open (Full-width)', 'javo_fr')
			, 'category'=> __('Javo', 'javo_fr')
			, 'icon' => 'javo-vc-icon'
			, 'description' => __('Display items in an open grid layout. (Full-width only)', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => ''
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Sub Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'sub_title'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Sub Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'sub_title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Header Linear Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'line_color'
					, 'value' => '#fff'
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Display item count', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'count'
					, 'value' => '7'
				)
			)
		));

		// Javo categories
		vc_map(array(
			'base'=> 'javo_categories'
			, 'name'=> __('Javo Large Blocks', 'javo_fr')
			, 'category'=> __('Javo', 'javo_fr')
			, 'icon' => 'javo-vc-icon'
			, 'description' => __('Displays items in a larger block style.', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => ''
				),Array(
					'type'=>'textfield'
					, 'heading'=> __('Sub Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'sub_title'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Sub Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'sub_title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Header Linear Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'line_color'
					, 'value' => '#fff'
				), Array(
					'type'=>'dropdown'
					, 'heading'=> __('Display Category Type', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'display_type'
					, 'value' => Array(
						__('Parent Only', 'javo_fr')		=> 'parent'
						, __('Parent + Child', 'javo_fr')	=> 'child'
					)
				), Array(
					'type'=>'dropdown'
					, 'heading'=> __('Hover Option to Display', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'display_item_count'
					, 'value' => Array(
						__('Show the amount of items on the categories', 'javo_fr')	=> ''
						, __('Do not display', 'javo_fr')							=> 'hide'
					)
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Category Font Size', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'category_size'
					, 'value' => '18'
				)
			)
		));

		// Javo Archive Categories
		vc_map(array(
			'base'=> 'javo_archive_categories'
			, 'name'=> __('Javo Archive Categories', 'javo_fr')
			, 'category'=> __('Javo', 'javo_fr')
			, 'icon' => 'javo-vc-icon'
			, 'description' => __('Displays items in a long horizontal block fashion.', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => ''
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Sub Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'sub_title'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Sub Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'sub_title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Header Linear Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'line_color'
					, 'value' => '#fff'
				), Array(
					'type'								=> 'dropdown'
					, 'heading'							=> __('Amount Column in One Row', 'javo_fr')
					, 'holder'							=> 'div'
					, 'class'							=> ''
					, 'param_name'						=> 'one_row_item'
					, 'value'							=> Array(
						'3 '.__('Columns', 'javo_fr')	=> 3
						, '4 '.__('Columns', 'javo_fr')	=> 4
					)
				), Array(
					'type'								=> 'checkbox'
					, 'heading'							=> __('Parents', 'javo_fr')
					, 'holder'							=> 'div'
					, 'class'							=> ''
					, 'param_name'						=> 'have_terms'
					, 'description'						=> __('Default : All Parents', 'javo_fr')
					, 'value'							=> apply_filters('javo_get_categories_value', 'item_category')
				), Array(
					'type'								=> 'textfield'
					, 'heading'							=> __('One Block per Amount Category', 'javo_fr')
					, 'holder'							=> 'div'
					, 'class'							=> ''
					, 'param_name'						=> 'category_max_count'
					, 'value'							=> 5
				), Array(
					'type'								=> 'textfield'
					, 'heading'							=> __('Container Radius Size (Only Pixcel Number)', 'javo_fr')
					, 'holder'							=> 'div'
					, 'class'							=> ''
					, 'param_name'						=> 'cbx_border_radius'
					, 'value'							=> 5
				), Array(
					'type'								=> 'colorpicker'
					, 'heading'							=> __('Container Border', 'javo_fr')
					, 'holder'							=> 'div'
					, 'param_name'						=> 'cbx_border'
					, 'value'							=> '#428bca'
				), Array(
					'type'								=> 'colorpicker'
					, 'heading'							=> __('Container Background', 'javo_fr')
					, 'holder'							=> 'div'
					, 'param_name'						=> 'cbx_background'
					, 'value'							=> '#fff'
				), Array(
					'type'								=>'colorpicker'
					, 'heading'							=> __('Linear Color', 'javo_fr')
					, 'holder'							=> 'div'
					, 'param_name'						=> 'cbx_hr'
					, 'value'							=> '#428bca'
				), Array(
					'type'								=>'colorpicker'
					, 'heading'							=> __('Font Color', 'javo_fr')
					, 'holder'							=> 'div'
					, 'param_name'						=> 'cbx_font'
					, 'value'							=> '#000'
				)
			)
		));

		// Javo Rating list
		vc_map(array(
			'base'=> 'javo_rating_list'
			, 'name'=> __('Javo Overall Rating Blocks', 'javo_fr')
			, 'category'=> __('Javo', 'javo_fr')
			, 'icon' => 'javo-vc-icon'
			, 'description' => __('Displays items a horizontal block layout with a focus on overall ratings.', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => ''
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Sub Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'sub_title'
					, 'value' => ''
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Display Rating count', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'count'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Sub Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'sub_title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Header Linear Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'line_color'
					, 'value' => '#fff'
				)
			)
		));

		// Javo Rating list
		vc_map(array(
			'base'=> 'javo_recent_ratings'
			, 'name'=> __('Javo Recent Rating Blocks', 'javo_fr')
			, 'category'=> __('Javo', 'javo_fr')
			, 'icon' => 'javo-vc-icon'
			, 'description' => __('Displays items a horizontal block layout with a focus on recent ratings.', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => ''
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Sub Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'sub_title'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Sub Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'sub_title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Header Linear Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'line_color'
					, 'value' => '#fff'
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Display Rating count', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'items'
					, 'value' => ''
				)
			)
		));


		// Javo fancy title
		vc_map(array(
			'base'=> 'javo_fancy_titles'
			, 'name'=> __('Javo Fancy Title', 'javo_fr')
			, 'category'=> __('Javo', 'javo_fr')
			, 'icon' => 'javo-vc-icon'
			, 'description' => __('Make an impression with a fancy title.', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Display Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => __('Title', 'javo_fr')
				), Array(
					'type'=>'dropdown'
					, 'heading'=> __('Header Type', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'type'
					, 'value' => Array(
						__('Red Line (Defalut)', 'javo_fr') => ''
						, __('Gray Circle Line', 'javo_fr') => 'gray_circle'
					)
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'text_color'
					, 'value' => __('#000000', 'javo_fr')
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Line Spacing', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'line_spacing'
					, 'value' => __('20', 'javo_fr')
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Font Size', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'font_size'
					, 'value' => __('12', 'javo_fr')
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Description', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'description'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Description Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'description_color'
					, 'value' => __('#000000', 'javo_fr')
				)
			)
		));

		// Member Register & Login
		vc_map(array(
			'base'=> 'javo_register_login'
			, 'name'=> __('Javo Tab Registration/Login', 'javo_fr')
			, 'category'=> __('Javo', 'javo_fr')
			, 'icon' => 'javo-vc-icon'
			, 'description' => __('Registration, Login, Find password and contact info (seen before logging in) ', 'javo_fr')
			, 'params'=>Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => ''
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Sub Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'sub_title'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Sub Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'sub_title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Header Linear Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'line_color'
					, 'value' => '#fff'
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Login information Box Title', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'login_info_box_title'
					, 'value' =>' '
				), Array(
					'type'=>'textarea'
					, 'heading'=> __('Login information Box Content', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'login_info_box'
					, 'value' =>' '
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Register information Box Title', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'register_info_box_title'
					, 'value' =>' '
				), Array(
					'type'=>'textarea'
					, 'heading'=> __('Register information Box', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'register_info_box'
					, 'value' => ' '
				), Array(
					'type'=>'textfield'
					, 'heading'=> __('Forget Password information Box Title', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'forget_info_box_title'
					, 'value'		=>' '
				), Array(
					'type'			=>'textarea'
					, 'heading'		=> __('Forget Password information Box', 'javo_fr')
					, 'holder'		=> 'div'
					, 'param_name'	=> 'forget_info_box'
					, 'value'		=> ' '
				)
			)
		));

		// Javo Gallery
		vc_map(array(
			'base'					=> 'javo_gallery'
			, 'name'				=> __('Javo Grid', 'javo_fr')
			, 'category'			=> __('Javo', 'javo_fr')
			, 'icon'				=> 'javo-vc-icon'
			, 'description'			=> __('Dispalys items in a grid, highlighting location and ratings.', 'javo_fr')
			, 'params'				=> Array(
				Array(
					'type'			=> 'textfield'
					, 'heading'		=> __('Title', 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'title'
					, 'value'		=> ''
				), Array(
					'type'			=> 'textfield'
					, 'heading'		=> __('Sub Title', 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'sub_title'
					, 'value'		=> ''
				), Array(
					'type'			=> 'colorpicker'
					, 'heading'		=> __('Title Text Color', 'javo_fr')
					, 'holder'		=> 'div'
					, 'param_name'	=> 'title_text_color'
					, 'value'		=> '#000'
				), Array(
					'type'			=>'colorpicker'
					, 'heading'		=> __('Sub Title Text Color', 'javo_fr')
					, 'holder'		=> 'div'
					, 'param_name'	=> 'sub_title_text_color'
					, 'value'		=> '#000'
				), Array(
					'type'			=> 'colorpicker'
					, 'heading'		=> __('Header Linear Color', 'javo_fr')
					, 'holder'		=> 'div'
					, 'param_name'	=> 'line_color'
					, 'value'		=> '#fff'
				), Array(
					'type'			=> 'dropdown'
					, 'heading'		=> __('Display Category Type', 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'display_type'
					, 'value'		=> Array(
						__('Parent Only', 'javo_fr')		=> 'parent'
						, __('Parent + Child', 'javo_fr')	=> 'child'
					)
				), Array(
					'type'=>'checkbox'
					, 'heading'		=> __('Parents', 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'have_terms'
					, 'description'	=> __('Default : All Parents', 'javo_fr')
					, 'value'		=> apply_filters('javo_get_categories_value', 'item_category')
				), Array(
					'type'			=> 'textfield'
					, 'heading'		=> __('Max display amount on all category ( 0 = All Items)', 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'max_amount'
					, 'description'	=> __('(Only Number)', 'javo_fr')
					, 'value'		=> (int)0
				), Array(
					'type'			=> 'checkbox'
					, 'heading'		=> __('Random Order', 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'rand_order'
					, 'value'		=> Array(__('Enabled', 'javo_fr') =>'use')
				)
			)
		));

		// Javo Events Gallery
		vc_map(array(
			'base'					=> 'javo_event_gallery'
			, 'name'				=> __('Javo Events Gallery', 'javo_fr')
			, 'category'			=> __('Javo', 'javo_fr')
			, 'icon'				=> 'javo-vc-icon'
			, 'description'			=> __('Show your items in a smaller grid with an emphasis on events.', 'javo_fr')
			, 'params'				=> Array(
				Array(
					'type'			=> 'textfield'
					, 'heading'		=> __('Title', 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'title'
					, 'value'		=> ''
				),Array(
					'type'			=>'textfield'
					, 'heading'		=> __('Sub Title', 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'sub_title'
					, 'value'		=> ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Sub Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'sub_title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Header Linear Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'line_color'
					, 'value' => '#fff'
				), Array(
					'type'=>'dropdown'
					, 'heading'=> __('Display Category Type', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'display_type'
					, 'value' => Array(
						__('Parent Only', 'javo_fr')		=> 'parent'
						, __('Parent + Child', 'javo_fr')	=> 'child'
					)
				)
			)
		));

		//Javo team-slider
		vc_map(array(
			'base'=> 'team_slider'
			, 'name'=> __('Javo Team Slider', 'javo_fr')
			, 'category'=> __('Javo', 'javo_fr')
			, 'icon' => 'javo-vc-icon'
			, 'description' => __('Description', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => ''
				),Array(
					'type'=>'textfield'
					, 'heading'=> __('Sub Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'sub_title'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Sub Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'sub_title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Header Linear Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'line_color'
					, 'value' => '#fff'
				),Array(
					'type'=>'dropdown'
					, 'heading'=> __('column count', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'column'
					, 'value' => Array(
						2=>2,
						3=>3,
						4=>4
					)
				)
			)
		));

		//Javo testimonial
		vc_map(array(
			'base'=> 'javo_testimonial'
			, 'name'=> __('Javo Testimonial', 'javo_fr')
			, 'category'=> __('Javo', 'javo_fr')
			, 'icon' => 'javo-vc-icon'
			, 'description' => __('Description', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => ''
				),Array(
					'type'=>'textfield'
					, 'heading'=> __('Sub Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'sub_title'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Sub Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'sub_title_text_color'
					, 'value' => '#000'
				),Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Content Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Header Linear Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'line_color'
					, 'value' => '#fff'
				),Array(
					'type'=>'textfield'
					, 'heading'=> __('Count( 0 = ALL )', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'count'
					, 'value'=>0
				)
			)
		));

		//Javo partner-slider
		vc_map(array(
			'base'=> 'javo_partner_slider'
			, 'name'=> __('Javo Partner Slider', 'javo_fr')
			, 'category'=> __('Javo', 'javo_fr')
			, 'icon' => 'javo-vc-icon'
			, 'description' => __('Description', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => ''
				),Array(
					'type'=>'textfield'
					, 'heading'=> __('Sub Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'sub_title'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Sub Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'sub_title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Header Linear Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'line_color'
					, 'value' => '#fff'
				),Array(
					'type'=>'textfield'
					, 'heading'=> __('column count', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'column'
					, 'value' => ''
				)
			)
		));

		// Javo Featured Items
		vc_map(array(
			'base'=> 'javo_featured_items'
			, 'name'=> __('Javo Featured Items', 'javo_fr')
			, 'category'=> __('Javo', 'javo_fr')
			, 'icon' => 'javo-vc-icon'
			, 'description' => __('Broadcast your featured items in a bold manner.', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'textfield'
					, 'heading'=> __('Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'title'
					, 'value' => ''
				),Array(
					'type'=>'textfield'
					, 'heading'=> __('Sub Title', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'sub_title'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Sub Title Text Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'sub_title_text_color'
					, 'value' => '#000'
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Header Linear Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'line_color'
					, 'value' => '#fff'
				), Array(
					'type'=>'checkbox'
					, 'heading' => __('Random Ordering ','javo_fr')
					, 'holder' => 'div'
					, 'param_name' => 'random'
					, 'value' => array(
						__('Able to order randomly','javo_fr') =>'rand'
					)
				)
			)
		));

		// javo image categories(to maps)
		vc_map(array(
			'base'=> 'javo_image_categories'
			, 'name'=> __('Image Categories (to Maps)', 'javo_fr')
		    , 'icon' => 'javo-vc-icon'
			, 'category'=> __('Javo', 'javo_fr')
			, 'description' => __('Add categories with your own images.', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'dropdown'
					, 'heading'=> __('Row column', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'column'
					, 'value' => Array(
						'1/3'=>'1-3',
						'2/3'=>'2-3',
						'full'=>'full'
					)
				),Array(
					'type'=>'textfield'
					, 'heading'=> __('Category Name', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'text'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Name Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'text_color'
					, 'value' => '#fff'
				), Array(
					'type'=>'checkbox'
					, 'heading'=> __('Name Border', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'text_border'
					, 'value' => Array(
						__('Enable','javo_fr')=>'use'
					)
				),Array(
					'type'=>'attach_image'
					, 'heading'=> __('Select an Image', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'attachment_id'
					, 'value' => ''
				),Array(
					'type'=>'textfield'
					, 'heading'=> __('Link (ex : ../map-page/?category=12)', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'link'
					, 'value' => '#'
				)
			)
		));

		//javo featured block
		vc_map(array(
			'base'=> 'javo_featured_item_block'
			, 'name'=> __('Javo Featured Item Block', 'javo_fr')
		    , 'icon' => 'javo-vc-icon'
			, 'category'=> __('Javo', 'javo_fr')
			, 'description' => __('Featured items with your own images.', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'=>'dropdown'
					, 'heading'=> __('Row Column', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'column'
					, 'value' => Array(
						'1/3'=>'1-3',
						'2/3'=>'2-3',
						'full'=>'full'
					)
				),Array(
					'type'=>'textfield'
					, 'heading'=> __('Item Name', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'javo_featured_block_title'
					, 'value' => ''
				), Array(
					'type'=>'colorpicker'
					, 'heading'=> __('Name Color', 'javo_fr')
					, 'holder' => 'div'
					, 'param_name'=> 'text_color'
					, 'value' => '#fff'
				),Array(
					'type'=>'dropdown'
					, 'heading'=> __('Original Featured Item', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'javo_featured_block_id'
					, 'value' => apply_filters('javo_get_fetured_item','')
				),Array(
					'type'=>'attach_image'
					, 'heading'=> __('Image (If you want another image)', 'javo_fr')
					, 'holder' => 'div'
					, 'class'=> ''
					, 'param_name'=> 'attachment_other_image'
					, 'value' => ''
				)
			)
		));

		//Javo Featured Full Slide
		vc_map(array(
			'base'=> 'javo_featured_items_slider'
			, 'name'=> __('Featured Items Slider(Full)', 'javo_fr')
		    , 'icon' => 'javo-vc-icon'
			, 'category'=> __('Javo', 'javo_fr')
			, 'description' => __('A slider for featured items.', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'				=> 'checkbox'
					, 'heading'			=> __('Random', 'javo_fr')
					, 'holder'			=> 'div'
					, 'class'			=> ''
					, 'param_name'		=> 'random'
					, 'value'			=> Array(
						__('Random', 'javo_fr')=>'rand'
					)
				), Array(
					'type'				=>'textfield'
					, 'heading'			=> __('Amount of Items', 'javo_fr')
					, 'holder'			=> 'div'
					, 'class'			=> ''
					, 'param_name'		=> 'count'
					, 'value'			=> '8'
				), Array(
					'type'				=> 'checkbox'
					, 'heading'			=> __("Hide Description", 'javo_fr')
					, 'holder'			=> 'div'
					, 'class'			=> ''
					, 'param_name'		=> 'meta_hide'
					, 'value'			=> Array(
						__('Hide', 'javo_fr')	=> 'hidden'
					)
				)
			)
		));

		// Javo Recent Items Slider
		vc_map(array(
			'base'=> 'javo_recent_items_slider'
			, 'name'=> __("Javo Recent Item Slider(Full)", 'javo_fr')
		    , 'icon' => 'javo-vc-icon'
			, 'category'=> __('Javo', 'javo_fr')
			, 'description' => __('A slider for recent Items.', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'				=> 'checkbox'
					, 'heading'			=> __('Random', 'javo_fr')
					, 'holder'			=> 'div'
					, 'class'			=> ''
					, 'param_name'		=> 'random'
					, 'value'			=> Array(
						__('Random', 'javo_fr')=>'rand'
					)
				), Array(
					'type'				=>'textfield'
					, 'heading'			=> __('Amount of Items', 'javo_fr')
					, 'holder'			=> 'div'
					, 'class'			=> ''
					, 'param_name'		=> 'count'
					, 'value'			=> '8'
				), Array(
					'type'				=> 'checkbox'
					, 'heading'			=> __( "Hide Description", 'javo_fr')
					, 'holder'			=> 'div'
					, 'class'			=> ''
					, 'param_name'		=> 'meta_hide'
					, 'value'			=> Array(
						__('Hide', 'javo_fr')	=> 'hidden'
					)
				)
			)
		));

		// javo category with icon
		/*vc_map(array(
			'base'=> 'javo_category_with_icon'
			, 'name'=> __('Javo Category With Icon (To Map)', 'javo_fr')
			, 'category'=> __('Javo', 'javo_fr')
			, 'icon' => 'javo-vc-icon'
			, 'description' => __("Shortcode that shows category list along with the set icon.", 'javo_fr')
			, 'params'=> Array(
				 Array(
					'type'								=> 'dropdown'
					, 'heading'							=> __('000 Result Map Style', 'javo_fr')
					, 'holder'							=> 'div'
					, 'class'							=> ''
					, 'param_name'						=> 'result_map_type'
					, 'value'							=> apply_filters('javo_get_map_templates', null)

				), Array(
					'type'								=> 'dropdown'
					, 'heading'							=> __('Amount Column in One Row', 'javo_fr')
					, 'holder'							=> 'div'
					, 'class'							=> ''
					, 'param_name'						=> 'one_row_item'
					, 'value'							=> Array(
						'3 '.__('Columns', 'javo_fr')	=> 3
						, '4 '.__('Columns', 'javo_fr')	=> 4
					)
				), Array(
					'type'								=> 'checkbox'
					, 'heading'							=> __('Parents', 'javo_fr')
					, 'holder'							=> 'div'
					, 'class'							=> ''
					, 'param_name'						=> 'have_terms'
					, 'description'						=> __('Default : All Parents', 'javo_fr')
					, 'value'							=> apply_filters('javo_get_categories_value', 'item_category')
				), Array(
					'type'								=> 'textfield'
					, 'heading'							=> __('One Block per Amount Category', 'javo_fr')
					, 'holder'							=> 'div'
					, 'class'							=> ''
					, 'param_name'						=> 'category_max_count'
					, 'value'							=> 5
				), Array(
					'type'								=> 'textfield'
					, 'heading'							=> __('Container Radius Size (Only Pixcel Number)', 'javo_fr')
					, 'holder'							=> 'div'
					, 'class'							=> ''
					, 'param_name'						=> 'cbx_border_radius'
					, 'value'							=> 5
				), Array(
					'type'								=> 'colorpicker'
					, 'heading'							=> __('Container Border', 'javo_fr')
					, 'holder'							=> 'div'
					, 'param_name'						=> 'cbx_border'
					, 'value'							=> '#428bca'
				), Array(
					'type'								=> 'colorpicker'
					, 'heading'							=> __('Container Background', 'javo_fr')
					, 'holder'							=> 'div'
					, 'param_name'						=> 'cbx_background'
					, 'value'							=> '#fff'
				), Array(
					'type'								=>'colorpicker'
					, 'heading'							=> __('Linear Color', 'javo_fr')
					, 'holder'							=> 'div'
					, 'param_name'						=> 'cbx_hr'
					, 'value'							=> '#428bca'
				), Array(
					'type'								=>'colorpicker'
					, 'heading'							=> __('Font Color', 'javo_fr')
					, 'holder'							=> 'div'
					, 'param_name'						=> 'cbx_font'
					, 'value'							=> '#000'
				)
			)
		));*/

		// Javo inline category slider
		vc_map(array(
			'base'					=> 'javo_inline_category_slider'
			, 'name'				=> __('Javo Inline Category Slider', 'javo_fr')
			, 'category'			=> __('Javo', 'javo_fr')
			, 'icon'				=> 'javo-vc-icon'
			, 'description'			=> __("A slider for categories","javo_fr")
			, 'params'				=> Array(
				Array(
					'type'								=> 'dropdown'
					, 'heading'							=> __('Landing map ( Result page)', 'javo_fr')
					, 'holder'							=> 'div'
					, 'class'							=> ''
					, 'param_name'						=> 'result_map_type'
					, 'value'							=> apply_filters('javo_get_map_templates', null)

				), Array(
					'type'			=> 'dropdown'
					, 'heading'		=> __('Display Category Type', 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'display_type'
					, 'value'		=> Array(
						__('Parent Only', 'javo_fr')		=> 'parent'
						, __('Parent + Child', 'javo_fr')	=> 'child'
					)
				), Array(
					'type'=>'checkbox'
					, 'heading'		=> __('Parents', 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'have_terms'
					, 'description'	=> __('Default : All Parents', 'javo_fr')
					, 'value'		=> apply_filters('javo_get_categories_value', 'item_category')
				), Array(
					'type'			=> 'textfield'
					, 'heading'		=> __('Display amount of items.', 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'max_amount'
					, 'description'	=> __('(Only Number. recomend around 8)', 'javo_fr')
					, 'value'		=> (int)0
				), Array(
					'type'			=> 'checkbox'
					, 'heading'		=> __('Random Ordering', 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'rand_order'
					, 'value'		=> Array(__('Enabled', 'javo_fr') =>'use')
				),Array(
					'type'			=> 'textfield'
					, 'heading'		=> __('Radius', 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'radius'
					, 'description'	=> __('Category image radius', 'javo_fr')
					, 'value'		=> (int)0
				), Array(
					'type'								=>'colorpicker'
					, 'heading'							=> __('Category Name Color', 'javo_fr')
					, 'holder'							=> 'div'
					, 'param_name'						=> 'inline_cat_text_color'
					, 'value'							=> ''
				), Array(
					'type'								=>'colorpicker'
					, 'heading'							=> __('Name Hover Color', 'javo_fr')
					, 'holder'							=> 'div'
					, 'param_name'						=> 'inline_cat_text_hover_color'
					, 'value'							=> ''
				), Array(
					'type'								=>'colorpicker'
					, 'heading'							=> __('Arrow Color', 'javo_fr')
					, 'holder'							=> 'div'
					, 'param_name'						=> 'inline_cat_arrow_color'
					, 'value'							=> ''
				)
			)
		));

		// javo item time line
		vc_map(array(
			'base'=> 'javo_item_time_line'
			, 'name'=> __('Javo Item Time Line', 'javo_fr')
			, 'category'=> __('Javo', 'javo_fr')
			, 'icon' => 'javo-vc-icon'
			, 'description' => __("Time line display for blog posts", 'javo_fr')
			, 'params'=> Array(
				 Array(
					'type'								=> 'textfield'
					, 'heading'							=> __('Loading amount of posts (when loading button is clicked )', 'javo_fr')
					, 'holder'							=> 'div'
					, 'class'							=> ''
					, 'param_name'						=> 'items'
					, 'value'							=> 4
				)
			)
		));

		//javo Mail
		vc_map(array(
			'base'					=> 'javo_mailchimp'
			, 'name'				=> __('Javo Mail Chimp', 'javo_fr')
			, 'icon'				=> 'javo-vc-icon'
			, 'category'			=> __('Javo', 'javo_fr')
			, 'description'			=> __('Javo MailChimp Shortcode', 'javo_fr')
			, 'params'=> Array(
				Array(
					'type'			=> 'dropdown'
					, 'heading'		=> __("LIST ID", 'javo_fr')
					, 'holder'		=> 'div'
					, 'class'		=> ''
					, 'param_name'	=> 'list_id'
					, 'description'	=> __('You need to create a list id on mailchimp site, if you don`t have', 'javo_fr')
					, 'value'		=> apply_filters( 'javo_mail_chimp_get_lists', null )
				)
			)
		));

		// fullwidth
		vc_add_param("vc_row", array(
			"type" => "checkbox",
			"class" => "javo-full-width",
			"heading" => __("Content FULL-WIDTH Layout", 'javo_fr'),
			"param_name" => "javo_full_width",
			"value" => array(
				"" => "1"
			)
		));

		// ! Adding animation to columns
		vc_add_param("vc_column", array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Animation", 'javo_fr'),
			"admin_label" => true,
			"param_name" => "animation",
			"value" => array(
				__("None","javo_fr") => "",
				__("Left","javo_fr") => "right-to-left",
				__("Right","javo_fr") => "left-to-right",
				__("Top","javo_fr") => "bottom-to-top",
				__("Bottom","javo_fr") => "top-to-bottom",
				__("Scale","javo_fr") => "scale-up",
				__("Fade","javo_fr") => "fade-in"
			)
		));

		// Javo VC-Row Append Attributes.

		vc_add_param("vc_row", array(
			"type" => "attach_image",
			"heading" => __("Background Image (Full Width)", 'javo_fr'),
			"group"=> __('Javo', 'javo_fr'),
			"param_name" => "bg_src",
			"value" => ''
		));

		vc_add_param("vc_row", array(
			"type" => "colorpicker",
			"heading" => __("Background Color (Full Width)", 'javo_fr'),
			"group"=> __('Javo', 'javo_fr'),
			"param_name" => "background_color",
			"value" => ''
		));

		vc_add_param("vc_row", array(
			"type" => "checkbox",
			"heading" => __("Enable Section Shadow (Full Width)", 'javo_fr'),
			"group"=> __('Javo', 'javo_fr'),
			"param_name" => "box_shadow",
			"value" => Array( ''=> 'use')
		));

		vc_add_param("vc_row", array(
			"type" => "colorpicker",
			"heading" => __("Section Shadow Color (Full Width)", 'javo_fr'),
			"group"=> __('Javo', 'javo_fr'),
			"param_name" => "box_shadow_color",
			"value" => '#000'
		));

		vc_add_param("vc_row", array(
			"type" => "dropdown",
			"heading" => __("Background Type (Full Width)", 'javo_fr'),
			"group"=> __('Javo', 'javo_fr'),
			"param_name" => "background_type",
			"value" => array(
				__('Default', 'javo_fr') => "",
				__('Parallax', 'javo_fr') => "parallax"
			)
		));

		vc_add_param("vc_row", array(
			"type" => "textfield",
			"heading" => __("Parallax Delay (Only Number)(Full Width)", 'javo_fr'),
			"group"=> __('Javo', 'javo_fr'),
			"param_name" => "parallax_delay",
			"value" => '0.1'
		));
	} //javo_vb_shortcodes_callback

	public function javo_vb_basic_shortcodes_trash_callback(){
		$javo_remove_shortcodes = Array(
			//'vc_images_carousel'
			//, 'vc_gallery'
			'get_items'
			, 'vc_posts_grid'
			//, 'vc_carousel'
		);
		foreach( $javo_remove_shortcodes as $element){
			vc_remove_element($element);
		};
	} //javo_vb_basic_shortcodes_trash_callback
	public function __cate($tax_name){
		//$tax_name = "category";
		$javo_get_tax = get_terms($tax_name, Array('hide_empty'=>0));
		if( !is_wp_error( $javo_get_tax ) ){
			$javo_get_tax_return = Array(__('No Select', 'javo_fr') => "");
			foreach($javo_get_tax as $tax){
				$javo_get_tax_return[ $tax->name ] = $tax->term_id;
			};
		};
		return !empty($javo_get_tax_return)? $javo_get_tax_return : null;
	}

	static function javo_get_categories_value_callback($taxonomy){
		$javo_this_return		= Array();
		$javo_this_terms		= get_terms( $taxonomy, Array('hide_empty'=>false, 'parent'=>0) );
		if( !empty( $javo_this_terms ) ){
			foreach( $javo_this_terms as $term ) {
				$javo_this_return[$term->name]		= $term->term_id;
			};
		};
		return $javo_this_return;
	}
	static function javo_get_categories_value_with_child_callback($taxonomy){
		$javo_this_return		= Array();
		$javo_this_terms		= get_terms( $taxonomy, Array('hide_empty'=>false) );
		if( !empty( $javo_this_terms ) ){
			foreach( $javo_this_terms as $term ) {
				$javo_this_return[$term->name]		= $term->term_id;
			};
		};
		return $javo_this_return;
	}

	static function javo_get_locations_value_with_child_callback($taxonomy){
		$javo_this_return		= Array();
		$javo_this_terms		= get_terms( $taxonomy, Array('hide_empty'=>false) );
		if( !empty( $javo_this_terms ) ){
			foreach( $javo_this_terms as $term ) {
				$javo_this_return[$term->name]		= $term->term_id;
			};
		};
		return $javo_this_return;
	}

	public function __get_p_type(){
		$javo_get_post_types = get_post_types();
		$javo_excluedes_post_types = Array( 'attachment', 'page', 'payment');
		$javo_get_post_types_return = Array();
		foreach($javo_excluedes_post_types as $post_type){
			if( in_array( $post_type , $javo_get_post_types ) ){
				unset( $javo_get_post_types[ $post_type ] );
			};
		};
		foreach($javo_get_post_types as $post_type){
			$javo_get_post_type = get_post_type_object($post_type);
			$javo_get_post_types_return[$javo_get_post_type->labels->name] = $post_type;

		}
		return $javo_get_post_types_return;
	}
	static function javo_get_stc_search_result_pages_callback(){
		$javo_this_return	= Array(__('Search result page', 'javo_fr') => '');
		$javo_query_args	= Array(
			"post_type"			=> "page"
			, "post_status"		=> "publish"
			, 'posts_per_page'	=> 1
			, 'meta_query'		=> Array(
				'relation'		=> 'OR'
				, Array(
					'key'		=> '_wp_page_template'
					, 'value'	=> 'templates/tp-javo-map-box.php'
				)
			)
		);
		$javo_get_pages = get_posts( $javo_query_args );
		if( !empty( $javo_get_pages ) ){
			foreach( $javo_get_pages as $page ){
				setup_postdata($page);
				$javo_this_return[ $page->post_title ] = $page->ID;
			}; // End Foreach
		}; // End If
		return (Array)$javo_this_return;
	}
	static function javo_get_fetured_item_callback(){
		$javo_this_return		= Array();
		$javo_wg_featured_args	= Array(
			'post_type'					=> 'item'
			, 'post_status'				=> 'publish'
			, 'orderby' => ''
			, 'meta_query'				=> Array(
				Array(
					'key'			=> 'javo_this_featured_item'
					, 'compare'		=> '='
					, 'value'		=> 'use'
				)
			)
		);
		$javo_wg_featured = get_posts($javo_wg_featured_args);
		if( !empty( $javo_wg_featured ) ){
			foreach( $javo_wg_featured as $item ) {
				$javo_this_return[$item->post_title] =$item->ID;
			};
		};
		return (Array)$javo_this_return;
	}

	static function javo_get_map_templates_callback( $append = Array() )
	{
		$javo_this_return = Array();
		$javo_query_args	= Array(
			"post_type"			=> "page"
			, "post_status"		=> "publish"
			, 'posts_per_page'	=> -1
			, 'meta_query'		=> Array(
				'relation'		=> 'OR'
				, Array(
					'key'		=> '_wp_page_template'
					, 'value'	=> Array(
						'templates/tp-javo-map-box.php'
						, 'templates/tp-javo-map-wide.php'
						, 'templates/tp-javo-map-tab.php'
					)
				)
			)
		);
		$javo_query_posts = query_posts($javo_query_args);
		if( !empty( $javo_query_posts ) ){
			foreach( $javo_query_posts as $query ) {
				$javo_this_return[$query->post_title] =$query->ID;
			};
		};
		wp_reset_query();

		if( is_Array( $append ) )
		{
			$javo_this_return = wp_parse_args( $javo_this_return, $append );
		}
		return (Array)$javo_this_return;
	}

	public static function javo_mail_chimp_get_lists_callback()
	{
		global $javo_tso;

		require_once JAVO_SYS_DIR.'/functions/MCAPI.class.php';

		$javo_result	= Array(
			__( "Theme Setting > General > Plugin > API KEY (Please add your API key)", 'javo_fr') => ''
		);

		if( '' !== ( $javo_api_key = $javo_tso->get( 'mailchimp_api', '' ) ) )
		{
			$javo_result	= Array();
			$mc_instance	= new MCAPI( $javo_api_key );
			$mc_lists_map	= $mc_instance->lists();

			if( !empty( $mc_lists_map['data'] ) )
			{
				foreach( $mc_lists_map['data'] as $list )
				{
					$javo_result[ $list[ 'name'] ] = $list[ 'id' ];
				}
			}else{
				$javo_result	= Array(
					__( "Wrong API key.", 'javo_fr') => ''
				);
			}
		}

		return $javo_result;
	}


};
new javo_vb_shortcodes();