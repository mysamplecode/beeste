<?php
global $javo_tso;
echo apply_filters('javo_shortcode_title', __('Location', 'javo_fr'), get_the_title() );?>
<div class="row">
	<div class="col-md-12">
		<div class="javo-single-map-area"></div>
	</div><!-- /.col-md-12 -->
</div><!-- /.row -->
<?php if($javo_tso->get('javo_location_tab_get_direction')!='disabled'){ ?>
<div class="row get-direction">
	<div class="col-md-12">
		<div class="input-group input-group-gray">
			<span class="input-group-addon border-none reactangle"><?php _e('Get directions', 'javo_fr');?></span>
			<div class="form-group">
				<div class="row gd-inputs">
					<div class="col-md-1 col-sm-1 col-xs-12">
						<div class="btn btn-dark btn-circle javo-tooltip" title="<?php _e('This directory is based on google map API', 'javo_fr');?>"><i class="fa fa-question"></i></div>
					</div><!-- /.col-md-1 -->
					<div class="col-md-5 col-sm-5 col-xs-12">
						<select data-javo-direction-travel class="form-control">
							<option value="driving"><?php _e('Driving', 'javo_fr');?></option>
							<option value="bicycling"><?php _e('Bicycling', 'javo_fr');?></option>
							<option value="transit"><?php _e('Transit', 'javo_fr');?></option>
							<option value="walking"><?php _e('Walking', 'javo_fr');?></option>
						</select>
					</div> <!-- /.	col-md-3 -->
					<div class="col-md-6 col-sm-6 col-xs-12">
						<input type="text" class="form-control" data-javo-direction-start-text placeholder="<?php _e('Address of departure','javo_fr');?>">
					</div> <!-- col-md-8 -->
				</div> <!-- row -->
			</div>
			<span class="input-group-btn">
				<button class="btn btn-primary admin-color-setting" type="button" data-javo-direction-start><?php _e('Search Now', 'javo_fr');?></button>
			</span>
		</div>

	</div><!-- /.col-md-12 -->
</div><!-- /.row -->
<?php } ?>