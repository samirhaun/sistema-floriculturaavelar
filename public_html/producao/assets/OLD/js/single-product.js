jQuery( function( $ ) {
    "use strict";
	$( 'body' )
		// Tabs
		.on( 'init', '.wc-tabs-wrapper, .woocommerce-tabs', function() {
			$( '.wc-tab, .woocommerce-tabs .panel:not(.panel .panel)' ).hide();

			var hash  = window.location.hash;
			var url   = window.location.href;
			var $tabs = $( this ).find( '.wc-tabs, ul.tabs' ).first();

			if ( hash.toLowerCase().indexOf( 'comment-' ) >= 0 || hash === '#reviews' || hash === '#tab-reviews' ) {
				$tabs.find( 'li.reviews_tab a' ).click();
			} else if ( url.indexOf( 'comment-page-' ) > 0 || url.indexOf( 'cpage=' ) > 0 ) {
				$tabs.find( 'li.reviews_tab a' ).click();
			} else if ( hash === '#tab-additional_information' ) {
				$tabs.find( 'li.additional_information_tab a' ).click();
			} else {
				$tabs.find( 'li:first a' ).click();
			}
		} )
		.on( 'click', '.wc-tabs li a, ul.tabs li a', function( e ) {
			e.preventDefault();
			var $tab          = $( this );
			var $tabs_wrapper = $tab.closest( '.wc-tabs-wrapper, .woocommerce-tabs' );
			var $tabs         = $tabs_wrapper.find( '.wc-tabs, ul.tabs' );

			$tabs.find( 'li' ).removeClass( 'active' );
			$tabs_wrapper.find( '.wc-tab, .panel:not(.panel .panel)' ).hide();

			$tab.closest( 'li' ).addClass( 'active' );
			$tabs_wrapper.find( $tab.attr( 'href' ) ).show();
		} )
		// Review link
		.on( 'click', 'a.woocommerce-review-link', function() {
			$( '.reviews_tab a' ).click();
			return true;
		} )

	// Init Tabs and Star Ratings
	$( '.wc-tabs-wrapper, .woocommerce-tabs' ).trigger( 'init' );
    if ($('.flex-control-nav, .woocommerce-product-gallery__wrapper').length > 0) {
        $('.woocommerce-product-gallery__wrapper').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            draggable: false,
            fade: true,
            asNavFor: '.flex-control-nav'
        });
        $('.flex-control-nav').slick({
            vertical: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: '.woocommerce-product-gallery__wrapper',
            dots: false,
            arrows: true,
            prevArrow: '<span class="fa fa-angle-up prev"></span>',
            nextArrow: '<span class="fa fa-angle-down next"></span>',
            focusOnSelect: true,
            slidesMargin: 14,
            responsive: [
                {
                    breakpoint: 991,
                    settings: {
                        vertical: false,
                        slidesToShow: 3,
                        prevArrow: '<span class="fa fa-angle-left prev"></span>',
                        nextArrow: '<span class="fa fa-angle-right next"></span>',
                    }
                }
            ]
        });
    }
    function star_rating() {
        $(document).on( 'click', '.comment-form-rating p.stars a', function() {
            var $star   	= $( this ),
                $rating 	= $( this ).closest( '#star-rating' ).find( '#rating' ),
                $container 	= $( this ).closest( '.stars' );

            $rating.val( $star.text() );
            $star.siblings( 'a' ).removeClass( 'active' );
            $star.addClass( 'active' );
            $container.addClass( 'selected' );
            return false;
        } )
    }
    $(document).ready(function () {
        star_rating();
        $('.woocommerce-product-gallery .woocommerce-product-gallery__image').zoom();
    });
} );
