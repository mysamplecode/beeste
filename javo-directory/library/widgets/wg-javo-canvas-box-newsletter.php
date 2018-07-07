<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class javo_Newsletter_for_Canvas extends WP_Widget
{
	/**
	 * Widget setup
	 */
	function __construct() {
		$widget_ops = array(
			'description' => __( 'Newsletter on canvas menu only', 'javo_fr' )
		);
        parent::__construct( 'javo_canvas_newsletter', __('[JAVO] Canvas Menu-Newletter','javo_fr'), $widget_ops );
	}
	/**
	 * Display widget
	 */
	function widget( $args, $instance )
	{
		global $jv_str;
		$mailchimp_str	= $jv_str[ 'mailchimp' ];
		$javo_var		= new javo_ARRAY( $instance );
		extract( $args, EXTR_SKIP );
		echo $before_widget;
		ob_start();
		?>
		<div class="container-fluid navmenu-fixed-right-canvas-fullwidth newsletter">
			<form role="form">
				<h4><?php _e( "Newsletter", 'javo_fr' ); ?></h4>
				<p><?php _e( "Subscribe and get the latest news about events and collections right to your inbox.", 'javo_fr'); ?></p>
				<div class="canvas-menu-newsletter-input-wrap">
					<input type="email" name="javo_email" placeholder="<?php _e("Your E-mail", 'javo_fr');?>"><i class="fa fa-location-arrow"></i>
				</div>
				<fieldset>
					<input type="hidden" name="ajaxurl" value="<?php echo admin_url( 'admin-ajax.php' );?>">
					<input type="hidden" name="javo_mailchimp_str_no_email" value="<?php echo $mailchimp_str[ 'no_email' ];?>">
					<input type="hidden" name="javo_mailchimp_security" value="<?php echo wp_create_nonce( "javo-mailchimp-shortcode" );?>">
					<input type="hidden" name="javo_mailchimp_list_id" value="<?php echo $javo_var->get('list_id');?>">
				</fieldset>
			</form>
		</div><!-- /.newsletter -->


		<script type="text/javascript">
		( function( $ ){
			var javo_wg_newletter_func = function()
			{
				if( ! window.javo_wg_newletter_instance )
				{
					window.javo_wg_newletter_instance	= true;
					this.event();
				}
			}

			javo_wg_newletter_func.prototype.forms		= '.navmenu-fixed-right-canvas-fullwidth.newsletter form';
			javo_wg_newletter_func.prototype.elements	= new Array(
				'input'
				, 'i.fa.fa-location-arrow'
			);
			javo_wg_newletter_func.prototype.ajaxurl	= $( "input[name='ajaxurl']" ).val();


			javo_wg_newletter_func.prototype.getParams	= function()
			{
				return {
					action		:	'javo_mailchimp'
					, nonce		: this.form.find( "[name='javo_mailchimp_security']" ).val()
					, list		: this.form.find( "[name='javo_mailchimp_list_id']" ).val()
					, mc_email	: this.form.find( "[name='javo_email']" ).val()
				};
			}

			javo_wg_newletter_func.prototype.event = function()
			{
				$( document )
					.on( 'submit', this.forms, this.submit )
					.on( 'click', this.forms + " i.fa.fa-location-arrow", this.trigger_submit )
				return this;
			}

			javo_wg_newletter_func.prototype.setDisable	= function( toggle )
			{
				for( var el in this.elements )
				{
					var tar	= $( this.forms ).find( this.elements[el] );

					if( toggle )
					{
						tar
							.prop( 'disabled', true )
							.addClass( 'disabled' );
					}else{
						tar
							.prop( 'disabled', false )
							.removeClass( 'disabled' );
					}
				}
			}

			javo_wg_newletter_func.prototype.trigger_submit = function( e )
			{
				e.preventDefault();
				$( this ).closest( 'form' ).trigger( 'submit' );
			}

			javo_wg_newletter_func.prototype.submit = function( e )
			{				
				e.preventDefault();

				var obj			= new javo_wg_newletter_func;
				var form		= $( this );
				var el_email	= form.find( "input[name='javo_email']" );

				if( ! el_email.val() )
				{
					$.javo_msg( {
						content	: $( "[name='javo_mailchimp_str_no_email']" ).val()
						, delay	: 500
						, close	: false
					} );
					return false;
				}
				obj.subscribe( form );
			}

			javo_wg_newletter_func.prototype.subscribe = function( form )
			{
				var obj			= new javo_wg_newletter_func;
				this.form		= form;
				this.setDisable( true );
				$.post(
					this.ajaxurl
					, this.getParams()
					, function( xhr ) {
						$.javo_msg({ content: xhr.message, delay: 10000 });
					}
					, "json"
				)
				.always( function() {
					obj.setDisable( false );
				} );
			}

			new javo_wg_newletter_func;

		})( jQuery );
		</script>

		<?php
		ob_end_flush();
		echo $after_widget;
	}

	/**
	 * Widget setting
	 */
	function form( $instance )
	{
		global $javo_tso;
		$javo_var			= new javo_ARRAY( $instance );
		$javo_chimp_lists	= apply_filters( 'javo_mail_chimp_get_lists', null );

		ob_start();
		?>
		<dl>
			<dt>
				<label><?php _e( "Mail Chimp List", 'javo_fr'); ?></label>
			</dt>
			<dd>
				<?php
				if( false !== $javo_tso->get( 'mailchimp_api', false ) ) :
					?>
					<select name="<?php echo esc_attr( $this->get_field_name( 'list_id' ) ); ?>">
						<?php
						if( !empty( $javo_chimp_lists ) && is_Array( $javo_chimp_lists ) )
						{
							foreach( $javo_chimp_lists as $label => $value ) {
								$is_checked	= selected( $value == $javo_var->get( 'list_id'), true, false );
								echo "<option value='{$value}'{$is_checked}>{$label}</option>";
							}
						} ?>
					</select>
					<?php
				else:
					?>
					<div class="notice notice-warning">
						<?php _e( "Theme Setting > General > Plugin > API KEY (Please add your API key)", 'javo_fr'); ?>
					</div>

					<input type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'list_id' ) ); ?>">
					<?php
				endif; 
				?>
			</dd>
		</dl>

		<?php
		ob_end_flush();
	}
}
/**
 * Register widget.
 *
 * @since 1.0
 */
add_action( 'widgets_init', create_function( '', 'register_widget( "javo_Newsletter_for_Canvas" );' ) );