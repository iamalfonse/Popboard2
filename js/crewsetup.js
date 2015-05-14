$(document).ready(function(){
	
	$('body').on('change','#crewimg', function(){
		ajaxFileUpload();
	})
	//error window handling
	.on('click', '.btn.cancel', function(){
		$('.error-overlay').remove();
	})

	//jcrop window handling
	.on('click', '.jcropCancel', function(){
		removeCrewPic();
		$('.jcropOverlay').fadeOut(500);
	})

	.on('click', '.jcropConfirm', function(){
		cropCrewPic();
	})

	.on('keyup', '.crewinfo .crewDesc', function(){
		var crewdesc = $(this).val();
		$('.crewPreview .crewAbout').html(crewdesc);
		$(this).removeClass('required');//if required was added after submit
		if(crewdesc == ''){
			$(this).addClass('required');
		}
	})
	.on('keyup', '.crewinfo .crewLoc', function(){
		var crewloc = $(this).val();
		$('.crewPreview .location').html(crewloc);
		$(this).removeClass('required');//if required was added after submit
		if(crewloc == ''){
			$('.crewPreview .location').html('&nbsp;');//placeholder if there's no value
			$(this).addClass('required');
		}
	})
	.on('focusout', '.crewinfo .crewDesc, .crewinfo .crewLoc', function(){
		var crewdesc = $('.crewinfo .crewDesc').val();
		var crewloc = $('.crewinfo .crewLoc').val();
		$('.crewPreview .crewAbout').html(crewdesc);
		$('.crewPreview .location').html(crewloc);
	})
	.on('click','.crewinfo input[type="radio"]', function(){
		var val = $(this).val();
		if(val == '1'){
			$('.crewprivate').append('<span>(Only crew members can view posts)</span>');
			$('.crewlist li').append('<div class="private">Private</div>');
		}else if(val == '0'){
			$('.crewprivate span, .crewlist .private').detach();
		}
	})
	.on('click','.submitbtn', function(event){
		
		var crewname = $('.crewinfo .crewName').val();
		var crewdesc = $('.crewinfo .crewDesc').val();
		var crewloc = $('.crewinfo .crewLoc').val();
		if(crewname == '' || crewdesc == '' || crewloc == ''){
			event.preventDefault();
			$('#right').prepend('<p class="error">Please fill in the required fields.</p>');

			if(crewname == ''){$('.crewinfo .crewName').addClass('required');}
			if(crewdesc == ''){$('.crewinfo .crewDesc').addClass('required');}
			if(crewloc == ''){$('.crewinfo .crewLoc').addClass('required');}

			return;
		}
	});

	$('#uploadphoto').click(function(){
		$('#profileimg').click();
	});

});//end document.ready



ajaxFileUpload = function(){
	console.log("start ajax upload ");

	$.ajaxFileUpload({
		url:'../setupcrewupload.php',
		secureuri:false,
		fileElementId:'crewimg',
		dataType: 'json',
		data:{name:'iu', id:'id'},
		success: function (data, status){
			if(typeof(data.uploaderror) != 'undefined')
			{
				if(data.uploaderror != '')
				{
					showError(data.uploaderror);
				}else
				{
					console.log('filesize: '+data.filesize);
					console.log('width: '+data.imagesize_w);
					console.log('height: '+data.imagesize_h);
					console.log('imagetype: '+data.imagetype);
					console.log('image name: '+data.imagename);
					console.log('image URL: '+data.filedir);

					// showjCrop(data.filedir);
					var pageHeight = $(document).height();
					// $('.jcropOverlay').height(pageHeight).fadeIn(500);
					$('.jcropContainer .mainImg').prepend('<img src="'+data.filedir+'" id="target" />');
					$('.preview-container').append('<img src="'+data.filedir+'?'+Math.random()+'" class="jcrop-preview" alt="Preview" />');
					
					updatePreview = function(c){
						$('#x').val(c.x);
						$('#y').val(c.y);
						$('#w').val(c.w);
						$('#h').val(c.h);

						if (parseInt(c.w) > 0){
							var rx = xsize / c.w;
							var ry = ysize / c.h;

							pimg.css({
								width: Math.round(rx * boundx) + 'px',
								height: Math.round(ry * boundy) + 'px',
								marginLeft: '-' + Math.round(rx * c.x) + 'px',
								marginTop: '-' + Math.round(ry * c.y) + 'px'
							});
						}
					};

					var jcrop_api,
				        boundx,
				        boundy,

				        // Grab some information about the preview pane
				        preview = $('#preview-pane'),
				        pcnt = $('#preview-pane .preview-container'),
				        pimg = $('#preview-pane .preview-container img'),

				        xsize = pcnt.width(),
				        ysize = pcnt.height();
				    
				    console.log('init',[xsize,ysize]);
				    $('#target').Jcrop({
				      onChange: updatePreview,
				      onSelect: updatePreview,
				      aspectRatio: xsize / ysize
				    },function(){
				      // Use the API to get the real image size
				      var bounds = this.getBounds();
				      boundx = bounds[0];
				      boundy = bounds[1];
				      // Store the API in the jcrop_api variable
				      jcrop_api = this;

				      // Move the preview into the jcrop container for css positioning
				      preview.appendTo(jcrop_api.ui.holder);
				    });

				    //crop automatically
				    cropCrewPic();
				}
			}
			
		},
		error: function (data, status, e)
		{
			console.log("Error: "+data.uploaderror);
			if(data.uploaderror == undefined){
				// showError("Wrong file type. Only images are allowed.");
			}
			
			//console.log(e);
			
		}
	});
		
	return false;
}

cropCrewPic = function(){
	var x = $('#x').val();
	var y = $('#y').val();
	var w = $('#w').val();
	var h = $('#h').val();
	var img = $('#target').attr('src');
	console.log('imgz: ',img);

	$.ajax({
		url: "../cropcrewpic.php",
		datatype: "html",
		data: {src: img, xvalue: x, yvalue: y, wvalue: w, hvalue: h},
		beforeSend: function() {
		    console.log('processing crew pic');
		},
		success: function(data) {
			console.log('successful crop: '+data);
			$('.crewPreview .crewImg img').attr('src', data+'?'+Math.random());
			$('#target').remove();
			$('.jcrop-holder').remove();
			$('.jcropOverlay').fadeOut(500);
			$('.jcropContainer').append('<div id="preview-pane"><div class="preview-container"></div></div>')
		}
	});
}

removeCrewPic = function(){

	$.ajax({
		url: "../removecrewpic.php",
		datatype: "html",
		beforeSend: function() {
		    console.log('removing crew pic');
		},
		success: function(data) {
			console.log('successful removal of crew pic: '+data);
			$('.avatarImg').attr('src', data+'?'+Math.random());
			$('#target').remove();
			$('.jcrop-holder').remove();
			$('.jcropOverlay').fadeOut(500);
			$('.jcropContainer').append('<div id="preview-pane"><div class="preview-container"></div></div>')
		}
	});
}

showError = function(error){
	$('body').append('<div class="error-overlay"><div class="error-container"><p>'+error+'</p><button class="btn cancel">Close</button></div></div>');
};