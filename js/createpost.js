$('document').ready(function(){
	//$('#postmessage').wysiwyg();
	// $('#postmessage').cleditor({
 //        height:       'auto', // height not including margins, borders or padding
 //        controls:     // controls to add to the toolbar
 //          "bold italic underline strikethrough  | " +
 //          "bullets numbering | outdent " +
 //          "indent | alignleft center alignright justify | undo redo | " +
 //          "rule image link unlink",
 //        useCSS:       true,
 //        docCSSFile:   // CSS file used to style the document contained within the editor
 //          "/stylesheets/cleditor.css",
 //        bodyStyle:    // style to assign to document body contained within the editor
 //          "background: #fff; color: #555; margin:4px; font:300 12pt 'Proxima Nova', 'Helvetica Neue', Helvetica, Arial, Verdana; cursor:text"
 //    });


    var content_shown = false;
    var timer;
    var ms = 2000; // milliseconds
    
    CKEDITOR.replace( 'postmessage', {
        
        on: {
            instanceReady: function() {
                // alert( this.name+' is ready' ); 
            },
            key: function(keyevent) {

                clearTimeout(timer);
                var img_arr_pos = 0;
                var img_pos = 0;
                var total_images = 0;
                // alert( this.name );

                //url to match in the text field (but don't match youtube or vimeo videos)
                var match_url = /\b(https?):\/\/(?!(www.)?youtu\.?be(.com)?|(www.)?vimeo\.com)([\-A-Z0-9.]+)(\/[\-A-Z0-9+&@#\/%=~_|!:,.;]*)?(\?[A-Z0-9+&@#\/%=~_|!:,.;]*)?/i;
                // var match_url = /(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)/i;
                
                //returns true and continue if matched url is found in text field
                if (match_url.test(CKEDITOR.instances.postmessage.getData())) {
                    

                    timer = setTimeout(function() {
                        
                        extracted_url = CKEDITOR.instances.postmessage.getData().match(match_url)[0]; //extracted first url from text filed
                        console.log('url: ', extracted_url);

                        if(content_shown == false){
                            $("#results").hide();
                            $("#loading_indicator").show(); //show loading indicator image
                            
                            //ajax request to be sent to extract-process.php
                            $.post('../extract-process.php',{'url': extracted_url}, function(data){         
                                // console.log(data);
                                content_shown = true;

                                console.log('extract html');

                                extracted_images = data.images;
                                // console.log(extracted_images);
                                total_images = parseInt(extracted_images.length-1);
                                img_arr_pos = 0;
                                img_pos = 0;
                                img_pos = img_arr_pos+1;

                                // console.log('total_images: ', total_images);

                                if(total_images>0){
                                    inc_image = '<div class="extracted_thumb" id="extracted_thumb"><img src="'+data.images[img_arr_pos]+'" width="" height=""></div>';
                                    //content to be loaded in #results element
                                    var content = '<div class="extracted_url"><div class="extracted_close"></div>'+ inc_image +'<div class="extracted_content"><h4><a href="'+extracted_url+'" target="_blank">'+data.title+'</a></h4><p>'+data.content+'</p><div class="thumb_wrap"><div class="thumb_sel"><span class="prev_thumb" id="thumb_prev"></span><span class="next_thumb" id="thumb_next"></span> </div><span class="small_text" id="total_imgs">'+img_pos+' of '+(total_images+1)+'</span><span class="small_text"> Choose a Thumbnail</span></div></div></div>';
                                }else{
                                    //content to be loaded in #results element
                                    var content = '<div class="extracted_url no_image"><div class="extracted_close"></div><div class="extracted_content"><h4><a href="'+extracted_url+'" target="_blank">'+data.title+'</a></h4><p>'+data.content+'</p></div></div>';
                                }
                                
                                //load results in the element
                                $("#results").html(content); //append received data into the element
                                $("#results").show(200); //show results with slide down effect
                                $("#loading_indicator").hide(); //hide loading indicator image

                                //user clicks previous thumbail
                                $("body").on("click","#thumb_prev", function(e){
                                    if(img_arr_pos>0){
                                        $('.prev_thumb, .next_thumb').removeClass('inactive');

                                        img_arr_pos--; //thmubnail array position decrement
                                        img_pos = img_arr_pos+1;
                                        //replace with new thumbnail
                                        $("#extracted_thumb").html('<img src="'+extracted_images[img_arr_pos]+'" >');

                                        //show thmubnail position
                                        $("#total_imgs").html((img_pos) +' of '+ (total_images+1));
                                    }else if (img_arr_pos==0){
                                        $('.prev_thumb').addClass('inactive');
                                    }
                                })
                                
                                //user clicks next thumbail
                                .on("click","#thumb_next", function(e){
                                    if(img_arr_pos<total_images){
                                        $('.prev_thumb, .next_thumb').removeClass('inactive');

                                        img_arr_pos++; //thmubnail array position increment
                                        img_pos = img_arr_pos+1;

                                        //replace with new thumbnail
                                        $("#extracted_thumb").html('<img src="'+extracted_images[img_arr_pos]+'" >');

                                        //replace thmubnail position text
                                        $("#total_imgs").html((img_pos) +' of '+ (total_images+1));
                                    }else if(img_arr_pos==total_images){
                                        $('.next_thumb').addClass('inactive');
                                    }
                                })
                                .on("click", ".extracted_close", function(){
                                    content_shown = false;
                                    total_images = 0;
                                    img_arr_pos = 0;
                                    img_pos = 0;
                                    $("#results").hide(200);
                                    $('.extracted_url').remove();
                                });

                            },'json')
                            .done(function() {
                                console.log('content_shown: ', content_shown);
                                // keyevent.editor.resize( '100%', '350', true );
                            })
                            .fail(function(data) {
                                // console.log( "error", data );
                                console.log( "-------------error--------------" );
                                $("#loading_indicator").hide();
                            });
                        }
                    }, ms); // end timer
                    
                } // end url match
            }
        }
    });


    // alert(CKEDITOR.instances.postmessage.getData());

    // front end validation
    $('#postsubmit').click(function(event){
        var title = $('#posttitle').val();
        var message = CKEDITOR.instances.postmessage.getData();
        if(title == '' || message.length == 0 ){
            event.preventDefault();
            $('.errorContainer').html('').prepend('<p class="error">Title and Message cannot be left blank.</p>');
      
        
            $("html, body").animate({ scrollTop: 0 }, 300);
            return false;
        }



    });


});