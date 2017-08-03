 function smoothAlert(header, message){
	jQuery.confirm({
		'title'		: header,
		'message'	: message,
		'buttons'	: {
			'OK'	: {
				'class'	: 'blue',
				'action': function(){}
			}
		}
	});
}

function deleteDive(url){
	jQuery.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'You are about to delete a dive. <br />It cannot be restored at a later time. <br /> Continue?',
		'buttons'	: {
			'Yes'	: {
				'class'	: 'blue',
				'action': function(){
					location.replace(addScrollToUrl(url));
				}
			},
			'No'	: {
				'class'	: 'gray',
				'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
			}
		}
	});
}


(function(jQuery){

	jQuery.confirm = function(params){

		if(jQuery('#confirmOverlay').length){
			// A confirm is already shown on the page:
			return false;
		}

		var buttonHTML = '';
		jQuery.each(params.buttons,function(name,obj){

			// Generating the markup for the buttons:

			buttonHTML += '<a href="#" class="button '+obj['class']+'">'+name+'<span></span></a>';

			if(!obj.action){
				obj.action = function(){};
			}
		});

		var markup = [
			'<div id="confirmOverlay">',
			'<div id="confirmBox">',
			'<h1>',params.title,'</h1>',
			'<p>',params.message,'</p>',
			'<div id="confirmButtons">',
			buttonHTML,
			'</div></div></div>'
		].join('');

		jQuery(markup).hide().appendTo('body').fadeIn();

		var buttons = jQuery('#confirmBox .button'),
			i = 0;

		jQuery.each(params.buttons,function(name,obj){
			buttons.eq(i++).click(function(){

				// Calling the action attribute when a
				// click occurs, and hiding the confirm.

				obj.action();
				jQuery.confirm.hide();
				return false;
			});
		});
	}

	jQuery.confirm.hide = function(){
		jQuery('#confirmOverlay').fadeOut(function(){
			jQuery(this).remove();
		});
	}

})(jQuery);