<?php
class javo_event_column_slider
{
	public function __construct()
	{
		add_shortcode('javo_events', Array( __CLASS__, 'events_func'));
	}
	public static function events_func( $atts, $content="" )
	{
		global $javo_custom_item_label, $javo_tso;

		extract(shortcode_atts(Array(
			'title'						=> ''
			, 'sub_title'				=> ''
			, 'title_text_color'		=> '#000'
			, 'sub_title_text_color'	=> '#000'
			, 'line_color'				=> '#fff'
			, 'category'				=> ''
			, 'page'					=> (int) 4
			, 'order'					=> 'DESC'
			, 'type'					=> 'single'
			, 'nopaging'				=> true
		),$atts));

		$javo_events_args = Array(
			'post_type'					=> 'jv_events'
			, 'post_status'				=> 'publish'
			, 'posts_per_page'			=> ( $page > 0 ? $page : -1 )
			, 'order'					=> $order
			, 'orderby'					=> 'post_date'
		);

		if($category!=''){
			$javo_events_args['tax_query'][] = Array(
				'taxonomy'				=> 'jv_events_category',
				'field'					=> 'term_id',
				'terms'					=> $category
			);
		}
		$javo_events = new WP_Query( $javo_events_args );
		ob_start();
		echo apply_filters(
			'javo_shortcode_title'
			, $title
			, $sub_title
			, Array(
				'title'					=>'color:'.$title_text_color.';'
				, 'subtitle'			=>'color:'.$sub_title_text_color.';'
				, 'line'				=>'border-color:'.$line_color.';'
			)
		); ?>
		<div class="sc-wrap" id="javo-sc-events-listing">
			<div class="sc-items sc-item-long-line-box">
				<div class='row'>
					<?php
					switch( $type )
					{
						case 'single':
							?>
							<div class="javo-event-item-slider single-event-col">
								<div class="row">
									<div class="col-md-offset-9 col-md-3">
										<!-- Controls -->
										<div class="controls pull-right hidden-xs">
											<a class="left fa fa-chevron-left btn btn-success btn-circle btn-white" href="#javo-event-item-slider-container" data-slide="prev"></a>
											<a class="right fa fa-chevron-right btn btn-success btn-circle btn-white" href="#javo-event-item-slider-container" data-slide="next"></a>
										</div>
									</div>
								</div>
								<div id="javo-event-item-slider-container" class="carousel slide" data-ride="carousel">
									<!-- Wrapper for slides -->
									<div class="carousel-inner">
										<?php
										$javo_active = ' active';
										$javo_events = new WP_Query( $javo_events_args );
										if( $javo_events->have_posts() )
										{
											while( $javo_events->have_posts() )
											{
												$javo_events->the_post();
												$javo_get_parent_id = (int)get_post_meta(get_the_ID(), 'parent_post_id', true);

												$javo_get_parent = get_post($javo_get_parent_id);

												if( $javo_get_parent == '' )
													continue;

												$javo_get_parent_url = apply_filters( 'javo_wpml_link', $javo_get_parent->ID ).'#item-event';
												?>
												<div class="item<?php echo $javo_active;?>">
													<div class="col-sm-12 pull-left">
														<div class="row">
															<div class="col-md-12 text-center">
																<div class="javo-shortcode-event-listing one-col-event">
																	<?php
																	if ( has_post_thumbnail() ) {
																		the_post_thumbnail('javo-box', Array('class'=> 'img-responsive'));
																	}else{
																		printf('<img src="%s" class="img-circle" style="width:100%%; height:246px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
																	} ?>
																	<div class="javo-event-one-col">
																		<a href="<?php echo $javo_get_parent_url;?>">
																			<?php echo $javo_get_parent->post_title;?>
																			<div class="offer">
																				<?php the_title();?><br/>
																			</div>
																			<div class="javo-event-one-col-content">
																				<?php echo javo_str_cut(get_the_excerpt(), 400);?>
																			</div><!-- events-content-->
																		</a>
																	</div> <!-- javo-shop-name-->
																</div><!-- javo-shortcode-event-listing-->
															</div>
														</div><!-- /.row -->
													</div><!-- /.col-md-12 -->
												</div><!-- /.item -->
												<?php
												$javo_active = null;
											// End While
											}
										// ENd If
										}
										wp_reset_query();
										?>
									</div>
								</div>
							</div>
							<?php
						break;
						default:
							// NORMAL TYPE
							if($type=='two-cols'){
								$javo_event_columns_slice = 6;
								$javo_event_columns = 2;
							}else if($type=='three-cols'){
								$javo_event_columns_slice = 4;
								$javo_event_columns = 3;
							}else{
								$javo_event_columns_slice = 3;
								$javo_event_columns = 4;
							} ?>
							<div class="javo-event-item-slider">

								<div class="row">
									<div class="col-md-offset-9 col-md-3">
										<!-- Controls -->
										<div class="controls pull-right hidden-xs">
											<a class="left fa fa-chevron-left btn btn-success btn-circle btn-white" href="#javo-event-item-slider-container" data-slide="prev"></a>
											<a class="right fa fa-chevron-right btn btn-success btn-circle btn-white" href="#javo-event-item-slider-container" data-slide="next"></a>
										</div>
									</div>
								</div><!-- /.row -->

								<div id="javo-event-item-slider-container" class="carousel slide" data-ride="carousel">
									<!-- Wrapper for slides -->
									<div class="carousel-inner">
										<div class="item active">
											<div class="row">
												<?php
												$javo_integer = 0;
												$javo_this_event_posts = new WP_Query( $javo_events_args );
												if( $javo_this_event_posts->have_posts() ){
													while($javo_this_event_posts->have_posts()){
														$javo_this_event_posts->the_post();
														$javo_get_parent_id		= (int)get_post_meta(get_the_ID(), 'parent_post_id', true);

														if( null !== ( $javo_get_parent = get_post( $javo_get_parent_id ) ) )
														{
															$javo_get_parent_url	= apply_filters( 'javo_wpml_link', $javo_get_parent->ID).'#item-events';
															$javo_meta_query		= new javo_GET_META( $javo_get_parent_id );
															?>
															<div class="col-sm-<?php echo $javo_event_columns_slice;?> pull-left javo-second-event-wrap">

																<div class="row">
																	<div class="col-md-12 text-center">
																		<div class="javo-second-event-listing ">
																			<!-- <div class="javo-shortcode-event-listing"> -->
																			<div class="event-inner-overlay-bg">
																				<a href="<?php echo $javo_get_parent_url;?>">
																					<div class="event-image-inner-top">
																						<div class="offer">
																							<?php the_title();?>
																						</div><!-- offer -->
																					</div><!-- event-image-inner-top -->
																					<div class="col-md-7 events-content">
																						<?php echo javo_str_cut(get_the_excerpt(), 70);?>
																					</div><!-- events-content-->
																				</a>
																			</div><!-- event-inner-overlay-bg -->
																			<?php
																			if ( has_post_thumbnail()) {
																				if($javo_event_columns != 2){
																					the_post_thumbnail('javo-box', Array('class'=> 'img-responsive'));
																				}else{
																					the_post_thumbnail('javo-box-v', Array('class'=> 'img-responsive'));
																				}
																			}else{
																				printf('<img src="%s" style="width:100%%; height:246px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
																			};?>

																			<div class="item-name">
																				<span class="top-small-post-title">
																					<?php
																					if(strlen($javo_get_parent->post_title)>30) echo substr($javo_get_parent->post_title, 0, 30).'...';
																					else echo $javo_get_parent->post_title; ?>
																				</span>
																			</div> <!-- item-name -->
																		</div><!-- javo-shortcode-event-listing -->

																		<div class="javo-left-overlay bg-black">
																			<?php if( get_post_meta( get_the_ID(), 'brand', true) != ""){ ?>
																			<div class="javo-txt-meta-area admin-color-setting">
																				<?php echo get_post_meta( get_the_ID(), 'brand', true);?>
																			</div> <!-- javo-txt-meta-area -->
																			<div class="corner-wrap ">
																				<div class="corner admin-color-setting"></div>
																				<div class="corner-background admin-color-setting"></div>
																			</div> <!-- corner-wrap -->
																			<?php }; ?>
																		</div><!-- /.javo-left-overlay -->
																	</div><!-- /.col-md-12 -->
																</div><!-- /.row -->

															</div><!-- /.javo-second-event-wrap -->
															<?php
															$javo_integer++;
														}
														if( $javo_integer % $javo_event_columns == 0 && $javo_integer<$page){

																echo "</div></div><div class='item'><div class='row'>";
														}
													} // Close While
												}else{
													?>
													<div class="col-md-12">
														<?php _e('No event posts found.', 'javo_fr');?>
													</div>
													<?php
												} // Close if
												wp_reset_query();
												?>
											</div><!-- /.row -->
										</div><!-- /.item.active -->
									</div><!-- Carousel Inner -->
								</div><!-- Slider Container -->
							</div>
					<?php
						// End Default
					// End Switch
					} ?>
				</div><!-- row -->
			</div><!-- /.sc-item-long-line-box -->
		</div><!-- /#javo-sc-events-listing -->
		<?php
		return ob_get_clean();
	}
};
new javo_event_column_slider();