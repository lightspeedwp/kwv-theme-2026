<?php
/**
 * Title: Footer
 * Slug: kwv/footer
 * Description: Dark footer with logo, call-to-action buttons, link columns, responsible-drinking notice and copyright.
 * Categories: footer
 * Keywords: footer, links, columns, copyright, social
 * Viewport Width: 1500
 * Block Types: core/template-part/footer
 * Post Types: wp_template
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Footer"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|40","right":"var:preset|spacing|20","left":"var:preset|spacing|20"},"blockGap":"var:preset|spacing|60"},"elements":{"link":{"color":{"text":"var:preset|color|neutral-400"}}}},"backgroundColor":"contrast","textColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-base-color has-contrast-background-color has-text-color has-background has-link-color" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--20)">

	<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
	<div class="wp-block-group alignwide">

		<!-- wp:group {"metadata":{"name":"Brand"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
		<div class="wp-block-group">
			<!-- wp:site-logo {"width":120} /-->

			<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
			<div class="wp-block-buttons">
				<!-- wp:button {"textColor":"base","width":100,"className":"is-style-outline","style":{"border":{"width":"1px","radius":"0px","color":"var:preset|color|base"}}} -->
				<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a class="wp-block-button__link has-base-color has-text-color has-border-color wp-element-button" style="border-color:var(--wp--preset--color--base);border-width:1px;border-radius:0px" href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'SHOP NOW', 'kwv' ); ?></a></div>
				<!-- /wp:button -->

				<!-- wp:button {"textColor":"base","width":100,"className":"is-style-outline","style":{"border":{"width":"1px","radius":"0px","color":"var:preset|color|base"}}} -->
				<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a class="wp-block-button__link has-base-color has-text-color has-border-color wp-element-button" style="border-color:var(--wp--preset--color--base);border-width:1px;border-radius:0px" href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'CONTACT', 'kwv' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Corporate Links"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"textColor":"base","fontSize":"100"} -->
			<p class="has-base-color has-text-color has-100-font-size" style="font-style:normal;font-weight:700"><?php esc_html_e( 'CORPORATE LINKS', 'kwv' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'Timeline', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'FAQ', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'Founders', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'Heritage', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'Infrastructure', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'Certifications / B-BBEE', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'Corporate Responsibility', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Featured Brands"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"textColor":"base","fontSize":"100"} -->
			<p class="has-base-color has-text-color has-100-font-size" style="font-style:normal;font-weight:700"><?php esc_html_e( 'FEATURED BRANDS', 'kwv' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'The Mentors', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'Cathedral Cellar', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'KWV Brandy', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'Wild Africa Cream', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"fontSize":"100"} -->
			<p class="has-100-font-size" style="font-style:normal;font-weight:700"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'SHOP NOW ›', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Noteworthy"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"textColor":"base","fontSize":"100"} -->
			<p class="has-base-color has-text-color has-100-font-size" style="font-style:normal;font-weight:700"><?php esc_html_e( 'NOTEWORTHY', 'kwv' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'Awards', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'New Releases', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'Winemakers', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Contact Us"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"textColor":"base","fontSize":"100"} -->
			<p class="has-base-color has-text-color has-100-font-size" style="font-style:normal;font-weight:700"><?php esc_html_e( 'CONTACT US', 'kwv' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'Emporium Opening Hours', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'KWV Head Office', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'Contact Form', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'Careers', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"100"} -->
			<p class="has-100-font-size"><a href="<?php echo esc_url( '#' ); ?>"><?php esc_html_e( 'Distributors', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}},"fontSize":"100"} -->
	<p class="has-text-align-center has-100-font-size" style="margin-top:var(--wp--preset--spacing--40)"><?php esc_html_e( 'PLEASE DRINK RESPONSIBLY', 'kwv' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:separator {"align":"wide","backgroundColor":"neutral-700","className":"is-style-wide"} -->
	<hr class="wp-block-separator alignwide has-text-color has-neutral-700-color has-alpha-channel-opacity has-neutral-700-background-color has-background is-style-wide"/>
	<!-- /wp:separator -->

	<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:paragraph {"textColor":"neutral-400","fontSize":"100"} -->
		<p class="has-neutral-400-color has-text-color has-100-font-size"><?php printf( esc_html__( 'COPYRIGHT © %s KWV', 'kwv' ), esc_html( gmdate( 'Y' ) ) ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
