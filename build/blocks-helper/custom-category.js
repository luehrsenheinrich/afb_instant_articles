/**
 * WordPress dependencies
 */
const { getCategories, setCategories } = wp.blocks;

/**
 * Internal dependencies
 */
import icons from './icons';

/**
 * Internal dependencies
 */

setCategories( [
	// Add a allfacebook-instant-articles block category
	{
		slug: 'allfacebook-instant-articles',
		title: 'allfacebook-instant-articles',
		icon: icons.afbia,
	},
	...getCategories().filter( ( { slug } ) => slug !== 'allfacebook-instant-articles' ),
] );
