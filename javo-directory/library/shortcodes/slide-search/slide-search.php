<?php

class javo_slide_search
{
	static $load_script = false;
	public function __construct()
	{
		add_shortcode("javo_slide_search", Array( __CLASS__, "javo_slide_search_callback"));
		add_action(	'wp_footer', Array( __CLASS__ ,'load_script_func' ) );
	}
	public static function load_script_func()
	{
		if( self::$load_script )
		{
			wp_enqueue_script( 'jQuery-chosen-autocomplete' );
		}
	}
	public static function javo_slide_search_callback( $atts, $content="" )
	{
		global
			$javo_tso
			, $javo_custom_item_tab;

		self::$load_script = true;


		extract(shortcode_atts(
			Array(
				"items"=>4
				, 'search_type'				=> 'horizontal'
				, 'background_size'			=> 'auto'
				, 'background_position_x'	=> 'left'
				, 'background_position_y'	=> 'top'
				, 'background_repeat'		=> 'no-repeat'
				, 'height'					=> '300'
				, 'hidden_elements'			=> ''
				, 'hidden_form'				=> false
				, 'title_size' => '25'
				, 'cat_loc_size' => '20'
			), $atts)
		);



		$javo_item_search_slider_args = Array(
			'post_type'				=> 'item'
			, 'post_status'			=> 'publish'
			, 'posts_per_page'		=> -1
			, 'meta_query'			=> Array(
				Array(
					'key'			=> 'javo_this_featured_item'
					, 'compare'		=> '='
					, 'value'		=> 'use'
				)
			)
		);

		$javo_item_search_hide_el	= explode(',', $hidden_elements);
		$javo_item_search_slider	= new wp_query($javo_item_search_slider_args);
		ob_start();
		?>

		<div id="<?php echo $search_type == 'vertical'? 'javo-box-bg-search' : 'javo-slide-search';?>" class="carousel slide" data-ride="carousel">
			<!-- Indicators -->
			<ol class="carousel-indicators list-unstyled">
				<?php
				for($i=0; $i < $javo_item_search_slider->found_posts; $i++){
					printf('<li data-target="%s" data-slide-to="%s" class="%s"></li>'
						, ( $search_type == 'vertical' ? '#javo-box-bg-search':'#javo-slide-search')
						, $i, ($i == 0 ? 'active' : '')
					);
				};?>
			</ol>
			<div class="<?php echo $search_type == 'vertical'? 'background-search-box' : 'slider-search-box';?>">
				<div class="inner">
					<div class="inner_wrap">
						<?php
						if($javo_tso->get('page_item_result', 0) > 0 ){
							if( $hidden_form != 'hidden' ){
								switch( $search_type ){
									case 'horizontal':
										?>
										<form class="navbar-form navbar-left" role="search" method="post" action="<?php echo apply_filters( 'javo_wpml_link', $javo_tso->get('page_item_result'));?>">
											<div class="slider-search-part-wrap">

												<?php if( !in_Array( 'txt_keyword', $javo_item_search_hide_el ) ): ?>
													<div class="form-group">
														<input type="text" class="form-control input-large" placeholder="<?php _e("Keyword","javo_fr");?>" name="keyword">
													</div>
												<?php endif; ?>

												<?php if( !in_Array( 'sel_category', $javo_item_search_hide_el ) ): ?>
													<div class="form-group">
														<select name="category">
															<option value=""><?php _e('Category', 'javo_fr');?></option>
															<?php echo apply_filters('javo_get_selbox_child_term_lists', 'item_category', null, 'select', 0, 0, 0, "-");?>
														</select>
													</div><!-- form-Group -->
												<?php endif; ?>

												<?php if( !in_Array( 'sel_location', $javo_item_search_hide_el ) ): ?>
													<div class="form-group">
														<select name="location">
															<option value=""><?php _e('Location', 'javo_fr');?></option>
															<?php echo apply_filters('javo_get_selbox_child_term_lists', 'item_location', null, 'select', 0, 0, 0, "-");?>
														</select>
													</div><!-- form-Group -->
												<?php endif; ?>

												<button type="submit" class="btn btn-primary admin-color-setting"><i class="glyphicon glyphicon-map-marker"></i> <?php _e('Search on Map', 'javo_fr');?></button>
											</div><!-- slider-search-part-wrap -->
										</form>
										<?php
									break;
									case 'vertical':
									default:
										?>
										<form class="navbar-form navbar-left" role="search" method="post" action="<?php echo apply_filters( 'javo_wpml_link', $javo_tso->get('page_item_result'));?>">
											<div class="slider-search-part-wrap">
												<h3><?php _e('Search','javo_fr'); ?></h3>
												<?php if( !in_Array( 'txt_keyword', $javo_item_search_hide_el ) ): ?>
													<div class="form-group"><input type="text" class="form-control input-sm" placeholder=<?php _e("Search","javo_fr");?> name="keyword"></div>
												<?php endif; ?>

												<?php if( !in_Array( 'sel_category', $javo_item_search_hide_el ) ): ?>
													<div class="form-group">
														<select name="category">
															<option value=""><?php _e('Category', 'javo_fr');?></option>
															<?php echo apply_filters('javo_get_selbox_child_term_lists', 'item_category', null, 'select', 0, 0, 0, "-");?>

														</select>
													</div><!-- form-Group -->
												<?php endif; ?>

												<?php if( !in_Array( 'sel_location', $javo_item_search_hide_el ) ): ?>
													<div class="form-group">
														<select name="location">
															<option value=""><?php _e('Location', 'javo_fr');?></option>
															<?php echo apply_filters('javo_get_selbox_child_term_lists', 'item_location', null, 'select', 0, 0, 0, "-");?>
														</select>
													</div><!-- form-Group -->
												<?php endif; ?>

												<div class="search-part-inner-text"><?php _e('Search location and categories to displayed on the map.', 'javo_fr'); ?></div>
												<input type="submit" class="btn btn-primary admin-color-setting" onclick="this.form.submit();" value="<?php _e('Submit', 'javo_fr');?>">
											</div><!-- slider-search-part-wrap -->
										</form>
									<?php
								} // End Switch
							}
						}else{
							?>
							<div class="alert alert-warning text-center">
								<strong><?php _e('Results Page has not yet been setup.', 'javo_fr');?></strong>
								<p>
									<?php _e('Please check Theme Settings > Item Pages > Search Result', 'javo_fr');?>
								</p>
							</div>
							<?php
						} //  End If?>
					</div> <!-- inner_wrap -->
				</div> <!-- inner -->
			</div><!-- slider-search-box -->
			<div class="slide-search-bottom-shadow"></div>
			<!-- Wrapper for slides -->
			<div class="carousel-inner">
			<?php
			wp_reset_query();
			if( $javo_item_search_slider->have_posts() ){
				$i=0;
				while( $javo_item_search_slider->have_posts() ){
					$javo_item_search_slider->the_post();
					$javo_meta_query			= new javo_GET_META( get_the_ID() );
					$javo_brand_label			= trim( $javo_meta_query->get_events_brand_label() );
					?>

					<div class="item<?php echo $i == 0? ' active':''?>" style="height:<?php echo $height;?>px;">
						<?php
						if( has_post_thumbnail() ){
							$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
							$javo_slide_search_css_args	= Array(
								'background-image'				=> sprintf('url("%s")', $large_image_url[0])
								, 'background-repeat'			=> $background_repeat
								, 'background-size'				=> $background_size
								, '-webkit-background-size'		=> $background_size
								, '-moz-background-size'		=> $background_size
								, '-ms-background-size'			=> $background_size
								, '-o-background-size'			=> $background_size
								, 'background-attachment'		=> 'fixed'
								, 'background-position-x'		=> $background_position_x
								, 'background-position-y'		=> $background_position_y
								, 'height'						=> '100%'
							);




							$javo_slide_search_css		= '';
							foreach( $javo_slide_search_css_args as $attribute => $value ){
								$javo_slide_search_css .= $attribute . ':' . $value . '; ';
							};?>
							<div class="slide-bg-images" style='<?php echo $javo_slide_search_css;?>'>
							<div style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; background-image: url('<?php echo JAVO_THEME_DIR;?>/assets/images/pattern-dots-single.png'); background-repeat: repeat; z-index: 1;"></div>
							<a href="<?php the_permalink();?>">
								<div class="<?php echo $search_type == 'vertical'? 'background-slide-title hidden-sm hidden-xs' : 'carousel-caption';?>">
									<h4 class='javo-slider-search-meta hidden-sm hidden-xs' style="font-size:<?php echo $cat_loc_size; ?>px;">
										<?php printf('%s / %s', $javo_meta_query->cat('item_location', __('No Location','javo_fr')), $javo_meta_query->cat('item_category', 'No Category'));?>
									</h4>
									<div class="search-slider-title-wrap">
										<h2 style="font-size:<?php echo $title_size; ?>px;"><?php the_title();?></h2>
									</div>
								</div><!-- carousel-caption -->
							</a>

							<div class="row item-author-info <?php echo $search_type;?>">
								<?php if( !in_Array( 'cle_event', $javo_item_search_hide_el ) ): ?>
									<div class="col-md-4"><a href="<?php the_permalink();?>.#item-events">
										<?php
										if( !empty($javo_brand_label) && $javo_custom_item_tab->get('events', '') == ''){ ?>
											<div class="event_info">
												<div class="item-author-info-circle-border"><?php echo $javo_brand_label;?></div>
											</div>
										<?php }else{
											printf('<div>&nbsp;</div>');
										};?>
									</a></div>
								<?php endif; ?>

								<?php
								if( !in_Array( 'cle_category', $javo_item_search_hide_el ) ):
									$javo_cat_featured = wp_get_attachment_image_src( $javo_meta_query->featured_cat(), 'javo-tiny');
									$javo_cat_featured = $javo_cat_featured[0]; ?>
									<div class="col-md-4">
										<a href="<?php the_permalink();?>"><img src="<?php echo $javo_cat_featured;?>">
										<div class="item-author-info-circle-border"></div></a>
									</div>
								<?php endif; ?>

								<?php if( !in_Array( 'cle_rating', $javo_item_search_hide_el ) ): ?>
								<div class="col-md-4">
									<?php if( $javo_custom_item_tab->get('ratings', '') == ''): ?>
									<a href="<?php the_permalink();?>.#item-ratings">
										<div class="rating_score text-center">
											<?php echo javo_review::get( 'average');?>
										</div>
										<div class="item-author-info-circle-border"></div>
									</a>
									<?php endif; ?>
								</div>
								<?php endif; ?>
							</div><!-- item-author-info -->
						</div> <!-- slide-bg-images -->
							<?php
						}; ?>
					</div><!-- item -->
					<?php
					$i++;
				}; // End While
			}else{
				?>
				<div class="item active">
					<img src="" alt="...">
					<div class="carousel-caption">
						<h3><?php _e('No Items Found.', 'javo_fr');?></h3>
						<p></p>
					</div><!-- carousel-caption -->
				</div><!-- item -->
				<?php
			}; // End IF
			wp_reset_query();?>
			</div><!-- carousel-inner -->

			<!-- Controls -->
			<a class="left carousel-control" href="#<?php echo $search_type == 'vertical'? 'javo-box-bg-search' : 'javo-slide-search';?>" role="button" data-slide="prev">
				<i class="glyphicon glyphicon-chevron-left"></i>
			</a>
			<a class="right carousel-control" href="#<?php echo $search_type == 'vertical'? 'javo-box-bg-search' : 'javo-slide-search';?>" role="button" data-slide="next">
				<i class="glyphicon glyphicon-chevron-right"></i>
			</a>
		</div> <!-- javo-slide-search -->

		<script type="text/javascript">
		jQuery(function($){
			"use strict";
			var javo_slide_serach = {
				init: function(){
					this.events();

					$('.background-search-box, .slider-search-box').find('.form-group > select').each(function()
					{
						$(this).chosen({ width:'100%', search_contains:1 });
					});
				}, events:function(){
					var $object = this;
					$('body').on('click', '.javo-this-post-views li a', function(){

						$(this).closest('.btn-group').children('button:first-child').text( $(this).text() );
						$object.options.ppp = $(this).data('views');
						$object.run();

					});
					$('.javo-this-filter').each( function(c, v){
						var _this = $(this);
						$(this).on('click', 'li[data-filter]', function(){
							$(this).closest('.btn-group').children('button:first-child').text( $(this).data('origin-title') );
							$(this).closest('ul').next().val( $(this).val() );

						});
					});
				}
			};
			javo_slide_serach.init();
		});
		</script>


		<?php
		$content = ob_get_clean();
		return $content;
	}
}
new javo_slide_search();