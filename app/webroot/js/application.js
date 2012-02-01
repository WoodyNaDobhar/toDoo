////file:app/webroot/js/application.js
$(document).ready(function(){

	// Caching the keyword textbox:
	var name = $('#name');
	
	// Defining a placeholder text:
	name.defaultText('Search by keyword');
	
	// Using jQuery UI's autocomplete widget:
	name.autocomplete({
		minLength:	1,
		source:		'http://localhost/foundersFactory/tasks/search'
	});
});

// A custom jQuery method for placeholder text:
$.fn.defaultText = function(value){

	//vars
	var element = this.eq(0);
	element.data('defaultText',value);
	
	//set the values
	element.focus(function(){
		if(element.val() == value){
			element.val('').removeClass('defaultText');
		}
	}).blur(function(){
		if(element.val() == '' || element.val() == value){
			element.addClass('defaultText').val(value);
		}
	});
	
	return element.blur();
}