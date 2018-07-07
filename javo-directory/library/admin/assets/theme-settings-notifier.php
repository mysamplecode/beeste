<?php

// library / define.php
global $javo_notification_deault_content;

$javo_recipients = Array(
	__( "None", 'javo_fr' )						=> ''
	, __( "Author", 'javo_fr' )					=> 'author'
	, __( "Administrator", 'javo_fr' )			=> 'admin'
	, __( "Author + Administrator", 'javo_fr' )	=> 'all'
);


{
	$javo_notify_features_args						= Array(

		'item'										=> Array(

			'title'									=> __( "New Items", 'javo_fr' )
			, 'options'								=> Array(

				'new_item_notifier'					=> Array(
					'label'							=> __( "Active Notification For New Items", 'javo_fr' )
					, 'value'						=> $javo_tso->get( 'new_item_notifier', '' )
					, 'type'						=> 'select'
				)

				, 'new_item_notifier_template'		=> Array(
					'label'							=> __( "Template Of Notification Mail", 'javo_fr' )
					, 'value'						=> stripslashes_deep( $javo_tso->get( 'new_item_notifier_template', $javo_notification_deault_content ) )
					, 'type'						=> 'textarea'
				)

			)
		)

		, 'event'									=> Array(

			'title'									=> __( "New Events", 'javo_fr' )
			, 'options'								=> Array(

				'new_event_notifier'				=> Array(
					'label'							=> __( "Active Notification For New Events", 'javo_fr' )
					, 'value'						=> $javo_tso->get( 'new_event_notifier', '' )
					, 'type'						=> 'select'
				)

				, 'new_event_notifier_template'		=> Array(
					'label'							=> __( "Template Of Notification Mail", 'javo_fr' )
					, 'value'						=> stripslashes_deep( $javo_tso->get( 'new_event_notifier_template', $javo_notification_deault_content ) )
					, 'type'						=> 'textarea'
				)

			)
		)
		, 'claim'									=> Array(

			'title'									=> __( "New Claims", 'javo_fr' )
			, 'options'								=> Array(

				'new_claim_notifier'				=> Array(
					'label'							=> __( "Active Notification For New Claims", 'javo_fr' )
					, 'value'						=> $javo_tso->get( 'new_claim_notifier', '' )
					, 'type'						=> 'select'
				)

				, 'new_claim_notifier_template'		=> Array(
					'label'							=> __( "Template Of Notification Mail", 'javo_fr' )
					, 'value'						=> stripslashes_deep( $javo_tso->get( 'new_claim_notifier_template', $javo_notification_deault_content ) )
					, 'type'						=> 'textarea'
				)

			)
		)
	);
	$javo_notify_features = Apply_Filters( 'javo_notify_features_args', $javo_notify_features_args );
} ?>

<div class="javo_ts_tab javo-opts-group-tab hidden" tar="notifier">
	<h2><?php _e('Notification', 'javo_fr'); ?> </h2>
	<table class="form-table">
	<tr><td bgcolor="#eee" colspan="2">
		<div class="update-nag">
			<h2><?php _e('Template code GuideLine', 'javo_fr');?></h2>
			<ul>
				<li><?php _e('{permalink} :  Item page url (for events, reviews will link to the item (parent) page)', 'javo_fr');?></li>
				<li><?php _e('{home_url} : Site url', 'javo_fr');?></li>
				<li><?php _e('{author_name} : Author "display_name"', 'javo_fr');?></li>
				<li><?php _e('{post_title} : Post Title', 'javo_fr');?></li>
			</ul>
		</div>
	</td></tr>

	<?php
	if( !empty( $javo_notify_features ) )
		foreach( $javo_notify_features as $args )
		{
			echo "<tr>";
				echo "<th>{$args['title']}</th>";
				echo "<td>";

					if( isset( $args['options'] ) )
						foreach( $args['options'] as $id => $attr )
						{
							echo "<h4>{$attr['label']}</h4>";
							echo "<fieldset class=\"inner\">";
							if( $attr['type'] == 'select' )
							{
								echo "<select name=\"javo_ts[{$id}]\">";
									foreach( $javo_recipients as $label => $recip )
										printf(
											"<option value=\"{$recip}\" %s>{$label}</option>"
											, selected( $attr['value'] == $recip, true, false )
										);
								echo "</select>";
							}else{
								echo "<span class=\"description\">" . __('(Please add your message : html code)', 'javo_fr' ) . "</span>";
								echo "<textarea name=\"javo_ts[{$id}]\" rows=\"10\" class=\"large-text\">{$attr['value']}</textarea>";
							}
							echo "</fieldset>";
						}
				echo "</td>";
			echo "</tr>";
		}
	?>
	</table>
</div>