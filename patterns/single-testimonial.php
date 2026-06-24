<?php
/**
 * Title: Single Testimonial
 * Slug: kwv/single-testimonial
 * Description: 
 * Categories: kwv/testimonial, kwv/features, kwv/card
 * Keywords: testimonial, text, review, rating, avatar, card
 * Viewport Width: 600
 * Block Types: 
 * Post Types: 
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Testimonial"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","right":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|40"}},"backgroundColor":"main","layout":{"type":"constrained"}} -->
	<div class="wp-block-group has-contrast-background-color has-background" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.3"}},"textColor":"base","fontSize":"large"} -->
		<p class="has-base-color has-text-color has-500-font-size" style="line-height:1.3"><?php esc_html_e( 'Wow, I really can\'t believe how powerful the WordPress Site Editor is combined with Ollie. I don\'t even need a page builder anymore!', 'kwv' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:separator {"className":"is-style-separator-thin","backgroundColor":"border-dark"} -->
		<hr class="wp-block-separator has-text-color has-border-dark-color has-alpha-channel-opacity has-border-dark-background-color has-background is-style-separator-thin"/>
	<!-- /wp:separator -->
	<!-- wp:group {"metadata":{"name":"Cite"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
		<!-- wp:image {"id":58,"width":"60px","height":"60px","sizeSlug":"full","linkDestination":"none","className":"is-style-rounded-full"} -->
			<figure class="wp-block-image size-full is-resized is-style-rounded-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/patterns/images/avatar-3.webp" alt="" class="wp-image-58" style="width:60px;height:60px"/></figure>
		<!-- /wp:image -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"0px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
			<div class="wp-block-group">
			<!-- wp:paragraph {"textColor":"base"} -->
				<p class="has-base-color has-text-color"><strong><?php esc_html_e( 'Maryann Alpine', 'kwv' ); ?></strong></p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"textColor":"main-accent","fontSize":"small"} -->
				<p class="has-neutral-300-color has-text-color has-300-font-size"><?php esc_html_e( 'Product Designer', 'kwv' ); ?></p>
			<!-- /wp:paragraph -->
			</div>
		<!-- /wp:group -->
		</div>
	<!-- /wp:group -->
	</div>
<!-- /wp:group -->
