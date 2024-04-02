"use strict";

function _typeof(obj) {
    "@babel/helpers - typeof";
    if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
        _typeof = function _typeof(obj) {
            return typeof obj;
        };
    } else {
        _typeof = function _typeof(obj) {
            return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
        };
    }
    return _typeof(obj);
}

function _classCallCheck(instance, Constructor) {
    if (!(instance instanceof Constructor)) {
        throw new TypeError("Cannot call a class as a function");
    }
}

function _defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
        var descriptor = props[i];
        descriptor.enumerable = descriptor.enumerable || false;
        descriptor.configurable = true;
        if ("value" in descriptor) descriptor.writable = true;
        Object.defineProperty(target, descriptor.key, descriptor);
    }
}

function _createClass(Constructor, protoProps, staticProps) {
    if (protoProps) _defineProperties(Constructor.prototype, protoProps);
    if (staticProps) _defineProperties(Constructor, staticProps);
    return Constructor;
}

function _inherits(subClass, superClass) {
    if (typeof superClass !== "function" && superClass !== null) {
        throw new TypeError("Super expression must either be null or a function");
    }
    subClass.prototype = Object.create(superClass && superClass.prototype, {
        constructor: {
            value: subClass,
            writable: true,
            configurable: true
        }
    });
    if (superClass) _setPrototypeOf(subClass, superClass);
}

function _setPrototypeOf(o, p) {
    _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
        o.__proto__ = p;
        return o;
    };
    return _setPrototypeOf(o, p);
}

function _createSuper(Derived) {
    var hasNativeReflectConstruct = _isNativeReflectConstruct();
    return function _createSuperInternal() {
        var Super = _getPrototypeOf(Derived), result;
        if (hasNativeReflectConstruct) {
            var NewTarget = _getPrototypeOf(this).constructor;
            result = Reflect.construct(Super, arguments, NewTarget);
        } else {
            result = Super.apply(this, arguments);
        }
        return _possibleConstructorReturn(this, result);
    };
}

function _possibleConstructorReturn(self, call) {
    if (call && (_typeof(call) === "object" || typeof call === "function")) {
        return call;
    }
    return _assertThisInitialized(self);
}

function _assertThisInitialized(self) {
    if (self === void 0) {
        throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
    }
    return self;
}

function _isNativeReflectConstruct() {
    if (typeof Reflect === "undefined" || !Reflect.construct) return false;
    if (Reflect.construct.sham) return false;
    if (typeof Proxy === "function") return true;
    try {
        Date.prototype.toString.call(Reflect.construct(Date, [], function () {
        }));
        return true;
    } catch (e) {
        return false;
    }
}

function _getPrototypeOf(o) {
    _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
        return o.__proto__ || Object.getPrototypeOf(o);
    };
    return _getPrototypeOf(o);
}

function _objectWithoutProperties(source, excluded) {
    if (source == null) return {};
    var target = _objectWithoutPropertiesLoose(source, excluded);
    var key, i;
    if (Object.getOwnPropertySymbols) {
        var sourceSymbolKeys = Object.getOwnPropertySymbols(source);
        for (i = 0; i < sourceSymbolKeys.length; i++) {
            key = sourceSymbolKeys[i];
            if (excluded.indexOf(key) >= 0) continue;
            if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue;
            target[key] = source[key];
        }
    }
    return target;
}

function _objectWithoutPropertiesLoose(source, excluded) {
    if (source == null) return {};
    var target = {};
    var sourceKeys = Object.keys(source);
    var key, i;
    for (i = 0; i < sourceKeys.length; i++) {
        key = sourceKeys[i];
        if (excluded.indexOf(key) >= 0) continue;
        target[key] = source[key];
    }
    return target;
}

$(document).ready(function () {
    $('.product-slider').each(function () {
        $(this).slick({
            dots: true,
            slidesToShow: 1
        });
    });
    $('.similar-slider').each(function () {
        var slider_prev = $(this).closest('section').find('.slick-prev');
        var slider_next = $(this).closest('section').find('.slick-next');
        $(this).slick({
            prevArrow: slider_prev,
            nextArrow: slider_next,
            swipe: false,
            draggable: false,
            slidesToShow: 4,
            responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3
                }
            }, {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2
                }
            }, {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1
                }
            }]
        });
    });
    $('.farming-gal').each(function () {
        var slider_prev = $(this).closest('section').find('.slick-prev');
        var slider_next = $(this).closest('section').find('.slick-next');
        $(this).slick({
            prevArrow: slider_prev,
            nextArrow: slider_next,
            swipe: false,
            draggable: false,
            slidesToShow: 3,
            responsive: [{
                breakpoint: 768,
                settings: {
                    slidesToShow: 2
                }
            }]
        });
    });

    $('.category-list').each(function () {
        $(this).slick({
            slidesToShow: 8,
            responsive: [{
                breakpoint: 860,
                settings: {
                    slidesToShow: 5
                }
            }, {
                breakpoint: 660,
                settings: {
                    slidesToShow: 4
                }
            }, {
                breakpoint: 460,
                settings: {
                    slidesToShow: 3
                }
            }, {
                breakpoint: 360,
                settings: {
                    slidesToShow: 2
                }
            }]
        });
    });
    $('.product-item__slider').each(function () {
        $(this).slick({
            dots: true,
            slidesToShow: 1,
            arrows: false
        });
    });
    $('.scroll_top').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });
    $('.tog-nav').on('click', function () {
        $(this).toggleClass('active');
        $('.navigation').slideToggle();
    }); // $('.tog-filter').on('click', function () {
    //     $('body').addClass('open_filter');
    // });
    $('.filter-close').on('click', function () {
        $('body').removeClass('open_filter');
    });
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.navigation, .tog-nav').length) {
            var bw = window.innerWidth;

            if (bw < 1024) {
                $('.navigation').slideUp();
                $('.tog-nav').removeClass('active');
            }
        }

        if (!$(e.target).closest('.filter, .tog-filter').length) {
            $('body').removeClass('open_filter');
        }

        if (!$(e.target).closest('.aside').length) {
            $('body').removeClass('open_aside');
        }

        e.stopPropagation();
    });
    if ($('#map-list').length > 0) {
        initMapList();
    }
    if ($('#map-product').length > 0) {
        initMap();
    }
    $(document).on('click', '.marker-price', function () {
        $('.marker-price').removeClass('active');
        $(this).addClass('active');
    }); // $(document).on('click', '.add-new-adr', function (e) {
    //     e.preventDefault();
    //     var n = $(this).closest('.wrap-new-adr').find('.wrap-new-adr__hide-item').length;
    //     n = n + 1;
    //     $(this).closest('.wrap-new-adr').find('.wrap-new-adr__hide').append('<div class="wrap-new-adr__hide-item append_item"><div class="form-description__item-title">Aдеса самовивозу </div><div class="form-horizontal"><div class="form-group half"> <input class="input_st" type="text" name="самовивоз' + n + '" placeholder="Адреса самовивозу" required="required" /></div><div class="form-group half"> <input class="input_st" type="text" name="Графік роботи' + n + '" placeholder="09:00 - 22:00" required="required" /></div></div> <div class="remove-adr">Видалити адресу</div></div>');
    // });
    $(document).on('click', '.remove-adr', function (e) {
        $(this).closest('.wrap-new-adr__hide-item.append_item').remove();
    });
    // $('.remove-file').click(function () {
    //     $(this).closest('.cabinet-item__photo-item').find('.upfile_product').val('');
    //     $(this).closest('.cabinet-item__photo-item').find('img').attr('src', '').removeClass('visible');
    // })

    $('.select_st').selectric({
        disableOnMobile: false,
        nativeOnMobile: false
    });
    $(document).on('change', '.check_all', function () {
        if ($(this).is(':checked')) {
            $('.check_all_sub').prop("checked", true);
            $('.select-all-link').slideDown();
        } else {
            $('.check_all_sub').prop("checked", false);
            $('.select-all-link').slideUp();
        }
    });
    $(document).on('change', '.check_has_sub', function () {
        if ($(this).is(':checked')) {
            $(this).closest('.filter-check__item').find('.filter-check__sub').slideDown();
        } else {
            $(this).closest('.filter-check__item').find('.filter-check__sub').slideUp();
            $(this).closest('.filter-check__item').find('.filter-check__sub input').prop("checked", false);
        }
    });
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
    }); // $('.counter_product').each(function () {
    //     var parent = $(this);
    //     var minus = $('.num_minus', parent);
    //     var pluss = $('.num_pluss', parent);
    //     var input = $('.counter_input', parent);
    //     if (input.val() == 2) {
    //         minus.removeClass('active');
    //     }
    //     pluss.click(function () {
    //         input.val(parseInt(input.val()) + 1).change();
    //         pluss.addClass('active');
    //     });
    //     minus.click(function () {
    //         if (input.val() > 1) {
    //             input.val(input.val() - 1).change();
    //         }
    //         if (input.val() == 1) {
    //             minus.removeClass('active');
    //         }
    //     });
    // });
    $(document).on('click', '.modal_open', function () {
        $.fancybox.close();
        var href_ = $(this).attr('href');
        $.fancybox.open($(this), {
            // closeBtn: false,
            // smallBtn: false,
            autoFocus: false,
            buttons: [],
            touch: false,
            hideScrollbar: false,
            swipe: false
        });
    });
    $('.js-collapse').on('click', '.js-collapse-title', function (event) {
        event.preventDefault();
        if ($(this).closest('.js-collapse-item').hasClass('active')) {
            $(this).closest('.js-collapse').find('.js-collapse-content').slideUp(400);
            $(this).closest('.js-collapse-item').removeClass('active');
        } else {
            $(this).closest('.js-collapse').find('.js-collapse-item').removeClass('active');
            $(this).closest('.js-collapse').find('.js-collapse-content').slideUp(400);
            $(this).closest('.js-collapse-item').addClass('active');
            $(this).next('.js-collapse-content').slideDown();
        }
    });
    $('.copy_link').on('click', function (e) {
        e.preventDefault();
        var copytext = document.createElement('input');
        copytext.value = window.location.href;
        document.body.appendChild(copytext);
        copytext.select();
        document.execCommand('copy');
        document.body.removeChild(copytext);
        $(this).addClass('active');
    });
    $(document).on('click', '.show_pass', function () {
        if ($(this).hasClass('active')) {
            $(this).closest('.form-group').find('input').attr('type', 'password');
        } else {
            $(this).closest('.form-group').find('input').attr('type', 'text');
        }

        $(this).toggleClass('active');
    });
    $(document).on('click', '.tog-hide-info', function () {
        if ($(this).hasClass('active')) {
            $(this).closest('.select-product__item-main').find('.select-product__item-hide').slideUp();
        } else {
            $(this).closest('.select-product__item-main').find('.select-product__item-hide').slideDown();
        }

        $(this).toggleClass('active');
    });
    $(document).on('click', '.tog-hide-order', function () {
        if ($(this).hasClass('active')) {
            $(this).closest('.orders-main__item').find('.orders-main__item-hide').slideUp();
        } else {
            $(this).closest('.orders-main__item').find('.orders-main__item-hide').slideDown();
        }

        $(this).toggleClass('active');
    });
    $(document).on('click', '.tog_hide_notification', function () {
        if ($(this).hasClass('active')) {
            $(this).closest('.notification-item').find('.notification-item__hide').slideUp();
        } else {
            $(this).closest('.notification-item').find('.notification-item__hide').slideDown();
        }

        $(this).toggleClass('active');
    }); // $('.js-range').each(function () {
    //     var this_ = $(this);
    //     var min = $(this).attr('data-min');
    //     var max = $(this).attr('data-max');
    //     var from_input = this_.closest('.filter-range').find('.js-input-from');
    //     var to_input = this_.closest('.filter-range').find('.js-input-to');
    //     var from = 0;
    //     var to = 0;
    //     this_.ionRangeSlider({
    //         hide_min_max: true,
    //         hide_from_to: true,
    //         // from: 2,
    //         onStart: function onStart(data) {
    //             from = data.from;
    //             to = data.to;
    //             from_input.val(from);
    //             to_input.val(to);
    //             // $('.range-grid__item').eq(from).addClass('active');
    //         },
    //         onChange: function onChange(data) {
    //             // $('.range-grid__item').removeClass('active');
    //             from = data.from;
    //             to = data.to;
    //             from_input.val(from);
    //             to_input.val(to);
    //             // $('.range-grid__item').eq(from).addClass('active');
    //         }
    //     });
    //     var instance = this_.data("ionRangeSlider");
    //     from_input.keyup(delay(function (e) {
    //         var val = $(this).val(); // validate
    //         var t_ = to_input.length;
    //         var val_m = 0;
    //         if (t_ > 0) {
    //             val_m = to_input.val()
    //         } else {
    //             val_m = Number(max);
    //         }
    //         if (Number(val) < Number(min)) {
    //             val = Number(min);
    //         } else if (val > Number(val_m)) {
    //             val = val_m;
    //         }
    //         $(this).val(val);
    //         instance.update({
    //             from: val
    //         });
    //     }, 500));
    //     to_input.keyup(delay(function (e) {
    //         var val1 = $(this).val(); // validate
    //         console.log(val1, from, to)
    //         var val_s = from_input.val()
    //         if (val1 < Number(val_s)) {
    //             val1 = Number(val_s);
    //         } else if (val1 > Number(max)) {
    //             val1 = Number(max);
    //         }
    //         $(this).val(val1);
    //         instance.update({
    //             to: val1
    //         });
    //     }, 500));
    // });
    $(document).on('click', '.js-tab-link', function (event) {
        event.preventDefault();
        var data_hreff = $(this).data('target');
        $(this).closest('.js-tab').find('.js-tab-link').removeClass('active');
        $(this).addClass('active');
        $(this).closest('.js-tab').find('.js-tab-item').hide().removeClass('active');
        $(this).closest('.js-tab').find('.js-tab-item[data-target="' + data_hreff + '"]').fadeIn().addClass('active');
    });
    jQuery.each(jQuery('textarea[data-autoresize]'), function () {
        var offset = this.offsetHeight - this.clientHeight;

        var resizeTextarea = function resizeTextarea(el) {
            jQuery(el).css('height', 'auto').css('height', el.scrollHeight + offset);
        };

        jQuery(this).on('keyup input', function () {
            resizeTextarea(this);
        }).removeAttr('data-autoresize');
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

function setInputFilter(textbox, inputFilter) {
    ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function (event) {
        textbox.bind(event, function () {
            if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            }
        });
    });
}

$(window).on('load resize scroll', function () {
    var bw = window.innerWidth;
    var bh = window.innerHeight; // if (bw < 768) {
    //     $('.aside-top').append($('.header-cabinet__btn'));
    //   } else {
    //     $('.header-cabinet').append($('.aside-top .header-cabinet__btn'));
    //   }

    var h = $('.header').outerHeight();
    $('main.content').css('margin-top', h);
    $('.filter').css('top', h);

    if ($(this).scrollTop() > 800) {
        $('.scroll_top').fadeIn();
    } else {
        $('.scroll_top').fadeOut();
    }

    if (bw < 660) {
        $('.navigation').append($('.header-tel'));
        $('.navigation').append($('.place-link'));
    } else {
        $('.navigation .header-tel').insertBefore($('.header-left .navigation'));
        $('.navigation .place-link').insertBefore($('.enter-link'));
    }

    if (bw < 861) {
        $('.product-group__right').insertBefore($('.product-description'));
        $('.order-group__right').insertBefore($('.order-form__bot'));
    } else {
        $('.product-group__left .product-group__right').insertAfter($('.product-group__left'));
        $('.order-group__left .order-group__right').insertAfter($('.order-group__left'));
    }

    if (bw < 1024) {
        $('.cart-group__right').insertBefore($('.cart-product__bot'));
    } else {
        $('.cart-group__left .cart-group__right').insertAfter($('.cart-group__left'));
    }

    var as_t = $('.aside-top').outerHeight();
    var as_b = $('.aside-bot').outerHeight();
    $('.aside-nav').css('max-height', bh - (as_t + as_b));
    var f_t = $('.filter-top').outerHeight();
    var f_b = $('.filter-bot ').outerHeight();
    $('.filter-list-wrap').css('max-height', bh - (as_t + as_b));

    if ($('.map-btn').length > 0) {
        if ($('.catalog-group .btn_center').length > 0) {
            if ($('.catalog-group .btn_center').isInViewport()) {
                $('.map-btn').fadeOut();
            } else {
                $('.map-btn').fadeIn();
            }
        } else {
        }
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
    var mapElement = document.getElementById('map-product'),
        map1Latitude = mapElement.getAttribute('data-lat'),
        map1Longtitude = mapElement.getAttribute('data-long'),
        mapPin = mapElement.getAttribute('data-pin');
    var map = new google.maps.Map(document.getElementById("map-product"), {
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

function initMapList() {
    var lastMapCenter = localStorage.getItem('lastMapCenter');
    var mapElement = document.getElementById('map-list'),
        map1Latitude = mapElement.getAttribute('data-lat'),
        map1Longtitude = mapElement.getAttribute('data-long'),
        saveCenter = mapElement.getAttribute('data-save-center') || '',
        clusterPin = mapElement.getAttribute('data-cluster'),
        json = mapElement.getAttribute('data-json'),
        mapId = mapElement.getAttribute('data-map'),
        map;

    if (lastMapCenter && saveCenter === 'true') {
        lastMapCenter = JSON.parse(lastMapCenter);
        map1Latitude = lastMapCenter.lat;
        map1Longtitude = lastMapCenter.lng;
    }

    var gmarkers1 = [];
    var posArr = [];
    var infoWindows = [];
    var marker1;
    var center = new google.maps.LatLng(map1Latitude, map1Longtitude);
    $.get(json, function (data) {
        var mapOptions = {
            zoom: 14,
            styles: style,
            center: center,
            scrollwheel: true
        };
        var lastMapZoom = localStorage.getItem('lastMapZoom');
        if (lastMapZoom) {
            mapOptions.zoom = parseInt(lastMapZoom);
        }
        map = new google.maps.Map(document.getElementById('map-list'), mapOptions);

        for (var i = 0; i < data.length; i++) {
            addMarker(data[i], map);
        }

        function addMarker(data, map) {
            // var category = marker[4];
            var title = data.title;
            var link = data.link;
            var pos = new google.maps.LatLng(data.lat, data.lng);
            var date_ = data.date;
            var subtitle = data.subtitle;
            var delivery = data.delivery;
            var place = data.place;
            var distance = data.distance;
            var price = data.price;
            var price_value = data.price_value;
            var reviews = data.reviews;
            var rating = data.rating;
            var shipping = data.shipping;
            var sellerLink = data.seller_link;
            var stock = data.stock;
            var order = data.order;
            var verified = data.verified;
            var ids = data.id;
            var slider = data.slider;
            var favorites = data.favorites;
            var labels_html = data.labels_html;
            var priceTag = document.createElement("div");
            var text_ = [];
            var t_a = [];
            var n_ = title.length;
            var t_ = '';
            var verified_html = "";
            var img_ = [];
            var img_slider = [];
            var content = '';
            console.log(labels_html)
            for (var i = 0; i < n_; i++) {
                var str_slider = "";
                var s_n = slider[i].length;

                for (var j = 0; j < s_n; j++) {
                    var img_html = '<div><img src="' + slider[i][j] + '" alt=""></div>';
                    img_.push(img_html);
                    str_slider += img_html;
                }

                if (verified[i] === true) {
                    verified_html = '<div class="iw-product-verified">' + verifiedSvg() + '</div>';
                }

                console.log(labels_html[i])
                t_ = '<div class="iw-wrap">' +
                    labels_html[i] +
                    '<div class="iw-container-slide">' +
                    '<div class="iw-container">' +
                    '<div class="iw-product-media">' +
                    '<a data-id="' + ids[i] + '" class="product-item__favorite add-to-favorite ' + favorites[i] + '" href="#">' + favoriteSvg() + '</a>' +
                    '<div class="product-item__slider">' + str_slider + '</div>' + '</div>' +
                    '<div class="iw-content">' + '<a class="product-item__title" href="' + link[i] + '">' + title[i] +  '</a>' +
                    '<a href="' + sellerLink[i] + '" target="_blank" class="iw-product-subtitle">' + subtitle[i] + verified_html + '</a>' +
                    '<ul class="product-item__reviews"> <li class="reviews-rating"> <strong>' + rating[i] + '</strong> </li><li>' + reviews[i] + '</li>' + '</ul>' +
                    '<ul class="iw-product-place"> <li>' + place + '</li><li>' + distance + '</li></ul>' +

                    '<div class="product-item__bot">' +
                    '<div class="product-item__price"> <strong>' + price[i] + ' </strong>' + price_value[i] + '</div>' +

                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                t_a.push(t_);
            }

            var t_t = "";
            $.each(t_a, function (e) {
                t_t += t_a[e];
            });
            content = '<div class="iw-slider">' + t_t + '</div>';
            priceTag.className = "price-tag";
            priceTag.textContent = title;

            if (n_ > 1) {
                marker1 = createHTMLMapMarker({
                    latlng: pos,
                    map: map,
                    html: '<div class="marker-price">' + n_ + '</div>'
                });
            } else {
                marker1 = createHTMLMapMarker({
                    latlng: pos,
                    map: map,
                    html: '<div class="marker-price">' + price + ' ' + price_value + '</div>'
                });
            }

            gmarkers1.push(marker1); // Marker click listener

            var infowindow = new google.maps.InfoWindow({

                maxWidth: 700
            });
            infoWindows.push(infowindow);

            google.maps.event.addListener(marker1, 'click', function (marker1, content) {
                return function () {
                    for (var i = 0; i < infoWindows.length; i++) {
                        infoWindows[i].close();
                    }

                    infowindow.setContent(content);
                    infowindow.open(map, marker1);
                    setTimeout(function () {
                        $('.iw-product-media .product-item__slider').slick({
                            dots: true,
                            slidesToShow: 1,
                            arrows: false
                        });
                        $('.iw-slider').slick({
                            dots: false,
                            slidesToShow: 1,
                            swipe: false,
                            draggable: false // arrows: false,

                        });
                        if ($(document).find('.iw-product-media .product-item__slider.slick-slider').length > 0) {
                            // $(document).find('.iw-product-media .product-item__slider.slick-slider').slick('setPosition');
                        }
                        $('.iw-slider').slick('setPosition');
                    }, 10);
                    var pos = this.getPosition();
                    var lat = pos.lat();
                    var lng = pos.lng();
                    map.panTo(pos);
                    localStorage.setItem('lastMapCenter', JSON.stringify({lat: lat, lng: lng}));
                };
            }(marker1, content));
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.marker-price, .iw-wrap, .iw-slider').length) {
                    $('.marker-price').removeClass("active");
                    infowindow.close();
                }

                e.stopPropagation();
            });
        }

        new MarkerClusterer(map, gmarkers1, {
            styles: [{
                url: clusterPin,
                textColor: '#EBAB03',
                height: 60,
                width: 60,
                textSize: 18
            }]
        });

        map.addListener("center_changed", function () {
            var center = map.getCenter();
            var lat = center.lat();
            var lng = center.lng();
            localStorage.setItem('lastMapCenter', JSON.stringify({lat: lat, lng: lng}));
        });
        map.addListener("bounds_changed", function () {
            var center = map.getCenter();
            var lat = center.lat();
            var lng = center.lng();
            localStorage.setItem('lastMapCenter', JSON.stringify({lat: lat, lng: lng}));
        });
        google.maps.event.addListener(map, 'zoom_changed', function () {
            // Отримання поточного значення зуму
            var zoom = map.getZoom();

            // Збереження зуму у localStorage
            localStorage.setItem('lastMapZoom', zoom);
        });


    }, 'json');
}

$.fn.isInViewport = function () {
    var elementTop = $(this).offset().top;
    var elementBottom = elementTop + $(this).outerHeight();
    var viewportTop = $(window).scrollTop() + 50;
    var viewportBottom = viewportTop + $(window).height();
    return elementBottom > viewportTop && elementTop < viewportBottom;
};

var createHTMLMapMarker = function createHTMLMapMarker(_ref) {
    var _ref$OverlayView = _ref.OverlayView,
        OverlayView = _ref$OverlayView === void 0 ? google.maps.OverlayView : _ref$OverlayView,
        args = _objectWithoutProperties(_ref, ["OverlayView"]);

    var HTMLMapMarker = /*#__PURE__*/function (_OverlayView) {
        _inherits(HTMLMapMarker, _OverlayView);

        var _super = _createSuper(HTMLMapMarker);

        function HTMLMapMarker() {
            var _this;

            _classCallCheck(this, HTMLMapMarker);

            _this = _super.call(this);
            _this.latlng = args.latlng;
            _this.html = args.html;

            _this.setMap(args.map);

            return _this;
        }

        _createClass(HTMLMapMarker, [{
            key: "createDiv",
            value: function createDiv() {
                var _this2 = this;

                this.div = document.createElement("div");
                this.div.style.position = "absolute";

                if (this.html) {
                    this.div.innerHTML = this.html;
                }

                google.maps.event.addDomListener(this.div, "click", function (event) {
                    google.maps.event.trigger(_this2, "click");
                });
            }
        }, {
            key: "appendDivToOverlay",
            value: function appendDivToOverlay() {
                var panes = this.getPanes();
                panes.overlayImage.appendChild(this.div);
            }
        }, {
            key: "positionDiv",
            value: function positionDiv() {
                var point = this.getProjection().fromLatLngToDivPixel(this.latlng);
                var offset = 0;
                var offset_l = 35;

                if (point) {
                    this.div.style.left = "".concat(point.x - offset_l, "px");
                    this.div.style.top = "".concat(point.y - offset, "px");
                }
            }
        }, {
            key: "draw",
            value: function draw() {
                if (!this.div) {
                    this.createDiv();
                    this.appendDivToOverlay();
                }

                this.positionDiv();
            }
        }, {
            key: "remove",
            value: function remove() {
                if (this.div) {
                    this.div.parentNode.removeChild(this.div);
                    this.div = null;
                }
            }
        }, {
            key: "getPosition",
            value: function getPosition() {
                return this.latlng;
            }
        }, {
            key: "getDraggable",
            value: function getDraggable() {
                return false;
            }
        }]);

        return HTMLMapMarker;
    }(OverlayView);

    return new HTMLMapMarker();
};

function ratingSvg() {
    return '<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 12 11.2" viewBox="0 0 12 11.2"><path d="M12 4.2c-.1-.2-.3-.4-.5-.4L8 3.5 6.6.4C6.5.1 6.3 0 6 0s-.5.1-.6.4L4 3.5l-3.4.3c-.3 0-.5.2-.6.4 0 .3 0 .5.2.7l2.6 2.2-.8 3.3c-.1.2 0 .5.2.6.1.1.2.1.4.1.1 0 .2 0 .3-.1l3-1.7 3 1.7c.2.1.5.1.7 0 .2-.1.3-.4.2-.6l-.6-3.3 2.6-2.2c.2-.2.2-.4.2-.7z" style="fill:#ffc327"></path></svg>';
}

function favoriteSvg() {
    return '<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 659.3 578.6" viewBox="0 0 659.3 578.6"><path d="m78 325 231.8 217.7c8 7.5 12 11.2 16.7 12.2 2.1.4 4.3.4 6.4 0 4.7-.9 8.7-4.7 16.7-12.2L581.3 325c65.2-61.3 73.1-162.1 18.3-232.7L589.3 79C523.7-5.6 392 8.6 345.9 105.2c-6.5 13.6-25.9 13.6-32.4 0C267.4 8.6 135.7-5.6 70.1 79L59.7 92.3C4.9 163 12.8 263.8 78 325z" style="fill:none;stroke:#fff;stroke-width:46.6667;stroke-miterlimit:133.3333"></path></svg>';
}

function verifiedSvg() {
    return '<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 15 15" viewBox="0 0 15 15"><path d="M8.2.3C8 .1 7.8 0 7.5 0c-.3 0-.5.1-.7.3l-.9.9h-.2L4.5.9c-.2-.1-.5 0-.7.1-.3.1-.5.4-.5.6l-.4 1.3V3h-.1l-1.2.3c-.2 0-.5.2-.6.4-.1.3-.2.6-.1.8l.3 1.2v.2l-.9.9c-.2.2-.3.4-.3.7 0 .3.1.5.3.7l.9.9v.2l-.3 1.2c-.1.3 0 .5.1.8.1.2.4.4.6.5l1.2.3h.1v.1l.3 1.2c.1.3.2.5.5.6.2.1.5.2.8.1l1.2-.3h.2l.9.9c.2.2.4.3.7.3.3 0 .5-.1.7-.3l.9-.9h.2l1.2.3c.3.1.5 0 .8-.1.2-.1.4-.4.5-.6l.3-1.2v-.1h.1l1.2-.3c.3-.1.5-.2.6-.5.1-.2.2-.5.1-.8l-.3-1.2v-.2l.9-.9c.2-.2.3-.4.3-.7 0-.3-.1-.5-.3-.7l-.9-.9v-.2l.3-1.2c.1-.3 0-.5-.1-.8-.1-.2-.4-.4-.6-.5l-1.2-.3h-.1v-.1l-.3-1.2c-.1-.3-.2-.5-.5-.6-.2-.1-.5-.2-.8-.1l-1.3.3H9L8.2.3zm-1.8 10c.1 0 .2 0 .3-.1L10.9 6c.2-.2.2-.6 0-.8l-.3-.3c-.2-.2-.6-.2-.8 0L6.4 8.4 5.1 7.1c-.2-.2-.6-.2-.8 0l-.2.2c-.2.2-.2.6 0 .8l2 2.1c.1 0 .2.1.3.1z" style="fill-rule:evenodd;clip-rule:evenodd;fill:#4d76ff"></path></svg>';
}