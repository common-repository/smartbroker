 jQuery.noConflict();
 
 jQuery(document).ready(function($){
 
	//hide honeypot
	$('#hpt').hide();
	
	var sb_server = $('#sb_server_address').html();
	var sb_listing_page = $('#sb_listing_page').html();
	
	//$('#tabs').tabs();
	
	/*set page title*/
	var newtitle = $('#sb_boat_builder_and_model').html();
	if (newtitle) {
		//var oldtitle = $('title').html();
		var oldtitle = document.title;
		var endpart = oldtitle.split("–");
		if (typeof endpart[1] == 'undefined') {
			var endpart = oldtitle.split("|"); //might be using "|" to separate title (actually, we're forcing this option via hooks now)
			}
		$('title').html(newtitle+" |"+endpart[1]);
		}
	
	$('.button').addClass('ui-state-default ui-corner-all bold').hover(function() {
		$(this).addClass('ui-state-hover');
		}, function() {
		$(this).removeClass('ui-state-hover');
		});
	
	$("body").on('click touch', '#sb_gallery_close', function() {
		$('#sb_gallery_overlay').remove();
		});
	$("body").on('click touch', '#sb_gallery_prev', function() {
		updateSbGallery($(this).attr('data-sbphotoindex'));
		});
	$("body").on('click touch', '#sb_gallery_next', function() {
		updateSbGallery($(this).attr('data-sbphotoindex'));
		});
		
	function updateSbGallery(photoIndex) {
			//console.log('Update request to image ' + photoIndex);
			//remove any existing divs we might not need
			$('#mono_vr_wrapper').remove();
			$('#stereo_vr_wrapper').remove();
			$('#sb_ytplayer').remove();
			$('#sb_gallery').css("background-image", "");
			$('#sb_view_original').hide();
			
			
			var type = imgList[photoIndex].vrType;
			
			newBackground = imgList[photoIndex].imgSrc;
			newBackground = newBackground.replace('/small/','/large/');
			
			if (type == 'sb_vr_mono_small') {
				var vrNode = "<div id='mono_vr_wrapper' data-url='" + newBackground + "'></div>";
				$('#sb_gallery').prepend(vrNode);
				var vrView = new VRView.Player('#mono_vr_wrapper', {image: newBackground, is_stereo: false, height: '100%', width: '100%'});
				}
			else if (type == 'sb_vr_stereo_small') {
				var vrNode = "<div id='stereo_vr_wrapper' data-url='" + newBackground + "'></div>";
				$('#sb_gallery').prepend(vrNode);
				var vrView = new VRView.Player('#stereo_vr_wrapper', {image: newBackground, is_stereo: true, height: '100%', width: '100%'});
				}
			else if (type == 'sb_video_small') {
				var ytId = YouTubeGetID(newBackground);
				var ytNode = '<div id="sb_yt_wrapper"><iframe id="sb_ytplayer" type="text/html" width="100%" height="100%" src="https://www.youtube.com/embed/' + ytId + '?autoplay=1&rel=0" frameborder="0"></iframe></div>';
				$('#sb_gallery').prepend(ytNode);
				}
			else {
				$('#sb_gallery').css("background-image", "url(" + newBackground + ")");
				$('#sb_view_original').attr('href',newBackground).show();
				}
			
			prevIndex = parseInt(photoIndex) - 1;
			if (prevIndex < 0) {prevIndex = imgList.length - 1;}
			$('#sb_gallery_prev').attr('data-sbphotoindex',prevIndex);
			
			nextIndex = parseInt(photoIndex) + 1;
			if (nextIndex > imgList.length - 1) {nextIndex = 0;}
			$('#sb_gallery_next').attr('data-sbphotoindex',nextIndex);
			}
	
	$("a[rel^='sb_prettyPhoto']").click(function(e){
		e.preventDefault();
		imgUrl =$(this).attr('href');
		imgIndex = $(this).attr('data-sbphotoindex');
		//console.log(imgUrl);
		nextIndex = parseInt(imgIndex) + 1;
		if (nextIndex > imgList.length - 1) {nextIndex = 0;}
		prevIndex = parseInt(imgIndex) - 1;
		if (prevIndex < 0) {prevIndex = imgList.length - 1;}
		
		//add overlay and images
		imageNode = " \
		<div id='sb_gallery_overlay'> \
			<div id='sb_gallery'> \
				<a id='sb_gallery_prev' href='#' title='Previous'></a> \
				<a id='sb_gallery_next' href='#' title='Next'></a> \
				<a id='sb_gallery_close' href='#' title='Close'></a> \
				<a id='sb_view_original' href='#' title='View original' target='_blank'></a> \
			</div> \
		</div>";
		
		$('body').prepend(imageNode);
		updateSbGallery(imgIndex);
		

	});
	

 //-------------------------------------------------------------------------------------------------------------------------------------------------------------
 //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	var showAdvanced = false;
 //-------------------------------------------------------------------------------------------------------------------------------------------------------------
//advanced search hide/show
	var showAdvanced = true;
	if ($('#size_low_get').html() == null){showAdvanced = false;}

	if (!(showAdvanced)) {
		$('.advanced_search').hide();
		}
	else {
		$('.advanced_search_icon').addClass('ui-icon-circle-triangle-n');
		$('.advanced_search_icon').removeClass('ui-icon-circle-triangle-s')
		}
	$('.advanced_search_handle').click(function() {
		   if ($('.advanced_search').is(":hidden"))
               {
                    $('.advanced_search').show("slow");
					$('.advanced_search_icon').addClass('ui-icon-circle-triangle-n');
					$('.advanced_search_icon').removeClass('ui-icon-circle-triangle-s');
               } else {
                    $('.advanced_search').hide("slow");
					$('.advanced_search_icon').addClass('ui-icon-circle-triangle-s');
					$('.advanced_search_icon').removeClass('ui-icon-circle-triangle-n');

               }
		});
		 
		
	//shade alternate lines on table
	$('#search_results_table tr:odd').addClass('odd');

	//catch Find Out More form and check we have got email address
	$('#sb_find_out_more').submit(function(e){
		var email = $('input[name="cwr"]').val();
		var emailIsValid = validateEmail(email);
		//alert ('Email: ' + email + ' - valid: ' + emailIsValid);
		var phoneNumber = $('input[name="phone"]').val();
		
		pcm = $('input[name="contact_method"]:checked').val();
		submitForm = true;
		
		if ((pcm == 'phone') && (phoneNumber == '')) {
			alert("If you'd like us to call you, you need include your phone number.");
			submitForm = false;
			}
		if ((pcm == 'email') && (emailIsValid == false)) {
			alert("If you'd like us to email you back, please include a valid email address.");
			submitForm = false;
			}
		if (submitForm == false) {
			e.preventDefault();
			}
		//if city and state exist, bundle into notes
		if ($('input[name="city"]').length == 1) {
			$('textarea[name="notes"]').val('City = ' + $('input[name="city"]').val() + " | " + $('textarea[name="notes"]').val());
			}
		if ($('select[name="state"]').length == 1) {
			$('textarea[name="notes"]').val('State = ' + $('select[name="state"]').val() + " | " + $('textarea[name="notes"]').val());
			}
		});
		
	//thumbnail re-gig
	imgList = [];
	//add primary image to imgList firstChild
	firstImgObject = {imgSrc: $('#sb_primary_image a').attr('href'), vrType: 'sb_vr_none_small'}
	imgList.push(firstImgObject);
	$('#sb_primary_image a').attr('data-sbPhotoIndex',0);
	
	var current = 1;
	$('.sb_clean_thumb a img').each(function(){
		imageObject = {};
		imageObject.imgSrc = $(this).attr('src');
		imageObject.vrType = $(this).attr('class');
		$(this).parent().css('height','80px')
			.css('width','100%')
			.css('background','url(' + imageObject.imgSrc + ')')
			.css('background-repeat','no-repeat')
			.css('background-size','cover')
			.attr('data-vr-type',imageObject.vrType)
			.attr('data-sbPhotoIndex',current)
			.filter('[data-vr-type="sb_vr_mono_small"]').html("<img src='/wp-content/plugins/smartbroker/images/vr-overlay.png' class='sb_vr_overlay'/>");
		
		$(this).parent().filter('[data-vr-type="sb_vr_stereo_small"]').html("<img src='/wp-content/plugins/smartbroker/images/vr-overlay.png' class='sb_vr_overlay'/>");
		$(this).parent().filter('[data-vr-type="sb_video_small"]').html("<img src='/wp-content/plugins/smartbroker/images/video-overlay.png' class='sb_vr_overlay sb_vr_overlay_video'/>");
		
		$(this).remove();
		imgList.push(imageObject);
		current++;
		});
	//console.log(imgList);
	
	
	
	});
	
	

	