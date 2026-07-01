<?php
/**
 * Title: Single Post
 * Slug: kwv/template-single-post
 * Template Types: single
 * Description: The KWV single blog post layout — dark header, a "back to news" link, post title and a gold-flanked meta row (author, role, date and share links), a wide featured image, the post body and previous/next post navigation.
 * Categories: kwv/posts
 * Keywords: post, single, blog, news, article
 * Viewport Width: 1500
 * Inserter: true
 */

$kwv_news_url = get_option( 'page_for_posts' )
	? get_permalink( (int) get_option( 'page_for_posts' ) )
	: home_url( '/news/' );
?>
<!-- wp:template-part {"slug":"header-dark","tagName":"header","className":"site-header"} /-->

<!-- wp:group {"tagName":"main","metadata":{"name":"Article"},"align":"full","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained","contentSize":"1100px","wideSize":"1520px"}} -->
<main class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)">

	<!-- wp:group {"metadata":{"name":"Article Header"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"className":"kwv-back-link","style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase"},"elements":{"link":{"color":{"text":"var:preset|color|brand-500"}}}},"textColor":"brand-500","fontSize":"200"} -->
		<p class="kwv-back-link has-brand-500-color has-text-color has-link-color has-200-font-size" style="font-style:normal;font-weight:700;text-transform:uppercase"><a href="<?php echo esc_url( $kwv_news_url ); ?>">&larr; <?php esc_html_e( 'Back to News', 'kwv' ); ?></a></p>
		<!-- /wp:paragraph -->

		<!-- wp:post-title {"level":1,"fontFamily":"heading","fontSize":"600","textColor":"contrast","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase","letterSpacing":"1px"}}} /-->

		<!-- wp:separator {"className":"is-style-separator-thin","style":{"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10"}}},"backgroundColor":"neutral-300"} -->
		<hr class="wp-block-separator has-text-color has-neutral-300-color has-alpha-channel-opacity has-neutral-300-background-color has-background is-style-separator-thin" style="margin-top:var(--wp--preset--spacing--10);margin-bottom:var(--wp--preset--spacing--10)"/>
		<!-- /wp:separator -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Post Meta"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group">
		<!-- wp:group {"metadata":{"name":"Byline"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
		<div class="wp-block-group">
			<!-- wp:avatar {"size":60,"style":{"border":{"radius":"100px"}}} /-->

			<!-- wp:group {"metadata":{"name":"Author"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group">
				<!-- wp:post-author-name {"isLink":false,"fontFamily":"heading","fontSize":"200","textColor":"contrast","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|snug","textTransform":"uppercase"}}} /-->

				<!-- wp:paragraph {"metadata":{"name":"Author Role","bindings":{"content":{"source":"kwv/author-role"}}},"textColor":"neutral-700","fontSize":"200","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
				<p class="has-neutral-700-color has-text-color has-200-font-size" style="margin-top:0;margin-bottom:0"><?php esc_html_e( 'Role', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

			<!-- wp:post-date {"format":"F j, Y","style":{"typography":{"fontStyle":"normal","fontWeight":"400","textTransform":"uppercase"},"border":{"left":{"color":"var:preset|color|neutral-300","width":"1px"}},"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10","left":"var:preset|spacing|30"}}},"textColor":"neutral-700","fontSize":"200"} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Share"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase","letterSpacing":"0.5px"},"spacing":{"margin":{"top":"0","bottom":"0"}}},"textColor":"neutral-700","fontSize":"100"} -->
			<p class="has-neutral-700-color has-text-color has-100-font-size" style="font-style:normal;font-weight:700;letter-spacing:0.5px;margin-top:0;margin-bottom:0"><?php esc_html_e( 'Share:', 'kwv' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:social-links {"iconColor":"contrast","iconColorValue":"#000000","size":"has-small-icon-size","className":"is-style-logos-only","layout":{"type":"flex"}} -->
			<ul class="wp-block-social-links has-small-icon-size has-icon-color is-style-logos-only">
				<!-- wp:social-link {"url":"#","service":"facebook"} /-->
				<!-- wp:social-link {"url":"#","service":"x"} /-->
				<!-- wp:social-link {"url":"#","service":"linkedin"} /-->
			</ul>
			<!-- /wp:social-links -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:post-featured-image {"aspectRatio":"5/2","align":"wide","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} /-->

	<!-- wp:post-content {"layout":{"type":"constrained"}} /-->

	<!-- wp:separator {"className":"is-style-separator-thin","style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"backgroundColor":"neutral-300"} -->
	<hr class="wp-block-separator has-text-color has-neutral-300-color has-alpha-channel-opacity has-neutral-300-background-color has-background is-style-separator-thin" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--30)"/>
	<!-- /wp:separator -->

	<!-- wp:group {"metadata":{"name":"Post Navigation"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
	<div class="wp-block-group">
		<!-- wp:group {"metadata":{"name":"Previous"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained","contentSize":"400px"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase","letterSpacing":"0.5px"},"spacing":{"margin":{"top":"0","bottom":"0"}}},"textColor":"neutral-700","fontSize":"100"} -->
			<p class="has-neutral-700-color has-text-color has-100-font-size" style="font-style:normal;font-weight:700;letter-spacing:0.5px;margin-top:0;margin-bottom:0"><?php esc_html_e( '‹ Previous Post', 'kwv' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:post-navigation-link {"textAlign":"left","type":"previous","showTitle":true,"arrow":"none","className":"kwv-post-nav","fontSize":"200"} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Next"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained","contentSize":"400px"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"align":"right","style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase","letterSpacing":"0.5px"},"spacing":{"margin":{"top":"0","bottom":"0"}}},"textColor":"neutral-700","fontSize":"100"} -->
			<p class="has-text-align-right has-neutral-700-color has-text-color has-100-font-size" style="font-style:normal;font-weight:700;letter-spacing:0.5px;margin-top:0;margin-bottom:0"><?php esc_html_e( 'Next Post ›', 'kwv' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:post-navigation-link {"textAlign":"right","type":"next","showTitle":true,"arrow":"none","className":"kwv-post-nav","fontSize":"200"} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer","className":"site-footer"} /-->
