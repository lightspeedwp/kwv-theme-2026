/**
 * assets/js/mega-menu.js — Interactivity API store for the category mega menu.
 */
import { describe, it, expect } from 'vitest';
import { loadScript } from '../helpers/load.js';

let setContext;

async function megaStore() {
	await loadScript( 'assets/js/mega-menu.js' );
	// Same fresh registry as the script, so this is the instance it registered with.
	const stub = await import( '../stubs/interactivity.js' );
	setContext = stub.setContext;
	return stub.stores[ 'kwv/megamenu' ];
}

describe( 'mega-menu store', () => {
	it( 'registers under the kwv/megamenu namespace', async () => {
		const store = await megaStore();

		expect( Object.keys( store.state ) ).toEqual( [
			'isParentActive',
			'isChildActive',
			'isGrandchildVisible',
		] );
		expect( Object.keys( store.actions ) ).toEqual( [
			'selectParent',
			'selectChild',
		] );
	} );

	it( 'derives active/visible state from the local context', async () => {
		const { state } = await megaStore();

		setContext( {
			activeParent: 'wine',
			parentSlug: 'wine',
			activeChild: 'red',
			childSlug: 'red',
		} );
		expect( state.isParentActive ).toBe( true );
		expect( state.isChildActive ).toBe( true );
		expect( state.isGrandchildVisible ).toBe( true );

		setContext( {
			activeParent: 'wine',
			parentSlug: 'spirits',
			activeChild: 'red',
			childSlug: 'red',
		} );
		expect( state.isParentActive ).toBe( false );
		expect( state.isGrandchildVisible ).toBe( false );

		setContext( {
			activeParent: 'wine',
			parentSlug: 'wine',
			activeChild: 'red',
			childSlug: 'white',
		} );
		expect( state.isChildActive ).toBe( false );
		expect( state.isGrandchildVisible ).toBe( false );
	} );

	it( 'selectParent activates the parent and its first child', async () => {
		const { actions } = await megaStore();
		const ctx = {
			activeParent: 'wine',
			activeChild: 'red',
			parentSlug: 'spirits',
			firstChildSlug: 'brandy',
		};
		setContext( ctx );

		actions.selectParent();

		expect( ctx ).toMatchObject( {
			activeParent: 'spirits',
			activeChild: 'brandy',
		} );
	} );

	it( 'selectChild activates the child only', async () => {
		const { actions } = await megaStore();
		const ctx = {
			activeParent: 'wine',
			activeChild: 'red',
			childSlug: 'white',
		};
		setContext( ctx );

		actions.selectChild();

		expect( ctx ).toMatchObject( {
			activeParent: 'wine',
			activeChild: 'white',
		} );
	} );
} );
