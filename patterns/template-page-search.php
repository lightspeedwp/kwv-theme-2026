<?php
/**
 * Title: Template: Search Results
 * Slug: kwv/template-page-search
 * Description: Search results body — light header, a search-query title with a search form, a grid of matching post cards with pagination (and a no-results message), then the dark footer. Used by the search template.
 * Categories: hidden
 * Keywords: template, search, results, query
 * Block Types: core/query
 * Template Types: search
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 */
?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Search Results"},"align":"full","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<main class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)">

	<!-- wp:group {"metadata":{"name":"Search Header"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:query-title {"type":"search","level":1,"fontFamily":"heading","fontSize":"700","textColor":"contrast","style":{"typography":{"fontWeight":"var:custom|font-weight|black","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}}} /-->

		<!-- wp:search {"label":"<?php echo esc_attr_x( 'Search', 'search form label', 'kwv' ); ?>","showLabel":false,"placeholder":"<?php echo esc_attr_x( 'Search the site…', 'search form placeholder', 'kwv' ); ?>","buttonText":"<?php echo esc_attr_x( 'Search', 'search form button', 'kwv' ); ?>","buttonPosition":"button-inside"} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:query {"queryId":0,"query":{"perPage":12,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"layout":{"type":"default"}} -->
	<div class="wp-block-query">
		<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","columnCount":3}} -->
			<?php require __DIR__ . '/blog-card.php'; ?>
		<!-- /wp:post-template -->
		<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}}} -->
			<!-- wp:query-pagination-previous /-->
			<!-- wp:query-pagination-numbers /-->
			<!-- wp:query-pagination-next /-->
		<!-- /wp:query-pagination -->
		<!-- wp:query-no-results -->
			<!-- wp:paragraph {"textColor":"neutral-700","fontSize":"300"} -->
			<p class="has-neutral-700-color has-text-color has-300-font-size"><?php esc_html_e( 'Sorry, no results were found. Try a different search term.', 'kwv' ); ?></p>
			<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->

</main>
<!-- /wp:group -->

