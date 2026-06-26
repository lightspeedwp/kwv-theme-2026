/**
 * KWV mega menu — Interactivity API store.
 *
 * Drives the fold-out category columns rendered by inc/mega-menu.php. The root
 * element holds `activeParent` / `activeChild` in its context; hovering a parent
 * or child reveals the next column to the side. Derived state getters read the
 * local (inherited) context so each list/row knows whether it is active/visible.
 *
 * @package kwv
 */

import { store, getContext } from '@wordpress/interactivity';

store( 'kwv/megamenu', {
	state: {
		/** True when this parent column/row is the active one. */
		get isParentActive() {
			const ctx = getContext();
			return ctx.activeParent === ctx.parentSlug;
		},
		/** True when this child row is the active one. */
		get isChildActive() {
			const ctx = getContext();
			return ctx.activeChild === ctx.childSlug;
		},
		/** True when this grandchild list belongs to the active parent + child. */
		get isGrandchildVisible() {
			const ctx = getContext();
			return (
				ctx.activeParent === ctx.parentSlug &&
				ctx.activeChild === ctx.childSlug
			);
		},
	},
	actions: {
		/** Activate a parent and open its first (grandchild-bearing) child. */
		selectParent() {
			const ctx = getContext();
			ctx.activeParent = ctx.parentSlug;
			ctx.activeChild = ctx.firstChildSlug;
		},
		/** Activate a child, revealing its grandchildren column. */
		selectChild() {
			const ctx = getContext();
			ctx.activeChild = ctx.childSlug;
		},
	},
} );
