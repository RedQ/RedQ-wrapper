


jQuery(document).ready(function($) {
   // write code here



$('.demo.menu .item')
  .tab()
;


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



});