<?php
global
	$post
	, $javo_tso;
$post = new stdClass();
$post->ID = 0;
$post->post_content = "[pmpro_levels]";
$item_verify = get_post( $_POST['post_id'] );
?>

<h2 class="page-header"><?php _e('Payment Summary', 'javo_fr');?></h2>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading"><?php echo $item_verify->post_title;?></div>
			<div class="panel-body">
				<?php echo apply_filters('the_content', $item_verify->post_content);?>
			</div><!-- /.panel-body -->
			<table class="table">
				<tr>
					<th width="20%"><?php _e('Post Status', 'javo_fr');?></th>
					<td>
						<span class="label label-<?php echo $item_verify->post_status == 'publish' ? 'primary' : 'default';?>">
							<?php echo strtoupper($item_verify->post_status);?>
						</span>
					</td>
				</tr>
				<tr>
					<th><?php _e('Posted Date', 'javo_fr');?></th>
					<td><?php echo $item_verify->post_date;?></td>
				</tr>

				<!-- PAID / FREE -->
				<?php if( get_post_meta( $item_verify->ID, 'javo_paid_state', true) != ''): ?>
				<tr>
					<th><?php _e('Payment Status', 'javo_fr');?></th>
					<td><?php echo strtoupper(get_post_meta( $item_verify->ID, 'javo_paid_state', true));?></td>
				</tr>
				<?php endif; ?>

				<!-- EXPIRE DAY -->
				<?php if( get_post_meta( $item_verify->ID, 'javo_expire_day', true) != ''): ?>
				<tr>
					<th><?php _e('Item Expiration', 'javo_fr');?></th>
					<td><?php echo javo_custom_paid_memberships_plugin::check_expire($item_verify->ID, __('Expired', 'javo_fr'), "label label-danger");?></td>
				</tr>
				<tr>
					<th><?php _e('Featured Item', 'javo_fr');?></th>
					<td>
						<?php
						if( 'use' == get_post_meta( $item_verify->ID, 'javo_this_featured_item', true ) )
						{
							printf('<span class="glyphicon glyphicon-ok"></span>&nbsp;%s', __('Yes' ,'javo_fr'));

						}else{
							printf('<span class="glyphicon glyphicon-remove"></span>&nbsp;%s', __('No' ,'javo_fr'));
						} ?>
					</td>

				</tr>
				<?php endif; ?>
			</table><!-- /.table -->
		</div><!-- /.panel.panel-default -->
	</div><!-- /.col-md-12 -->
</div><!-- /.row -->

<div class="row">
	<div class="col-md-12 text-center">
		<a href="<?php echo home_url( JAVO_DEF_LANG.JAVO_MEMBER_SLUG.'/'.wp_get_current_user()->user_login.'/'.JAVO_ITEMS_SLUG );?>" class="btn btn-default">
			<span class="glyphicon glyphicon-repeat"></span>&nbsp;<?php _e('Go Back', 'javo_fr');?>
		</a>
	</div><!-- /.col-md-12 -->
</div><!-- /.row -->
<?php if( $javo_tso->get('payment', '') == 'use' ): ?>
	<?php if( defined('PMPRO_VERSION') ): ?>
		<div class="row">
			<div class="col-md-12">
				<h3 class="oage-header"><?php _e("Current Order's Level Information", 'javo_fr');?></h3>
				<?php javo_custom_paid_memberships_plugin::orders(); ?>
			</div><!-- /.col-md-12 -->
		</div><!-- /.row -->
	<?php endif; ?>
	<?php if( function_exists('pmpro_wp') ): ?>
		<div class="row">
			<div class="col-md-12">
				<h3 class="page-header"><?php _e('Level Selection', 'javo_fr');?></h3>
				<?php
				if( function_exists('pmpro_wp') ) pmpro_wp();
				echo apply_filters( 'the_content', $post->post_content ); ?>
			</div><!-- /.col-md-12 -->
		</div><!-- /.row -->
	<?php endif; ?>
<?php endif;?>