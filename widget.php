<?php
/**
 * Description: Widget page for the plugin. Widget will display most recent logged dives.
 * Author: Per Ola Saether
 */

//Initialize widget
function divebook_init() {

    if (!function_exists('register_sidebar_widget'))
        return;

    // Widget sidebar layout
    function divebook_sidebar($args) {

        extract($args);
        // Get stored widget options
        $options = get_option('divebook');
        $title = $options['title'];
        $numdives = $options['numdives'];
    	$link = $options['divebooklink'];
//        global $current_user;
        get_currentuserinfo();

        //Get the table name with the WP database prefix
        global $wpdb;
        $table_name = $wpdb->prefix . "dive";

        $sql = "SELECT diver, date, divesite, max_depth, divetime FROM " . $table_name . " order by id desc limit " . $numdives . ";";
        $results = $wpdb->get_results($sql);
        //Sidebar widget layout
        echo $before_widget . $before_title . $title . $after_title;

    	if (!empty($link)) {
    		echo '<a href="';
    		echo $link;
			echo '">';
			_e('Go to DiveBook', 'divebook');
			echo '</a><br/>';
    	}
        foreach ($results as $result){
        echo $result->date;
        echo '<br/>' . __('Diver: ', 'divebook');
        echo get_userdata($result->diver)->display_name;
        echo '<br/>' . __('Site: ', 'divebook');
        echo $result->divesite;
        echo '<br/>';
        echo $result->max_depth;
        _e(' meters  ', 'divebook');
        echo $result->divetime;
        _e(' minutes', 'divebook');
        echo '<hr/>';
        }
        echo $after_widget;
    }


    // Control form where the user can change settings
    // for the widget
    function divebook_control() {

        // Get stored options
        $options = get_option('divebook');
        //Default options
        if (!is_array($options))
            $options = array('numdives' => 5, 'title' => 'DiveBook', 'divebooklink' => '');

        //Store title and numdives in options table
        if ($_POST['divebook-submit']) {
            $options['title'] = strip_tags(stripslashes($_POST['divebook-title']));
            $options['numdives'] = strip_tags(stripslashes($_POST['divebook-numdives']));
            $options['divebooklink'] = strip_tags(stripslashes($_POST['divebook-link']));
            update_option('divebook', $options);
        }
        // Format options to be valid HTML
        $title = htmlspecialchars($options['title'], ENT_QUOTES);
        $numdives = htmlspecialchars($options['numdives'], ENT_QUOTES);
    	$link = htmlspecialchars($options['divebooklink'], ENT_QUOTES);

        //Control form layout
        echo '<p style="text-align:left;">
        <label for="divebook-title"> ' . __('Title:', 'divebook') . '
        <input style="width: 200px;" id="divebook-title" name="divebook-title" type="text" value="' . $title . '" />
        </label>
        <label for="divebook-numdives"> ' . __('Number of dives to display:', 'divebook') . '
        <br/><input style="width: 30px;" id="divebook-numdives" name="divebook-numdives" type="text" value="' . $numdives . '" />
        </label>
        <br/><label for="divebook-link"> ' . __('Link to DiveBook:', 'divebook') . '
        <input style="width: 200px;" id="divebook-link" name="divebook-link" type="text" value="' . $link . '" />
        </label>
        </p>';
        echo '<input type="hidden" id="divebook-submit" name="divebook-submit" value="1" />';
    }

    //Register Widget so it appears in available widgets
    register_sidebar_widget(array('DiveBook', 'DiveBook'), 'divebook_sidebar');
    register_widget_control(array('DiveBook', 'DiveBook'), 'divebook_control');
}

add_action('widgets_init', 'divebook_init');

?>
