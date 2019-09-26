/**
 * External dependencies
 */
import classnames from 'classnames';
import { indexOf } from 'lodash';

/**
 * WordPress dependencies
 */
const {
	__,
} = wp.i18n;

const {
	Component,
} = wp.element;

const {
	RichText,
} = wp.blockEditor;

class BlockEdit extends Component {
	render() {
		const {
			attributes,
			setAttributes,
			className,
		} = this.props;

		const {
			content,
		} = attributes;

		return [
			<div className={ className } key={ 'block' }>
				<p>Hello there!</p>
				<RichText
					placeholder={ __( 'General Kenobi!' ) }
					value={ content }
					onChange={ ( value ) => setAttributes( { content: value } ) }
					withoutInteractiveFormatting
					className={ classnames(
						'wp-block-allfacebook-instant-articles-hello__content', {
							'has-four-swords': indexOf( content, 'Kenobi' ) > -1,
						}
					) }
				/>
			</div>,
		];
	}
}

export default BlockEdit;
