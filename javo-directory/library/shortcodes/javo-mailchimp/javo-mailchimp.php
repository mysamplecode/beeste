<?php
class javo_mailchimp{

	public function __construct(){
		add_shortcode(	'javo_mailchimp'			, Array( __CLASS__, 'callback' ) );
		add_action( 'wp_footer'						, Array( __CLASS__, 'scripts' ) );
		add_action( 'wp_ajax_javo_mailchimp'		, Array( __CLASS__, 'response' ) );
		add_action( 'wp_ajax_nopriv_javo_mailchimp'	, Array( __CLASS__, 'response' ) );
	}

	public static function callback( $atts, $content='' )
	{

		global $javo_tso;
		extract( shortcode_atts(Array(

			/*	Describe :		list_id
			*	Type :			String( Empty / 'map' )
			*/
			'list_id'			=> ''

		), $atts));

		$errors				= new wp_error;
		$javo_cmp_action	= trailingslashit( home_url() );

		// Parameter

		// API KEY
		if( '' === ( $javo_tso->get( 'mailchimp_api', '' ) ) )
		{
			$errors->add( 'mailchimp-no-api', sprintf( "<strong>%s</strong><br>%s"
				, __("The API Key does not exist", 'javo_fr')
				, __("Please insert API Key in Theme Settings > General > API Key", 'javo_fr')
			) );
		}
		 
		// MAILCHIMP LIST
		if( '' === $list_id )
		{
			$errors->add( 'mailchimp-no-list', sprintf( "<strong>%s</strong><br>%s"
				, __("List is not selected.", 'javo_fr')
				, __("Please select list in Shortcode Edit.", 'javo_fr')
			) );
		}


		ob_start();
		if( ! count( $errors->get_error_codes() ) > 0 ):
			?>
			<div id="javo_mailchimp" class="javo-mailchimp-container">
				<form id="newsletter-form" name="newsletter-form" data-url="<?php echo $javo_cmp_action;?>" method="post" role="form">

					<div class="javo-mailchimp-wrap">
						<div class="form-group javo-mailchimp-inner">

							<div class="input-group input-group-sm javo-mailchimp-inner-name">
								<!--<label class="input-group-addon" for="javo_cmp_name">
									<span class="glyphicon glyphicon-envelope"></span>
								</label>-->
								<input type="text" name="yname" id="javo_cmp_name" class="javo-mailchimp-inner-name-input" placeholder="<?php _e("Your name", 'javo_fr');?>" class="form-control" required>
							</div><!-- /.input-group -->

							<div class="input-group input-group-sm javo-mailchimp-inner-mail">
								<input type="email" name="mc_email" id="javo_cmp_email" class="javo-mailchimp-inner-mail-input"placeholder="<?php _e("Your email", 'javo_fr');?>" class="form-control" required>
								<div class="form-group javo-mailchimp-inner-sand">

									<button type="submit" class="btn btn-primary javo-mailchimp-inner-sand-icon">
										<span><i class="fa fa-location-arrow"><?php _e("", 'javo_fr'); ?></i></span>
									</button>

								</div><!--javo-mailchimp-inner-sand-->
							</div><!-- /.input-group -->


						</div><!-- /.form-group -->
					</div>




					<fieldset>
						<input type="hidden" name="javo_mailchimp_security" value="<?php echo wp_create_nonce( "javo-mailchimp-shortcode" );?>">
						<input type="hidden" name="cm_list" value="<?php echo $list_id; ?>">
						<input type="hidden" name="ajaxurl" value="<?php echo admin_url( "admin-ajax.php" ); ?>">
						<input type="hidden" name="no_msg" value="<?php _e("Failed to register", 'javo_fr'); ?>">
					</fieldset>
				</form>

			</div>
			<?php
		else:
			?>
			<div class="well well-sm">
				<span class="glyphicon glyphicon-exclamation-sign"></span>
				<?php
				foreach( $errors->get_error_messages() as $messages ){
					echo "<p>{$messages}</p>";
				} ?>
			</div>
			<?php
		endif; // End if
		return ob_get_clean();
	}

	public static function scripts()
	{
		ob_start();
		?>
		<script type="text/javascript">
		jQuery( function( $ ){

			if( typeof window.javo_mailchimp_script == "undefined" )
			{







				window.javo_mailchimp_script = {

					init: function(){

						$( document ).on( 'submit', 'form#newsletter-form', function( e ){

							e.preventDefault();

							var wp_once	= $( this ).find( "input[name='javo_mailchimp_security']" ).val();
							var cm_list	= $( this ).find( "input[name='cm_list']" ).val();
							var ajaxurl	= $( this ).find( "input[name='ajaxurl']" ).val();
							var ok_msg	= $( this ).find( "input[name='ok_msg']" ).val();
							var no_msg	= $( this ).find( "input[name='no_msg']" ).val();


							var param	= {
								action		: 'javo_mailchimp'
								, mc_email	: $('#javo_cmp_email').attr('value')
								, yname		: $('#javo_cmp_name').attr('value')
								, list		: cm_list
								, nonce		: wp_once
							};

							$.ajax({
								url			: ajaxurl
								, type		: 'POST'
								, data		: param
								, dataType	: 'JSON'
								, xhr: function(){

									var xhr = new window.XMLHttpRequest();

									xhr.addEventListener( 'progress', function( e ){

										console.log( e );

									}, false );

									return xhr;

								}

								, success	: function( xhr )
								{

									jQuery.javo_msg({ content: xhr.message, delay:10000 });
									console.log( xhr );

								}

								, error: function( xhr )
								{

									jQuery.javo_msg({ content: no_msg, delay:10000 });
									console.log( xhr.responseText );

								}
							});
						});
					}
				}
			}
			window.javo_mailchimp_script.init();
		} );
		</script>
		<?php
		ob_end_flush();
	}
	public static function response()
	{

		global $javo_tso;
		check_ajax_referer( 'javo-mailchimp-shortcode', 'nonce');

		$javo_query		= new javo_ARRAY( $_POST );

		if( '' !== ( $javo_api_key = $javo_tso->get( 'mailchimp_api', '' ) ) )
		{
			include_once JAVO_SYS_DIR.'/functions/MCAPI.class.php';
			$mcapi		= new MCAPI( $javo_api_key );

			$name		= explode(" ", $javo_query->get('yname', '') );
			$fname		= !empty( $name[0] ) ? $name[0] : '';
			unset( $name[0] );

			$lname		= !empty($name) ? join( ' ', $name) : '';

			$merge_vars	= array(
				'FNAME'		=> $fname
				, 'LNAME'	=> $lname
			);

			$answer = $mcapi->listSubscribe( $javo_query->get( 'list', '' ), $javo_query->get( 'mc_email', '' ), $merge_vars );
			if( $mcapi->errorCode )
				{
				// An error ocurred, return error message
				$msg = $mcapi->errorMessage;
			}else{
				// It worked!
				$msg = __('Success!&nbsp; Check your inbox or spam folder for a message containing a confirmation link.','javo_fr');
			}			
		}else{
			$msg = __( "Please insert API Key in Theme Settings > General > API Key", 'javo_fr' );
		}

		die( json_encode( Array( 'message' => $msg ) ) );
	}
}
new javo_mailchimp();
