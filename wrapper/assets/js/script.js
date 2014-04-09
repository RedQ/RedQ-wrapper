


jQuery(document).ready(function($) {




               $('.demo.menu .item').tab();
               $('.wp-color-picker-field').wpColorPicker();




                $('.group').hide();


                var activetab = '';
                if (typeof(localStorage) != 'undefined' ) {
                    activetab = localStorage.getItem("activetab");
                }

                if (activetab != '' && $(activetab).length ) {
                    $(activetab).fadeIn();
                } else {
                    $('.group:first').fadeIn();
                }

                $('.group .collapsed').each(function(){
                    $(this).find('input:checked').parent().parent().parent().nextAll().each(
                    function(){
                        if ($(this).hasClass('last')) {
                            $(this).removeClass('hidden');
                            return false;
                        }
                        $(this).filter('.hidden').removeClass('hidden');
                    });
                });

                if (activetab != '' && $(activetab + '-tab').length ) {
                    $(activetab + '-tab').addClass('nav-tab-active');
                }
                else {
                    $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
                }


                $('.nav-tab-wrapper a').click(function(evt) {
                    $('.nav-tab-wrapper a').removeClass('nav-tab-active');
                    $(this).addClass('nav-tab-active').blur();
                    var clicked_group = $(this).attr('href');
                    if (typeof(localStorage) != 'undefined' ) {
                        localStorage.setItem("activetab", $(this).attr('href'));
                    }
                    $('.group').hide();
                    //$(clicked_group).fadeIn();
                    $(clicked_group).show().addClass('animated fadeInRight');;
                    evt.preventDefault();
                });

            $('.cancel').on('click',function(e){
                e.preventDefault();

               var imgref = $(this).attr('ref');
                 $('input.'+imgref).val(' ');
                 $('img.'+imgref).attr('src','').css({'display':'none'});
                 $(this).hide();

            });




// repeat 

    $(".docopy").on("click", function(e){
      e.preventDefault();
 
      // the loop object
      $loop = $(this).parent();
 
      // the group to copy
      $group = $loop.find('.to-copy').clone().insertBefore($(this)).removeClass('to-copy');
 
      // the new input
      $input = $group.find('input');
 
      input_name = $input.attr('data-rel');
      count = $loop.children('.of-repeat-group').not('.to-copy').length;
 
      $input.attr('name', input_name + '[' + ( count - 1 ) + ']');
 
 
    });
 
    $(".of-repeat-group").on("click", ".dodelete", function(e){
      e.preventDefault();
      $(this).parent().remove();
    });


         // Uploading files
var file_frame;
 
  $('.upload_image_button').on('click', function( event ){
 
    event.preventDefault();

    ref = $(this).attr('ref');


 
    // If the media frame already exists, reopen it.
    if ( file_frame ) {
      file_frame.open();
      return;
    }
 
    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: $( this ).data( 'uploader_title' ),
      button: {
        text: $( this ).data( 'uploader_button_text' ),
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });
 
    // When an image is selected, run a callback.
    file_frame.on( 'select', function() {
      // We set multiple to false so only get one image from the uploader
      attachment = file_frame.state().get('selection').first().toJSON();
 
      // Do something with attachment.id and/or attachment.url here
      $('input.'+ref).val(attachment.url);
      $('.cancel_'+ref).css({'display':''});
      $('img.'+ref).attr('src',attachment.url).css({'display':'block'});




    });
 
    // Finally, open the modal
    file_frame.open();
  });

});