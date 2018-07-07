<?php
global $javo_this_single_page_type
	, $javo_tso
	, $javo_animation_fixed
	, $javo_custom_item_label;

echo apply_filters('javo_shortcode_title', __($javo_custom_item_label->get('ratings', 'Ratings'), 'javo_fr'), get_the_title() );?>

<!-- total rating result start -->
<div class="total-rating">
	<div class="row total-rating-top-wrap">
		<div class="col-xs-12 col-md-12 col-sm-12 total-rating-top <?php echo $javo_animation_fixed;?>">
			<div class="well well-sm">
				<div class="total-rating-title">
					<h3><?php printf( __('Total %s', 'javo_fr'), $javo_custom_item_label->get('REVIEWS', __('REVIEWS', 'javo_fr')));?></h3>

				</div> <!-- total-rating-title -->


				<div class="row total-rating-wrap">
					<div class="col-xs-12 col-md-6 col-sm-6 <?php echo $javo_animation_fixed;?>">
						<div class="total-rating-left-wrap">
							<!--<div class="total-rating-title">
								<h3><?php _e($javo_tso->get('rating_alert_header'), 'javo_fr');?></h3>
							</div> <!-- total-rating-title -->

							<div class="rating-inner-wrap">
								<div class="rating"><?php echo javo_review::fa_get();?></div> <!-- rating -->
								<h1 class="rating-num"><?php echo javo_review::get( 'average'); ?></h1>
								<div class="rating-user">
									<span class="glyphicon glyphicon-user"></span><?php echo javo_review::get( 'count').' '; _e('Total', 'javo_fr');?>
								</div>
							</div>
							<div class="rating-alert-content-wrap">
								<?php _e($javo_tso->get('rating_alert_content'), 'javo_fr');?>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-sm-6 rating-desc-wrap">
						<div class="rating-desc">
							<?php javo_review::part_progress(); ?>
						</div> <!-- rating-desc -->
					</div> <!-- col-md-7 -->

				</div>

			</div> <!-- well -->
		</div> <!-- col-md-12 -->


		<?php javo_review::getWriteForm( true ); ?>

	</div> <!-- row total-rating-top-wrap -->
</div>