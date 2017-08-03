<?php
/**
 * Description: Creates database tables used by DiveBook
 * Author: Per Ola Saether
 */

//Create database tables needed by the DiveBook widget
function divebook_db_create () {
    divebook_create_table_dive();
}

//Create dive table
function divebook_create_table_dive(){
    //Get the table name with the WP database prefix
    global $wpdb;
    $table_name = $wpdb->prefix . "dive";
	//Database table versions
	global $divebook_db_table_dive_version;
	$divebook_db_table_dive_version = "1.5";
    $installed_ver = get_option( "divebook_db_table_dive_version" );
     //Check if the table already exists and if the table is up to date, if not create it
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name
            ||  $installed_ver != $divebook_db_table_dive_version ) {
        $sql = "CREATE TABLE " . $table_name . " (
              id mediumint(9) NOT NULL AUTO_INCREMENT,
              diver mediumint(9) NOT NULL,
              date date NOT NULL,
              divesite tinytext NOT NULL,
              divebuddies tinytext,
              max_depth smallint(3) UNSIGNED NOT NULL,
              divetime smallint(3) UNSIGNED NOT NULL,
              water_temperature tinytext,
              visibility smallint(1) UNSIGNED,
              visibility_meters tinytext,
              description text,
              gps_position tinytext,
              UNIQUE KEY id (id)
            );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        update_option( "divebook_db_table_dive_version", $divebook_db_table_dive_version );
	}

    //Add database table versions to options
    add_option("divebook_db_table_dive_version", $divebook_db_table_dive_version);
}
?>