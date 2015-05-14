$(document).ready(function(){

	$(".enterBirthday").birthdaypicker({
		maxAge: 100
	});
	
	$('body').on('change','#profileimg', function(){
		ajaxFileUpload();
	})
	//error window handling
	.on('click', '.btn.cancel', function(){
		$('.error-overlay').remove();
	})

	//jcrop window handling
	.on('click', '.jcropCancel', function(){
		removeProfilePic();
		$('.jcropOverlay').fadeOut(500);
	})

	.on('click', '.jcropConfirm', function(){
		cropProfilePic();
	});

	$('#uploadphoto').click(function(){
		$('#profileimg').click();
	});



	
});//end document.ready



ajaxFileUpload = function(){
	console.log("start ajax upload ");

	$.ajaxFileUpload({
		url:'../setupfileupload.php',
		secureuri:false,
		fileElementId:'profileimg',
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
					$('.jcropOverlay').height(pageHeight).fadeIn(500);
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

cropProfilePic = function(){
	var x = $('#x').val();
	var y = $('#y').val();
	var w = $('#w').val();
	var h = $('#h').val();
	var img = $('#target').attr('src');

	$.ajax({
		url: "../cropprofilepic.php",
		datatype: "html",
		data: {src: img, xvalue: x, yvalue: y, wvalue: w, hvalue: h},
		beforeSend: function() {
		    console.log('processed profile pic');
		},
		success: function(data) {
			console.log('successful crop: '+data);
			$('.avatarImg').attr('src', data+'?'+Math.random());
			$('#target').remove();
			$('.jcrop-holder').remove();
			$('.jcropOverlay').fadeOut(500);
			$('.jcropContainer').append('<div id="preview-pane"><div class="preview-container"></div></div>')
		}
	});
}

removeProfilePic = function(){

	$.ajax({
		url: "../removeprofilepic.php",
		datatype: "html",
		beforeSend: function() {
		    console.log('removing profile pic');
		},
		success: function(data) {
			console.log('successful removal ofprofile pic: '+data);
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