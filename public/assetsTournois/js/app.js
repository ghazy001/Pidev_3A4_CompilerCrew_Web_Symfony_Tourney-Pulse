// Template Name: Striker
// Template URL: https://techpedia.co.uk/template/striker
// Description:  Striker - Sports Club Html Template
// Version: 1.0.0

(function (window, document, $, undefined) {
  "use strict";
  var Init = {
    i: function (e) {
      Init.s();
      Init.methods();
    },
    s: function (e) {
      (this._window = $(window)),
        (this._document = $(document)),
        (this._body = $("body")),
        (this._html = $("html"));
    },
    methods: function (e) {
      Init.w();
      Init.BackToTop();
      Init.preloader();
      Init.miniCart();
      Init.searchInput();
      Init.quantityHandle();
      Init.sizeChange();
      Init.colorChange();
      Init.showReview();
      Init.initializeSlick();
      Init.formValidation();
      Init.contactForm();
      Init.videoPlay();
      Init.modalPopup();
    },
    w: function (e) {
      this._window.on("load", Init.l).on("scroll", Init.res);
    },
    BackToTop: function () {
      var btn = $("#backto-top");
      $(window).on("scroll", function () {
        if ($(window).scrollTop() > 300) {
          btn.addClass("show");
        } else {
          btn.removeClass("show");
        }
      });
      btn.on("click", function (e) {
        e.preventDefault();
        $("html, body").animate(
          {
            scrollTop: 0,
          },
          "300"
        );
      });
    },
    preloader: function () {
      setTimeout(function () {
        $("#preloader").hide("slow");
      }, 2000);
    },
    miniCart: function () {
      $(document).ready(function ($) {
        var $body = $("body");

        $(".cart-button, .close-button, #sidebar-cart-curtain").click(function (e) {
          e.preventDefault();

          $body.toggleClass("show-sidebar-cart");

          if ($("#sidebar-cart-curtain").is(":visible")) {
            $("#sidebar-cart-curtain").fadeOut(500);
          } else {
            $("#sidebar-cart-curtain").fadeIn(500);
          }
        });
      });
    },
    searchInput: function () {
      if ($(".input-box").length) {
        let inputBox = document.querySelector(".input-box"),
          searchIcon = document.querySelector(".search"),
          closeIcon = document.querySelector(".close-icon");

        // ---- ---- Open Input ---- ---- //
        searchIcon.addEventListener("click", () => {
          inputBox.classList.add("open");
        });
        // ---- ---- Close Input ---- ---- //
        closeIcon.addEventListener("click", () => {
          inputBox.classList.remove("open");
        });
      }
    },
    showReview: function () {
      $(".review-btn").on("click", function () {
        var id = $(this).attr("data-atr");
        $(".review-block").hide("slow");
        $("#" + id).show("slow");
        console.log(id);
      });
    },
    quantityHandle: function () {
      $(".decrement").on("click", function () {
        var qtyInput = $(this).closest(".quantity-wrap").children(".number");
        var qtyVal = parseInt(qtyInput.val());
        if (qtyVal > 0) {
          qtyInput.val(qtyVal - 1);
        }
      });
      $(".increment").on("click", function () {
        var qtyInput = $(this).closest(".quantity-wrap").children(".number");
        var qtyVal = parseInt(qtyInput.val());
        qtyInput.val(parseInt(qtyVal + 1));
      });
    },
    initializeSlick: function (e) {
      if ($(".teams-slider").length) {
        $(".teams-slider").slick({
          infinite: true,
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: false,
          centerPadding: "0",
          infinite: true,
          cssEase: "linear",
          autoplay: true,
          autoplaySpeed: 3000,
        });
      }
      if ($(".product-slider").length) {
        $(".product-slider").slick({
          infinite: true,
          slidesToShow: 4,
          slidesToScroll: 1,
          arrows: false,
          centerPadding: "0",
          infinite: true,
          cssEase: "linear",
          autoplay: true,
          autoplaySpeed: 2000,
          responsive: [
            {
              breakpoint: 1599,
              settings: {
                slidesToShow: 3,
              },
            },
            {
              breakpoint: 992,
              settings: {
                slidesToShow: 2,
                arrows: false,
              },
            },
            {
              breakpoint: 650,
              settings: {
                slidesToShow: 1,
              },
            },
          ],
        });
      }
    },
    videoPlay: function () {
      $(".video .play-btn").on("click", function () {
        $(".video .img-box").hide("slow");
        $(".video .video-box").show("slow");
      });
    },
    sizeChange: function () {
      $(".size li").on("click", function () {
        $("li").removeClass("active");
        $(this).addClass("active");
      });
    },
    colorChange: function () {
      $(".color li").on("click", function () {
        $("li").removeClass("coloractive");
        $(this).addClass("coloractive");
      });
    },
    formValidation: function () {
      if ($(".contact-form").length) {
        $(".contact-form").validate();
      }
    },
    contactForm: function () {
      $(".contact-form").on("submit", function (e) {
        e.preventDefault();
        if ($(".contact-form").valid()) {
          var _self = $(this);
          _self
            .closest("div")
            .find('button[type="submit"]')
            .attr("disabled", "disabled");
          var data = $(this).serialize();
          $.ajax({
            url: "./assets/mail/contact.php",
            type: "post",
            dataType: "json",
            data: data,
            success: function (data) {
              $(".contact-form").trigger("reset");
              _self.find('button[type="submit"]').removeAttr("disabled");
              if (data.success) {
                document.getElementById("message").innerHTML =
                  "<h3 class='bg-success text-white p-3 mt-3'>Email Sent Successfully</h3>";
              } else {
                document.getElementById("message").innerHTML =
                  "<h3 class='bg-success text-white p-3 mt-3'>There is an error</h3>";
              }
              $("#message").show("slow");
              $("#message").slideDown("slow");
              setTimeout(function () {
                $("#message").slideUp("hide");
                $("#message").hide("slow");
              }, 3000);
            },
          });
        } else {
          return false;
        }
      });
    },
    modalPopup: function () {
      $(".modal-popup").on("click", function () {
        $(".newsletter-popup").animate({ opacity: "1" }, "slow", function () {
          $(this).css("z-index", 999);
        });
      });
      $(".close").on("click", function () {
        $(".newsletter-popup").animate({ opacity: "0" }, "slow", function () {
          $(this).css("z-index", -10);
        });
      });
    },
  };
  Init.i();
})(window, document, jQuery);
