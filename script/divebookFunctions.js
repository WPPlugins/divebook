/**
 * Description: Util JS functions for DiveBook
 * Author: Per Ola Saether
 */

//Reset form to normal input
function resetForm(){
	jQuery("input[name=divebookform_hidden]").val('Log Dive');
	jQuery("th[id=divebook-header]").replaceWith('<th id="divebook-header">Log a Dive</th>');
	jQuery("input[name=diveID]").val('');
	jQuery("input[name=date]").val('');
	jQuery("input[name=datehidden]").val('');
	jQuery("input[name=divesite]").val('');
	jQuery("input[name=divebuddies]").val('');
	jQuery("input[name=gps_position]").val('');
	jQuery("input[name=maxdepth]").val('');
	jQuery("input[name=divetime]").val('');
	jQuery("input[name=watertemperature]").val('');
	jQuery("select[name=visibility]").val(0);
	jQuery("input[name=visibility_meters]").val('');
	jQuery("textarea[name=description]").val('');
	jQuery("#editDive").hide();
	jQuery("#addDive").show();
}

/**
 *
 * @access public
 * @return void
 **/
function editDive(diveArray){
	//populate edit dive
	jQuery("input[name=divebookform_hidden]").val('Edit Dive');
	jQuery("th[id=divebook-header]").replaceWith('<th id="divebook-header">Edit Dive</th>');
	jQuery("input[name=datehidden]").val(diveArray[3]);
	jQuery("input[name=date]").val(diveArray[3]);
        if (jQuery("input[name=diver]") != null){
            jQuery("input[name=diver]").val(diveArray[2]);
        }
        if (jQuery("select[name=diver_on_behalf]") != null){
            jQuery("select[name=diver_on_behalf]").val(diveArray[1]);
        }
        jQuery("input[name=diveID]").val(diveArray[0]);
	jQuery("input[name=divesite]").val(fixSpecialChars(diveArray[4]));
	jQuery("input[name=divebuddies]").val(fixSpecialChars(diveArray[5]));
	jQuery("input[name=gps_position]").val(fixSpecialChars(diveArray[8]));
	jQuery("input[name=maxdepth]").val(diveArray[6]);
	jQuery("input[name=divetime]").val(diveArray[7]);
	jQuery("input[name=watertemperature]").val(diveArray[13]);
	jQuery("select[name=visibility]").val(diveArray[10]);
	jQuery("input[name=visibility_meters]").val(diveArray[11]);
	jQuery("textarea[name=description]").val(fixSpecialChars(diveArray[12]));

	jQuery("#editDive").show();
	jQuery("#addDive").hide();
	//load popup
	loadPopup();
}


//Set dive input form action url
function setFormActionAndSubmit(url){
	if (validateForm()) {
		document.diveinputform.action = addScrollToUrl(url);
		document.diveinputform.submit();
	}
}


function viewDive(diveArray){
    //populate view dive
    jQuery("input[name=viewdive-date]").val(diveArray[3]);
    jQuery("input[name=viewdive-diver]").val(diveArray[2]);
    jQuery("input[name=viewdive-divesite]").val(fixSpecialChars(diveArray[4]));
    jQuery("input[name=viewdive-divebuddies]").val(fixSpecialChars(diveArray[5]));
    jQuery("input[name=viewdive-gps_position]").val(fixSpecialChars(diveArray[8]));
    jQuery("input[name=viewdive-maxdepth]").val(diveArray[6]);
    jQuery("input[name=viewdive-divetime]").val(diveArray[7]);
    jQuery("input[name=viewdive-watertemperature]").val(diveArray[13]);
    jQuery("input[name=viewdive-visibility]").val(diveArray[10]);
    jQuery("input[name=viewdive-visibility_meters]").val(diveArray[11]);
    jQuery("textarea[name=viewdive-description]").val(fixSpecialChars(diveArray[12]));
    //load popup
    loadViewDive();
}

function fixSpecialChars(str){
    str= str.replace(/\&amp;/g,'&');
    str= str.replace(/\&lt;/g,'<');
    str= str.replace(/\&gt;/g,'>');
    str= str.replace(/\&quot;/g,'"');
    return str;
}

function orderDives(url){
	location.replace(addScrollToUrl(url));
}

/**
 *
 * @access public
 * @return void
 **/
function orderDives(url, value){
	var urlWithParameter = addParameterToUrl(url, 'dive_orderby', value);
	location.replace(addScrollToUrl(urlWithParameter));
}

/**
 *
 * @access public
 * @return void
 **/
function changePage(url, pageNumber){
	var urlWithParameter = addParameterToUrl(url, 'divelog_page', pageNumber);
	location.replace(addScrollToUrl(urlWithParameter));
}

/**
 *
 * @access public
 * @return void
 **/
function addScrollToUrl(url){
	var scrollTop = jQuery(window).scrollTop();
	var urlWithScroll = addParameterToUrl(url, 'scrolled', scrollTop);
	return urlWithScroll;
}

/**
 *
 * @access public
 * @return void
 **/
function filterDives(url){
	var diverFilter = jQuery("select[name=filter-diver]").val();
	var divesiteFilter = jQuery("input[name=filter-divesite]").val();
        if (divesiteFilter == '') divesiteFilter = '0';
	var urlWithFilterParameter = url;
	urlWithFilterParameter = addParameterToUrl(url, 'filter_diver', diverFilter);
	urlWithFilterParameter = addParameterToUrl(urlWithFilterParameter, 'filter_divesite', divesiteFilter);
	//reset page
	urlWithFilterParameter = urlWithFilterParameter = addParameterToUrl(urlWithFilterParameter, 'divelog_page', 1);
	location.replace(addScrollToUrl(urlWithFilterParameter));
}

/**
 *
 * @access public
 * @return void
 **/
function addParameterToUrl(url, parametername, parametervalue){
	var urlWithParameter = url;
	if (getParameterFromUrl(parametername) != -1) {
		//Parameter exists, replace value
		var oldParameter = parametername + "=" +getParameterFromUrl(parametername);
		var newParameter = parametername + "=" +parametervalue;
		urlWithParameter = urlWithParameter.replace(oldParameter, newParameter);
	}
	else if (url.indexOf("?") == -1) {
		urlWithParameter = url + "?" +parametername + "=" +parametervalue;
	}
	else{
		urlWithParameter = url + "&" +parametername + "=" +parametervalue;
	}
	return urlWithParameter;
}


function getParameterFromUrl(name){
	var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (!results) { return -1; }
	return results[1] || -1;
}

function handleError(e){
	jQuery("input[name=date]").removeAttr("readonly");
	return true;
}
window.onerror = handleError;


function validateForm()
{
 if(jQuery("input[name=date]").attr("readonly") == false)
 {
	 if (!isEmpty(document.getElementById("date"))) {
	 	jQuery("input[name=datehidden]").val(jQuery("input[name=date]").val());
	 }
 }

 if(isEmpty(document.getElementById("datehidden")))
 {
  smoothAlert('Form not valid', 'You have not entered a dive date. <br/>Please fill in all fields marked with a *');
  return false;
 }
 else if(isEmpty(document.getElementById("divesite")))
 {
  smoothAlert('Form not valid', 'You have not entered a dive site. <br/>Please fill in all fields marked with a *');
  document.getElementById("divesite").focus();
  return false;
 }
 else if(isEmpty(document.getElementById("maxdepth")))
 {
  smoothAlert('Form not valid', 'You have not entered max depth. <br/>Please fill in all fields marked with a *');
  document.getElementById("maxdepth").focus();
  return false;
 }
 else if(isEmpty(document.getElementById("divetime")))
 {
  smoothAlert('Form not valid', 'You have not entered dive time. <br/>Please fill in all fields marked with a *');
  document.getElementById("divetime").focus();
  return false;
 }
 else if(!isNumeric(document.getElementById("maxdepth").value))
 {
  smoothAlert('Form not valid', 'Please enter only numbers in the max depth field');
  document.getElementById("maxdepth").focus();
  document.getElementById("maxdepth").select();
  return false;
 }
 else if(!isNumeric(document.getElementById("divetime").value))
 {
  smoothAlert('Form not valid', 'Please enter only numbers  in the dive time field');
  document.getElementById("divetime").focus();
  document.getElementById("divetime").select();
  return false;
 }
 else if(!isEmpty(document.getElementById("visibility_meters")) && !isNumeric(document.getElementById("visibility_meters").value))
 {
  smoothAlert('Form not valid', 'Please enter only numbers  in the visibility (in meters) field');
  document.getElementById("visibility_meters").focus();
  document.getElementById("visibility_meters").select();
  return false;
 }
 else if(!isDate(document.getElementById("datehidden").value))
 {
  smoothAlert('Form not valid', 'Please enter a valid date: YYYY-MM-DD');
  return false;
 }
 else if(!isValidTemperature(document.getElementById("watertemperature").value))
 {
  smoothAlert('Form not valid', 'Please enter a valid water temperature. <br/>Valid range: -99 to 99');
  return false;
 }
 return true;
 }

function isEmpty(aTextField)
{
 if((aTextField.value.length==0) || (aTextField.value==null)) {return true;}
 else{return false;}
 }


function isNumeric(sText){
    var objRegExp  = /(^-?\d\d*$)/;
    return objRegExp.test(sText);
}

/**
 *
 * @access public
 * @return void
 **/
function isValidTemperature(sText){
	if (sText.length==0 || sText==null) {
		return true;
	}
	else if (sText.indexOf('-') == 0) {
		return isNumeric(sText.substring(1, sText.length));
	}
	return isNumeric(sText);
}

function isDate(strValue){
    var objRegExp = /^\d{4}(\-|\/|\.)\d{1,2}\1\d{1,2}$/;

      //check to see if in correct format
      if(!objRegExp.test(strValue))
        return false; //doesn't match pattern, bad date
      else{
        var strSeparator = strValue.substring(4,5);
        var arrayDate = strValue.split(strSeparator);
        //create a lookup for months not equal to Feb.
        var arrayLookup = { '01' : 31,'03' : 31,
                            '04' : 30,'05' : 31,
                            '06' : 30,'07' : 31,
                            '08' : 31,'09' : 30,
                            '10' : 31,'11' : 30,'12' : 31};
        var intDay = parseInt(arrayDate[2],10);

        //check if month value and day value agree
        if(arrayLookup[arrayDate[1]] != null) {
          if(intDay <= arrayLookup[arrayDate[1]] && intDay != 0)
            return true; //found in lookup table, good date
        }

        //check for February (bugfix 20050322)
        //bugfix  for parseInt kevin
        //bugfix  biss year  O.Jp Voutat
        var intMonth = parseInt(arrayDate[1],10);
        if (intMonth == 2) {
           var intYear = parseInt(arrayDate[0]);
           if (intDay > 0 && intDay < 29) {
               return true;
           }
           else if (intDay == 29) {
             if ((intYear % 4 == 0) && (intYear % 100 != 0) ||
                 (intYear % 400 == 0)) {
                  // year div by 4 and ((not div by 100) or div by 400) ->ok
                 return true;
             }
           }
        }
      }
      return false; //any other values, bad date

}