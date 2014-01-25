$(document).ready(function() {
	
	// this is all you need to initialize the plugin
	$('.tabtab').tabTab();
	
	
});


// you can leave this one out too
function classSwitcher(select,target) {

	var select = $(select),
		option = select.find('option'),
		target = $(target);
		
	
	option.each(function() {
		
		var $this = $(this);
		
		if(target.hasClass($this.val().replace(' ',''))) $this.attr('selected','selected');
	});
	
	
	select.change(function() {
		
		option.each(function() {
			
			target.removeClass($(this).val().replace(' ',''));
		});
		
		
		target.addClass($(this).val().replace(' ',''));
	});
}
