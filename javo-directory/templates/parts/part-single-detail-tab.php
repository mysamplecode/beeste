<?php
global $javo_custom_field
		, $post
		, $javo_tso
		, $javo_video_query
		, $javo_favorite;
$javo_this_author				= get_userdata($post->post_author);
$javo_this_author_avatar_id		= get_the_author_meta('avatar');
$javo_directory_query			= new javo_get_meta( get_the_ID() );
$javo_this_item_tab_slide_type = 'type2';

{
	$javo_detail_item_values	= Array(
		'jv_item_address'		=> Array(
			'label'				=> __( "Address", 'javo_fr' )
			, 'value'			=> get_post_meta( get_the_ID(), 'jv_item_address', true )
			, 'class'			=> 'single-contact-address'
		)
		, 'jv_item_phone'		=> Array(
			'label'				=> __( "Phone", 'javo_fr' )
			, 'value'			=> get_post_meta( get_the_ID(), 'jv_item_phone', true )
			, 'class'			=> 'single-contact-phone'
			, 'href'			=> sprintf( "tel:%s", get_post_meta( get_the_ID(), 'jv_item_phone', true ) )
		)
		, 'jv_item_email'		=> Array(
			'label'				=> __( "E-mail", 'javo_fr' )
			, 'value'			=> get_post_meta( get_the_ID(), 'jv_item_email', true )
			, 'class'			=> 'single-contact-email'
			, 'href'			=> sprintf( "mailto:%s", get_post_meta( get_the_ID(), 'jv_item_email', true ) )
		)
		, 'jv_item_website'		=> Array(
			'label'				=> __( "Website", 'javo_fr' )
			, 'value'			=> get_post_meta( get_the_ID(), 'jv_item_website', true )
			, 'class'			=> 'single-contact-website'
			, 'href'			=> get_post_meta( get_the_ID(), 'jv_item_website', true )
		)
		, 'item_category'		=> Array(
			'label'				=> __( "Category", 'javo_fr' )
			, 'value'			=> $javo_directory_query->cat('item_category', __('No Category','javo_fr'), false, false )
			, 'class'			=> 'single-contact-category'
		)
		, 'item_location'		=> Array(
			'label'				=> __( "Location", 'javo_fr' )
			, 'value'			=> $javo_directory_query->cat('item_location', __('No Location','javo_fr'), false, false )
			, 'class'			=> 'single-contact-location'
		)
		, 'item_tags'			=> Array(
			'label'				=> __( "Tags", 'javo_fr' )
			, 'value'			=> $javo_directory_query->Tag('string')
			, 'class'			=> 'single-contact-tag'
		)
	);
	$javo_detail_item_metas		= apply_filters( 'javo_single_detail_item_args', $javo_detail_item_values, get_the_ID(), $javo_directory_query );
} ?>
<!-- slide -->
	<div class="row">
		<div class="col-md-12">
			<?php get_template_part('templates/parts/part', 'single-detail-tab-sliders');?>
		</div> <!-- col-md-12 -->
	</div> <!-- row -->

	<div class="single-sns-wrap-div <?php if($javo_tso->get('claim_use')=='use') echo 'before-claim';?>">
		<span class="javo-archive-sns-wrap social-wrap pull-right">
			<i class="sns-facebook" data-title="<?php the_title();?>" data-url="<?php echo 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>" >
				<a class="facebook javo-tooltip" title="<?php _e('Share Facebook', 'javo_fr');?>"></a>
			</i>
			<i class="sns-twitter" data-title="<?php the_title();?>" data-url="<?php 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>">
				<a class="twitter javo-tooltip" title="<?php _e('Share Twitter', 'javo_fr');?>"></a>
			</i>
			<i class="sns-heart">
				<a class="javo-tooltip favorite javo_favorite<?php echo $javo_favorite->on( get_the_ID(), ' saved');?>"  data-post-id="<?php the_ID();?>" title="<?php _e('Add My Favorite', 'javo_fr');?>"></a>
			</i>
		</span>
	</div><!-- single-sns-wrap-div -->
	<!-- slide end -->
	<?php
	if(
		$javo_tso->get( 'claim_use' ) == 'use' &&
		get_post_meta( get_the_ID(), 'claimed', true ) != 'yes'
	): ?>
		<div class="claim_btn_wrap clearfix">
			<a href="#" data-toggle="modal" data-target="#jv-claim-reveal" class="btn btn-primary javo-tooltip pull-right" title="" data-original-title="<?php _e('Claim This Business','javo_fr'); ?>"><i class="glyphicon glyphicon-briefcase"></i>&nbsp;<?php _e('Own This Business?', 'javo_fr'); ?></a>
		</div> <!-- claim_btn_wrap -->
	<?php endif; ?>
	<div class="row">
		<div class="col-md-12 description-part">
			<div class="item-single-details-box">
				<h4 class="detail-titles"><?php _e('Description', 'javo_fr'); ?></h4>
				<div class="javo-left-overlay">
					<div class="javo-txt-meta-area admin-color-setting"><?php _e('Description', 'javo_fr'); ?></div> <!-- javo-txt-meta-area -->
					<div class="corner-wrap">
						<div class="corner admin-color-setting"></div>
						<div class="corner-background admin-color-setting"></div>
					</div> <!-- corner-wrap -->
				</div> <!-- javo-left-overlay -->
				<!-- <div class="title-box"><?php _e('Description', 'javo_fr'); ?></div> -->
				<div class="inner-items">
					<?php the_content();?>
				</div> <!-- inner-items -->
			</div> <!-- item-single-details-box -->
		</div> <!-- col-md-12 -->
		<?php
		if( $javo_video_query->get('single_position', 'slide') == 'descript' ){
			?>

		<div class="col-md-12"> <!-- video start -->
			<div class="item-single-details-box">
				<h4 class="detail-titles"><?php _e('Video', 'javo_fr'); ?></h4>
				<div class="javo-left-overlay">
					<div class="javo-txt-meta-area admin-color-setting"><?php _e('Video', 'javo_fr'); ?></div> <!-- javo-txt-meta-area -->
					<div class="corner-wrap">
						<div class="corner admin-color-setting"></div>
						<div class="corner-background admin-color-setting"></div>
					</div> <!-- corner-wrap -->
				</div> <!-- javo-left-overlay -->
				<!-- <div class="title-box"><?php _e('Description', 'javo_fr'); ?></div> -->
				<div class="inner-items">
					<?php echo $javo_video_query->get('html'); ?>
				</div> <!-- inner-items -->
			</div> <!-- item-single-details-box -->
		</div> <!-- col-md-12 // video end -->


			<?php
		};?>

		<div class="col-md-12 contact-part">
			<div class="item-single-details-box">
				<h4 class="detail-titles"><?php _e('Contact', 'javo_fr'); ?></h4>
				<div class="javo-left-overlay">
					<div class="javo-txt-meta-area admin-color-setting"><?php _e('Contact', 'javo_fr'); ?></div> <!-- javo-txt-meta-area -->
					<div class="corner-wrap">
						<div class="corner admin-color-setting"></div>
						<div class="corner-background admin-color-setting"></div>
					</div> <!-- corner-wrap -->
				</div> <!-- javo-left-overlay -->

				<div class="inner-items">
					<ul>
						<?php
						if( !empty( $javo_detail_item_metas ) )
						{
							foreach( $javo_detail_item_metas as $info )
							{
                                                            if (strpos(  strtoupper($info['value']),"N/A")!==FALSE ||
                                                                    (strpos(  strtoupper($info['value']),"NOT AVAILABLE")!==FALSE)
                                                               )
                                                                continue;
                                                            
								if( !empty( $info['value'] ))
								{
									echo "<li class=\"{$info['class']}\">";
										if( isset( $info['href'] ) ) {
											$__href	= esc_url( $info[ 'href' ] );
											if($info['class']=='single-contact-website')
												echo "<span>{$info['label']}</span> <a href=\"{$__href}\" target=\"_blank\">{$info['value']}</a>";
											else
												echo "<span>{$info['label']}</span> <a href=\"{$__href}\" target=\"_self\">{$info['value']}</a>";
										} else {
											echo "<span>{$info['label']}</span> {$info['value']}";
										}
									echo "</li>";
								}
							}
						} ?>
					</ul>
				</div>
			</div>
		</div>
		<?php
		if( $javo_video_query->get('single_position', 'slide') == 'contact' ){
			?>
			<div class="col-md-12"> <!-- video start -->
				<div class="item-single-details-box">
					<h4 class="detail-titles"><?php _e('Video', 'javo_fr'); ?></h4>
					<div class="javo-left-overlay">
						<div class="javo-txt-meta-area admin-color-setting"><?php _e('Video', 'javo_fr'); ?></div> <!-- javo-txt-meta-area -->
						<div class="corner-wrap">
							<div class="corner admin-color-setting"></div>
							<div class="corner-background admin-color-setting"></div>
						</div> <!-- corner-wrap -->
					</div> <!-- javo-left-overlay -->
					<!-- <div class="title-box"><?php _e('Description', 'javo_fr'); ?></div> -->
					<div class="inner-items">
						<?php echo $javo_video_query->get('html'); ?>
					</div> <!-- inner-items -->
				</div> <!-- item-single-details-box -->
			</div> <!-- col-md-12 // video end -->
			<?php }; ?>
			<div class="col-md-12 custom-part">
			<?php
			$javo_integer = 0;
			$javo_el_childrens = "";
			$javo_custom_field = javo_custom_field::gets();

			if( !empty( $javo_custom_field ) ){
				foreach($javo_custom_field as $field){
					$javo_marge_value = '';
					$javo_this_value = !empty( $field['value'] ) ? (Array) $field['value'] : Array();
					if(
						empty( $javo_this_value ) || $javo_this_value == '' &&
						( !empty( $field['type'] ) && $field['type'] != "group" ) 
                                                
					){
						continue;
					}
					if($field['type']!="group"){
						$javo_integer++;
					}
					foreach( $javo_this_value as $value)
					{
						$javo_marge_value .= $value . ', ';
					}

					$javo_marge_value = substr( trim( $javo_marge_value ), 0, -1 );
                                        
                                        if (strpos(  strtoupper($javo_marge_value),"N/A")!==FALSE ||
                                               (strpos(  strtoupper($javo_marge_value),"NOT AVAILABLE")!==FALSE)
                                            )
                                                continue;
                                        
					if( !empty( $field['type'] ) && $field['type'] == "group" )
					{
						$javo_el_childrens .= "<li><h5>{$field['label']}</h5></li>";

					}else{
                                                if(strtolower($field['label'])=="60 min massage"){
                                                    $javo_marge_value = str_replace("$","", $javo_marge_value);
                                                    $javo_marge_value = "$".$javo_marge_value;
                                                }
						$javo_el_childrens .= "<li class=\"{$field['css']}\"><span>{$field['label']}</span>{$javo_marge_value}</li>";
					}

				} // End Foreach
			}
			if( (int)$javo_integer > 0 ){
				?>
				<div class="item-single-details-box">
					<h4 class="detail-titles"><?php echo $javo_tso->get('field_caption', __('Additional Information', 'javo_fr'))?></h4>
					<div class="javo-left-overlay">
						<div class="javo-txt-meta-area admin-color-setting"><?php echo $javo_tso->get('field_caption', __('Aditional Information', 'javo_fr'))?></div> <!-- javo-txt-meta-area -->
						<div class="corner-wrap">
							<div class="corner admin-color-setting"></div>
							<div class="corner-background admin-color-setting"></div>
						</div> <!-- corner-wrap -->
					</div> <!-- javo-left-overlay -->
					<div class="inner-items">
						<ul><?php echo $javo_el_childrens;?></ul>
					</div>
				</div>
				<?php
			};// End If?>

		</div> <!-- col-md-12 -->
	</div> <!-- row -->