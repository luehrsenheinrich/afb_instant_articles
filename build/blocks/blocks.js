/**
 * WordPress dependencies
 */
const {
	registerBlockType,
	unstable__bootstrapServerSideBlockDefinitions, // eslint-disable-line camelcase
} = wp.blocks;

/**
 * Internal dependencies
 */
import * as hello from './hello-there';

/**
 * Function to register an individual block.
 *
 * @param {Object} block The block to be registered.
 *
 */
const registerBlock = ( block ) => {
	if ( ! block ) {
		return;
	}

	const { metadata, settings, name } = block;

	if ( metadata ) {
		unstable__bootstrapServerSideBlockDefinitions( { [ name ]: metadata } );
	}

	registerBlockType( name, settings );
};

[
	hello,
].forEach( registerBlock );
