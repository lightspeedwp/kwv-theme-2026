<?php
/**
 * Title: Pricing Table With Testimonials
 * Slug: kwv/pricing-table-with-testimonials
 * Description:
 * Categories: kwv/pricing
 * Keywords: cta, button, call to action, purchase
 * Viewport Width: 1500
 * Block Types:
 * Post Types:
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Pricing Table With Testimonials","categories":["kwv/pricing"],"patternName":"kwv/pricing-table-with-testimonials"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","right":"var:preset|spacing|30","left":"var:preset|spacing|30"},"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|50"}},"backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull has-neutral-200-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--30)">
	<!-- wp:group {"metadata":{"name":"Titles"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
		<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"primary","fontSize":"small"} -->
			<p class="has-text-align-center has-brand-500-color has-text-color has-300-font-size" style="font-style:normal;font-weight:500"><?php esc_html_e( 'Simple Pricing', 'kwv' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"textAlign":"center","className":"wp-block-heading"} -->
			<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Small prices, huge value', 'kwv' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center"} -->
			<p class="has-text-align-center"><?php esc_html_e( 'Easily create beautiful and responsive pricing tables with Ollie patterns. Connect your eCommerce solution and start making bank.', 'kwv' ); ?></p>
		<!-- /wp:paragraph -->
		</div>
	<!-- /wp:group -->
	<!-- wp:columns {"verticalAlignment":"top","metadata":{"name":"Pricing Tables"},"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
		<div class="wp-block-columns alignwide are-vertically-aligned-top">
		<!-- wp:column {"verticalAlignment":"top","metadata":{"name":"Pricing Table"},"className":"is-style-default","style":{"border":{"radius":"5px","width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base","borderColor":"border-light"} -->
			<div class="wp-block-column is-vertically-aligned-top is-style-default has-border-color has-neutral-300-border-color has-base-background-color has-background" style="border-width:1px;border-radius:5px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
			<!-- wp:group {"metadata":{"name":"Price"},"style":{"spacing":{"blockGap":"10px"}},"fontSize":"base","layout":{"type":"flex","flexWrap":"nowrap","orientation":"horizontal"}} -->
				<div class="wp-block-group has-200-font-size">
				<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500","lineHeight":"1"}},"fontSize":"x-large","fontFamily":"primary"} -->
					<p class="has-primary-font-family has-600-font-size" style="font-style:normal;font-weight:500;line-height:1"><?php esc_html_e( '$9', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"1.4rem"}}},"textColor":"secondary","fontSize":"small","fontFamily":"primary"} -->
					<p class="has-neutral-700-color has-text-color has-primary-font-family has-300-font-size" style="margin-top:1.4rem"><?php esc_html_e( 'per month', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
				</div>
			<!-- /wp:group -->
			<!-- wp:group {"metadata":{"name":"Price Description"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
				<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"medium"} -->
					<p class="has-400-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e( 'Beginner', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"textColor":"secondary","fontSize":"small"} -->
					<p class="has-neutral-700-color has-text-color has-300-font-size"><?php esc_html_e( 'Great for beginners who want to give your product a trial run and see if it fits their workflow.', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
				</div>
			<!-- /wp:group -->
			<!-- wp:buttons -->
				<div class="wp-block-buttons">
				<!-- wp:button {"width":100,"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
					<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><?php esc_html_e( 'Get Started', 'kwv' ); ?></a></div>
				<!-- /wp:button -->
				</div>
			<!-- /wp:buttons -->
			<!-- wp:group {"metadata":{"name":"Features"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"fontSize":"small","layout":{"type":"constrained"}} -->
				<div class="wp-block-group has-300-font-size">
				<!-- wp:group {"metadata":{"name":"Feature"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
					<div class="wp-block-group">
					<!-- wp:paragraph -->
						<p><strong><?php esc_html_e( '✓', 'kwv' ); ?></strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph -->
						<p><?php esc_html_e( 'Publishing Suite', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					</div>
				<!-- /wp:group -->
				<!-- wp:separator {"className":"is-style-separator-thin","backgroundColor":"border-light"} -->
					<hr class="wp-block-separator has-text-color has-neutral-300-color has-alpha-channel-opacity has-neutral-300-background-color has-background is-style-separator-thin"/>
				<!-- /wp:separator -->
				<!-- wp:group {"metadata":{"name":"Feature"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
					<div class="wp-block-group">
					<!-- wp:paragraph -->
						<p><strong><?php esc_html_e( '✓', 'kwv' ); ?></strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph -->
						<p><?php esc_html_e( 'Advanced Tools', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					</div>
				<!-- /wp:group -->
				<!-- wp:separator {"className":"is-style-separator-thin","backgroundColor":"border-light"} -->
					<hr class="wp-block-separator has-text-color has-neutral-300-color has-alpha-channel-opacity has-neutral-300-background-color has-background is-style-separator-thin"/>
				<!-- /wp:separator -->
				<!-- wp:group {"metadata":{"name":"Feature"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
					<div class="wp-block-group">
					<!-- wp:paragraph -->
						<p><strong><?php esc_html_e( '✓', 'kwv' ); ?></strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph -->
						<p><?php esc_html_e( 'Priority Support', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					</div>
				<!-- /wp:group -->
				<!-- wp:separator {"className":"is-style-separator-thin","backgroundColor":"border-light"} -->
					<hr class="wp-block-separator has-text-color has-neutral-300-color has-alpha-channel-opacity has-neutral-300-background-color has-background is-style-separator-thin"/>
				<!-- /wp:separator -->
				<!-- wp:group {"metadata":{"name":"Feature"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
					<div class="wp-block-group">
					<!-- wp:paragraph -->
						<p><strong><?php esc_html_e( '✓', 'kwv' ); ?></strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph -->
						<p><?php esc_html_e( 'Blazing-Fast Performance', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					</div>
				<!-- /wp:group -->
				</div>
			<!-- /wp:group -->
			</div>
		<!-- /wp:column -->
		<!-- wp:column {"verticalAlignment":"top","metadata":{"name":"Pricing Table"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"border":{"radius":"5px"}},"backgroundColor":"main","textColor":"base"} -->
			<div class="wp-block-column is-vertically-aligned-top has-base-color has-contrast-background-color has-text-color has-background" style="border-radius:5px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
			<!-- wp:group {"metadata":{"name":"Price"},"style":{"spacing":{"blockGap":"10px"}},"fontSize":"base","layout":{"type":"flex","flexWrap":"nowrap","orientation":"horizontal"}} -->
				<div class="wp-block-group has-200-font-size">
				<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500","lineHeight":"1"}},"fontSize":"x-large","fontFamily":"primary"} -->
					<p class="has-primary-font-family has-600-font-size" style="font-style:normal;font-weight:500;line-height:1"><?php esc_html_e( '$29', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"1.4rem"}}},"textColor":"main-accent","fontSize":"small","fontFamily":"primary"} -->
					<p class="has-neutral-300-color has-text-color has-primary-font-family has-300-font-size" style="margin-top:1.4rem"><?php esc_html_e( 'per month', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
				</div>
			<!-- /wp:group -->
			<!-- wp:group {"metadata":{"name":"Price Description"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
				<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"medium"} -->
					<p class="has-400-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e( 'Professional', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"textColor":"main-accent","fontSize":"small"} -->
					<p class="has-neutral-300-color has-text-color has-300-font-size"><?php esc_html_e( 'Great for professionals who are building a lot of WordPress websites and need all the tools.', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
				</div>
			<!-- /wp:group -->
			<!-- wp:buttons -->
				<div class="wp-block-buttons">
				<!-- wp:button {"width":100,"className":"is-style-button-light","style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
					<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-button-light"><a class="wp-block-button__link wp-element-button" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><?php esc_html_e( 'Get Started', 'kwv' ); ?></a></div>
				<!-- /wp:button -->
				</div>
			<!-- /wp:buttons -->
			<!-- wp:group {"metadata":{"name":"Features"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"fontSize":"small","layout":{"type":"constrained"}} -->
				<div class="wp-block-group has-300-font-size">
				<!-- wp:group {"metadata":{"name":"Feature"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
					<div class="wp-block-group">
					<!-- wp:paragraph -->
						<p><strong><?php esc_html_e( '✓', 'kwv' ); ?></strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph -->
						<p><?php esc_html_e( 'Everything in Essential plus...', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					</div>
				<!-- /wp:group -->
				<!-- wp:separator {"className":"is-style-separator-thin","backgroundColor":"border-dark"} -->
					<hr class="wp-block-separator has-text-color has-border-dark-color has-alpha-channel-opacity has-border-dark-background-color has-background is-style-separator-thin"/>
				<!-- /wp:separator -->
				<!-- wp:group {"metadata":{"name":"Feature"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
					<div class="wp-block-group">
					<!-- wp:paragraph -->
						<p><strong><?php esc_html_e( '✓', 'kwv' ); ?></strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph -->
						<p><?php esc_html_e( 'Video Course', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					</div>
				<!-- /wp:group -->
				<!-- wp:separator {"className":"is-style-separator-thin","backgroundColor":"border-dark"} -->
					<hr class="wp-block-separator has-text-color has-border-dark-color has-alpha-channel-opacity has-border-dark-background-color has-background is-style-separator-thin"/>
				<!-- /wp:separator -->
				<!-- wp:group {"metadata":{"name":"Feature"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
					<div class="wp-block-group">
					<!-- wp:paragraph -->
						<p><strong><?php esc_html_e( '✓', 'kwv' ); ?></strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph -->
						<p><?php esc_html_e( 'Private Coaching', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					</div>
				<!-- /wp:group -->
				<!-- wp:separator {"className":"is-style-separator-thin","backgroundColor":"border-dark"} -->
					<hr class="wp-block-separator has-text-color has-border-dark-color has-alpha-channel-opacity has-border-dark-background-color has-background is-style-separator-thin"/>
				<!-- /wp:separator -->
				<!-- wp:group {"metadata":{"name":"Feature"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
					<div class="wp-block-group">
					<!-- wp:paragraph -->
						<p><strong><?php esc_html_e( '✓', 'kwv' ); ?></strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph -->
						<p><?php esc_html_e( 'Access to Slack', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					</div>
				<!-- /wp:group -->
				<!-- wp:separator {"className":"is-style-separator-thin","backgroundColor":"border-dark"} -->
					<hr class="wp-block-separator has-text-color has-border-dark-color has-alpha-channel-opacity has-border-dark-background-color has-background is-style-separator-thin"/>
				<!-- /wp:separator -->
				<!-- wp:group {"metadata":{"name":"Feature"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
					<div class="wp-block-group">
					<!-- wp:paragraph -->
						<p><strong><?php esc_html_e( '✓', 'kwv' ); ?></strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph -->
						<p><?php esc_html_e( 'Online Workshops', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					</div>
				<!-- /wp:group -->
				</div>
			<!-- /wp:group -->
			</div>
		<!-- /wp:column -->
		<!-- wp:column {"verticalAlignment":"top","metadata":{"name":"Pricing Table"},"className":"is-style-default","style":{"border":{"radius":"5px","width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base","borderColor":"border-light"} -->
			<div class="wp-block-column is-vertically-aligned-top is-style-default has-border-color has-neutral-300-border-color has-base-background-color has-background" style="border-width:1px;border-radius:5px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
			<!-- wp:group {"metadata":{"name":"Price"},"style":{"spacing":{"blockGap":"10px"}},"fontSize":"base","layout":{"type":"flex","flexWrap":"nowrap","orientation":"horizontal"}} -->
				<div class="wp-block-group has-200-font-size">
				<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500","lineHeight":"1"}},"fontSize":"x-large","fontFamily":"primary"} -->
					<p class="has-primary-font-family has-600-font-size" style="font-style:normal;font-weight:500;line-height:1"><?php esc_html_e( '$19', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"1.4rem"}}},"textColor":"secondary","fontSize":"small","fontFamily":"primary"} -->
					<p class="has-neutral-700-color has-text-color has-primary-font-family has-300-font-size" style="margin-top:1.4rem"><?php esc_html_e( 'per month', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
				</div>
			<!-- /wp:group -->
			<!-- wp:group {"metadata":{"name":"Price Description"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
				<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"medium"} -->
					<p class="has-400-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e( 'Essential', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"textColor":"secondary","fontSize":"small"} -->
					<p class="has-neutral-700-color has-text-color has-300-font-size"><?php esc_html_e( 'Great for folks who are just getting started and only need the basic features and support.', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
				</div>
			<!-- /wp:group -->
			<!-- wp:buttons -->
				<div class="wp-block-buttons">
				<!-- wp:button {"width":100,"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
					<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><?php esc_html_e( 'Get Started', 'kwv' ); ?></a></div>
				<!-- /wp:button -->
				</div>
			<!-- /wp:buttons -->
			<!-- wp:group {"metadata":{"name":"Features"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"fontSize":"small","layout":{"type":"constrained"}} -->
				<div class="wp-block-group has-300-font-size">
				<!-- wp:group {"metadata":{"name":"Feature"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
					<div class="wp-block-group">
					<!-- wp:paragraph -->
						<p><strong><?php esc_html_e( '✓', 'kwv' ); ?></strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph -->
						<p><?php esc_html_e( 'Publishing Suite', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					</div>
				<!-- /wp:group -->
				<!-- wp:separator {"className":"is-style-separator-thin","backgroundColor":"border-light"} -->
					<hr class="wp-block-separator has-text-color has-neutral-300-color has-alpha-channel-opacity has-neutral-300-background-color has-background is-style-separator-thin"/>
				<!-- /wp:separator -->
				<!-- wp:group {"metadata":{"name":"Feature"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
					<div class="wp-block-group">
					<!-- wp:paragraph -->
						<p><strong><?php esc_html_e( '✓', 'kwv' ); ?></strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph -->
						<p><?php esc_html_e( 'Advanced Tools', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					</div>
				<!-- /wp:group -->
				<!-- wp:separator {"className":"is-style-separator-thin","backgroundColor":"border-light"} -->
					<hr class="wp-block-separator has-text-color has-neutral-300-color has-alpha-channel-opacity has-neutral-300-background-color has-background is-style-separator-thin"/>
				<!-- /wp:separator -->
				<!-- wp:group {"metadata":{"name":"Feature"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
					<div class="wp-block-group">
					<!-- wp:paragraph -->
						<p><strong><?php esc_html_e( '✓', 'kwv' ); ?></strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph -->
						<p><?php esc_html_e( 'Priority Support', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					</div>
				<!-- /wp:group -->
				<!-- wp:separator {"className":"is-style-separator-thin","backgroundColor":"border-light"} -->
					<hr class="wp-block-separator has-text-color has-neutral-300-color has-alpha-channel-opacity has-neutral-300-background-color has-background is-style-separator-thin"/>
				<!-- /wp:separator -->
				<!-- wp:group {"metadata":{"name":"Feature"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
					<div class="wp-block-group">
					<!-- wp:paragraph -->
						<p><strong><?php esc_html_e( '✓', 'kwv' ); ?></strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph -->
						<p><?php esc_html_e( 'Blazing-Fast Performance', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					</div>
				<!-- /wp:group -->
				</div>
			<!-- /wp:group -->
			</div>
		<!-- /wp:column -->
		</div>
	<!-- /wp:columns -->
	<!-- wp:paragraph {"align":"center","textColor":"secondary","fontSize":"small"} -->
		<p class="has-text-align-center has-neutral-700-color has-text-color has-300-font-size"><?php esc_html_e( 'Not convinced? We offer a', 'kwv' ); ?> <strong><mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-brand-500-color"><?php esc_html_e( '100% money back guarantee', 'kwv' ); ?></mark></strong> <?php esc_html_e( 'for all purchases. Try our product with confidence, and if you don\'t like it, we\'ll make it right.', 'kwv' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:columns {"metadata":{"name":"Testimonial Columns"},"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
		<div class="wp-block-columns alignwide">
		<!-- wp:column {"metadata":{"name":"Testimonial"},"className":"is-style-default","style":{"border":{"width":"1px","color":"#E3E3F0","radius":"5px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base"} -->
			<div class="wp-block-column is-style-default has-border-color has-base-background-color has-background" style="border-color:#E3E3F0;border-width:1px;border-radius:5px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
				<div class="wp-block-group">
				<!-- wp:image {"id":57,"width":"60px","height":"60px","sizeSlug":"full","linkDestination":"none","className":"is-style-rounded-full"} -->
					<figure class="wp-block-image size-full is-resized is-style-rounded-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/patterns/images/avatar-2.webp" alt="" class="wp-image-57" style="width:60px;height:60px"/></figure>
				<!-- /wp:image -->
				<!-- wp:group {"metadata":{"name":"Text"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
					<div class="wp-block-group">
					<!-- wp:paragraph {"fontSize":"small"} -->
						<p class="has-300-font-size"><?php esc_html_e( 'Now that I\'ve seen how powerful the Site Editor can be, I can\'t live without it. Ollie made it super easy to get started with the "new" WordPress.', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"small"} -->
						<p class="has-300-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e( 'Marion Alpine · SparkCode', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					</div>
				<!-- /wp:group -->
				</div>
			<!-- /wp:group -->
			</div>
		<!-- /wp:column -->
		<!-- wp:column {"metadata":{"name":"Testimonial"},"className":"is-style-default","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"border":{"width":"1px","color":"#E3E3F0","radius":"5px"}},"backgroundColor":"base"} -->
			<div class="wp-block-column is-style-default has-border-color has-base-background-color has-background" style="border-color:#E3E3F0;border-width:1px;border-radius:5px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
				<div class="wp-block-group">
				<!-- wp:image {"id":62,"width":"60px","height":"60px","sizeSlug":"full","linkDestination":"none","className":"is-style-rounded-full"} -->
					<figure class="wp-block-image size-full is-resized is-style-rounded-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/patterns/images/avatar-7.webp" alt="" class="wp-image-62" style="width:60px;height:60px"/></figure>
				<!-- /wp:image -->
				<!-- wp:group {"metadata":{"name":"Text"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
					<div class="wp-block-group">
					<!-- wp:paragraph {"fontSize":"small"} -->
						<p class="has-300-font-size"><?php esc_html_e( 'I finally feel like I can ditch my costly page builder plugin. Now, I can quickly build out page sections or full page designs with Ollie patterns.', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"small"} -->
						<p class="has-300-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e( 'Giannis Holiday · Creatif', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					</div>
				<!-- /wp:group -->
				</div>
			<!-- /wp:group -->
			</div>
		<!-- /wp:column -->
		</div>
	<!-- /wp:columns -->
	</div>
<!-- /wp:group -->
