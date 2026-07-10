<?php
/**
 * Title: Term Banner Hero
 * Slug: kwv/term-banner-hero
 * Description: Shop / category / brand archive hero — a cream title panel beside a banner image. The title is a dynamic query title (the shop, category, or brand name); the banner is the current term's uploaded banner image, falling back to the shared shop hero image when the term has none.
 * Categories: kwv/woocommerce, kwv/hero
 * Keywords: hero, shop, archive, products, banner, category, brand
 * Template Types: archive-product, taxonomy-product_cat, taxonomy-product_brand
 * Inserter: false
 * Viewport Width: 1500
 */

// Prefer the current term's banner; otherwise fall back to the shared shop hero image.
$kwv_banner = function_exists( 'Kwv\get_queried_term_banner' ) ? \Kwv\get_queried_term_banner( 'large' ) : array(
	'id'  => 0,
	'url' => '',
);

$kwv_banner_id  = (int) $kwv_banner['id'];
$kwv_banner_url = (string) $kwv_banner['url'];

if ( ! $kwv_banner_url ) {
	$kwv_fallback = get_posts(
		array(
			'post_type'        => 'attachment',
			'name'             => 'shop-hero-image',
			'posts_per_page'   => 1,
			'post_status'      => 'inherit',
			'suppress_filters' => false,
		)
	);

	if ( $kwv_fallback ) {
		$kwv_banner_id  = (int) $kwv_fallback[0]->ID;
		$kwv_banner_url = (string) wp_get_attachment_image_url( $kwv_banner_id, 'large' );
	}
}
?>
<!-- wp:group {"metadata":{"name":"Shop Hero"},"align":"full","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"blockGap":"0"},"dimensions":{"minHeight":"0px"}},"backgroundColor":"brand-100","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-brand-100-background-color has-background" style="min-height:0px;margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:columns {"verticalAlignment":"stretch","align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|0","bottom":"var:preset|spacing|0"},"blockGap":{"left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-stretch" style="margin-top:var(--wp--preset--spacing--0);margin-bottom:var(--wp--preset--spacing--0)"><!-- wp:column {"verticalAlignment":"center","width":"33.33%","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|60","right":"var:preset|spacing|40"}}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--60);flex-basis:33.33%"><!-- wp:query-title {"type":"archive","showPrefix":false,"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"textColor":"contrast","fontSize":"600"} /--></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"stretch","width":"","style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<div class="wp-block-column is-vertically-aligned-stretch" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<?php if ( $kwv_banner_url ) : ?>
<!-- wp:image {"id":<?php echo (int) $kwv_banner_id; ?>,"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( $kwv_banner_url ); ?>" alt="" class="wp-image-<?php echo (int) $kwv_banner_id; ?>"/></figure>
<!-- /wp:image -->
<?php endif; ?>
</div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
