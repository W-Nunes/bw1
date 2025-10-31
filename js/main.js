$(document).ready(function () {



  $("div.blog-post").hover(
    function () {
      $(this).find("div.content-hide").slideToggle("fast");
    },
    function () {
      $(this).find("div.content-hide").slideToggle("fast");
    }
  );

  $('.flexslider').flexslider({
    prevText: '',
    nextText: ''
  });

  $('.testimonails-slider').flexslider({
    animation: 'slide',
    slideshowSpeed: 5000,
    prevText: '',
    nextText: '',
    controlNav: false
  });

  $(function () {

    // Instantiate MixItUp:

    $('#Container').mixItUp();



    $(document).ready(function () {
      $(".fancybox").fancybox();
    });

  });

  $(".coupon-click").on('click', function () {
    $(this).parents('.coupon-input-area-1').find(".coupon-input-area").toggleClass('show');
  });

});

