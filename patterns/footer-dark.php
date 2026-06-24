<?php
/**
 * Title: Footer
 * Slug: kwv/footer-dark
 * Description: 
 * Categories: footer
 * Keywords: 
 * Viewport Width: 1500
 * Block Types: core/template-part/footer
 * Post Types: wp_template
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Footer"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","right":"var:preset|spacing|30","left":"var:preset|spacing|30"},"margin":{"top":"0px"},"blockGap":"var:preset|spacing|50"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"backgroundColor":"main","textColor":"base","layout":{"inherit":true,"type":"constrained"}} -->
	<div class="wp-block-group alignfull has-base-color has-contrast-background-color has-text-color has-background has-link-color" style="margin-top:0px;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--30)">
	<!-- wp:columns {"metadata":{"name":"Footer Columns"},"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-columns alignwide">
		<!-- wp:column -->
			<div class="wp-block-column">
			<!-- wp:site-title {"level":0,"isLink":false,"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} /-->
			<!-- wp:paragraph {"textColor":"main-accent","fontSize":"small"} -->
				<p class="has-neutral-300-color has-text-color has-300-font-size"><?php esc_html_e( 'Easily create beautiful, fully-customizable websites with the new WordPress Site Editor and the Ollie block theme. No coding skills required. Download for free today!', 'kwv' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:social-links {"iconColor":"main","iconColorValue":"#150E29","iconBackgroundColor":"base","iconBackgroundColorValue":"#fff","className":"is-style-default","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|20"}}},"layout":{"type":"flex","justifyContent":"left"}} -->
				<ul class="wp-block-social-links has-icon-color has-icon-background-color is-style-default">
				<!-- wp:social-link {"url":"#","service":"twitter"} /-->
				<!-- wp:social-link {"url":"#","service":"instagram"} /-->
				<!-- wp:social-link {"url":"#","service":"linkedin"} /-->
				<!-- wp:social-link {"url":"#","service":"facebook"} /-->
				</ul>
			<!-- /wp:social-links -->
			</div>
		<!-- /wp:column -->
		<!-- wp:column -->
			<div class="wp-block-column">
			<!-- wp:columns {"metadata":{"name":"Nav Columns"}} -->
				<div class="wp-block-columns">
				<!-- wp:column {"metadata":{"name":"Nav Column"}} -->
					<div class="wp-block-column">
					<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} -->
						<p style="font-style:normal;font-weight:600"><?php esc_html_e( 'Company', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"textColor":"main-accent","fontSize":"small","layout":{"type":"constrained"}} -->
						<div class="wp-block-group has-neutral-300-color has-text-color has-300-font-size">
						<!-- wp:paragraph -->
							<p><?php esc_html_e( 'About', 'kwv' ); ?></p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph -->
							<p><?php esc_html_e( 'Careers', 'kwv' ); ?></p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph -->
							<p><?php esc_html_e( 'Brand Assets', 'kwv' ); ?></p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph -->
							<p><?php esc_html_e( 'Contact', 'kwv' ); ?></p>
						<!-- /wp:paragraph -->
						</div>
					<!-- /wp:group -->
					</div>
				<!-- /wp:column -->
				<!-- wp:column {"metadata":{"name":"Nav Column"}} -->
					<div class="wp-block-column">
					<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} -->
						<p style="font-style:normal;font-weight:600"><?php esc_html_e( 'Resources', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"textColor":"main-accent","fontSize":"small","layout":{"type":"constrained"}} -->
						<div class="wp-block-group has-neutral-300-color has-text-color has-300-font-size">
						<!-- wp:paragraph -->
							<p><?php esc_html_e( 'Blog', 'kwv' ); ?></p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph -->
							<p><?php esc_html_e( 'Contact', 'kwv' ); ?></p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph -->
							<p><?php esc_html_e( 'Support Docs', 'kwv' ); ?></p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph -->
							<p><?php esc_html_e( 'Get Help', 'kwv' ); ?></p>
						<!-- /wp:paragraph -->
						</div>
					<!-- /wp:group -->
					</div>
				<!-- /wp:column -->
				<!-- wp:column {"metadata":{"name":"Nav Column"}} -->
					<div class="wp-block-column">
					<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} -->
						<p style="font-style:normal;font-weight:600"><?php esc_html_e( 'Product', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"textColor":"main-accent","fontSize":"small","layout":{"type":"constrained"}} -->
						<div class="wp-block-group has-neutral-300-color has-text-color has-300-font-size">
						<!-- wp:paragraph -->
							<p><?php esc_html_e( 'Features', 'kwv' ); ?></p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph -->
							<p><?php esc_html_e( 'Pricing', 'kwv' ); ?></p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph -->
							<p><?php esc_html_e( 'Use Cases', 'kwv' ); ?></p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph -->
							<p><?php esc_html_e( 'Demo', 'kwv' ); ?></p>
						<!-- /wp:paragraph -->
						</div>
					<!-- /wp:group -->
					</div>
				<!-- /wp:column -->
				</div>
			<!-- /wp:columns -->
			</div>
		<!-- /wp:column -->
		</div>
	<!-- /wp:columns -->
	<!-- wp:separator {"align":"wide","className":"is-style-separator-thin","backgroundColor":"border-dark"} -->
		<hr class="wp-block-separator alignwide has-text-color has-border-dark-color has-alpha-channel-opacity has-border-dark-background-color has-background is-style-separator-thin"/>
	<!-- /wp:separator -->
	<!-- wp:group {"metadata":{"name":"Footer Subnav"},"align":"wide"} -->
		<div class="wp-block-group alignwide">
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"},"elements":{"link":{"color":{"text":"var:preset|color|neutral-300"}}}},"textColor":"main-accent","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
			<div class="wp-block-group has-neutral-300-color has-text-color has-link-color">
			<!-- wp:paragraph {"fontSize":"small"} -->
				<p class="has-300-font-size"><?php esc_html_e( '© 2026 · Powered by WordPress and ', 'kwv' ); ?><a href="https://olliewp.com"><?php esc_html_e( 'Ollie', 'kwv' ); ?></a></p>
			<!-- /wp:paragraph -->
			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"fontSize":"small","layout":{"type":"flex","flexWrap":"nowrap"}} -->
				<div class="wp-block-group has-300-font-size">
				<!-- wp:paragraph -->
					<p><?php esc_html_e( 'Download', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph -->
					<p><?php esc_html_e( 'Visit Ollie', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph -->
					<p><?php esc_html_e( 'Visit YouTube', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
				</div>
			<!-- /wp:group -->
			</div>
		<!-- /wp:group -->
		</div>
	<!-- /wp:group -->
	</div>
<!-- /wp:group -->
