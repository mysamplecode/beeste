<?php
class javo_search_form
{
	static $load_script = false;

	public function __construct(){
		add_shortcode(	'javo_search_form'	, Array( __CLASS__ ,'javo_search_form_callback' ) );
		add_action(	'wp_footer'				, Array( __CLASS__ ,'load_script_func' ) );
	}

	public static function load_script_func()
	{
		if( self::$load_script )
		{
			wp_enqueue_script( 'jQuery-chosen-autocomplete' );
			wp_enqueue_script( 'google-map' );
		}
	}

	public static function javo_search_form_callback( $atts, $content='' )
	{
		self::$load_script	= true;
		$javo_query			= new javo_ARRAY( $_GET );

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

					/*	Describe :		Hidden Border
					*	Type :			String( Empty / 'hide' )
					*/
					, 'display_border'	=> ''
				)
				, $atts
			)
		);

		/* Result Page */ {
			$javo_redirect		= home_url();

			if( (int) $action > 0 && ! is_archive() && ! is_search() ) {
				$javo_redirect = apply_filters( 'javo_wpml_link', $action );
			}
		}

		/* Hide Field */ {
			$javo_hide_el			= (Array) @explode( ',', $hide_field );
			$javo_hide_el			= (object) Array_flip( $javo_hide_el );
		}

		/* Styles */ {
			$javo_display_border	= $display_border === 'hide' || is_search() || is_archive() ? ' border-none' : null;

		}

		ob_start(); ?>
		<div class="container search-type-a-wrap">
			<form role="form" data-javo-search-form class="search-type-a-form" method="get">

				<div class="search-type-a-inner<?php echo $javo_display_border;?>">

					<?php if( ! isset( $javo_hide_el->keyword ) ) : ?>
						<div class="search-box-inline">
							<input
								type		= "text"
								class		= "search-a-items form-control"
								name		= "s"
								placeholder	= "<?php _e('Keyword', 'javo_fr');?>"
								value		= "<?php echo $javo_query->get( 'keyword', null );?>"
							>
						</div><!-- /.search-box-inline -->
					<?php endif; ?>

					<?php if( ! isset( $javo_hide_el->category ) ) : ?>
						<div class="search-box-inline">
							<select name="filter[item_category]" class="form-control">
								<option value=""><?php _e('Category', 'javo_fr');?></option>
								<?php
								echo apply_filters(
									'javo_get_selbox_child_term_lists'
									, 'item_category'
									, null
									, 'select'
									, $javo_query->get('category', 0)
									, 0
									, 0
								); ?>
							</select>
						</div><!-- /.search-box-inline -->
					<?php endif; ?>

					<?php if( ! isset( $javo_hide_el->location ) ) : ?>
						<div class="search-box-inline">
							<select name="filter[item_location]" class="form-control">
								<option value=""><?php _e('Location', 'javo_fr');?></option>
								<?php
								echo apply_filters(
									'javo_get_selbox_child_term_lists'
									, 'item_location'
									, null
									, 'select'
									, $javo_query->get('location', 0)
									, 0
									, 0
								); ?>
							</select>
						</div><!-- /.search-box-inline -->
					<?php endif; ?>

					<?php if( ! isset( $javo_hide_el->google ) && ! is_archive() && ! is_search() ) : ?>
						<div class="search-box-inline javo-search-form-geoloc">
							<input
								type="text"
								name="geoloc"
								class="form-control jv-search-location-input"
							>
							<i class="fa fa-map-marker javo-geoloc-trigger"></i>
						</div><!-- /.col-md-2 -->
					<?php endif; ?>

					<div class="search-box-inline">
						<button
							type="submit"
							class="jv-submit-button btn btn-primary admin-color-setting"
						>
						<i class="fa fa-search"></i>
						<?php _e('Search', 'javo_fr');?>
						</button>

					</div><!-- /.col-md-2 -->

				</div> <!-- search-type-a-inner -->

				<fieldset>
					<input
						type	= "hidden"
						value	= "<?php echo (int) $action > 0 ? apply_filters( 'javo_wpml_link', $action ) : null ;?>"
						javo-search-target
					>
				</fieldset>
			</form>

			<form class	= "hidden" role="search">
				<input type="hidden" name="post_type" value="item">
				<input type="hidden" name="category">
				<input type="hidden" name="location">
				<input type="hidden" name="keyword">
				<input type="hidden" name="geolocation">
				<input type="hidden" name="radius_key">
				<input type="hidden" name="s">
			</form>

		</div> <!-- container search-type-a-wrap -->

		<script type="text/javascript">
		jQuery( function( $ )
		{
			var javo_search_form_func = function()
			{
				this.elements = {
					origin		: 'form[data-javo-search-form]'
					, result	: '[data-javo-patch-form-for-result]'
					, template	: '[data-javo-patch-form-for-template]'
					, type		: '[data-javo-search-form-action-type]'
					, geo_trig	: '.javo-geoloc-trigger'
				}

				if( ! window.__JSF__INSTANCE__ )
				{
					window.__JSF__INSTANCE__ = true;
					this
						.init()
						.methods();
				}
			}

			javo_search_form_func.prototype = {

				constructor: javo_search_form_func

				, init : function()
				{
					this.setAutoCompleteObject();
					return this;
				}

				, setAutoCompleteObject : function()
				{
					var form			= $( this.elements.origin );
					var term_elements	= form.find( "select[name^='filter']" );
					var geo_elment		= form.find( "[name='geoloc']" )[0];

					term_elements.chosen({ search_contains: 1 });

					if( geo_elment )
						new google.maps.places.Autocomplete( geo_elment );
				}

				, methods : function()
				{
					$( document )
						.on( 'submit' , this.elements.origin, this.submit )
						.on( 'click' , this.elements.geo_trig, this.trigger_geo );

					return this;
				}

				, trigger_geo : function( e )
				{
					e.preventDefault();
					var _form		= $( this ).closest( 'form' );
					var form		= _form.next();

					_form
						.find( '*' )
						.addClass( 'disabled' )
						.attr( 'disabled', true );

					$( this ).addClass( 'fa-spin' );

					form.find( "[name='geolocation']" ).val(1);
					_form.submit();
				}

				, submit : function( e )
				{
					e.preventDefault();

					var _form		= $( this );
					var form		= _form.next();
					var __TAR__		= _form.find( '[javo-search-target]' ).val();

					if( __TAR__ ) {

						// Template
						form
							.prop({ action : __TAR__, method: 'post' })
							.find( "[name='s'], [name='post_type']" )
							.remove();

					} else {

						// Search Result
						form.prop({ action : '', method : 'get' });

					}

					; form
						.find( "[name='category']")
						.val( _form.find( "select[name='filter[item_category]']" ).val() )

					; form
						.find( "[name='location']")
						.val( _form.find( "select[name='filter[item_location]']" ).val() )

					; form
						.find( "[name='s'], [name='keyword']")
						.val( _form.find( "input[name='s']" ).val() )

					; form
						.find( "[name='radius_key']")
						.val( _form.find( "input[name='geoloc']" ).val() )

					form.submit();
				}


			};
			new javo_search_form_func;
		});
		</script>

		<?php
		return ob_get_clean();
	}
}
new javo_search_form();