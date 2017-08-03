<?php

/**
 * Description: Page for viewing dive details.
 * Author: Per Ola Saether
 */

function prepare_view_dive(){
?>

     <div id="viewdive" style="display: none;">
         <table>
             <thead>
             <tr>
                 <th id="viewdive-header"><?php _e('View dive', 'divebook');?></th>
                 <th></th>
             </tr>
             </thead>
             <tbody>
            <tr>
                <td>
                    <label><?php _e('Diver', 'divebook');?></label><input type="text" name="viewdive-diver" readonly="readonly"/>
                </td>
                <td>
                    <label><?php _e('Date (YYYY-MM-DD)', 'divebook');?></label><input type="text" name="viewdive-date" readonly="readonly"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label><?php _e('Dive site', 'divebook');?></label><input type="text" name="viewdive-divesite" readonly="readonly"/>
                </td>
                <td>
                    <label><?php _e('GPS position', 'divebook');?></label><input type="text" name="viewdive-gps_position" readonly="readonly"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label><?php _e('Max depth (in meters)', 'divebook');?></label><input type="text" name="viewdive-maxdepth" readonly="readonly"/>
                </td>
                <td>
                    <label><?php _e('Dive time (in minutes)', 'divebook');?></label><input type="text" name="viewdive-divetime" readonly="readonly"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label><?php _e('Water temperature (in Celsius)', 'divebook');?></label><input type="text" name="viewdive-watertemperature" readonly="readonly"/>
                </td>
                <td>
                	<label><?php _e('Dive buddies', 'divebook');?></label><input type="text" name="viewdive-divebuddies" readonly="readonly"/>
                </td>
            </tr>
            <tr>
            	<td>
            		<label><?php _e('Visibility (by scale)', 'divebook');?></label><input type="text" name="viewdive-visibility" readonly="readonly"/>
				</td>
				<td>
            		<label><?php _e('Visibility (in meters)', 'divebook');?></label><input type="text" name="viewdive-visibility_meters" readonly="readonly"/>
				</td>
			</tr>
             </tbody>
         </table>

         <table>
             <tbody>
            <tr>
                <td>
                    <label><?php _e('Description', 'divebook');?></label><textarea rows="5" cols="75" name="viewdive-description"  readonly="readonly"></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <div id="confirmButtons">
			    	<a class="button gray" onclick="javascript:disableViewDive();"><?php _e('Close', 'divebook');?><span></span></a>
					</div>
                </td>
            </tr>
             </tbody>
         </table>
     </div>
     <br/>
    <div id="background-viewdive" style="display: none;"></div>

<?php
}


?>