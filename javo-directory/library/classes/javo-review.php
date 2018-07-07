<?php
class javo_review
{
	const MAX_SCORE	= 5;

	public function __construct()
	{
		// Enqueue Scripts
		add_action( 'admin_enqueue_scripts'				, Array( __CLASS__, 'admin_enqueue' ) );

		// Review Submit
		add_action( 'wp_ajax_nopriv_add_item_review'	, Array( __CLASS__, 'register_review' ) );
		add_action( 'wp_ajax_add_item_review'			, Array( __CLASS__, 'register_review' ) );

		// Review reply Submit
		add_action( 'wp_ajax_nopriv_add_comment_reply'	, Array( __CLASS__, 'register_review_reply' ) );
		add_action( 'wp_ajax_add_comment_reply'			, Array( __CLASS__, 'register_review_reply' ) );

		// Review Listings
		add_action( 'wp_ajax_nopriv_get_item_review'	, Array( __CLASS__, 'listings_review' ) );
		add_action( 'wp_ajax_get_item_review'			, Array( __CLASS__, 'listings_review' ) );

		// Review Listings
		add_action(
			'wp_ajax_nopriv_javo_update_review'
			, Array( __CLASS__, 'update_review_contents' )
		);
		add_action(
			'wp_ajax_javo_update_review'
			, Array( __CLASS__, 'update_review_contents' )
		);

		// Review Scripts
		add_action( 'wp_footer'							, Array( __CLASS__, 'scripts' ) );
		add_action( 'javo_save_review'					, Array( __CLASS__, 'save_rating' ), 10, 2 );
		add_filter( 'javo_review_add_rating_meta'		, Array( __CLASS__, 'add_rating_meta' ), 10, 2 );
		add_action( 'update_rating'						, Array( __CLASS__, 'update_rating' ), 10 , 2 );

		// Trigger
		add_action( 'transition_comment_status'			, Array( __CLASS__, 'modify_hook_trig' ), 10, 3 );

		// Admin
		add_action( 'add_meta_boxes'					, Array( __CLASS__, 'comment_meta_boxes' ) );
		add_action( 'edit_comment'						, Array( __CLASS__, 'comment_meta_update' ) );

		// Display
		add_action( 'javo_review_scores_display'		, Array( __CLASS__, 'javo_review_scores_display_callback' ), 10, 3 );

	}

	public static function setup_reviewData( &$review )
	{
		if( ! $review->author = new WP_User( $review->user_id ) )
		{
			$review->author				= new stdClass();
			$review->author->ID			= 0;
			$review->author->avatar		= 0;
		}
		$review->average	= get_comment_meta( $review->comment_ID, 'rating_average', true );
		$review->scores		= get_comment_meta( $review->comment_ID, 'rating_scores', true );
	}

	public static function javo_review_scores_display_callback(
		$review
		, $show_title	= false
		, $total		= false
	){
		if( $total ){
			?>
			<div class="row">
				<div class="col-md-6 text-right">
					<?php _e('Total', 'javo_fr');?>
				</div>
				<div class="col-md-6"><?php echo get_post_meta( $commend_id, 'rating_average', true);?></div>
			</div> <!-- row -->
			<?php
		};

		if( !empty( $review->scores ) ){
			foreach( $review->scores as $label => $value )
			{
				echo "<div class=\"row\">";

					if( $show_title )
						echo "<div class=\"col-md-6 col-sm-6 col-xs-6 text-right\">{$label}</div>";

					printf(
						"<div class=\"%s javo-tooltip\" title=\"%s\" data-direction=\"left\">"
						. "<div class=\"javo-rating-registed-score\" data-score=\"%s\"></div></div>"
						, ( $show_title ? 'col-md-6 col-sm-6 col-xs-6' : 'col-md-12 col-sm-12 col-xs-12' )
						, $label
						, $value
					);
				echo "</div>";
			}; // End Foreach
		}; // End If
	}

	public static function admin_enqueue()
	{
		wp_enqueue_script(
			'javo-wp-media-tirgger'
			, get_template_directory_uri() . "/assets/js/javo-wp-media-control.js"
			, false
			, '2.0.1'
			, true
		);
	}

	public static function comment_meta_boxes()
	{
		add_meta_box(
			'javo-review-args-box'
			, __( "Review Detail", 'javo_fr' )
			, Array( __CLASS__, 'comment_meta_box' )
			, 'comment'
			, 'normal'
			, 'high'
		);
	}

	public static function scoresCalculation( $scores_values )
	{
		global $javo_tso;

		$javo_filtered_scores	= Array();
		$javo_results			= Array();
		$javo_rating_fields		= $javo_tso->get( 'rating_field', Array() );


		if( empty( $javo_rating_fields ) )
			return false;

		if( empty( $scores_values ) || !is_Array( $scores_values ) )
			return false;

		foreach( $javo_rating_fields as $field )
			if( isset( $scores_values[ $field ] ) )
				$javo_filtered_scores[ $field ] = floatVal( $scores_values[ $field ] );

		$javo_results['scores']		= $javo_filtered_scores;
		$javo_results['count']		= sizeof( $javo_filtered_scores );
		$javo_results['total']		= Array_Sum( $javo_filtered_scores );
		@$javo_results['average']	= floatVal( Array_Sum( $javo_filtered_scores ) / sizeof( $javo_filtered_scores ) );
		return $javo_results;
	}

	public static function comment_meta_update( $comment_id )
	{
		$javo_query				= new javo_Array( $_POST );
		$javo_rating_scores		= $javo_query->get( 'javo_review_score' );

		if( $result = self::scoresCalculation( $javo_rating_scores ) )
		{
			update_comment_meta( $comment_id, 'rating_scores'	, $result['scores'] );
			update_comment_meta( $comment_id, 'rating_total'	, $result['total'] );
			update_comment_meta( $comment_id, 'rating_average'	, $result['average'] );
		}

		update_comment_meta( $comment_id, 'detail_images', $javo_query->get( 'javo_cmt_image' ) );
		do_action( 'update_rating', $comment_id );
	}

	public static function comment_meta_box( $comment )
	{

		global $javo_tso;

		$comment_id			= (int) $comment->comment_ID;
		$cmt_ratings		= get_comment_meta( $comment_id, 'rating_scores', true );
		$cmt_images			= get_comment_meta( $comment_id, 'detail_images', true );

		ob_start();
		?>
		<div id="postcustomstuff">
			<table id="list-table">
				<thead>
					<tr>
						<th class="left"><?php _e('Option Name', 'javo_fr');?></th>
						<th><?php _e('Value', 'javo_fr');?></th>
					</tr>
				</thead>
				<tbody id="the-list" data-wp-lists="list:meta">
					<?php if( $javo_cmt_fields	= $javo_tso->get( 'rating_field' ) ) : ?>
						<tr>
							<td valign="top"><p><?php _e("Rating Scores", 'javo_fr');?></p></td>
							<td valign="top">
								<table class="javo-post-header-meta">
									<tbody>
										<?php
										foreach( $javo_cmt_fields as $field )
										{
											$javo_cmt_rating		= 0;
											if( is_Array( $cmt_ratings ) && !empty( $cmt_ratings[ $field ] ) )
												$javo_cmt_rating	= (float) $cmt_ratings[ $field ];

											echo "<tr>";
												echo "<td valign=\"middle\">{$field}</td>";
												echo "<td valign=\"middle\">";
													echo "<input" . ' ';
													echo "type	=\"text\"" . ' ';
													echo "name	=\"javo_review_score[{$field}]\"". ' ';
													echo "value	=\"{$javo_cmt_rating}\">";
												echo "</td>";
												echo "<td valign=\"middle\"> /".self::MAX_SCORE."</td>";

											echo "</tr>";

										} ?>
									</tbody>
								</table>
							</td>
						</tr>
					<?php endif; ?>
					<tr>
						<td valign="top"><p><?php _e("Upload Images", 'javo_fr');?></p></td>
						<td valign="top">
							<button type="button" class="button button-primary" data-javo-wp-media-add>
								<?php _e( "Add Image", 'javo_fr' );?>
							</button>
							<div id="javo-images-item-container">
								<?php
								if( !empty( $cmt_images ) )
								{
									foreach( $cmt_images as $image_id )
									{
										if( $javo_image_src = wp_get_attachment_image_src( $image_id , 'thumbnail' ) )
											$javo_image_src = $javo_image_src[0];

										if( false !== $javo_image_src )
										{
											echo "<div class='item'>";
												echo "<p><img src=\"{$javo_image_src}\"></p>";
												echo "<input name='javo_cmt_image[]' value='{$image_id}' type='hidden'>";
												echo "<button type=\"button\" class=\"button\" data-javo-wp-media-del>";
													_e( "Delete", 'javo_fr' );
												echo "</button>";
											echo "</div>";
										}
									}
								} ?>
							</div>
						</td>
					</tr>
				</tbody>
			</table><!-- /#list-table -->
		</div><!-- /#postcustomstuff-->

		<script type="text/html" id="javo-review-image-container">
			<div class="item">
				<p><img src="{image_src}"></p>
				<input type="hidden" name="{image_input_name}" value="{image_id}">
				<button type="button" class="button" data-javo-wp-media-del>
					<?php _e("Delete", 'javo_fr');?></button>
			</div><!-- /.item-container -->
		</script>

		<script type="text/javascript">
		jQuery( function( $ ) {
			jQuery.javo_wp_media({
				template			: $( "#javo-review-image-container" )
				, container			: $( "#javo-images-item-container" )
				, input_name		: "javo_cmt_image[]"
				/* Default
				, add_button		: $( "[data-javo-wp-media-add]" )
				, delete_button		: $( "[data-javo-wp-media-del]" )*/
			});
		});
		</script>

		<?php
		ob_end_flush();
	}

	public static function modify_hook_trig( $old, $new, $comment ) {
		do_action( 'update_rating', $comment->comment_ID );
	}

	public static function add_rating_meta( $comment_id, $args )
	{
		global $javo_tso;

		if( ! (int) $comment_id )
			return $comment_id;

		{
			$_comment					= get_comment( $comment_id );
			$_cmt_author				= new WP_User( $_comment->user_id );

			$javo_avatar_src			= $javo_tso->get( 'no_image', JAVO_IMG_DIR.'/no-image.png' );

			if( (int) $_cmt_author->avatar > 0 )
			{
				if( $javo_avatar_meta = wp_get_attachment_image_src( $_cmt_author->avatar, 'javo-avatar' ) )
				{
					if( '' !== ( $src = $javo_avatar_meta[0] ) )
						$javo_avatar_src	= $src;
				}
			}

			$args[ 'rating_sum' ]		= get_comment_meta( $comment_id, 'rating_total', true);
			$args[ 'rating_average' ]	= get_comment_meta( $comment_id, 'rating_average', true);
			$args[ 'ratings' ]			= get_comment_meta( $comment_id, 'rating_scores', true);
			$args[ 'avatar' ]			= $javo_avatar_src;
		}

		/* Images */{
			$javo_cmt_images			= get_comment_meta( $comment_id, 'detail_images', true );
			if( !empty( $javo_cmt_images ) && is_Array( $javo_cmt_images ) )
			{
				ob_start();
				?>
				<div class="row review-thumbnails-inner">
					<?php
					$javo_integer = 0;
					foreach( $javo_cmt_images as $attach_id )
					{
						if(
							( $javo_image_src = wp_get_attachment_image_src( $attach_id , Array( 170, 170 ) ) ) &&
							( $javo_full_image_src = wp_get_attachment_image_src( $attach_id , 'full' ) )
						) {
							$javo_image_src = $javo_image_src[0];
							$javo_full_image_src = $javo_full_image_src[0];
						}

						if( false !== $javo_image_src )
						{
							$javo_integer++;

							echo "<div class=\"col-md-4 col-xs-4\">";
								echo "<div class=\"javo-thb\" style=\"background-image:url({$javo_image_src}); height:120px;cursor:pointer;\" href=\"{$javo_full_image_src}\">";
								echo "</div>";
							echo "</div>";

							if( $javo_integer % 3 == 0 )
								echo "</div><div class=\"row review-thumbnails-inner\">";
						}
					} ?>
				</div><!-- /.row-->
				<?php
				$args[ 'thumbnails' ]		= ob_get_clean();
			}
		}

		return $args;
	}

	public static function register_review()
	{
		check_ajax_referer( 'javo-write-review', 'nonce' );

		global $javo_tso;

		$response					= Array();
		$javo_query					= new javo_Array( $_POST );
		$post_id					= $javo_query->get( 'post_id', 0 );

		if( (int) $post_id == 0 )
			die( json_encode( $response ) );

		$comment_approved			= $javo_tso->get( 'approve_review', false ) === 'approve';

		$javo_cmt_author			= $javo_query->get(
			'comment_author'
			, __( "Anonymous", 'javo_fr' )
		);

		$javo_cmt_author_id			= 0;

		if( is_user_logged_in() ) {
			if( (boolean) $javo_cmt_author_id = get_current_user_id() ) {
				$javo_cmt_author		= new WP_User( $javo_cmt_author_id );
				$javo_cmt_author		= $javo_cmt_author->display_name;
			}
		}

		$args							= Array(
			'user_id'					=> $javo_cmt_author_id
			, 'comment_type'			=> $javo_cmt_author_id > 0 ? "javo-member-review" : ''
			, 'comment_post_ID'			=> $post_id
			, 'comment_author'			=> $javo_cmt_author
			, 'comment_content'			=> $javo_query->get( 'comment_content', '' )
			, 'comment_author_email'	=> $javo_query->get( 'comment_author_email', '' )
			, 'comment_author_url'		=> esc_url( $javo_query->get( 'comment_author_url', '' ) )
			, 'comment_approved'		=> intVal( $comment_approved )
		);

		if( $comment_id = wp_insert_comment( $args ) )
		{
			$_comment				= get_comment( $comment_id );
			$_data_meta				= Array(
				'id'				=> $_comment->comment_ID
				, 'author'			=> $_comment->comment_author
				, 'content'			=> $_comment->comment_content
				, 'date'			=> get_comment_date( false, $_comment )
			);

			// Register Successfully
			$response['state']		= true;
			$response['comment_id']	= $comment_id;
			$response['author']		= $javo_cmt_author;
			$response['content']	= $javo_query->get( 'comment_content', '' );
			$response['approve']	= (boolean)$comment_approved;

			update_comment_meta( $comment_id, 'detail_images', $javo_query->get( 'javo_dim_detail', Array() ) );

			do_action( 'javo_save_review', $comment_id, $javo_query );

			$response['data']		= apply_filters(
				'javo_review_add_rating_meta'
				, $comment_id
				, $_data_meta
			);
		}

		$response = apply_filters( 'javo_save_review_result', $response );

		die( json_encode( $response ) );
	}

	public static function register_review_reply()
	{
		global $javo_tso;

		$response					= Array();
		$javo_query					= new javo_Array( $_POST );
		$comment_id					= $javo_query->get( 'comment_id', 0 );

		$comment_approved			= $javo_tso->get( 'approve_review', false ) === 'approve';

		if( (int) $comment_id == 0 )
			die( json_encode( $response ) );

		$response['state']			= 'failed';

		$javo_cmt_author_id			= 0;

		$javo_cmt_author			= $javo_query->get(
			'comment_author'
			, __( "Anonymous", 'javo_fr' )
		);

		if( is_user_logged_in() ) {
			if( (boolean) $javo_cmt_author_id = get_current_user_id() ) {
				$javo_cmt_author	= new WP_User( $javo_cmt_author_id );
				$javo_cmt_author	= $javo_cmt_author->display_name;
			}
		}

		$args						= Array(
			'user_id'				=> $javo_cmt_author_id
			, 'comment_post_ID'		=> $javo_query->get( 'post_id', 0 )
			, 'comment_parent'		=> $comment_id
			, 'comment_author'		=> $javo_cmt_author
			, 'comment_content'		=> $javo_query->get( 'content', '' )
			, 'comment_approved'	=> intVal( $comment_approved )
		);

		if( $comment_id = wp_insert_comment( $args ) )
		{
			$_comment				= get_comment( $comment_id );

			$response['state']		= 'success';
			$response['approve']	= (boolean) $comment_approved;
			$response['data']		= Array(
				'id'				=> "ID=>".$comment_id
				, 'author'			=> $_comment->comment_author
				, 'content'			=> $_comment->comment_content
				, 'date'			=> sprintf(
					__( "%s ago" , 'javo_fr' )
					, human_time_diff( get_comment_date( 'U', $_comment ), current_time( 'timestamp' ) )
				)
			);
		}

		die( json_encode( $response ) );
	}

	public static function save_rating( $comment_id, $query =null )
	{
		global $javo_tso;

		if( ! $comment_id )
			return;

		$javo_scores					= (Array) $query->get( 'javo_rats', Array() );
		$javo_rating_scores				= Array();

		foreach( $javo_scores as $index => $field )
			$javo_rating_scores[ $field['label'] ] = $field['score'];


		if( $result = self::scoresCalculation( $javo_rating_scores ) )
		{
			update_comment_meta( $comment_id, 'rating_scores'	, $result['scores'] );
			update_comment_meta( $comment_id, 'rating_total'	, $result['total'] );
			update_comment_meta( $comment_id, 'rating_average'	, $result['average'] );
		}

		do_action( 'update_rating', $comment_id );
	}

	public static function listings_review()
	{
		global $javo_tso;

		global $wpdb;

		check_ajax_referer( 'javo-listings-review', 'nonce' );

		$response					= Array();
		$javo_query					= new javo_Array( $_POST );
		$post_id					= $javo_query->get( 'post_id', 0 );

		if( (int) $post_id == 0 )
			die( json_encode( $response ) );

		$javo_comments_args			= Array(
			'post_id'				=> $post_id
			, 'parent'				=> 0
			, 'status'				=> 'approve'
			, 'number'				=> 5
			, 'offset'				=> (int) $javo_query->get( 'offset', 0 )
		);

		$response['offset']			= $javo_query->get( 'offset' );

		$javo_get_comments			= get_comments( $javo_comments_args );

		$javo_comment				= Array();

		foreach( $javo_get_comments as $comment )
		{
			$_this_relies_args		= Array(
				'post_id'			=> $post_id
				, 'status'			=> 'approve'
				, 'parent'			=> $comment->comment_ID
			);

			$comment_reply			= Array();

			if( $_this_replies = get_comments( $_this_relies_args ) )
			{
				foreach( $_this_replies as $reply )
				{
					$comment_reply[]	= Array(
						'author'		=> $reply->comment_author
						, 'content'		=> $reply->comment_content
						, 'date'		=> sprintf(
							__( "%s ago" , 'javo_fr' )
							, human_time_diff( get_comment_date( 'U', $reply ), current_time('timestamp') )
						)
					);
				}
			}

			$_data_meta				= Array(
				'id'				=> $comment->comment_ID
				, 'author'			=> $comment->comment_author
				, 'content'			=> $comment->comment_content
				, 'date'			=> sprintf(
					__( "%s ago" , 'javo_fr' )
					, human_time_diff( get_comment_date( 'U', $comment ), current_time('timestamp') )
				)
				, 'reply'			=> $comment_reply
				, 'is_author'		=> get_current_user_id() === (int) $comment->user_id && (int) $comment->user_id > 0
			);

			$javo_comment[]			= apply_filters( 'javo_review_add_rating_meta', $comment->comment_ID, $_data_meta );
		}

		$response['state']			= true;
		$response['data']			= $javo_comment;

		die( json_encode( $response ) );
	}

	public static function update_review_contents()
	{
		$response		= Array();
		$javo_query		= new javo_Array( $_POST );

		$comment_id		= $javo_query->get( 'comment', false );

		if( ! $comment = get_comment( $comment_id ) )
			$response[ 'error' ]		= __( "Invalid comment ID.", 'javo_fr' );

		if( (int)$comment->user_id !== get_current_user_id() )
			$response[ 'error' ]		= __( "Your not the author", 'javo_fr' );

		if( !isset( $response['error'] ) )
		{
			remove_action( 'edit_comment'	, Array( __CLASS__, 'comment_meta_update' ) );
			$comment_id	= wp_update_comment(
				Array(
					'comment_ID'		=> $comment->comment_ID
					, 'comment_content'	=> $javo_query->get( 'content' )
				)
			);
			add_action( 'edit_comment'		, Array( __CLASS__, 'comment_meta_update' ) );

			if( $comment_id )
				$response[ 'state' ]	= 'OK';
		}

		die( json_encode( $response ) );
	}

	public static function getWriteForm( $lists = false )
	{
		global
			$post
			, $javo_tso
			, $javo_custom_item_label;

		if( (int) $post->ID <= 0 )
			die( -1 );

		wp_enqueue_media();

		$post_id			= $post->ID;
		$allow_write_form	= false;

		ob_start();
		?>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">

			<?php
			if( $javo_tso->get( 'review_only_member', false ) )
				$allow_write_form	= is_user_logged_in();

			else
				$allow_write_form	= is_user_logged_in();
				//$allow_write_form = true;

			if( $allow_write_form ):
				?>
					<div id="javo-review-form-container" class="alert alert-warning cursor-pointer">

						<span class="delete-ready">
							<?php _e( "LEAVE A REVIEW", 'javo_fr' );?>
						</span>

						<form class="hidden" role="form">


							<div class="row">
								<div class="jv-rating-wrap">
									<div class="col-md-12 jv-rating-top-inner">
										<!--<span><?php _e( "Your Rating", 'javo_fr' );?></span>-->
										<?php
										if( (boolean) $javo_rating_fields = $javo_tso->get('rating_field') )
										{
											?>
											<ul class="list-group">
												<?php
												foreach( $javo_rating_fields as $index => $label )
												{
													?>
													<li class="list-group-item col-md-6">
														<div class="row">
															<div class="col-md-6 col-sm-6 javo-raintg-form-field-label-wrap">
																<span class="javo-raintg-form-field-label"><?php echo $label;?></span>
															</div>
															<div class="col-md-6 col-sm-6 javo_rat_star-warp">
																<span class="javo_rat_star" data-score="0" data-input-name="javo_rats[<?php echo $index;?>][score]" data-label="<?php echo $label;?>" required></span>
																<input type="hidden" name="javo_rats[<?php echo $index;?>][label]" value="<?php echo $label;?>">
															</div>
														</div><!-- /.row -->
													</li>
													<?php
												} ?>
											</ul>
											<?php
										} ?>
									</div><!-- /.col-md-6 -->
									<div class="col-md-12 jv-rating-bottom-inner">
										<textarea name="comment_content" class="form-control" data-label="<?php _e( "Contents", 'javo_fr' );?>"></textarea>
									</div><!-- /.col-md-6 -->
								</div><!--jv-rating-wrap-->
							</div><!--/.row -->

							<div class="row">
								<div class="col-md-12">

								</div><!-- /.col-md-12 -->
							</div><!--/.row -->

							<div class="row comment_image_preview"></div><!-- /.row -->

							<div class="row jv-rating-submit-wrap">
								<div class="col-md-12">
									<div class="col-md-3 jv-rating-cancel"></div>
									<div class="col-md-9 jv-rating-submit">
										<div class="inline-block">
											<button
												type			= "button"
												class			= "btn btn-default javo-fileupload"
												data-multiple	= "1"
												data-title		= "<?php _e( "Review Thumbnail", 'javo_fr' );?>"
												data-preview	= ".comment_image_preview"
											>
												<i class="fa fa-picture-o"></i>
												<?php _e( "Upload Image", 'javo_fr' );?>
											</button>
											<input type="hidden" name="comment_image_id">
											<button type="submit" class="btn btn-primary">
												<i class="fa fa-send"></i>
												<?php _e( "Publish Review", 'javo_fr' );?>
											</button>

										</div><!-- /.inline-block -->
									</div>
								</div><!-- /.col-md-12 -->
							</div><!--/.row -->

							<fieldset>
								<input type="hidden" name="action" value="add_item_review">
								<input type="hidden" name="post_id" value="<?php echo $post_id;?>">
								<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'javo-write-review' ); ?>">
							</fieldset>
						</form>
					</div><!-- well -->
				<?php
				else:

				endif;
				?>

			</div><!-- /.col-md-12 -->
		</div><!-- /.rating-form-wrap -->

		<fieldset>
			<input type="hidden" javo-ajax-url value="<?php echo admin_url('admin-ajax.php'); ?>">
		</fieldset>

		<?php
		if( $lists )
			self::getReviewLists();
		ob_end_flush();
	}

	public static function getReviewLists()
	{
		global $post;

		if( (int) $post->ID <= 0 )
			die( -1 );

		$post_id				= $post->ID;

		ob_start();
		?>
		<div class="row javo-reviews-title-wrap">
			<div class="col-md-12">
				<h3 class="page-header">
					<?php _e( "REVIEWS", 'javo_fr' );?>
				</h3>

			</div><!-- /.col-md-12-->
		</div><!-- /.row -->

		<div class="row javo-detail-item-review-wrap">
			<div id="javo-detail-item-review-container"></div>
		</div><!-- /.row -->

		<div class="row">
			<div class="col-md-12 text-center">
				<button type="button" class="btn btn-primary disabled" id="javo-detail-item-review-loadmore">
					<i class="fa fa-write"></i>
					<?php _e( "Load More", 'javo_fr' ); ?>
				</button><!-- /#javo-detail-item-review-loadmore -->
			</div><!-- /.col-md-12 -->
		</div><!-- /.row -->

		<fieldset id="javo-detail-item-review-parameter">
			<input type="hidden" name="ajaxurl" value="<?php echo admin_url( 'admin-ajax.php' );?>">
			<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'javo-listings-review' ); ?>">
			<input type="hidden" name="post_id" value="<?php echo $post_id;?>">
			<input type="hidden" name="user_id" value="">
			<input type="hidden" name="reply_register_success"	value="<?php _e( "Successfully Saved", 'javo_fr' ); ?>">
			<input type="hidden" name="reply_modify_fail"		value="<?php _e( "Save failed", 'javo_fr' ); ?>">
			<input type="hidden" name="review_register_success"	value="<?php _e( "Successfully Saved", 'javo_fr' ); ?>">
			<input type="hidden" name="review_register_fail"	value="<?php _e( "Save failed", 'javo_fr' ); ?>">

		</fieldset>

		<script type="text/html" id="javo-detail-item-review-template">
			<div class="javo-detail-item-review-inner col-md-12 col-sm-12 col-xs-12">
				<!-- Title -->
					<div class="">

						<div class="inline-block col-md-2 col-xs-3 col-ms-2 review-left-wrap">
							<div class="javo-thb" style="background-image:url({avatar}); width:100px; height:100px; border-radius:100%;"></div>

						</div><!-- /.inline-block -->

						<div class="inline-block col-md-9 col-xs-12 col-ms-10 review-right-wrap">
						<!-- Content -->
							<div class="row">
								<div class="review-author pull-left">
									<h4>{author}</h4>
									<!-- Date -->
									<small>{date}</small>
								</div><!-- /.pull-left -->
								<div class="review-rating pull-right">
							<!-- Ratings -->
									<div class="row">
										<div class="col-md-12">
											<div data-score="{rating_average}"></div> {edit}
										</div><!-- /.col-md-12 -->
									</div><!-- /.row -->
								</div>
								<div class="col-md-12 col-xs-12 javo-detail-item-comment-content-wrap">
									<div class="javo-detail-item-comment-content">
										{content}
									</div><!-- /.javo-detail-item-comment-content -->
								</div><!-- /.col-md-12 -->
							</div><!-- /.row -->
							<!-- Thumbnails -->
							<div class="row review-thumbnails-wrap">
								<div class="col-md-12 col-xs-12">
									{thumbnails}
								</div><!-- /.col-md-12 -->
							</div><!-- /.row -->
							<!-- Reply Listing -->
							<div class="row javo-detail-item-reply-container" data-parent-id="{comment-id}">
								<div class="row">
									<div class="col-md-12 javo-detail-item-inner">
										{reviews}
									</div><!-- /.col-md-12 -->
								</div><!-- /.row -->
							</div><!-- /.javo-detail-item-reply-container -->
							<!-- Reply Field -->
							<div class="row javo-detail-item-reply-textarea">

							<?php if( is_user_logged_in() ) : ?>
								<div class="col-md-3 col-xs-1">
									<span><i class="fa fa-reply"></i><span class="hidden-xs">{author}<span></span>
								</div><!-- /.col-md-1 -->
								<div class="col-md-9 col-xs-11">
									<div
										class="javo-detail-item-reply"
										data-parent-id="{comment-id}"
										contenteditable="true"
										placeholder="<?php _e('Write a comment..', 'javo_fr');?>"></div>
								</div><!-- /.col-md-11 -->
								<?php endif; ?>
							</div>
						</div><!-- /.inline-block -->
					</div><!-- /.panel-footer -->
			</div>
			<!-- Separator -->
				<hr>

		</script>
		<script type="text/html" id="javo-detail-item-reply-template">


				<div class="row">

					<div class="col-md-12 javo-detail-item-replys">
						<div class="javo-detail-item-reply-inner">
							<span class="col-md-3">{author}</span>
							<h6 class="javo-detail-item-reply-content col-md-9">{content}
								<span class="javo-detail-item-reply-content-date">{date}</span>
							</h6>
						</div>
						<div class="javo-detail-item-reply-meta"></div>
					</div><!-- /.col-md-4 -->

				</div><!-- /.row -->
		</script>

		<script type="text/html" id="javo-detail-item-review-empty">
			<div class="alert alert-warning dismiss-ready text-center col-xs-12 col-md-12">
				<?php _e( "Not found review.", 'javo_fr' );?>
			</div>
		</script>
		<?php
		ob_get_flush();
	}


	public static function update_rating( $comment_id )
	{
		global $javo_tso;

		if( (int) $comment_id === 0 )
			return $comment_id;

		if( ! $javo_cmt = get_comment( $comment_id ) )
			return $comment_id;

		$javo_cmt_post				= $javo_cmt->comment_post_ID;

		if( 'item' !== get_post_type( $javo_cmt_post ) )
			return $comment_id;

		$javo_cmt_args				= Array(
			'status'				=> 'approve'
			, 'parent'				=> 0
			, 'post_id'				=> $javo_cmt_post
		);

		$javo_comments				= get_comments( $javo_cmt_args );
		$javo_rating_sum			= $javo_rating_average = 0;
		$javo_comments_count		= count( $javo_comments );

		foreach( $javo_comments as $cmt ) {
			$javo_rating_sum		+= (float)get_comment_meta( $cmt->comment_ID, 'rating_average', true );
		}
		$javo_rating_average		= 0;

		@$javo_rating_average		= sprintf( "%.1f", ( (float) $javo_rating_sum / $javo_comments_count ) );

		update_post_meta( $javo_cmt_post, 'rating_average', $javo_rating_average );
		update_post_meta(
			$javo_cmt_post
			, 'rating_meta'
			, Array(
				'total'			=> $javo_rating_sum
				, 'count'		=> $javo_comments_count
				, 'average'		=> $javo_rating_average
			)
		);
	}

	public static function get( $key, $default=0 )
	{
		global $post;

		if( (int) $post->ID == 0 )
			return;

		$javo_cmt_meta			= get_post_meta( $post->ID, 'rating_meta', true );

		if( !empty( $javo_cmt_meta[ $key ] ) )
			return $javo_cmt_meta[ $key ];

		else
			return $default;
	}

	public static function fa_get(
		$fill = 'fa-star'
		, $unfill = 'fa-star-o'
	){

		$javo_post_score		= round( self::get( 'average' ) );
		$javo_ulfill_score		= abs( self::MAX_SCORE - $javo_post_score );
		$javo_el_start			= str_repeat( "<i class=\"fa {$fill}\"></i>", $javo_post_score );
		$javo_el_end			= str_repeat( "<i class=\"fa {$unfill}\"></i>", $javo_ulfill_score );
		return "{$javo_el_start}{$javo_el_end}";
	}

	public static function part_progress()
	{
		global
			$javo_tso
			, $post;

		$javo_rating_fields		= $javo_tso->get( 'rating_field', Array() );

		$javo_cmt_args			= Array(
			'status'			=> 'approve'
			, 'parent'			=> 0
			, 'post_id'			=> $post->ID
		);

		$javo_comments			= get_comments( $javo_cmt_args );
		$javo_comments_count	= count( $javo_comments );

		$javo_score_results		= Array();

		foreach( $javo_comments as $cmt )
		{
			$javo_rating_scores	= get_comment_meta( $cmt->comment_ID, 'rating_scores', true );

			if( !empty( $javo_rating_fields ) ) {
				foreach( $javo_rating_fields as $field ) {
					if( isset( $javo_rating_scores[ $field ] ) )
						$javo_score_results[ $field ][] = floatVal( $javo_rating_scores[ $field ] );
				}
			}
		}

		if( !empty( $javo_rating_fields ) )
		{
			foreach( $javo_rating_fields as $field )
			{

				$javo_part_sum = $javo_part_avg = 0;

				if( !empty( $javo_score_results[ $field] ) )
					$javo_part_sum		= Array_Sum( $javo_score_results[ $field ] );

				if( $javo_part_sum )
					@$javo_part_avg		= (float) $javo_part_sum / $javo_comments_count;

				$javo_part_per			= sprintf( "%.1f", ( ( $javo_part_avg / self::MAX_SCORE ) * 100 ) );
				echo "<div class=\"progress-wrap\">";
					echo "<div class=\"progress-title col-md-4 col-sm-4 col-xs-6\">{$field}</div>";
					echo "<div class=\"progress col-md-8 col-sm-6 col-xs-6\">";
						echo "<div class=\"progress-bar progress-bar-blug progress-bar-striped\"" . ' ';
						echo "aria-valuenow=\"{$javo_part_per}\" aria-valuemin=\"0\" aria-valuemax=\"100\"" . ' ';
						echo "style=\"width:{$javo_part_per}%\">";
							echo $javo_part_per. '% ';
						echo "</div>";
					echo "</div>";
				echo "</div>";
			}
		}
	}

	public static function scripts()
	{
		printf( "\n\t<script type=\"text/javascript\">\n" );
		printf( "\t\twindow.__javo_str_empty_alert_close_button='%s';\n"	, __( "OK", 'javo_fr' ) );
		printf( "\t\twindow.__javo_str_is_empty='%s';\n"					, __( "Please rate on %s.", 'javo_fr' ) );
		printf( "\t\twindow.__javo_str_comment_pending='%s';\n"				, __( "Your comment is awaiting moderation.", 'javo_fr' ) );
		printf( "\t</script>\n" );



		ob_start();
		?>
		<script type="text/javascript">
		jQuery( function( $ ) {
			var javo_write_review = function( element ){
				this.el			= $( element );
				this.selector	= element;
				if( ! window.__JAVO_WRITE_REVIEW__ )
				{
					window.__JAVO_WRITE_REVIEW__ = true;
					this.init();
				}
			};

			javo_write_review.prototype = {

				constructor : javo_write_review

				, init : function()
				{
					var el				= this.el;
					var form			= el.find( "form" );
					this.list_offset	= 0;

					form.removeClass( 'hidden' ).hide();

					this
						.setInputRatingFields();

					$( document )

						.on( 'click', this.selector, this.showWriteForm )
						.on( 'click', '#javo-detail-item-review-loadmore', this.getReviewLists() )
						.on( 'submit', form.selector, this.submit_rating() )
						.on( 'keypress', ".javo-detail-item-reply", this.appendReply() )
						.on( 'click', '.javo-detail-item-edit-comment', this.editComment() );

					if( $( "#javo-detail-item-review-container" ).length > 0 )
						$( "#javo-detail-item-review-loadmore" ).trigger( 'click' );

				}

				, showWriteForm : function( e )
				{
					var form = $( this ).find( "form" );
					form.slideDown( 'fast' );

					$( this )
						.removeClass( 'cursor-pointer')
						.off( 'click' )
						.find( '.delete-ready')
						.remove();
				}

				, setInputRatingFields : function( reset )
				{
					var element		= $('.javo_rat_star');

					element.each(
						function(k, v)
						{
							if( ! reset )
							{
								$( this ).raty({
									starOff: '<?php echo JAVO_IMG_DIR?>/star-off.png'
									, starOn: '<?php echo JAVO_IMG_DIR?>/star-on.png'
									, starHalf: '<?php echo JAVO_IMG_DIR?>/star-half.png'
									, half: true
									, width:150
									, scoreName: $(this).data('input-name')
									, score: function() {
										return $(this).attr('data-score');
									}
								});
							}else{
								$( this ).raty( 'reload' );
							}
						}
					);
					return this;
				}

				, setRatings : function()
				{
					var el	= $( "#javo-detail-item-review-container" );

					el
						.find( "[data-score]" )
						.raty({
							starOff		: '<?php echo JAVO_IMG_DIR?>/star-off-s.png'
							, starOn	: '<?php echo JAVO_IMG_DIR?>/star-on-s.png'
							, starHalf	: '<?php echo JAVO_IMG_DIR?>/star-half-s.png'
							, half		: true
							, readOnly	: true
							, scoreName	: $(this).data('input-name')
							, score		: function() {
								return $(this).attr('data-score');
							}
						})
						.css( 'width', 'auto' );
				}

				, empty_field : function( elements )
				{
					var
						obj		= this
						output	= '';


					obj.el.find( "form [required]" ).each(
						function( i, k )
						{
							var value;

							if( typeof $( this ).data( 'input-name' ) != 'undefined'  ) {
								value = $( "[name='" + $( this ).data( 'input-name' ) + "']" ).val();
							}else{
								value = $( this ).val();
							}

							if( ! value )
								output += window.__javo_str_is_empty.replace( /%s/g, $( this ).data('label') ) + "<br>";
						}
					);

					if( output ) {
						$.javo_msg( {
							content		: output
							, button	: window.__javo_str_empty_alert_close_button
						} );
						return false;
					}
					return true;
				}

				, submit_rating : function()
				{
					var obj				= this;

					return function( e ){
						e.preventDefault();
						var
							form			= $( this )
							, ajax_url		= $( "[javo-ajax-url]" ).val()
							, el			= $( "#javo-detail-item-review-container" )
							, param			= form.serialize()
							, param_meta	= $( "#javo-detail-item-review-parameter" );

						if( ! obj.empty_field() )
							return false;

						$.post(
							ajax_url
							, param
							, function( response )
							{
								if( response.state )
								{
									if( response.approve )
									{
										$.javo_msg({ content: param_meta.find("[name='review_register_success']").val() });
										response.data.prepend = true;
										obj.list_offset = parseInt( obj.list_offset ) + 1;
										obj.add_listing( response.data );
										el.find( ".dismiss-ready" ).remove();
										obj.setReviewImageSlider();
									}else{
										$.javo_msg({ content: window.__javo_str_comment_pending });
									}
									obj.reset_review_form();

								}else{
									$.javo_msg({ content: param_meta.find("[name='review_register_fail']").val() });
								}
							}
							, 'json'
						)
						.fail( function( xhr ) {
							console.log( xhr.responseText );
						} );
					}
				}

				, reset_review_form : function()
				{
					var
						obj					= this
						, image_container	= obj.el.find( ".comment_image_preview" );

					obj.el.find( "[name='comment_content']" ).val( null );

					obj.setInputRatingFields( true );
					image_container.html( false );

				}

				, setReviewImageSlider : function()
				{
					var el = $( "#javo-detail-item-review-container .javo-detail-item-review-inner" );
					el.each(
						function(){
							$(this).magnificPopup(
								{
									type		: 'image'
									, delegate	: 'div.javo-thb[href]'
									, gallery	: { enabled: true }
								}
							);
						}
					);
				}

				, add_listing : function( data )
				{
					var obj = this;
					var str	= $( "#javo-detail-item-review-template" ).html();
					var emp	= $( "#javo-detail-item-review-empty" ).html();
					var el	= $( "#javo-detail-item-review-container" );
					var edit_text;

					el.find( ".dismiss-ready" ).remove();

					if( data )
					{
						var
							edit_link			= ''
							, del_link			= ''
							, detail_ratings	= "";


						if( typeof data.is_author != "undefined" && data.is_author )
						{
							edit_link		+= "<a class=\"javo-detail-item-edit-comment\"" + " ";
							edit_link		+= "data-comment-id=\"" + data.id + "\" href=\"javascript:\">";
								edit_link		+= "<i class=\"fa fa-pencil\"></i>" + " ";
								edit_link		+= edit_text || "Edit";
							edit_link		+= "</a>";

							del_link		+= "<a class=\"javo-detail-item-delete-comment\"" + " ";
							del_link		+= "data-comment-id=\"" + data.id + "\" href=\"javascript:\">";
								del_link		+= "<i class=\"fa fa-pencil\"></i>" + " ";
								del_link		+= edit_text || "Delete";
							del_link		+= "</a>";
						}

						str = str.replace( /{comment-id}/g		, data.id || '' );
						str = str.replace( /{author}/g			, data.author || '' );
						str = str.replace( /{avatar}/g			, data.avatar || '' );
						str = str.replace( /{content}/g			, data.content || '' );
						str = str.replace( /{date}/g			, data.date || '' );
						str = str.replace( /{ratings}/g			, data.ratings || '' );
						str = str.replace( /{rating_sum}/g		, data.rating_sum || 0 );
						str = str.replace( /{rating_average}/g	, data.rating_average || 0 );
						str = str.replace( /{thumbnails}/g		, data.thumbnails || '' );
						str = str.replace( /{reviews}/g			, obj.add_listing_reply( data ) );
						str = str.replace( /{edit}/g			, edit_link );
						str = str.replace( /{del}/g				, del_link );

						if( typeof data.ratings != "undefined" )
						{
							var dr_score = '';
							$.each(
								data.ratings
								, function( title, score )
								{
									dr_score		+= "<div class=\"row\">";
										dr_score		+= "<div class=\"col-md-4\">";
										dr_score		+= title;
										dr_score		+= "</div>";
										dr_score		+= "<div class=\"col-md-8\">";
										dr_score		+= "<div data-score=\"" + score + "\"></div>";
										dr_score		+= "</div>";
									dr_score		+= "</div>";
								}
							);
							detail_ratings += dr_score;
						}



						if( data.prepend ) {
							// el.prepend( str );
							$( str )
								.prependTo( el )
								.find( '[data-score]' )
								.css( 'cursor', 'pointer' )
								.popover(
									{
										content		: detail_ratings
										, html		: true
										, trigger	: 'hover'
										, placement	: 'top'
									}
								)
								.on(
									'shown.bs.popover'
									, function ()
									{
										$( this )
											.parent()
											.find( '.popover [data-score]' )
											.raty({
												starOff: '<?php echo JAVO_IMG_DIR?>/star-off-s.png'
												, starOn: '<?php echo JAVO_IMG_DIR?>/star-on-s.png'
												, starHalf: '<?php echo JAVO_IMG_DIR?>/star-half-s.png'
												, half: true
												, width:150
												, readOnly	: true
												, score: function() {
													return $(this).attr('data-score');
												}
											});
									}
								);

						}else{
							// el.append( str );
							$( str )
								.appendTo( el )
								.find( '[data-score]' )
								.css( 'cursor', 'pointer' )
								.popover(
									{
										content		: detail_ratings
										, html		: true
										, trigger	: 'hover'
										, placement	: 'top'
									}
								)
								.on(
									'shown.bs.popover'
									, function ()
									{
										$( this )
											.parent()
											.find( '.popover [data-score]' )
											.raty({
												starOff: '<?php echo JAVO_IMG_DIR?>/star-off-s.png'
												, starOn: '<?php echo JAVO_IMG_DIR?>/star-on-s.png'
												, starHalf: '<?php echo JAVO_IMG_DIR?>/star-half-s.png'
												, half: true
												, width:150
												, readOnly	: true
												, score: function() {
													return $(this).attr('data-score');
												}
											});
									}
								);

						}
					}else{
						el.append( emp );
					}

					$('[data-toggle="popover"]').popover();
				}

				, add_listing_reply : function( data )
				{
					var result = '';

					if( typeof data.reply == 'undefined' )
						return '';

					$.each(
						data.reply
						, function( i, k ){
							var str = $("#javo-detail-item-reply-template").html();
							str = str.replace( /{author}/g			, k.author || '' );
							str = str.replace( /{content}/g			, k.content || '' );
							str = str.replace( /{date}/g			, k.date || '' );
							result += str;
						}
					);
					return result;
				}

				, getReviewLists : function()
				{
					var obj			= this;
					var param_el	= $( "#javo-detail-item-review-parameter" );
					var ajaxurl		= param_el.find( "[name='ajaxurl']" ).val();
					var param		= {
						action		: 'get_item_review'
						, nonce		: param_el.find( "[name='nonce']" ).val()
						, post_id	: param_el.find( "[name='post_id']").val()
					}

					return function( e )
					{
						e.preventDefault();

						var	el		= $( this );

						param.offset	= obj.list_offset;

						$.ajaxSetup({

							beforeSend : function() {
								el.button( 'loading' );
							}
							, complete : function() {
								el.button( 'reset' );
								obj.setRatings();
							}
						});
						$.post(
							ajaxurl
							, param
							, function( response )
							{
								if( response.state ) {
									if( response.data.length > 0 ) {
										$.each( response.data, function( i, data ){
											obj.add_listing( data );
										});
										obj.list_offset += response.data.length;
									}else{
										obj.add_listing();
									}
									obj.setReviewImageSlider();
								}else{
									$.javo_msg({ content: 'Error' });
								}
							}
							, 'json'
						)

						.fail( function( xhr ) {
							console.log( xhr.responseText );
						} );
					}
				}

				, appendReply : function(){

					var obj = this, opt;
					return function( e ) {

						if( e.keyCode == 13 )
						{
							opt = {
								content		: $( this ).html()
								, parent	: $( this ).data( 'parent-id' )
							};
							obj.regsiterReply( this, opt );
						}
					};
				}

				, regsiterReply : function( el, opt )
				{
					var obj				= this;
					var param_el		= $( "#javo-detail-item-review-parameter" );
					var ajaxurl			= param_el.find( "[name='ajaxurl']" ).val();
					var param			= {
						action			: 'add_comment_reply'
						, nonce			: param_el.find( "[name='nonce']" ).val()
						, comment_id	: opt.parent
						, post_id		: param_el.find( "[name='post_id']").val()
						, content		: opt.content
					};

					if( $( el ).hasClass( 'disabled' ) )
						return false;

					$( el )
										.attr( 'contenteditable', false )
										.addClass( 'disabled' );

					$.post(
						ajaxurl
						, param
						, function( xhr )
						{
							var str;
							var data = xhr.data;

							if( xhr.state == 'success' )
							{
								if( xhr.approve ){
									str = $("#javo-detail-item-reply-template").html();
									str = str.replace( /{author}/g		, data.author || '' );
									str = str.replace( /{content}/g		, data.content || '' );
									str = str.replace( /{date}/g		, data.date || '' );
									$( ".javo-detail-item-reply-container[data-parent-id='" + opt.parent + "']" )
										.prepend( str );

									$.javo_msg({ content: param_el.find( "[name='reply_register_success']" ).val() });

								}else{
									$.javo_msg({ content: window.__javo_str_comment_pending });
								}
							}

							$( el )
										.empty()
										.attr( 'contenteditable', true )
										.removeClass( 'disabled' );
						}
						, 'json'
					);
				}

				, editComment : function()
				{
					var obj = this;

					return function( e )
					{
						e.preventDefault();

						var
							el				= $( this )
							, comment_id	= $( this ).data( "comment-id" )
							, parent		= $( this ).closest( ".javo-detail-item-review-inner" )
							, text_el		= parent.find( ".javo-detail-item-comment-content" )
							, param_el		= $( "#javo-detail-item-review-parameter" )
							, ajaxurl		= param_el.find( "[name='ajaxurl']" ).val();

						if( $( this ).hasClass( 'active' ) )
						{

							$.post(
								ajaxurl

								, {
									action		: 'javo_update_review'
									, comment	: comment_id
									, content	: text_el.html()
								}

								, function( response )
								{
									if( typeof response.error === "undefined" )
									{
										if( response.state !== "OK" ) {
											$.javo_msg({ content: param_el.find( "[name='reply_modify_fail']" ).val(), delay : 5000 });
										}else{
											el.removeClass( 'active' );
											text_el
												.attr( "contenteditable" , false )
												.removeClass( "text-field" );
										}

									}else{
										$.javo_msg({ content: response.error, delay : 5000 });
									}
								}
								, 'json'
							)
							.fail( function( xhr ){
								console.log( xhr.responseText );
							} );



						}else{
							$( this ).addClass( 'active' );
							text_el
								.attr( "contenteditable" , true )
								.addClass( "text-field")
								.focus();
						}
					}
				}
			};

			new javo_write_review( "#javo-review-form-container" );

		} );
		</script>
		<?php
		ob_get_flush();
	}

}

new javo_review;