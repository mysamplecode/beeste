<?php

/* Map Load Ajax */
add_action("wp_ajax_nopriv_get_hmap", 'javo_get_hmap_callback');
add_action("wp_ajax_get_hmap", 'javo_get_hmap_callback');

/* Map Load Ajax */
add_action("wp_ajax_nopriv_get_hmap_favorite_lists", 'javo_map_get_favorite_lists_callback');
add_action("wp_ajax_get_hmap_favorite_lists", 'javo_map_get_favorite_lists_callback');

add_filter('javo_map_info_window_content', 'javo_map_info_window_content_callback');

function javo_get_hmap_callback(){

	global
		$javo_tso
		, $javo_favorite
		, $javo_custom_item_tab;

	$javo_query = new javo_array($_POST);

	if( $javo_query->get( 'lang', null ) != null ){
		global $sitepress;
		if( !empty( $sitepress ) ){
			$sitepress->switch_lang($javo_query->get( 'lang'), true);
		};
	};

	$javo_this_posts_args = Array(
		'post_status'=> 'publish'
		, 'post_type'=> $javo_query->get('post_type', 'post')
		, 'posts_per_page'=> $javo_query->get('ppp', 20)
		, 'paged'=> (int)$javo_query->get('current', 1)
		, 'order'=> $javo_query->get('order', 'DESC')
	);

	if( $javo_query->get('filter', null) != null){

		if( is_Array( $javo_query->get('filter') ) ){

			foreach( $javo_query->get('filter') as $taxonomy => $terms ){

				if( !empty( $terms) ){
					$javo_this_posts_args['tax_query']['relation'] = 'AND';
					$javo_this_posts_args['tax_query'][] = Array(
						'taxonomy'	=> $taxonomy
						, 'field'	=> 'term_id'
						, 'terms'	=> $terms
					);
				};
			};
		};
	}; // End Filter

	if( $javo_query->get('keyword', null) != null){
		$javo_this_posts_args['s']		= $javo_query->get('keyword');
	};

	$javo_this_posts_markers = Array();
	$javo_this_posts = new WP_Query($javo_this_posts_args);
	ob_start();
	?>

	<div class="body-content">
		<div class="col-md-12">
			<div id="products" class="list-group">
				<?php
				if( $javo_this_posts->have_posts() ){
						$i=0;
					while( $javo_this_posts->have_posts() ){
						$javo_this_posts->the_post();
						$javo_this_author_avatar_id = get_the_author_meta('avatar');
						?>

						<div class="item col-md-6 col-xs-12">
							<div class="thumbnail item-list-box-map">
								<div class="thumb-wrap">
										<?php
										if( has_post_thumbnail() ){
											the_post_thumbnail('javo-box-v', Array('class'=> 'group list-group-image item-thumbs'));
										}else{
											printf('<img src="%s" style="width:100%%; height:219px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));

										};?>

										<div class="javo-left-overlay">
											<div class="javo-txt-meta-area admin-color-setting"><?php echo javo_str_cut(javo_get_cat(get_the_ID(), 'item_category', __('No Category', 'javo_fr')),20);?>	</div> <!-- javo-txt-meta-area -->
											<div class="corner-wrap">
												<div class="corner"></div>
												<div class="corner-background"></div>
											</div> <!-- corner-wrap -->
										</div>
										<div class="rate-icons">
											<?php if( $javo_custom_item_tab->get('ratings', '') == ''): ?>
												<div class="col-md-2">
													<div class="col-md-12 javo-rating-registed-score" data-score="<?php echo javo_review::get( 'average');?>"></div>
												</div>
											<?php endif; ?>
										</div> <!-- rate-icons -->
										<div class="intro">
											<h2 class="group inner list-group-item-heading"><?php the_title();?></h2>
										</div> <!-- intro -->
										<div class="location">
										<?php echo wp_get_attachment_image($javo_this_author_avatar_id, 'javo-tiny', true, Array('class'=> 'img-circle', 'style'=>'width:50px; height:50px;'));?>

										</div> <!-- location -->
										<div class="three-inner-button">
											<a class="javo-hmap-marker-trigger three-inner-move" data-id="<?php echo 'mid_'.get_the_ID();?>" data-post-id="<?php the_ID();?>"><?php _e('Move', 'javo_fr');?></a>
											<a href="<?php the_permalink(); ?>" class="three-inner-detail"><?php _e('Detail', 'javo_fr');?></a>
											<a href="<?php the_permalink(); ?>" target="_brank" class="three-inner-popup"><?php _e('Popup', 'javo_fr');?></a>
										</div><!-- three-inner-button -->
								</div> <!-- thumb-wrap -->

								<div class="caption">
									<p class="group inner list-group-item-text">
									</p> <!-- list-group-item-text -->
									<div class="row">
										<div class="item-title-list">
											<a href="<?php the_permalink(); ?>"><?php the_title();?></a>
										</div>
										<div class="group inner list-group-item-text item-excerpt-list">
											<?php the_excerpt();?>
										</div> <!-- list-group-item-text -->
										<div class="col-xs-8 col-sm-8 col-md-8">
											<div class="row">
												<div class="col-md-12">
													<?php echo strtoupper(javo_str_cut(javo_get_cat( get_the_ID(), 'item_location', __('No Location','javo_fr')),22)); ?>
												</div><!-- col md 8 -->
											</div><!-- Row -->
										</div>
										<div class="col-xs-4 col-sm-4 col-md-4">
											<div class="social-wrap pull-right">
												<span class="javo-sns-wrap">
													<i class="sns-facebook" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
														<a class="facebook"></a>
													</i>
													<i class="sns-twitter" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
														<a class="twitter"></a>
													</i>
													<i class="sns-heart">
														<a class="favorite javo_favorite<?php echo $javo_favorite->on( get_the_ID(), ' saved');?>"  data-post-id="<?php the_ID();?>"></a>
													</i>
												</span>
											</div>
										</div><!-- socail -->
									</div><!-- row-->
								</div><!-- Caption -->
							</div><!-- Thumbnail -->
						</div><!-- Col-md-4 -->

						<?php
						$javo_this_geolocation = @unserialize(get_post_meta( get_the_ID(), 'latlng', true));
						$javo_set_icon = '';
						$javo_marker_term_id = wp_get_post_terms( get_the_ID() , 'item_category');
						if( !empty( $javo_marker_term_id ) ){
							$javo_set_icon = get_option('javo_item_category_'.$javo_marker_term_id[0]->term_id.'_marker', '');
							if( $javo_set_icon == ''){
								$javo_set_icon = $javo_tso->get('map_marker', '');
							};
						};
						$javo_this_posts_markers[ get_the_ID() ] = Array(
							'lat'=> $javo_this_geolocation['lat']
							, 'lng'=> $javo_this_geolocation['lng']
							, 'content'=> apply_filters( 'javo_map_info_window_content', get_the_ID() )
							, 'icon'=> $javo_set_icon
						);
					}; // End While
				}else{
					_e('Not Found Items', 'javo_fr');
				}; // End If
				printf('<div class="clearfix"></div>');

				$big = 999999999; // need an unlikely integer
				$javo_this_pagination = paginate_links( array(
					'base' => "%_%",
					'format' => '?%#%',
					'current' => (int)$javo_query->get('current', 1),
					'prev_text'    => __('< Prev' , 'javo_fr'),
					'next_text'    => __('Next >' , 'javo_fr'),
					'total' => $javo_this_posts->max_num_pages,
					'before_page_number'=> '<span class="javo-hmap-pagination">',
					'after_page_number'=> '</span>'
				) );
				printf('<div class="clearfix"></div><div class="javo-hmap-pagination-wrap">%s</div>', $javo_this_pagination);
				?>
			</div><!-- /.col-md-12-->
		</div><!-- /#products -->
	</div><!-- /.body-content -->
	<div style="padding:200px 0;">&nbsp;</div>

	<?php
	$javo_this_content_htmls = ob_get_clean();
	wp_reset_query();
	echo json_encode(Array(
		'html'=> $javo_this_content_htmls
		, 'markers'=> $javo_this_posts_markers
	));
	exit;
}

function javo_map_info_window_content_callback( $post_id ){
	// Map InfoWindow Contents Filter
	$javo_this_post = get_post( $post_id );
	if( $javo_this_post == null ) return false;
	$javo_meta_query = new javo_get_meta( $post_id );
	$javo_this_author_avatar_id = get_the_author_meta('avatar');
	$javo_this_author_name = sprintf('%s %s', get_the_author_meta('first_name'), get_the_author_meta('last_name'));

	ob_start();
	?>
	<div class="javo_somw_info panel" style="min-height:220px;">
		<div class="des">
			<ul class="list-unstyled">
				<li><div class="prp-meta"><h4><strong><?php the_title();?></h4></strong></div></li>
				<li><div class="prp-meta"><?php echo $javo_meta_query->get('phone');?></div></li>
				<li><div class="prp-meta"><?php echo $javo_meta_query->get('mobile');?></div></li>
				<li><div class="prp-meta"><?php echo $javo_meta_query->get('website');?></div></li>
				<li><div class="prp-meta"><?php echo $javo_meta_query->get('address');?><a href="<?php the_permalink(); ?>#single-tab-location" class="btn btn-primary btn-get-direction btn-sm"><?php _e("Get directions", "javo_fr"); ?></a></div></li>
			</ul>
		</div> <!-- des -->

		<div class="pics">
			<div class="thumb">
				<a href="<?php the_permalink();?>" target="_blank"><?php the_post_thumbnail('javo-map-thumbnail'); ?></a>
			</div> <!-- thumb -->
			<div class="img-in-text"><?php echo javo_get_cat(get_the_ID(), 'item_category', __('No Category', 'javo_fr'));?></div>
			<div class="javo-left-overlay">
				<div class="javo-txt-meta-area custom-bg-color-setting"><?php echo javo_get_cat(get_the_ID(), 'item_location', __('No Location', 'javo_fr'));?></div> <!-- javo-txt-meta-area -->

				<div class="corner-wrap">
					<div class="corner admin-color-setting"></div>
					<div class="corner-background admin-color-setting"></div>
				</div> <!-- corner-wrap -->
			</div> <!-- javo-left-overlay -->
		</div> <!-- pic -->

		<div class="row">
			<div class="col-md-12">
				<div class="btn-group btn-group-justified pull-right">
					<a class="btn btn-primary btn-sm" onclick="javo_map.brief_run(this);" data-id="<?php echo get_the_ID();?>">
						<i class="fa fa-user"></i> <?php _e("Brief", "javo_fr"); ?>
					</a>
					<a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">
						<i class="fa fa-group"></i> <?php _e("Detail", "javo_fr"); ?>
					</a>
					<a href="javascript:" class="btn btn-primary btn-sm" onclick="javo_map.contact_run(this)" data-to="<?php echo $javo_meta_query->get('email');?>" data-username="<?php echo $javo_this_author_name;?>">
						<i class="fa fa-envelope"></i> <?php _e("Contact", "javo_fr"); ?>
					</a>
				 </div><!-- btn-group -->
			</div> <!-- col-md-12 -->
		</div> <!-- row -->
	</div> <!-- javo_somw_info -->
	<?php
	return ob_get_clean();
}


function javo_map_get_favorite_lists_callback(){
	ob_start();
	if( is_user_logged_in() ){
		$javo_this_current_user_favorites = get_user_meta( get_current_user_id(), 'favorites', true);
		?>
		<div class="alert alert-rectangle alert-dark alert-no-padding">
			<h3 class="javo-sidebar-title text-center"><?php _e('My Favorites', 'javo_fr');?></h3>
		</div>

		<!-- Todo: Insert Sidebar content here. XD -->
		<ul class="list-group">
			<?php
			if( !empty($javo_this_current_user_favorites) ){
				foreach( $javo_this_current_user_favorites as $favorite ){
					if(!empty($favorite['post_id'] ) ){
						$javo_this_post = get_post($favorite['post_id']);
						if( $javo_this_post == null ) continue;
						printf( '<li class="list-group-item javo-hmap-marker-trigger text-center" data-id="mid_%s" data-post-id="%s"><span class="glyphicon glyphicon-heart-empty"></span>%s</li>', $javo_this_post->ID, $javo_this_post->ID, javo_str_cut($javo_this_post->post_title, 23) );
					};
				}; // End foreach
			}; // End if
			?>
		</ul>
		<?php
	}else{
		?>
		<div class="well well-default">
			<strong><?php _e('Alert', 'javo_fr');?></strong>
			<p><?php _e('Please login', 'javo_fr');?></p>
		</div>
		<?php
	}
	$javo_this_output = ob_get_clean();
	echo json_encode(Array(
		'html'=> $javo_this_output

	));
	exit;
}