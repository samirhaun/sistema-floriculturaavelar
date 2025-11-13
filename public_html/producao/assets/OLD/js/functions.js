jQuery(document).ready(function ($) {
    "use strict";
    function azeroth_video() {
        $('.buttonvideo').simpleLightboxVideo();
    }
    azeroth_video();
    function azeroth_google_maps() {
        if ($('.azeroth-google-maps').length <= 0) {
            return;
        }
        $('.azeroth-google-maps').each(function () {
            var $this = $(this),
                $id = $this.attr('id'),
                $title_maps = $this.attr('data-title_maps'),
                $phone = $this.attr('data-phone'),
                $email = $this.attr('data-email'),
                $zoom = parseInt($this.attr('data-zoom'), 10),
                $latitude = $this.attr('data-latitude'),
                $longitude = $this.attr('data-longitude'),
                $address = $this.attr('data-address'),
                $map_type = $this.attr('data-map-type'),
                $pin_icon = $this.attr('data-pin-icon'),
                $modify_coloring = true,
                $saturation = $this.attr('data-saturation'),
                $hue = $this.attr('data-hue'),
                $map_style = $this.data('map-style'),
                $styles;

            if ($modify_coloring == true) {
                var $styles = [
                    {
                        stylers: [
                            {hue: $hue},
                            {invert_lightness: false},
                            {saturation: $saturation},
                            {lightness: 1},
                            {
                                featureType: "landscape.man_made",
                                stylers: [{
                                    visibility: "on"
                                }]
                            }
                        ]
                    }, {
                        "featureType": "all",
                        "elementType": "labels.text.fill",
                        "stylers": [{"saturation": 36}, {"color": "#000000"}, {"lightness": 40}]
                    }, {
                        "featureType": "all",
                        "elementType": "labels.text.stroke",
                        "stylers": [{"visibility": "on"}, {"color": "#000000"}, {"lightness": 16}]
                    }, {
                        "featureType": "all",
                        "elementType": "labels.icon",
                        "stylers": [{"visibility": "off"}]
                    }, {
                        "featureType": "administrative",
                        "elementType": "geometry.fill",
                        "stylers": [{"color": "#000000"}, {"lightness": 20}]
                    }, {
                        "featureType": "administrative",
                        "elementType": "geometry.stroke",
                        "stylers": [{"color": "#000000"}, {"lightness": 17}, {"weight": 1.2}]
                    }, {
                        "featureType": "landscape",
                        "elementType": "geometry",
                        "stylers": [{"color": "#000000"}, {"lightness": 20}]
                    }, {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [{"color": "#000000"}, {"lightness": 21}]
                    }, {
                        "featureType": "road.highway",
                        "elementType": "geometry.fill",
                        "stylers": [{"color": "#000000"}, {"lightness": 17}]
                    }, {
                        "featureType": "road.highway",
                        "elementType": "geometry.stroke",
                        "stylers": [{"color": "#000000"}, {"lightness": 29}, {"weight": 0.2}]
                    }, {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [{"color": "#000000"}, {"lightness": 18}]
                    }, {
                        "featureType": "road.local",
                        "elementType": "geometry",
                        "stylers": [{"color": "#000000"}, {"lightness": 16}]
                    }, {
                        "featureType": "transit",
                        "elementType": "geometry",
                        "stylers": [{"color": "#000000"}, {"lightness": 19}]
                    }, {
                        "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [{"color": "#000000"}, {"lightness": 17}]
                    }
                ];
            }
            var map;
            var bounds = new google.maps.LatLngBounds();
            var mapOptions = {
                zoom: $zoom,
                panControl: true,
                zoomControl: true,
                mapTypeControl: true,
                scaleControl: true,
                draggable: true,
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId[$map_type],
                styles: $styles
            };

            map = new google.maps.Map(document.getElementById($id), mapOptions);
            map.setTilt(45);

            // Multiple Markers
            var markers = [];
            var infoWindowContent = [];

            if ($latitude != '' && $longitude != '') {
                markers[0] = [$address, $latitude, $longitude];
                infoWindowContent[0] = [$address];
            }

            var infoWindow = new google.maps.InfoWindow(), marker, i;

            for (i = 0; i < markers.length; i++) {
                var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
                bounds.extend(position);
                marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: markers[i][0],
                    icon: $pin_icon
                });

                map.fitBounds(bounds);
            }

            var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function (event) {
                this.setZoom($zoom);
                google.maps.event.removeListener(boundsListener);
            });
        });
    }

    $(document).ready(function () {
        azeroth_google_maps();
    });
    function azeroth_header_sticky($elem) {
        var $this = $elem;
        $this.on('azeroth_header_sticky', function () {
            $this.each(function () {
                var previousScroll = 0,
                    header = $(this).closest('.header'),
                    header_wrap_stick = $(this),
                    header_position = $(this).find('.header-position'),
                    headerOrgOffset = header_position.offset().top;
                header_wrap_stick.css('height', header_wrap_stick.outerHeight());
                $(document).on('scroll', function (ev) {
                    var currentScroll = $(this).scrollTop();
                    if (currentScroll > headerOrgOffset) {
                        header_position.addClass('fixed');
                    } else {
                        header_position.removeClass('fixed');
                    }
                    previousScroll = currentScroll;
                });

            })
        }).trigger('azeroth_header_sticky');
        $(window).on('resize', function () {
            $this.trigger('azeroth_header_sticky');
        });
    }

    function azeroth_vertical_menu($elem) {
        /* SHOW ALL ITEM */
        var _countLi = 0,
            _verticalMenu = $elem.find('.vertical-menu'),
            _blockNav = $elem.closest('.block-nav-category'),
            _blockTitle = $elem.find('.block-title');

        $elem.each(function () {
            var _dataItem = $(this).data('items') - 1;
            _countLi = $(this).find('.vertical-menu>li').length;

            if (_countLi > (_dataItem + 1)) {
                $(this).addClass('show-button-all');
            }
            $(this).find('.vertical-menu>li').each(function (i) {
                _countLi = _countLi + 1;
                if (i > _dataItem) {
                    $(this).addClass('link-other');
                }
            })
        });

        $elem.find('.vertical-menu').each(function () {
            var _main = $(this);
            _main.children('.menu-item.parent').each(function () {
                var curent = $(this).find('.submenu');
                $(this).children('.toggle-submenu').on('click', function () {
                    $(this).parent().children('.submenu').stop().slideToggle(300);
                    _main.find('.submenu').not(curent).stop().slideUp(300);
                    $(this).parent().toggleClass('show-submenu');
                    _main.find('.menu-item.parent').not($(this).parent()).removeClass('show-submenu');
                });
                var next_curent = $(this).find('.submenu');
                next_curent.children('.menu-item.parent').each(function () {
                    var child_curent = $(this).find('.submenu');
                    $(this).children('.toggle-submenu').on('click', function () {
                        $(this).parent().parent().find('.submenu').not(child_curent).stop().slideUp(300);
                        $(this).parent().children('.submenu').stop().slideToggle(300);
                        $(this).parent().parent().find('.menu-item.parent').not($(this).parent()).removeClass('show-submenu');
                        $(this).parent().toggleClass('show-submenu');
                    })
                });
            });
        });

        /* VERTICAL MENU ITEM */
        if (_verticalMenu.length > 0) {
            $(document).on('click', '.open-cate', function (e) {
                _blockNav.find('li.link-other').each(function () {
                    $(this).slideDown();
                });
                $(this).addClass('close-cate').removeClass('open-cate').html($(this).data('closetext'));
                e.preventDefault();
            });
            $(document).on('click', '.close-cate', function (e) {
                _blockNav.find('li.link-other').each(function () {
                    $(this).slideUp();
                });
                $(this).addClass('open-cate').removeClass('close-cate').html($(this).data('alltext'));
                e.preventDefault();
            });

            _blockTitle.on('click', function () {
                $(this).toggleClass('active');
                $(this).parent().toggleClass('has-open');
                $body.toggleClass('category-open');
            });
        }
    }

    function azeroth_auto_width_vertical_menu() {
        var full_width = parseInt($('.container').outerWidth()) - 50;
        var menu_width = parseInt($('.vertical-menu').outerWidth());
        var w = (full_width - menu_width);
        $('.vertical-menu').find('.megamenu').each(function () {
            $(this).css('max-width', w + 'px');
        });
    };

    function azeroth_animation_tabs($elem, _tab_animated) {
        _tab_animated = (_tab_animated == undefined || _tab_animated == "") ? '' : _tab_animated;
        if (_tab_animated == "") {
            return;
        }
        $elem.find('.owl-slick .slick-active, .product-list-grid .product-item').each(function (i) {
            var _this = $(this),
                _style = _this.attr('style'),
                _delay = i * 200;

            _style = (_style == undefined) ? '' : _style;
            _this.attr('style', _style +
                ';-webkit-animation-delay:' + _delay + 'ms;'
                + '-moz-animation-delay:' + _delay + 'ms;'
                + '-o-animation-delay:' + _delay + 'ms;'
                + 'animation-delay:' + _delay + 'ms;'
            ).addClass(_tab_animated + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                _this.removeClass(_tab_animated + ' animated');
                _this.attr('style', _style);
            });
        });
    }

    function azeroth_init_carousel($elem) {
        $elem.not('.slick-initialized').each(function () {
            var _this = $(this),
                _responsive = _this.data('responsive'),
                _config = [];
            if (_this.hasClass('slick-vertical')) {
                _config.prevArrow = '<span class="fa fa-angle-up prev"></span>';
                _config.nextArrow = '<span class="fa fa-angle-down next"></span>';
            } else {
                _config.prevArrow = '<span class="fa fa-angle-left prev"></span>';
                _config.nextArrow = '<span class="fa fa-angle-right next"></span>';
            }
            _config.responsive = _responsive;

            _this.on('init', function (event, slick, direction) {
                azeroth_popover_button();
            });
            _this.slick(_config);
        });
    }

    function azeroth_countdown($elem) {
        $elem.on('azeroth_countdown', function () {
            $elem.each(function () {
                var _this = $(this),
                    _text_countdown = '';

                _this.countdown(_this.data('datetime'), function (event) {
                    _text_countdown = event.strftime(
                        '<span class="days"><span class="number">%D</span><span class="text">Days</span></span>' +
                        '<span class="hour"><span class="number">%H</span><span class="text">Hours</span></span>' +
                        '<span class="mins"><span class="number">%M</span><span class="text">Mins</span></span>' +
                        '<span class="secs"><span class="number">%S</span><span class="text">Secs</span></span>'
                    );
                    _this.html(_text_countdown);
                });
            });
        }).trigger('azeroth_countdown');
    }

    // azeroth_init_dropdown
    $(document).on('click', function (event) {
        var _target = $(event.target).closest('.azeroth-dropdown'),
            _parent = $('.azeroth-dropdown');

        if (_target.length > 0) {
            _parent.not(_target).removeClass('open');
            if (
                $(event.target).is('[data-azeroth="azeroth-dropdown"]') ||
                $(event.target).closest('[data-azeroth="azeroth-dropdown"]').length > 0
            ) {
                _target.toggleClass('open');
                event.preventDefault();
            }
        } else {
            $('.azeroth-dropdown').removeClass('open');
        }
    });

    // category product
    function azeroth_category_product($elem) {
        $elem.each(function () {
            var _main = $(this);
            _main.find('.cat-parent').each(function () {
                if ($(this).hasClass('current-cat-parent')) {
                    $(this).addClass('show-sub');
                    $(this).children('.children').stop().slideDown(300);
                }
                $(this).children('.children').before('<span class="carets"></span>');
            });
            _main.children('.cat-parent').each(function () {
                var curent = $(this).find('.children');
                $(this).children('.carets').on('click', function () {
                    $(this).parent().toggleClass('show-sub');
                    $(this).parent().children('.children').stop().slideToggle(300);
                    _main.find('.children').not(curent).stop().slideUp(300);
                    _main.find('.cat-parent').not($(this).parent()).removeClass('show-sub');
                });
                var next_curent = $(this).find('.children');
                next_curent.children('.cat-parent').each(function () {
                    var child_curent = $(this).find('.children');
                    $(this).children('.carets').on('click', function () {
                        $(this).parent().toggleClass('show-sub');
                        $(this).parent().parent().find('.cat-parent').not($(this).parent()).removeClass('show-sub');
                        $(this).parent().parent().find('.children').not(child_curent).stop().slideUp(300);
                        $(this).parent().children('.children').stop().slideToggle(300);
                    })
                });
            });
        });
    }

    function azeroth_magnific_popup() {
        $('.product-360-button a').magnificPopup({
            type: 'inline',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            disableOn: false,
            preloader: false,
            fixedContentPos: false,
            callbacks: {
                open: function () {
                }
            }
        });
    }

    function azeroth_better_equal_elems($elem) {
        $elem.each(function () {
            if ($(this).find('.equal-elem').length) {
                $(this).find('.equal-elem').css({
                    'height': 'auto'
                });
                var _height = 0;
                $(this).find('.equal-elem').each(function () {
                    if (_height < $(this).height()) {
                        _height = $(this).height();
                    }
                });
                $(this).find('.equal-elem').height(_height);
            }
        });
    }

    // Azeroth Ajax Tabs
    $(document).on('click', '.azeroth-tabs .tab-link a, .azeroth-accordion .panel-heading a', function (e) {
        e.preventDefault();
        var _this = $(this),
            _ID = _this.data('id'),
            _tabID = _this.attr('href'),
            _ajax_tabs = _this.data('ajax'),
            _sectionID = _this.data('section'),
            _tab_animated = _this.data('animate'),
            _loaded = _this.closest('.tab-link,.azeroth-accordion').find('a.loaded').attr('href');

        if (_ajax_tabs == 1 && !_this.hasClass('loaded')) {
            $(_tabID).closest('.tab-container,.azeroth-accordion').addClass('loading');
            _this.parent().addClass('active').siblings().removeClass('active');
            $.ajax({
                type: 'POST',
                url: azeroth_ajax_frontend.ajaxurl,
                data: {
                    action: 'azeroth_ajax_tabs',
                    security: azeroth_ajax_frontend.security,
                    id: _ID,
                    section_id: _sectionID,
                },
                success: function (response) {
                    if (response['success'] == 'ok') {
                        $(_tabID).html($(response['html']).find('.vc_tta-panel-body').html());
                        $(_tabID).closest('.tab-container,.azeroth-accordion').removeClass('loading');
                        $('[href="' + _loaded + '"]').removeClass('loaded');
                        azeroth_countdown($(_tabID).find('.azeroth-countdown'));
                        azeroth_init_carousel($(_tabID).find('.owl-slick'));
                        if ($('.owl-slick .product-item').length > 0) {
                            azeroth_hover_product_item($(_tabID).find('.owl-slick .row-item,' +
                                '.owl-slick .product-item.style-1,' +
                                '.owl-slick .product-item.style-2,' +
                                '.owl-slick .product-item.style-3,' +
                                '.owl-slick .product-item.style-4'));
                        }
                        if ($(_tabID).find('.variations_form').length > 0) {
                            $(_tabID).find('.variations_form').each(function () {
                                $(this).wc_variation_form();
                            });
                        }
                        $(_tabID).trigger('azeroth_ajax_tabs_complete');
                        _this.addClass('loaded');
                        $(_loaded).html('');
                    } else {
                        $(_tabID).closest('.tab-container,.azeroth-accordion').removeClass('loading');
                        $(_tabID).html('<strong>Error: Can not Load Data ...</strong>');
                    }
                    /* for accordion */
                    _this.closest('.panel-default').addClass('active').siblings().removeClass('active');
                    _this.closest('.azeroth-accordion').find(_tabID).slideDown(400);
                    _this.closest('.azeroth-accordion').find('.panel-collapse').not(_tabID).slideUp(400);
                },
                complete: function () {
                    $(_tabID).addClass('active').siblings().removeClass('active');
                    setTimeout(function (args) {
                        azeroth_animation_tabs($(_tabID), _tab_animated);
                    }, 10);
                }
            });
        } else {
            _this.parent().addClass('active').siblings().removeClass('active');
            $(_tabID).addClass('active').siblings().removeClass('active');
            /* for accordion */
            _this.closest('.panel-default').addClass('active').siblings().removeClass('active');
            _this.closest('.azeroth-accordion').find(_tabID).slideDown(400);
            _this.closest('.azeroth-accordion').find('.panel-collapse').not(_tabID).slideUp(400);
            azeroth_animation_tabs($(_tabID), _tab_animated);
        }
    });

    $(document).on('click', 'a.backtotop', function (e) {
        $('html, body').animate({scrollTop: 0}, 800);
        e.preventDefault();
    });
    $(document).on('scroll', function () {
        if ($(window).scrollTop() > 200) {
            $('.backtotop').addClass('active');
        } else {
            $('.backtotop').removeClass('active');
        }
    });
    $(document).on('click', '.quantity .quantity-plus', function (e) {
        var _this = $(this).closest('.quantity').find('input.qty'),
            _value = parseInt(_this.val()),
            _max = parseInt(_this.attr('max')),
            _step = parseInt(_this.data('step')),
            _value = _value + _step;
        if (_max && _value > _max) {
            _value = _max;
        }
        _this.val(_value);
        _this.trigger("change");
        e.preventDefault();
    });

    $(document).on('change', function () {
        $('.quantity').each(function () {
            var _this = $(this).find('input.qty'),
                _value = _this.val(),
                _max = parseInt(_this.attr('max'));
            if (_value > _max) {
                $(this).find('.quantity-plus').css('pointer-events', 'none')
            } else {
                $(this).find('.quantity-plus').css('pointer-events', 'auto')
            }
        })
    });

    $(document).on('click', '.quantity .quantity-minus', function (e) {
        var _this = $(this).closest('.quantity').find('input.qty'),
            _value = parseInt(_this.val()),
            _min = parseInt(_this.attr('min')),
            _step = parseInt(_this.data('step')),
            _value = _value - _step;
        if (_min && _value < _min) {
            _value = _min;
        }
        if (!_min && _value < 0) {
            _value = 0;
        }
        _this.val(_value);
        _this.trigger("change");
        e.preventDefault();
    });

    function azeroth_product_gallery($elem) {
        $elem.each(function () {
            var _items = $(this).closest('.product-inner').data('items'),
                _main_slide = $(this).find('.product-gallery-slick'),
                _dot_slide = $(this).find('.gallery-dots');

            _main_slide.not('.slick-initialized').each(function () {
                var _this = $(this),
                    _config = [];

                if ($('body').hasClass('rtl')) {
                    _config.rtl = true;
                }
                _config.prevArrow = '<span class="fa fa-angle-left prev"></span>';
                _config.nextArrow = '<span class="fa fa-angle-right next"></span>';
                _config.cssEase = 'linear';
                _config.infinite = false;
                _config.fade = true;
                _config.slidesMargin = 0;
                _config.arrows = false;
                _config.asNavFor = _dot_slide;
                _this.slick(_config);
            });
            _dot_slide.not('.slick-initialized').each(function () {
                var _config = [];
                if ($('body').hasClass('rtl')) {
                    _config.rtl = true;
                }
                _config.slidesToShow = _items;
                _config.infinite = false;
                _config.focusOnSelect = true;
                _config.vertical = true;
                _config.slidesMargin = 10;
                _config.prevArrow = '<span class="fa fa-angle-up prev"></span>';
                _config.nextArrow = '<span class="fa fa-angle-down next"></span>';
                _config.asNavFor = _main_slide;
                _config.responsive = [
                    {
                        breakpoint: 1199,
                        settings: {
                            vertical: false,
                            prevArrow: '<span class="fa fa-angle-left prev"></span>',
                            nextArrow: '<span class="fa fa-angle-right next"></span>',
                        }
                    }
                ];
                $(this).slick(_config);
            })
        })
    }

    function azeroth_hover_product_item($elem) {
        $elem.each(function () {
            var _winw = $(window).innerWidth();
            if (_winw > 1024) {
                $(this).on('mouseenter', function () {
                    $(this).closest('.slick-list').css({
                        'padding-left': '15px',
                        'padding-right': '15px',
                        'padding-bottom': '100px',
                        'margin-left': '-15px',
                        'margin-right': '-15px',
                        'margin-bottom': '-100px'
                    });
                });
                $(this).on('mouseleave', function () {
                    $(this).closest('.slick-list').css({
                        'padding-left': '0',
                        'padding-right': '0',
                        'padding-bottom': '0',
                        'margin-left': '0',
                        'margin-right': '0',
                        'margin-bottom': '0'
                    });
                });
            }
        });
    }

    function azeroth_popover_button() {
        $('[data-toggle="tooltip"]').each(function () {
            $(this).tooltip({
                title: $(this).text()
            });
        });
        // .product-item .add-to-cart a
        $('.product-inner.tooltip-all-top .yith-wcqv-button,.product-inner.tooltip-top .add-to-cart a, .product-inner.tooltip-top .compare,.product-inner.tooltip-top .yith-wcwl-add-to-wishlist a').each(function () {
            $(this).tooltip({
                title: $(this).text(),
                trigger: 'hover',
                placement: 'top'
            });
        });
        $('.product-inner.tooltip-left .add-to-cart a, .product-inner.tooltip-left .yith-wcqv-button,.product-inner.tooltip-left .compare,.product-inner.tooltip-left .yith-wcwl-add-to-wishlist a').each(function () {
            $(this).tooltip({
                title: $(this).text(),
                trigger: 'hover',
                placement: 'left'
            });
        });
        $('.product-inner.tooltip-right .add-to-cart a, .product-inner.tooltip-right .yith-wcqv-button,.product-inner.tooltip-right .compare,.product-inner.tooltip-right .yith-wcwl-add-to-wishlist a').each(function () {
            $(this).tooltip({
                title: $(this).text(),
                trigger: 'hover',
                placement: 'right'
            });
        });
    }

    function azeroth_threesixty(ev) {
        var imageLink = (location.pathname.replace('single-product-360deg.html', 'assets/images'));
        $('.azeroth-threed-view').ThreeSixty({
            totalFrames: 12,
            endFrame: 12,
            currentFrame: 1,
            imgList: '.threed-view-images',
            progress: '.spinner',
            imgArray: [imageLink+'/is_main.jpg', imageLink+'/is_main1.jpg', imageLink+'/is_main2.jpg', imageLink+'/is_main4.jpg', imageLink+'/is_main5.jpg', imageLink+'/is_main6.jpg', imageLink+'/is_main7.jpg', imageLink+'/is_main8.jpg', imageLink+'/is_main9.jpg', imageLink+'/is_main10.jpg', imageLink+'/is_main11.jpg', imageLink+'/is_main12.jpg'],
            height: 600,
            width: 600,
            responsive: true,
            navigation: true
        });
    }

    if ($('.azeroth-threed-view').length > 0) {
        azeroth_threesixty();
    }
    // Load all needed functions when document ready
    if ($('.vertical-menu').length > 0) {
        azeroth_auto_width_vertical_menu();
    }
    if ($('.owl-slick').length) {
        $('.owl-slick').each(function () {
            azeroth_init_carousel($(this));
        });
    }
    // Window load
    $(window).load(function () {
        if ($('.azeroth-countdown').length) {
            azeroth_countdown($('.azeroth-countdown'));
        }
        if ($('.product-gallery').length) {
            azeroth_product_gallery($('.product-gallery'));
        }
        if ($('.owl-slick .product-item').length) {
            azeroth_hover_product_item($('.azeroth-products .owl-slick .row-item,' +
                '.owl-slick .product-item'));
        }
        if ($('.block-nav-category').length) {
            azeroth_vertical_menu($('.block-nav-category'));
        }
        if ($('.widget_azeroth_nav_menu').length) {
            azeroth_vertical_menu($('.widget_azeroth_nav_menu'));
        }
        if ($('.category-search-option').length) {
            $('.category-search-option').chosen();
        }
        if ($('.category .chosen-results').length && $.fn.scrollbar) {
            $('.category .chosen-results').scrollbar();
        }
        if ($('.block-minicart .cart_list').length && $.fn.scrollbar) {
            $('.block-minicart .cart_list').scrollbar();
        }
        if ($('.widget_product_categories .product-categories').length) {
            azeroth_category_product($('.widget_product_categories .product-categories'));
        }
        if ($('.header-sticky .header-wrap-stick').length) {
            azeroth_header_sticky($('.header-sticky .header-wrap-stick'));
        }
        if ($('.equal-container.better-height').length) {
            azeroth_better_equal_elems($('.equal-container.better-height'));
        }
        azeroth_popover_button();
        azeroth_magnific_popup();

    });

    // Window resize
    $(window).resize(function () {
        if ($('.vertical-menu').length > 0) {
            azeroth_auto_width_vertical_menu();
        }
        if ($('.equal-container.better-height').length) {
            azeroth_better_equal_elems($('.equal-container.better-height'));
        }
    });
});
