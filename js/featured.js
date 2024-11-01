jQuery(document).ready(function($){
	var divWidth = $('#sb_featured_carousel').width();
	$('#sb_featured_carousel').height(parseInt(divWidth) * .7);
	
	var featured_links = [];
	$('#sb_links a').each(function(){
		var featured_link = {};
		featured_link.img = $(this).attr('href');
		featured_link.link = $(this).attr('data-link');
		featured_link.description = $(this).attr('data-description');
		featured_links.push(featured_link);
		});
	
	if (featured_links.length > 0) {
		$('#sb_featured_slide_2').css('background-image','url(' + featured_links[0].img + ')').attr('data-index','0').attr('href',featured_links[0].link);
		$('#sb_featured_slide_2 p').html(featured_links[0].description);
		$('#sb_featured_slide_holder').css('left','-100%');
		
		sb_t = setInterval(switch_carousel,4000);
		}
	//switch_carousel(n);
	//console.log(featured_links);
	function switch_carousel() {
		var previous_index = $('#sb_featured_slide_2').attr('data-index');
		index = parseInt(previous_index) + 1;
		if (index > featured_links.length - 1) {
			index = 0;
			}
		$('#sb_featured_slide_1 p').html(featured_links[previous_index].description);
		$('#sb_featured_slide_1').css('background-image','url(' + featured_links[previous_index].img + ')').attr('data-index',previous_index).attr('href',featured_links[previous_index].link);
		$('#sb_featured_slide_holder').css('transition','').css('left','0%');
		
		$('#sb_featured_slide_2 p').html(featured_links[index].description);
		$('#sb_featured_slide_2').css('background-image','url(' + featured_links[index].img + ')').attr('data-index',index).attr('href',featured_links[index].link);
		setTimeout(animate_slides, 500);
		}
	
	function animate_slides() {
		$('#sb_featured_slide_holder').css('transition','left 1s').css('left','-100%');
		}
	});