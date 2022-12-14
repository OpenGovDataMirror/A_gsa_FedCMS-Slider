<?php
/*
    Plugin Name: FedCMS Slider
    Plugin URI: http://www.sites.usa.gov
    Description: Created for use by Wordpress sites running on  <a href="http://www.sites.usa.gov" title="Sites.USA.Gov">Sites.USA.Gov</a>.
    Author: GSA OCSIT
    Version: 1.0.1
    Author URI: http://www.gsa.gov

    FedCMS Slider is released under GPL:
    http://www.opensource.org/licenses/gpl-license.php
*/

if (!defined('WP_PLUGIN_URL')) {
	define('WP_PLUGIN_URL', plugins_url());
}

add_action('admin_menu', 'fedcms_slider_plugin_admin_menu');

function fedcms_slider_plugin_admin_menu() {
    add_menu_page('Add FedCMS Slider ', 'FedCMS Slider', 'publish_posts', 'fedcms_slider', 'fedcms_slider_main', WP_PLUGIN_URL.'/fedcms-slider/gsa-logo.jpg');
    add_submenu_page('fedcms_slider','Edit slider','Edit Slider', 'publish_posts', 'add-Slider', 'fedcms_slider_admin_page');
    add_submenu_page('fedcms_slider','Uninstall FedCMS Slider','Uninstall FedCMS Slider', 'publish_posts', 'uninstall-FedCMS-Slider', 'fedcms_slider_uninstall');
    add_action( 'admin_init', 'register_fedcms_slider_settings' );
}

if (isset($_GET['page']) && $_GET['page'] == 'add-Slider' ) {
    add_action('admin_init', 'fedcms_slider_admin_scripts');
    add_action('admin_print_styles', 'fedcms_slider_admin_styles');
}

function register_fedcms_slider_settings() {
    //register_setting( 'fedcms-settings-group', 'option_etc' );
    global $wpdb;
    $table_name = $wpdb->prefix . "fedcms_slider"; 
    $fedslider_data = $wpdb->get_results("SELECT option_name FROM $table_name ORDER BY id");
    foreach ($fedslider_data as $data) {
        register_setting( 'fedcms-settings-group', $data->option_name);
        }
    
}

function fedcms_slider_admin_scripts() {
    wp_enqueue_script  ('thickbox');
    wp_register_script ('colorpicker-js', plugins_url('colorpicker.js',__FILE__), array('jquery'));
    wp_enqueue_script  ('colorpicker-js' );
    wp_register_script ('custom-js', plugins_url('custom.js',__FILE__), array('jquery'));
    wp_enqueue_script  ('custom-js' );
}

function fedcms_slider_admin_styles() {
    wp_enqueue_style('thickbox');
     wp_register_style('colorpicker-css', plugins_url('colorpicker.css',__FILE__));
    wp_enqueue_style( 'colorpicker-css');
    wp_register_style('tooltip-css', plugins_url('tooltip.css',__FILE__));
    wp_enqueue_style( 'tooltip-css');
    //wp_register_style('fedcms-css', plugins_url('style.css',__FILE__));
    //wp_enqueue_style( 'fedcms-css');
}

/*
function fedcms_slider_a_scripts() {
    wp_register_script ( 'a-js', WP_PLUGIN_URL.'/fedcms_slider/jslider.js', array('jquery'));
    wp_enqueue_script  ('a-js' );
}
*/

add_action('wp_print_scripts', 'fedcms_slider_head_scripts');

function fedcms_slider_head_scripts() {
    wp_enqueue_script('jquery');
    wp_register_script('fedcms_slider', plugins_url('jslider.js',__FILE__), array('jquery'));
	wp_enqueue_script('fedcms_slider');
    wp_register_style('fedcms-css', plugins_url('style.css',__FILE__));
    wp_enqueue_style('fedcms-css');
}

function fedcms_slider_defaults() {
    $default = array(
        'slideNr' => 3,
    	'width' =>   630,
    	'height' => 280,
    	'delay' => 3000,
        'titleSpeed' => 3000,
    	'show-nav' => 'true',
        //'nav-style' => '1',
        'auto-rotate' => 'false',
    	'opacity' => '0.7',
    	'fontFamily' => 'Arial, Helvetica, sans-serif',
    	'titleSize' => 16,
    	'fontSize' => 12,
        //'links' => 'true',
        'keyboardNav' => 'true',
        'titleColor' => 'FFFFFF',
    	'textColor' => 'FFFFFF',
    	'bgColor' => '222222',
    	'chars' => 150,
    	'slide1' => 'GSA.gov',
        'heading1' => 'This is just a test 1',
    	'link1' => 'http://www.gsa.gov',
        'htmlOnly1' => 'false',
        'boxHtml1' => '',
        'slide2' => 'Sites.USA.gov',
        'heading2' => 'This is just a test 2',
        'link2' => 'http://sites.usa.gov',
        'htmlOnly2' => 'false',
        'boxHtml2' => '',
        'slide3' => 'USA.gov',
        'heading3' => 'This is just a test 3',
        'link3' => 'http://www.usa.gov',
        'htmlOnly3' => 'false',
        'boxHtml3' => '',
        'borderWidth' => '0',
        'borderRadius' => '0',
        'borderColor' => 'FFFFFF',
        'holdermar' => '0px 10px 10px 10px',
        'holderfloat' => 'none',
        'target' => '',
        //'randomize' => 0
    );

return $default;
}

function fedcms_slider_install(){
    global $wpdb;
	$table_name = $wpdb->prefix . "fedcms_slider"; 
    
		$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  option_name VARCHAR(255) NOT NULL DEFAULT  'fedcms_slider_defaults',
		  active tinyint(1) NOT NULL DEFAULT  '0',
		  PRIMARY KEY (`id`),
          UNIQUE (
                    `option_name`
            )
		);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

}

register_activation_hook(__FILE__,'fedcms_slider_plugin_install');

function fedcms_slider_plugin_install() {
    register_setting( 'fedcms-settings-group', 'fedcms_slider_defaults' );
    add_option('fedcms_slider_defaults', fedcms_slider_defaults());
    fedcms_slider_install();
    global $wpdb;
	$table_name = $wpdb->prefix . "fedcms_slider"; 
    $sql = "INSERT IGNORE INTO " . $table_name . " values ('','fedcms_slider_defaults','1');";
    $wpdb->query( $sql );
}

function fedcms_slider_plugin_uninstall() {
    global $wpdb;
	$table_name = $wpdb->prefix . "fedcms_slider"; 
    $fedslider_data = $wpdb->get_results("SELECT option_name FROM $table_name ORDER BY id");
    foreach ($fedslider_data as $data) {
        delete_option($data->option_name);
        }
    $sql = "DROP TABLE " . $table_name;
		$wpdb->query( $sql );
}

if(isset($_POST['uninstallfedslider']) && $_POST['uninstallfedslider']){
    fedcms_slider_plugin_uninstall();
}

if (isset($_POST['fedcms_slider-reset']) && $_POST['fedcms_slider-reset'] == 1) { 
    $option=$_GET['edit'];
    update_option($option, fedcms_slider_defaults());
    $message = '<div class="updated" id="message"><p><strong>Settings Reset to Default</strong></p></div>';
}
else
{
    if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
        $message = '<div class="updated" id="message"><p><strong>Settings Saved</strong></p></div>';
        $variable = $_POST['option01'];
        //update_option('fedcms-slider-options', $variable);
    }
}

function fedcms_slider_uninstall(){
  if($_POST['uninstallfedslider']){

echo '<div class="wrap"><div id="message" class="updated fade">';
    echo '<p><h2> FedCMS Slider Successfully Uninstalled </h2></p></div>';
	echo '<h2>'.__('FedCMS Slider Uninstall', 'fedcms_slider').'</h2>';
	echo '<p><p><h3> FedCMS Slider Successfully Uninstalled </h3></p><strong>'.sprintf(__('Deactivate the FedCMS Slider from Plugins panel to Finish the Uninstallation.', 'fedcms_slider'), $deactivate_url).'</strong></p>';
	echo '</div>';    }else { ?>
<form method="post" action="">
<div class="wrap">
	<h2><?php _e('Uninstall FedCMS Slider', 'fedcms_slider'); ?></h2>
	<p>
		<?php _e('Deactivating FedCMS Slider plugin does not remove any data that may have been created, such as the slider data and the image links. To completely remove this plugin, you can uninstall it here.', 'fedcms_slider'); ?>
	</p>
	<p style="color: red">
		<strong><?php _e('WARNING:', 'fedcms_slider'); ?></strong><br />
		<?php _e('Once uninstalled, this cannot be undone. You should use a Database Backup plugin of WordPress to back up all the data first.', 'fedcms_slider'); ?>
	</p>
	<p style="color: red">
		<strong><?php _e('The following WordPress Options/Tables will be DELETED:', 'fedcms_slider'); ?></strong><br />
	</p>
	<table class="widefat" style="width: 200px;">
		<thead>
			<tr>
            <?php
					global $wpdb;
	                $table_name = $wpdb->prefix . "fedcms_slider"; ?>
				<th><?php _e('Table: '.$table_name, 'fedcms_slider'); ?></th>
			</tr>
		</thead>
		<tr>
			<td valign="top" class="alternate">
				<ol>
				<?php
                     $fedslider_data = $wpdb->get_results("SELECT option_name FROM $table_name ORDER BY id");
                      foreach ($fedslider_data as $data) {
                      echo '<li>'.$data->option_name.'</li>';
                      }
				?>
				</ol>
			</td>
		</tr>
	</table>
	<p style="text-align: center;">
		<?php _e('Do you really want to uninstall FedCMS Slider?', 'fedcms_slider'); ?><br /><br />
		<input type="submit" name="uninstallfedslider" value="<?php _e('UNINSTALL FedCMS Slider', 'fedcms_slider'); ?>" class="button-primary" onclick="return confirm('<?php _e('You Are About To Uninstall FedCMS Slider From WordPress.\nThis Action Is Not Reversible.\n\n Choose [Cancel] To Stop, [OK] To Uninstall.', 'fedcms_slider'); ?>')" />
	</p>
</div>
</form>
  <?php    
  }
}

function fedcms_slider_main()
{
    ?>
    <div class="wrap" style="width:820px;"><div id="icon-options-general" class="icon32"><br /></div>
    <h2>FedCMS Slider 1.0.1 Settings</h2>
    <div class="metabox-holder" style="width: 820px; float:left;">
    <small>Welcome to FedCMS Slider 1.0.1</small>
     <div class="inside">
     <br />
     </div>
     </div>
<?php
//FedCMS Slider Functions
    
if(@$_GET['add'])
{
    $option=$_POST['option_name'];
    if(!get_option($_POST['option_name']))
    {
     if($option){
            $option = preg_replace('/[^a-z0-9\s]/i', '', $option);  
            $option = str_replace(" ", "_", $option);
            global $wpdb;
            $table_name = $wpdb->prefix . "fedcms_slider"; 
             $options = get_option($option);
            if($options)
            {
                $v_message= 'Unable to Add Slider,  different name';
            }else{
                $sql = "INSERT INTO " . $table_name . " values ('','".$option."','1');";
                if ($wpdb->query( $sql )){
                        register_setting( 'fedcms-settings-group', $option );
                        add_option($option, fedcms_slider_defaults());
                        $v_message= ' Slider successfully added';
                        }
                else{
                        $v_message= 'Unable to Add Slider, can not insert Slider';
                        }
                };
            }else{
                    $v_message= ' Unable to Add Slider';
                }
    }else{
        $v_message= ' Unable to Add Slider, try a different name';
    }
    ?>
<div class="updated" id="message"><p><strong>
    <?php echo $v_message; ?>
</strong></p></div>
<?php
}

if(@$_GET['delete'])
{
    $option=$_GET['delete'];
    delete_option($option);
    unregister_setting( 'fedcms-settings-group', $option );
    global $wpdb;
    $table_name = $wpdb->prefix . "fedcms_slider"; 
    $sql = "DELETE FROM " . $table_name . " WHERE option_name='".$option."';";
        $wpdb->query( $sql );
?>
<div class="updated" id="message"><p><strong>
    Slider Deleted
</strong></p></div>
<?php
}

if(@$_GET['deactivate']) //if deactivate called, get id and set active to 0
{
    $id=$_GET['deactivate'];
    global $wpdb;
    $table_name = $wpdb->prefix . "fedcms_slider"; 
    $sql = "UPDATE " . $table_name . " SET active='0' WHERE id='".$id."';";
        $wpdb->query( $sql );
        ?>
<div class="updated" id="message"><p><strong>
    Slider Deactivated
</strong></p></div>
<?php
}
if(@$_GET['activate']) // if activate called, get id and set active to 1
{
    $id=$_GET['activate'];
    global $wpdb;
    $table_name = $wpdb->prefix . "fedcms_slider"; 
    $sql = "UPDATE " . $table_name . " SET active='1' WHERE id='".$id."';";
        $wpdb->query( $sql );
        ?>
<div class="updated" id="message"><p><strong>
   Slider Activated
</strong></p></div>
<?php
}
?>
    <table class="widefat" cellspacing="0">
    
        <thead>
            <tr>
                <th scope="col" id="name" class="manage-column column-name" colspan="5">Table Of Sliders</th>
            </tr>
            <tr style="background: #efefef;">
            <td style="width: 100px;text-align:center;"> ID </td>
            <td style="width: 100px;text-align:center;"> Slider Name </td>
            <td style="width: 100px;text-align:center;"> Edit </td>
            <td style="width: 100px;text-align:center;"> Active </td>
            <td style="width: 100px;text-align:center;"> Delete </td>
            </tr>
            </thead>
            <tbody>
            <?php
              get_fedcms_sliders();
             ?>
            </tbody>
        </table>
    </div>
    <?php
}

function get_fedcms_sliders()
{
    global $wpdb;$num=1;
    $table_name = $wpdb->prefix . "fedcms_slider"; 
    $fedslider_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id");
    foreach ($fedslider_data as $data) { 
        
        if($data->active == 1)
        { $active='<a href="?page=fedcms_slider&deactivate='.$data->id.'" class="button">Deactivate</a>';
          $disabled = '';
        }
        else {
            $active='<a href="?page=fedcms_slider&activate='.$data->id.'" class="button">Activate</a>';
            $disabled='disabled="disabled"';
            }
        
       echo '<tr style="height:40px;"><td style="width: 100px;text-align:center;padding: 10px;" >'.$data->id.'</td><td style="width: 100px;text-align:center;padding: 10px;" valign="middle"> '.$data->option_name.' </td><td style="width: 100px;text-align:center;padding: 10px;" >
       <a href="?page=add-Slider&edit='.$data->option_name.'" class="button" '.$disabled.'>Edit</a>        
       </td><td style="width: 100px;text-align:center;padding: 10px;"> '.$active.' </td>
       <td style="width: 100px;text-align:center;padding: 10px;" > <a href="?page=fedcms_slider&delete='.$data->option_name.'" class="button">Delete</a> </td></tr>';
         $num++;}
         ?>
       <form method="post" action="?page=fedcms_slider&add=1">
       <tr style="height:60px;"> <td style="width: 100px;text-align:center;padding: 20px;"><?php echo ($data->id+1); ?> </td>
       <td style="padding: 20px;" colspan="2"><input type="text" id="option_name" name="option_name" size="70" />
       <font style="font-size:10px;">&nbsp;&nbsp;&nbsp;&nbsp;* Do not use spaces, numbers or special characters in the name.</font>
       </td>
       <td style="width: 100px;text-align:center;padding: 20px;" colspan="2"><input type="submit" class="button-primary" style="padding: 10px 30px 10px 30px;" value="Add new Slider" />  </td>
       </tr>
       </form>
       <?php
}


function fedcms_slider_admin_page() { 
    global $message; 
    $option=$_GET['edit'];
    ?>
<div class="wrap" style="width:820px;"><div id="icon-options-general" class="icon32"><br /></div>
<?php echo $message; ?>
<?php 
if($_GET["edit"]){
$option=$_GET['edit'];
}else{
    $option='fedcms_slider_defaults';
    }
?>
<h2><?php _e("FedCMS Slider 1.0.1 Edit Options Page [ ".$option." ]"); ?></h2>

<form method="post" action="options.php">
<?php settings_fields( 'fedcms-settings-group' ); ?>
<?php
//wp_nonce_field('update-options'); 
$options = get_option($option);
?>

    <div class="metabox-holder" style="width: 350px; float:left;">
        <div class="postbox">
        <h3><?php _e("General Setings", 'fedcms_slider'); ?></h3>
            <div id="general" class="inside" style="padding: 10px;">

                <p><?php _e("Slider max width", 'fedcms_slider'); ?>:<input type="text" name="<?php echo $option; ?>[width]" value="<?php echo $options['width'] ?>" size="3" />&nbsp;&nbsp;<?php _e("Minimum height", 'fedcms_slider'); ?>:<input type="text" name="<?php echo $option; ?>[height]" value="<?php echo $options['height'] ?>" size="3" /></p>
                <p><?php _e("Delay between images", 'fedcms_slider'); ?>:<input type="text" name="<?php echo $option; ?>[delay]" value="<?php echo $options['delay'] ?>" size="3" />&nbsp;in ms</p>
                <p><?php _e("Opacity of title and navigation", 'fedcms_slider'); ?>:<input type="text" name="<?php echo $option; ?>[opacity]" value="<?php echo $options['opacity'] ?>" size="3" /></p>
                <p><?php _e("Speed of title appereance", 'fedcms_slider'); ?>:<input type="text" name="<?php echo $option; ?>[titleSpeed]" value="<?php echo $options['titleSpeed'] ?>" size="3" />&nbsp;in ms</p>

                <p><?php _e("Allow keyboard navigation", 'fedcms_slider'); ?>:<select name="<?php echo $option; ?>[keyboard-nav]"><option value="true" <?php selected('true', $options['keyboard-nav']); ?>>Yes</option><option value="false" <?php selected('false', $options['keyboard-nav']); ?>>No</option></select></p>

                <p><?php _e("Show navigation buttons", 'fedcms_slider'); ?>:<select name="<?php echo $option; ?>[show-nav]"><option value="true" <?php selected('true', $options['show-nav']); ?>>Yes</option><option value="false" <?php selected('false', $options['show-nav']); ?>>No</option></select>
                &nbsp;&nbsp;<a href="#" class="tooltip"><span><img src='<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/navigationbuttons.png' /> </span><img src='<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/tooltip.png' /> </a>
                </p>

                <?php /*?><p><?php _e("Navigation Type", 'fedcms_slider'); ?>:<select name="<?php echo $option; ?>[nav-style]"><option value="true" <?php selected('true', $options['nav-style']); ?>>Yes</option><option value="false" <?php selected('false', $options['nav-style']); ?>>No</option></select>

                <p><?php _e("Show images as links ", 'fedcms_slider'); ?>:<select name="<?php echo $option; ?>[links]"><option value="true" <?php selected('true', $options['links']); ?>>Yes</option><option value="false" <?php selected('false', $options['links']); ?>>No</option></select></p>
                <?php */?>
                <p><?php _e("Auto Rotate?", 'fedcms_slider'); ?>:<select name="<?php echo $option; ?>[auto-rotate]"><option value="true" <?php selected('true', $options['auto-rotate']); ?>>Yes</option><option value="false" <?php selected('false', $options['auto-rotate']); ?>>No</option></select>
                &nbsp;&nbsp;<a href="#" class="tooltip"><span><img src='<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/imagebuttons.png' /> </span><img src='<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/tooltip.png' /> </a></p>
                <?php /* ?>
                <p><?php _e("Pause on mouse hover", 'fedcms_slider'); ?>:<select name="<?php echo $option; ?>[hoverPause]"><option value="true" <?php selected('true', $options['hoverPause']); ?>>Yes</option><option value="0" <?php selected('0', $options['hoverPause']); ?>>No</option></select></p>
                <?php */?>
                <p><?php _e("Border Width", 'fedcms_slider'); ?>: <input type="text" name="<?php echo $option; ?>[borderWidth]" value="<?php echo $options['borderWidth'] ?>" size="3" />px &nbsp;&nbsp;<?php _e("Border Radius", 'fedcms_slider'); ?>: <input type="text" name="<?php echo $option; ?>[borderRadius]" value="<?php echo $options['borderRadius'] ?>" size="3" />px</p>
                <p><?php _e("Border Color", 'fedcms_slider'); ?>:<input id="borderColor" type="text" name="<?php echo $option; ?>[borderColor]" value="<?php echo $options['borderColor'] ?>" size="8" />&nbsp;HEX
                &nbsp;&nbsp;<a href="#" class="tooltip"><span><img src='<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/border.png' /> </span><img src='<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/tooltip.png' /> </a></p>
                <p><?php _e("Font family", 'fedcms_slider'); ?>:<select name="<?php echo $option; ?>[fontFamily]"><option value="'Trebuchet MS', Helvetica, sans-serif" <?php selected("'Trebuchet MS', Helvetica, sans-serif", $options['fontFamily']); ?>>'Trebuchet MS', Helvetica, sans-serif</option><option value="Arial, Helvetica, sans-serif" <?php selected('Arial, Helvetica, sans-serif', $options['fontFamily']); ?>>Arial, Helvetica, sans-serif</option><option value="Tahoma, Geneva, sans-serif" <?php selected('Tahoma, Geneva, sans-serif', $options['fontFamily']); ?>>Tahoma, Geneva, sans-serif</option><option value="Verdana, Geneva, sans-serif" <?php selected('Verdana, Geneva, sans-serif', $options['fontFamily']); ?>>Verdana, Geneva, sans-serif</option><option value="Georgia, serif" <?php selected('Georgia, serif', $options['fontFamily']); ?>>Georgia, serif</option><option value="'Arial Black', Gadget, sans-serif" <?php selected("'Arial Black', Gadget, sans-serif", $options['fontFamily']); ?>>'Arial Black', Gadget, sans-serif</option><option value="'Bookman Old Style', serif" <?php selected("'Bookman Old Style', serif", $options['fontFamily']); ?>>'Bookman Old Style', serif</option><option value="'Comic Sans MS', cursive" <?php selected("'Comic Sans MS', cursive", $options['fontFamily']); ?>>'Comic Sans MS', cursive</option><option value="'Courier New', Courier, monospace" <?php selected("'Courier New', Courier, monospace", $options['fontFamily']); ?>>'Courier New', Courier, monospace</option><option value="Garamond, serif" <?php selected("Garamond, serif", $options['fontFamily']); ?>>Garamond, serif</option><option value="'Times New Roman', Times, serif" <?php selected("'Times New Roman', Times, serif", $options['fontFamily']); ?>>'Times New Roman', Times, serif</option><option value="Impact, Charcoal, sans-serif" <?php selected("Impact, Charcoal, sans-serif", $options['fontFamily']); ?>>Impact, Charcoal, sans-serif</option><option value="'Lucida Console', Monaco, monospace" <?php selected("'Lucida Console', Monaco, monospace", $options['fontFamily']); ?>>'Lucida Console', Monaco, monospace</option><option value="'MS Sans Serif', Geneva, sans-serif" <?php selected("'MS Sans Serif', Geneva, sans-serif", $options['fontFamily']); ?>>'MS Sans Serif', Geneva, sans-serif</option></select></p>
                <p><?php _e("Title font size", 'fedcms_slider'); ?>:<input type="text" name="<?php echo $option; ?>[titleSize]" value="<?php echo $options['titleSize'] ?>" size="3" />px</p>
                <p><?php _e("Title color", 'fedcms_slider'); ?>:<input id="titleColor" type="text" name="<?php echo $option; ?>[titleColor]" value="<?php echo $options['titleColor'] ?>" size="8" />&nbsp;HEX</p>
                <small><?php _e("Click on the text box to pick a color", 'fedcms_slider'); ?></small>
                <p><?php _e("Text font size", 'fedcms_slider'); ?>:<input type="text" name="<?php echo $option; ?>[fontSize]" value="<?php echo $options['fontSize'] ?>" size="3" />px
                &nbsp;&nbsp;<a href="#" class="tooltip"><span><img src='<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/titletext.png' /> </span><img src='<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/tooltip.png' /> </a></p>
                <p><?php _e("Text color", 'fedcms_slider'); ?>:<input id="textColor" type="text" name="<?php echo $option; ?>[textColor]" value="<?php echo $options['textColor'] ?>" size="8" />&nbsp;HEX</p>
                <small><?php _e("Click on the text box to pick a color", 'fedcms_slider'); ?></small>
                <p><?php _e("Background color", 'fedcms_slider'); ?>:<input id="bgColor" type="text" name="<?php echo $option; ?>[bgColor]" value="<?php echo $options['bgColor'] ?>" size="8" />&nbsp;HEX</p>
                <small><?php _e("To select color click on Tick at the right bottom of the color panel", 'fedcms_slider'); ?></small>
                <p><input type="submit" class="button" value="<?php _e('Save Settings') ?>" /></p>

            </div>
        </div>
    </div>

   
      <div class="metabox-holder" style="width: 450px;float:right;margin-bottom:-10px;">          
        <div class="postbox">
        <h3><?php _e("Slide Configuration", 'fedcms_slider'); ?> </h3>
            <div class="inside" style="padding: 10px;" id="boximgsrc">

                <p><?php _e("Slides", 'fedcms_slider'); ?>:
                <select name="<?php echo $option; ?>[slideNr]">
                <?php for($s=1; $s<21; $s++){ ?>
                    <option value="<?php echo $s; ?>" <?php selected($s, $options['slideNr']); ?>><?php echo $s; ?></option>
                <?php } ?>
                </select>
                </p>
                <p><?php _e("Slide Text Character limit", 'fedcms_slider'); ?>:
                <input type="text" name="<?php echo $option; ?>[chars]" value="<?php echo $options['chars'] ?>" size="3" />
                </p>
                <p>
                <?php _e("Container Margin", 'fedcms_slider'); ?>: <input type="text" name="<?php echo $option; ?>[holdermar]" size="30" id="holdermar"  value="<?php echo $options['holdermar']; ?>"/>
                &nbsp;&nbsp;<a href="#" class="tooltip"><span><img src='<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/images/cntnerspcing.png' /> </span><img src='<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/tooltip.png' /></a>
                <br /><small>Order of Spacing(margin): TOPpx RIGHTpx BOTTOMpx LEFTpx</small>
                </p> 
               <p>
                <?php _e("Container Float", 'fedcms_slider'); ?>: <select name="<?php echo $option; ?>[holderfloat]" id="holderfloat">
                <option value="none" <?php selected('none', $options['holderfloat']); ?>>None</option>
                <option value="left" <?php selected('left', $options['holderfloat']); ?>>Left</option>
                <option value="right" <?php selected('right', $options['holderfloat']); ?>>Right</option>
                </select>
                &nbsp;&nbsp;<a href="#" class="tooltip"><span><img src='<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/images/float.png' /> </span><img src='<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/tooltip.png' /> </a>
                </p> 
                <p><small><?php _e("Open image links in new window:", 'fedcms_slider'); ?></small>&nbsp;<input type="checkbox" name="<?php echo $option; ?>[target]" value="_blank" <?php if($options['target'] === '_blank'){ echo 'CHECKED';}; ?> /></p>

                <p><input type="submit" class="button" value="<?php _e('Save Settings') ?>" /></p>

            </div>
        </div>
    </div>

    <?php
        $slides = $options['slideNr'] + 1;
        $allowed_tags = wp_kses_allowed_html( 'post' );
    ?>
    <?php for($x=1; $x<$slides; $x++){ ?>
    <div class="metabox-holder" style="width: 450px;float:right;margin-bottom:-10px;">
        <div class="postbox">
        <h3><?php _e("Custom Slider", 'fedcms_slider'); ?> <?php echo '#'.$x; ?><div class="click" id="<?php echo $x; ?>" style="float:right;cursor:pointer;"><?php _e("(+/-)", 'fedcms_slider'); ?></div></h3>
            <div class="inside" id="box<?php echo $x; ?>" style="padding: 10px;display:none;">
                <p>
                <?php _e("HTML Box", 'fedcms_slider'); ?>: <select name="<?php echo $option; ?>[htmlOnly<?php echo $x; ?>]" id="htmlOnly<?php echo $x; ?>" class="fedcms-html-box">
                <option value="false" <?php selected('false', $options['htmlOnly'.$x]); ?>>No</option>
                <option value="true" <?php selected('true', $options['htmlOnly'.$x]); ?>>Yes</option>
                </select>
                </p>
                <?php _e("Heading text", 'fedcms_slider'); ?>:<br />
                <input type="text" name="<?php echo $option; ?>[heading<?php echo $x; ?>]" value="<?php echo $options['heading'.$x.''] ?>" size="62" /><br />
                <p><?php _e("Main body text", 'fedcms_slider'); ?>:<br />
                 <textarea name="<?php echo $option; ?>[slide<?php echo $x; ?>]" cols=51 rows=3><?php echo $options['slide'.$x.''] ?></textarea>
               <br />
                <?php _e("Slider links to (optional)", 'fedcms_slider'); ?>:<br />
                <input type="text" name="<?php echo $option; ?>[link<?php echo $x; ?>]" value="<?php echo $options['link'.$x.''] ?>" size="62" /><br />
                </p>
                <?php _e("Slider background image url(optional)", 'fedcms_slider'); ?>:<br />
                <input type="text" name="<?php echo $option; ?>[imageurl<?php echo $x; ?>]" value="<?php echo $options['imageurl'.$x.''] ?>" size="62" /><br />
                </p>
                <p id="html-code-<?php echo $x;?>" style="display:<?php echo $options['htmlOnly'.$x] == 'true' ? 'block' : 'none';?>;"><?php _e("HTML", 'fedcms_slider'); ?>:<br />
                 <textarea name="<?php echo $option; ?>[boxHtml<?php echo $x; ?>]" cols=51 rows=3><?php echo wp_kses($options['boxHtml'.$x.''],$allowed_tags); ?></textarea>
                </p>
                <p><input type="submit" class="button" value="<?php _e('Save Settings') ?>" /></p>

            </div>
        </div>
    </div>
    <?php } ?>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="<?php echo $option; ?>" />
</form>


<form method="post" style="clear:both;">
<input type="hidden" name="fedcms_slider-reset" value="1" />
<p><input type="submit" class="button-primary" onclick="return confirm('Are you sure you want to reset to default settings?')" value="<?php _e('Reset') ?>" /></p>
</form>

</div>
<?php }

// ADD SLIDER HEAD STYLES
function fedcms_slider_head($option)  {
    $options = get_option($option);/*
?>
<!-- Start Slider options -->
<script type="text/javascript">
var $jq = jQuery.noConflict(); $jq(document).ready(function() { 
    $jq('#<?php echo $option; ?>').coinslider({ width: <?php echo $options['width']; ?>,     height: <?php echo $options['height']; ?>, spw: <?php echo $options['spw']; ?>, sph: <?php echo $options['sph']; ?>, delay: <?php echo $options['delay']; ?>, sDelay: <?php echo $options['sDelay']; ?>, opacity: <?php echo $options['opacity']; ?>, titleSpeed: <?php echo $options['titleSpeed']; ?>, effect: '<?php echo $options["effect"]; ?>', navigation: <?php echo $options['navigation']; ?>, links : <?php echo $options['links']; ?>, stickynav: <?php echo $options['stickynav']; ?>, hoverPause: <?php echo $options['hoverPause']; ?> }); }); 
    </script>
<?php
*/
if($options['auto-rotate'] == 'true')
{
    ?><script type="text/javascript">
    jQuery(document).ready(function( $ )
    {
        setInterval(function () {
            $("div.box").cyclediv($(this).parent().attr('id'),'next');
        },<?php echo $options['delay'];?>);
    });
    </script><?php
}
if($options['keyboard-nav'] == 'true')
{
?><script type="text/javascript">
    jQuery(document).keydown(function(e){
        if (e.keyCode == 37) { 
           jQuery("#previous").trigger("click");
           return false;
        }
        if (e.keyCode == 39) { 
           jQuery("#next").trigger("click");
           return false;
        }
    });
    </script><?php
}
?><style type="text/css" media="screen">
#<?php echo $option; ?>container {
    margin: <?php echo $options['holdermar']; ?>;
    float:<?php echo $options['holderfloat']; ?>;
    /*position: relative;*/
    }
<?php
if($options['show-nav'] == 'false')
{
    ?>
    #<?php echo $option; ?> #previous, #<?php echo $option; ?> #next {
        display:none;
    }
    <?php
}
?>
#<?php echo $option; ?> { 
    max-width: 100%;
    width: <?php echo $options['width']; ?>px; 
    min-height:<?php echo $options['height']; ?>px;
    
    position: relative; 
    }
    
    #<?php echo $option; ?> a, #<?php echo $option; ?> a img {
        border: none !important; 
        text-decoration: none !important; 
        outline: none !important;
        } 
        
    #<?php echo $option; ?> h4 {
        color: #<?php echo $options['textColor']; ?> !important;
        margin: 0px !important;padding: 0px !important;
        font-family: <?php echo $options['fontFamily']; ?> !important;
        font-size: <?php echo $options['titleFont']; ?>px !important;}
    
    #<?php echo $option; ?> .fedcms_slider_title, #<?php echo $option; ?> .fedcms_slider_title a {
        color: #<?php echo $options['titleColor']; ?>  !important;
        font-size: #<?php echo $options['titleSize']; ?>  !important;
        font-family: <?php echo $options['fontFamily']; ?> !important;
    }

    #<?php echo $option; ?> .fedcms_slider_text, #<?php echo $option; ?> .fedcms_slider_text a {
        color: #<?php echo $options['textColor']; ?>  !important;
        font-size: #<?php echo $options['fontSize']; ?>  !important;
        font-family: <?php echo $options['fontFamily']; ?> !important;
    }

    #<?php echo $option; ?> .fedcms_slider_text a:hover {color:#fff !important;}

    #<?php echo $option; ?> .cs-title {
        background: #<?php echo $options['bgColor']; ?>;
        color: #<?php echo $options['textColor']; ?>  !important;
        font-family: <?php echo $options['fontFamily']; ?> !important;
        font-size: <?php echo $options['fontSize']; ?>px !important;
        letter-spacing: normal !important;line-height: normal !important;}
        
    #<?php echo $option; ?> .cs-title{ position:absolute;
    <?php switch($options['layout']){
            case 'stripe-top':{
                echo 'top:0px; height: '.($options['height']/2 -50).'px;width: '.($options['width']-$options['borderWidth']+10).'px; padding: 10px 10px 10px 10px;overflow:hidden;';
                break;
            }
            case 'stripe-right':{ $width=$options['width']/3+$options['borderWidth']-10;
                echo 'margin-left: '.($options['width']-$width).'px;top: 0px;width: '.($width).'px; padding: 10px 10px 0px 10px;';
                break;
            }
            case 'stripe-bottom':{
                          echo 'width: '.($options['width']-$options['borderWidth']-10).'px; padding: 10px;'; 
                break;
            }
            case 'stripe-left':{
                echo 'left:0px;top: 0px;width: '.($options['width']/3+$options['borderWidth']-10).'px; padding: 10px 10px 0px 10px;';
                break;
            }
        }
        ?>
        }
    <?php 
    if($options['buttons'] == 'false')
    {
        echo '#cs-buttons-'.$option.' { display: none; }';
    } 
     ?>
    #<?php echo $option; ?>container .cs-buttons {clear:both; font-size: 0px; margin: <?php echo $options['navplace']; ?>; float: left; }
       #cs-button-<?php echo $option; ?>{ z-index:999;outline:none;}
     <?php
     switch ($options['navstyle']){
        case 'nav_small':  { ?>
       #<?php echo $option; ?>container .cs-buttons { font-size: 0px; margin: 8px 0 0 8px;padding: 8px 8px 8px 5px; float: left; 
                                             background: #dfdfdf;
                                              -webkit-border-radius: 5px;
                                              -moz-border-radius: 5px;
                                              border-radius: 5px;
                                              outline: none !important;
                                            }
                              #<?php echo $option; ?>container .cs-buttons a { margin-left: 5px; height: 5px; width: 5px; float: left; 
                                               background: #<?php echo $options['bgColor']; ?>;
                                               text-indent: -1000px;
                                               -webkit-border-radius: 5px;
                                                -moz-border-radius: 5px;
                                                border-radius: 5px;
                                                outline: none !important;
                                                <?php if($options['vnavenable'])
                                                {
                                                    echo "clear: both;margin-bottom:5px;";
                                                }  ?>
                                                }              
                             #<?php echo $option; ?>container   a.cs-active { background-color: #<?php echo $options['textColor']; ?>; outline: none !important;}          
            <?php break;
        }
        case 'nav_style1':  { ?>
                            #<?php echo $option; ?>container   .cs-buttons a { margin-left: 5px; height: 16px; width: 15px; float: left; 
                                               text-indent: -999px;
                                               background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/nav_style1.png') no-repeat;
                                               background-position: left;
                                               outline: none !important;
                                               <?php if($options['vnavenable'])
                                                {
                                                    echo "clear: both;margin-bottom:5px;";
                                                }  ?>
                                               }             
                              #<?php echo $option; ?>container   .cs-buttons a:hover,
 #<?php echo $option; ?>container a.cs-active { background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/nav_style1.png') no-repeat;background-position: right; outline: none !important;}          
            <?php break;
        }
        
        case 'nav_style2':  { ?>
                             #<?php echo $option; ?>container  .cs-buttons a { margin-left: 5px; height: 15px; width: 15px; float: left; 
                                               text-indent: -999px; background: #dfdfdf;
                                               border: 5px solid #c6c6c6; 
                                               text-indent: -1000px; 
                                               outline: none !important;
                                               opacity:0.7;filter:alpha(opacity=70);
                                                -webkit-border-radius: 15px;
                                                -moz-border-radius: 15px;
                                                border-radius: 15px;
                                               <?php if($options['vnavenable'])
                                                {
                                                    echo "clear: both;margin-bottom:5px;";
                                                }  ?>
                                               }             
                                 
                              #<?php echo $option; ?>container   .cs-buttons a:hover  { background: #efefef; border-color: #444;outline: none !important;}
                              #<?php echo $option; ?>container   a.cs-active { background: #efefef; border-color: #444; outline: none !important;}          
            <?php break;
        }
        
        case 'nav_style3':  { ?>
                              #<?php echo $option; ?>container .cs-buttons a { margin-left: 5px; height: 33px; width: 33px; float: left; 
                                               text-indent: -999px;
                                               background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/nav_2.png') no-repeat;
                                               outline: none !important;
                                              <?php if($options['vnavenable'])
                                                {
                                                    echo "clear: both;margin-bottom:5px;";
                                                }  ?> 
                                               }             
                               #<?php echo $option; ?>container  .cs-buttons a:hover,
 #<?php echo $option; ?>container a.cs-active { background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/nav_2_active.png') no-repeat; outline: none !important;}          
            <?php break;
        }
        
        case 'nav_style4':  { ?>
                             #<?php echo $option; ?>container  .cs-buttons a { margin-left: 5px; height: 12px; width: 12px; float: left; 
                                               background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/nav_style4.png') no-repeat;
                                               background-position: left;
                                               outline: none !important;
                                              <?php if($options['vnavenable'])
                                                {
                                                    echo "clear: both;margin-bottom:5px;";
                                                }  ?>
                                               }             
                              #<?php echo $option; ?>container   .cs-buttons a:hover,
 #<?php echo $option; ?>container a.cs-active { background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/nav_style4.png') no-repeat;background-position: right; outline: none !important;}          
            <?php break;
        }
        case 'nav_style5':  { ?>
                              #<?php echo $option; ?>container .cs-buttons a { margin-left: 5px; height: 14px; width: 14px; float: left; 
                                               background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/nav_style5.png') no-repeat;
                                               background-position: top;
                                               outline: none !important;
                                              <?php if($options['vnavenable'])
                                                {
                                                    echo "clear: both;margin-bottom:5px;";
                                                }  ?>
                                               }             
                               #<?php echo $option; ?>container .cs-buttons a:hover,
 #<?php echo $option; ?>container a.cs-active { background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/nav_style5.png') no-repeat;background-position: bottom; outline: none !important;}          
            <?php break;
        }
        
        default: { ?>
           #<?php echo $option; ?>container .cs-buttons { font-size: 0px; padding: 10px; float: left; outline: none !important;}
           #<?php echo $option; ?>container .cs-buttons a { margin-left: 5px; height: 15px; width: 15px; float: left; 
                            background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/default_style.png') no-repeat;background-position:top;
                                                text-indent: -1000px;
                                                outline: none !important;
                            <?php if($options['vnavenable'])
                                                {
                                                    echo "clear: both;margin-bottom:5px;";
                                                }  ?> }
             #<?php echo $option; ?>container .cs-buttons a:hover  { background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/default_style.png') no-repeat;background-position: bottom;top:15px;outline: none !important;}
            #<?php echo $option; ?>container  a.cs-active { background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/default_style.png') no-repeat;background-position:bottom;outline: none !important;}          
                                
        
            <?php
        }
     }
     ?>
     #<?php echo $option; ?>  .cs-prev,#<?php echo $option; ?>  .cs-next { outline:none; }
     <?php
switch($options['arrstyle']){
    case 'arr_style1':{ ?>
      #<?php echo $option; ?>  .cs-prev {margin-left:8px; line-height: 50px;width: 50px;height:50px; background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/nav_style1_arrows-prev.png')no-repeat; text-indent: -999px;}
      #<?php echo $option; ?>  .cs-next {margin-right: 5px; line-height: 50px;width: 50px;height:50px; background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/nav_style1_arrows-next.png')no-repeat; text-indent: -999px;}
        <?php break;
    }
    case 'arr_style2':{?>
      #<?php echo $option; ?>  .cs-prev {margin-left:8px; line-height: 30px;width: 30px;height:30px; background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/images/nav_style2_arrows-prev.png')no-repeat; text-indent: -999px;}
      #<?php echo $option; ?>  .cs-next {margin-right: 5px; line-height: 30px;width: 30px;height:30px; background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/images/nav_style2_arrows-next.png')no-repeat; text-indent: -999px;}
    <?php       
        break;
    }
    case 'arr_style3':{ ?>
          #<?php echo $option; ?>  .cs-prev {margin-left:8px; line-height: 50px;width: 50px;height:50px; background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/images/nav_style3_arrows-prev.png')no-repeat; text-indent: -999px;}
          #<?php echo $option; ?>  .cs-next {margin-right: 5px; line-height: 50px;width: 50px;height:50px; background: url('<?php echo WP_CONTENT_URL;?>/plugins/fedcms-slider/images/nav_style3_arrows-next.png')no-repeat; text-indent: -999px;}
    <?php
        break;
    }
    default:{?>
           #<?php echo $option; ?>  .cs-prev,#<?php echo $option; ?> .cs-next {font-weight: bold;background: #<?php echo $options['bgColor']; ?> !important;font-size: 28px !important;font-family: "Courier New", Courier, monospace;color: #<?php echo $options['textColor']; ?> 
!important;padding: 0px 10px !important;-moz-border-radius: 5px;-khtml-border-radius: 5px;-webkit-border-radius: 5px;}
    <?php }
}
     ?>
     
       #<?php echo $option; ?>,#<?php echo $option; ?> img {
        border:<?php echo $options['borderWidth']; ?>px solid #<?php echo $options['borderColor']; ?>; 
        border-radius:<?php echo $options['borderRadius']; ?>px;
        -moz-border-radius:<?php echo $options['borderRadius']; ?>px;
        -webkit-border-radius:<?php echo $options['borderRadius']; ?>px;
        }
        <?php
        for($i=1;$i<=$options['slideNr'];$i++)
        {
            echo "#".$option." #box".$i." { background: url('".$options['imageurl'.$i]."') no-repeat; background-size: 100% auto;}"; 
        }
        ?>
</style>
<!-- End Slider options -->
<?php }

function fedcms_slider($option='fedcms_slider_defaults'){ 
    $options = get_option($option);
    if(!$option){ $option='fedcms_slider_defaults';$options = get_option($option); }
    global $wpdb;$num=1;
    $table_name = $wpdb->prefix . "fedcms_slider"; 
    $slider_data = $wpdb->get_results("SELECT active FROM $table_name WHERE option_name='".$option."'");
    foreach ($slider_data as $data) { 
        if($data->active == 1)
        {  fedcms_slider_head($option);
    ?>
    <div id="<?php echo $option.'container'; ?>">
    <?php
  echo '<div id="'.$option.'" style="position:relative;">';

    $randx = range(1, $options['slideNr']);
    if(@$options['randomize']){        //RANDOMISING IMAGES
           shuffle($randx);
    }//Randomise Images
    ?>
    <div id="previous">Previous</div>
    <?php
    $allowed_tags = wp_kses_allowed_html( 'post' );
    foreach( $randx as $x){ ?>
       
       <?php 
 /*<a href="<?php echo $options['link'.$x.'']; ?>" style="background:#fff;" target="<?php echo $options['target']; ?>">*/
        $slide_text=$options['slide'.$x.''];
        $slide_title=$options['heading'.$x.''];
        
       ?>
        <div id="box<?php echo $x;?>" class="box">
        <?php
            if('true' == $options['htmlOnly'.$x.''])
            {
            ?>
                <div id="box-inner<?php echo $x;?>"><?php echo wp_kses($options['boxHtml'.$x.''], $allowed_tags); ?></div>
            <?php
            }
            else
            {
            ?>
           <div id="slider-overlay">
                <span class='fedcms_slider_title'><?php echo $options['link'.$x] != '' ? '<a href="'.$options['link'.$x.''].'" target="'.$options['target'].'">' : ''; echo $slide_title; echo $options['link'.$x] != '' ? '</a>' : '';?></span><span class='fedcms_slider_text'><?php echo $options['link'.$x] != '' ? '<a href="'.$options['link'.$x.''].'" target="'.$options['target'].'">' : ''; echo substr($slide_text,0,$options['chars']); echo strlen($slide_text)>$options['chars'] ? '...':''; echo $options['link'.$x] != '' ? '</a>' : ''; ?></span>
           </div>
           <?php
            }
            ?>
        </div>
       
    <?php 
    //echo $options['link'.$x] != '' ? '</a>' : '';
}
    ?>
    <div id="next">Next</div>
    <div id="slider-navigation">
        <?php 
        for($i=1;$i<=$options['slideNr'];$i++)
        {
            echo '<a href="#" id="slide'.$i.'" class="fedcms_slider_button">'.$i.'</a>&nbsp;&nbsp;';
        }
        ?>
    </div>
    </div></div>
    <?php
   }//ENDIF
  }//END-FOR
}//END FUNCTION SLIDER

function fedcms_slider_short_code($atts) {
    ob_start();
    extract(shortcode_atts(array(
        "name" => ''
    ), $atts));
    fedcms_slider($name);
    $output = ob_get_clean();
    return $output;
}
add_shortcode('fedcms_slider', 'fedcms_slider_short_code');



// REGISTER SLIDER AS WIDGET
add_action('widgets_init', create_function('', "register_widget('fedcms_slider_widget');"));

class fedcms_slider_widget extends WP_Widget {

    function fedcms_slider_widget() { 
       $options = get_option('fedcms_slider_options');
        $widget_ops = array( 'classname' => 'fedcms_slider-widget', 'description' => 'jQuery Image Slider' );
        $control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'fedcms_slider-widget' );
        $this->WP_Widget( 'fedcms_slider-widget', 'Slider Widget', $widget_ops, $control_ops );
    }

    function widget($args, $instance) {
        extract($args);

        echo $before_widget;

            if (!empty($instance['title']))
                echo $before_title . $instance['title'] . $after_title;
                
    if (empty($instance['fedcms_slider']))
    {
        $instance['fedcms_slider']='fedcms_slider_options';
    }
    fedcms_slider($instance['fedcms_slider']); //check

    echo $after_widget;
    }

    function update($new_instance, $old_instance) {
         $instance=$old_instance;
        /* Strip tags (if needed) and update the widget settings. */
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['fedcms_slider'] = $new_instance['fedcms_slider'];
        return $instance;
    }

    function form($instance) { ?>
    
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title"); ?>:</label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:95%;" /></p>
        <p><label><?php _e("Slider Name"); ?>:</label>  <br />  
            <select id="<?php echo $this->get_field_name('fedcms_slider'); ?>" name="<?php echo $this->get_field_name('fedcms_slider'); ?>">
            <?php
             global $wpdb;$num=1;
    $table_name = $wpdb->prefix . "fedcms_slider"; 
    $fedslider_data = $wpdb->get_results("SELECT * FROM $table_name where active=1 ORDER BY id");
    foreach ($fedslider_data as $data) { 
        ?>
            <option value="<?php echo $data->option_name; ?>" <?php if ( $data->option_name == $instance['fedcms_slider'] ) echo 'selected="selected"'; ?>><?php echo $data->option_name; ?></option>
            <?php 
            }
            ?>
            </select>
            </p>

    <?php
    }
}


// LIMIT CONTENT FUNCTION
/*
function fedcms_slider_limitpost ($max_char, $more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
    $content = get_the_content($more_link_text, $stripteaser, $more_file);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $content = strip_tags($content);

   if (strlen($_GET['p']) > 0) {
      echo $content;
      echo "&nbsp;<a rel='nofollow' href='";
      the_permalink();
      echo "'>".__('Read More', 'vibe')." &rarr;</a>";
   }
   else if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
        $content = substr($content, 0, $espacio);
        $content = $content;
        echo $content;
        echo "...";
        echo "&nbsp;<a rel='nofollow' href='";
        the_permalink();
        echo "'>".$more_link_text."</a>";
   }
   else {
      echo $content;
      echo "&nbsp;<a rel='nofollow' href='";
      the_permalink();
      echo "'>".__('Read More', 'vibe')." &rarr;</a>";
   }
}
*/
?>