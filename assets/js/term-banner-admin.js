/**
 * KWV term banner image picker.
 *
 * Wires the "Banner image" field on the product category / brand term add and
 * edit screens to the WordPress media library. Selecting an image writes its
 * attachment ID into the hidden `kwv_banner_image_id` input and swaps the
 * preview; removing clears both. Presentation is the default admin styling; this
 * script only handles the media frame and field state.
 *
 * Localised strings arrive via `kwvTermBanner` (see inc/term-banner.php).
 */
( function ( $ ) {
	'use strict';

	var l10n = window.kwvTermBanner || { title: 'Select banner image', button: 'Use this image' };
	var frame;

	// Delegated so it survives the add-term form being reset/re-rendered via AJAX.
	$( document ).on( 'click', '.kwv-banner-select', function ( event ) {
		event.preventDefault();

		var $field = $( this ).closest( '.kwv-banner-field' );

		frame = wp.media( {
			title: l10n.title,
			button: { text: l10n.button },
			library: { type: 'image' },
			multiple: false,
		} );

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			var url = attachment.sizes && attachment.sizes.medium
				? attachment.sizes.medium.url
				: attachment.url;

			$field.find( '.kwv-banner-input' ).val( attachment.id );
			$field.find( '.kwv-banner-preview' ).html(
				$( '<img>', { alt: '' } )
					.attr( 'src', url )
					.css( { maxWidth: '300px', height: 'auto', display: 'block' } )
			);
			$field.find( '.kwv-banner-remove' ).show();
		} );

		frame.open();
	} );

	$( document ).on( 'click', '.kwv-banner-remove', function ( event ) {
		event.preventDefault();

		var $field = $( this ).closest( '.kwv-banner-field' );
		$field.find( '.kwv-banner-input' ).val( '' );
		$field.find( '.kwv-banner-preview' ).empty();
		$( this ).hide();
	} );
}( jQuery ) );
