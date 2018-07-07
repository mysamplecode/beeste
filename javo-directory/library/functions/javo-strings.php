<?php
add_action( 'init', 'javo_register_string' );
define( "__JAVO", 'javo_fr' );
function javo_register_string()
{
	global $jv_str;
	$jv_str = Array(

		/*
		Basic Comment
		*/
		'not_found_item'			=> __( "Not Found Items", __JAVO )
		, 'server_error'			=> __( "Server Error", __JAVO )
		, 'location_acc_fail'		=> __( "Your position access failed.", __JAVO )

		// Word
		, 'save'					=> __( "Save", __JAVO )
		, 'edit'					=> __( "Edit", __JAVO )

		, 'address'					=> __( "Address", __JAVO )
		, 'phone'					=> __( "Phone", __JAVO )
		, 'email'					=> __( "E-mail", __JAVO )
		, 'website'					=> __( "Website", __JAVO )

		// Image
		, 'featured_image'			=> __( "Featured Image", __JAVO )
		, 'detail_image'			=> __( "Detail Image", __JAVO )

		, 'preview'					=> __( "Preview", __JAVO )


		// Terms
		, 'no_category'				=> __( "No Category", __JAVO )
		, 'no_location'				=> __( "No Location", __JAVO )


		/*
		Add Item
		*/
		, 'title_null'				=> __( "Please Type Item Title.", __JAVO )
		, 'content_null'			=> __( "Please Type Item Description.", __JAVO )
		, 'latlng_null'				=> __( "Please Find Address Or Marker Drag.", __JAVO )
		, 'item_edit_success'		=> __( "Item Modified Successfully!", __JAVO )
		, 'item_new_success'		=> __( "Thanks For Posting Your Item!", __JAVO )
		, 'detail_image_limit'		=> __( "Amount of images you can upload is %d", __JAVO )
		, 'additional_information'	=> __( "Additional Information", __JAVO )
		, 'upload_x'				=> __( "Upload %s", __JAVO )

		/*
		Javo Map (Box)
		*/
		, 'move'					=> __( "Move", __JAVO )
		, 'detail'					=> __( "Detail", __JAVO )
		, 'popup'					=> __( "Popup", __JAVO )

		/*
		Javo Mail Plugin
		*/
		, 'javo_email'				=> Array(
			'to_null_msg'			=> __( "Please, insert recipient's email address.", __JAVO )
			, 'from_null_msg'		=> __( "Please, insert sender's email address.", __JAVO )
			, 'subject_null_msg'	=> __( "Please, insert your name.", __JAVO )
			, 'content_null_msg'	=> __( "Please, insert message content.", __JAVO )
			, 'failMsg'				=> __( "Sorry, your message could not be sent.", __JAVO )
			, 'successMsg'			=> __( "Successfully sent!", __JAVO )
			, 'confirmMsg'			=> __( "Do you want to send this message?", __JAVO )
		)

		/*
		Javo MailChimp Widget
		*/
		, 'mailchimp'				=> Array(
			'no_email'				=> __( "Please Type Email Address.", __JAVO )

		)
	);
}