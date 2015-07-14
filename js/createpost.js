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

  CKEDITOR.replace( 'postmessage' );
  

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