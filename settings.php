<?php
/**
 * Description: Settings page for editing plugin settings.
 * Author: Per Ola Saether
 */

add_action('admin_menu', 'divebook_menu');

if($_POST['divebook_settings_hidden'] == 'Y') {
    //Form data sent
 	$showCredits = strip_tags(stripslashes($_POST['credits']));
        $showDonate = strip_tags(stripslashes($_POST['donate']));
	$adminCanEdit = strip_tags(stripslashes($_POST['admin_edit']));
        $onlyAdmin = strip_tags(stripslashes($_POST['only_admin']));
        $onBehalf = strip_tags(stripslashes($_POST['on_behalf']));
        $showLogin = strip_tags(stripslashes($_POST['show_login']));
        $showFilter = strip_tags(stripslashes($_POST['show_filter']));
        $showPageNavigator = strip_tags(stripslashes($_POST['show_page_navigator']));
        $showRegister = strip_tags(stripslashes($_POST['show_register']));
	$divesPerPage = strip_tags(stripslashes($_POST['dives_per_page']));

        update_option('divebook_show_credits', $showCredits);
        update_option('divebook_show_donate', $showDonate);
	update_option('divebook_admin_edit', $adminCanEdit);
        update_option('divebook_only_admin', $onlyAdmin);
        update_option('divebook_on_behalf', $onBehalf);
        update_option('divebook_show_filter', $showFilter);
        update_option('divebook_show_page_navigator', $showPageNavigator);
        update_option('divebook_show_login', $showLogin);
	update_option('divebook_show_register', $showRegister);
        if (is_numeric($divesPerPage)) {
            update_option('divebook_dives_per_page', $divesPerPage);
	} ?>
        <div class="updated"><p><strong><?php _e('Settings saved.', 'divebook' ); ?></strong></p></div>
<?php }
else {
 	//Normal page display
	$showCredits = get_option('divebook_show_credits');
	if (($showCredits == null)) {
            $showCredits = true;
            add_option("divebook_show_credits", $showCredits);
        }
        $showDonate = get_option('divebook_show_donate');
	if (($showDonate == null)) {
            $showDonate = true;
            add_option("divebook_show_donate", $showDonate);
        }
	$adminCanEdit = get_option('divebook_admin_edit');
	if (($adminCanEdit == null)) {
            $adminCanEdit = false;
            add_option("divebook_admin_edit", $adminCanEdit);
	}
        $onlyAdmin = get_option('divebook_only_admin');
	if (($onlyAdmin == null)) {
            $onlyAdmin = false;
            add_option("divebook_only_admin", $onlyAdmin);
	}
        $onBehalf = get_option('divebook_on_behalf');
	if (($onBehalf == null)) {
            $onBehalf = false;
            add_option("divebook_on_behalf", $onBehalf);
	}
        $showFilter = get_option('divebook_show_filter');
	if (($showFilter == null)) {
            $showFilter = true;
            add_option("divebook_show_filter", $showFilter);
	}
        $showPageNavigator = get_option('divebook_show_page_navigator');
	if (($showPageNavigator == null)) {
            $showPageNavigator = true;
            add_option("divebook_show_page_navigator", $showPageNavigator);
	}
        $showLogin = get_option('divebook_show_login');
	if (($showLogin == null)) {
            $showLogin = true;
            add_option("divebook_show_login", $showLogin);
	}
        $showRegister = get_option('divebook_show_register');
	if (($showRegister == null)) {
            $showRegister = true;
            add_option("divebook_show_register", $showRegister);
	}
	$divesPerPage = get_option('divebook_dives_per_page');
	if (($divesPerPage == null)) {
            $divesPerPage = 25;
            add_option("divebook_dives_per_page", $divesPerPage);
	}
     }

function divebook_menu() {
  add_options_page('DiveBook Settings', 'DiveBook', 'manage_options', 'DiveBookSettings', 'divebook_settings');
}

function divebook_settings() {

    if (!current_user_can('manage_options'))  {
        wp_die( __('You do not have sufficient permissions to access this page.', 'divebook') );
    }
    //Normal page display
    ?>
    <div class="wrap">
        <?php    echo "<h2>" . __( 'DiveBook', 'divebook' ) . "</h2>"; ?>
        <form name="divebook_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
           <input type="hidden" name="divebook_settings_hidden" value="Y">
           <?php    echo "<h4>" . __( 'DiveBook General Settings', 'divebook') . "</h4>"; ?>

        <?php if (get_option('divebook_only_admin')) { ?>
                <input type="checkbox" name="only_admin" value="only_admin" checked="true"/><b> <?php _e('Only Administrator can add dives', 'divebook');?></b>
                <?php }
              else{ ?>
                <input type="checkbox" name="only_admin" value="only_admin"/><b> <?php _e('Only Administrator can add dives', 'divebook');?></b>
                <?php }?>
        <p>(<?php _e('If checked only the Adminstrator can add dives', 'divebook');?>)</p>

        <?php if (get_option('divebook_admin_edit')) { ?>
                <input type="checkbox" name="admin_edit" value="admin_edit" checked="true"/><b> <?php _e('Administrator can delete and edit all dives', 'divebook');?></b>
                <?php }
              else{ ?>
                <input type="checkbox" name="admin_edit" value="admin_edit"/><b> <?php _e('Administrator can delete and edit all dives', 'divebook');?></b>
                <?php }?>
        <p>(<?php _e('If checked the Administrator can delete and edit all dives', 'divebook');?>)</p>

        <?php if (get_option('divebook_on_behalf')) { ?>
                <input type="checkbox" name="on_behalf" value="on_behalf" checked="true"/><b> <?php _e('Administrator can add dives on behalf of other users', 'divebook');?></b>
                <?php }
              else{ ?>
                <input type="checkbox" name="on_behalf" value="on_behalf"/><b> <?php _e('Administrator can add dives on behalf of other users', 'divebook');?></b>
                <?php }?>
        <p>(<?php _e('If checked the Administrator can add dives on behalf of other users', 'divebook');?>)</p>

        <?php if (get_option('divebook_show_page_navigator')) { ?>
                <input type="checkbox" name="show_page_navigator" value="show_page_navigator" checked="true"/><b> <?php _e('Show page navigator for divelog', 'divebook');?></b>
                <?php }
              else{ ?>
                <input type="checkbox" name="show_page_navigator" value="show_page_navigator"/><b> <?php _e('Show page navigator for divelog', 'divebook');?></b>
                <?php }?>
        <p>(<?php _e('If checked page navigation in divelog will be enabled. If not all dives will be displayed on one page', 'divebook');?>)</p>

        
        <input name="dives_per_page" style="width:30px;" value="<?php echo get_option('divebook_dives_per_page');?>"/>
	<label><?php _e('dives displayed per page in divelog', 'divebook');?></label>
        <br/><br/>

        
        <?php if (get_option('divebook_show_filter')) { ?>
                <input type="checkbox" name="show_filter" value="show_filter" checked="true"/><b> <?php _e('Display filter for divelog', 'divebook');?></b>
                <?php }
              else{ ?>
                <input type="checkbox" name="show_filter" value="show_filter"/><b> <?php _e('Display filter for divelog', 'divebook');?></b>
                <?php }?>
        <p>(<?php _e('If checked the filter (diver and dive site) will be displayed', 'divebook');?>)</p>


        <?php if (get_option('divebook_show_login')) {?>
                <input type="checkbox" name="show_login" value="show_login" checked="true"/><b> <?php _e('Display login link', 'divebook');?></b>
                <?php }
            else{ ?>
                <input type="checkbox" name="show_login" value="show_login"/><b> <?php _e('Display login link', 'divebook');?></b>
                <?php }?>
        <p>(<?php _e('If checked login link will be displayed if user is not logged in', 'divebook');?>)</p>

        <?php if (get_option('divebook_show_register')) {?>
                <input type="checkbox" name="show_register" value="show_register" checked="true"/><b> <?php _e('Display register link', 'divebook');?></b>
                <?php }
            else{ ?>
                <input type="checkbox" name="show_register" value="show_register"/><b> <?php _e('Display register link', 'divebook');?></b>
                <?php }?>
        <p>(<?php _e('If checked register link will be displayed if user is not logged in', 'divebook');?>)</p>

        <?php if (get_option('divebook_show_credits')) { ?>
                <input type="checkbox" name="credits" value="credits" checked="true"/><b> <?php _e('Show credits', 'divebook');?></b>
                <?php }
              else{ ?>
                <input type="checkbox" name="credits" value="credits"/><b> <?php _e('Show credits', 'divebook');?></b>
                <?php }?>

        <?php if (get_option('divebook_show_donate')) { ?>
                <input type="checkbox" name="donate" value="donate" checked="true"/><b> <?php _e('Include donate button with credits', 'divebook');?></b>
                <?php }
                else{ ?>
                <input type="checkbox" name="donate" value="donate"/> <b><?php _e('Include donate button with credits', 'divebook');?></b>
                <?php }?>
        <p>(<?php _e('If checked credits will be displayed under the divelog', 'divebook');?>)</p>

        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Update Options', 'divebook' ) ?>" />
        </p>
       </form>
    </div>

    <div style="text-align: center;">
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="GXXHSVQKYJLVA">
        <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
        <br/>
        <?php _e( 'Inspire me to continue developing the DiveBook plugin', 'divebook');?>
        </form>
    </div>
<?php
}
?>