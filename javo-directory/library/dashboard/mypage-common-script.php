<?php
$javo_alert_strings = Array(
	'confirm_delete_item' => __('Do you want to delete the selected item?', 'javo_fr')
); ?>

<script type="text/javascript">
jQuery(function($){
	"use strict";
	window.javo_mypage_script = {
		ajax:{}
		, events:function(){
			var $object = this;
			var $this;

			$("body")
			.on("click", ".javo_this_trash", function(e){
				e.preventDefault();
				$this = $(this);
				$object.ajax.data			= {};
				$object.ajax.data.post		= $this.data("post");
				$object.ajax.data.action	= "trash_item";
				$object.ajax.success		= function(d){

					if( d.result == "success" ){
						$.javo_msg({content:"<?php _e('The item has been successfully deleted.', 'javo_fr');?>"}, function(){
							$this.closest('.row.content-panel-wrap-row').remove();
						});
					}else{
						$.javo_msg({content:"<?php _e('The item could not be deleted. You don\'t have permission to delete this item.', 'javo_fr');?>"});
					};

				};
				if(!confirm("<?php echo $javo_alert_strings['confirm_delete_item'];?>")) return false;
				$.ajax( $object.ajax );
			})
			.on('click', '.toggle-full-mode', function(){
				$('body').toggleClass('content-full-mode');
			});
		} //-- Close Events();

		, setBootstrapDatePicker: function(){

			var options = {};

			options.format			= "yyyy-mm-dd";
			options.startView		= 2;
			options.autoclose		= true;
			options.todaHighlight	= true;

			$( "[data-javo-date]" ).datepicker( options );
		}


		, init:function(){

			// Initialize Ajax Variable
			this.ajax.url		= "<?php echo admin_url('admin-ajax.php');?>";
			this.ajax.type		= "post";
			this.ajax.dataType	= "json";

			// Event handler
			this.events();

			// Initialize Active Plugins
			$('.mypage-tooltips').tooltip();

			// Auto Complete
			$('select[data-autocomplete]').chosen({ search_contains:1 });

			this.setBootstrapDatePicker();
		}
	};

	window.javo_mypage_script.init();
});
</script>