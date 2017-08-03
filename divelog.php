<?php
/**
 * Shows the divelog
 * Author: Per Ola Saether
 */

function show_divelog($url){
    //Get the table name with the WP database prefix
    global $wpdb;
    $table_name = $wpdb->prefix . "dive";
	$user_table_name = $wpdb->prefix . "users";

	global $totalNumberOfDives;

	$orderBy = 'date';
	$orderDirection = 'desc';
	if (isset($_GET['dive_orderby'])) {
		$order = $_GET['dive_orderby'];
		if (startsWith('date', $order)) {
			$orderBy = 'date';
		}
		else if (startsWith('display_name', $order)) {
			$orderBy = 'display_name';
		}
		else if (startsWith('divesite', $order)) {
			$orderBy = 'divesite';
		}
		else if (startsWith('max_depth', $order)) {
			$orderBy = 'max_depth';
		}
		else if (startsWith('divetime', $order)) {
			$orderBy = 'divetime';
		}

		if (endsWith('desc', $order)) {
			$orderDirection = 'desc';
		}else{
			$orderDirection = 'asc';
		}
	}

	$filterDiver;
	if (isset($_GET['filter_diver'])) {
		$filterDiver = $_GET['filter_diver'];
	}

	$filterDivesite;
	if (isset($_GET['filter_divesite'])) {
		$filterDivesite = $_GET['filter_divesite'];
                if ($filterDivesite == '0'){
                    $filterDivesite = '';
                }
	}

	$currentPage = 1;
	if (isset($_GET['divelog_page'])) {
		$currentPage = $_GET['divelog_page'];
	}
	$numberOfDivesPerPage = get_option('divebook_dives_per_page');
	$startLimit = ($currentPage -1)* $numberOfDivesPerPage;

	$sqlDives;
	$sqlTotalDives;
	if (!empty($filterDiver) && !empty($filterDivesite)) {
		$sqlDives = "SELECT " . $table_name . ".*, " . $user_table_name . ".display_name from " . $table_name . ", " . $user_table_name . " where " . $table_name . ".diver = " . $user_table_name . ".ID and diver=" . $filterDiver . " and divesite LIKE '%" . $filterDivesite . "%' order by " . $orderBy . " " . $orderDirection;

		$sqlTotalDives = "SELECT Count(id) from " . $table_name . " where diver=" . $filterDiver . " and divesite LIKE '%" . $filterDivesite . "%';";
	}
	else if (!empty($filterDiver)) {
		$sqlDives = "SELECT " . $table_name . ".*, " . $user_table_name . ".display_name from " . $table_name . ", " . $user_table_name . " where " . $table_name . ".diver = " . $user_table_name . ".ID and diver=" . $filterDiver . " order by " . $orderBy . " " . $orderDirection;

		$sqlTotalDives = "SELECT Count(id) from " . $table_name . " where diver=" . $filterDiver . ";";
	}
	else if (!empty($filterDivesite)) {
		$sqlDives = "SELECT " . $table_name . ".*, " . $user_table_name . ".display_name from " . $table_name . ", " . $user_table_name . " where " . $table_name . ".diver = " . $user_table_name . ".ID and divesite LIKE '%" . $filterDivesite . "%' order by " . $orderBy . " " . $orderDirection;

		$sqlTotalDives = "SELECT Count(id) from " . $table_name . " where divesite LIKE '%" . $filterDivesite . "%';";
	}
	else{
		$sqlDives = "SELECT " . $table_name . ".*, " . $user_table_name . ".display_name from " . $table_name . ", " . $user_table_name . " where " . $table_name . ".diver = " . $user_table_name . ".ID order by " . $orderBy . " " . $orderDirection;
		$sqlTotalDives = "SELECT Count(id) from " . $table_name . ";";
	}
        
        if (get_option('divebook_show_page_navigator')){
            $sqlDives = $sqlDives . " limit " . $startLimit . ", " . $numberOfDivesPerPage . ";";
        }else $sqlDives = $sqlDives . ";";

        $dives = $wpdb->get_results($sqlDives);
	$numberOfDives = $wpdb->get_var($sqlTotalDives);

	$sqlDivers = "SELECT DISTINCT " . $table_name . ".diver, " . $user_table_name . ".display_name from " . $table_name . ", " . $user_table_name . " where " . $table_name . ".diver = " . $user_table_name . ".ID order by " . $user_table_name . ".display_name asc;";
	$divers = $wpdb->get_results($sqlDivers);


	$numberOfPages = 1;
	if ($numberOfDives != 0) {
		$numberOfPages = intval($numberOfDives/$numberOfDivesPerPage);
		if ($numberOfDives%$numberOfDivesPerPage != 0) {
			$numberOfPages ++;
		}
	}

    global $current_user;
    get_currentuserinfo();
    $oddTableRow = true;
     
    if (get_option('divebook_show_filter')){
        displayFilter($divers, $filterDiver, $filterDivesite, $url);
    }
    if (get_option('divebook_show_page_navigator')){
       displayPageBrowser($numberOfDives, $totalNumberOfDives, $currentPage, $numberOfPages, $url);
    }
    ?>
     
    <div id="divelog">
        <table>
            <thead>
                <tr>
                    <th scope="col" id="divelog_orderby" onclick="javascript:orderDives('<?php if ($orderBy != 'date' || $orderDirection == 'asc') echo $url . '\',' . '\'date_desc'; else echo $url . '\',' . '\'date_asc';?>');">
                    	<img src="<?php if ($orderDirection == 'desc') echo WP_CONTENT_URL . '/plugins/divebook/images/sort-descend.png'; else echo WP_CONTENT_URL . '/plugins/divebook/images/sort-ascend.png';?>" <?php if ($orderBy != 'date') echo 'style="display:none;"';?> height="20" width="20"/>
                    	<span id="divelog_orderby_text"><?php _e('Date', 'divebook');?></span>
                    </th>
                    <th scope="col" id="divelog_orderby" onclick="javascript:orderDives('<?php if ($orderBy != 'display_name' || $orderDirection == 'desc') echo $url . '\',' . '\'display_name_asc'; else echo $url . '\',' . '\'display_name_desc';?>');">
                    	<img src="<?php if ($orderDirection == 'desc') echo WP_CONTENT_URL . '/plugins/divebook/images/sort-descend.png'; else echo WP_CONTENT_URL . '/plugins/divebook/images/sort-ascend.png';?>" <?php if ($orderBy != 'display_name') echo 'style="display:none;"';?> height="20" width="20"/>
                    	<span id="divelog_orderby_text"><?php _e('Diver', 'divebook');?></span>
                    </th>
                    <th scope="col" id="divelog_orderby" onclick="javascript:orderDives('<?php if ($orderBy != 'divesite' || $orderDirection == 'desc') echo $url . '\',' . '\'divesite_asc'; else echo $url . '\',' . '\'divesite_desc';?>');">
                    	<img src="<?php if ($orderDirection == 'desc') echo WP_CONTENT_URL . '/plugins/divebook/images/sort-descend.png'; else echo WP_CONTENT_URL . '/plugins/divebook/images/sort-ascend.png';?>" <?php if ($orderBy != 'divesite') echo 'style="display:none;"';?> height="20" width="20"/>
                    	<span id="divelog_orderby_text"><?php _e('Dive site', 'divebook');?></span>
                    </th>
                    <th scope="col" id="divelog_orderby" onclick="javascript:orderDives('<?php if ($orderBy != 'max_depth' || $orderDirection == 'desc') echo $url . '\',' . '\'max_depth_asc'; else echo $url . '\',' . '\'max_depth_desc';?>');">
                    	<img src="<?php if ($orderDirection == 'desc') echo WP_CONTENT_URL . '/plugins/divebook/images/sort-descend.png'; else echo WP_CONTENT_URL . '/plugins/divebook/images/sort-ascend.png';?>" <?php if ($orderBy != 'max_depth') echo 'style="display:none;"';?> height="20" width="20"/>
                    	<span id="divelog_orderby_text"><?php _e('Depth', 'divebook');?></span>
                    </th>
                    <th scope="col" id="divelog_orderby" onclick="javascript:orderDives('<?php if ($orderBy != 'divetime' || $orderDirection == 'desc') echo $url . '\',' . '\'divetime_asc'; else echo $url . '\',' . '\'divetime_desc';?>');">
                    	<img src="<?php if ($orderDirection == 'desc') echo WP_CONTENT_URL . '/plugins/divebook/images/sort-descend.png'; else echo WP_CONTENT_URL . '/plugins/divebook/images/sort-ascend.png';?>" <?php if ($orderBy != 'divetime') echo 'style="display:none;"';?> height="20" width="20"/>
                    	<span id="divelog_orderby_text"><?php _e('Dive time', 'divebook');?></span>
                    </th>
                    <th scope="col"><br/><?php _e('Action', 'divebook');?></th>
                </tr>
            </thead>
            <tbody>
            <script language="javascript" type="text/javascript">
            var divesForScript = [];
            </script>
<?php $diveIndex = 0;
                 foreach ($dives as $dive){
                 if (!$oddTableRow){
                     $oddTableRow = true;
                     ?><tr><?php
                 }
                 else{
                     $oddTableRow = false;
                     ?><tr class="odd"><?php
                 }
                ?>
                    <td><?php echo $dive->date ?></td>
                    <td><?php echo get_userdata($dive->diver)->display_name ?></td>
                    <td><?php echo $dive->divesite ?></td>
                    <td><?php echo $dive->max_depth . ' meters'?></td>
                    <td><?php echo $dive->divetime . ' minutes' ?></td><script language="javascript" type="text/javascript" charset="utf-8">
            divesForScript[<?php echo $diveIndex;?>] = [];
            divesForScript[<?php echo $diveIndex;?>][0] = <?php echo $dive->id;?>;
            divesForScript[<?php echo $diveIndex;?>][1] = <?php echo $dive->diver;?>;
            divesForScript[<?php echo $diveIndex;?>][2] = "<?php echo get_userdata($dive->diver)->display_name;?>";
            divesForScript[<?php echo $diveIndex;?>][3] = "<?php echo washString($dive->date);?>";
            divesForScript[<?php echo $diveIndex;?>][4] = "<?php echo washString($dive->divesite);?>";
            divesForScript[<?php echo $diveIndex;?>][5] = "<?php echo washString($dive->divebuddies);?>";
            divesForScript[<?php echo $diveIndex;?>][6] = <?php echo $dive->max_depth;?>;
            divesForScript[<?php echo $diveIndex;?>][7] = <?php echo $dive->divetime;?>;
            divesForScript[<?php echo $diveIndex;?>][8] = "<?php echo washString($dive->gps_position);?>";
            divesForScript[<?php echo $diveIndex;?>][9] = <?php echo $dive->visibility;?>;
            divesForScript[<?php echo $diveIndex;?>][10] = "<?php echo getVisibilityText($dive->visibility);?>";
            divesForScript[<?php echo $diveIndex;?>][11] = "<?php echo $dive->visibility_meters;?>";
            divesForScript[<?php echo $diveIndex;?>][12] = "<?php echo washString($dive->description);?>";
            divesForScript[<?php echo $diveIndex;?>][13] = "<?php echo $dive->water_temperature;?>";
        </script><td> <a id="dive" onclick="javascript:viewDive(divesForScript[<?php echo $diveIndex;?>]);"><?php _e('View', 'divebook');?></a>
                    <?php if (($dive->diver == $current_user->ID && !get_option('divebook_only_admin')) || (current_user_can('manage_options') && (get_option('divebook_admin_edit') || get_option('divebook_only_admin')))){ ?>
                        <a id="dive" onclick="javascript:editDive(divesForScript[<?php echo $diveIndex;?>]);"><?php _e('Edit', 'divebook');?></a>
                        <a id="dive" onclick="javascript:deleteDive('<?php echo addParameterToUrl($url, 'deletedive=' . $dive->id);?>');"><?php _e('Delete', 'divebook');?></a>
                    <?php } ?>
                    </td>
                </tr>
                <?php 
                $diveIndex++;
                }
                $oddTableRow = true; ?>
        </tbody>
    </table>
</div>

<?php
if (get_option('divebook_show_page_navigator')){
   echo displayPageBrowser($numberOfDives, $totalNumberOfDives, $currentPage, $numberOfPages, $url);
}
?>


<?php }

function displayFilter($divers, $filterDiver, $filterDivesite, $url){
    ?>
     <div id="divelog-filter">
         <table>
             <thead>
             <tr>
                 <th id="divelog-filter-header"><?php _e('Filter dives', 'divebook');?></th>
                 <th></th>
             </tr>
             </thead>
             <tbody>
            <tr>
            	<td style="width:50%; padding:5px 15px 0px;">
                    <label><?php _e('Diver', 'divebook');?></label><select name="filter-diver" id="filter-diver">
                            <option value="0" selected="selected"><?php _e('All divers', 'divebook');?></option>
                                <?php 	foreach($divers as $diver){?>
                                        <option value="<?php echo $diver->diver;?>" <?php if (!empty($filterDiver) && $diver->diver == $filterDiver) echo 'selected="selected"';?>><?php echo get_userdata($diver->diver)->display_name;?></option>
                                <?php }?>
                        </select>
                </td>
                <td style="width:50%; padding:5px 15px 0px;">
                    <label><?php _e('Dive site', 'divebook');?></label><input type="text" name="filter-divesite" value="<?php if (!empty($filterDivesite)) echo $filterDivesite;?>"/>
                </td>
            </tr>
            </tbody>
          </table>
          <table>
            <tr>
                <td style="padding:5px 15px 0px;">
                    <div id="confirmButtons">
                        <a class="button blue" onclick="javascript:filterDives('<?php echo $url;?>');"><?php _e('Filter', 'divebook');?><span></span></a>
                    </div>
                </td>
            </tr>
          </table>
     </div>

    <?php
}

function displayPageBrowser($numberOfDives, $totalNumberOfDives, $currentPage, $numberOfPages, $url){
    ?>
    <div id="pagebrowser">
    <table>
        <tr>
            <td style="text-align:left; width:40%;">
                <span id="displayed-dives"><?php printf(_n('Displaying %1$d out of %2$d dive', 'Displaying %1$d out of %2$d dives', $totalNumberOfDives, 'divebook'), $numberOfDives, $totalNumberOfDives);?></span>
            </td>
            <td style="text-align:right; width:10%;">
                <span id="page_first"  onclick="javascript:changePage('<?php echo $url;?>', 1)" <?php if ($currentPage == 1) echo 'style="display:none"';?>><?php _e('First', 'divebook');?></span>
            </td>
            <td style="text-align:left; width:10%;">
                <span id="page_prev" onclick="javascript:changePage('<?php echo $url;?>', <?php echo $currentPage -1;?>)" <?php if ($currentPage == 1) echo 'style="display:none"';?>><?php _e('Prev', 'divebook');?></span>
            </td>
            <td style="text-align:center; width:20%;">
                <span id="page_number"><?php printf(__('Page %1$d of %2$d'), $currentPage, $numberOfPages);?></span>
            </td>
            <td style="text-align:right; width:10%;">
                <span id="page_next" onclick="javascript:changePage('<?php echo $url;?>', <?php echo $currentPage +1;?>)" <?php if ($currentPage == $numberOfPages) echo 'style="display:none"';?>><?php _e('Next', 'divebook');?></span>
            </td>
            <td style="text-align:left; width:10%;">
                <span id="page_last" onclick="javascript:changePage('<?php echo $url;?>', <?php echo $numberOfPages;?>)" <?php if ($currentPage == $numberOfPages) echo 'style="display:none"';?>><?php _e('Last', 'divebook');?></span>
            </td>
        </tr>
    </table>
    </div>

    <?php
}

function getVisibilityText($visibilityCode){
	if (!empty($visibilityCode)) {
		if ($visibilityCode == 0) {
			return _e('Not selected', 'divebook');
		}else if ($visibilityCode == 1) {
			return _e('Amazing', 'divebook');
		}else if ($visibilityCode == 2) {
			return _e('Great', 'divebook');
		}else if ($visibilityCode == 3) {
			return _e('Good', 'divebook');
		}else if ($visibilityCode == 4) {
			return _e('OK', 'divebook');
		}else if ($visibilityCode == 5) {
			return _e('Bad', 'divebook');
		}else if ($visibilityCode == 6) {
			return _e('Could not see a thing', 'divebook');
		}
	}
}

function washString($string){
    $string = preg_replace("/\r?\n/", "\\n", addslashes($string));
    $string = htmlspecialchars($string, ENT_NOQUOTES);
    return $string;
}

function startsWith($needle, $haystack)
{
	return preg_match('/^'.preg_quote($needle)."/", $haystack);
}

function endsWith($needle, $haystack)
{
	return preg_match("/".preg_quote($needle) .'$/', $haystack);
}


?>