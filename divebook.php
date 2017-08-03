<?php
/*
Plugin Name: DiveBook
Description: Plugin with functionality for logging, editing and viewing scuba dives.
Version: 1.1.4
Author: Per Ola Saether
Author URI: http://www.breathingtech.com/about
License: GPL2
*/

//Include the setting file
include("settings.php");
//Include the widget file
include("widget.php");
//Include the database file
include("database.php");
//Include the dive input form file
include("diveinputform.php");
//Include the dive stats file
include("divestats.php");
//Include the dive log file
include("divelog.php");
//Include the view dive file
include("viewdive.php");


//Activation hook so the DB is created when plugin is activated
register_activation_hook(__FILE__,'divebook_db_create');
//Add filter to filter executable php code in content
add_filter('the_content', 'filter_php_code', 0);
//Add action to load css
add_action('wp_print_styles', 'divebook_load_css');
//Add script
add_action('wp_print_scripts', 'divebook_enqueue_js');
//I18n
add_action( 'init', 'divebook_load_languages' );

$plugin = plugin_basename(__FILE__);

function divebook_actlinks( $links ) {
 // Add a link to this plugin's settings page
 $donate_link = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GXXHSVQKYJLVA" target="blank">Donate</a>';
 array_unshift( $links, $donate_link );
 return $links;
}

add_filter("plugin_action_links_$plugin", 'divebook_actlinks' );

function divebook_display(){
	$urlWithRemovedParams = remove_url_params(null);
	dive_inputform($urlWithRemovedParams);
    divebook_stats();
    echo '<br/>';
    show_divelog($urlWithRemovedParams);
	prepare_view_dive();
	if (get_option( "divebook_show_credits" )) {
            echo '<br/>';
            show_divebook_credits();
            if (get_option("divebook_show_donate")) {
                show_divebook_donation();
            }
	}
}

function show_divebook_credits(){
    ?>
    <p id="divebook-creds">The DiveBook plugin is created by <a href="http://www.breathingtech.com/about" target="blank">Per Ola Saether</a>
	and can be downloaded from the <a href="http://wordpress.org/extend/plugins/divebook/" target="blank">WordPress Plugin Directory</a>.</p>
    <?php
}

function show_divebook_donation(){
?>
    <div style="text-align: center;">
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="GXXHSVQKYJLVA">
        <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
    </div>
<?php
}

function divebook_enqueue_js () {
	if (!is_admin()) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
		wp_enqueue_script('jquery');

		wp_deregister_script('jquery-ui');
		wp_register_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.js');
		wp_enqueue_script('jquery-ui');

		wp_enqueue_script('divebook-functions', WP_CONTENT_URL . '/plugins/divebook/script/divebookFunctions.js', array('jquery'),'1.0.3');
		wp_enqueue_script('divebook-confirm', WP_CONTENT_URL . '/plugins/divebook/script/confirm.js', array('jquery'),'1.0.1');
		wp_enqueue_script('divebook-popup', WP_CONTENT_URL . '/plugins/divebook/script/divepopup.js', array('jquery', 'jquery-ui'),'1.0.2');
	}

}

function divebook_load_css() {
	wp_enqueue_style('divebookDatepicker', WP_CONTENT_URL . '/plugins/divebook/css/jquery.ui.datepicker.css');
	wp_enqueue_style('divebookTheme', WP_CONTENT_URL . '/plugins/divebook/css/jquery.ui.theme.css');
	wp_enqueue_style('divebookStyle', WP_CONTENT_URL . '/plugins/divebook/css/divebook.css');
	wp_enqueue_style('divebookPopupStyle', WP_CONTENT_URL . '/plugins/divebook/css/inputpopup.css');
	wp_enqueue_style('divebookConfirmStyle', WP_CONTENT_URL . '/plugins/divebook/css/confirm.css');
}

function divebook_load_languages() {
	load_plugin_textdomain( 'divebook', false, 'divebook/languages' );
}

function filter_php_code($filter_content){
	$filter_content = preg_replace_callback('/\[divebook\]((.|\n)*?)\[\/divebook\]/', 'get_php_from_output_buffer', $filter_content);
	return $filter_content;
}

function get_php_from_output_buffer($matches){
    try {
        eval('ob_start();'.$matches[1].'$php_output = ob_get_contents();ob_end_clean();');
    } catch (Exception $ex) {
    }
    return $php_output;
}



function getCurrentPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function addParameterToUrl($url, $parameter){
	if (!strpos($url, chr(63))) {
		//No parameter added, use ?
		$url = $url . "?";
	}else{
		//Parameter already added, use &
		$url = $url . "&";
	}
	return $url . $parameter;
}

function remove_url_params($url)
{
	if (!empty($url)) {
		$In_url = $url;
	}
	$params = array("deletedive");
	$In_url = getCurrentPageURL();
	trim($In_url);
	$qm = chr(63); //Creates a question mark
	$L_qm_pos = strpos($In_url, $qm) + 1; // Creates a variable that holds the position of the question mark (it may be 0 if there is no question mark)
	// When searching for the parameter, make sure that it is
	// proceeded by an & or ? and followed by an = sign
	foreach($params as $param){

		$L_search_string = ("?" . $param . "=");
		$L_param_pos = strpos($In_url,$L_search_string);
		if (!empty($L_param_pos))
		{
			// Parameter is led by ?... preserve the question mark
			$L_param_pos++;
		}
		else
		{
			$L_search_string = ("&" . $param . "=");
			$L_param_pos = strpos($In_url, $L_search_string);
		}

		if (!empty($L_param_pos))
		{
			// The parameter exists in the param string so remove it
			// Find the end of the param name value pair
			for ($i = $L_param_pos + 1; $i <= strlen($In_url); $i++)
			{
				$L_character = (substr($In_url, $i, 1));
				if ($L_character == " " or $L_character == "&")
				{
					$i ++;
					break;
				}
			}

			$In_url = repair_url(substr($In_url, 0,$L_param_pos) .
			        substr($In_url, $i - 1, strlen($In_url)));
		}
	}
	return $In_url; // Return the url
}

function repair_url($In_url)
{
	$L_pos = strpos($In_url, "?&");
	if (!empty($L_pos))
	{
		// Remvove the &
		$In_url = substr($In_url, 0,$L_pos + 1) .
		        substr($In_url, $L_pos + 2, strlen($In_url));
	}

	$L_pos = strpos($In_url, "&&");
	if (!empty($L_pos))
	{
		// Remove one of the &s
		$In_url = substr($In_url, 0,$L_pos + 1) .
		        substr($In_url, $L_pos + 2, strlen($In_url));
	}

	if (strpos($In_url, "?") > 0 and (strpos($In_url, "?") == strlen($L_url)))
	{
		// Then the question mark that is trailing has no parameters, remove it
		$In_url = substr($In_url, 0, strlen($L_url - 1));
	}

	return $In_url;
}
?>