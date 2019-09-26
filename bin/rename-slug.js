// Include prompt module.
const prompt = require( 'prompt' );
const forEach = require( 'lodash/forEach' );
const replace = require( 'replace-in-file' );

// This json object is used to configure what data will be retrieved from command line.
const promptAttributes = [
	{
		name: 'oldSlug',
		type: 'string',
		hidden: false,
		default: 'allfacebook-instant-articles',
	},
	{
		name: 'newSlug',
		type: 'string',
		hidden: false,
	},
];

// Start the prompt to read user input.
prompt.start();

// Prompt and get user input then display those data in console.
prompt.get( promptAttributes, function( err, result ) {
	if ( err ) {
		console.error( err ); // eslint-disable-line no-console
		return 1;
	}

	// Get user input from result object.
	const oldSlug = result.oldSlug;
	const newSlug = result.newSlug;
	const fallbackSlug = oldSlug.startsWith( '_' ) ? `bb${ oldSlug.substring( 1 ) }` : false;
	const newSlugDropCap = newSlug.startsWith( '_' ) ? newSlug.charAt( 1 ).toUpperCase() + newSlug.substring( 2 ) : newSlug.charAt( 0 ).toUpperCase() + newSlug.substring( 1 );

	const from = [ new RegExp( oldSlug, 'g' ) ];
	const fromCaps = [ new RegExp( oldSlug.toUpperCase(), 'g' ) ];
	const fromDropCap = oldSlug.startsWith( '_' ) ? '_' + oldSlug.charAt( 1 ).toUpperCase() + oldSlug.substring( 2 ) : oldSlug.charAt( 0 ).toUpperCase() + oldSlug.substring( 1 );

	if ( fallbackSlug ) {
		from.push( new RegExp( fallbackSlug, 'g' ) );
	}

	const replaceMap = {};
	replaceMap[ oldSlug ] = newSlug;

	if ( fallbackSlug ) {
		replaceMap[ fallbackSlug ] = newSlug;
	}

	const dir = __dirname;
	const dirBase = dir.substring( 0, dir.length - 4 );

	const options = {
		files: dirBase + '/**/*.*',
		from,
		to: newSlug,
		ignore: [ '**/node_modules/**' ],
	};
	const optionsCaps = {
		files: dirBase + '/**/*.*',
		from: fromCaps,
		to: newSlug.toUpperCase(),
		ignore: [ '**/node_modules/**' ],
	};
	const optionsDropCap = {
		files: dirBase + '/**/*.*',
		from: fromDropCap,
		to: newSlugDropCap,
		ignore: [ '**/node_modules/**' ],
	};

	const results = replace.sync( options );
	const resultsCaps = replace.sync( optionsCaps );
	const resultsDropCap = replace.sync( optionsDropCap );

	forEach( results, ( res ) => {
		if ( res.hasChanged ) {
			console.log( 'changed: ', res.file.replace( dirBase, '' ) ); // eslint-disable-line no-console
		}
	} );
	forEach( resultsCaps, ( res ) => {
		if ( res.hasChanged ) {
			console.log( 'changed: ', res.file.replace( dirBase, '' ) ); // eslint-disable-line no-console
		}
	} );
	forEach( resultsDropCap, ( res ) => {
		if ( res.hasChanged ) {
			console.log( 'changed: ', res.file.replace( dirBase, '' ) ); // eslint-disable-line no-console
		}
	} );
} );
