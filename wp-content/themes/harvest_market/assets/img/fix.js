var doc = document;
var $doc = $(doc);
var load = false;
var loading = false;
var w = window;
var $w = $(w);
var parser = new DOMParser();
var $_body = $('body');
var dt = new DataTransfer();
var cart = {};
var correspondence = 0;
var storage = {};

$doc.ready(function () {
    setCorrespondenceID();
    addingToBuffer();
    setCart();
    countCartItems();
    getUserPosition();
    commentNavInit();
    initTriggerOnSelect();
    setUserCity();
    cf7Init();
    initSubCategories();
    initCorrespondence();
    initCorrespondenceReload();
    checkingNotificationsSchedules();
    checkingStorage();
    $doc.on('click', '.move-to-element', function (e) {
        e.preventDefault();
        var $t = $(this);
        var href = $t.attr('href');
        var $element = $doc.find(href);
        if ($element.length === 0) return;
        $('html, body').animate({
            scrollTop: $element.offset().top - 50
        }, 500);
    });
    $doc.on('click', '.filter-check-input', function (e) {
        var $t = $(this);
        var $form = $t.closest('form');
        $form.trigger('submit');
    });
    $doc.on('input', '.user-color-input', function (e) {
        var $t = $(this);
        var color = $t.val();
        $doc.find('.content section').css('background-color', color);
    });
    $doc.on('change', '.user-color-input', function (e) {
        var $t = $(this);
        var color = $t.val();
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                'action': 'change_user_color',
                'color': color,
            }
        }).done(function (r) {
            console.log(r);
        });
    });
    $doc.on('click', '.more-text-btn', function (e) {
        e.preventDefault();
        var $t = $(this);
        if ($t.hasClass('active')) {
            $t.find('span').text($t.attr('data-text'));
        } else {
            $t.find('span').text($t.attr('data-show'));
        }
        $t.toggleClass('active');
        $t.parent().find('.hidden-text').slideToggle();
        $t.closest('.testimonials-item__text').find('.preview-text').slideToggle();
    });
    $doc.on('click', '.comment-navigation .nav-previous a', function (e) {
        e.preventDefault();
        if (load) return;
        var $t = $(this);
        var href = $t.attr('href');
        var $commentlist = $doc.find(".commentlist");
        var $pagination = $doc.find('.comment-navigation');
        load = true;
        $pagination.addClass('not-active');
        showPreloader();
        $.ajax({
            type: 'GET',
            url: href,
        }).done(function (r) {
            hidePreloader();
            var $requestBody = $(parser.parseFromString(r, "text/html"));
            $commentlist.append($requestBody.find('.commentlist').html());
            $pagination.html($requestBody.find('.comment-navigation').html());
            load = false;
            $pagination.removeClass('not-active');
            commentNavInit();
        });
    });
    $doc.on('click', '.add-tag-more-js', function (e) {
        e.preventDefault();
        var $t = $(this);
        var $wrapper = $t.closest(".form-group");
        var $field = $wrapper.find('.content-field');
        var val = $field.val();
        var id = $field.attr('id');
        if (id === undefined) {
            id = 'content-field' + $field.index();
            $field.attr('id', id);
        }
        replaceString($field, getPosInRow(document.getElementById(id)));
    });
    $doc.on('click', '.add-to-cart', generateCheckoutLink);
    $doc.on('click', '.show_tel', function (e) {
        e.preventDefault();
        var $t = $(this);
        var id = $t.attr('data-id');
        var $wrapper = $t.closest('.product-card__btn');
        if (id === undefined) return;
        showPreloader();
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                action: 'get_phone_numbers',
                id: id
            }
        }).done(function (r) {
            if (!r) return;
            $t.remove();
            $wrapper.append(r);
            hidePreloader();
        });
    });
    $doc.on('click', '.show-user-tel', function (e) {
        e.preventDefault();
        var $t = $(this);
        var id = $t.attr('data-user-id');
        var $wrapper = $t.closest('.btn-wrapper');
        if (id === undefined) return;
        showPreloader();
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                action: 'get_phone_numbers',
                user: id
            }
        }).done(function (r) {
            if (!r) return;
            $t.remove();
            $wrapper.append(r);
            hidePreloader();
        });
    });
    $doc.on('click', '.select-user-city-js', function (e) {
        e.preventDefault();
        var $t = $(this);
        var val = $t.attr('data-value') || '';
        var latitude = $t.attr('data-latitude') || '';
        var longitude = $t.attr('data-longitude') || '';
        var $form = $t.closest('form');
        var $input = $form.find('.products-places-autocomplete');
        $input.val(val);
        $form.find('.products-places-list').html('');
        $form.find('.confirm-city').attr('data-city', val);
        $form.find('.confirm-city').attr('data-lat', latitude);
        $form.find('.confirm-city').attr('data-lon', longitude);
        $form.find('.confirm-city').removeClass('hidden');
        if (latitude && longitude) {
            setCookie('latitude', latitude, 1);
            setCookie('longitude', longitude, 1);
        }
    });
    $doc.on('click', '.select-filter-address-js', function (e) {
        e.preventDefault();
        var $t = $(this);
        var val = $t.attr('data-value') || '';
        var $form = $t.closest('form');
        var $input = $form.find('.filter-place-input');
        $input.val(val);
        $form.find('.products-places-list').html('');
        if (val === '') {
            var $from_input = $form.find('.js-input-radius');
            $from_input.removeAttr('name');
        }
        $form.trigger('submit');
    });
    $doc.on('submit', '.filter-form', function (e) {
        e.preventDefault();
        var $t = $(this);
        setFilterSerialize($t);
        var serialize = $t.serialize();
        var action = $t.attr('action');
        var method = $t.attr('method');
        renderCatalog({
            url: action + '?' + serialize,
            addEntry: true
        });
    });
    $doc.on('click', '.confirm-city', function (e) {
        e.preventDefault();
        var $button = $(this);
        var user_confirm_city_old = getCookie('user_confirm_city') || '';
        var city = $button.attr('data-city') || '';
        var lat = $button.attr('data-lat') || '';
        var lon = $button.attr('data-lon') || '';
        $doc.find('.confirmed-city-js').text(city.trim());
        setCookie('user_confirm_city', city, 7);
        $.fancybox.close();
        if (lat && lon) {
            setCookie('latitude', lat, 1);
            setCookie('longitude', lon, 1);
        }
        if (user_confirm_city_old !== city) {
            showPreloader();
            window.location.reload();
        }
    });
    $doc.on('change', '.trigger-on-select', function (e) {
        e.preventDefault();
        var $t = $(this);
        triggeredOnSelect($t);
    });
    $doc.on('click', '.remove-cart', function (e) {
        e.preventDefault();
        if ($(this).hasClass('remove-correspondence')) return;
        if ($(this).hasClass('remove-notification')) return;
        if ($(this).hasClass('clear-favorites')) return;
        cart = {};
        setCookie('cart', JSON.stringify(cart), 365);
        countCartItems();
        reInitCart();
    });
    $doc.on('click', '.remove-correspondence', function (e) {
        e.preventDefault();
        var $t = $(this);
        var correspondence = $t.attr('data-id');
        showPreloader();
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                'action': 'remove_correspondence',
                'correspondence': correspondence,
            }
        }).done(function (r) {
            window.location.reload();
        });
    });
    $doc.on('click', '.clear-favorites', function (e) {
        e.preventDefault();
        var $t = $(this);
        showPreloader();
        setCookie('favorites', '', 365);
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                action: 'set_user_favorites',
            }
        }).done(function (r) {
            window.location.reload();
        });
    });
    $doc.on('click', '.remove-notification', function (e) {
        e.preventDefault();
        var $t = $(this);
        var notification = $t.attr('data-id');
        showPreloader();
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                'action': 'remove_notification',
                'notification': notification,
            }
        }).done(function (r) {
            hidePreloader();
            $t.closest('.notification-item').remove();
        });
    });
    $doc.on('click', '.remove-notifications', function (e) {
        e.preventDefault();
        var $t = $(this);
        var notification = $t.attr('data-id');
        showPreloader();
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                'action': 'remove_notification',
            }
        }).done(function (r) {
            hidePreloader();
            $doc.find('.notification-item').remove();
            $doc.find('.notification').html(r);
        });
    });
    $doc.on('click', '.cart-product__item-remove', function (e) {
        e.preventDefault();
        var $t = $(this);
        var id = $t.attr('data-id');
        if (id === undefined) return;
        delete cart[id];
        setCookie('cart', JSON.stringify(cart), 365);
        countCartItems();
        reInitCart();
    });
    $doc.on('click', '.pagination-js a', catalogLinkRender);
    $doc.on('change', '.trigger-submit-on-change', function (e) {
        $(this).closest('form').trigger('submit');
    });
    $doc.on('click', '.add-to-favorite', function (e) {
        e.preventDefault();
        var $t = $(this);
        var id = $t.attr('data-id');
        var $selector = $doc.find('.add-to-favorite[data-id="' + id + '"]');
        if (id === undefined) return;
        var favorites = getCookie('favorites');
        if (favorites) {
            favorites = favorites.split(',');
            var index = favorites.indexOf(id);
            if (index > -1) {
                favorites.splice(index, 1);
                $selector.removeClass('active');
            } else {
                favorites.push(id);
                $selector.addClass('active');
            }
        } else {
            favorites = [id];
            $selector.addClass('active');
        }
        $doc.find('.favorites-count').text(favorites.length);
        favorites = favorites.join(',');
        setCookie('favorites', favorites, 365);
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                action: 'set_user_favorites',
            }
        }).done(function (r) {

        });
    });
    $doc.on('click', '.add-new-adr', function (e) {
        e.preventDefault();
        $(this).closest('.wrap-new-adr').find('.wrap-new-adr__hide').append(addressItemHTML);
    });
    $doc.on('input', '.products-places-autocomplete', function (e) {
        var $t = $(this);
        var val = $t.val();
        var l = val.length;
        if (l > 1) {
            $t.closest('form').trigger('submit');
        }
    });
    $doc.on('input', '.filter-place-input', function (e) {
        var $t = $(this);
        var val = $t.val();
        var l = val.length;
        var $form = $t.closest('form');
        var $list = $form.find('.products-places-list');
        if (l > 1) {
            showPreloader();
            $form.addClass('loading');
            $.ajax({
                type: "POST",
                url: admin_ajax,
                data: {
                    action: 'get_products_addresses',
                    string: val,
                }
            }).done(function (r) {
                hidePreloader();
                $form.removeClass('loading');
                if (r) {
                    $list.html(r);
                } else {
                    $list.html('');
                }
            });
        }
    });
    $doc.on('submit', '.products-places-form', function (e) {
        e.preventDefault();
        var $form = $(this);
        var $list = $form.find('.products-places-list');
        showPreloader();
        $form.addClass('loading');
        $.ajax({
            type: $form.attr('method'),
            url: admin_ajax,
            data: $form.serialize()
        }).done(function (r) {
            hidePreloader();
            $form.removeClass('loading');
            if (r) {
                $list.html(r);
            } else {
                $list.html('');
            }
        });
    });
    $doc.on('input', '.number-input', function (e) {
        var $t = $(this);
        var val = $t.val();
        var l = val.length;
        if (l > 0) {
            for (let a = 0; a < l; a++) {
                $t.val(val.replace(/[A-Za-zА-Яа-яЁё]/, ''));
                val = $t.val();
            }
        }
    });
    $doc.on('input', '.content-field', function (e) {
        var $t = $(this);
        var $wrapper = $t.closest('.form-group');
        var $counter = $wrapper.find('.content-count-js');
        var val = $t.val();
        var l = val.length;
        $counter.html(l + '/9000');
        if (l >= 8999) {
            e.preventDefault();
            $t.val(val.slice(0, 9000));
            val = $t.val();
            l = val.length;
            $counter.html(l + '/9000');
            return;
        }
    });
    $doc.on('contextmenu', '.content-field', function (e) {
        e.preventDefault();
        var $menu = $doc.find(".context-menu");
        $menu.toggleClass('context-menu--active');
        positionMenu(e);
    });
    $doc.on('change', '.categories-select-js, .sub-categories-select-js', function (e) {
        var $this = $(this);
        setSubCategories($this);
    });
    $doc.on('change', '.upfile_product', function (e) {
        var $t = $(this);
        var max = $t.attr('data-max')
        max = Number(max);
        var filesList = this.files;
        var filesListLength = filesList.length;
        if ((max !== undefined) && !isNaN(max) && filesListLength > max) {
            e.preventDefault();
            alert(max + ' файлів максимум');
            $doc.find('.cabinet-item__photo-item').find('img').attr('src', '').removeClass('visible');
            $doc.find('.upfile_product').val('');
            return;
        }
        var dtFiles = dt.files;
        var dtFilesLength = dtFiles.length;
        if (dtFilesLength < 10 && dtFilesLength > 0) {
            var testLength = dtFilesLength + filesListLength;
            if (testLength > 10) {
                alert('10 файлів максимум');
                $doc.find('.cabinet-item__photo-item').find('img').attr('src', '').removeClass('visible');
                $doc.find('.upfile_product').val('');
                return;
            } else {
                for (let file of filesList) {
                    dt.items.add(file);
                }
                this.files = dt.files;
                renderPreviewFileInput(dt.files);
                return;
            }
        }
        for (let file of filesList) {
            dt.items.add(file);
        }
        renderPreviewFileInput(filesList);
    });
    $doc.on('click', '.remove-file', function (e) {
        e.preventDefault();
        var $t = $(this);
        if ($t.hasClass('remove-avatar')) {
            showPreloader();
            $.ajax({
                type: 'POST',
                url: admin_ajax,
                data: {
                    action: 'delete_avatar',
                }
            }).done(function (r) {
                hidePreloader();
                $doc.find('.user-avatar').attr('src', r);
                $doc.find('.personal-ava__media img').attr('src', '');
                $doc.find('.personal-ava__media img').removeClass('visible');
            });
            return;
        }
        var index = $t.closest('.cabinet-item__photo-item').index();
        var input = document.getElementById('photos');
        if (index === undefined) return;
        var newFileList = [];
        var dataList = dt.items;
        dataList.remove(index);
        input.files = dt.files;
        $doc.find('.cabinet-item__photo-item').eq(index).find('img').attr('src', '').removeClass('visible');
        renderPreviewFileInput(dt.files);
    });
    $doc.on('submit', '.form-js', function (e) {
        e.preventDefault();
        var $form = $(this);
        var $thanks = $('#dialog');
        var this_form = $form.attr('id');
        var test = true,
            thsInputs = $form.find('input, textarea'),
            $select = $form.find('select[required]');
        var $address = $form.find('input.address-js');
        $thanks.find('.modal-title__subtitle').text('');
        $thanks.find('.modal-title__main').text('');
        $select.each(function () {
            var $ths = $(this);
            var $label = $ths.closest('.form-group');
            var val = $ths.val();
            if (val === null || val === undefined) {
                test = false;
                $label.addClass('error');
            } else {
                $label.removeClass('error');
            }
        });
        thsInputs.each(function () {
            var thsInput = $(this),
                $label = thsInput.closest('.form_element'),
                thsInputType = thsInput.attr('type'),
                thsInputVal = thsInput.val().trim(),
                inputReg = new RegExp(thsInput.data('reg')),
                inputTest = inputReg.test(thsInputVal);
            if (thsInput.attr('required')) {
                if (thsInputVal.length <= 0) {
                    test = false;
                    thsInput.addClass('error');
                    $label.addClass('error');
                    thsInput.focus();
                    if (thsInputType === 'file') {
                        $form.find('.cabinet-item__photo-item').eq(0).addClass('error');
                        $('html, body').animate({
                            scrollTop: $form.find('.cabinet-item__photo-item').eq(0).offset().top
                        });
                    }
                } else {
                    thsInput.removeClass('error');
                    $label.removeClass('error');
                    if (thsInput.data('reg')) {
                        if (inputTest === false) {
                            test = false;
                            thsInput.addClass('error');
                            $label.addClass('error');
                            thsInput.focus();
                        } else {
                            thsInput.removeClass('error');
                            $label.removeClass('error');
                        }
                    }
                    if (thsInputType === 'file') {
                        $form.find('.cabinet-item__photo-item').eq(0).removeClass('error');
                    }
                }
            }
        });
        var $password = $form.find('input[name="password"]');
        var $passwordRepeat = $form.find('input[name="repeat_password"]');
        var $passwordOld = $form.find('input[name="old_password"]');
        var $passwordNew = $form.find('input[name="new_password"]');
        if ($password.length > 0 && $passwordRepeat.length > 0) {
            if ($password.val() !== $passwordRepeat.val()) {
                $password.addClass('error');
                $passwordRepeat.addClass('error');
                return;
            }
            if (!isValidPassword($password.val())) {
                showMassage(errorPswMsg);
                $password.addClass('error');
                $passwordRepeat.addClass('error');
                return;
            }
            $password.removeClass('error');
            $passwordRepeat.removeClass('error');
        } else if ($password.length > 0 && $password.val().length > 0) {
            if (!isValidPassword($password.val())) {
                showMassage(errorPswMsg);
                $password.addClass('error');
                $passwordRepeat.addClass('error');
                return;
            }
            $password.removeClass('error');
            $passwordRepeat.removeClass('error');
        }
        if ($passwordOld.length > 0 && $passwordNew.length > 0) {
            if (!isValidPassword($passwordNew.val())) {
                showMassage(errorPswMsg);
                $passwordNew.addClass('error');
                return;
            }
            $passwordNew.removeClass('error');
        }
        var $inp = $form.find('input[name="consent"]');
        if ($inp.length > 0) {
            if ($inp.prop('checked') === false) {
                $inp.closest('.form-consent').addClass('error');
                return;
            }
            $inp.closest('.form-consent').removeClass('error');
        }
        if ($address.length > 0) {
            var addressTest = true;
            $address.each(function (index) {
                var $el = $(this);
                var val = $el.val() || '';
                var selected = $el.attr('data-selected') || '';
                if (selected.trim() !== val.trim()) {
                    test = false;
                    addressTest = false;
                    $el.addClass('error');
                } else {
                    $el.removeClass('error');
                }
                if (val.length === 0) {
                    test = false;
                    $el.addClass('error');
                }
            });
            if (!addressTest) showMassage(locationErrorString);
        }
        if (test) {
            var thisForm = document.getElementById(this_form);
            var formData = new FormData(thisForm);
            showPreloader();
            $.fancybox.close();
            $.ajax({
                type: $form.attr('method'),
                url: admin_ajax,
                processData: false,
                contentType: false,
                data: formData,
            }).done(function (r) {
                console.log(!$form.hasClass('checkout-packages-order'));
                if (!$form.hasClass('change-user-data-form') || !$form.hasClass('checkout-packages-order')) {
                    $form.trigger('reset');
                }
                if (r) {
                    if (isJsonString(r)) {
                        var res = JSON.parse(r);
                        if ($form.hasClass('upload-avatar-form') && res.type === 'success') {
                            if (res.user_avatar !== undefined) $doc.find('.user-avatar').attr('src', res.user_avatar);
                        }
                        if ($form.hasClass('set-coupon') && res.type === 'success') {
                            var discount = res['discount'];
                            var coupon = res['coupon'];
                            if (discount && coupon) {
                                setCookie('coupon', coupon, 7);
                            }
                        }
                        if ($form.hasClass('new-order') && res.type === 'success') {
                            setCookie('coupon', '', 1);
                        }
                        if ($form.hasClass('new-advertisement-js') && res.type === 'success') {
                            renderPreviewFileInput([]);
                            $.fancybox.open({
                                src: '#created',
                                touch: false,
                                baseClass: 'thanks_msg'
                            });
                            setTimeout(function () {
                                $.fancybox.close();
                            }, 3000);
                        }
                        if ($form.hasClass('sign-in-form') && res.type === 'success' || res.is_reload === 'true') {
                            window.location.reload();
                            return;
                        }
                        if (res.msg !== '' && res.msg !== undefined) {
                            if ($form.hasClass('add-enterprise-form')) {
                                $form.closest('.faq-item__content').html('<div class="form-description__item-title">' + res.msg + '</div>');
                            } else {
                                showMassage(res.msg);
                            }
                        }
                        if (res.url !== undefined) {
                            showPreloader();
                            setTimeout(function () {
                                window.location.href = res.url;
                                return;
                            }, 3100);
                        }
                        if ($form.hasClass('comment-form')) {
                            if (res.html !== undefined) {
                                $('.commentlist').html(res.html);
                            }
                        }
                    } else {
                        if ($form.hasClass('new-message-js')) {
                            $doc.find('.chat-main__content').html(r);
                            var $img = $doc.find('.chat-file').find('img');
                            $img.attr('src', $img.attr('data-src'));
                            initCorrespondence();
                        } else {
                            showMassage(r);
                        }
                    }
                }
                hidePreloader();
                if ($form.hasClass('checkout-packages-order')) showPreloader();
                dt = new DataTransfer();
            });
        }
    });
    $doc.on('click', '.num_minus, .num_pluss', function (e) {
        e.preventDefault();
        var $t = $(this);
        var isMinus = $t.hasClass('num_minus');
        var input = $t.closest('.counter_product').find('.counter_input');
        var oldValue = Number(input.val() || 1);
        var isCart = input.hasClass('counter_input--cart');
        var $buttons = $t.closest('.product-card').find('.add-to-cart');
        var price = Number(input.attr('data-price'));
        var unit = input.attr('data-unit');
        var currency = input.attr('data-currency');
        var max = input.attr('data-max');
        var min = input.attr('data-min');
        var val = Number(input.val());
        if (isMinus) {
            if (min) {
                min = Number(min);
                if (val > 1 && val > min) val = val - 1;
            } else {
                if (val > 1) val = val - 1;
            }
        } else {
            if (max) {
                max = Number(max);
                if (val < max) {
                    val = val + 1;
                }
            } else {
                val = val + 1;
            }
        }
        input.val(val).change();
        $doc.find('.qnt-input-js').val(val).change();
        var sum = price * val;
        var html = '<strong>' + sum + '</strong> ' + currency;
        $t.closest('.product-card__count').find('.product__price-js').html(html);
        $doc.find('.form-count__price').html(html);
        var test = true;
        if (min) {
            if (val >= min) {
                $buttons.removeClass('not-active');
            } else {
                $buttons.addClass('not-active');
                test = false;
            }
        }
        if (max) {
            max = Number(max);
            if (val <= max) {
                $buttons.removeClass('not-active');
            } else {
                $buttons.addClass('not-active');
                test = false;
            }
        }
        if (test) {
            $buttons.removeClass('not-active');
        } else {
            $buttons.addClass('not-active');
        }
        $buttons.attr('data-qnt', val);
        if (isCart && oldValue !== val) {
            var id = input.attr('data-id');
            if (id === undefined) return;
            cart[id].qnt = val;
            setCookie('cart', JSON.stringify(cart), 365);
            countCartItems();
            reInitCart();
        }
    });
    $doc.on('click', '.js-collapse--title', function (event) {
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
    $doc.on('click', '.add-products-continue, .deactivate-products', function (event) {
        event.preventDefault();
        var $t = $(this);
        var isDeactivate = $t.hasClass('deactivate-products');
        if (load) return;
        $t.addClass('not-active');
        load = true;
        var $inputs = $doc.find('.checked-product:checked');
        var array = [];
        if ($inputs) {
            $inputs.each(function () {
                var $t = $(this);
                var id = $t.attr('data-id');
                array.push(id);
            });
            showPreloader();
            $.ajax({
                type: 'POST',
                url: admin_ajax,
                data: {
                    'action': isDeactivate ? 'deactivate_products' : 'activate_auto_renew',
                    'ids': array,
                }
            }).done(function (r) {
                hidePreloader();
                load = false;
                $t.removeClass('not-active');
                if (r) {
                    if (isJsonString(r)) {
                        var res = JSON.parse(r);
                        if (res.msg !== '' && res.msg !== undefined) {
                            showMassage(res.msg);
                        }
                        if (res.url !== undefined) {
                            showPreloader();
                            setTimeout(function () {
                                window.location.href = res.url;
                                return;
                            }, 3100);
                        }
                        if (res.type === 'success') {
                            array.forEach(function (id) {
                                if (isDeactivate) {
                                    $doc.find('.select-product__item[data-id="' + id + '"]').addClass('no-active');
                                    $doc.find('.change-auto-continue[value="' + id + '"]').prop('checked', false);
                                } else {
                                    $doc.find('.change-auto-continue[value="' + id + '"]').prop('checked', true);
                                }
                            });
                        }
                    }
                }
            });
        }
    });
    $doc.on('change', '.is-fop-check', function (e) {
        var $t = $(this);
        var isChecked = $t.prop('checked') === true ? 'true' : '';
        setCookie('is_fop', isChecked, 1);
    });
    $doc.on('change', '.upload-avatar-form', function (e) {
        var $t = $(this);
        $t.closest('form').trigger('submit');
    });
    $doc.on('change', '.sort-select', function (e) {
        var $t = $(this);
        var $th = $t.find('option:selected');
        var order = $th.attr('data-order');
        $t.closest('form').find('input[name="order"]').val(order);
    });
    $doc.on('change', '.change-order-status', function (e) {
        var $t = $(this);
        var $th = $t.find('option:selected');
        var id = $t.attr('data-id');
        if (load) return;
        load = true;
        showPreloader();
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                'action': 'change_order_status',
                'id': id,
                'status': $t.val(),
            }
        }).done(function (r) {
            hidePreloader();
            load = false;
            if (r) {
                if (isJsonString(r)) {
                    var res = JSON.parse(r);
                    if (res.msg !== '' && res.msg !== undefined) {
                        showMassage(res.msg);
                    }
                    if (res.type === 'success') {

                    }
                }
            }
        });
    });
    $doc.on('change', '.trigger-on-change', function (e) {
        var $t = $(this);
        $t.closest('form').trigger('submit');
    });
    $doc.on('change', '.change-auto-continue', function (e) {
        var $t = $(this);
        if (load) {
            e.preventDefault();
            return;
        }
        $t.addClass('not-active');
        load = true;
        var id = $t.val();
        var isChecked = $t.prop('checked') === true ? 'true' : 'false';
        showPreloader();
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                'action': 'change_auto_continue',
                'id': id,
                'isChecked': isChecked,
            }
        }).done(function (r) {
            hidePreloader();
            load = false;
            $t.removeClass('not-active');
        });
    });
    $doc.on('change', '.change-user-status', function (e) {
        var $t = $(this);
        if (load) {
            e.preventDefault();
            return;
        }
        $t.addClass('not-active');
        load = true;
        var id = $t.val();
        var off = $t.attr('data-text-off') || '';
        var on = $t.attr('data-text-on') || '';
        var isChecked = $t.prop('checked') === true;
        var isCheckedString = isChecked ? 'true' : 'false';
        showPreloader();
        $t.closest('li').find('.text-out-state').text(isChecked ? off : on);
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                'action': 'change_user_status',
                'id': id,
                'isChecked': isCheckedString,
            }
        }).done(function (r) {
            hidePreloader();
            load = false;
            $t.removeClass('not-active');
            if (r) {
                if (isJsonString(r)) {
                    var res = JSON.parse(r);
                    if (res.msg !== '' && res.msg !== undefined) {
                        showMassage(res.msg);
                    }
                    if (res.url !== undefined) {
                        showPreloader();
                        setTimeout(function () {
                            window.location.href = res.url;
                            return;
                        }, 3100);
                    }
                    if (res.type !== 'error') {
                        if (res.text !== '' && res.text !== undefined) $t.closest('.table-cabinet__body-row').find('.user-status').text(res.text);
                    }
                }
            }
        });
    });
    $doc.on('keypress', '.search_input', function (e) {
        var $t = $(this);
        var val = $t.val();
        if ('Enter' === e.key) {
            $t.closest('form').trigger('submit');
        }
    });
    $doc.on('keyup', '.search-field-js', function (e) {
        var $t = $(this);
        var selector = $t.attr('data-selector');
        var wrapperSelector = $t.attr('data-wrapper-selector');
        var $list = $doc.find(selector);
        if ($list.length === 0) return;
        var val = $t.val();
        if (val.length === 0) {
            if (wrapperSelector === undefined) {
                $list.removeClass('hidden');
            } else {
                $doc.find(wrapperSelector).removeClass('hidden');
            }
        } else {
            val = val.toUpperCase().trim();
            $list.each(function () {
                var $this = $(this);
                var text = $this.text().toUpperCase().trim();
                if (text.includes(val)) {
                    if (wrapperSelector === undefined) {
                        $this.removeClass('hidden');
                    } else {
                        $this.closest(wrapperSelector).removeClass('hidden');
                    }
                } else {
                    if (wrapperSelector === undefined) {
                        $this.addClass('hidden');
                    } else {
                        $this.closest(wrapperSelector).addClass('hidden');
                    }
                }
            });
        }
    });
    $doc.on('click', '.activating-product', function (e) {
        e.preventDefault();
        var $t = $(this);
        if (load) return;
        $t.addClass('not-active');
        load = true;
        var id = $t.attr('data-id');
        var status = $t.attr('data-status') || 'publish';
        showPreloader();
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                'action': 'change_product_status',
                'id': id,
                'status': status,
            }
        }).done(function (r) {
            hidePreloader();
            load = false;
            $t.removeClass('not-active');
            if (r) {
                if (isJsonString(r)) {
                    var res = JSON.parse(r);
                    if (res.msg !== '' && res.msg !== undefined) {
                        showMassage(res.msg);
                    }
                    if (res.url !== undefined) {
                        showPreloader();
                        setTimeout(function () {
                            window.location.href = res.url;
                            return;
                        }, 3100);
                    }
                    if (res.type === 'success') {
                        $t.closest('.select-product__item').removeClass('not-active');
                        $t.closest('.select-product__item').removeClass('no-active');
                        $t.closest('.select-product__item').find('.management-link .disable').removeClass('disable');
                        $t.remove();
                    }
                }
            }
        });
    });
    $doc.on('change', ".up_file_product", function (e) {
        var $t = $(this);
        var file = this.files[0];
        var this_ = $(this);
        var max = Number($t.attr('data-max') || 1);
        if (file) {
            var reader = new FileReader();
            reader.onload = function (event) {
                this_.closest('.cabinet-item__photo-item').find('img').attr('src', event.target.result).addClass('visible');
            };
            reader.readAsDataURL(file);
            if (max > 1) {
                $t.closest('.cabinet-item__photo').append(imageInputHtml(max));
            }
        } else {
            this_.closest('.cabinet-item__photo-item').find('img').attr('src', '').removeClass('visible');
        }
    });
    $doc.on('click', '.cancel-order', function (e) {
        e.preventDefault();
        var $t = $(this);
        if (load) return;
        $t.addClass('not-active');
        load = true;
        var id = $t.attr('data-id');
        var $item = $t.closest('.orders-main__item');
        showPreloader();
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                'action': 'cancel_order',
                'id': id,
            }
        }).done(function (r) {
            hidePreloader();
            load = false;
            if (r) {
                if (isJsonString(r)) {
                    var res = JSON.parse(r);
                    if (res.msg !== '' && res.msg !== undefined) {
                        showMassage(res.msg);
                    }
                    if (res.text !== '' && res.text !== undefined) {
                        $item.find('.order-status').html(res.text);
                    }
                    if (res.url !== undefined) {
                        showPreloader();
                        setTimeout(function () {
                            window.location.href = res.url;
                            return;
                        }, 3100);
                    }
                    if (res.type !== 'error') {
                        $item.find('.order-status').addClass('none');
                        $t.remove();
                    }
                }
            }
        });
    });
    $doc.on('click', '.remove-user', function (e) {
        e.preventDefault();
        var $t = $(this);
        if (load) return;
        $t.addClass('not-active');
        load = true;
        var id = $t.attr('data-id');
        var $item = $t.closest('.table-cabinet__body-row');
        showPreloader();
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                'action': 'remove_trusted_user',
                'id': id,
            }
        }).done(function (r) {
            hidePreloader();
            load = false;
            if (r) {
                if (isJsonString(r)) {
                    var res = JSON.parse(r);
                    if (res.msg !== '' && res.msg !== undefined) {
                        showMassage(res.msg);
                    }
                    if (res.url !== undefined) {
                        showPreloader();
                        setTimeout(function () {
                            window.location.href = res.url;
                            return;
                        }, 3100);
                    }
                    if (res.type !== 'error') {
                        $item.remove();
                    }
                }
            }
        });
    });
    $doc.on('click', '.move-to-correspondence', function (e) {
        e.preventDefault();
        var $t = $(this);
        if (load) return;
        $t.addClass('not-active');
        load = true;
        var productID = $t.attr('data-product');
        var userID = $t.attr('data-user');
        showPreloader();
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                'action': 'get_correspondence_link',
                'product_id': productID,
                'user_id': userID,
            }
        }).done(function (r) {
            hidePreloader();
            load = false;
            if (r) {
                if (isJsonString(r)) {
                    var res = JSON.parse(r);
                    if (res.msg !== '' && res.msg !== undefined) {
                        showMassage(res.msg);
                    }
                    if (res.url !== undefined) {
                        showPreloader();
                        window.location.href = res.url;
                    }
                }
            }
        });
    });
    $doc.on('change', ".chat-file_input", function (e) {
        var $t = $(this);
        var file = this.files[0];
        var $img = $t.closest('.chat-file').find('img');
        if (file) {
            var reader = new FileReader();
            reader.onload = function (event) {
                $img.attr('src', event.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            $img.attr('src', $img.attr('data-src'));
        }
    });
    $doc.on('click', ".load-oldest-messages", function (e) {
        e.preventDefault();
        var $t = $(this);
        var max = $t.attr('data-max_num_pages');
        var paged = $t.attr('data-paged');
        var correspondence = $t.attr('data-correspondence');
        if (max === undefined || paged === undefined || correspondence === undefined) return;
        max = Number(max);
        paged = Number(paged);
        correspondence = Number(correspondence);
        $t.remove();
        if (load) return;
        $t.addClass('not-active');
        load = true;
        showPreloader();
        paged = paged + 1;
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                'action': 'get_messages_page',
                'correspondence': correspondence,
                'paged': paged,
            }
        }).done(function (r) {
            hidePreloader();
            load = false;
            if (r) {
                $doc.find('.dialog-date').remove();
                $doc.find('.chat-main__content').prepend(r);
            }
        });
    });
    $doc.on('click', ".link-js", function (e) {
        e.preventDefault();
        var $t = $(this);
        if (load) return;
        $t.addClass('not-active');
        load = true;
        showPreloader();
        $.ajax({
            type: 'GET',
            url: $t.attr('href'),
        }).done(function (r) {
            hidePreloader();
            load = false;
            $t.removeClass('not-active');
            if (r) {
                var $r = $(parser.parseFromString(r, "text/html"));
                $doc.find('main.content').html($r.find('main.content').html());
                initSliders();
            } else {
                window.location.href = $t.attr('href');
            }
        }).fail(function () {
            window.location.href = $t.attr('href');
        });
    });
    $doc.on('click', ".product-item", function (e) {
        e.preventDefault();
        var $t = $(this);
        var div = $(".add-to-favorite");
        if (!div.is(e.target) && div.has(e.target).length === 0) {
            showPreloader();
            window.location.href = $t.find('.product-item__title').attr('href');
        }
    });
    $doc.on('click', ".correspondence-link", function (e) {
        e.preventDefault();
        var $t = $(this);
        if (load) return;
        load = true;
        showPreloader();
        var url = $t.attr('href') || $t.attr('data-url');
        var $container = $doc.find('.chat-main');
        var dataAjax = {
            type: "GET",
            url: url
        };
        $.ajax(dataAjax).done(function (r) {
            var $r = $(parser.parseFromString(r, "text/html"));
            var $r_container = $r.find('.chat-main');
            $container.html($r_container.html());
            load = false;
            hidePreloader();
            $doc.find('.chat-group__right.chat-empty').removeClass('chat-empty');
        });
    });
    $doc.on('change', '.place-list .check_st', function () {
        var count_ = $(this).closest('.place-list ').attr('data-choice');
        var n = $(this).closest('.place-list').find('.check_st').length;
        var с_ = $(this).closest('.place-list').find('.check_st:checkbox:checked').length;
        var p = $(this).closest('.place-list-modal').attr('data-parent');
        var arr = [];
        $(this).closest('.place-list').find('.check_st:checkbox:checked').each(function () {
            arr.push($(this).val());
        });
        if (count_ > с_) {
            $(this).closest('.place-list').find('.check-item').removeClass('disable');
        } else {
            $(this).closest('.place-list').find('.check_st:checkbox:not(:checked)').parent('.check-item').addClass('disable');
        }
        initPackages();
    });
    $doc.on('click', '.deactivate-purchased, .activate-purchased', function (e) {
        e.preventDefault();
        var $t = $(this);
        var index = $t.attr('data-index');
        var id = $t.attr('data-id');
        var hasActivate = $t.hasClass('activate-purchased');
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                'action': 'set_purchased_status',
                'status': hasActivate ? 'activate' : 'deactivate',
                'index': index,
                'id': id,
            }
        }).done(function (r) {
            hidePreloader();
            load = false;
            if (r) $t.closest('li').html(r);
        });
    });
    $doc.on('click', '.delete-purchased', function (e) {
        e.preventDefault();
        var $t = $(this);
        var index = $t.attr('data-index');
        var id = $t.attr('data-id');
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                'action': 'delete_purchased',
                'index': index,
                'id': id,
            }
        }).done(function (r) {
            hidePreloader();
            load = false;
            if (r) $t.closest('.package-service-main__list').html(r);
        });
    });
    $('.tog-filter').on('click', function (e) {
        e.preventDefault();
        $('body').addClass('open_filter');
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
            onStart: function onStart(data) {
                from = data.from;
                to = data.to;
                from_input.val(from);
                to_input.val(to);
            },
            onChange: function onChange(data) {
                from = data.from;
                to = data.to;
                from_input.val(from);
                to_input.val(to);
            },
            onFinish: function onChange(data) {
                var name = from_input.attr('data-name');
                if (name !== undefined) from_input.attr('name', name);
                this_.closest('form').trigger('submit');
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
            var val1 = $(this).val();
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
    $(document).click(function (e) {
        var div = $(".content-field");
        if (!div.is(e.target)
            && div.has(e.target).length === 0) {
            toggleMenuOff();
        }
    });
    $doc.find('.counter_service').each(function () {
        var parent = $(this);
        var minus = $('.num_minus', parent);
        var pluss = $('.num_pluss', parent);
        var input = $('.counter_input', parent);
        var price = Number(input.attr('data-price'));
        input.attr('data-total', price);
        price.toString().includes('.');
        price = price.toFixed(2);
        $(this).closest('.js-package').find('.js-price').text(price);
        $(this).closest('.js-package').find('.js-price').text(price);
        if (input.val() === 2) {
            minus.removeClass('active');
        }
        pluss.click(function () {
            input.val(parseInt(input.val()) + 1).change();
            pluss.addClass('active');
            var val_ = input.val();
            var total = val_ * price;
            input.attr('data-total', total);
            total.toString().includes('.');
            total = total.toFixed(2);
            $(this).closest('.js-package').find('.js-price').text(total);
            if (input.val() > 0) {
                $(this).closest('.js-package').addClass('active');
            } else {
                $(this).closest('.js-package').removeClass('active');
            }
            initPackages();
        });
        minus.click(function () {
            if (input.val() > 0) {
                input.val(input.val() - 1).change();

                if (input.val() >= 1) {
                    var val_ = input.val();
                    var total = val_ * price;
                    input.attr('data-total', total);
                    total.toString().includes('.');
                    total = total.toFixed(2);
                    $(this).closest('.js-package').find('.js-price').text(total);
                }
            }
            if (input.val() > 0) {
                $(this).closest('.js-package').addClass('active');
            } else {
                $(this).closest('.js-package').removeClass('active');
            }
            if (input.val() === 0) {
                minus.removeClass('active');
            }
            initPackages();
        });
        initPackages();
    });
    $doc.on('click', '.aside-tog', function () {
        var bw = window.innerWidth;
        var $b = $_body;
        if (bw > 1023) {
            if ($b.hasClass('hide_aside')) {
                setCookie('body_class', '', 365);
            } else {
                setCookie('body_class', 'hide_aside', 365);
            }
            $b.toggleClass('hide_aside');
        } else {
            $b.toggleClass('open_aside');
        }
    });
    showElements();
    showingContent();
});

function initCalendar() {
    $doc.find('.js-range-period').not('.range-period-current').each(function () {
        $(this).addClass('range-period-current');
        $(this).daterangepicker({
            "singleDatePicker": true,
            "autoApply": true,
            minDate: minDate,
            locale: {
                "format": "DD.MM.YYYY",
                "autoApply": true,
                "daysOfWeek": daysOfWeek,
                "monthNames": monthNames,
                firstDay: 1
            }
        });
    });
}

function checkingStorage() {
    var storagePackages = localStorage.getItem('storagePackages');
    if (storagePackages) {
        storage = JSON.parse(storagePackages);
    }
}

var isObjectEmpty = (objectName) => {
    return Object.keys(objectName).length === 0
}

function initPackages() {
    $doc.find('.counter_input').each(function () {
        var $t = $(this);
        var val = $t.val();
        val = Number(val);
        var $parent = $t.closest('.package-service__item');
        var ID = $parent.attr('data-id');
        if (!isNaN(val)) {
            var suffix = $parent.attr('data-qnt-suffix');
            var total = $t.attr('data-total');
            var count = $t.attr('data-count');
            var countNum = Number(count);
            var $placeList = $doc.find('.place-list[data-choice="' + count + '"][data-id="' + ID + '"] .check_st');
            var placesList = [];
            var key = ID + '_';
            if (!isNaN(countNum)) {
                key = key + countNum;
            }
            $placeList.each(function () {
                var $t = $(this);
                if ($t.prop('checked') === true) {
                    var id = $t.attr('data-value');
                    placesList.push(id);
                }
            });
            storage[key] = {
                ID: ID,
                total: total,
                val: val,
                count: isNaN(countNum) ? count : countNum,
                placesList: placesList ? placesList.join() : ''
            };
            if (val === 0) delete storage[key];
        }
    });
    var stringStorage = JSON.stringify(storage);
    localStorage.setItem('storagePackages', stringStorage);
    setPackagesBar(stringStorage);
}

function setPackagesBar(stringStorage) {
    if (load) return;
    load = true;
    showPreloader();
    $.ajax({
        type: 'POST',
        data: {
            order: stringStorage
        }
    }).done(function (r) {
        hidePreloader();
        load = false;
        if (r) {
            var $r = $(parser.parseFromString(r, "text/html"));
            $doc.find('.package-service__right').html($r.find('.package-service__right').html());
            initCalendar();
            initSelectric();
        }
    });
}

function initSelectric() {
    $doc.find('.select_st:not(.select-init)').each(function () {
        $(this).addClass('select-init');
        $(this).selectric({
            disableOnMobile: false,
            nativeOnMobile: false
        });
    });
}

function showingContent() {
    $doc.find('main.content').addClass('showing');
}

function initSliders() {
    $doc.find('.product-item__slider:not(.slick-slider)').each(function () {
        $(this).slick({
            dots: true,
            slidesToShow: 1,
            arrows: false
        });
    });
    $doc.find('.product-slider:not(.slick-slider)').each(function () {
        $(this).slick({
            dots: true,
            slidesToShow: 1
        });
    });
    $doc.find('.similar-slider:not(.slick-slider)').each(function () {
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
    $doc.find('.category-list:not(.slick-slider)').each(function () {
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
}

function checkingNotificationsSchedules() {
    if (userID !== 0) {
        setInterval(checkingNotifications, 15000);
    }
}

function checkingNotifications() {
    $.ajax({
        type: 'POST',
        url: admin_ajax,
        data: {
            'action': 'get_notifications_number',
        }
    }).done(function (r) {
        if (r) {
            var num = Number(r.trim());
            if (!isNaN(num)) {
                if (num > 0) {
                    $doc.find('.btn_notification span').removeClass('hidden');
                } else {
                    $doc.find('.btn_notification span').addClass('hidden');
                }
            }
        }
    });
}

function setCorrespondenceID() {
    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    if (urlParams.has('correspondence')) correspondence = urlParams.get('correspondence');
}

function getNewMessages() {
    if (correspondence !== 0) {
        $.ajax({
            type: 'POST',
            url: admin_ajax,
            data: {
                'action': 'get_messages_page',
                'correspondence': correspondence,
                'paged': 1,
            }
        }).done(function (r) {
            hidePreloader();
            load = false;
            if (r) {
                var $r = $(parser.parseFromString(r, "text/html"));
                var $r_messages = $r.find('.dialog-item');
                if ($r_messages) {
                    var test = false;
                    $r_messages.each(function () {
                        var $t = $(this);
                        var id = $t.attr('id');
                        if ($t.hasClass('not-read')) {
                            $doc.find('.chat-main__content').append($t);
                            test = true;
                        }
                    });
                    if (test) initCorrespondence();
                }
            }
        });
    }
}

function initCorrespondenceReload() {
    setInterval(getNewMessages, 20000);
}

function initCorrespondence() {
    if ($doc.find('.dialog-item').length === 0) return;
    var msg = document.querySelector('.dialog-item.not-read .dialog-item__text');
    if (msg) {
        msg.scrollIntoView({block: "start", behavior: "smooth"});
    } else {
        var nodes = document.querySelectorAll('.dialog-item');
        var first = nodes[0];
        var last = nodes[nodes.length - 1];
        last.querySelector('.dialog-item__text').scrollIntoView({block: "start", behavior: "smooth"});
    }
}

function initSubCategories() {
    if ($doc.find('.categories-select-js').length === 0) return;
    setSubCategories($doc.find('.categories-select-js').eq(0));
}

function showElements() {
    $doc.find('.hidden-opacity').addClass('hidden-opacity-showed');
}

function setSubCategories($select) {
    var $this = $select;
    var val = $this.val();
    var selector = $this.attr('data-selector');
    var $wrapper = $this.closest('.form-group');
    var $next = $doc.find(selector);
    var $nextHTML = $next.html();
    var $selectric = $next.data('selectric');
    if (val === null || val === undefined) {
        $next.removeAttr('required');
        $next.html('');
        $next.val('').trigger('change');
        $selectric.refresh();
        $next.closest('.form-group').addClass('not-active');
        return;
    }
    var id = $this.attr('data-id') || '';
    if ($next.length === 0) return;
    showPreloader();
    $wrapper.addClass('not-active');
    $.ajax({
        type: 'POST',
        url: admin_ajax,
        data: {
            action: 'get_subcategories',
            parent: val,
            product_id: id,
        }
    }).done(function (r) {
        hidePreloader();
        $wrapper.removeClass('not-active');
        if (r && (r.trim().length > 0)) {
            $next.attr('required', 'required');
            $next.html(r);
            $selectric.refresh();
            $next.closest('.form-group').removeClass('not-active');
            $next.trigger('change');
        } else {
            $next.removeAttr('required');
            $next.html('');
            $next.val('').trigger('change');
            $selectric.refresh();
            $next.closest('.form-group').addClass('not-active');
        }
    });
}

function addingToBuffer() {
    var $images = $doc.find('.add-to-buffer');
    if ($images) {
        $images.each(async function (index) {
            var $t = $(this);
            var src = $t.attr('src');
            var words = src.split('.');
            var fileName = 'image-' + index + '.' + words[words.length - 1];
            var file = await getFileFromUrl(src, fileName);
            dt.items.add(file);
            doc.getElementById('photos').files = dt.files;
        });
    }
}

async function getFileFromUrl(url, name) {
    const response = await fetch(url);
    const data = await response.blob();
    return new File([data], name, {
        type: data.type,
    });
}

function imageInputHtml(max) {
    var count = $doc.find('.cabinet-item__photo-item').length;
    max = max - 1;
    return '<div class="cabinet-item__photo-item">\n' +
        '                                        <label>\n' +
        '                                            <input\n' +
        '                                                    required\n' +
        '                                                    id="photos-' + count + '"\n' +
        '                                                    data-max="' + max + '"\n' +
        '                                                    class="up_file_product hidden"\n' +
        '                                                    type="file"\n' +
        '                                                    name="upfile[]"\n' +
        '                                                    accept="image/heic, image/png, image/jpeg, image/webp"\n' +
        '                                            />\n' +
        '                                        </label>\n' +
        '                                        <img src="" alt=""/>\n' +
        '                                        <span class="remove-file remove-file--edit"></span>\n' +
        '                                    </div>';
}

function cf7Init() {
    $('.form-consent input[type="checkbox"]').each(function () {
        var $t = $(this);
        $t.closest('.wpcf7-list-item').append('<span></span>');
    });
}

w.addEventListener("popstate", function (event) {
    renderCatalog({
        url: document.location
    });
});

function setFilterSerialize($form) {
    var $items = $form.find('.filter-check-input:checked');
    var $box = $form.find('.filter-form-box');
    var obj = {};
    var html = '';
    $items.each(function () {
        var $t = $(this);
        var name = $t.attr('data-name');
        var val = $t.val();
        if (obj[name] === undefined) obj[name] = [];
        obj[name].push(val);
    });
    for (var key in obj) {
        var items = obj[key];
        if (items) {
            items = items.join();
            html += '<input type="hidden" name="' + key + '" value="' + items + '">';
        }
    }
    $box.html(html);
}

function renderCatalog(args) {
    var url, addEntry;
    url = args.url;
    addEntry = args.addEntry;
    var $catalog = $doc.find('.container-js').not('.testimonials');
    var $pagination = $doc.find('.pagination-js').not('.testimonials-pagination');
    if (load) return;
    load = true;
    $pagination.addClass('not-active');
    showPreloader();
    if (addEntry === true) window.history.pushState({}, '', url);
    var dataAjax = {
        type: "GET",
        url: url
    };
    $.ajax(dataAjax).done(function (r) {
        var $r = $(parser.parseFromString(r, "text/html"));
        var $r_catalog = $r.find('.container-js').not('.testimonials');
        $catalog.html($r_catalog.html());
        load = false;
        hidePreloader();
        $pagination.html($r.find('.pagination-js').not('.testimonials-pagination').html());
        $pagination.removeClass('not-active');
        initProductSliders();
    });
}

function setUserCity() {
    var $selector = $doc.find('.city-js');
    var $button = $doc.find('.confirm-city');
    if ($selector.length === 0) return;
    setTimeout(function () {
        $.ajax({
            type: "POST",
            url: admin_ajax,
            data: {
                action: 'get_user_city'
            },
        }).done(function (r) {
            if (r) {
                if (isJsonString(r)) {
                    var res = JSON.parse(r);
                    if (res.city !== undefined) {
                        $selector.text(res.city);
                        $button.attr('data-city', res.city);
                        setCookie('user_city', res.city, 0.5);
                    }
                } else {
                    r = r.trim();
                    $selector.text(r);
                    $button.attr('data-city', r);
                    setCookie('user_city', r, 0.5);
                }
            }
        });
    }, 1000);
}

function initTriggerOnSelect() {
    $doc.find('.trigger-on-select').each(function () {
        triggeredOnSelect($(this));
    });
}

function triggeredOnSelect($select) {
    var val = $select.val();
    var $option = $select.find('option:selected');
    if ($option.length > 0) {
        var trigger = $option.attr('data-trigger');
        if (trigger === undefined) return;
        $doc.find('.trigger-element').addClass('hidden');
        $doc.find('.trigger-element').find('input, select, textarea').removeAttr('required');
        $doc.find(trigger).removeClass('hidden');
        $doc.find(trigger).find('input, select, textarea').attr('required', 'required');
    }
}

function generateCheckoutLink(e) {
    e.preventDefault();
    var $t = $(this);
    var id = $t.attr('data-id');
    if (id === undefined) return;
    var qnt = $t.attr('data-qnt');
    var max = $t.attr('data-max');
    var href = $t.attr('href');
    qnt = qnt ? Number(qnt) : 1;
    if (max) {
        qnt = qnt <= max ? qnt : max;
    }
    window.location.href = href + '?product=' + id + '&qnt=' + qnt;
}

function reInitCart() {
    return;
    if (load) return;
    var $section = $doc.find('.cart-render-js');
    load = true;
    $section.addClass('not-active');
    showPreloader();
    $.ajax({
        type: 'GET',
    }).done(function (r) {
        hidePreloader();
        var $requestBody = $(parser.parseFromString(r, "text/html"));
        $section.html($requestBody.find('.cart-render-js').html());
        load = false;
        $section.removeClass('not-active');
        countCartItems();
    });
}

function setCart() {
    var cookie = getCookie('cart');
    if (cookie) {
        cart = JSON.parse(cookie);
    }
}

function addToCart(e) {
    e.preventDefault();
    var $t = $(this);
    var id = $t.attr('data-id');
    if (id === undefined) return;
    var qnt = $t.attr('data-qnt');
    var max = $t.attr('data-max');
    qnt = qnt ? Number(qnt) : 1;
    if (max) {
        max = Number(max);
        qnt = qnt <= max ? qnt : max;
    }
    if (cart[id] === undefined) {
        cart = {};
        cart[id] = {
            id: id,
            qnt: qnt,
        }
    } else {
        cart[id].qnt = cart[id].qnt + qnt;
        if (max) {
            qnt = cart[id].qnt <= max ? cart[id].qnt : max;
            cart[id].qnt = qnt;
        }
    }
    setCookie('cart', JSON.stringify(cart), 365);
    countCartItems();
    if ($t.find('span').hasClass('added')) return;
    var html = $t.find('span').html();
    html = html.replaceAll(inCartStr, inCartAddedStr);
    $t.find('span').html(html);
    $t.find('span').addClass('added');
    setTimeout(function () {
        html = html.replaceAll(inCartAddedStr, inCartStr);
        $t.find('span').html(html);
        $t.find('span').removeClass('added');
    }, 1000);
}

function countCartItems() {
    var res = 0;
    for (let id in cart) {
        var item = cart[id];
        var qnt = item.qnt;
        res = res + qnt;
    }
    $doc.find('.count-cart').text(res);
}

function commentNavInit() {
    var $previous = $doc.find('.comment-navigation .nav-previous a');
    $previous.addClass('btn_st b_yelloow');
}

function replaceString($field, index) {
    var string = $field.val();
    var newString = '';
    var more = $field.attr('data-more');
    if (string) {
        string = string.replaceAll(more, '');
        var length = string.length;
        for (var i = 0; i <= length; i++) {
            if (i === index) {
                newString += more;
            }
            if (string[i] !== undefined) {
                newString += string[i];
            }
        }
    }
    $field.val(newString);
}

function getCaret(el) {
    if (el.selectionStart) {
        return el.selectionStart;
    } else if (document.selection) {
        el.focus();
        var r = document.selection.createRange();
        if (r == null) {
            return 0;
        }
        var re = el.createTextRange(),
            rc = re.duplicate();
        re.moveToBookmark(r.getBookmark());
        rc.setEndPoint('EndToStart', re);
        return rc.text.length;
    }
    return 0;
}

function getPosCaret(el) {
    var caret = getCaret(el);
    var text = el.value.substr(0, caret).replace(/^(.*[\n\r])*([^\n\r]*)$/, '$2');
    return text.length;
}

function getPosInRow(el) {
    var caret = getCaret(el);
    var text = el.value.substr(0, caret).replace(/^(.*[\n\r])*([^\n\r]*)$/, '$2');
    return caret;
}

$(window).on('load resize scroll', function () {
    toggleMenuOff();
});

function toggleMenuOff() {
    var $menu = $doc.find(".context-menu");
    $menu.removeClass('context-menu--active');
}

function getPosition(e) {
    var posx = 0;
    var posy = 0;
    e = e || window.event;
    if (e.pageX || e.pageY) {
        posx = e.pageX;
        posy = e.pageY;
    } else if (e.clientX || e.clientY) {
        posx = e.clientX + document.body.scrollLeft +
            document.documentElement.scrollLeft;
        posy = e.clientY + document.body.scrollTop +
            document.documentElement.scrollTop;
    }

    return {
        x: posx,
        y: posy
    }
}

function positionMenu(e) {
    var $menu = $doc.find(".context-menu");
    var $wrapper = $menu.closest(".form-group");
    var clickCoords = getPosition(e);
    var clickCoordsX = clickCoords.x;
    var clickCoordsY = clickCoords.y;
    var menuWidth = $menu.width() + 4;
    var menuHeight = $menu.height() + 4;
    var windowWidth = window.innerWidth;
    var windowHeight = window.innerHeight;
    var windowScrollTop = $w.scrollTop();
    if ((windowWidth - clickCoordsX) < menuWidth) {
        $menu.css('left', windowWidth - menuWidth + "px");
    } else {
        $menu.css('left', clickCoordsX - windowScrollTop + "px");
    }
    if ((windowHeight - clickCoordsY) < menuHeight) {
        $menu.css('top', windowHeight - menuHeight + "px");
    } else {
        $menu.css('top', clickCoordsY + "px");
    }
}

function lozadInit() {
    var observer = lozad('.lozad:not(.lozad_showed)', {
        loaded: function loaded(el) {
            var $el = $(el);
            $el.addClass('lozad_showed');
            if ($el.hasClass('post-video')) {
                var id = $el.attr('id');
                if (id === undefined) return;
                var video = doc.getElementById(id);
                video.play();
            }
        }
    });
    observer.observe();
}

function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function showMassage(message) {
    $('#dialog .modal-title__subtitle').text(message);
    $.fancybox.open({
        src: '#dialog',
        touch: false,
        baseClass: 'thanks_msg'
    });
    setTimeout(function () {
        $.fancybox.close();
    }, 3000);
}

function isValidPassword(password) {
    var regexp = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;
    return password.match(regexp);
}

w.initAutocomplete = initAutocomplete;

function renderPreviewFileInput(filesList) {
    if (filesList) {
        var l = filesList.length;
        for (let index = 0; index < l && index < 10; index++) {
            var file = filesList[index];
            var $preview = $doc.find('.cabinet-item__photo-item').eq(index).find('img');
            if (file) {
                var src = $preview.attr('src');
                var name = $preview.attr('data-name');
                var fileName = file.name;
                var result = window.URL.createObjectURL(file);
                if (name !== fileName) {
                    if (src !== result) {

                        console.log(src);
                        console.log(result);
                        $preview.attr('src', result).addClass('visible');
                        $preview.attr('data-name', fileName);
                    }
                    // var reader = new FileReader();
                    // reader.readAsDataURL(file);
                    // reader.onload = function (event) {
                    //     var result = event.target.result;
                    //     if (src !== result) {
                    //         $preview.attr('src', result).addClass('visible');
                    //         $preview.attr('data-name', fileName);
                    //     }
                    // };
                }

            } else {
                $preview.attr('src', '').removeClass('visible');
                $preview.attr('data-name', '');
            }
        }
        $doc.find('.cabinet-item__photo-item').each(function (i) {
            if (i > (l - 1)) {
                $(this).find('img').attr('src', '').removeClass('visible');
                $(this).find('img').attr('data-name', '');
            }
        });
    } else {
        $doc.find('.cabinet-item__photo-item').find('img').attr('src', '').removeClass('visible');
        $doc.find('.cabinet-item__photo-item').find('img').attr('data-name', '');
    }
}

function addressItemHTML() {
    return '<div class="wrap-new-adr__hide-item append_item"><div class="form-description__item-title">Aдеса самовивозу </div><div class="form-horizontal"><div class="form-group half"> <input class="input_st" type="text" name="pick_up_address[]" placeholder="Адреса самовивозу" required="required" /></div><div class="form-group half"> <input class="input_st" type="text" name="pick_up_work_time[]" placeholder="09:00 - 22:00" required="required" /></div></div> <div class="remove-adr">Видалити адресу</div></div>';
}

function getUserPosition() {
    var lat = getCookie('latitude') || false;
    var lon = getCookie('longitude') || false;
    if (lat && lon) return;
    if ("geolocation" in navigator) {
        // Геолокація доступна в браузері
        $doc.find('.coordinates').text("Геолокація не підтримується в цьому браузері.");
        navigator.geolocation.getCurrentPosition(function (position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            setCookie('latitude', latitude, 1);
            setCookie('longitude', longitude, 1);
            $doc.find('.confirm-city').attr('data-lat', latitude);
            $doc.find('.confirm-city').attr('data-lon', longitude);
            $doc.find('.coordinates').text("Широта: " + latitude + ", Довгота: " + longitude);
        });
    } else {
        $doc.find('.coordinates').text("Геолокація не підтримується в цьому браузері.");
    }
}

function catalogLinkRender(e) {
    e.preventDefault();
    if (load) return;
    var $this = $(this);
    var isReviews = $this.hasClass('reviews-next-link');
    var bodySelector = '.container-js';
    var paginationSelector = '.pagination-js';
    if (isReviews) {
        bodySelector += '.testimonials'
        paginationSelector += '.testimonials-pagination'
    }
    var href = $this.attr('href');
    var $section = $this.closest('section');
    var $body = $section.find(bodySelector);
    var $pagination = $section.find(paginationSelector);
    load = true;
    $pagination.addClass('not-active');
    showPreloader();
    $.ajax({
        type: 'GET',
        url: href,
    }).done(function (r) {
        hidePreloader();
        var $requestBody = $(parser.parseFromString(r, "text/html"));
        if ($this.hasClass('next-post-link-js')) {
            $body.append($requestBody.find(bodySelector).html());
        } else {
            $body.html($requestBody.find('.container-js').html());
            window.history.pushState({}, '', href);
            $doc.find('title').text($requestBody.find('title').text());
        }
        $pagination.html($requestBody.find(paginationSelector).html());
        load = false;
        $pagination.removeClass('not-active');
        if (!$this.hasClass('next-post-link-js') && $section.length > 0) {
            $('html, body').animate({
                scrollTop: $section.offset().top
            });
        }
        initProductSliders();
    });
}

function showPreloader() {
    $('.preloader').addClass('active');
}

document.addEventListener('wpcf7mailsent', function (event) {
    $.fancybox.close();
    $('#dialog .modal-title__subtitle').text(event.detail.apiResponse.message);
    $.fancybox.open({
        src: '#dialog',
        touch: false,
        baseClass: 'thanks_msg'
    });
    $doc.find('input').removeClass('error');
    $doc.find('.ch_block_icon').removeClass('active');
    setTimeout(function () {
        $.fancybox.close();
    }, 3000);
}, false);

document.addEventListener('wpcf7invalid', function (event) {
    var invalid_fields = event.detail.apiResponse.invalid_fields;
    for (var a = 0; a < invalid_fields.length; a++) {
        var id = invalid_fields[a].error_id;
        $doc.find('input[aria-describedby="' + id + '"]').addClass('error');
    }
}, false);

function hidePreloader() {
    $('.preloader').removeClass('active')
}

function loadingImages() {
    var observer = lozad('.lozad:not(.lozad_showed)', {
        loaded: function loaded(el) {
            $(el).addClass('lozad_showed');
            if ($(el).hasClass('svg')) {
                $(el).toSVG({
                    svgClass: " svg_converted",
                    onComplete: function onComplete(data) {
                    }
                });
            }
        }
    });
    observer.observe();
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/; Secure; SameSite=None";
}

function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function initProductSliders() {

    $doc.find('.product-item__slider:not(.slick-initialized)').each(function () {
        $(this).slick({
            dots: true,
            slidesToShow: 1,
            arrows: false
        });
    });
}

function initAutocomplete() {
    if ($('#google-map-api').length === 0) return;
    $('.address-js').each(function (index) {
        var $t = $(this);
        var id = $t.attr('id');
        if (id === null || id === undefined) {
            $t.attr('id', 'address-input-' + index);
            id = $t.attr('id');
        }
        var addressField = document.querySelector('#' + id);
        var autocomplete = new google.maps.places.Autocomplete(addressField, {
            fields: ["formatted_address", "address_components", "geometry", "name"],
            strictBounds: false,
            types: [],
        });
        autocomplete.addListener("place_changed", function () {
            addressField.removeAttribute('data-selected');
            fillInAddress(autocomplete, addressField);
        });
    });
}

function fillInAddress(autocomplete, addressField) {
    var place = autocomplete.getPlace();
    var lat = place.geometry.location.lat();
    var lng = place.geometry.location.lng();
    var name = place.name;
    var formatted_address = place.formatted_address;
    var address1 = "";
    var postcode = "";
    document.getElementById('lat').value = lat;
    document.getElementById('lng').value = lng;
    for (const component of place.address_components) {
        const componentType = component.types[0];
        switch (componentType) {
            case "street_number": {
                address1 = component.long_name + ' ' + address1;
                break;
            }
            case "route": {
                address1 += component.short_name;
                break;
            }
            case "postal_code": {
                address1 += ', ' + component.long_name;
                postcode = component.long_name;
                break;
            }
            case "postal_code_suffix": {
                postcode = postcode + '-' + component.long_name;
                break;
            }
            case "locality":
                address1 += ' ' + component.long_name;
                document.getElementById('user_city').value = component.long_name;
                break;
            case "administrative_area_level_1": {
                address1 += ' ' + component.short_name;
                document.getElementById('user_region').value = component.long_name;
                break;
            }
            case "country":
                address1 += ' ' + component.long_name;
                document.getElementById('user_country').value = component.long_name;
                document.getElementById('user_country_code').value = component.short_name;
                break;
        }
    }
    addressField.value = formatted_address;
    addressField.setAttribute('data-selected', formatted_address);
    document.getElementById('user_post_code').value = postcode;
}