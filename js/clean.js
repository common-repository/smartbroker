jQuery.noConflict();

jQuery(document).ready(function($){
 $('#sb_view_all_link').click(function(e){
	e.preventDefault();
	$('#sb_primary_image a:first-child').click();
	
	});
	
	$('#sb_advanced_options').hide();
	
	$('#sb_show_advanced').click(function(e){
		e.preventDefault();
		$('#sb_advanced_options').toggle('fast',function(){
			var text = 'Hide advanced search options &#9650;'
			if ($(this).is(':hidden')) {
				text = 'Show advanced search options &#9660;'
				}
			$('#sb_show_advanced').html(text);
			});
		});
//end jQuery
});