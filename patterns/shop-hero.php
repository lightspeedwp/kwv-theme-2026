<?php
/**
 * Title: Shop Hero
 * Slug: kwv/shop-hero
 * Description: Full-width shop/category hero — a cream title panel beside the uploaded shop-hero-image lifestyle photo. The title is a dynamic query title, so it reads "All Products" on the shop and the category name on product category archives.
 * Categories: kwv/woocommerce, kwv/hero
 * Keywords: hero, shop, archive, products, banner, category
 * Block Types: core/template-part/header
 * Template Types: archive-product, taxonomy-product_cat
 * Inserter: true
 * Viewport Width: 1500
 */

$kwv_hero    = get_posts(
	array(
		'post_type'        => 'attachment',
		'name'             => 'shop-hero-image',
		'posts_per_page'   => 1,
		'post_status'      => 'inherit',
		'suppress_filters' => false,
	)
);
$kwv_hero_id  = $kwv_hero ? (int) $kwv_hero[0]->ID : 0;
$kwv_hero_url = $kwv_hero_id ? (string) wp_get_attachment_image_url( $kwv_hero_id, 'full' ) : '';
?>
<!-- wp:group {"metadata":{"name":"Shop Hero"},"align":"full","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"blockGap":"0"}},"backgroundColor":"base","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
	<!-- wp:columns {"verticalAlignment":"stretch","isStackedOnMobile":true,"align":"full","style":{"spacing":{"blockGap":{"top":"0","left":"0"},"margin":{"top":"0","bottom":"0"}}}} -->
	<div class="wp-block-columns alignfull are-vertically-aligned-stretch" style="margin-top:0;margin-bottom:0">
		<!-- wp:column {"verticalAlignment":"center","width":"34%","backgroundColor":"brand-100","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|60","right":"var:preset|spacing|40"}}}} -->
		<div class="wp-block-column is-vertically-aligned-center has-brand-100-background-color has-background" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--60);flex-basis:34%">
			<!-- wp:query-title {"type":"archive","showPrefix":false,"level":1,"textColor":"contrast","fontSize":"600","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"stretch","width":"66%","style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
		<div class="wp-block-column is-vertically-aligned-stretch" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
			<?php if ( $kwv_hero_url ) : ?>
			<!-- wp:cover {"url":"<?php echo esc_url( $kwv_hero_url ); ?>","id":<?php echo (int) $kwv_hero_id; ?>,"dimRatio":0,"isUserOverlayColor":false,"focalPoint":{"x":0.5,"y":0.45},"minHeight":340,"minHeightUnit":"px","contentPosition":"center center","align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-cover alignfull" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;min-height:340px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><img class="wp-block-cover__image-background wp-image-<?php echo (int) $kwv_hero_id; ?>" alt="" src="<?php echo esc_url( $kwv_hero_url ); ?>" style="object-position:50% 45%" data-object-fit="cover" data-object-position="50% 45%"/><div class="wp-block-cover__inner-container"></div></div>
			<!-- /wp:cover -->
			<?php endif; ?>
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
