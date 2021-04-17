
/* Returns a querystring of BP cookies (cookies beginning with 'bp-') */
function bp_get_cookies() {
	var allCookies = document.cookie.split( ';' ), // get all cookies and split into an array
		bpCookies = {},
		cookiePrefix = 'bp-',
		i, cookie, delimiter, name, value;

	// loop through cookies
	for ( i = 0; i < allCookies.length; i++ ) {
		cookie = allCookies[i];
		delimiter = cookie.indexOf( '=' );
		name = jQuery.trim( unescape( cookie.slice( 0, delimiter ) ) );
		value = unescape( cookie.slice( delimiter + 1 ) );

		// if BP cookie, store it
		if ( name.indexOf( cookiePrefix ) === 0 ) {
			bpCookies[name] = value;
		}
	}

	// returns BP cookies as querystring
	return encodeURIComponent( jQuery.param( bpCookies ) );
}

/**
 * Get a querystring parameter from a URL.
 *
 * @param {string} Query string parameter name.
 * @param param
 * @param url
 * @param {string} URL to parse. Defaults to current URL.
 */
function bp_get_query_var( param, url ) {
	var qs = {};

	// Use current URL if no URL passed.
	if ( typeof url === 'undefined' ) {
		url = location.search.substr( 1 ).split( '&' );
	} else {
		url = url.split( '?' );
		url = url.length > 1 ? url[1].split( '&' ) : [];
	}

	// Parse querystring into object props.
	// http://stackoverflow.com/a/21152762
	url.forEach( function( item ) {
		qs[item.split( '=' )[0]] = item.split( '=' )[1] && decodeURIComponent( item.split( '=' )[1] );
	} );

	if ( qs.hasOwnProperty( param ) && qs[param] != null ) {
		return qs[param];
	}
	return false;
}

/**
 * Deselects any select options or input options for the specified field element.
 *
 * @param {string} container HTML ID of the field
 */
function clear( container ) {
	var radioButtons, options, i;
	container = document.getElementById( container );
	if ( ! container ) {
		return;
	}

	radioButtons = container.getElementsByTagName( 'INPUT' );
	options = container.getElementsByTagName( 'OPTION' );
	i = 0;

	if ( radioButtons ) {
		for ( i = 0; i < radioButtons.length; i++ ) {
			radioButtons[i].checked = '';
		}
	}

	if ( options ) {
		for ( i = 0; i < options.length; i++ ) {
			options[i].selected = false;
		}
	}
}
