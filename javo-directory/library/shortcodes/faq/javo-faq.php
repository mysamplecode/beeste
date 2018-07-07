<?php
class javo_faq{
	public function __construct(){
		add_shortcode("javo_faq", Array($this, "javo_faq_callback"));
	}
	public function javo_faq_callback($atts, $content=""){
		global $javo_tso;
		extract(shortcode_atts(
			Array(
				'title'=>''
				, 'sub_title'=>''
				, 'title_text_color'=>'#000'
				, 'sub_title_text_color'=>'#000'
				, 'text_color' => '#aaa'
				, 'line_color'=> '#fff'
			), $atts)
		);

		wp_enqueue_script("javo-faq-js", JAVO_THEME_DIR."/library/shortcodes/faq/javo-faq.js", "1.0", false);
		wp_enqueue_style( 'javo-faq-css', JAVO_THEME_DIR."/library/shortcodes/faq/javo-faq.css", '1.0' );
		$javo_fap_args = Array(
			'post_type'=> 'jv_faqs'
			, 'post_status'=> 'publish'
			, 'posts_per_page'=> -1
		);
		ob_start();?>
		<style>
		.faq-cat-content .panel-body>p{color:<?php echo $text_color;?> !important;}
		</style>
		<?php echo apply_filters('javo_shortcode_title', $title, $sub_title, Array('title'=>'color:'.$title_text_color.';', 'subtitle'=>'color:'.$sub_title_text_color.';', 'line'=>'border-color:'.$line_color.';'));?>
			<div class="javo-faq">
				<div class="row">
					<div class="col-md-12">
						<!-- Nav tabs category -->
						<ul class="nav nav-tabs faq-cat-tabs">
							<?php
							$javo_faq_categories = get_terms( 'jv_faq_category' );
							?>
							<li class="active"><a href="#javo-faq-all" data-toggle="tab"><?php _e("All", "javo_fr"); ?></a></li>
							<?php
							foreach( $javo_faq_categories as $idx=> $cat ){
								printf('<li><a href="#javo-faq-%s" data-toggle="tab">%s</a></li>'
									, $cat->term_id, $cat->name
								);
							};
							wp_reset_query();?>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content faq-cat-content">
							<!-- ALL -->
							<div class="tab-pane active in fade" id="javo-faq-all">
								<div class="panel-group" id="javo-faq-con-all">
									<?php
									$javo_faq = new WP_Query($javo_fap_args);
									if( $javo_faq->have_posts() ){
										while( $javo_faq->have_posts() ){
											$javo_faq->the_post();?>
											<div class="panel panel-default panel-faq">
												<div class="panel-heading">
													<a data-toggle="collapse" data-parent="#javo-faq-con-all" href="<?php echo '#javo-faq-sub-con-'.get_the_ID();?>">
														<h4 class="panel-title">
															<?php the_title();?>
															<span class="pull-left"><i class="glyphicon glyphicon-plus"></i></span>
														</h4>
													</a>
												</div>
												<div id="<?php echo 'javo-faq-sub-con-'.get_the_ID();?>" class="panel-collapse collapse">
													<div class="panel-body">
														<?php the_content();?>
													</div>
												</div>
											</div>
									<?php
										};
									};
									wp_reset_query();?>
								</div>
							</div>
						<?php
						foreach( $javo_faq_categories as $idx=>$cat ){
							?>
							<div class="tab-pane fade" id="<?php echo 'javo-faq-'.$cat->term_id;?>">
								<div class="panel-group" id="<?php echo 'javo-faq-con-'.$cat->term_id;?>">
									<?php
									$javo_fap_args['tax_query'] = Array(
										Array(
											'taxonomy'=> 'jv_faq_category'
											, 'field'=> 'term_id'
											, 'terms' =>$cat->term_id
										)
									);
									$javo_faq = new wp_query($javo_fap_args);
									if( $javo_faq->have_posts() ){

										while( $javo_faq->have_posts() ){
											$javo_faq->the_post(); ?>
											<div class="panel panel-default panel-faq">
												<div class="panel-heading">
													<a data-toggle="collapse" data-parent="<?php echo '#javo-faq-con-'.$cat->term_id;?>" href="#javo-faq-sub-con-<?php echo $cat->term_id.'-'.get_the_ID();?>">
														<h4 class="panel-title">
															<?php the_title();?>
															<span class="pull-left"><i class="glyphicon glyphicon-plus"></i></span>
														</h4>
													</a>
												</div>
												<div id="javo-faq-sub-con-<?php echo $cat->term_id;?>-<?php echo get_the_ID();?>" class="panel-collapse collapse">
													<div class="panel-body">
														<?php the_content();?>
													</div>
												</div>
											</div>
									<?php
										};
									};?>
								</div>
							</div>
							<?php
						};?>
						</div>
					</div>
				</div>
			</div>
		<?php
		$content = ob_get_clean();
		wp_reset_query();
		return $content;
	}
}
new javo_faq();