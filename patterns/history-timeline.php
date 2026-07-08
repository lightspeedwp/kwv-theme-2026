<?php
/**
 * Title: History — Timeline
 * Slug: kwv/history-timeline
 * Description: Heritage timeline — a Carousel Block of round year "milestone" images. Hovering (or keyboard-focusing) a milestone slides out a short history note. Uses the kwv/timeline-milestone group block style.
 * Categories: kwv/features, kwv/history, kwv/pages
 * Keywords: history, timeline, carousel, milestone, years, heritage, slider
 * Viewport Width: 1500
 * Inserter: true
 */

/*
 * Milestones render as identical slides, so build them from a small local
 * table rather than repeating ~20 lines of block markup six times. The pattern
 * stays self-contained (no inc/ helper) and every value is escaped on output.
 * Swap the images/notes for the real archive assets and copy when available.
 */
$kwv_milestones = array(
	array(
		'year'  => '1918',
		'image' => 'KWV-20-YO.jpg',
		'alt'   => __( 'KWV founding, 1918', 'kwv' ),
		'note'  => __( 'Professor Abraham Izak Perold, the legendary botanist, ampelographer and wine scientist who developed the Pinotage grape, joins KWV and becomes responsible for the experimentation of new cultivars and to improve quality control processes.', 'kwv' ),
	),
	array(
		'year'  => '1924',
		'image' => 'KWV-Brandy-10.jpg',
		'alt'   => __( 'KWV milestone, 1924', 'kwv' ),
		'note'  => __( 'A defining year in the KWV story. Replace this note with the historical detail for 1924.', 'kwv' ),
	),
	array(
		'year'  => '1926',
		'image' => 'Roodeberg-Black_2021-002-1.png',
		'alt'   => __( 'KWV milestone, 1926', 'kwv' ),
		'note'  => __( 'A defining year in the KWV story. Replace this note with the historical detail for 1926.', 'kwv' ),
	),
	array(
		'year'  => '1928',
		'image' => 'The-Mentors_Pinotage-2.jpg',
		'alt'   => __( 'KWV milestone, 1928', 'kwv' ),
		'note'  => __( 'A defining year in the KWV story. Replace this note with the historical detail for 1928.', 'kwv' ),
	),
	array(
		'year'  => '1930',
		'image' => 'Laborie_MCC_Brut.jpg',
		'alt'   => __( 'KWV milestone, 1930', 'kwv' ),
		'note'  => __( 'A defining year in the KWV story. Replace this note with the historical detail for 1930.', 'kwv' ),
	),
	array(
		'year'  => '1935',
		'image' => 'Cathedral-Cellar_Cabernet-Sauvignon.jpg',
		'alt'   => __( 'KWV milestone, 1935', 'kwv' ),
		'note'  => __( 'A defining year in the KWV story. Replace this note with the historical detail for 1935.', 'kwv' ),
	),
);

$kwv_uploads = 'http://localhost:8901/wp-content/uploads/2026/06/';
?>
<!-- wp:group {"metadata":{"name":"History Timeline"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|100","bottom":"var:preset|spacing|90"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-light-page-section" style="padding-top:var(--wp--preset--spacing--100);padding-bottom:var(--wp--preset--spacing--90)"><!-- wp:cb/carousel-v2 {"slidesPerView":2,"spaceBetween":20,"speed":500,"navigation":true,"pagination":false,"loop":true,"breakpoints":[{"width":1024,"slidesPerView":6,"slidesPerGroup":1}],"resizeObserver":true,"observer":true,"align":"full"} -->
<div class="wp-block-cb-carousel-v2 alignfull cb-carousel-block" data-cb-slides-per-view="2" data-cb-slides-per-group="1" data-cb-space-between="20" data-cb-speed="500" data-cb-navigation="true" data-cb-loop="true" data-cb-resize-observer="true" data-cb-observer="true" data-cb-breakpoints="{&quot;1024&quot;:{&quot;slidesPerView&quot;:6,&quot;slidesPerGroup&quot;:1}}">
	<div class="swiper">
		<div class="cb-wrapper swiper-wrapper">
		<?php foreach ( $kwv_milestones as $kwv_m ) : ?>
			<!-- wp:cb/slide-v2 -->
			<div class="wp-block-cb-slide-v2 cb-slide swiper-slide"><!-- wp:group {"metadata":{"name":"Milestone <?php echo esc_html( $kwv_m['year'] ); ?>"},"className":"is-style-timeline-milestone","layout":{"type":"constrained"}} -->
			<div class="wp-block-group is-style-timeline-milestone"><!-- wp:group {"metadata":{"name":"Media"},"className":"timeline-milestone__media","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group timeline-milestone__media"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="<?php echo esc_url( $kwv_uploads . $kwv_m['image'] ); ?>" alt="<?php echo esc_attr( $kwv_m['alt'] ); ?>"/></figure>
			<!-- /wp:image --></div>
			<!-- /wp:group -->

			<!-- wp:heading {"level":3,"className":"timeline-milestone__year","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading"}},"textColor":"contrast","fontSize":"400","fontFamily":"heading"} -->
			<h3 class="wp-block-heading timeline-milestone__year has-contrast-color has-text-color has-heading-font-family has-400-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading)"><?php echo esc_html( $kwv_m['year'] ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:group {"metadata":{"name":"Info"},"className":"timeline-milestone__info","layout":{"type":"constrained"}} -->
			<div class="wp-block-group timeline-milestone__info"><!-- wp:paragraph {"style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"textColor":"neutral-700","fontSize":"200"} -->
			<p class="has-neutral-700-color has-text-color has-200-font-size" style="line-height:var(--wp--custom--line-height--body)"><?php echo esc_html( $kwv_m['note'] ); ?></p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:group --></div>
			<!-- /wp:group --></div>
			<!-- /wp:cb/slide-v2 -->
		<?php endforeach; ?>
		</div>
	</div>
	<div class="cb-pagination swiper-pagination"></div>
	<div class="cb-button-prev swiper-button-prev"></div>
	<div class="cb-button-next swiper-button-next"></div>
</div>
<!-- /wp:cb/carousel-v2 --></div>
<!-- /wp:group -->
