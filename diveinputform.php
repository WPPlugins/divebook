<?php

/**
 * Description: Input form for new dives and edit
 * existing dives.
 * Author: Per Ola Saether
 */
function dive_inputform($url) {
    if (is_user_logged_in()) {

        if (!get_option('divebook_only_admin') || current_user_can('manage_options')){
            //Show dive input form
            show_log_dive($url);

            if (isset($_GET['deletedive'])) {
                $dive = getDive($_GET['deletedive']);
                if ($current_user->ID == $dive->diverID || (get_option('divebook_admin_edit') && current_user_can('manage_options'))) {
                    deleteDiveById($_GET['deletedive']);
                }
            }
        }
    }
    else {
        //Show register/login info
        show_register_info();
    }
}

function getDive($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "dive";
    $sqlDiveById = "SELECT * from " . $table_name . " where id=" . $id . ";";
    $dive = $wpdb->get_row($sqlDiveById);
    return $dive;
}

function deleteDiveById($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "dive";
    $sqlDeleteDiveById = "delete from " . $table_name . " where id=" . $id . ";";
    $wpdb->query($sqlDeleteDiveById);
}

function show_register_info() {
    if (get_option('divebook_show_login') && !get_option('divebook_only_admin')){ ?>
     <p id="divebook"><?php _e('You must ', 'divebook'); ?><a href="<?php echo wp_login_url(getCurrentPageURL()); ?>"><?php _e('log in', 'divebook'); ?></a><?php _e(' to be able to log dives.', 'divebook'); ?></p>    
    <?php }
    if (get_option('divebook_show_register') && !get_option('divebook_only_admin')){ ?>
     <p id="divebook"><?php _e('It is easy to ', 'divebook'); ?><a href="<?php bloginfo('url'); ?>/wp-register.php"><?php _e('register', 'divebook'); ?></a><?php _e(' as a user.', 'divebook'); ?></p>
    <?php }
}

if ($_POST['divebookform_hidden'] == 'Log Dive') {
    //Form data sent
    $diverID = strip_tags(stripslashes($_POST['diverID']));
    $date = strip_tags(stripslashes($_POST['datehidden']));
    $divesite = strip_tags(stripslashes($_POST['divesite']));
    $divebuddies = strip_tags(stripslashes($_POST['divebuddies']));
    $maxdepth = strip_tags(stripslashes($_POST['maxdepth']));
    $divetime = strip_tags(stripslashes($_POST['divetime']));
    $description = strip_tags(stripslashes($_POST['description']));
    $watertemperature = strip_tags(stripslashes($_POST['watertemperature']));
    $visibility = strip_tags(stripslashes($_POST['visibility']));
    $visibility_meters = strip_tags(stripslashes($_POST['visibility_meters']));
    $gps_position = strip_tags(stripslashes($_POST['gps_position']));

    //Get the table name with the WP database prefix
    global $wpdb;
    $table_name = $wpdb->prefix . "dive";

    $wpdb->query($wpdb->prepare("INSERT INTO " . $table_name . "( diver, date, divesite, divebuddies, max_depth, divetime, description, water_temperature, visibility, gps_position, visibility_meters)
    Values ( %d, %s, %s, %s, %d, %d, %s, %s, %d, %s, %s)", $diverID, $date, $divesite, $divebuddies, $maxdepth, $divetime, $description, $watertemperature, $visibility, $gps_position, $visibility_meters));
}

if ($_POST['divebookform_hidden'] == 'Edit Dive') {
    //Form data sent
    $diveId = strip_tags(stripslashes($_POST['diveID']));
    $diverID = strip_tags(stripslashes($_POST['diverID']));
    $date = strip_tags(stripslashes($_POST['datehidden']));
    $divesite = strip_tags(stripslashes($_POST['divesite']));
    $divebuddies = strip_tags(stripslashes($_POST['divebuddies']));
    $maxdepth = strip_tags(stripslashes($_POST['maxdepth']));
    $divetime = strip_tags(stripslashes($_POST['divetime']));
    $description = strip_tags(stripslashes($_POST['description']));
    $watertemperature = strip_tags(stripslashes($_POST['watertemperature']));
    $visibility = strip_tags(stripslashes($_POST['visibility']));
    $visibility_meters = strip_tags(stripslashes($_POST['visibility_meters']));
    $gps_position = strip_tags(stripslashes($_POST['gps_position']));

    //Get the table name with the WP database prefix
    global $wpdb;
    $table_name = $wpdb->prefix . "dive";

    $sql = "UPDATE " . $table_name . " SET date='" . $date . "', divesite='" . $divesite . "',
    diver=" . $diverID . ", divebuddies='" . $divebuddies . "', max_depth=" . $maxdepth . ", divetime=" . $divetime . ",
    gps_position='" . $gps_position . "', description='" . $description . "', water_temperature='" . $watertemperature . "',
 	visibility=" . $visibility . ", visibility_meters='" . $visibility_meters . "' WHERE id=" . $diveId . ";";

    $wpdb->query($sql);
}

function getDivers(){
    global $wpdb;
    $user_table_name = $wpdb->prefix . "users";
    $sqlDivers = "SELECT ID, display_name from " . $user_table_name . " order by display_name asc;";
    return $divers = $wpdb->get_results($sqlDivers);
}

function show_log_dive($url) {
    global $current_user;
    get_currentuserinfo();
    $divers = getDivers();
?>
    <div id="confirmButtons" style="text-align: left;">
        <a class="button blue" onclick="javascript:loadPopup();" style="padding: 0px 30px 0px 35px; font-size: 18px/33px;"><?php _e('Add a new dive', 'divebook'); ?><span></span></a>
    </div>
    <div id="divebook-input" style="display: none;">
        <form name="diveinputform" method="post">
            <input type="hidden" name="divebookform_hidden" value="Log Dive"/>
            <table>
                <thead>
                    <tr>
                        <th id="divebook-header"><?php _e('Log a Dive', 'divebook'); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <label><?php _e('Diver *', 'divebook'); ?></label>
                            <?php if (get_option('divebook_on_behalf') && current_user_can('manage_options')){?>
                            <select class="diver_on_behalf" name="diver_on_behalf">
                                <?php 	foreach($divers as $diver){?>
                                        <option value="<?php echo $diver->ID;?>" <?php if ($diver->ID == $current_user->ID) echo 'selected="selected"';?>><?php echo $diver->display_name;?></option>
                                <?php }?>
                            </select>
                            <?php }
                            else {?>
                            <input type="text" name="diver" readonly="readonly" value="<?php echo $current_user->display_name; ?>"/>
                            <?php }?>
                             <input type="hidden" name="diverID" value="<?php echo $current_user->ID; ?>"/>
                            <input type="hidden" name="diveID"/>
                        </td>
                        <td>
                            <label><?php _e('Date *', 'divebook'); ?></label><input type="text" name="date" id="date" readonly="readonly"/>
                            <input type="hidden" name="datehidden" id="datehidden"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php _e('Dive site *', 'divebook'); ?></label><input type="text" name="divesite" id="divesite"/>
                        </td>
                        <td>
                            <label><?php _e('GPS position', 'divebook'); ?></label><input type="text" name="gps_position"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php _e('Max depth (in meters) *', 'divebook'); ?></label><input type="text" name="maxdepth" maxlength="3" id="maxdepth"/>
                        </td>
                        <td>
                            <label><?php _e('Dive time (in minutes) *', 'divebook'); ?></label><input type="text" name="divetime" maxlength="3" id="divetime"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php _e('Water temperature (in Celsius)', 'divebook'); ?></label><input type="text" name="watertemperature" maxlength="3" id="watertemperature"/>
                        </td>
                        <td>
                            <label><?php _e('Dive buddies', 'divebook'); ?></label><input type="text" name="divebuddies"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php _e('Visibility (by scale)', 'divebook'); ?></label><select name="visibility" id="visibility">
                                <option value="0" selected="true"><?php _e('Not selected', 'divebook'); ?></option>
                                <option value="1"><?php _e('Amazing', 'divebook'); ?></option>
                                <option value="2"><?php _e('Great', 'divebook'); ?></option>
                                <option value="3"><?php _e('Good', 'divebook'); ?></option>
                                <option value="4"><?php _e('OK', 'divebook'); ?></option>
                                <option value="5"><?php _e('Bad', 'divebook'); ?></option>
                                <option value="6"><?php _e('Could not see a thing', 'divebook'); ?></option>
                            </select>
                        </td>
                        <td>
                            <label><?php _e('Visibility (in meters)', 'divebook'); ?></label><input type="text" name="visibility_meters" maxlength="4" id="visibility_meters"/>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table>
                <tbody>
                    <tr>
                        <td>
                            <label><?php _e('Description', 'divebook'); ?></label><textarea id="description" rows="5" cols="75" name="description"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div id="confirmButtons">
                                <a class="button blue" id="addDive" onclick="javascript:this.blur(); setFormActionAndSubmit('<?php echo $url ?>');"><?php _e('Log Dive', 'divebook'); ?><span></span></a><a class="button blue" id="editDive" style="display:none;" onclick="javascript:this.blur(); setFormActionAndSubmit('<?php echo $url ?>');"><?php _e('Edit dive', 'divebook'); ?><span></span></a><a class="button gray" onclick="javascript:disablePopup();"><?php _e('Cancel', 'divebook'); ?><span></span></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    <div id="backgroundPopup" style="display: none;"></div>
<?php
}