<?php
/**
 * Brand page (/brand/…): the product slider's assets and query size.
 *
 * The brand archive (templates/taxonomy-product_brand.html →
 * patterns/woo-brand-archive.php) shows a brand's products one at a time in a
 * slider instead of the shop grid. The products are a normal WooCommerce Product
 * Collection whose product template is the editable "Brand Product Slide"
 * template part; assets/js/brand-slider.js turns that list into the slider with
 * Swiper. Without JavaScript the products simply stay a stacked list.
 *
 * Swiper comes from the Carousel Block plugin, which bundles a full build
 * (Thumbs, Keyboard and A11y modules) beside the trimmed one its own block
 * loads. If that plugin is ever removed the script is not enqueued and the page
 * falls back to the stacked list.
 *
 * The brand content itself (logo, introduction, awards, links) is owned by the
 * KWV Enhancements plugin — see its modules/brands.php.
 *
 * @package kwv
 */

namespace Kwv\BrandPage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const TAXONOMY      = 'product_brand';
const STYLE_HANDLE  = 'kwv-brand-page';
const SCRIPT_HANDLE = 'kwv-brand-slider';
const SWIPER_HANDLE = 'kwv-brand-slider-swiper';
const SWIPER_DIR    = 'carousel-block/blocks/vendor/swiper/';

/**
 * The most products a brand page shows. A brand's whole range is one slider,
 * so the archive is not paginated; this is only a safety ceiling.
 */
const MAX_PRODUCTS = 100;

/**
 * Whether the current request is a brand page.
 *
 * @return bool
 */
function is_brand_page() {
	return is_tax( TAXONOMY );
}

/**
 * Show a brand's full range on one page.
 *
 * The slider's Product Collection inherits the main query, so its size comes
 * from WooCommerce's per-page filter. Runs after the theme's shop-grid value
 * (inc/woocommerce.php, priority 10).
 *
 * @param int $per_page Products per page.
 * @return int
 */
function products_per_page( $per_page ) {
	return is_brand_page() ? MAX_PRODUCTS : $per_page;
}
add_filter( 'loop_shop_per_page', __NAMESPACE__ . '\products_per_page', 20 );

/**
 * Flag that scripting is available, before first paint.
 *
 * The stylesheet hides every product but the first only under this class, so
 * the stacked list never flashes before the slider starts — and a visitor
 * without JavaScript still gets every product.
 */
function print_js_flag() {

	if ( ! is_brand_page() ) {
		return;
	}

	wp_print_inline_script_tag( "document.documentElement.classList.add('kwv-brand-slider-js');" );
}
add_action( 'wp_head', __NAMESPACE__ . '\print_js_flag', 1 );

/**
 * Enqueue the brand page stylesheet and, when Swiper is available, the slider.
 */
function enqueue_assets() {

	if ( ! is_brand_page() ) {
		return;
	}

	$swiper_js  = WP_PLUGIN_DIR . '/' . SWIPER_DIR . 'swiper-bundle.min.js';
	$swiper_css = WP_PLUGIN_DIR . '/' . SWIPER_DIR . 'swiper-bundle.min.css';
	$has_swiper = is_readable( $swiper_js ) && is_readable( $swiper_css );

	if ( $has_swiper ) {
		wp_enqueue_style(
			SWIPER_HANDLE,
			plugins_url( SWIPER_DIR . 'swiper-bundle.min.css' ),
			array(),
			(string) filemtime( $swiper_css )
		);
	}

	// The page stylesheet overrides Swiper's defaults, so it loads after it.
	wp_enqueue_style(
		STYLE_HANDLE,
		get_theme_file_uri( 'assets/styles/woocommerce-brand-page.css' ),
		$has_swiper ? array( 'theme-woocommerce-style', SWIPER_HANDLE ) : array( 'theme-woocommerce-style' ),
		\Kwv\asset_version( 'assets/styles/woocommerce-brand-page.css' )
	);

	if ( ! $has_swiper ) {
		return;
	}

	wp_enqueue_script(
		SWIPER_HANDLE,
		plugins_url( SWIPER_DIR . 'swiper-bundle.min.js' ),
		array(),
		(string) filemtime( $swiper_js ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_enqueue_script(
		SCRIPT_HANDLE,
		get_theme_file_uri( 'assets/js/brand-slider.js' ),
		array( SWIPER_HANDLE ),
		\Kwv\asset_version( 'assets/js/brand-slider.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_localize_script(
		SCRIPT_HANDLE,
		'kwvBrandSlider',
		array(
			'previous'   => __( 'Previous product', 'kwv' ),
			'next'       => __( 'Next product', 'kwv' ),
			'thumbnails' => __( 'Choose a product', 'kwv' ),
			/* translators: %s: product name. */
			'show'       => __( 'Show %s', 'kwv' ),
			/* translators: 1: slide number, 2: number of slides. */
			'slide'      => __( 'Product {{index}} of {{slidesLength}}', 'kwv' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets', 20 );

/**
 * Tidy a product's short description inside a brand page slide.
 *
 * Short descriptions are often pasted from other sites and carry inline
 * styles, fixed widths and centring (KWV 10 Year Old Brandy's is a
 * 1876px-wide table), which broke out of the slide into its neighbours.
 * On brand pages only, this strips `style`, `width`, `height`, `align` and
 * pasted `data-*` attributes from everything inside the summary, so the text
 * takes the slide's own typography. The product data itself is unchanged, and
 * the single product page still shows it as entered. Table cells are flowed
 * as plain text by assets/styles/woocommerce-brand-page.css.
 *
 * @param string $block_content Rendered Product Summary block.
 * @return string
 */
function clean_product_summary( $block_content ) {

	if ( ! is_brand_page() || '' === (string) $block_content ) {
		return $block_content;
	}

	$html  = new \WP_HTML_Tag_Processor( $block_content );
	$depth = 0;

	while ( $html->next_tag() ) {

		// Leave the block's own two wrappers (and their font-size class) alone.
		if ( $depth < 2 ) {
			++$depth;
			continue;
		}

		foreach ( array( 'style', 'width', 'height', 'align' ) as $attribute ) {
			$html->remove_attribute( $attribute );
		}

		foreach ( (array) $html->get_attribute_names_with_prefix( 'data-' ) as $attribute ) {
			$html->remove_attribute( $attribute );
		}
	}

	return $html->get_updated_html();
}
add_filter( 'render_block_woocommerce/product-summary', __NAMESPACE__ . '\clean_product_summary' );
