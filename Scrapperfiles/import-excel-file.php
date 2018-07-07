<?php

define ( 'DB_SERVER' , 'localhost' );
define ( 'DB_USERNAME' , 'root' );
define ( 'DB_PASSWORD' , 'bugs' );
define ( 'DB_DATABASE' , 'javo-main' );

mysql_connect ( DB_SERVER , DB_USERNAME , DB_PASSWORD , DB_DATABASE );
mysql_select_db ( DB_DATABASE );

global $start , $end , $fp;

ini_set ( 'memory_limit' , '1024M' );
$sizes_temp = array( 'full' => array( 820 , 420 ) , 'thumbnail' => array( 150 , 150 ) , 'medium' => array( 300 , 170 ) , 'large' => array( 1024 , 577 ) , 'javo-tiny' => array( 80 , 80 ) , 'javo-avatar' => array( 250 , 250 ) , 'javo-box' => array( 288 , 266 ) , 'javo-map-thumbnail' => array( 150 , 165 ) , 'javo-box-v' => array( 400 , 220 ) , 'javo-large' => array( 500 , 400 ) , 'javo-huge' => array( 720 , 370 ) , 'javo-item-detail' => array( 823 , 420 ) , 'post-thumbnail' => array( 132 , 133 ) );
$attach_meta = array( 'width' => 0 , 'height' => 0 , 'file' => '' ,
    'sizes' => array() ,
    'image_meta' =>
    array( 'aperture' => 0 , 'credit' => '' , 'camera' => '' , 'caption' => '' ,
	'created_timestamp' => 0 , 'copyright' => '' , 'focal_length' => 0 , 'iso' => 0 ,
	'shutter_speed' => 0 , 'title' => '' , 'orientation' => 0 ) );

//define ( 'JSON_FETCH_PATH' , 'http://bestee.com/wp-content/uploads/javo_all_items_1_.json' );
define ( 'JSON_FETCH_PATH' , 'javo_all_items_1_.json' );

ini_set ( "display_errors" , 1 );
require_once 'excel_reader2.php';

$data = new Spreadsheet_Excel_Reader ( "YPAU-Yoga-94165.xls" );

echo "Total Sheets in this xls file: " . count ( $data->sheets ) . PHP_EOL;
for ( $i = 0; $i < count ( $data->sheets ); $i++ )
{ // Loop to get all sheets in a file.
    if ( count ( $data->sheets[ $i ][ 'cells' ] ) > 0 )
    { // checking sheet not empty
	echo "Sheet $i: Total rows in sheet $i  " . count ( $data->sheets[ $i ][ 'cells' ] ) . PHP_EOL;
	$start = 1;
	$end = count ( $data->sheets[ $i ][ 'cells' ] );
	if ( isset ( $argv[ 1 ] ) )
	    $start = $argv[ 1 ];
	else
	    die ( "Need the start argument" . PHP_EOL );
	if ( isset ( $argv[ 2 ] ) )
	    $end = $argv[ 2 ];
	else
	    die ( "Need the end argument" . PHP_EOL );
	$fp = @fopen ( "ins_{$start}_{$end}_" . time () . ".sql" , 'w' );
	if ( $fp == false )
	{
	    die ( "Unable to create insert file" . PHP_EOL );
	}

	$json_contents = file_get_contents ( JSON_FETCH_PATH );
	$javo_all_posts = json_decode ( $json_contents , true );
	$javo_all_posts = clean_json ( $javo_all_posts );

	$cnt = $start;
	for ( $j = $start; $j <= $end; $j++ )
	{ // loop used to get each row of the sheet
	    $company_name = $data->sheets[ $i ][ 'cells' ][ $j ][ 2 ];
	    $post_name = strtolower ( str_replace ( " " , "_" , $company_name ) );
	    $post_name = strtolower ( str_replace ( "&" , "" , $post_name ) );
	    $post_name = strtolower ( str_replace ( "'" , "" , $post_name ) );

	    $check_wp_post = "SELECT * from wp_posts where `post_name` = '" . $post_name . "'";
	    echo $cnt . ". " . $post_name . PHP_EOL;
	    $cnt++;
	    $result_check_wo_post = mysql_query ( $check_wp_post ) or die ( mysql_error () );
	    if ( mysql_num_rows ( $result_check_wo_post ) == 0 )
	    {
		$company_name = validentry ( $data , $i , $j , 2 );

		$meta_array = array();
		$meta_array[ 'javo_control_options' ] = '';
		$meta_array[ 'javo_slider_type' ] = '';
		$meta_array[ 'javo_posts_per_page' ] = '';
		$meta_array[ 'javo_item_tax' ] = 's:17:"a:1:{i:0;s:0:"";}";';
		$meta_array[ 'javo_blog_tax' ] = '';
		$meta_array[ 'javo_item_terms' ] = 's:2:"N;";';
		$meta_array[ 'javo_blog_terms' ] = 's:2:"N;";';
		$meta_array[ 'idaebf222c625dc618de474d17c815c8f3' ] = 'Not Available';
		$meta_array[ 'id303fb94090c93ee191b82517e6bb6775' ] = '|';
		$meta_array[ 'idf4788ba3938c1f1a779bee05f1cf298c' ] = '|';
		$meta_array[ 'id6f5389e9e5fc19bd89fd9885a78abb26' ] = 'a:1:{i:0;s:4:"chk2";}';
		$meta_array[ 'id26069a520ab624269ab2508cd0e68018' ] = 'radio2';
		$meta_array[ 'id3ae87c10fca2d922a6fa3e1e6bbb7a53' ] = 'select2';
		$meta_array[ 'id3210f62dbedeea90f5e255d507417fa9' ] = 'sgdfdgfhgjhkjyghn';
		$meta_array[ 'javo_paid_state' ] = 'paid';
		$meta_array[ 'javo_expire_day' ] = '20920417071928';
		$meta_array[ '_thumbnail_id' ] = '1234';
		$meta_array[ 'detail_images' ] = array();
		$meta_array[ 'jv_item_lat' ] = '-25.274398';
		$meta_array[ 'jv_item_lng' ] = '133.775136';
		$meta_array[ '_edit_lock' ] = '1436167440:1';
		$meta_array[ '_edit_last' ] = '1';
		$meta_array[ '_vc_post_settings' ] = 'a:2:{s:7:"vc_grid";a:0:{}s:10:"vc_grid_id";a:0:{}}';
		$meta_array[ 'slide_template' ] = 'default';
		$meta_array[ 'javo_this_featured_item' ] = 'use';
		$meta_array[ 'jv_item_street_visible' ] = '0';
		$meta_array[ 'jv_item_street_lat' ] = '-25.274398';
		$meta_array[ 'jv_item_street_lng' ] = '133.775136';
		$meta_array[ 'jv_item_street_heading' ] = '34';
		$meta_array[ 'jv_item_street_pitch' ] = '10';
		$meta_array[ 'jv_item_street_zoom' ] = '1';

		$address = validentry ( $data , $i , $j , 3 );

		if ( strcmp ( $address , mysql_escape_string ( "N/A" ) ) != 0 )
		{
		    $lat_long = get_lat_long ( $address );
		    if ( strlen ( $lat_long[ 'address' ] ) > strlen ( $address ) )
		    {
			$address = $lat_long[ 'address' ];
		    }
		    $meta_array[ 'jv_item_lat' ] = mysql_escape_string ( $lat_long[ 'lat' ] );
		    $meta_array[ 'jv_item_lng' ] = mysql_escape_string ( $lat_long[ 'long' ] );
		    $meta_array[ 'jv_item_street_lat' ] = $meta_array[ 'jv_item_lat' ];
		    $meta_array[ 'jv_item_street_lng' ] = $meta_array[ 'jv_item_lng' ];
		}

		$meta_array[ 'jv_item_address' ] = mysql_escape_string ( $address );
		$phone_number = validentry ( $data , $i , $j , 4 );
		$meta_array[ 'jv_item_phone' ] = mysql_escape_string ( $phone_number );
		$email = validentry ( $data , $i , $j , 5 );
		$meta_array[ 'jv_item_email' ] = mysql_escape_string ( $email );
		$website = validentry ( $data , $i , $j , 6 );
		$meta_array[ 'jv_item_website' ] = mysql_escape_string ( $website );
		/*		 * ********************Opening Hours From Monday to Friday**************************** */
		$o_h_mon = validentry ( $data , $i , $j , 9 );
		$o_h_tue = validentry ( $data , $i , $j , 10 );
		$o_h_wed = validentry ( $data , $i , $j , 11 );
		$o_h_thu = validentry ( $data , $i , $j , 12 );
		$o_h_fri = validentry ( $data , $i , $j , 13 );
		$meta_array[ 'ida10f36d041fb6d54e8d17327a38453e7' ] = mysql_escape_string ( $o_h_mon . ", " . $o_h_tue . ", " . $o_h_wed . ", " . $o_h_thu . ", " . $o_h_fri );
		/*		 * ********************Opening Hours Of Saturday and Sunday**************************** */
		$o_h_sat = validentry ( $data , $i , $j , 14 );
		$meta_array[ 'ida158f0b4def3e52a5024723805b6a586' ] = mysql_escape_string ( $o_h_sat );
		$o_h_sun = validentry ( $data , $i , $j , 15 );
		$meta_array[ 'id2a4396ee66cda5f6adeddcab751fcab2' ] = mysql_escape_string ( $o_h_sun );
		$desc1 = description_entry ( $data , $i , $j , 16 ) . description_entry ( $data , $i , $j , 17 ) . description_entry ( $data , $i , $j , 18 ) . description_entry ( $data , $i , $j , 19 ) . description_entry ( $data , $i , $j , 20 ) . description_entry ( $data , $i , $j , 21 ) . description_entry ( $data , $i , $j , 22 ) . description_entry ( $data , $i , $j , 23 ) . description_entry ( $data , $i , $j , 24 ); //24
		/*		 * ***********************Inserting Into wp-posts Table******************************** */

		$get_last_id = "Select Max(ID) from wp_posts";
		$result_last_id = mysql_query ( $get_last_id ) or die ( mysql_error () );
		$result_last_id = mysql_fetch_array ( $result_last_id );
		$result_last_id = $result_last_id[ 'Max(ID)' ] + 1;

		$javo_all_posts = upate_json ( $javo_all_posts , $result_last_id , $company_name , $meta_array[ 'jv_item_lat' ] , $meta_array[ 'jv_item_lng' ] );

		$insert_new_post = "insert into wp_posts "
			. "(post_status, comment_status, ping_status, post_author, post_date, post_date_gmt, post_title, post_content ,post_name, post_modified, post_modified_gmt, guid, post_type) values "
			. "('publish','closed','closed','1','" . date ( "Y-m-d h:i:s" ) . "','" . date ( "Y-m-d h:i:s" ) . "','" . $company_name . "','" . $desc1 . "','" . $post_name . "','" . date ( "Y-m-d h:i:s" ) . "','" . date ( "Y-m-d h:i:s" ) . "','http://bestee.com/?post_type=item&#038;p=" . $result_last_id . "','item')";
		write_to_file ( $fp , $insert_new_post );
		mysql_query ( $insert_new_post ) or die ( mysql_error () );
		$last_id = mysql_insert_id ();

		$images = validentry ( $data , $i , $j , 8 );
		if ( strcmp ( $images , mysql_escape_string ( "N/A" ) ) != 0 )
		{
		    if ( strlen ( trim ( $images ) ) > 0 )
		    {
			$iarr = array();
			$count = 0;
			$img = explode ( ';' , $images );
			foreach ( $img as $im )
			{
			    $im = str_replace ( "Images/" , "" , $im );
			    list($name , $ext) = explode ( '.' , $im );
			    $image_path = "http://bestee.com/wp-content/uploads/2015/08/{$im}";

			    $insert_new_image_post = "insert into wp_posts "
				    . "(post_author, post_date, post_date_gmt, post_title, post_content ,post_name, post_modified, post_modified_gmt, guid, post_type, post_mime_type) values "
				    . "('1','" . date ( "Y-m-d h:i:s" ) . "','" . date ( "Y-m-d h:i:s" ) . "','" . $name . "','','" . $name . "','" . date ( "Y-m-d h:i:s" ) . "','" . date ( "Y-m-d h:i:s" ) . "','" . $image_path . "','attachment','image/jpeg')";
			    write_to_file ( $fp , $insert_new_image_post );
			    mysql_query ( $insert_new_image_post ) or die ( mysql_error () );
			    $last_image_id = mysql_insert_id ();
			    write_meta ( $last_image_id , '_wp_attached_file' , "2015/08/{$im}" );
			    $image_info = (getimagesize ( "http://bestee.com/wp-content/uploads/2015/08/{$im}" ));
			    if ( isset ( $image_info[ 0 ] ) && isset ( $image_info[ 1 ] ) )
			    {
				$attach_meta[ 'width' ] = $image_info[ 0 ];
				$attach_meta[ 'height' ] = $image_info[ 1 ];
				$attach_meta[ 'file' ] = "{$im}";
				foreach ( $sizes_temp as $t => $s )
				{
				    $attach_meta[ 'sizes' ][ $t ] = array();
				    $attach_meta[ 'sizes' ][ $t ][ 'width' ] = $s[ 0 ];
				    $attach_meta[ 'sizes' ][ $t ][ 'height' ] = $s[ 1 ];
				    $attach_meta[ 'sizes' ][ $t ][ 'file' ] = "2015/08/{$im}";
				    $attach_meta[ 'sizes' ][ $t ][ 'mime-type' ] = $image_info[ 'mime' ];
				}
				write_meta ( $last_image_id , '_wp_attached_metadata' , serialize ( $attach_meta ) );
			    }
			    if ( $count == 0 )
			    {
				$meta_array[ '_thumbnail_id' ] = "$last_image_id";
			    }
			    $count++;
			    $iarr[] = "$last_image_id";
			}
			$meta_array[ 'detail_images' ] = serialize ( $iarr );
			$meta_array[ 'detail_images' ] = serialize ( $meta_array[ 'detail_images' ] );
		    }
		    else
		    {
			unset ( $meta_array[ '_thumbnail_id' ] );
			unset ( $meta_array[ 'detail_images' ] );
		    }
		}
		else
		{
		    unset ( $meta_array[ '_thumbnail_id' ] );
		    unset ( $meta_array[ 'detail_images' ] );
		}
		foreach ( $meta_array as $k => $v )
		{
		    write_meta ( $last_id , $k , $v );
		}
	    }
	}
    }
    break;
}
fclose ( $fp );
// Make JSON file
$file_handler = fopen ( "javo_all_items_1_.json" , 'w' );
fwrite ( $file_handler , json_encode ( $javo_all_posts ) );
fclose ( $file_handler );


function write_meta ( $last_id , $k , $v )
{
    global $fp;
    mysql_query ( "insert into wp_postmeta (post_id, meta_key, meta_value) values ('$last_id','$k','$v')" ) or die ( mysql_error () );
    write_to_file ( $fp , "insert into wp_postmeta (post_id, meta_key, meta_value) values ('$last_id','$k','$v')" );
}

function description_entry ( $data , $i , $j , $num )
{
    $res = validentry ( $data , $i , $j , $num );
    if ( strcmp ( $res , mysql_escape_string ( "N/A" ) ) != 0 )
    {
	return $res . "<br/>";
    }
    else
    {
	return "";
    }
}

function validentry ( $data , $i , $j , $num )
{
    if ( isset ( $data->sheets[ $i ][ 'cells' ][ $j ][ $num ] ) && strlen ( $data->sheets[ $i ][ 'cells' ][ $j ][ $num ] ) > 0 )
	return mysql_escape_string ( $data->sheets[ $i ][ 'cells' ][ $j ][ $num ] );
    else
	return mysql_escape_string ( "N/A" );
}

function write_to_file ( $fp , $ins )
{
    fwrite ( $fp , "$ins;" . PHP_EOL );
}

function clean_json ( $javo_all_posts )
{
    $ind = array();
    if ( count ( $javo_all_posts ) > 0 )
    {
	foreach ( $javo_all_posts as $index => $post_object )
	{
	    $check_wp_post = "SELECT * from wp_posts where `ID` = {$post_object[ 'post_id' ]}";
	    $result_check_wo_post = mysql_query ( $check_wp_post ) or die ( mysql_error () );
	    if ( mysql_num_rows ( $result_check_wo_post ) == 0 )
		$ind[] = $index;
	}
	foreach ( $ind as $i )
	    unset ( $javo_all_posts[ $i ] );
    }
    return $javo_all_posts;
}

function upate_json ( $javo_all_posts , $post_id , $title , $lat , $long )
{
    $javo_result = Array(
	'post_id' => $post_id
	, 'post_title' => htmlentities ( $title )
	, 'lat' => $lat
	, 'lng' => $long
	, 'rating' => ""
	, 'icon' => ""
	, 'cat_term' => ""
	, 'loc_term' => ""
	, 'tags' => ""
    );
    $javo_all_posts[] = $javo_result;
    return $javo_all_posts;
}

function get_lat_long ( $address )
{

    $add = $address;
    $lat = '-25.274398';
    $long = '133.775136';
    $address = str_replace ( " " , "+" , $address );

    sleep ( 1 );
    $json = file_get_contents ( "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=Australia" );
    $json = json_decode ( $json );

    if ( isset ( $json->{'results'}[ 0 ] ) )
    {
	if ( isset ( $json->{'results'}[ 0 ]->{'geometry'}->{'location'}->{'lat'} ) )
	{
	    $lat = $json->{'results'}[ 0 ]->{'geometry'}->{'location'}->{'lat'};
	}
	else
	{
	    echo ( "**** Google Map API has stopped responding ****".PHP_EOL );
	}
	if ( isset ( $json->{'results'}[ 0 ]->{'geometry'}->{'location'}->{'lng'} ) )
	{
	    $long = $json->{'results'}[ 0 ]->{'geometry'}->{'location'}->{'lng'};
	}
	else
	{
	    echo ( "**** Google Map API has stopped responding ****".PHP_EOL );
	}
	if ( isset ( $json->{'results'}[ 0 ]->{'formatted_address'} ) )
	{
	    $add = $json->{'results'}[ 0 ]->{'formatted_address'};
	}
	else
	{
	    echo ( "**** Google Map API has stopped responding ****".PHP_EOL );
	}
    }
    else 
    {
	echo ( "**** Google Map API has stopped responding ****".PHP_EOL );
    }
    return array( 'lat' => $lat , 'long' => $long , 'address' => $add );
}
