(function ($) {
  function sliderrr(scopeElement, event_) {
    // console.log("event - > ", event_);

    let slider = scopeElement.find(".elemento-owl-slider-common-secript");
    if (slider.length && slider.length > 0) {
      let dataSetting = slider.attr("data-setting");
      if (dataSetting) {
        dataSetting = JSON.parse(dataSetting);
        if (dataSetting) {
          let owlCarouselArg = { slideTransition: "linear", navSpeed: 1000 };
          owlCarouselArg["responsive"] = {
            300: {
              items: dataSetting.items_mobile,
            },
            600: {
              items: dataSetting.items_tablet,
            },
            900: {
              items: dataSetting.items,
            },
          };
          // number of column
          if ("items" in dataSetting) {
            owlCarouselArg["items"] = dataSetting.items;
          }
          //autoplay
          if ("autoplay" in dataSetting) {
            owlCarouselArg["autoplay"] = true;
            owlCarouselArg["autoplaySpeed"] =
              parseInt(dataSetting.autoPlaySpeed) * 1000;
          }
          //dots and navigation speed
          if ("slider_controll" in dataSetting) {
            // for dots
            owlCarouselArg["dots"] =
              dataSetting.slider_controll == "ar_do" ||
              dataSetting.slider_controll == "dot"
                ? true
                : false;
            // for arrows
            owlCarouselArg["nav"] =
              dataSetting.slider_controll == "ar_do" ||
              dataSetting.slider_controll == "arr"
                ? true
                : false;
          }
          // slider loop
          owlCarouselArg["loop"] =
            "slider_loop" in dataSetting && dataSetting.slider_loop == "1"
              ? true
              : false;
          // slider direction
          owlCarouselArg["rtl"] =
            "autoPlayDirection" in dataSetting &&
            dataSetting.autoPlayDirection == "l"
              ? true
              : false;

          //////// lll_lll_yyy_uuu_iii

          let OWlCarouselSlider = slider.find(".elemento-owl-slider");
          var intOWL = OWlCarouselSlider.owlCarousel(owlCarouselArg);
          intOWL.trigger("refresh.owl.carousel");
        }
      }
    }
  }
  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/elemento-product-simple.default",
      sliderrr
    );
  });

  function next_previous_clone() {
    let thisBtn = $(this);
    let thisWrapper = thisBtn.closest(".elemento-owl-slider-common-secript");
    if (thisBtn.hasClass("elemento-addons-owl-next")) {
      thisWrapper.find(".owl-nav").find(".owl-next").click();
      // owl-nav
    } else if (thisBtn.hasClass("elemento-addons-owl-prev")) {
      thisWrapper.find(".owl-nav").find(".owl-prev").click();
    }
  }
  $(document).on("click", ".elemento-addons-owl-np-cln", next_previous_clone);
})(jQuery);


