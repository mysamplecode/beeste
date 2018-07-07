<?php
/*
*
*	Add Item Confirm Window
*
*
*/
?>
<div class="modal fade" id="addItem-confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Modal title</h4>
			</div><!-- /.modal-header -->
			<div class="modal-body">

				<?php javo_cutom_paid_memberships_plugin::orders(); ?>

			</div><!-- /.modal-body -->
			<div class="modal-footer text-center">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div> <!-- /.modal.fade -->