$(document).ready(function(){
	
	// $('#bgslider').hide();
    
	// $('#bgslider').fadeIn(1500);//fade the background back in once all the images are loaded
	// run every 7s
	// setInterval('cycleImages()', 7000);

});//end document.ready

function cycleImages(){
	var $active = $('#bgslider .active');
	var $next = ($('#bgslider .active').next().length > 0) ? $('#bgslider .active').next() : $('#bgslider .image:first');
	$next.css('z-index',2); //move the next image up the pile
	$active.fadeOut(1500,function(){ //fade out the top image
		$active.css('z-index',1).show().removeClass('active'); //reset the z-index and unhide the image
		$next.css('z-index',3).addClass('active'); //make the next image the top one
	});
}