/**
 * WordPress dependencies
 */
const {
	__,
} = wp.i18n;

/**
 * Internal dependencies
 */
import edit from './edit';
import icons from '../../blocks-helper/icons';
import metadata from './block.json';
import save from './save';

const { name } = metadata;

export { metadata, name };

export const settings = {
	title: __( 'Hello allfacebook-instant-articles', 'allfacebook-instant-articles' ),
	description: __( 'Sample block of the wp-plugin-boilerplate by Luehrsen // Heinrich.', 'allfacebook-instant-articles' ),
	icon: icons.hello,
	keywords: [
		__( 'hello', 'allfacebook-instant-articles' ),
		__( 'allfacebook-instant-articles', 'allfacebook-instant-articles' ),
	],
	supports: {
		align: true,
		alignWide: false,
	},
	edit,
	save,
};
