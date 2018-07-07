<?php
global $post; ?>

<div class="row">
	<div class="col-md-12">
		<?php

		if( false !== (boolean)( $jv_item_custom_tab = apply_filters(
			'the_content'
			, get_post_meta( $post->ID, "jv_item_custom_inner", true )
		) ) ){
			echo $jv_item_custom_tab;
		}else{
			printf( "<div class=\"text-center\">%s</div>", __( "No data found", 'javo_fr' ) );
		} ?>
	</div>
</div><!-- /.row -->