
jQuery(document).ready(function () {
    jQuery('.testimonial-wrap').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        arrows: false
      });


      // fancybox-js
      jQuery('.play-button a').fancybox({
        caption : function( instance, item ) {
          return $(this).parent().find('.card-text').html();
        }
      });
});
