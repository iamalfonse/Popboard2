$(document).ready(function(){

updateProfileBg = function(colorimg){
	var pageprofile = $('#pageprofile').val();
	$.ajax({
		url: "../updateprofilebg.php",
		datatype: "html",
		data: {profilebg: colorimg, userprofile: pageprofile},
		beforeSend: function() {
			console.log('update bg: '+pageprofile);
		    console.log('update bg: '+colorimg);
		    
		},
		success: function(data) {
			console.log('background updated: '+data);
			if(data == '0'){
				showError('Bro, stop hacking, bro!');
			}else if(data == '2'){
				showError('Bruhh, you don\'t have permission to do that bro!');
			}else if(data == '1'){
				$('.userprofile').prop('class', 'userprofile '+colorimg);
			}
		}
	});
}


var openFile = function(event) {
	console.log('called openFile()');
    var input = event.target;
    console.log(input);

    

    // showjCrop(data.filedir);
	var pageHeight = $(document).height();
	$('.jcropOverlay').height(pageHeight).fadeIn(500);
	// $('.jcropContainer .mainImg').prepend('<img src="/'+data.filedir+'" id="target" />');
	// $('.preview-container').append('<img src="/'+data.filedir+'?'+Math.random()+'" class="jcrop-preview" alt="Preview" />');
	

	var reader = new FileReader();
    reader.onload = function(){
      var dataURL = reader.result;
      console.log('dataURL:',dataURL);
      // var output = document.getElementById('target');
      // output.src = dataURL;

      	$('.jcropContainer .mainImg').prepend('<img src="'+dataURL+'" id="target" />');
		$('.preview-container').append('<img src="'+dataURL+'" class="jcrop-preview" alt="Preview" />');
	
    };
    reader.readAsDataURL(input.files[0]);
    // return false;

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
};

previewImage = function(file) {
 //    var galleryId = "gallery";

 //    var gallery = document.getElementById(galleryId);
 //    var imageType = /image.*/;

 //    if (!file.type.match(imageType)) {
 //        throw "File Type must be an image";
 //    }

 //    var thumb = document.createElement("div");
 //    thumb.classList.add('thumbnail'); // Add the class thumbnail to the created div

 //    var img = document.createElement("img");
 //    img.file = file;
 //    thumb.appendChild(img);
 //    gallery.appendChild(thumb);

 //    // Using FileReader to display the image content
 //    var reader = new FileReader();
 //    reader.onload = (function(aImg) { 
 //    	return function(e) { 
 //    		aImg.src = e.target.result; 
 //    	}; 
 //    })(img);
 //    reader.readAsDataURL(file);

 //    // JCROP SCRIPT START

	// // var imgsrc = $('.profileinfo__avatar img').prop('src');
	// var imgsrc = aImg.src;
	// console.log('imgsrc: ',aImg.src);
	// return false;


	// //--------------------------------------

	





	// //--------------------------------------

	// // showjCrop(data.filedir);
	// var pageHeight = $(document).height();
	// $('.jcropOverlay').height(pageHeight).fadeIn(500);
	// // $('.jcropContainer .mainImg').prepend('<img src="/'+data.filedir+'" id="target" />');
	// // $('.preview-container').append('<img src="/'+data.filedir+'?'+Math.random()+'" class="jcrop-preview" alt="Preview" />');
	// $('.jcropContainer .mainImg').prepend('<img src="'+imgsrc+'" id="target" />');
	// $('.preview-container').append('<img src="'+imgsrc+'?'+Math.random()+'" class="jcrop-preview" alt="Preview" />');
	
	// updatePreview = function(c){
	// 	$('#x').val(c.x);
	// 	$('#y').val(c.y);
	// 	$('#w').val(c.w);
	// 	$('#h').val(c.h);

	// 	if (parseInt(c.w) > 0){
	// 		var rx = xsize / c.w;
	// 		var ry = ysize / c.h;

	// 		pimg.css({
	// 			width: Math.round(rx * boundx) + 'px',
	// 			height: Math.round(ry * boundy) + 'px',
	// 			marginLeft: '-' + Math.round(rx * c.x) + 'px',
	// 			marginTop: '-' + Math.round(ry * c.y) + 'px'
	// 		});
	// 	}
	// };

	// var jcrop_api,
 //        boundx,
 //        boundy,

 //        // Grab some information about the preview pane
 //        preview = $('#preview-pane'),
 //        pcnt = $('#preview-pane .preview-container'),
 //        pimg = $('#preview-pane .preview-container img'),

 //        xsize = pcnt.width(),
 //        ysize = pcnt.height();
    
 //    console.log('init',[xsize,ysize]);
 //    $('#target').Jcrop({
 //      onChange: updatePreview,
 //      onSelect: updatePreview,
 //      aspectRatio: xsize / ysize
 //    },function(){
 //      // Use the API to get the real image size
 //      var bounds = this.getBounds();
 //      boundx = bounds[0];
 //      boundy = bounds[1];
 //      // Store the API in the jcrop_api variable
 //      jcrop_api = this;

 //      // Move the preview into the jcrop container for css positioning
 //      preview.appendTo(jcrop_api.ui.holder);
 //    });


}

ajaxFileUpload = function(that){
	console.log("start ajax upload ");

	
    // var files = that.files;
    // for(var i=0; i<files.length; i++){
    //     previewImage(that.files[i]);
    // }

    // return false;
	

	$.ajaxFileUpload({
		url:'../setupfileupload.php',
		secureuri:false,
		fileElementId:'profileimg',
		dataType: 'json',
		data:{name:'iu', id:'id'},
		beforeSend: function() {
			console.log('name: '+name);
		    console.log('id: '+id);
		},
		success: function (data, status){
			if(typeof(data.uploaderror) != 'undefined'){
				if(data.uploaderror != ''){
					showError(data.uploaderror);
				}else{
					console.log('filesize: '+data.filesize);
					console.log('width: '+data.imagesize_w);
					console.log('height: '+data.imagesize_h);
					console.log('imagetype: '+data.imagetype);
					console.log('image name: '+data.imagename);
					console.log('image URL: '+data.filedir);

					// showjCrop(data.filedir);
					var pageHeight = $(document).height();
					$('.jcropOverlay').height(pageHeight).fadeIn(500);
					$('.jcropContainer .mainImg').prepend('<img src="/'+data.filedir+'" id="target" />');
					$('.preview-container').append('<img src="/'+data.filedir+'?'+Math.random()+'" class="jcrop-preview" alt="Preview" />');
					
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
			console.log(data);
			if(data == undefined){
				// showError("Wrong file type. Only images are allowed.");
			}
			
			// console.log(e);
			
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
	console.log('src',img);

	
	$.ajax({
		url: "../cropprofilepic.php",
		datatype: "html",
		data: {src: img, xvalue: x, yvalue: y, wvalue: w, hvalue: h},
		beforeSend: function() {
		    console.log('processed profile pic');
		    console.log('src',img);
		    
		},
		success: function(data) {
			console.log('successful crop: '+data);

			$('.avatar').attr('src', data+'?'+Math.random());
			$('#target').remove();
			$('.jcrop-holder').remove();
			$('.jcropOverlay').fadeOut(500);
			$('.jcropContainer').append('<div id="preview-pane"><div class="preview-container"></div></div>')
		}
	});

	return false;
}

removeProfilePic = function(){
	console.log('removeProfilePic()');
	$.ajax({
		url: "../removeprofilepic.php",
		dataType: "html",
		beforeSend: function() {
		    console.log('removing profile pic');
		},
		success: function(data) {
			console.log('successful removal ofprofile pic: '+data);
			$('.avatar').attr('src', data+'?'+Math.random());
			$('#target').remove();
			$('.jcrop-holder').remove();
			$('.jcropOverlay').fadeOut(500);
			$('.jcropContainer').append('<div id="preview-pane"><div class="preview-container"></div></div>')
		}
	});

	return false;
}

// showError = function(error){
// 	$('body').append('<div class="error-overlay"><div class="error-container"><p>'+error+'</p><button class="btn cancel">Close</button></div></div>');
// 	$('body').addClass('openOverlay');
// 	return false;
// };	

	
	$('body')
	//show bg color/img selector overlay
	.on('click','.showbgSelector', function(){
		var docHeight = $(document).height();
		$('.profilebgOverlay').css({'height':docHeight}).fadeIn(300);
		$('.profilebgWrap').css({'top':$(window).scrollTop() + 150});
		$('body').addClass('openOverlay');
	})
	.on('click','.profilebgWrap li', function(){
		if($(this).hasClass('locked')){
			//show error
		}else {
			var colorimg = $(this).prop('class');
			updateProfileBg(colorimg);
		}
	})
	.on('click', '.profilebgOverlay, .closex', function(){
		$('.profilebgOverlay').fadeOut(300);
		$('body').removeClass('openOverlay');
	})
	.on('click', '.profilebgWrap', function(){
		return false;
	})

	// show jCrop overlay
	.on('change','#profileimg', function(event){
		ajaxFileUpload(this);
		// openFile(event);
		return false;
	})

	//error window handling
	.on('click', '.btn.cancel', function(){
		$('.error-overlay').remove();
	})

	//jcrop window handling
	.on('click', '.jcropCancel', function(){
		removeProfilePic();
		$('.jcropOverlay').fadeOut(500);
		return false;
	})

	.on('click', '.jcropConfirm', function(){
		cropProfilePic();
		// console.log('wtf');
		return false;
	})

	.on('click', '#uploadphoto', function(){
		$('#profileimg').click();
	});
	
});


