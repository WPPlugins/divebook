/**
 * jQuery popup script
 * Author: Per Ola Saether
 */

//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;
var viewDivePopupStatus = 0;

//loading popup with jQuery magic!
function loadPopup(){
    //loads popup only if it is disabled
    if(popupStatus==0){
    centerPopup();

    jQuery("#backgroundPopup").css({
    "opacity": "0.7"
    });
    jQuery("#backgroundPopup").fadeIn("slow");
    jQuery("#divebook-input").fadeIn("slow");
    popupStatus = 1;
    }
}

//loading popup with jQuery magic!
function loadViewDive(){
    //loads popup only if it is disabled
    if(viewDivePopupStatus==0){
    centerViewDive();

	jQuery("#background-viewdive").css({
    "opacity": "0.7"
    });
    jQuery("#background-viewdive").fadeIn("slow");
    jQuery("#viewdive").fadeIn("slow");
    viewDivePopupStatus = 1;
    }
}

//disabling popup with jQuery magic!
function disablePopup(){
    //disables popup only if it is enabled
    if(popupStatus==1){
    jQuery("#backgroundPopup").fadeOut("slow");
    jQuery("#divebook-input").fadeOut("slow");
    popupStatus = 0;
    resetForm();
    }
}

//disabling popup with jQuery magic!
function disableViewDive(){
    //disables popup only if it is enabled
    if(viewDivePopupStatus==1){
    jQuery("#background-viewdive").fadeOut("slow");
    jQuery("#viewdive").fadeOut("slow");
    viewDivePopupStatus = 0;
    }
}

//centering popup
function centerPopup(){
    //request data for centering
	var windowHeight = jQuery(window).height();
	var popupHeight = jQuery("#divebook-input").height();
    //centering
    jQuery("#divebook-input").css({
    "position": "fixed",
    "top": ((windowHeight/2) - (popupHeight/2))
    });
    //only need force for IE6

    jQuery("#backgroundPopup").css({
    "height": windowHeight
    });
}

//centering popup
function centerViewDive(){
    //request data for centering
	var windowHeight = jQuery(window).height();
    var popupHeight = jQuery("#viewdive").height();
    //centering
    jQuery("#viewdive").css({
    "position": "fixed",
	"top": ((windowHeight/2) - (popupHeight/2))
    });
    //only need force for IE6

    jQuery("#background-viewdive").css({
    "height": windowHeight
    });
}



jQuery(document).ready(function(){

    jQuery("#backgroundPopup").hide();
    jQuery("#divebook-input").hide();

    jQuery("#background-viewdive").hide();
    jQuery("#viewdive").hide();

    if (getParameterFromUrl('scrolled') != -1) {
    	jQuery(window).scrollTop(getParameterFromUrl('scrolled'));
    }

    if (jQuery('.diver_on_behalf') != null){
        jQuery('.diver_on_behalf').change(function() {
            jQuery("input[name=diverID]").val(jQuery('.diver_on_behalf').val());
        });
    }

	jQuery(function() {
		jQuery( "#date" ).datepicker({
		dateFormat: "DD, d MM, yy",
		altFormat: "yy-mm-dd",
		altField: "#datehidden",
		firstDay: 1,
		nextText: "",
		prevText: "",
		showButtonPanel: true
		});

	});

        jQuery("ui-datepicker-div").hide();
      
    //LOADING INPUT POPUP
    //Click the button event!
    jQuery("#divebook-add-button").click(function(){
    //load popup
        loadPopup();
        });
    //CLOSING POPUP
    //Click out event!
    jQuery("#backgroundPopup").click(function(){
    disablePopup();
    });
    //Press Escape event!
    jQuery(document).keypress(function(e){
    if(e.keyCode==27 && popupStatus==1){
    disablePopup();
    }
    });

    //LOADING VIEW DIVE POPUP
    //CLOSING POPUP
    //Click out event!
    jQuery("#background-viewdive").click(function(){
    disableViewDive();
    });
    //Press Escape event!
    jQuery(document).keypress(function(e){
    if(e.keyCode==27 && viewDivePopupStatus==1){
    disableViewDive();
    }
    });
});