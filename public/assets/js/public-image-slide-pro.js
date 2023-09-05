(function($) {
  $.fn.imageslidepro = function() {
    let activeCountSpan = $('#active-count');
    let totalCountSpan = $('#total-count');
    let nextButton = $('.carousel-control-next');
    let prevButton = $('.carousel-control-prev');
    let slideItems = $('.slide');
    let currentIndex = 0;

    // Set the first slide as active initially
    slideItems.hide().removeClass('active_img');
    slideItems.eq(currentIndex).addClass('active_img');

    activeCountSpan.text(currentIndex + 1);
    totalCountSpan.text(slideItems.length);

    nextButton.on('click', function () {
      slideItems.hide().removeClass('active_img');

      if (currentIndex < slideItems.length - 1) {
        currentIndex++;
      } else {
        currentIndex = 0;
      }

      let next_image = slideItems.eq(currentIndex);
      next_image.addClass('active_img');

      activeCountSpan.text(currentIndex + 1);
      totalCountSpan.text(slideItems.length);
    });

    prevButton.on('click', function () {
      slideItems.hide().removeClass('active_img');

      if (currentIndex <= 0) {
        currentIndex = slideItems.length - 1;
      } else {
        currentIndex--;
      }

      let prev_image = slideItems.eq(currentIndex);
      prev_image.addClass('active_img');

      activeCountSpan.text(currentIndex + 1);
      totalCountSpan.text(slideItems.length);
    });

    setInterval(function () {
      slideItems.hide().removeClass('active_img');
      currentIndex = Math.floor(Math.random() * slideItems.length);
      let next_image = slideItems.eq(currentIndex);
      next_image.addClass('active_img');

      activeCountSpan.text(currentIndex + 1);
      totalCountSpan.text(slideItems.length);
    }, 5000);
  };
})(jQuery);

jQuery(document).ready(function () {
  jQuery('.carousel-control-next, .carousel-control-prev, .slide').imageslidepro();
});
