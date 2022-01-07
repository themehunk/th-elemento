(function ($) {
  const elemento = {
    init: function () {
      elemento.bind();
      elemento.hover_sliderBottom();
    },
    postPagination: function (e) {
      e.preventDefault();
      let thisBtn = $(this);
      let container_ = thisBtn.closest(".elemento-addons-simple-post");
      //   container_.find(".elemento-addons-loader_").addClass("active");
      let settings_div = container_.find("[data-setting]");
      let settings_attr = settings_div.attr("data-setting");
      if (settings_attr) {
        let stringiFy = JSON.parse(settings_attr);
        // console.log("stringiFy->", stringiFy);
        let currentPAge = stringiFy.current_page;
        if (currentPAge) {
          if (thisBtn.attr("data-link") == "next") {
            currentPAge = currentPAge + 1;
          } else if (thisBtn.attr("data-link") == "prev") {
            currentPAge = currentPAge - 1;
          } else {
            currentPAge = thisBtn.attr("data-link");
          }
          stringiFy.current_page = currentPAge;
          let data_ = {
            action: "elemento_simple_post",
            post_data: stringiFy,
          };
          $.ajax({
            method: "POST",
            url: elemento_simple_url.admin_ajax,
            data: data_,
            success: function (response) {
              if (response.success) {
                let keepContainer = container_.find(
                  ".elemento-post-layout-listGrid"
                );
                let keepJson = JSON.stringify(response.data.settings);
                keepContainer.attr("data-setting", keepJson);
                keepContainer.html(response.data.posthtml);
                $(".elemento-addons-pagination").replaceWith(
                  response.data.pagination
                );
              }
            },
          });
        }
      }
    },
    sliderrr: function (slider) {
      // console.log("event - > ", event_);
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
    },
    quickviewModel: function (e) {
      e.preventDefault();
      if ($(".elemento_quick_view_model").length)
        $(".elemento_quick_view_model").remove();
      let thisBtn = $(this);
      let productId = thisBtn.attr("data-product");
      // add div over lay in body
      let htmlBody = $("body");
      // add div over lay in body


      if (productId) {
        // console.log('elemento_simple_url.admin_ajax',elemento_simple_url.admin_ajax);

        $.ajax({
          method: "post",
          url: elemento_simple_url.admin_ajax,
          data: {
            action: "elemento_quick_view_product_simple",
            product_id: productId,
          },
          // dataType: "JSON",
          success: function (data) {
            // console.log("data->", data);
            // return;
            htmlBody.append('<div class="elemento_quick_view_model"></div>');
            $(".elemento_quick_view_model").html(data);
            $(".elemento-quickview-wrapper").addClass("active");
            let container_ = $(".elemento_quick_view_model");
            let sliderWrapper = container_.find(
              ".elemento-owl-slider-common-secript"
            );
            let getSlider = sliderWrapper.find(".elemento-owl-slider");
            if (getSlider.length) {
              elemento.sliderrr(sliderWrapper);
            }
          },
        });
        // console.log("productId->", productId);
      }
    },
    quickviewModelClose: function () {
      let thisBtn = $(this);
      let wrapper_ = $(".elemento_quick_view_model");
      wrapper_.find(".elemento-quickview-wrapper").fadeOut("slow", function () {
        // setTimeout(() => {
        //   wrapper_.fadeOut("slow", function () {
        //     $(this).remove();
        //   });
        // }, 3000);
        wrapper_.fadeOut("slow", function () {
          $(this).remove();
        });
      });
      // $(".elemento_quick_view_model").remove();
    },
    elemento_plusMinus_quantity: function () {
      let getBtn = $(this);
      let thiswrapper = getBtn.closest(".quickview-add-to-cart");
      // check plus or minus
      let checkPlusMinus = false;
      if (getBtn.hasClass("plus_")) {
        checkPlusMinus = "plus";
      } else if (getBtn.hasClass("minus_")) {
        checkPlusMinus = "minus";
      }
      //checkquantity
      let findBtnAddtoCart = thiswrapper.find(".add_to_cart_button");
      let getCurrentQuantity = findBtnAddtoCart.attr("data-quantity");

      getCurrentQuantity = parseInt(getCurrentQuantity);
      if (checkPlusMinus && getCurrentQuantity) {
        let putQuantity = false;
        if (checkPlusMinus == "minus" && getCurrentQuantity > 1) {
          putQuantity = getCurrentQuantity - 1;
        } else if (checkPlusMinus == "plus") {
          putQuantity = getCurrentQuantity + 1;
        }
        // put quantity inbtn
        if (putQuantity) {
          findBtnAddtoCart.attr("data-quantity", putQuantity);
          thiswrapper.find(".counter_").text(putQuantity);
        }
      }
    },

    hover_sliderBottom: function () {
      $(document).on(
        {
          mouseenter: function () {
            let element = $(this);
            element.addClass("hovered");
            let container = element.closest(".ea-simple-product-slider");
            container.find(".owl-stage-outer").addClass("stage-hovered");
          },
          mouseleave: function () {
            let element_ = $(this);
            let container_ = element_.closest(".ea-simple-product-slider");

            element_.removeClass("hovered");
            container_.find(".owl-stage-outer").removeClass("stage-hovered");
          },
        },
        ".ea-simple-product-slider .elemento-product-outer-wrap"
      );
    },

    bind: function () {
      $(document).on(
        "click",
        ".elemento-addons-simple-post .elemento-post-link:not(.disable)",
        elemento.postPagination
      );
      $(document).on(
        "click",
        ".ea-simple-product-slider .elemento-addons-quickview-simple",
        elemento.quickviewModel
      );
      $(document).on(
        "click",
        ".elemento-quickview-close",
        elemento.quickviewModelClose
      );
      $(document).on(
        "click",
        ".ea-simple-product-slider .elemento_quick_view_model .quickview-add-to-cart .minus_,.ea-simple-product-slider .elemento_quick_view_model .quickview-add-to-cart .plus_",
        elemento.elemento_plusMinus_quantity
      );
    },
  };
  elemento.init();
})(jQuery);
