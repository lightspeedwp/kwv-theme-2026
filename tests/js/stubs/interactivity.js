/**
 * Test stand-in for `@wordpress/interactivity`.
 *
 * Records every store() definition and lets a test choose the context that
 * getContext() returns, so store getters and actions can be exercised directly.
 */
export const stores = {};

let currentContext = {};

export function setContext( context ) {
	currentContext = context;
}

export function getContext() {
	return currentContext;
}

export function store( namespace, definition ) {
	stores[ namespace ] = definition;
	return definition;
}
