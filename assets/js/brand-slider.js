/**
 * Brand page product slider.
 *
 * The brand archive (patterns/woo-brand-archive.php) renders the brand's
 * products as an ordinary WooCommerce Product Collection — a list, one item per
 * product, each item the editable "Brand Product Slide" template part. This
 * script turns that list into a one-product-at-a-time slider:
 *
 * - previous / next arrows, swipe, and arrow-key navigation;
 * - a strip of product thumbnails, built from each slide's own product image,
 *   in the "Product Thumbnails" group that follows the slider;
 * - products that are not on screen are made inert, so their links and Add to
 *   Cart buttons are out of the tab order and hidden from assistive tech.
 *
 * Swiper (full bundle, from the Carousel Block plugin) is a declared dependency;
 * without it, or without JavaScript, the products stay a stacked list. Markup
 * is only decorated — never moved — so WooCommerce's own Add to Cart behaviour
 * on each product keeps working untouched.
 */
( function () {
	'use strict';

	var i18n = window.kwvBrandSlider || {};
	var reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );

	var CHEVRON =
		'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 48" width="24" height="48" fill="none" aria-hidden="true" focusable="false">' +
		'<path d="M20 4 4 24l16 20" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>';

	/**
	 * Build a previous/next button.
	 *
	 * @param {string} direction 'prev' or 'next'.
	 * @param {string} label     Accessible name.
	 * @return {HTMLButtonElement} The button.
	 */
	function arrow( direction, label ) {
		var button = document.createElement( 'button' );
		button.type = 'button';
		button.className = 'kwv-brand-slider__arrow kwv-brand-slider__arrow--' + direction;
		button.setAttribute( 'aria-label', label );
		button.innerHTML = CHEVRON;
		return button;
	}

	/**
	 * The product name shown on a slide.
	 *
	 * @param {Element} slide A product list item.
	 * @return {string} The name, or an empty string.
	 */
	function productName( slide ) {
		var title = slide.querySelector( '.wp-block-post-title' );
		return title ? title.textContent.trim() : '';
	}

	/**
	 * Build the thumbnail strip for a set of slides.
	 *
	 * Each thumbnail reuses the slide's product image, so it is whatever image
	 * the slide shows — no second image setting to keep in step.
	 *
	 * @param {Element[]} slides The product list items.
	 * @return {HTMLElement|null} The strip, or null when no slide has an image.
	 */
	function buildThumbs( slides ) {
		var strip = document.createElement( 'div' );
		var wrapper = document.createElement( 'div' );
		var found = false;

		strip.className = 'swiper kwv-brand-slider__thumbs';
		strip.setAttribute( 'role', 'group' );
		strip.setAttribute( 'aria-label', i18n.thumbnails || 'Choose a product' );
		wrapper.className = 'swiper-wrapper';

		slides.forEach( function ( slide ) {
			var source = slide.querySelector( '.kwv-brand-slide__media img, .wc-block-components-product-image img' );
			var name = productName( slide );
			var button = document.createElement( 'button' );

			button.type = 'button';
			button.className = 'swiper-slide kwv-brand-slider__thumb';
			button.setAttribute( 'aria-label', ( i18n.show || 'Show %s' ).replace( '%s', name ) );

			if ( source ) {
				var image = document.createElement( 'img' );
				image.src = source.currentSrc || source.src;
				image.alt = '';
				image.loading = 'lazy';
				image.decoding = 'async';
				button.appendChild( image );
				found = true;
			} else {
				button.textContent = name;
			}

			wrapper.appendChild( button );
		} );

		strip.appendChild( wrapper );

		return found ? strip : null;
	}

	/**
	 * Keep only the visible product reachable by keyboard and assistive tech.
	 *
	 * @param {Object} swiper The main Swiper instance.
	 */
	function syncInert( swiper ) {
		swiper.slides.forEach( function ( slide, index ) {
			slide.inert = index !== swiper.activeIndex;
		} );
	}

	/**
	 * Mark the current thumbnail for assistive tech.
	 *
	 * @param {Object}      swiper The main Swiper instance.
	 * @param {Element|null} strip  The thumbnail strip.
	 */
	function syncCurrentThumb( swiper, strip ) {
		if ( ! strip ) {
			return;
		}

		Array.prototype.forEach.call( strip.querySelectorAll( '.kwv-brand-slider__thumb' ), function ( thumb, index ) {
			if ( index === swiper.activeIndex ) {
				thumb.setAttribute( 'aria-current', 'true' );
			} else {
				thumb.removeAttribute( 'aria-current' );
			}
		} );
	}

	/**
	 * Turn one Product Collection into a slider.
	 *
	 * @param {Element} root The `.kwv-brand-slider` Product Collection.
	 */
	function init( root ) {
		var list = root.querySelector( '.wc-block-product-template' );
		var slides = list ? Array.prototype.slice.call( list.children ) : [];
		var thumbsHost = document.querySelector( '.kwv-brand-slider-thumbs' );
		var speed = reducedMotion.matches ? 0 : 450;
		var thumbs = null;
		var strip = null;

		if ( ! list || ! slides.length ) {
			return;
		}

		// A single product needs no slider: leave it as it is.
		if ( slides.length < 2 ) {
			root.classList.add( 'kwv-brand-slider--single' );
			return;
		}

		root.classList.add( 'swiper' );
		list.classList.add( 'swiper-wrapper' );
		slides.forEach( function ( slide ) {
			slide.classList.add( 'swiper-slide' );

			// WooCommerce wraps an unlinked product image in an inert "#" anchor;
			// keep that dead link out of the tab order.
			var deadLink = slide.querySelector( '.wc-block-components-product-image a[href="#"]' );
			if ( deadLink ) {
				deadLink.setAttribute( 'tabindex', '-1' );
			}
		} );

		var prev = arrow( 'prev', i18n.previous || 'Previous product' );
		var next = arrow( 'next', i18n.next || 'Next product' );
		root.appendChild( prev );
		root.appendChild( next );

		if ( thumbsHost ) {
			strip = buildThumbs( slides );
		}

		if ( strip ) {
			thumbsHost.appendChild( strip );
			thumbs = new window.Swiper( strip, {
				slidesPerView: 'auto',
				spaceBetween: 16,
				freeMode: true,
				centerInsufficientSlides: true,
				watchSlidesProgress: true,
				speed: speed,
				a11y: { enabled: false },
			} );
		}

		var main = new window.Swiper( root, {
			slidesPerView: 1,
			spaceBetween: 32,
			speed: speed,
			autoHeight: true,
			navigation: { prevEl: prev, nextEl: next },
			keyboard: { enabled: true, onlyInViewport: true },
			thumbs: thumbs ? { swiper: thumbs } : undefined,
			a11y: {
				prevSlideMessage: i18n.previous || 'Previous product',
				nextSlideMessage: i18n.next || 'Next product',
				slideLabelMessage: i18n.slide || 'Product {{index}} of {{slidesLength}}',
				// The list keeps its own list/listitem semantics.
				slideRole: 'listitem',
				containerRoleDescriptionMessage: null,
				itemRoleDescriptionMessage: null,
			},
			on: {
				afterInit: function ( swiper ) {
					syncInert( swiper );
					syncCurrentThumb( swiper, strip );
				},
				slideChange: function ( swiper ) {
					syncInert( swiper );
					syncCurrentThumb( swiper, strip );
				},
			},
		} );

		// Swiper's thumbnail module only answers pointer taps. A click handler
		// covers the keyboard too (Enter/Space on a focused thumbnail).
		if ( strip ) {
			Array.prototype.forEach.call( strip.querySelectorAll( '.kwv-brand-slider__thumb' ), function ( thumb, index ) {
				thumb.addEventListener( 'click', function () {
					main.slideTo( index );
				} );
			} );
		}
	}

	/**
	 * Start every brand slider on the page, or stand down without Swiper.
	 */
	function boot() {
		if ( 'function' !== typeof window.Swiper ) {
			// No slider: show the full list again.
			document.documentElement.classList.remove( 'kwv-brand-slider-js' );
			return;
		}

		Array.prototype.forEach.call( document.querySelectorAll( '.kwv-brand-slider' ), init );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', boot );
	} else {
		boot();
	}
} )();
