<?php
/**
 * Navigation Menu widget class
 *
 * @since 3.0.0
 */
 class javo_Mailchimp_Widget extends WP_Widget {

        var $api_key;

	function __construct() {
		global $javo_tso;
		//echo $javo_tso->get('mailchimp_api');
		$widget_ops = array( 'description' => __('Mailchimp newsletter subscribe form.','javo_fr') );
		parent::__construct( 'kleo_mailchimp', __('[Javo] Mailchimp Newsletter','javo_fr'), $widget_ops );
                //$this->api_key = sq_option('mailchimp_api');
                $this->api_key = $javo_tso->get('mailchimp_api', '3d7e0a133dac5ee64680d18b736cbfab-us10');
	}

	function widget($args, $instance) {

		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		if ( isset ( $_POST['mc_email']) ) {

			if ( isset ( $this->api_key ) && !empty ( $this->api_key ) ) {

				include_once (JAVO_SYS_DIR.'/functions/MCAPI.class.php');

				$mcapi = new MCAPI($this->api_key);

				$merge_vars = array(
                                        "YNAME" => $_POST['yname']
				);

				$list_id = $instance['mailchimp_list'];

				if($mcapi->listSubscribe($list_id, $_POST['mc_email'], $merge_vars ) ) {
					// Everything ok
					$msg = '<span style="color:green;">'.__('Success!&nbsp; Check your inbox or spam folder for a message containing a confirmation link.','javo_fr').'</span>';
				}else{
					// An error ocurred, return error message
					$msg = '<span style="color:red;"><b>'.__('Error:','javo_fr').'</b>&nbsp; ' . $mcapi->errorMessage.'</span>';

				}

			}

		}



		echo $args['before_widget'];
		echo '<div class="panel">';
		if ( !empty($instance['title']) )
			echo $args['before_title'] .'<i class="icon-thumbs-up"></i> '. $instance['title'] . $args['after_title'];

                //Before text
		if ( ! empty( $instance['mailchimp_before_text'] ) ) :
			echo	'<p>'.$instance['mailchimp_before_text'].'</p>';
		endif;


                echo '
                  <!--Newsletter form-->
                  <form id="newsletter-form" name="newsletter-form" data-url="'.trailingslashit(home_url()).'" method="post" class="row">
					<div class="col-md-4">
						<div class="mc-input-name">
							<span class="glyphicon glyphicon-envelope"></span>
						</div>
						<input type="text" name="yname" id="yname" placeholder="Your name" required>
					</div>
					<div class="col-md-5">
						<div class="me-input-email">
							<span class="glyphicon glyphicon-user"></span>
						</div>
						<input type="email" name="mc_email" id="mc_email" placeholder="Your email" required>
					</div>
					<div class="col-md-3">
						<button type="submit" id="newsletter-submit" name="newsletter-submit" class="small radius button expand">'.__("JOIN US",'javo_fr').'</button>
					</div>
					<textarea name="_mc4wp_required_but_not_really" style="display: none !important;"></textarea>

					<input type="hidden" id="list" name="list" value="'.$instance['mailchimp_list'].'" />

					<div class="mc-after-text"><small id="result">'.(isset ( $msg )?$msg:'' ).'</small></div>';







                 /*   <div class="'.(isset($args['id']) && (strpos($args['id'], 'footer') !== false)?"four":"twelve").' columns">
                      <div class="row collapse">
                        <div class="two mobile-one columns">
                                <span class="prefix"><i class="icon-user"></i></span>
                        </div>
                        <div class="ten mobile-three columns">
                                <input type="text" name="yname" id="yname" placeholder="Your name" required>
                        </div>
                      </div>
                    </div>
                    <div class="'.(isset($args['id']) && (strpos($args['id'], 'footer') !== false)?"five":"twelve").' columns">
                      <div class="row collapse">
                        <div class="two mobile-one columns">
                                <span class="prefix"><i class="icon-envelope"></i></span>
                        </div>
                        <div class="ten mobile-three columns">
                                <input type="email" name="mc_email" id="mc_email" placeholder="Your email" required>
                        </div>
                      </div>
                    </div>
                    <div class="'.(isset($args['id']) && (strpos($args['id'], 'footer') !== false)?"three":"six").' columns">
                        <p><button type="submit" id="newsletter-submit" name="newsletter-submit" class="small radius button expand">'.__("JOIN US",'javo_fr').'</button></p>
                    </div>
                    <div class="twelve column">

                      <div><small id="result">'.(isset ( $msg )?$msg:'' ).'</small></div>';*/

                        // After text
                        if ( ! empty( $instance['mailchimp_after_text'] ) )	{
                                echo	$instance['mailchimp_after_text'];
                        }

                echo '</div>
                  </form><!--end newsletter-form-->';


                $nonce = wp_create_nonce("mc_mail");
                echo "<script type='text/javascript'>
                    jQuery(document).ready(function($) {
                        // Prepare the Newsletter and send data to Mailchimp
                        $('#newsletter-form').submit(function() {
                                $.ajax({
                                        url: ajaxurl,
                                        type: 'POST',
                                        data: {
                                                action: 'mc_action',
                                                mc_email: $('#mc_email').attr('value'),
                                                yname: $('#yname').attr('value'),
                                                list: $('#list').attr('value'),
                                                nonce: '".$nonce."'
                                        },
                                        success: function(data){
                                                $('#result').html(data).css('color', 'green');
                                        },
                                        error: function() {
                                                $('#result').html('".__('Sorry, an error occurred.', 'javo_fr')."').css('color', 'red');
                                        }

                                });
                                return false;
                        });
                    });
                </script>";
                echo '</div><!--end panel-->';
		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		$instance['mailchimp_before_text'] =  stripslashes($new_instance['mailchimp_before_text']) ;
		$instance['mailchimp_after_text'] =  stripslashes($new_instance['mailchimp_after_text']) ;
		$instance['mailchimp_list'] = $new_instance['mailchimp_list'];
		return $instance;
	}

	function form( $instance ) {
		global $javo_tso;
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$mailchimp_before_text = isset( $instance['mailchimp_before_text'] ) ? $instance['mailchimp_before_text'] : '';
		$mailchimp_after_text = isset( $instance['mailchimp_after_text'] ) ? $instance['mailchimp_after_text'] : '';
		$mailchimp_list = isset( $instance['mailchimp_list'] ) ? $instance['mailchimp_list'] : '';

		if ( !function_exists('curl_init') ) {
			echo __('Curl is not enabled. Please contact your hosting company and ask them to enable CURL.','javo_fr');
			return;
		}

		if ( !isset ( $this->api_key ) && empty ( $this->api_key ) ) {
			echo __('You need to enter your MailChimp API_KEY in theme options before using this widget.','javo_fr');
			return;
		}


		if ( isset ( $this->api_key ) && !empty ( $this->api_key ) ) {
			include_once (JAVO_SYS_DIR.'/functions/MCAPI.class.php');
			$api_key = $javo_tso->get('mailchimp_api','55fe57298275c135cad2e8eb7fe36983-us7');

			$mcapi = new MCAPI($api_key);

			$lists = $mcapi->lists();
		}

		?>
		<p>
                    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','javo_fr') ?></label>
                    <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>

		<p>
                    <label for="<?php echo $this->get_field_id('mailchimp_list'); ?>"><?php _e('Select List:','javo_fr'); ?></label>
                    <select id="<?php echo $this->get_field_id('mailchimp_list'); ?>" name="<?php echo $this->get_field_name('mailchimp_list'); ?>">

                    <?php
                    foreach ($lists['data'] as $key => $value) {
                            $selected = (isset($mailchimp_list) && $mailchimp_list == $value['id']) ? ' selected="selected" ' : '';
                            ?>
                                    <option <?php echo $selected; ?>value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                            <?php
                    }
                    ?>

                    </select>
		</p>

		<p>
			<div><label for="<?php echo $this->get_field_id('mailchimp_before_text'); ?>"><?php echo __('Text before form :','javo_fr'); ?></label></div>
			<div><textarea class="widefat" id="<?php echo $this->get_field_id('mailchimp_before_text'); ?>" name="<?php echo $this->get_field_name('mailchimp_before_text'); ?>" rows="5"><?php echo $mailchimp_before_text; ?></textarea></div>
		</p>
		<p>
			<div><label for="<?php echo $this->get_field_id('mailchimp_after_text'); ?>"><?php echo __(' Text after form:','javo_fr'); ?></label></div>
			<div><textarea class="widefat" id="<?php echo $this->get_field_id('mailchimp_after_text'); ?>" name="<?php echo $this->get_field_name('mailchimp_after_text'); ?>" rows="5"><?php echo $mailchimp_after_text; ?></textarea></div>
		</p>

		<?php
	}
}


add_action( 'widgets_init', create_function( '', 'register_widget( "javo_Mailchimp_Widget" );' ) );


/*
 * Ajax helper
*/
add_action('wp_ajax_mc_action', 'mc_action');
add_action('wp_ajax_nopriv_mc_action', 'mc_action');

function mc_action(){
	global $javo_tso;

    $api_key = $javo_tso->get('mailchimp_api','55fe57298275c135cad2e8eb7fe36983-us7');

    if ( isset ( $_POST['mc_email']) && wp_verify_nonce( $_POST['nonce'], "mc_mail")) {

        if ( isset ( $api_key ) && !empty ( $api_key ) ) {

				include_once (JAVO_SYS_DIR.'/functions/MCAPI.class.php');
                $api_key = $javo_tso->get('mailchimp_api','55fe57298275c135cad2e8eb7fe36983-us7');

                $mcapi = new MCAPI($api_key);

                $name = explode(" ", $_POST['yname']);
                $fname = (!empty($name[0])?$name[0]:"");
                unset($name[0]);
                $lname = (!empty($name)?join(" ", $name):"");

                $merge_vars = array(
                        'FNAME' => $fname,
                        'LNAME' => $lname
                );

                $list_id = $_POST['list'];

                $answer = $mcapi->listSubscribe($list_id, $_POST['mc_email'], $merge_vars );
                 if($mcapi->errorCode) {
                    // An error ocurred, return error message
                    $msg = '<span style="color:red;"><b>'.__('Error:','javo_fr').'</b>&nbsp; ' . $mcapi->errorMessage.'</span>';
                }else{
                    // It worked!
                    $msg = __('Success!&nbsp; Check your inbox or spam folder for a message containing a confirmation link.','javo_fr');
                }
            echo ($msg);
        }
    }
    die();
}

?>
