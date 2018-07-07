<?php

// PMP Veriables
global
	$wpdb
	, $javo_tso
	, $pmpro_msg
	, $pmpro_msgt
	, $pmpro_levels
	, $current_user
	, $current_level
	, $pmpro_currency_symbol;

if($pmpro_msg){ echo "<div class='pmpro_message {$pmpro_msgt}'>{$pmpro_msg}</div>"; }
?>

<div class="javo-item-price-table">
	<?php
	foreach( $pmpro_levels as $lv )
	{
		$current_level = isset($current_user->membership_level->ID) && ($current_user->membership_level->ID == $lv->id);

		$javo_membership = Array();
		$javo_membership['posts'] = Array(
			'icon'				=> 'glyphicon glyphicon-pencil'
			, 'label'			=> __('Item', 'javo_fr')
			, 'unlimited'		=> (int)$lv->javo_allow_post_unliimit > 0
			, 'unlimited_str'	=> __('Unlimited Posting', 'javo_fr')
			, 'amount'			=> $lv->javo_allow_post
			, 'suffix'			=> __('posts', 'javo_fr')
			, 'no_use'			=> __('No Posting Items', 'javo_fr')

		);

		$javo_membership['expire'] = Array(
			'icon'				=> 'glyphicon glyphicon-calendar'
			, 'label'			=> __('Duration', 'javo_fr')
			, 'unlimited'		=> (int)$lv->javo_expire_unliimit > 0
			, 'unlimited_str'	=> __('Unlimited Duration', 'javo_fr')
			, 'amount'			=> $lv->javo_expire_day
			, 'suffix'			=> __('days', 'javo_fr')
			, 'no_use'			=> __('No Duration', 'javo_fr')
		);

		$javo_membership['featured'] = Array(
			'icon'				=> 'glyphicon glyphicon-thumbs-up'
			, 'label'			=> __('Featured', 'javo_fr')
			, 'unlimited'		=> (int)$lv->javo_featured_unliimit > 0
			, 'unlimited_str'	=> __('Unlimited Featured', 'javo_fr')
			, 'amount'			=> $lv->javo_cnt_featured
			, 'suffix'			=> __('posts', 'javo_fr')
			, 'no_use'			=> __('No Featured', 'javo_fr')
		);

		if( $javo_tso->get('pmp_level_direction', '') != 'horizontal' ):

			// DISPLAY TYPE "VERTICAL"
			?>
			<ul class="javo-price-item<?php echo isset( $lv->javo_popular ) && (int)$lv->javo_popular > 0? ' popular' : ''; ?>">
				<li class="title">
					<h2><?php echo $lv->name;?></h2>
				</li>
				<li class="price">
					<span>
						<?php
						if( pmpro_isLevelFree( $lv ) )
							$cost_text = sprintf("<strong>%s</strong>", __('Free', 'javo_fr'));
						else
							$cost_text = pmpro_getLevelCost($lv, true, true);

						$expiration_text = pmpro_getLevelExpiration($lv);

						if(!empty($cost_text) && !empty($expiration_text))
							echo $cost_text . "<br />" . $expiration_text;
						elseif(!empty($cost_text))
							echo $cost_text;
						elseif(!empty($expiration_text))
							echo $expiration_text;
						?>
					</span>
				</li>
				<?php
				foreach( $javo_membership as $item )
				{
					if( !pmpro_isLevelFree( $lv ) )
					{
						if( !$item['unlimited'] )
						{
							if( (int)$item['amount'] > 0 )
							{
								echo "<li class='attribute'><span><i class='{$item['icon']}'></i> {$item['label']} {$item['amount']} {$item['suffix']}</span></li>";
							}else{
								echo "<li class='attribute disabled'><span><i class='{$item['icon']}'></i> {$item['no_use']}</span></li>";
							}
						}else{
							echo "<li class='attribute'><span><i class='{$item['icon']}'></i> {$item['unlimited_str']}</span></li>";
						}
					}else{
						// IT IS FREE LEVEL
						echo "<li class='attribute x3'></li>";
					}
				// FOREACH
				} ?>
				<li  class="submit">
					<?php if(empty($current_user->membership_level->ID)) { ?>
						<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url("checkout", "?level=" . $lv->id, "https")?>"><?php _e('Select', 'javo_fr');?></a>
					<?php } elseif ( !$current_level ) { ?>
						<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url("checkout", "?level=" . $lv->id, "https")?>"><?php _e('Select', 'javo_fr');?></a>
					<?php } elseif($current_level) { ?>

						<?php
						//if it's a one-time-payment level, offer a link to renew
						if(!pmpro_isLevelRecurring($current_user->membership_level) && !empty($current_user->membership_level->enddate))
						{ ?>
							<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url("checkout", "?level=" . $lv->id, "https")?>"><?php _e('Renew', 'javo_fr');?></a>
						<?php
						}else{ ?>
							<a class="pmpro_btn disabled" href="<?php echo pmpro_url("account")?>"><?php _e('Your Level', 'javo_fr');?></a>
						<?php
						}
					} ?>
				</li>
			</ul>
			<?php
		else:
			// DISPLAY TYPE "HORIZONTAL"
			?>
			<div class="row javo-price-item-horizontal<?php echo isset( $lv->javo_popular ) && (int)$lv->javo_popular > 0? ' popular' : ''; ?>">
				<div class="list-group">
					<div class="list-group-item">
						<div class="row">
							<div class="col-md-2 columns title">
								<?php echo $lv->name;?>
							</div><!-- /.col-md-2.columns -->
							<div class="col-md-5 columns price">
								<span>
									<?php
									if( pmpro_isLevelFree( $lv ) )
										$cost_text = sprintf("<strong>%s</strong>", __('Free', 'javo_fr'));
									else
										$cost_text = pmpro_getLevelCost($lv, true, true);

									$expiration_text = pmpro_getLevelExpiration($lv);

									if(!empty($cost_text) && !empty($expiration_text))
										echo $cost_text . $expiration_text;
									elseif(!empty($cost_text))
										echo $cost_text;
									elseif(!empty($expiration_text))
										echo $expiration_text;
									?>
								</span>

							</div><!-- /.col-md-5.columns -->
							<div class="col-md-3 columns text-left">
								<ul class="list-unstyled">
									<?php
									foreach( $javo_membership as $item )
									{
										if( !pmpro_isLevelFree( $lv ) )
										{
											if( !$item['unlimited'] )
											{
												if( (int)$item['amount'] > 0 )
												{
													echo "<li class='attribute'><span><i class='{$item['icon']}'></i> {$item['label']} {$item['amount']} {$item['suffix']}</span></li>";
												}else{
													echo "<li class='attribute disabled'><span><i class='{$item['icon']}'></i> {$item['no_use']}</span></li>";
												}
											}else{
												echo "<li class='attribute'><span><i class='{$item['icon']}'></i> {$item['unlimited_str']}</span></li>";
											}
										}else{
											// IT IS FREE LEVEL
											echo "<li class='attribute x3'></li>";
										}
									// FOREACH
									} ?>
								</ul><!-- meta -->

							</div><!-- /.col-md-3.columns -->

							<div class="col-md-2 columns submit">
								<?php if(empty($current_user->membership_level->ID)) { ?>
									<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url("checkout", "?level=" . $lv->id, "https")?>"><?php _e('Select', 'javo_fr');?></a>
								<?php } elseif ( !$current_level ) { ?>
									<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url("checkout", "?level=" . $lv->id, "https")?>"><?php _e('Select', 'javo_fr');?></a>
								<?php } elseif($current_level) { ?>

									<?php
									//if it's a one-time-payment level, offer a link to renew
									if(!pmpro_isLevelRecurring($current_user->membership_level) && !empty($current_user->membership_level->enddate))
									{ ?>
										<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url("checkout", "?level=" . $lv->id, "https")?>"><?php _e('Renew', 'javo_fr');?></a>
									<?php
									}else{ ?>
										<a class="pmpro_btn disabled" href="<?php echo pmpro_url("account")?>"><?php _e('Your Level', 'javo_fr');?></a>
									<?php
									}
								} ?>
							</div><!-- /.col-md-2.columns -->

						</div><!-- /.row -->
					</div><!-- /.list-group-item -->
				</div><!-- /.list-group -->
			</div><!-- /.row.javo-price-item-horizontal -->
			<?php
		endif;
	// END FOREACH
	} ?>
</div>