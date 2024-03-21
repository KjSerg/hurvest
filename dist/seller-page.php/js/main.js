"use strict";

$(document).ready(function () {
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
    nativeOnMobile: false,
    onChange: function onChange(element) {// var label_val = $(this).closest('.wrap_select').find('span.label').text();
      // $(this).closest('.wrap_select').find('.hide_input').val(label_val);
    }
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
  $('.js-range').each(function () {
    var this_ = $(this);
    var min = $(this).attr('data-min');
    var max = $(this).attr('data-max');
    var from_input = this_.closest('.filter-range').find('.js-input-from');
    var to_input = this_.closest('.filter-range').find('.js-input-to');
    var from = 0;
    var to = 0;
    this_.ionRangeSlider({
      hide_min_max: true,
      hide_from_to: true,
      // from: 2,
      onStart: function onStart(data) {
        from = data.from;
        to = data.to;
        from_input.val(from);
        to_input.val(to); // $('.range-grid__item').eq(from).addClass('active');
      },
      onChange: function onChange(data) {
        // $('.range-grid__item').removeClass('active');
        from = data.from;
        to = data.to;
        from_input.val(from);
        to_input.val(to); // $('.range-grid__item').eq(from).addClass('active');
      }
    });
    var instance = this_.data("ionRangeSlider");
    from_input.keyup(delay(function (e) {
      var val = $(this).val(); // validate

      var t_ = to_input.length;
      var val_m = 0;

      if (t_ > 0) {
        val_m = to_input.val();
      } else {
        val_m = Number(max);
      }

      if (Number(val) < Number(min)) {
        val = Number(min);
      } else if (val > Number(val_m)) {
        val = val_m;
      }

      $(this).val(val);
      instance.update({
        from: val
      });
    }, 500));
    to_input.keyup(delay(function (e) {
      var val1 = $(this).val(); // validate

      console.log(val1, from, to);
      var val_s = from_input.val();

      if (val1 < Number(val_s)) {
        val1 = Number(val_s);
      } else if (val1 > Number(max)) {
        val1 = Number(max);
      }

      $(this).val(val1);
      instance.update({
        to: val1
      });
    }, 500));
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