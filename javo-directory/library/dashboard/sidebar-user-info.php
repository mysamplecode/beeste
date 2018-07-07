<?php
/**
*** User Information
***/
global $javo_tso;
$javo_this_user = wp_get_current_user();
?>
<div class="container profile-and-image-container">
	<div class="col-xs-6 col-sm-2">
		<div class="row author-img">
			<div class="col-md-12">
				<?php echo get_avatar( $javo_this_user, 150 ); ?>
			</div><!-- 12 Columns -->
		</div>
		<div class="row author-names">
			<div class="col-md-12">
				<ul class="list-unstyled text-center">
					<li><?php echo $javo_this_user->display_name; ?></li>
				</ul>
			</div><!-- 12 Columns -->
		</div>
	</div> <!-- col-xs-6 col-sm-3 -->
	<div class="col-xs-6 col-sm-10">
		&nbsp;
	</div> <!-- col-xs-12 col-sm-10 -->
</div> <!-- container -->