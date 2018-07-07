<?php
class javo_category_with_icon{
	static $javo_this_taxonomies;
	public function __construct(){
		add_shortcode('javo_category_with_icon', Array( 'javo_category_with_icon', 'javo_category_with_icon_callback' ));
	}
	static function javo_category_icon_callback()
	{
		if( empty( self::$javo_this_taxonomies ) ){ return false; }
		?>
		<style type="text/css">
			<?php
			foreach( self::$javo_this_taxonomies as $term ){
				$javo_icon = get_option( 'javo_item_category_'.$term->term_id.'_icon', '' );
				printf('div.javo-category-with-icon-listing[javo-category-shortcode-icon="%s"]:before{ content:"%s" !important; font-family:FontAwesome; position:absolute; left:-45px; top:10px; font-size:40px;}', $term->name, stripslashes($javo_icon));
			};?>
		</style>
		<?php
	}
	static function javo_category_with_icon_callback( $atts, $content=""){

		extract( shortcode_atts(Array(
			'result_map_type' => ''
			/*
			 *	Category Container Box Style
			 **********************************/
			, 'cbx_border'				=> '#428bca'
			, 'cbx_border_radius'		=> '5'
			, 'cbx_background'			=> '#fff'
			, 'cbx_hr'					=> '#428bca'
			, 'cbx_font'				=> '#000'


			/*
			one_row_item
			===================================
				DataType	: int
				Value		: 3 / 4
				Fixed Value	: true
			*/
			, 'one_row_item'			=> 3


			/*
			category_max_count
			===================================
				DataType	: int
				Value		: 0 ~ n
				Fixed Value	: false
			*/
			, 'category_max_count'		=> 5


			/*
			have_terms
			===================================
				DataType	: String
				Value		: Split(,)
				Fixed Value	: false
			*/
			, 'have_terms'				=> ''

		), $atts) );

		// Initialize Style
		$javo_cbx_css					= sprintf('
			border:solid 1px %s;
			background:%s;
			color:%s;
			border-radius:%spx;
			-webkit-border-radius:%spx;
			-moz-border-radius:%spx;
			-ms-border-radius:%spx;
			-o-border-radius:%spx;'
			/*== Variable Values ==*/
			, $cbx_border
			, $cbx_background
			, $cbx_font
			, $cbx_border_radius
			, $cbx_border_radius
			, $cbx_border_radius
			, $cbx_border_radius
			, $cbx_border_radius
		);
		$javo_cbx_hr_css				= sprintf('border-bottom:solid 1px %s;', $cbx_hr);
		$javo_cbx_font_css				= sprintf('color:%s;', $cbx_font);

		$javo_set_column				= $one_row_item == 3 ? 'col-md-4 col-sm-4 col-xs-6' : 'col-md-3 col-sm-4 col-xs-6';
		$have_terms						= @explode(',', $have_terms);
		$javo_have_terms				= Array();
		if( !empty($have_terms) ){
			foreach( $have_terms as $term){
				if( (int)$term <= 0 ){
					continue;
				};
				$javo_have_terms[]		= get_term( $term, 'item_category');
			};
		};
		$javo_get_categories			= !empty( $javo_have_terms )? $javo_have_terms : get_terms('item_category', Array('hide_empty'=> false, 'parent'=> false));

		self::$javo_this_taxonomies = $javo_get_categories;

		add_action('wp_footer', Array(__class__, 'javo_category_icon_callback'), 11);

		ob_start(); ?>
			<div class="javo-category-with-icon-list-wrap">
				<div class="row">
				<?php
				if( !empty( $javo_get_categories ) ){
					$i = 1;
					foreach( $javo_get_categories as $term ){
						?>
						<div class="<?php echo $javo_set_column;?>">
							<div class="panel panel-primary" >
								<div class="panel-body row">
									<div class="javo-category-icon-wrap col-md-2">
									</div>
									<div class="javo-category-with-icon-listing-wrap col-md-10">
										<div class="javo-category-with-icon-listing " javo-category-shortcode-icon="<?php echo $term->name;?>">
											<h3 class="panel-title text-center" style='margin:0px;'>
												<div class="javo-category-icon-listing-separator-wrap">	
													<span class="javo-category-icon-listing-separator"></span>
												</div>
												<a href="<?php echo apply_filters( 'javo_wpml_link', $result_map_type);?>/?category=<?php echo $term->term_id; ?>">
													<?php echo strtoupper($term->name); ?>
												</a>
											</h3>
											
											<?php
											$javo_this_children = Array(
												'hide_empty'	=> 0
												, 'parent'		=> $term->term_id
											);
											if( (int)$category_max_count > 0 ){
												$javo_this_children['number']	= (int)$category_max_count;
											}
											$javo_this_children = get_terms($term->taxonomy, $javo_this_children);

											if(!empty($javo_this_children) ){
												foreach($javo_this_children as $children){
													printf("<div class='row'>
														<div class='col-md-12'>
															<a href='%s' style='%s'>
																<div class='text'>%s</div>
															</a>
														</div></div>"
														, apply_filters( 'javo_wpml_link', $result_map_type).'?category='.get_term($children, $term->taxonomy)->term_id
														, $javo_cbx_font_css
														, get_term($children, $term->taxonomy)->name
													);

												}

											};
											if( count( $javo_this_children ) >= $category_max_count ){
												?>
												<div class="row javo-category-icon-listing-more">
													<div class="col-md-12 text-center categor">
														<div>...</div>
														<div>
														<?php printf('<a href="%s" style="%s">%s</a>', get_term_link($term), $javo_cbx_font_css, __('More', 'javo_fr'));?></div>
													</div><!-- /.col-md-12 -->
												</div><!-- /.row -->
												<?php
											};?>
										</div><!-- /.javo-archive-category-listing -->
									</div><!--/.javo-archive-category-listing-wrap-->
								</div><!-- /.panel-body -->

							</div><!-- /.panel -->
						</div><!-- /.col-md-4 -->
						<?php
						if( $i % (int)$one_row_item == 0){ printf('</div><div class="row">'); };
						$i++;
					};	// End Foreach
				};		// End IF
				?>
				</div><!-- /.row -->
			</div><!-- /.javo-directory-list-wrap -->
			<?php
		return ob_get_clean();
	}
};
new javo_category_with_icon();