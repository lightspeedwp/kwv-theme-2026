<?php
/**
 * Title: Footer Light
 * Slug: kwv/footer-light
 * Description: 
 * Categories: footer
 * Keywords: 
 * Viewport Width: 1500
 * Block Types: core/template-part/footer
 * Post Types: wp_template
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Footer"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","right":"var:preset|spacing|30","left":"var:preset|spacing|30"},"margin":{"top":"0px"},"blockGap":"var:preset|spacing|40"},"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}},"border":{"top":{"color":"var:preset|color|neutral-300","width":"1px"},"right":[],"bottom":[],"left":[]}},"backgroundColor":"base","textColor":"main","layout":{"inherit":true,"type":"constrained"}} -->
	<div class="wp-block-group alignfull has-contrast-color has-base-background-color has-text-color has-background has-link-color" style="border-top-color:var(--wp--preset--color--neutral-300);border-top-width:1px;margin-top:0px;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--30)">
	<!-- wp:columns {"metadata":{"name":"Footer Columns"},"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-columns alignwide">
		<!-- wp:column -->
			<div class="wp-block-column">
			<!-- wp:site-title {"level":0,"isLink":false,"style":{"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}}},"textColor":"main"} /-->
			<!-- wp:paragraph {"fontSize":"small"} -->
				<p class="has-300-font-size"><?php esc_html_e( 'Easily create beautiful, fully-customizable websites with the new WordPress Site Editor and the Ollie block theme. No coding skills required. Download for free today!', 'kwv' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:social-links {"iconColor":"base","iconColorValue":"#fff","iconBackgroundColor":"main","iconBackgroundColorValue":"#14111f","className":"is-style-default","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|20"}}},"layout":{"type":"flex","justifyContent":"left"}} -->
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
			<!-- wp:columns -->
				<div class="wp-block-columns">
				<!-- wp:column {"metadata":{"name":"Nav Column"}} -->
					<div class="wp-block-column">
					<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} -->
						<p style="font-style:normal;font-weight:600"><?php esc_html_e( 'Company', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"fontSize":"small","layout":{"type":"constrained"}} -->
						<div class="wp-block-group has-300-font-size">
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
					<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"fontSize":"small","layout":{"type":"constrained"}} -->
						<div class="wp-block-group has-300-font-size">
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
					<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"fontSize":"small","layout":{"type":"constrained"}} -->
						<div class="wp-block-group has-300-font-size">
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
	<!-- wp:group {"metadata":{"name":"Footer Subnav"},"align":"wide","style":{"spacing":{"padding":{"top":"40px"}},"elements":{"link":{"color":{"text":"var:preset|color|neutral-700"}}}},"textColor":"secondary"} -->
		<div class="wp-block-group alignwide has-neutral-700-color has-text-color has-link-color" style="padding-top:40px">
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
			<div class="wp-block-group">
			<!-- wp:paragraph {"fontSize":"small"} -->
				<p class="has-300-font-size"><?php esc_html_e( '© 2026', 'kwv' ); ?><strong><?php esc_html_e( '·', 'kwv' ); ?></strong><?php esc_html_e( '&nbsp;Powered by WordPress and ', 'kwv' ); ?><a href="https://olliewp.com"><?php esc_html_e( 'Ollie', 'kwv' ); ?></a></p>
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
