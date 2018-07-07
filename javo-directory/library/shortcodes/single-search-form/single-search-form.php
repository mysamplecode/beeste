<?php
class javo_single_search_form
{
	static $load_script = false;
	public function __construct()
	{
		add_shortcode(	'javo_single_search_form', Array( __CLASS__ ,'javo_search_form_callback' ) );
		add_action(	'wp_footer', Array( __CLASS__ ,'load_script_func' ) );
	}
	public static function load_script_func()
	{
		if( self::$load_script )
		{
			wp_enqueue_script( 'google-map' );
			wp_enqueue_script( 'gmap-v3' );
			wp_enqueue_script( 'jquery-type-header' );
		}
	}
	public static function javo_search_form_callback( $atts, $content ="" )
	{
	    global
			$javo_tso
			, $javo_tso_map;

		self::$load_script = true;

		extract(
			shortcode_atts(
				Array(
					/*	Describe :		Action
					*	Type :			String( Empty / 'map' )
					*/
					'action'			=> ''

					/*	Describe :		Hidden Field
					*	Type :			Array
					*/
					, 'hide_field'		=> Array()

					/*	Describe :		Search input type
					*	Type :			String
					*/
					, 'input_field'		=> 'location'

					/*	Describe :		Search input Placeholder
					*	Type :			String
					*/
					, 'place_holder'	=> ''
					, 'single_search_button_color' => ''
				)
				, $atts
			)
		);
		$custom_class = '';
		if(isset($atts['class']))
		{
		    $custom_class = $atts['class'];
		}
		$errors					= new wp_error();
		$javo_query				= new javo_ARRAY( $_GET );
		$javo_redirect			= home_url();

		// Get Item Tages
		{
			$javo_all_tags		= "";
			if( $input_field == 'keyword' ) {

				foreach( get_tags( Array( 'fields' => 'names' ) ) as $tags )
				{
					$javo_all_tags	.= "{$tags}|";
				}
				$javo_all_tags		= substr( $javo_all_tags, 0, -1 );
			}
		}

		// Get Item Tages
		{
			if(
				(int) $action > 0 &&
				! is_archive() &&
				! is_search()
			){
				$javo_redirect = apply_filters( 'javo_wpml_link', $action );
			}
		}

		// No setup result page
		if( $errors->get_error_code() != "" )
		{
			ob_start();
			echo "<div class='container'><div class='alert alert-warning' role='alert'>{$errors->get_error_message()}</div></div>";
			return ob_get_clean();
		}
		ob_start();
		?>

		<div class="container javo-single-search-form <?php echo $custom_class;?>" id="javo-single-search-form">
			<form role="form" data-javo-search-form class="search-form-type">

				<div class="search-type-a-inner">


					<div class="search-box-inline search-box-inline-position">
						<input type="text" class="search-type-inner-position-text" placeholder="<?php echo $place_holder;?>">
						<div class="inner-marker"><i class="fa fa-map-marker javo-current-position cursor-pointer"></i></div>
						<div class="search-box-inline search-box-inline-position-button">
							<button type="submit" class="search-submit admin-color-setting"
							<?php if($single_search_button_color!=''){
								echo 'style="background-color:'.$single_search_button_color.';"';
							} ?>	>
								<i class="fa fa-search search-box-inline-icon cursor-pointer"></i>
								<span class="search-submit-text"><?php _e("search", 'javo_fr');?></span>
							</button>
						</div><!-- /.col-md-2 -->

					</div><!-- /.col-md-2 -->

				</div> <!-- search-type-inner -->

			</form><!--search-form-type-->

			<fieldset>
				<input type="hidden" value="<?php echo (int)$action > 0 ? apply_filters( 'javo_wpml_link', $action) :null;?>" data-javo-search-action-template-url>
				<input type="hidden" value="<?php echo (int)$action > 0 ? 'data-javo-patch-form-for-template' : 'data-javo-patch-form-for-result';?>" data-javo-search-form-action-type>
				<input type="hidden" javo-map-all-tags value="<?php echo $javo_all_tags; ?>">
				<input type="hidden" javo-input-field-type value="<?php echo $input_field;?>">
			</fieldset>

			<!-- Search Result Page -->
			<form action="<?php echo home_url('/');?>" method="get" data-javo-patch-form-for-result>
				<input type="hidden" name="post_type" value="item">
				<input type="hidden" name="location" value="<?php echo $javo_query->get('location');?>" data-javo-sf-location>
				<input type="hidden" name="s" data-javo-sf-keyword>
			</form><!-- /data-javo-patch-form-for-result : Go to Archive Page -->

			<!-- Javo Map Template -->
			<form action="<?php echo $javo_redirect;?>" method="get" data-javo-patch-form-for-template>
				<input type="hidden" name="location" data-javo-sf-location-keyword>
				<input type="hidden" name="radius_key" data-javo-sf-google-keyword>
				<input type="hidden" name="keyword" data-javo-sf-keyword>
				<input type="hidden" name="geolocation" data-javo-sf-geolocation>
			</form><!-- /data-javo-patch-form-for-template : Go to Map -->
		</div>

		<script type="text/javascript">

		jQuery( function($){
			window.javo_single_search_func = {

				init: function()
				{

					this.el_org		= $( "[data-javo-search-form]" );
					this.el			= $( "[" + $("[data-javo-search-form-action-type]").val() + "]" );
					this.type		= this.el.closest( "#javo-single-search-form" ).find( "[javo-input-field-type]" ).val();
					this.tags		= $('[javo-map-all-tags]').val().toLowerCase().split( '|' );
					this.el_keyword = $("#javo-single-search-form input.search-type-inner-position-text");

					if( this.type == "keyword" )
					{
						this.setKeywordAutoComplete();
					}else{
						var javo_ac = new google.maps.places.Autocomplete( this.el_keyword[0] );
					}

					;$( document )
									.on( 'submit'	, this.el_org.selector, this.submit )
									.on( 'click'	, '.javo-current-position', this.position )
				}

				, setKeywordAutoComplete: function()
				{
					this.el_keyword.typeahead({
						hint			: false
						, highlight		: true
						, minLength		: 1
					}, {
						name			: 'tags'
						, displayKey	: 'value'
						, source		: this.keywordMatchesCallback( this.tags )
					});
				}

				, keywordMatchesCallback: function( tags )
				{
					return function keywordFindMatches( q, cb )
					{
						var matches, substrRegex;

						substrRegex		= new RegExp( q, 'i');
						matches			= [];

						$.each( tags, function( i, tag ){
							if( substrRegex.test( tag ) ){

								matches.push({ value : tag });
							}
						});
						cb( matches );
					}
				}

				, position: function( e )
				{
					e.preventDefault();

					var obj		= javo_single_search_func;

					if( $( this ).hasClass( 'active' ) )
					{
						obj.el.find( "[data-javo-sf-geolocation]" ).val( 0 );
						$( this ).removeClass( 'active' );
					}else{
						obj.el.find( "[data-javo-sf-geolocation]" ).val( 1 );
						$( this ).addClass( 'active' ).addClass( 'fa-spin' );
					}

					obj.submit(e);
				}

				, submit: function( e )
				{
					e.preventDefault();

					var obj		= javo_single_search_func;
					var el_org	= obj.el_org;
					var el		= obj.el;
					var type	= obj.type;
					var input	= el_org.find( "input.search-type-inner-position-text" );

					if( type == "keyword" )
					{
						// Keyword
						el.find( "[data-javo-sf-keyword]" ).val( input.val() );
					}else{
						// Locaiton
						el.find( "[data-javo-sf-google-keyword]" ).val( input.val() );
					}
					el.submit();
				}
			}
			window.javo_single_search_func.init();
		});
		</script>
		<?php
		return ob_get_clean();
	}
}
new javo_single_search_form();