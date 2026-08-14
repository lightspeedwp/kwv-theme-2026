<?php
/**
 * Title: History — Timeline
 * Slug: kwv/history-timeline
 * Description: Heritage timeline — a Carousel Block of round year "milestone" images. Hovering (or keyboard-focusing) a milestone slides out a short history note. Clicking a milestone image opens it in the WordPress core lightbox. Uses the kwv/timeline-milestone group block style.
 * Categories: kwv/features, kwv/history, kwv/pages
 * Keywords: history, timeline, carousel, milestone, years, heritage, slider, lightbox
 * Viewport Width: 1500
 * Inserter: true
 */

/*
 * One representative milestone slide, as static block markup.
 *
 * The live timeline (~40 cards) is a synced pattern in the dev database and
 * will replace this file wholesale in the dev→theme pattern reset. Until then:
 * do NOT rebuild this as a PHP loop over a milestone table. Generated block
 * markup does not round-trip through the editor, so the pattern could not be
 * edited or re-synced. Add further milestones as further wp:cb/slide-v2 blocks.
 */
?>
<!-- wp:group {"metadata":{"name":"History Timeline"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|100","bottom":"var:preset|spacing|90"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-light-page-section" style="padding-top:var(--wp--preset--spacing--100);padding-bottom:var(--wp--preset--spacing--90)"><!-- wp:cb/carousel-v2 {"slidesPerView":2,"spaceBetween":20,"speed":500,"navigation":true,"pagination":false,"loop":true,"breakpoints":[{"width":1024,"slidesPerView":6,"slidesPerGroup":1}],"resizeObserver":true,"observer":true,"align":"full"} -->
<div class="wp-block-cb-carousel-v2 alignfull cb-carousel-block" data-cb-slides-per-view="2" data-cb-slides-per-group="1" data-cb-space-between="20" data-cb-speed="500" data-cb-navigation="true" data-cb-loop="true" data-cb-resize-observer="true" data-cb-observer="true" data-cb-breakpoints="{&quot;1024&quot;:{&quot;slidesPerView&quot;:6,&quot;slidesPerGroup&quot;:1}}">
	<div class="swiper">
		<div class="cb-wrapper swiper-wrapper">
			<!-- wp:cb/slide-v2 -->
			<div class="wp-block-cb-slide-v2 cb-slide swiper-slide"><!-- wp:group {"metadata":{"name":"Milestone 1918"},"className":"is-style-timeline-milestone","layout":{"type":"constrained"}} -->
			<div class="wp-block-group is-style-timeline-milestone"><!-- wp:group {"metadata":{"name":"Media"},"className":"timeline-milestone__media","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group timeline-milestone__media"><!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="/wp-content/uploads/2026/06/KWV-20-YO.jpg" alt="<?php echo esc_attr__( 'KWV founding, 1918', 'kwv' ); ?>"/></figure>
			<!-- /wp:image --></div>
			<!-- /wp:group -->

			<!-- wp:heading {"level":3,"className":"timeline-milestone__year","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading"}},"textColor":"contrast","fontSize":"400","fontFamily":"heading"} -->
			<h3 class="wp-block-heading timeline-milestone__year has-contrast-color has-text-color has-heading-font-family has-400-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading)">1918</h3>
			<!-- /wp:heading -->

			<!-- wp:group {"metadata":{"name":"Info"},"className":"timeline-milestone__info","layout":{"type":"constrained"}} -->
			<div class="wp-block-group timeline-milestone__info"><!-- wp:paragraph {"style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"textColor":"neutral-700","fontSize":"200"} -->
			<p class="has-neutral-700-color has-text-color has-200-font-size" style="line-height:var(--wp--custom--line-height--body)"><?php echo esc_html__( 'Professor Abraham Izak Perold, the legendary botanist, ampelographer and wine scientist who developed the Pinotage grape, joins KWV and becomes responsible for the experimentation of new cultivars and to improve quality control processes.', 'kwv' ); ?></p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:group --></div>
			<!-- /wp:group --></div>
			<!-- /wp:cb/slide-v2 -->
		</div>
	</div>
	<div class="cb-pagination swiper-pagination"></div>
	<div class="cb-button-prev swiper-button-prev"></div>
	<div class="cb-button-next swiper-button-next"></div>
</div>
<!-- /wp:cb/carousel-v2 --></div>
<!-- /wp:group -->
