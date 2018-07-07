<?php
get_header();?>

<div class="container" id="javo-user-404-page-wrap">
	<div class="row">
		<div class="col-md-12">
			<h1 class="page-header text-center"><?php _e('Cannot find user.', 'javo_fr');?></h1>
		</div><!-- /.col-md-12 -->
	</div><!--/.row -->
	<div class="row">
		<div class="col-md-12">
			<h3 class="text-center"><?php _e('There are no users with that username.', 'javo_fr');?></h3>
		</div><!-- /.col-md-12 -->
	</div><!--/.row -->
	<div class="row">
		<div class="col-md-12">
			<div class="javo-user-404-controls text-center">
				<a class="btn btn-primary" href="<?php echo home_url();?>"><?php _e('Go Home', 'javo_fr');?></a>
				<a class="btn btn-primary" href="javascript:history.back(-1);"><?php _e('Go Back', 'javo_fr');?></a>
			</div>

		</div><!-- /.col-md-12 -->
	</div><!--/.row -->
	<div class="javo-user-404-footer"></div>
</div><!-- /#javo-user-404-page-wrap -->





<?php
get_footer();