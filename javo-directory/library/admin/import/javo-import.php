<?php
if (!function_exists ('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
class Javo_Import {

    public $message = "";
    public $attachments = false;
    function Javo_Import() {
        add_action('admin_menu', array(&$this, 'javo_admin_import'));
        add_action('admin_init', array(&$this, 'register_javo_theme_settings'));

    }
    function register_javo_theme_settings() {
        register_setting( 'javo_options_import_page', 'javo_options_import');
    }

    function init_javo_import() {
        if(isset($_REQUEST['import_option'])) {
            $import_option = $_REQUEST['import_option'];
            if($import_option == 'content'){
                $this->import_content('proya_content.xml');
            }elseif($import_option == 'custom_sidebars') {
                $this->import_custom_sidebars('custom_sidebars.txt');
            } elseif($import_option == 'widgets') {
                $this->import_widgets('widgets.txt');
            } elseif($import_option == 'theme_settings'){
                $this->import_theme_settings('theme_settings.txt');
            }elseif($import_option == 'menus'){
                $this->import_menus('menus.txt');
            }elseif($import_option == 'settingpages'){
                $this->import_settings_pages('settingpages.txt');
            }elseif($import_option == 'complete_content'){
                $this->import_content('proya_content.xml');
                $this->import_theme_settings('javo_theme_settings.txt');
                $this->import_widgets('widgets.txt');
                $this->import_menus('menus.txt');
                $this->import_settings_pages('settingpages.txt');
                $this->message = __("Content imported successfully", "javo_fr");
            }
        }
    }

    public function import_content($file){
        if (!class_exists('WP_Importer')) {
            ob_start();
            $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
            require_once($class_wp_importer);
            require_once(get_template_directory() . '/library/admin/import/class.wordpress-importer.php');
            $javo_import = new WP_Import();
            set_time_limit(0);
            $path = get_template_directory() . '/library/admin/import/files/' . $file;

            $javo_import->fetch_attachments = $this->attachments;
            $returned_value = $javo_import->import($path);
            if(is_wp_error($returned_value)){
                $this->message = __("An Error Occurred During Import", "javo_fr");
            }
            else {
                $this->message = __("Content imported successfully", "javo_fr");
            }
            ob_get_clean();
        } else {
            $this->message = __("Error loading files", "javo_fr");
        }
    }

    public function import_widgets($file){
        $this->import_custom_sidebars('custom_sidebars.txt');
        $options = $this->file_options($file);
        foreach ((array) $options['widgets'] as $javo_widget_id => $javo_widget_data) {
            update_option( 'widget_' . $javo_widget_id, $javo_widget_data );
        }
        $this->import_sidebars_widgets($file);
        $this->message = __("Widgets imported successfully", "javo_fr");
    }

    public function import_sidebars_widgets($file){
        $javo_sidebars = get_option("sidebars_widgets");
        unset($javo_sidebars['array_version']);
        $data = $this->file_options($file);
        if ( is_array($data['sidebars']) ) {
            $javo_sidebars = array_merge( (array) $javo_sidebars, (array) $data['sidebars'] );
            unset($javo_sidebars['wp_inactive_widgets']);
            $javo_sidebars = array_merge(array('wp_inactive_widgets' => array()), $javo_sidebars);
            $javo_sidebars['array_version'] = 2;
            wp_set_sidebars_widgets($javo_sidebars);
        }
    }

    public function import_custom_sidebars($file){
        $options = $this->file_options($file);
        update_option( 'javo_sidebars', $options);
        $this->message = __("Custom sidebars imported successfully", "javo_fr");
    }

    public function import_theme_settings($file){
        $options = $this->file_options($file);
        update_option( 'javo_themes_settings', $options);
        $this->message = __("Options imported successfully", "javo_fr");
    }

    public function import_menus($file){
        global $wpdb;
        $javo_terms_table = $wpdb->prefix . "terms";
        $this->menus_data = $this->file_options($file);		


        $menu_array = array();
        foreach ($this->menus_data as $registered_menu => $menu_slug) {
            $term_rows = $wpdb->get_results("SELECT * FROM $javo_terms_table where slug='{$menu_slug}'", ARRAY_A);

            if(isset($term_rows[0]['term_id'])) {
                $term_id_by_slug = $term_rows[0]['term_id'];
            } else {
                $term_id_by_slug = null;
            }
            $menu_array[$registered_menu] = $term_id_by_slug;
        }
        set_theme_mod('nav_menu_locations', array_map('absint', $menu_array ) );

    }
    public function import_settings_pages($file){
        $pages = $this->file_options($file);

        foreach($pages as $javo_page_option => $javo_page_id){
			echo( "Key=> {$javo_page_option}, Value => {$javo_page_id} <br>" );
            update_option( $javo_page_option, $javo_page_id);
        }
    }
    public function file_options($file){
        $file_content = "";
        $file_for_import = get_template_directory() . '/library/admin/import/files/' . $file;
        if ( file_exists($file_for_import) ) {
            $file_content = $this->javo_file_contents($file_for_import);
        } else {
            $this->message = __("File doesn't exist", "javo_fr");
        }
        if ($file_content) {
            $unserialized_content = unserialize(base64_decode($file_content));
            if ($unserialized_content) {
                return $unserialized_content;
            }
        }
        return false;
    }

    function javo_file_contents( $path ) {
        $javo_content = '';
        if ( function_exists('realpath') )
            $filepath = realpath($path);
        if ( !$filepath || !@is_file($filepath) )
            return '';

        if( ini_get('allow_url_fopen') ) {
            $javo_file_method = 'fopen';
        } else {
            $javo_file_method = 'file_get_contents';
        }
        if ( $javo_file_method == 'fopen' ) {
            $javo_handle = fopen( $filepath, 'rb' );

            if( $javo_handle !== false ) {
                while (!feof($javo_handle)) {
                    $javo_content .= fread($javo_handle, 8192);
                }
                fclose( $javo_handle );
            }
            return $javo_content;
        } else {
            return file_get_contents($filepath);
        }
    }

    function javo_admin_import() {
        if(isset($_REQUEST['import'])){
            //$this->init_javo_import();
        }

        //$this->pagehook = add_submenu_page('javo_options_proya_page', 'Javo Theme', esc_html__('Javo Import', 'javo'), 'manage_options', 'javo_options_import_page', array(&$this, 'javo_generate_import_page'));
        $this->pagehook = add_menu_page('Javo Theme', esc_html__('Javo Import', 'javo_fr'), 'manage_options', 'javo_options_import_page', array(&$this, 'javo_generate_import_page'),'dashicons-download');

    }

    function javo_generate_import_page() {
        //wp_enqueue_style('bootstrap');   // Get style files if you need.
        ?>
        <div id="jv-metaboxes-general" class="wrap jv-a-page jv-a-page-info">
            <h2 class="jv-a-page-title"><?php _e('javo-demo — One-Click Import', 'javo_fr') ?></h2>
            <form method="post" action="" id="importContentForm">
                <div class="jv-a-page-form">
                    <div class="jv-a-page-form-section-holder clearfix">
                        <h3 class="jv-a-page-section-title">Import Demo Content</h3>
                        <div class="jv-a-page-form-section">
                            <div class="jv-a-field-desc">
                                <h4><?php esc_html_e('Import', 'javo_fr'); ?></h4>
                                <p>Choose demo content you want to import</p>
                            </div>
                            <div class="jv-a-section-content">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <em class="jv-a-field-description">Demo Site</em>
                                            <select name="import_example" id="import_example" class="form-control jv-a-form-element">
                                               <option value="">Please Select One</option>
												<option value="javo-demo">Demo1</option>
												<option value="javo-demo2">Demo2</option>
												<option value="javo-demo3">Demo3</option>											
												<option value="javo-demo4">Demo4</option>											
												<option value="javo-demo5">Demo5</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <em class="jv-a-field-description">Import Type</em>
                                            <select name="import_option" id="import_option" class="form-control jv-a-form-element">
                                                <option value="">Please Select One</option>
                                                <option value="complete_content">All</option>
                                                <option value="content">Content</option>
                                                <option value="widgets">Widgets</option>
                                                <option value="menus">Menus</option>
                                                <option value="theme_settings">Theme Settings</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="jv-a-page-form-section" >
                            <div class="jv-a-field-desc">
                                <h4><?php esc_html_e('Import attachments', 'javo_fr'); ?></h4>
                                <p>Do you want to import media files?</p>
                            </div>

                            <div class="jv-a-section-content">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <input type="checkbox" value="1" class="jv-a-form-element" name="import_attachments" id="import_attachments" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-button-section clearfix">
                                    <input type="submit" class="btn btn-primary btn-sm " value="Import" name="import" id="import_demo_data" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"></div>

                        </div>

                        <div class="import_load"><span><?php _e('The import process may take some time. Please be patient.', 'javo_fr') ?> </span><br />
                            <div class="jv-progress-bar-wrapper html5-progress-bar">
                                <div class="progress-bar-wrapper">
                                    <progress id="progressbar" value="0" max="100"></progress>
                                </div>
                                <div class="progress-value">0%</div>
                                <div class="progress-bar-message">
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-warning">
                            <strong><?php _e('Important notes:', 'javo_fr') ?></strong>
                            <ul>
                                <li><?php _e('Please note that import process will take time needed to download all attachments from demo web site.', 'javo_fr'); ?></li>
                                <li> <?php _e('If you plan to use shop, please install WooCommerce before you run import.', 'javo_fr')?></li>
                            </ul>
                        </div>


                        <!--								<div class="success_msg alert" id="success_msg" >--><?php //echo $this->message; ?><!--</div>-->




                    </div>

                </div>
            </form>
        </div>
        <script type="text/javascript">
		jQuery( function( $ ){

			$(document)
				.on('click', '#import_demo_data', function(e)
				{				
                    e.preventDefault();

                    if( $( "#import_option" ).val() == "" ) {
                    	alert('Please select Import Type.');
                    	return false;
                    }

                    if (confirm('Are you sure, you want to import Demo Data now?')) {

                        $('.import_load').css('display','block');

                        var progressbar		= $('#progressbar')
                        var import_opt		= $( "#import_option" ).val();
                        var import_expl		= $( "#import_example" ).val();

                        var p = 0;

                        if( import_opt == 'content' ){
                            for( var i=1; i<10; i++ ){
                                var str;
                                if(i < 10) str = 'javo_demo_content_0'+i+'.xml';
                                else str = 'javo_demo_content_'+i+'.xml';

                                jQuery.ajax({
                                    type: 'POST',
                                    url: ajaxurl,
                                    data: {
                                        action: 'javo_dataImport',
                                        xml: str,
                                        example: import_expl,
                                        import_attachments: ($("#import_attachments").is(':checked') ? 1 : 0)
                                    },
                                    success: function(data, textStatus, XMLHttpRequest){
                                        p+= 10;
                                        $('.progress-value').html((p) + '%');
                                        progressbar.val(p);
                                        if (p == 90) {
                                            str = 'javo_demo_content_10.xml';
                                            jQuery.ajax({
                                                type: 'POST',
                                                url: ajaxurl,
                                                data: {
                                                    action: 'javo_dataImport',
                                                    xml: str,
                                                    example: import_expl,
                                                    import_attachments: ($("#import_attachments").is(':checked') ? 1 : 0)
                                                },
                                                success: function(data, textStatus, XMLHttpRequest){
                                                    p+= 10;
                                                    $('.progress-value').html((p) + '%');
                                                    progressbar.val(p);
                                                    $('.progress-bar-message').html('<div class="alert alert-success"><strong>Import is completed</strong></div>');
                                                },
                                                error: function(MLHttpRequest, textStatus, errorThrown){
                                                }
                                            });
                                        }
                                    },
                                    error: function(MLHttpRequest, textStatus, errorThrown){
                                    }
                                });
                            }
                        } else if(import_opt == 'widgets') {
                            jQuery.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'javo_widgetsImport',
                                    example: import_expl
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    $('.progress-value').html((100) + '%');
                                    progressbar.val(100);
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
                            });
                            $('.progress-bar-message').html('<div class="alert alert-success"><strong>Import is completed</strong></div>');
                        } else if(import_opt == 'menus'){
							 jQuery.ajax({
								xhr: function()
								{
									var xhr;

									if( window.XMLHttpRequest )
									{
										xhr = new window.XMLHttpRequest();
									}else{
										xhr = new ActiveXObject( "Microsoft.XMLHTTP" );
									}

									xhr.upload.addEventListener( 'progress', function( e ){
										if( e.lengthComputable )
										{
											progressbar.val( e.loaded / e.total * 50 );
											console.log( e.loaded / e.total * 50 );
										}
									}, false );

									xhr.addEventListener( 'progress', function( e ){
										if( e.lengthComputable )
										{
											progressbar.val( e.loaded / e.total * 50 );
											console.log( e.loaded / e.total * 50 );
										}
									}, false );

									return xhr;
								}
								, type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'javo_menusImport',
                                    example: import_expl
                                },
                                success: function(data, textStatus, XMLHttpRequest){
									console.log( data );
                                    $('.progress-value').html((100) + '%');
                                    progressbar.val(100);
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
                            });
                            $('.progress-bar-message').html('<div class="alert alert-success"><strong>Import is completed</strong></div>');

                        } else if(import_opt == 'theme_settings'){
                            jQuery.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'javo_optionsImport',
                                    example: import_expl
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    $('.progress-value').html((100) + '%');
                                    progressbar.val(100);
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
                            });
                            $('.progress-bar-message').html('<div class="alert alert-success"><strong>Import is completed</strong></div>');
                        }else if(import_opt == 'complete_content'){
                            for(var i=1;i<10;i++){
                                var str;
                                if (i < 10) str = 'javo_demo_content_0'+i+'.xml';
                                else str = 'javo_demo_content_'+i+'.xml';
                                jQuery.ajax({
                                    type: 'POST',
                                    url: ajaxurl,
                                    data: {
                                        action: 'javo_dataImport',
                                        xml: str,
                                        example: import_expl,
                                        import_attachments: ($("#import_attachments").is(':checked') ? 1 : 0)
                                    },
                                    success: function(data, textStatus, XMLHttpRequest){
                                        p+= 10;
                                        $('.progress-value').html((p) + '%');
                                        progressbar.val(p);
                                        if (p == 90) {
                                            str = 'javo_demo_content_10.xml';
                                            jQuery.ajax({
                                                type: 'POST',
                                                url: ajaxurl,
                                                data: {
                                                    action: 'javo_dataImport',
                                                    xml: str,
                                                    example: import_expl,
                                                    import_attachments: ($("#import_attachments").is(':checked') ? 1 : 0)
                                                },
                                                success: function(data, textStatus, XMLHttpRequest){
                                                    jQuery.ajax({
                                                        type: 'POST',
                                                        url: ajaxurl,
                                                        data: {
                                                            action: 'javo_otherImport',
                                                            example: import_expl
                                                        },
                                                        success: function(data, textStatus, XMLHttpRequest){

																console.log( data );
                                                            $('.progress-value').html((100) + '%');
                                                            progressbar.val(100);
                                                            $('.progress-bar-message').html('<div class="alert alert-success">Import is completed.</div>');
                                                        },
                                                        error: function(MLHttpRequest, textStatus, errorThrown){
                                                        }
                                                    });
                                                },
                                                error: function(MLHttpRequest, textStatus, errorThrown){
                                                }
                                            });
                                        }
                                    },
                                    error: function(MLHttpRequest, textStatus, errorThrown){
                                    }
                                });
                            }
                        }
                    }
                    return false;
                });



				});
        </script>

        </div>

    <?php	}

}
global $my_Javo_Import;
$my_Javo_Import = new Javo_Import();



if(!function_exists('javo_dataImport'))
{
    function javo_dataImport()
    {
        global $my_Javo_Import;

        if ($_POST['import_attachments'] == 1)
            $my_Javo_Import->attachments = true;
        else
            $my_Javo_Import->attachments = false;

        $folder = "javo-demo/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $my_Javo_Import->import_content($folder.$_POST['xml']);

        die();
    }

    add_action('wp_ajax_javo_dataImport', 'javo_dataImport');
}

if(!function_exists('javo_widgetsImport'))
{
    function javo_widgetsImport()
    {
        global $my_Javo_Import;

        $folder = "javo-demo/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $my_Javo_Import->import_widgets($folder.'widgets.txt');

        die();
    }

    add_action('wp_ajax_javo_widgetsImport', 'javo_widgetsImport');
}

if(!function_exists('javo_menusImport'))
{
    function javo_menusImport()
    {
        global $my_Javo_Import;

        $folder = "javo-demo/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $my_Javo_Import->import_menus($folder.'menus.txt');

        die();
    }

    add_action('wp_ajax_javo_menusImport', 'javo_menusImport');
}




if(!function_exists('javo_optionsImport'))
{
    function javo_optionsImport()
    {
        global $my_Javo_Import;

        $folder = "javo-demo/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $my_Javo_Import->import_theme_settings($folder.'theme_settings.txt');

        die();
    }

    add_action('wp_ajax_javo_optionsImport', 'javo_optionsImport');
}

if(!function_exists('javo_otherImport'))
{
    function javo_otherImport()
    {
        global $my_Javo_Import;

        $folder = "javo-demo/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $my_Javo_Import->import_theme_settings($folder.'theme_settings.txt');
        $my_Javo_Import->import_widgets($folder.'widgets.txt');
        $my_Javo_Import->import_menus($folder.'menus.txt');
        $my_Javo_Import->import_settings_pages($folder.'settingpages.txt');

        die();
    }

    add_action('wp_ajax_javo_otherImport', 'javo_otherImport');
}