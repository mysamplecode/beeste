<?php
class javo_custom_paid_memberships_plugin
{

	// Last this file of Modify Theme Version
	protected $ver = '1.0';

	// Database Custom Columns
	public static $columns = Array(
		'javo_allow_post'				=> "bigint(20) unsigned not null default 0"
		, 'javo_expire_day'				=> "bigint(20) unsigned not null default 0"
		, 'javo_cnt_featured'			=> "bigint(20) unsigned not null default 0"
		, 'javo_allow_post_unliimit'	=> "tinyint(1) unsigned not null default 0"
		, 'javo_expire_unliimit'		=> "tinyint(1) unsigned not null default 0"
		, 'javo_featured_unliimit'		=> "tinyint(1) unsigned not null default 0"
		, 'javo_after_action'			=> "tinyint(1) unsigned not null default 0"
		, 'javo_popular'				=> "tinyint(1) unsigned not null default 0"
	);

	const DEBUG_MODE = true;

	// Void main;
	function __construct()
	{
		// MODIFY PAID-MEMBERSHIPS-PRO TABLES.

		if( !defined('PMPRO_VERSION') )
			return false;

		self::modify_pmp_table();
		add_action( 'pmpro_save_membership_level'					, Array( __CLASS__, 'save_membership_level_callback' ) );
		add_action( 'pmpro_membership_level_after_other_settings'	, Array( __CLASS__, 'after_other_settings_callback' ) );
		add_action( 'pmpro_after_order_settings'						, Array( __CLASS__, 'after_admin_order_settings_callback' ) );
		add_action( 'pmpro_orders_extra_cols_header'					, Array( __CLASS__, 'custom_orders_cols_header' ) );
		add_action( 'pmpro_orders_extra_cols_body'					, Array( __CLASS__, 'custom_orders_cols_body' ) );
		add_action( 'pmpro_added_order'								, Array( __CLASS__, 'added_order_callback' ) );

		// AJAX
		add_action( 'wp_ajax_nopriv_item_publisher'					, Array( __CLASS__, 'item_publisher_callback' ) );
		add_action( 'wp_ajax_item_publisher'							, Array( __CLASS__, 'item_publisher_callback' ) );

		// Expire
		add_action( 'template_include'								, Array( __CLASS__, 'post_expire_callback' ), 99);

		// No Level user
		add_filter( 'javo_dashboard_custom_template_url'			, Array( __CLASS__, 'before_add_item_level_confirm' ), 50, 2 );
	}

	public static function added_order_callback( $morder )
	{
		global $wpdb;

		// Update
		$wpdb->update(

			// TABLE
			$wpdb->pmpro_membership_orders

			// SET
			, Array(
				'javo_allow_post'			=> esc_sql( $morder->membership_level->javo_allow_post )
				, 'javo_expire_day'			=> esc_sql( $morder->membership_level->javo_expire_day )
				, 'javo_cnt_featured'		=> esc_sql( $morder->membership_level->javo_cnt_featured )
				, 'javo_popular'			=> esc_sql( $morder->membership_level->javo_popular )
			)

			// WHERE
			, Array( 'code'	=> $morder->code )
		);
	}

	public static function modify_pmp_table()
	{
		global $wpdb;

		// MODIFY TABLE 'pmpro_membership_levels', 'pmpro_membership_orders'
		foreach( self::$columns as $column => $attribute )
		{
			foreach(
				Array(
					$wpdb->pmpro_membership_levels
					, $wpdb->pmpro_membership_orders
				)
				as $table_name
			){
				$javo_column_exists = $wpdb->prepare( "
					SHOW COLUMNS
					FROM
						{$table_name}
					LIKE
						%s"
					, $column
				);

				if( $wpdb->get_var( $javo_column_exists ) == null )
				{
					// Todo : Custom Table code Here.
					$wpdb->query( "alter table $wpdb->pmpro_membership_levels add {$column} {$attribute} ");
					$wpdb->query( "alter table $wpdb->pmpro_membership_orders add {$column} {$attribute} ");
				} // End if

			} // End Foreach
		} // End Foreach
	}

	public static function get_level_info( $level_id )
	{
		global $wpdb;

		$javo_return = $wpdb->get_row("
			SELECT
				*
			FROM
				$wpdb->pmpro_membership_levels
			WHERE
				id = '$level_id'
			LIMIT
				1"
			, OBJECT
		);

		if( empty( $javo_return ) )
		{
			$javo_return = new stdClass();

			// Initialize Member Variables
			$javo_return->javo_allow_post			= 0;
			$javo_return->javo_expire_day			= 0;
			$javo_return->javo_cnt_featured			= 0;
			$javo_return->javo_featured_unliimit	= 0;
			$javo_return->javo_allow_post_unliimit	= 0;
			$javo_return->javo_expire_unliimit		= 0;
			$javo_return->javo_after_action			= 0;
			$javo_return->javo_popular				= 0;
		}
		return $javo_return;
	}

	public static function get_order_info( $order_id )
	{
		global $wpdb;

		$javo_return = $wpdb->get_row("
			SELECT
				*
			FROM
				$wpdb->pmpro_membership_orders
			WHERE
				code = '$order_id'
			LIMIT
				1"
			, OBJECT
		);

		if( empty( $javo_return ) )
		{
			$javo_return = new stdClass();

			// Initialize Member Variables
			$javo_return->javo_allow_post = 0;
			$javo_return->javo_expire_day = 0;
			$javo_return->javo_cnt_featured = 0;
		}
		return $javo_return;
	}

	public static function before_add_item_level_confirm( $template_path, $query_slug )
	{
		global
			$javo_tso
			, $wpdb;

		$is_paid_mobe	= $javo_tso->get( 'payment', '' ) === 'use';
		$current_membership_id = $wpdb->query(
			$wpdb->prepare( "
				SELECT
					membership_id
				FROM
					$wpdb->pmpro_memberships_users
				WHERE
					user_id = %s
				AND
					status = 'active'
				LIMIT
					1"
			, get_current_user_id()
			)
		);

		if( $query_slug === JAVO_ADDITEM_SLUG && $is_paid_mobe && (int) $current_membership_id === 0 && ! get_query_var('edit')  )
		{
			wp_safe_redirect( esc_url( home_url( $javo_tso->get( 'payment_price_table_url' ) ) ) );
			return;
		}

		return $template_path;
	}


	static function save_membership_level_callback( $saveid )
	{
		global $wpdb;

		$javo_update = Array();

		foreach( self::$columns as $column => $attribute )
		{
			$javo_value = isset( $_REQUEST[ $column ] ) ? addslashes( $_REQUEST[ $column ] ) : null;
			$javo_update[ $column ] = esc_sql( $javo_value );
		}

		$wpdb->update( $wpdb->pmpro_membership_levels, $javo_update, Array('ID' => $saveid) );
	}

	static function after_other_settings_callback()
	{
		global $wpdb;

		// Initialize Save Id
		$saveid = 0;

		if(
			isset( $_REQUEST['edit'] ) &&
			0 < (int)$_REQUEST['edit']
		){
			$saveid = $_REQUEST['edit'];
		}elseif(
			isset($_REQUEST['copy']) &&
			0 < (int)$_REQUEST['copy']
		){
			$saveid =  $_REQUEST['copy'];
		}

		$level = self::get_level_info( $saveid );

		ob_start();
		?>
		<h3 class="topborder"><?php _e('[Javo] Settings for Posting Items', 'javo_fr');?></h3>
		<table class="form-table javo-pmp-custom-table">
			<tbody>
				<tr>
					<th scope="row" valign="top"><label><?php _e('Amount of Featured Items', 'javo_fr');?>:</label></th>
					<td>
						<div data-toggle>
							<label>
								<input type="checkbox" name="javo_featured_unliimit" value="1" <?php checked( 1 == (int)$level->javo_featured_unliimit );?>>
								<?php _e('Unlimited', 'javo_fr');?>
							</label>
						</div>
						<input type="text" name="javo_cnt_featured" value="<?php echo (int)$level->javo_cnt_featured;?>">
						<small><?php _e('Posts', 'javo_fr');?></small>
						<div class="description"><small><?php _e('Required : Numbers Only', 'javo_fr');?></small></div>
					</td>
				</tr>

				<tr class="">
					<th scope="row" valign="top"><label><?php _e('Amount of Items', 'javo_fr');?>:</label></th>
					<td>
						<div data-toggle>
							<label>
								<input type="checkbox" name="javo_allow_post_unliimit" value="1" <?php checked( 1 == (int)$level->javo_allow_post_unliimit );?>>
								<?php _e('Unlimited', 'javo_fr');?>
							</label>
						</div>
						<input type="text" name="javo_allow_post" value="<?php echo (int)$level->javo_allow_post;?>">
						<small><?php _e('Posts', 'javo_fr');?></small>
						<div class="description"><small><?php _e('Required : Numbers Only', 'javo_fr');?></small></div>
					</td>
				</tr>

				<tr class="">
					<th scope="row" valign="top"><label><?php _e('Duration of Posts', 'javo_fr');?>:</label></th>
					<td>
						<div data-toggle>
							<label>
								<input type="checkbox" name="javo_expire_unliimit" value="1" <?php checked( 1 == (int)$level->javo_expire_unliimit );?>>
								<?php _e('Unlimited', 'javo_fr');?>
							</label>
						</div>
						<input type="text" name="javo_expire_day" value="<?php echo (int)$level->javo_expire_day;?>">
						<small><?php _e('Days', 'javo_fr');?></small>
						<div class="description"><small><?php _e('Required : Numbers Only', 'javo_fr');?></small></div>
					</td>
				</tr>


				<tr>
					<th scope="row" valign="top"><label><?php _e('Status after being Posted', 'javo_fr');?>:</label></th>
					<td>
						<div>
							<label>
								<input type="radio" name="javo_after_action" value="0" <?php checked( 0 == (int)$level->javo_after_action );?>>
								<?php _e('Published', 'javo_fr');?>
							</label>
						</div>
						<div>
							<label>
								<input type="radio" name="javo_after_action" value="1" <?php checked( 1 == (int)$level->javo_after_action );?>>
								<?php _e('Pending', 'javo_fr');?>
							</label>
						</div>
					</td>
				</tr>

				<tr>
					<th scope="row" valign="top"><label><?php _e('Add Popular Tag', 'javo_fr');?>:</label></th>
					<td>
						<div>
							<label>
								<input type="checkbox" name="javo_popular" value="1" <?php checked( 1 == (int)$level->javo_popular );?>>
								<?php _e('Popular', 'javo_fr');?>
							</label>
						</div>
					</td>
				</tr>


			</tbody>
		</table>
		<script type="text/javascript">

			jQuery( function($){

				var javo_pmp_custom_func = {

					init:function(){

						this.checked();
						$(document).on('click', 'input[type="checkbox"]', this.checked);
					}
					, checked:function(){

						$(document).find('div[data-toggle]').each( function(){
							var _tar_chk	= $(this).find('input[type="checkbox"]');
							var _tar_text	= $(this).next('input[type="text"]');

							if( _tar_chk.is(':checked') )
							{
								_tar_text.prop('disabled', true);
							}else{
								_tar_text.prop('disabled', false);
							}
						});
					}
				};
				javo_pmp_custom_func.init();
			});
		</script>
		<?php
		ob_end_flush();
	}

	static function after_admin_order_settings_callback( $order )
	{
		global $wpdb;

		// GET ORDER CODE
		$invoice_code = $order->code;

		if( isset( $_REQUEST['save'] ) )
		{
			$javo_allow_post	= isset( $_REQUEST['javo_allow_post'] )		? $_REQUEST['javo_allow_post']		: 0;
			$javo_expire_day	= isset( $_REQUEST['javo_expire_day'] )		? $_REQUEST['javo_expire_day']		: 0;
			$javo_cnt_featured	= isset( $_REQUEST['javo_cnt_featured'] )	? $_REQUEST['javo_cnt_featured']	: 0;

			// Update
			$wpdb->update(
				// TABLE
				$wpdb->pmpro_membership_orders

				// SET
				, Array(
					'javo_allow_post'			=> $javo_allow_post
					, 'javo_expire_day'			=> $javo_expire_day
					, 'javo_cnt_featured'		=> $javo_cnt_featured
				)

				// WHERE
				, Array(
					'code'						=> $invoice_code
				)
			);
		}

		// GET ORDER INFORMATIONS
		$javo_order = self::get_order_info( $invoice_code );
		ob_start();
		?>
		<tr>
			<th scope="row" valign="top"><label><?php _e('Amount of Featured Items', 'javo_fr');?>:</label></th>
			<td>
				<input type="text" name="javo_cnt_featured" value="<?php echo (int)$javo_order->javo_cnt_featured;?>">
				<small><?php _e('Posts', 'javo_fr');?></small>
				<div class="description"><small><?php _e('Required : Numbers Only', 'javo_fr');?></small></div>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><label><?php _e('Amount of Items', 'javo_fr');?>:</label></th>
			<td>
				<input type="text" name="javo_allow_post" value="<?php echo (int)$javo_order->javo_allow_post;?>">
				<small><?php _e('Posts', 'javo_fr');?></small>
				<div class="description"><small><?php _e('Required : Numbers Only', 'javo_fr');?></small></div>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><label><?php _e('Duraton of Posts', 'javo_fr');?>:</label></th>
			<td>
				<input type="text" name="javo_expire_day" value="<?php echo (int)$javo_order->javo_expire_day;?>">
				<small><?php _e('Days', 'javo_fr');?></small>
				<div class="description"><small><?php _e('Required : Numbers Only', 'javo_fr');?></small></div>
			</td>

		</tr>
		<?php
		ob_end_flush();
	}

	static function custom_orders_cols_header( $order )
	{
		printf("<th>%s</th><th>%s</th><th>%s</th>"
			, __('Posts', 'javo_fr')
			, __('Expire', 'javo_fr')
			, __('Featured', 'javo_fr')
		);
	}

	static function custom_orders_cols_body( $order )
	{
		global $wpdb;

		// GET ORDER CODE

		$invoice_code = $order->code;

		// GET ORDER INFORMATIONS
		$javo_order = self::get_order_info( $invoice_code );

		printf("<td>%s</td><td>%s</td><td>%s</td>"
			, $javo_order->javo_allow_post . ' ' . __('posts', 'javo_fr')
			, $javo_order->javo_expire_day . ' ' . __('days', 'javo_fr')
			, $javo_order->javo_cnt_featured . ' ' . __('posts', 'javo_fr')
		);
	}

	private static function init_level( $user_id )
	{
		global $wpdb;

		if( (int)$user_id > 0 )
		{

			$result = $wpdb->query(
				$wpdb->prepare( "
					UPDATE
						$wpdb->pmpro_memberships_users
					SET
						status = %s
					WHERE
						user_id = %s
					AND
						status = 'active'"
				, 'inactive'
				, $user_id
				)
			);

			if( $result !== false )
			{
				return true;
			}
		}
		return false;
	}

	public static function debug( $result )
	{
		if( !self::DEBUG_MODE ){ return false; }

		ob_start();
		var_dump( $result );
		$content = ob_get_clean();
		$fn = @fopen( dirname( __FILE__ ) . '/test.log', 'a+' );
		@fwrite( $fn, $content );
		@fclose( $fn );
	}

	static function orders()
	{
		global
			$wpdb
			, $current_user;

		if( !defined('PMPRO_VERSION') ){ return false; }

		$cur_lv = $current_user->membership_level;

		$javo_query = new javo_ARRAY( $_POST );

		if( (int)wp_get_current_user()->ID <= 0 ){ return false; }
		if( $current_user->membership_level == "" )
		{
			ob_start();
			?>
			<div class="alert alert-info">
				<strong> <?php _e("You don't have any membership level. please select one of the membership levels.", 'javo_fr');?> </strong>
			</div><!-- /.alert.alert-info -->
			<?php
			ob_end_flush();
			return false;
		}

		$post_id = $javo_query->get('post_id', 0);
		$sql = $wpdb->prepare("
			SELECT
				orders.membership_id
				, orders.javo_allow_post
				, orders.code
				, orders.javo_expire_day
				, orders.javo_cnt_featured
				, orders.timestamp
				, levels.name
				, levels.javo_allow_post_unliimit
				, levels.javo_expire_unliimit
				, levels.javo_featured_unliimit
			FROM
				$wpdb->pmpro_membership_orders orders
			LEFT JOIN
				$wpdb->pmpro_membership_levels levels
			ON
				orders.membership_id = levels.id
			WHERE
				( orders.javo_allow_post > 0 OR levels.javo_allow_post_unliimit = 1 )
			AND
				orders.user_id='%s'
			AND
				orders.status='success'
			"
			, wp_get_current_user()->ID
		);
		$orders = $wpdb->get_results( $sql, OBJECT );

		ob_start();
		?>
		<div class="panel">
			<table class="table table-striped table-hover">
				<tr class="warning">
					<th class="text-center"><?php _e('Order No.', 'javo_fr');?></th>
					<th class="text-center"><?php _e('Level Type', 'javo_fr');?></th>
					<th class="text-center"><?php _e('Posts Remaining', 'javo_fr');?></th>
					<th class="text-center"><?php _e('Duration', 'javo_fr');?></th>
					<th class="text-center"><?php _e('Featured Item', 'javo_fr');?></th>
					<th class="text-center"><?php _e('Purchased Date', 'javo_fr');?></th>
					<th></th>
				</tr>
				<?php
				if(
					isset( $cur_lv->initial_payment ) &&
					(float)$cur_lv->initial_payment > 0
				){
					if( !empty( $orders ) )
					{
						foreach( $orders as $order )
						{
							?>
							<tr class="javo-order-row">
								<td class="text-center">
									<?php echo $order->code;?>
								</td>
								<td class="text-center"><?php echo $order->name;?></td>
								<td class="text-center">
									<?php
									if( $order->javo_allow_post_unliimit ){
										_e('Unlimited', 'javo_fr');
									}else{
										printf(__("%s posts", 'javo_fr'), $order->javo_allow_post);
									} ?>
								</td>
								<td class="text-center">
									<?php
									if( $order->javo_expire_unliimit )
									{
										_e('Unlimited', 'javo_fr');
									}else{
										printf(__("%s day(s)", 'javo_fr'), $order->javo_expire_day);
									} ?>
								</td>
								<td class="text-center">
									<div>
										<label>
											<input type="checkbox" data-featured>
											<?php _e('Set as Featured', 'javo_fr');?>
										</label>
									</div>
									<?php
									if( $order->javo_featured_unliimit )
									{
										_e('Unlimited', 'javo_fr');
									}else{
										printf(__("%s posts", 'javo_fr'), $order->javo_cnt_featured);
									} ?>
								</td>
								<td class="text-center"><?php echo date('Y-m-d', strtotime( $order->timestamp ));?></td>
								<td class="text-center">
									<a href="" class="btn btn-default btn-xs" data-code="<?php echo $order->code;?>">
										<span class="glyphicon glyphicon-ok"></span>
										<?php _e('Submit', 'javo_fr');?>
									</a>
								</td>
							</tr>
							<?php
						// END FOREACH
						}
					}else{ ?>
				</table>
				<table class="table">
					<tr>
						<td class="text-center"><?php _e('Order not found', 'javo_fr');?></td>
					</tr>
						<?php
					// END IF
					}
				}else{ ?>
				</table>
				<table class="table">
					<tr>
						<td class="success text-center">
							<span>
								<?php _e('Submit as free ', 'javo_fr');?>
							</span>
							<a class="btn btn-default btn-md" data-code="free">
								<span class="glyphicon glyphicon-cloud"></span>
								<?php _e('Free', 'javo_fr');?>
							</a>
						</td>
					</tr>
				<?php } // END IF ?>

				</table>
		</div>

		<!-- Parameters -->
		<fieldset>
			<input type="hidden" name="post_id" value="<?php echo $post_id;?>">



		</fieldset>

		<script type="text/javascript">

			jQuery( function( $ ){

				var javo_add_item_orders = {

					options:{
						ajax:{
							url: "<?php echo admin_url('admin-ajax.php');?>"
							, type: 'post'
							, dataType:'json'
							, error:function( response )
							{
								$.javo_msg({ content: "<?php _e('Server Error', 'javo_fr');?>", delay:5000 });
								console.log( response.responseText );
							}
						}
					}

					, init: function()
					{
						this.attr	= 'a[data-code]';
						this.el		= $( this.attr );

						$(document).on( 'click', this.attr, this.publish );
					}

					, restore: function()
					{
						this.el.button('reset').removeClass('disabled');
					}

					, publish: function(e)
					{
						e.preventDefault();

						var el_featured = $(this).closest('.javo-order-row').find('input[type="checkbox"][data-featured]');

						var obj = javo_add_item_orders;

						var lParam = {
							action		: 'item_publisher'
							, code		: $(this).data('code')
							, post_id	: $('input[name="post_id"]').val()
							, featured	: el_featured.is(":checked")
						}

						if( $(this).hasClass('disabled') ){ return false; }
						$('a[data-code]').addClass('disabled');
						$(this).button('loading');

						obj.options.ajax.data = lParam;

						obj.options.ajax.success = function( response )
						{
							if( response.state != 'success' )
							{
								$.javo_msg({ content: response.comment, delay: 100000 }, function()
								{
									obj.restore();
								});
							}else{

								$.javo_msg({ content: "Your item has been successfully submitted.", delay: 100000 }, function()
								{
									document.location.href = response.permalink;
								});
							}
						}
						$.ajax( obj.options.ajax );
					}
				};
				javo_add_item_orders.init();

			} );

		</script>


		<?php

		ob_end_flush();
	}
	public static function response_err( $response_comment )
	{
		echo json_encode( Array(
			'state'			=> 'fail'
			, 'comment'		=> $response_comment
		) );
		die();
	}
	public static function item_publisher_callback()
	{
		global
			$wpdb
			, $current_user;

		$javo_query		= new javo_ARRAY( $_POST );
		$response		= Array();

		$level_id = !empty( $current_user->membership_level ) ? $current_user->membership_level->ID : 0;

		$order = self::get_order_info( $javo_query->get('code') );
		$level = self::get_level_info( $level_id );

		// Price
		$javo_free = isset( $level->initial_payment ) && (float)$level->initial_payment > 0 ? false : true;

		// After Action
		$is_pending = !( isset( $level->javo_after_action ) && !$level->javo_after_action );

		// Set Featured
		$is_featured = $javo_query->get('featured', "false") == "true" ? true : false ;

		// Checking for invalid order code.
		if( $javo_query->get('code', null) == null )
		{
			self::response_err( __("Can't get order code for available post.", 'javo_fr') );
		}

		// Checking for invalid target items.
		if( (int)$javo_query->get('post_id', 0) <= 0 )
		{
			self::response_err( __('Failed to get item number.', 'javo_fr') );
		}

		// Checking for have an allow posts amount.
		if( !( $javo_free || $level->javo_allow_post_unliimit ) )
		{
			if( (int)$order->javo_allow_post <= 0 )
			{
				self::response_err( __("Posts Remaining balance is now 0.", 'javo_fr') );
			}
		}

		// checking for amount of featured available
		if( $is_featured )
		{
			if( ! $level->javo_featured_unliimit )
			{
				if( (int)$order->javo_cnt_featured <= 0 )
				{
					self::response_err( __("Featured Item balance is now 0.", 'javo_fr') );
				}
			}
		}

		// Item Status Update Action
		$post_id = wp_update_post( Array(
			'ID'				=> (int) $javo_query->get('post_id')
			, 'post_status'		=> $is_pending ? 'pending' : 'publish'
		) );

		if( (int) $post_id <= 0 )
		{
			self::response_err( $order->javo_allow_post );

		}else{

			if( $javo_free ){
				update_post_meta( $post_id, 'javo_paid_state', 'free');
			}else{


				// IF NOT UNLIMITED POST
				if( !$level->javo_allow_post_unliimit )
				{

					// Use available post
					$sql = $wpdb->prepare("
						UPDATE
							{$wpdb->pmpro_membership_orders}
						SET
							javo_allow_post = javo_allow_post -1
						WHERE
							code='%s'"
						,  $order->code
					);
					$wpdb->query( $sql );

					// If after it work to count of allow posts is zero, initialize of current user level.
					$balance_ = $wpdb->get_var("
						SELECT
							javo_allow_post
						FROM
							{$wpdb->pmpro_membership_orders}
						WHERE
							code='{$order->code}'
					");

					if(
						(int) $balance_ <= 0 &&
						empty( $order->subscription_transaction_id )
					){
						if( self::init_level( wp_get_current_user()->ID ) === false )
						{
							self::response_err( __("Database Error", 'javo_fr') );
						};
					}
				}
				// IF NOT UNLIMITED FEATURED
				if( $is_featured && !$level->javo_featured_unliimit )
				{
					// Use available post
					$sql = $wpdb->prepare("
						UPDATE
							{$wpdb->pmpro_membership_orders}
						SET
							javo_cnt_featured = javo_cnt_featured - 1
						WHERE
							code='%s'"
						,  $order->code
					);
					$wpdb->query( $sql );
				}

				// IF NOT UNLIMITED EXPIRE
				if( !$level->javo_expire_unliimit )
				{
					$expire = date( 'YmdHis', strtotime( $order->javo_expire_day . ' days' ) );
				}else{
					$expire = "unlimited";
				}

				// IF SET FEATURED
				if( $is_featured )
				{
					update_post_meta( $post_id, 'javo_this_featured_item', 'use');
				}

				update_post_meta( $post_id, 'javo_expire_day', $expire);
				update_post_meta( $post_id, 'javo_paid_state', 'paid');
			}
			$response['permalink']		=  $is_pending ?
				home_url(JAVO_DEF_LANG.JAVO_MEMBER_SLUG.'/'.wp_get_current_user()->user_login.'/'.JAVO_ITEMS_SLUG) :
				get_permalink( $post_id );
			$response['state']			= 'success';
		}
		echo json_encode( $response );
		die();
	}
	public static function post_expire_callback( $template )
	{
		global
			$wp_query
			, $javo_tso
			, $post;

		if( !$wp_query->is_singular('item') ){ return $template; }
		if( $post->post_type != 'item' ){ return $template; }

		$javo_free_meta	= get_post_meta( $post->ID, 'javo_paid_state', true);
		$javo_free		= $javo_free_meta == 'free' ? true: false;

		$javo_expire_day = get_post_meta( $post->ID, 'javo_expire_day', true);

		if(
			(
				strtotime( $javo_expire_day ) >=
				strtotime( 'now' )
			) ||
			$javo_free ||
			( !$javo_free && $javo_expire_day == "unlimited") ||
			$javo_tso->get('payment', '') != 'use'
		){
			return $template;
		}else{
			return locate_template( 'content-expire.php' );
		}
	}
	public static function check_expire(
		$post_id
		, $expire_over=""
		, $classes="lable lable-default"
	){
		$javo_expire = get_post_meta( $post_id, 'javo_expire_day', true);

		$before = "<span class='{$classes}'>";
		$after = "</span>";

		if( (int)$post_id <= 0 || $javo_expire == '' ) return "-";

		if( strtolower($javo_expire) == "unlimited" ) return __("Unlimited", 'javo_fr');

		if( strtotime('now') <= strtotime( $javo_expire ) )
		{
			return date("Y-m-d H:i:s", strtotime($javo_expire));
		}else{
			return $expire_over == "" ? date("Y-m-d H:i:s", strtotime($javo_expire)) : $before.$expire_over.$after;
		}
	}
}
new javo_custom_paid_memberships_plugin();