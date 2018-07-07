<?php
// Post, item get lists.
add_action('wp_ajax_nopriv_post_list', 'javo_post_list_callback');
add_action('wp_ajax_post_list', 'javo_post_list_callback');

function javo_post_list_callback(){

	// Wordpress Queries
	global
		$wp_query
		, $javo_tso
		, $javo_favorite
		, $javo_custom_item_label
		, $javo_custom_item_tab;

	$video_media = Array(
		"youtube"			=> "//www.youtube.com/embed/"
		, "vimeo"			=> "//player.vimeo.com/video/"
		, "screenr"			=> "//www.youtube.com/embed/"
		, "dailymotion"		=> "//www.youtube.com/embed/"
		, "metacafe"		=> "//www.youtube.com/embed/"
	);

	$javo_query				= new javo_ARRAY( $_POST );

	if( $javo_query->get( 'lang', null ) != null ){
		global $sitepress;
		if( !empty( $sitepress ) ){
			$sitepress->switch_lang($javo_query->get( 'lang'), true);
		};
	};

	// List view tyope
	$mode					= $javo_query->get('type', null);
	$post_type				= $javo_query->get('post_type', 'item');
	$p						= $javo_query->get('post_id', null);
	$ppp					= $javo_query->get('ppp', 10);
	$page					= $javo_query->get('page', 1);
	$tax					= $javo_query->get('tax', 10);
	$term_meta				= $javo_query->get('term_meta', 10);

	$meta					= Array();

	$args					= Array(
		'post_type'			=> $post_type
		, 'post_status'		=> 'publish'
		, 'posts_per_page'	=> $ppp
		, 'paged'			=> $page
	);

	if((int)$p > 0 ) $args['p'] = $p;

	if($tax != NULL && is_Array($tax)){
		foreach($tax as $key=>$value){
			if($value != ""){
				$args['tax_query']['relation'] = "AND";
				$args['tax_query'][] = Array(
					"taxonomy"=> $key,
					"field"=> "term_id",
					"terms"=> $value
				);
			};
		};
	};

	if($term_meta != NULL && is_Array($term_meta)){
		foreach($term_meta as $key=>$value){
			if($value != ""){
				$args['meta_query']['relation'] = "AND";
				$args['meta_query'][] = Array(
					"key"=> $key,
					"value"=> (int)$value,
					"compare"=> ">="
				);
			};
		};
	};
	$args["s"] = $javo_query->get('keyword', null);

	// Not found image featured url
	$noimage = JAVO_IMG_DIR."/no-image.png";
	ob_start(); ?>
	<script type="text/javascript">
	if(typeof(window.jQuery) != "undefined")
	{
		jQuery(document).ready(function($)
		{
			"use strict";
			$(".javo_hover_body").css({
				"position":"absolute",
				"top":"0",
				"left":"0",
				"z-index":"2",
				"width":"100%",
				"height":"100%",
				"padding":"10px",
				"margin": "0px",
				"backgroundColor":"rgba(0, 0, 0, 0.4)",
				"display":"none"
			});
			$(".javo_img_hover")
				.css({
					"position":"relative", "overflow":"auto", "display":"inline-block"
				}).hover(function(){
					$(this).find(".javo_hover_body").fadeIn("fast");
				}, function(){
					$(this).find(".javo_hover_body").clearQueue().fadeOut("slow");
				});
		});
	};
	</script>
	<?php
	switch($mode){
		case 2:$posts = new WP_Query($args);?>
		<div class="row javo-item-grid-listing">
			<?php
			$i=0;
			## Thumbnail Type ###################
			if( $posts->have_posts() ){
				while($posts->have_posts() ){
					$posts->the_post();
					$javo_meta_query = new javo_GET_META( get_the_ID() );
					$meta = Array(
						'strong'=> $javo_meta_query->cat('item_category', __('No Category', 'javo_fr'))
						, "featured"=> $javo_meta_query->cat('item_location', __('No Location', 'javo_fr'))
					);
					?>
					<div class="col-md-4 col-sm-6 col-xs-6 pull-left">
						<div class="panel panel-default panel-relative">
							<?php
							$detail_images = (Array)@unserialize(get_post_meta(get_the_ID(), "detail_images", true));
							$detail_images[] = get_post_thumbnail_id( get_the_ID() );
							if(!empty($detail_images)):
								echo '<div class="javo_detail_slide">';
								$javo_this_image_meta	= wp_get_attachment_image_src($detail_images, 'large');
								$javo_this_image		= $javo_this_image_meta[0];
								?>

									<?php echo '<ul class="slides list list-unstyled">';
									foreach($detail_images as $index => $image){
										$javo_this_image_meta	= wp_get_attachment_image_src($image, 'full');
										$javo_this_image		= $javo_this_image_meta[0];
										?>
										<li>
											<u href="<?php echo $javo_this_image; ?>" style="cursor:pointer;">
												<?php
												$javo_this = wp_get_attachment_image($image, "javo-box-v", false);
												if( $javo_this ){
													echo $javo_this;
												}else{
													printf('<img src="%s" class="img-responsive wp-post-image" style="width:100%%; height:201px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
												};?>
											</u>
										</li>
										<?php
									};
									echo '</ul>';
								echo '</div>';
							endif;?>
							<div class="panel-body">
								<div class="col-md-12">
									<a href="<?php the_permalink();?>" class="javo-tooltip javo-igl-title"  title="<?php the_title();?>">
										<div>
											<h2 class="panel-title text-center">
												<strong> <?php echo javo_str_cut(get_the_title(), 120);?> </strong>
											</h2>
											<div class="text-center"><?php echo $meta['strong'];?></div>
										</div>
									</a>
									<ul class="list list-unstyled">
										<li class="text-center text-excerpt javo-grid-listing-excerpt"><?php echo javo_str_cut(get_the_excerpt(), 150);?></li>
										<li class="text-center">
											<h2 class="javo-sns-wrap social-wrap">
												<i class="sns-facebook" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
													<a class="facebook javo-tooltip" title="<?php _e('Share Facebook', 'javo_fr');?>"></a>
												</i>
												<i class="sns-twitter" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
													<a class="twitter javo-tooltip" title="<?php _e('Share Twitter', 'javo_fr');?>"></a>
												</i>
												<i class="sns-heart">
													<a class="javo-tooltip favorite javo_favorite<?php echo $javo_favorite->on( get_the_ID(), ' saved');?>"  data-post-id="<?php the_ID();?>" title="<?php _e('Add My Favorite', 'javo_fr');?>"></a>
												</i>
											</h2>
										</li>
									</ul><!-- List group -->
								</div>
							</div>
							<div class="javo-left-overlay">
								<div class="javo-txt-meta-area admin-color-setting">
									<?php echo $meta['featured']; ?>
								</div> <!-- javo-txt-meta-area -->
								<div class="corner-wrap">
									<div class="corner admin-color-setting"></div>
									<div class="corner-background admin-color-setting"></div>
								</div> <!-- corner-wrap -->
							</div><!-- javo-left-overlay -->



						</div> <!-- /.panel -->
					</div><!-- /.col-sm-4 -->
					<?php
					$i++;
					echo $i % 3 == 0? '<p style="clear:both;"></p>':'';
				}; // End While
			}; // End If
		break;
		case 4:
			$posts = new WP_Query($args);
			if( $posts->have_posts() ){
				while($posts->have_posts() ){
					$posts->the_post();
					$javo_meta_query = new javo_GET_META( get_the_ID() );
					$pd = strtotime(get_the_date());
					$meta = Array(
						'column1'	=> $post_type == "item" ? $javo_meta_query->cat('item_category', __('No Category', 'javo_fr')) : get_the_author_meta('user_login')
						, 'column2'	=> $post_type == "item" ? $javo_meta_query->cat('item_location', __('No Location', 'javo_fr')) : get_the_author_meta('user_login')
						, 'column3'	=> $post_type == "item" ? (int)$javo_meta_query->get_child_count('jv_events') : get_the_author_meta('user_login')
						, 'column4'	=> $post_type == "item" ? wp_count_comments( get_the_ID() )->approved : get_the_author_meta('user_login')
						, 'column5' => sprintf(' %s'
							, ($post_type == "item" ? $javo_meta_query->get('item_category', __('No Category', 'javo_fr')) : get_the_author_meta('user_login'))
						), 'column6'=> sprintf('<i class="%s"></i> %s'
							, ($post_type == "item" ? "icon-status" : "javo-con status")
							, ($post_type == "item" ? $javo_meta_query->get('item_category', __('No Category', 'javo_fr')) : get_the_author_meta('user_login'))
						), 'features'=> sprintf('<span class="%s"></span> %s'
							, ($post_type == "item" ? "glyphicon glyphicon-user" : "")
							, ($post_type == "item" ? $javo_meta_query->get('item_category', __('No Category', 'javo_fr')) : get_the_author_meta('user_login'))
						)
						, "month"=> sprintf('%.1f', javo_review::get( 'average') )
						, "day"=> '/ '.(int)$javo_meta_query->get_child_count('ratings', 'rating_parent_post_id')
					);
					?>
					<div class="row pretty_blogs" id="mini-album-listing">
						<div class="col-md-4 col-sm-5 col-xs-6 blog-thum-box">
							<?php
							$detail_images = (Array)@unserialize(get_post_meta(get_the_ID(), "detail_images", true));
							$detail_images[] = get_post_thumbnail_id( get_the_ID() );
							if(!empty($detail_images)):
								?>
								<div class="javo_detail_slide">
									<ul class="slides list-unstyled">
										<?php
										foreach($detail_images as $index => $image){
											$javo_this_image_meta	= wp_get_attachment_image_src($image, 'full');
											$javo_this = wp_get_attachment_image($image, "javo-box-v", false);
											if( !$javo_this ){
												$javo_this = sprintf('<img src="%s" class="img-responsive wp-post-image" style="width:100%%; height:219px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
											};
											$javo_this_image		= $javo_this_image_meta[0];
											printf('<li><u href="%s">%s</u></li>', $javo_this_image, $javo_this	);
										};
										?>
									</ul>
									<?php if( $javo_custom_item_tab->get('ratings', '') == ''): ?>
										<div class="javo-mini-album-rating-area">
											<div class="javo-rating-registed-score" data-score="<?php echo javo_review::get( 'average');?>"></div>
										</div>
									<?php endif; ?>
								</div>
								<?php
							endif;?>
							<div class="">
								<span class="down-text"><?php //echo $meta['month'];?></span>
								<span class="up-text"><?php //echo $meta['day'];?></span>
							</div>

						</div> <!-- col-md-5 -->

						<div class="col-md-8 col-sm-7 col-xs-6 blog-meta-box">
							<div class="blog-meta-box-arrow"></div>
							<h2 class="title"><a href="<?php echo get_permalink(get_the_ID());?>"><?php the_title();?></a></h2>

							<div class="excerpt"><?php echo javo_str_cut(get_the_excerpt(), 240);?>&nbsp;&nbsp;<a href="<?php the_permalink();?>">[<?php _e('MORE', 'javo_fr'); ?>]</a></div>

							<div class="row">
								<div class="col-md-12 javo-blog-meta-tags">
									<div class="pull-left javo-tooltip" title="<?php _e('Category', 'javo_fr');?>">
										<ul class="pagination pagination-sm no-margin pagination-dark pagination-upper">
											<li class="active"><a href="#"><?php echo $meta['column1'];?></a></li>
										</ul>
									</div>
									<div class="pull-left javo-tooltip" title="<?php _e('Location', 'javo_fr');?>">
										<ul class="pagination pagination-sm no-margin pagination-dark pagination-upper">
											<li class="active"><a href="#"><?php echo $meta['column2'];?></a></li>
										</ul>
									</div>
									<?php if( $javo_custom_item_tab->get('events', '') == ''):?>
										<div class="pull-left">
											<ul class="pagination pagination-sm no-margin pagination-dark">
												<li><a href="#" class="javo-accent bold-number"><?php echo $meta['column3'];?></a></li>
												<li class="active"><a href="#"><?php echo $javo_custom_item_label->get('events', __("Events", 'javo_fr'))."(s)";?></a></li>
											</ul>
										</div>
									<?php endif; ?>

									<?php if( $javo_custom_item_tab->get('reviews', '') == ''):?>
										<div class="pull-left">
											<ul class="pagination pagination-sm no-margin pagination-dark">
												<li><a href="#" class="javo-accent bold-number"><?php echo $meta['column4'];?></a></li>
												<li class="active"><a href="#"><?php echo $javo_custom_item_label->get('reviews', __("Reviews", 'javo_fr'))."(s)"; ?></a></li>
											</ul>
										</div>
									<?php endif; ?>
									<div class="pull-right">
										<span class="javo-sns-wrap social-wrap">
											<i class="sns-facebook" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
												<a class="facebook javo-tooltip" title="<?php _e('Share Facebook', 'javo_fr');?>"></a>
											</i>
											<i class="sns-twitter" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
												<a class="twitter javo-tooltip" title="<?php _e('Share Twitter', 'javo_fr');?>"></a>
											</i>
											<i class="sns-heart">
												<a class="javo-tooltip favorite javo_favorite<?php echo $javo_favorite->on( get_the_ID(), ' saved');?>"  data-post-id="<?php the_ID();?>" title="<?php _e('Add My Favorite', 'javo_fr');?>"></a>
											</i>
										</span>
									</div>
								</div><!-- Col-md-12 -->
							</div> <!-- property-meta -->
						</div> <!-- col-md-7 -->
					</div> <!-- row -->
					<?php
				}; // End While
			}; // End If
		break;
		case 11:
			$posts = new WP_Query($args);
			$javo_variable_integer = 0;
			?>
			<div class="row" id="box-listing">
				<?php
				if( $posts->have_posts() ){
					while( $posts->have_posts() ){
						$posts->the_post();
						$javo_meta_query = new javo_GET_META( get_the_ID() );
						$javo_meta_string = Array(
							'strong'			=> get_comments_number( get_the_ID() )
							, 'featured'		=> $javo_meta_query->cat('category', __('No Category', 'javo_fr'))
							, 'type'			=> sprintf('<span class="glyphicon glyphicon-user"></span> %s ( %s )'
													, get_the_author_meta('display_name')
													, esc_attr( get_the_date( __('M d, Y', 'javo_fr') ) )
												)
						);
						?>
						<div class="col-md-6 col-sm-6 col-xs-6 box-wraps">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="main-box item-wrap1">
										<div class="row blog-wrap-inner">
											<div class="col-md-5 col-sm-5 col-xs-5 img-wrap">
												<div class="javo_img_hover">
													<?php
													if( has_post_thumbnail() ){
														the_post_thumbnail('javo-box', Array('class'=> 'img-responsive', 'style'=>'width:100%;'));
													}else{
														printf('<img src="%s" class="img-responsive wp-post-image">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
													};?>
												</div> <!-- javo_img_hover -->
											</div> <!-- col-md-5 -->
											<div class="col-md-7 col-sm-7 col-xs-7">
												<div class="detail">
													<a href="<?php the_permalink(); ?>"><h3><?php the_title();?></h3></a>
													<p class="expert"><?php echo javo_str_cut( get_the_excerpt(), 120);?></p>
												</div>
											</div> <!-- col-md-7 -->
										</div> <!-- row -->
									</div> <!-- main-box -->
								</div><!-- Panel Body -->

								<ul class="list-group">
									<li class="list-group-item">
										<div class="row">
											<div class="col-md-9 col-sm-9 col-xs-8 javo-box-listing-meta">
												<?php echo $javo_meta_string['type']; ?>
											</div>
											<div class="col-md-3 col-sm-3 col-xs-4 text-right">
												<span class="javo-sns-wrap social-wrap">
													<i class="sns-facebook" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
														<a class="facebook javo-tooltip" title="<?php _e('Share Facebook', 'javo_fr');?>"></a>
													</i>
													<i class="sns-twitter" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
														<a class="twitter javo-tooltip" title="<?php _e('Share Twitter', 'javo_fr');?>"></a>
													</i>
												</span>
											</div> <!-- col-6-md -->
										</div><!-- row -->
									</li>
								  </ul>
								<div class="javo-left-overlay">
									<div class="javo-txt-meta-area admin-color-setting">
										<?php echo $javo_meta_string['featured']; ?>
									</div> <!-- javo-txt-meta-area -->
									<div class="corner-wrap">
										<div class="corner"></div>
										<div class="corner-background"></div>
									</div> <!-- corner-wrap -->
								</div><!-- javo-left-overlay -->
							</div><!-- Panel Wrap -->
						</div> <!-- col-lg-6 -->
						<?php
						$javo_variable_integer++;
						echo $javo_variable_integer % 2 == 0 ? '<p class="clearfix"></p>':'';
					};	// End While
				}; // End IF
				?>
			</div>
			<?php
			break;
		case "listers": $posts = query_posts($args);
			## Blog Calender Type #################
			foreach($posts as $post): setup_postdata($post);
			$javo_list_str = new get_char($post);
			$author = get_userdata($post->post_author);
			if(has_post_thumbnail($post->ID)){
				$thumbnail = get_the_post_thumbnail($post->ID, "javo-avatar");
			}else{
				$thumbnail = $noimage;
			};?>
			<!--<div class="row">-->
				<div class="col-md-3" id="listers-box-list">
				<div class="panel panel-default">
					  <div class="panel-heading">

					  <div class="lister-pic">
						<a href="<?php echo get_permalink($post->ID);?>">
							<?php
								$img_src = wp_get_attachment_image_src(get_user_meta($author->ID, "avatar", true), "javo-avatar");
								if($img_src !="") printf("<img src='%s'>", $img_src[0]);
							?>
						</a>
							<div class="text-rb-meta">
							<?php
								printf("%s %s"
									, number_format($javo_list_str->get_author_item_count())
									, __("items", "javo_fr")
								);?>
							</div>
						</div>

					  </div> <!-- panel-heading -->
					  <ul class="list-group">
  						<li class="list-group-item"><h3 class="panel-title"><span class="glyphicon glyphicon-user"></span>&nbsp;
								<?php echo $author->first_name;?>&nbsp;<?php echo $author->last_name;?></h3></li>

						<li class="list-group-item"><?php echo javo_str_cut($javo_list_str->a_meta('description'), 130); ?></li>
						<li class="list-group-item">
							<a href="http://facebook.com/<?php echo $javo_list_str->a_meta('facebook');?>" target="_blank"><i class="fa fa-facebook"></i></a>
							<a href="http://twitter.com/<?php echo $javo_list_str->a_meta('twitter');?>" target="_blank"><i class="fa fa-twitter"></i></a>
						</li>
					  </ul><!-- List group -->
					</div><!-- panel -->



				</div> <!-- col-md-3 -->
		<?php endforeach; break; case "new_list": $posts = new WP_Query($args);
			?>
			<div class="body-content">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<!-- grid/list -->
							<div id="products" class="row list-group">
							<?php
							if( $posts->have_posts() ){
								while( $posts->have_posts() ){
									$posts->the_post();
									$javo_this_author_avatar_id = get_the_author_meta('avatar');
									?>
									<div class="item col-md-4 javo-animation x1 javo-left-to-right-100">

									<div class="panel panel-default  item-list-box-listing">
										<div class="panel-body">
											<div class="main-box blog-wrap1">
												<div class="row blog-wrap-inner">
													<div class="col-md-12img-wrap">
														<div class="javo_img_hover" style="position: relative; display: inline-block;">
															<a href="<?php the_permalink(); ?>">
																<?php
																	if( has_post_thumbnail() ){
																	the_post_thumbnail('javo-box-v', Array('class'=> 'group list-group-image item-thumbs img-responsive'));
																};?>
																<div class="img-on-ratings text-right">
																	<div class="javo-rating-registed-score" data-score="<?php echo javo_review::get( 'average');?>"></div>
																</div>

																<div class="javo_hover_body" style="position: absolute; top: 0px; left: 0px; z-index: 2; width: 100%; height: 100%; padding: 10px; margin: 0px; display: none; opacity: 0.3151841979620183; background-color: rgba(0, 0, 0, 0.4);">
																	<div class="javo_hover_content" style="cursor: pointer; display: table; width: 100%; height: 100%;" data-href="<?php the_permalink(); ?>">
																	<div class="javo_hover_content_link" style="display: table-cell; color: rgb(239, 239, 239); vertical-align: middle; font-size: 2em; text-align: center;"><i class="glyphicon glyphicon-plus"></i></div></div> <!-- javo_hover_content -->
																</div> <!-- javo_hover_body -->
															</a>
														</div> <!-- javo_img_hover -->
													</div> <!-- col-md-12 -->
												</div> <!-- row -->
											</div> <!-- main-box -->
										</div><!-- Panel Body -->


										<ul class="list-group">
											<li class="list-group-item">
												<div class="row">
													<div class="col-md-8">
														<?php echo javo_str_cut( get_the_title(), 20);?>


													</div>
													<div class="col-md-4 text-right">
														<div class="social-wrap javo-type-11-share">
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
														</div> <!-- sc-social-wrap -->
													</div> <!-- col-6-md -->
												</div><!-- row -->
											</li> <!-- list-group-item -->
										</ul> <!-- list-group -->

										<div class="panel-footer options-wrap">
											<div class="row">
												<ul class="options">
													<li class="col-md-6 col-sm-6 col-xs-6"><i class="javo-con category"></i>&nbsp;<?php echo javo_get_cat(get_the_ID(), 'item_category', __('No Category', 'javo_fr'));?></li>
													<li class="col-md-6 col-sm-6 col-xs-6 text-right"><i class="javo-con location"></i>&nbsp;<?php echo javo_get_cat(get_the_ID(), 'item_location', __('No Location', 'javo_fr'));?></li>
												</ul>
											</div>
										</div><!-- panel-footer -->
										<div class="javo-left-overlay">
											<div class="javo-txt-meta-area">
												<?php echo javo_get_cat(get_the_ID(), 'item_category', __('No Category', 'javo_fr'));?>
											</div> <!-- javo-txt-meta-area -->
											<div class="corner-wrap">
												<div class="corner"></div>
												<div class="corner-background"></div>
											</div> <!-- corner-wrap -->
										</div><!-- javo-left-overlay -->
									</div> <!-- panel -->
									</div> <!-- item col-md-4 -->
									<?php
								}; // Close while
							}else{

							}; // Emd While
							?>
							<!-- // grid/list -->


						</div>
					</div>
				</div>
			</div>
			<?php
		break; default:
		_e('Error', 'javo_fr');
	};

	$big = 999999999; // need an unlikely integer
	wp_reset_query();
	echo "<div class='javo_pagination'>".paginate_links( array(
		'base' => "%_%",
		'format' => '?paged=%#%',
		'current' => $page,
		'total' => $posts->max_num_pages
	))."</div>";

	$content = ob_get_clean();



	// Markers
	$javo_this_info_window_contents = new WP_Query($args);
	$markers = Array();
	if( $javo_this_info_window_contents->have_posts() ){
		while( $javo_this_info_window_contents->have_posts() ){
			$javo_this_info_window_contents->the_post();
			$javo_meta_query = new javo_GET_META( get_the_ID() );
			$javo_this_author_avatar_id = get_the_author_meta('avatar');
			$javo_this_author_name = sprintf('%s %s', get_the_author_meta('first_name'), get_the_author_meta('last_name'));
			ob_start();?>
				<div class="javo_somw_info">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-6">
							<h4><?php the_title();?></h4>
							<ul class="list-unstyled">
								<li class="jv-list-address"><div class="prp-meta"><?php echo get_post_meta( get_the_ID(), 'jv_item_address', true ); ?></div></li>
								<li class="jv-list-phone"><div class="prp-meta"><?php echo get_post_meta( get_the_ID(), 'jv_item_phone', true ); ?></div></li>
								<li class="jv-list-website"><div class="prp-meta"><a href="<?php echo get_post_meta( get_the_ID(), 'jv_item_website', true ); ?>"><?php echo get_post_meta( get_the_ID(), 'jv_item_website', true );?></a></div></li>
								<li class="jv-list-email"><div class="prp-meta"><a href="mailto:<?php echo get_post_meta( get_the_ID(), 'jv_item_email', true ); ?>"><?php echo get_post_meta( get_the_ID(), 'jv_item_email', true ); ?></a></div></li>
							</ul>
						</div><!-- /.col-md-8 -->
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="thumb">
								<a href="<?php the_permalink();?>" target="_blank">
									<?php
									if( has_post_thumbnail() )
									{
										the_post_thumbnail('javo-map-thumbnail');
									}else{
										printf('<img src="%s" style="width:150px; height:165px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));

									} ?>
								</a>
								<div class="img-in-text"><?php echo $javo_meta_query->cat('item_category', __('No Category', 'javo_fr'));?></div>
								<div class="javo-left-overlay">
									<div class="javo-txt-meta-area admin-color-setting"><?php echo $javo_meta_query->cat('item_location', __('No Location', 'javo_fr'));?></div>
									<div class="corner-wrap">
										<div class="corner admin-color-setting"></div>
										<div class="corner-background admin-color-setting"></div>
									</div> <!-- corner-wrap -->
								</div> <!-- javo-left-overlay -->
							</div> <!-- thumb -->
						</div><!-- /.col-md-4 -->
					</div><!-- /.row -->



				</div> <!-- javo_somw_info -->
			<?php
			$infoWindow = ob_get_clean();
			$latlng					= Array(
				'lat'				=> get_post_meta( get_the_ID(), 'jv_item_lat', true )
				, 'lng'				=> get_post_meta( get_the_ID(), 'jv_item_lng', true )
			);

			$javo_set_icon = '';
			$javo_marker_term_id = wp_get_post_terms( get_the_ID() , 'item_category');
			if( !empty( $javo_marker_term_id ) ){
				$javo_set_icon = get_option('javo_item_category_'.$javo_marker_term_id[0]->term_id.'_marker', '');
				if( $javo_set_icon == ''){
					$javo_set_icon = $javo_tso->get('map_marker', '');
				};
			};
			if( !empty( $latlng['lat'] ) &&
				!empty( $latlng['lng'] )
			){
				$markers[] = Array(
					'id'					=> 'mid_' . get_the_ID()
					, 'lat'					=> $latlng['lat']
					, 'lng'					=> $latlng['lng']
					, 'info'				=> Array(
						'content'			=> $infoWindow
						, 'post_title'		=> get_the_title()
					)
					, 'icon'				=> $javo_set_icon
				);
			}
		}; // End While
	}; // End If

	// Not found results
	ob_start();?>
	<div class="row">
		<div class="col-md-12 text-center">
			<?php _e( 'No result found. Please try again', 'javo_fr' ); ?>
		</div>

	</div>

	<?php
	$blank_content = ob_get_clean();
	$result = Array(
		"result"		=> "success"
		, "html"		=> $posts->found_posts > 0 ? $content: $blank_content
		, "markers"		=> $markers
	);
	echo json_encode($result);
	exit(0);

}