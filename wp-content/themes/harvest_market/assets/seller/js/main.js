"use strict";

$(document).ready(function () {
  $('.open_filter_seller').on('click', function (e) {
    e.preventDefault();
    $('body').addClass('open_filter_sel');
  });
  $('.filter-close').on('click', function () {
    $('body').removeClass('open_filter_sel');
  });
  $(document).on('click', function (e) {
    if (!$(e.target).closest('.info-catalog__filter-wrap, .open_filter_seller').length) {
      $('body').removeClass('open_filter_sel');
    }

    e.stopPropagation();
  });
  $('.head-slider ').slick({
    infinite: true,
    adaptiveHeight: true,
    prevArrow: '<button type="button" class="slick-prev"><svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 8.2 15" viewBox="0 0 8.2 15"><path d="M.2 7c-.3.3-.3.8 0 1.1l6.7 6.7c.3.3.8.3 1.1 0 .3-.3.3-.8 0-1.1L1.8 7.5 8 1.3c.3-.3.3-.8 0-1.1-.3-.3-.8-.3-1.1 0L.2 7z" style="fill:#fff"/></svg></button>',
    nextArrow: '<button type="button" class="slick-next"><svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 8.2 15" viewBox="0 0 8.2 15"><path d="M8 8c.3-.3.3-.7 0-1L1.3.2C1-.1.5-.1.2.2c-.3.3-.3.8 0 1.1l6.2 6.2-6.2 6.2c-.3.3-.3.8 0 1.1.3.3.8.3 1.1 0L8 8z" style="fill:#fff"/></svg></button>',
    dots: true,
    speed: 500,
    fade: true,
    cssEase: 'linear',
    autoplay: true,
    autoplaySpeed: 3000
  });
  $('.product-item__slider').each(function () {
    $(this).slick({
      dots: true,
      slidesToShow: 1,
      arrows: false
    });
  });
  $('.filter-list').on('click', '.filter-list__item-title', function (event) {
    event.preventDefault();

    if ($(this).closest('.filter-list__item').hasClass('active')) {
      $(this).closest('.filter-list__item').find('.filter-list__item-content').slideUp(400);
      $(this).closest('.filter-list__item').removeClass('active');
    } else {
      $(this).closest('.filter-list__item').addClass('active');
      $(this).closest('.filter-list__item').find('.filter-list__item-content').slideDown();
    }
  });
  $('.scroll_top').click(function () {
    $('body,html').animate({
      scrollTop: 0
    }, 800);
    return false;
  });
  $('.select_st').selectric({
    disableOnMobile: false,
    nativeOnMobile: false
  });
  $('.counter_product').each(function () {
    var parent = $(this);
    var minus = $('.num_minus', parent);
    var pluss = $('.num_pluss', parent);
    var input = $('.counter_input', parent);

    if (input.val() == 2) {
      minus.removeClass('active');
    }

    pluss.click(function () {
      input.val(parseInt(input.val()) + 1).change();
      pluss.addClass('active');
    });
    minus.click(function () {
      if (input.val() > 1) {
        input.val(input.val() - 1).change();
      }

      if (input.val() == 1) {
        minus.removeClass('active');
      }
    });
  });
  $('.scroll_top').click(function () {
    $('body,html').animate({
      scrollTop: 0
    }, 800);
    return false;
  });

  if ($('#seller-map').length > 0) {
    initMap();
  }

  $('.more-text').click(function (e) {
    if ($(this).hasClass('active')) {
      $(this).find('span').text($(this).attr('data-text'));
    } else {
      $(this).find('span').text($(this).attr('data-show'));
    }

    $(this).toggleClass('active');
    e.preventDefault();
    $(this).parent().find('.hidden-text').slideToggle();
  });
  $(document).on("mouseenter", "label.rating-item", function () {
    $(this).addClass("prev").removeClass("next").nextAll().addClass("next"), $(this).prevAll().addClass("prev").removeClass("next"), $(this).nextAll().removeClass("prev");
  });
  $(document).on("mouseleave", "label.rating-item", function () {
    $(this).prevAll().removeClass("next"), $(this).prevAll().removeClass("prev"), $(this).nextAll().removeClass("prev"), $(this).nextAll().removeClass("next"), $(this).removeClass("next"), $(this).removeClass("prev");
  });
  $(document).on("click", "label.rating-item", function () {
    $("label.rating-item").removeClass("active__prev"), $("label.rating-item").removeClass("active"), $("label.rating-item").removeClass("next"), $(this).addClass("active").prevAll().addClass("active__prev");
  });
  $(" .search-input").focus(function () {
    $(this).addClass('active focus');
  }).blur(function () {
    if ($(this).val() == "") {
      $(this).removeClass('active');
    }

    $(this).removeClass('focus');
  });
});

function delay(callback, ms) {
  var timer = 0;
  return function () {
    var context = this,
        args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}

$(window).on('load resize scroll', function () {
  var bw = window.innerWidth;
  var bh = window.innerHeight;

  if ($(this).scrollTop() > 800) {
    $('.scroll_top').fadeIn();
  } else {
    $('.scroll_top').fadeOut();
  }
});
var style = [{
  "featureType": "landscape.man_made",
  "elementType": "geometry",
  "stylers": [{
    "color": "#f7f1df"
  }]
}, {
  "featureType": "landscape.natural",
  "elementType": "geometry",
  "stylers": [{
    "color": "#d0e3b4"
  }]
}, {
  "featureType": "landscape.natural.terrain",
  "elementType": "geometry",
  "stylers": [{
    "visibility": "off"
  }]
}, {
  "featureType": "poi",
  "elementType": "labels",
  "stylers": [{
    "visibility": "off"
  }]
}, {
  "featureType": "poi.business",
  "elementType": "all",
  "stylers": [{
    "visibility": "off"
  }]
}, {
  "featureType": "poi.medical",
  "elementType": "geometry",
  "stylers": [{
    "color": "#fbd3da"
  }]
}, {
  "featureType": "poi.park",
  "elementType": "geometry",
  "stylers": [{
    "color": "#bde6ab"
  }]
}, {
  "featureType": "road",
  "elementType": "geometry.stroke",
  "stylers": [{
    "visibility": "off"
  }]
}, {
  "featureType": "road",
  "elementType": "labels",
  "stylers": [{
    "visibility": "off"
  }]
}, {
  "featureType": "road.highway",
  "elementType": "geometry.fill",
  "stylers": [{
    "color": "#ffe15f"
  }]
}, {
  "featureType": "road.highway",
  "elementType": "geometry.stroke",
  "stylers": [{
    "color": "#efd151"
  }]
}, {
  "featureType": "road.arterial",
  "elementType": "geometry.fill",
  "stylers": [{
    "color": "#ffffff"
  }]
}, {
  "featureType": "road.local",
  "elementType": "geometry.fill",
  "stylers": [{
    "color": "black"
  }]
}, {
  "featureType": "transit.station.airport",
  "elementType": "geometry.fill",
  "stylers": [{
    "color": "#cfb2db"
  }]
}, {
  "featureType": "water",
  "elementType": "geometry",
  "stylers": [{
    "color": "#a2daf2"
  }]
}];

function initMap() {
  var mapElement = document.getElementById('seller-map'),
      map1Latitude = mapElement.getAttribute('data-lat'),
      map1Longtitude = mapElement.getAttribute('data-long'),
      mapPin = mapElement.getAttribute('data-pin');
  var map = new google.maps.Map(document.getElementById("seller-map"), {
    zoom: 10,
    styles: style,
    center: {
      lat: Number(map1Latitude),
      lng: Number(map1Longtitude)
    },
    disableDefaultUI: true
  });
  var beachMarker = new google.maps.Marker({
    position: {
      lat: Number(map1Latitude),
      lng: Number(map1Longtitude)
    },
    map: map,
    icon: mapPin
  });
}