/**
 * External dependencies
 */
import classnames from 'classnames';
import { indexOf } from 'lodash';

/**
 * WordPress dependencies
 */
const {
	RichText,
} = wp.blockEditor;

export default ( { attributes, className } ) => {
	const {
		content,
	} = attributes;

	const contentClasses = classnames( 'wp-block-allfacebook-instant-articles-hello__content', {
		'has-four-swords': indexOf( content, 'Kenobi' ) > -1,
	} );

	return (
		<div className={ className }>
			<p>Hello there!</p>
			<RichText.Content
				className={ contentClasses }
				value={ content }
			/>
		</div>
	);
};
