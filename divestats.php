<?php
/**
 * Displays statistics for DiveBook.
 * Author: Per Ola Saether
 */

function divebook_stats(){
    //Get the table name with the WP database prefix
    global $wpdb;
    $table_name = $wpdb->prefix . "dive";

    $sqlDives = "SELECT count(id) FROM " . $table_name . ";";
    global $totalNumberOfDives;
	$totalNumberOfDives = $wpdb->get_var($sqlDives);

    $sqlDivers = "SELECT count(distinct diver) FROM " . $table_name . ";";
    $numberOfDivers = $wpdb->get_var($sqlDivers);

    $sqlMeters = "SELECT SUM(max_depth) FROM " . $table_name . ";";
    $totalMeters = $wpdb->get_var($sqlMeters);
	if ($totalMeters == 0) {
		$averageMeters = 0;
	}else{
		$averageMeters = $totalMeters / $totalNumberOfDives;
	}

    $sqlMinutes = "SELECT SUM(divetime) FROM " . $table_name . ";";
    $totalMinutes = $wpdb->get_var($sqlMinutes);
    if ($totalMinutes == 0) {
    	$averageMinutes = 0;
    }else {
    	$averageMinutes = $totalMinutes / $totalNumberOfDives;
    }

    ?>
    <div id="divelog">
        <table>
            <thead>
                <tr>
                    <th><?php _e('DiveBook Statistics', 'divebook');?></th>
                    <th><?php _e('Facts', 'divebook');?></th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd">
                    <td><?php _e('Total number of dives', 'divebook');?></td>
                    <td><?php echo $totalNumberOfDives; ?></td>
                </tr>
                <tr>
                    <td><?php _e('Total number of divers', 'divebook');?></td>
                    <td><?php echo $numberOfDivers; ?></td>
                </tr>
                <tr class="odd">
                    <td><?php _e('Average max depth', 'divebook');?></td>
                    <td><?php echo round($averageMeters,0) . __(' meters', 'divebook');?></td>
                </tr>
                <tr>
                    <td><?php _e('Average dive time', 'divebook');?></td>
                    <td><?php echo round($averageMinutes,0). __(' minutes', 'divebook');?></td>
                </tr>
            </tbody>
        </table>
    </div>
<?php
}
?>