<?php
/**
 * Title: History — KWV Archives
 * Slug: kwv/history-archives
 * Description: "KWV Archives" section — a section header followed by a four-up grid of tall, rounded archive images with captions, and a centred Read More button.
 * Categories: kwv/posts, kwv/history, kwv/pages
 * Keywords: history, archives, news, grid, gallery, cards, read more
 * Viewport Width: 1500
 * Inserter: true
 */

/* Four archive cards, built from a local table (see history-timeline.php). */
$kwv_archives = array(
	array(
		'image'   => 'KWV-Classic-Shiraz-1000x1000px.jpeg',
		'caption' => __( 'Cathedral Cellar Shiraz 2024', 'kwv' ),
	),
	array(
		'image'   => 'The-Mentors_Pinotage-2.jpg',
		'caption' => __( 'The Mentors Cabernet Sauvignon 2022', 'kwv' ),
	),
	array(
		'image'   => 'Laborie_MCC_Brut.jpg',
		'caption' => __( 'Laborie Brut Rosé', 'kwv' ),
	),
	array(
		'image'   => 'wineclub-edition-13-box.jpg',
		'caption' => __( 'Winemaker’s Selection Edition 12', 'kwv' ),
	),
);

$kwv_uploads = '/wp-content/uploads/2026/06/';
?>
<!-- wp:group {"metadata":{"name":"KWV Archives"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|90","bottom":"var:preset|spacing|90"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-light-page-section" style="padding-top:var(--wp--preset--spacing--90);padding-bottom:var(--wp--preset--spacing--90)"><!-- wp:group {"align":"wide","className":"is-style-section-header","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide is-style-section-header"><!-- wp:heading {"style":{"typography":{"fontWeight":"var:custom|font-weight|extra-bold","textTransform":"uppercase"}},"textColor":"contrast","fontFamily":"heading"} -->
<h2 class="wp-block-heading has-contrast-color has-text-color has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--extra-bold);text-transform:uppercase">KWV Archives</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:columns {"metadata":{"name":"Archive Grid"},"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns alignwide">
<?php foreach ( $kwv_archives as $kwv_a ) : ?>
	<!-- wp:column -->
	<div class="wp-block-column"><!-- wp:image {"aspectRatio":"35/43","scale":"cover","sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"12px"}}} -->
	<figure class="wp-block-image size-large has-custom-border"><img src="<?php echo esc_url( $kwv_uploads . $kwv_a['image'] ); ?>" alt="<?php echo esc_attr( $kwv_a['caption'] ); ?>" style="border-radius:12px;aspect-ratio:35/43;object-fit:cover"/></figure>
	<!-- /wp:image -->

	<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}},"typography":{"lineHeight":"var:custom|line-height|body"}},"textColor":"neutral-700","fontSize":"200"} -->
	<p class="has-neutral-700-color has-text-color has-200-font-size" style="margin-top:var(--wp--preset--spacing--30);line-height:var(--wp--custom--line-height--body)"><?php echo esc_html( $kwv_a['caption'] ); ?></p>
	<!-- /wp:paragraph --></div>
	<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"textAlign":"center","style":{"spacing":{"padding":{"left":"var:preset|spacing|60","right":"var:preset|spacing|60"}}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-text-align-center wp-element-button" style="padding-left:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60)">Read More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
