<?php
/**
 * Brand pages: the brand shop and the brand showcase.
 *
 * A brand has two pages:
 *
 * - **Brand shop**, `/brand/{brand}/` — the brand's ordinary term archive
 *   (templates/taxonomy-product_brand.html → patterns/woo-product-archive.php):
 *   the banner and the filtered product grid, exactly like a category. Every
 *   link WordPress builds to a brand (menus, filters, `get_term_link()`) lands
 *   here.
 * - **Brand showcase**, `/brand/{brand}/showcase/` — the brand's logo,
 *   introduction and a one-product-at-a-time slider
 *   (templates/brand-showcase.html → patterns/woo-brand-archive.php). The home
 *   page brand carousels link here, and so does every product: a product's own
 *   page redirects to its brand's showcase with that product selected
 *   (`#product-{ID}`, read by assets/js/brand-slider.js).
 *
 * On the showcase the products are a normal WooCommerce Product Collection
 * whose product template is the editable "Brand Product Slide" template part;
 * assets/js/brand-slider.js turns that list into the slider with Swiper.
 * Without JavaScript the products simply stay a stacked list.
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
 * The URL segment after `/brand/{brand}/` that selects the showcase.
 */
const SHOWCASE_ENDPOINT = 'showcase';

/**
 * Query var set on a showcase request (by the rewrite rule below).
 */
const VIEW_QUERY_VAR = 'kwv_brand_view';

/**
 * Block template (templates/brand-showcase.html) for the showcase.
 */
const SHOWCASE_TEMPLATE = 'brand-showcase';

/**
 * Whether the current request is a brand showcase (the slider page).
 *
 * The brand shop (`/brand/{brand}/` without the endpoint) is not: it is an
 * ordinary product archive.
 *
 * @return bool
 */
function is_brand_page() {
	return is_tax( TAXONOMY ) && SHOWCASE_ENDPOINT === get_query_var( VIEW_QUERY_VAR );
}

/**
 * The brand's URL base (`brand`), as WooCommerce registered the taxonomy.
 *
 * @return string
 */
function brand_base() {

	$taxonomy = get_taxonomy( TAXONOMY );
	$slug     = ( $taxonomy && is_array( $taxonomy->rewrite ) && ! empty( $taxonomy->rewrite['slug'] ) ) ? $taxonomy->rewrite['slug'] : 'brand';

	return trim( (string) $slug, '/' );
}

/**
 * The rewrite rule's regex for `/brand/{brand}/showcase/`.
 *
 * @return string
 */
function showcase_rule() {
	return '^' . preg_quote( brand_base(), '#' ) . '/([^/]+)/' . SHOWCASE_ENDPOINT . '/?$';
}

/**
 * Register `/brand/{brand}/showcase/`.
 *
 * Runs after WooCommerce registers the taxonomy (init, priority 5). If the
 * stored rewrite rules don't have the rule yet — first load after a deploy —
 * they are rebuilt once, so no manual "Save permalinks" is needed.
 */
function register_showcase_route() {

	if ( ! taxonomy_exists( TAXONOMY ) ) {
		return;
	}

	$rule = showcase_rule();

	add_rewrite_rule( $rule, 'index.php?' . TAXONOMY . '=$matches[1]&' . VIEW_QUERY_VAR . '=' . SHOWCASE_ENDPOINT, 'top' );

	$stored = get_option( 'rewrite_rules' );

	if ( is_array( $stored ) && ! isset( $stored[ $rule ] ) ) {
		flush_rewrite_rules( false );
	}
}
add_action( 'init', __NAMESPACE__ . '\register_showcase_route', 20 );

/**
 * Let WordPress read the showcase query var.
 *
 * @param string[] $vars Public query vars.
 * @return string[]
 */
function add_query_var( $vars ) {
	$vars[] = VIEW_QUERY_VAR;
	return $vars;
}
add_filter( 'query_vars', __NAMESPACE__ . '\add_query_var' );

/**
 * A brand's showcase URL, optionally with one product selected.
 *
 * @param \WP_Term|int $brand      Brand term or ID.
 * @param int          $product_id Product to select, or 0 for the first.
 * @return string The URL, or '' for an unknown brand.
 */
function showcase_url( $brand, $product_id = 0 ) {

	$link = get_term_link( $brand, TAXONOMY );

	if ( is_wp_error( $link ) ) {
		return '';
	}

	if ( get_option( 'permalink_structure' ) ) {
		$url = trailingslashit( $link ) . user_trailingslashit( SHOWCASE_ENDPOINT );
	} else {
		$url = add_query_arg( VIEW_QUERY_VAR, SHOWCASE_ENDPOINT, $link );
	}

	return $product_id > 0 ? $url . '#product-' . absint( $product_id ) : $url;
}

/**
 * Serve the showcase with its own block template.
 *
 * Prepends templates/brand-showcase.html to the taxonomy hierarchy; the brand
 * shop keeps taxonomy-product_brand.html.
 *
 * @param string[] $templates Template slugs, most specific first.
 * @return string[]
 */
function showcase_template_hierarchy( $templates ) {

	if ( is_brand_page() ) {
		array_unshift( $templates, SHOWCASE_TEMPLATE );
	}

	return $templates;
}
add_filter( 'taxonomy_template_hierarchy', __NAMESPACE__ . '\showcase_template_hierarchy' );

/**
 * Name the showcase template in the Site Editor.
 *
 * @param array $types Template types: slug => title and description.
 * @return array
 */
function register_showcase_template_type( $types ) {

	$types[ SHOWCASE_TEMPLATE ] = array(
		'title'       => _x( 'Brand Showcase', 'Template name', 'kwv' ),
		'description' => __( 'A brand\'s logo, introduction and product slider, at /brand/…/showcase/. The home page brand carousels and every product link here. The brand\'s shop grid uses the Products by Brand template.', 'kwv' ),
	);

	return $types;
}
add_filter( 'default_template_types', __NAMESPACE__ . '\register_showcase_template_type' );

/**
 * The brand whose showcase a product belongs on, or null.
 *
 * Only products the slider can sell directly are sent there: visible, simple
 * products with a brand. A product with options (variable, grouped,
 * subscription) needs its own page to choose them — the slide's button links
 * to it — so those keep the single product page, as do products with no
 * brand (e.g. Lifestyle merchandise). Filter `kwv_product_showcase_brand`
 * to change the choice; return null to keep a product's own page.
 *
 * @param \WC_Product $product The product.
 * @return \WP_Term|null
 */
function product_showcase_brand( $product ) {

	$brand = null;

	if ( $product instanceof \WC_Product && $product->is_type( 'simple' ) && $product->is_visible() ) {
		$brands = get_the_terms( $product->get_id(), TAXONOMY );

		if ( is_array( $brands ) && $brands ) {
			// A product in several brands goes to the first (by name).
			usort(
				$brands,
				static function ( $a, $b ) {
					return strcasecmp( $a->name, $b->name );
				}
			);
			$brand = $brands[0];
		}
	}

	/**
	 * Filters the brand whose showcase a product page redirects to.
	 *
	 * @param \WP_Term|null $brand   The brand, or null to keep the product page.
	 * @param \WC_Product   $product The product.
	 */
	$brand = apply_filters( 'kwv_product_showcase_brand', $brand, $product );

	return $brand instanceof \WP_Term ? $brand : null;
}

/**
 * Where a product page request should go instead, or ''.
 *
 * The single product page is retired for branded products: they are shown
 * on their brand's showcase with the product selected. Left alone: previews,
 * non-GET requests (a form posting Add to Cart to the product URL) and any
 * request carrying `add-to-cart`.
 *
 * @return string
 */
function product_redirect_url() {

	if ( ! is_singular( 'product' ) || is_preview() || is_customize_preview() ) {
		return '';
	}

	$method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( sanitize_key( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : 'GET';

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- presence check only.
	if ( 'GET' !== $method || isset( $_GET['add-to-cart'] ) ) {
		return '';
	}

	$product = function_exists( 'wc_get_product' ) ? wc_get_product( get_queried_object_id() ) : null;
	$brand   = $product ? product_showcase_brand( $product ) : null;

	return $brand ? showcase_url( $brand, $product->get_id() ) : '';
}

/**
 * Redirect a branded product's page to its brand showcase.
 *
 * A temporary (302) redirect, so the decision can be reversed without browsers
 * and search engines having cached it; filter `kwv_product_redirect_status`
 * to make it permanent once settled.
 */
function redirect_product_to_showcase() {

	$url = product_redirect_url();

	if ( '' === $url ) {
		return;
	}

	$status = (int) apply_filters( 'kwv_product_redirect_status', 302 );

	if ( wp_safe_redirect( $url, $status, 'KWV' ) ) {
		exit;
	}
}
add_action( 'template_redirect', __NAMESPACE__ . '\redirect_product_to_showcase', 5 );

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
 * Tidy a product's description inside a brand page slide.
 *
 * The slide shows the main Product description (the field KWV edits).
 * Descriptions are often pasted from other sites and carry inline styles,
 * fixed widths and centring (a pasted 1876px-wide table, for example), which
 * would break out of the slide into its neighbours. On brand pages only, this
 * strips `style`, `width`, `height`, `align` and pasted `data-*` attributes
 * from everything inside the Product Description block, so the text takes
 * the slide's own typography. The product data itself is unchanged, and the
 * single product page still shows it as entered. Table cells are flowed as
 * plain text by assets/styles/woocommerce-brand-page.css.
 *
 * @param string $block_content Rendered Product Description block.
 * @return string
 */
function clean_product_description( $block_content ) {

	if ( ! is_brand_page() || '' === (string) $block_content ) {
		return $block_content;
	}

	$html  = new \WP_HTML_Tag_Processor( $block_content );
	$first = true;

	while ( $html->next_tag() ) {

		// Leave the block's own wrapper (and its font-size class) alone.
		if ( $first ) {
			$first = false;
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
add_filter( 'render_block_woocommerce/product-description', __NAMESPACE__ . '\clean_product_description' );
