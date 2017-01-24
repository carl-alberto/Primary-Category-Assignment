jQuery( document ).ready(function( $ ) {

//	var pcAjaxObject
	var ajaxurl = pcAjaxObject.ajax_url; // jshint ignore:line
	var sitesearchurl = pcAjaxObject.site_search_url; // jshint ignore:line
	var searchq;
	var ajax2 = {
			'action': 'pc_ajax_request'
		};

	$.post( ajaxurl, ajax2, function( response ) {
		$( '#pc-searchfilter' ).html( response );
	});

	function SearchString() {
		return '/?q=' + $( '.search-field' ).val();
	}

	function SearchTaxonomy() {
		var searchsplit = $( '#alltaxonomies' ).val().split( '--' );
		var searchfinal;
		if ( searchsplit[0].length !== 0 ) {
			searchfinal = '&post_type=' + searchsplit[0] + '&' + searchsplit[1] + '=' + searchsplit[2];
			return searchfinal;
		}
	}

	$( '.sc-search-submit' ).click(function() {
		if ( $( '.search-field' ).val() ) {
			if ( $( '#alltaxonomies' ).val().length !== 0  ) {
				searchq = sitesearchurl + SearchString() + SearchTaxonomy() ;
				window.location.href = searchq;
			} else {
				searchq = sitesearchurl + SearchString();
				window.location.href = searchq;
			}
		} else {
			$( '#pc-searchfilter' ).append( '<div class="searcherror">Enter your search query</div>' );
			$( '.searcherror' ).delay( 1000 ).fadeOut( 'slow' );
		}
	});

	$( '.search-form.custom-search' ).after( '<div id="pc-searchfilter"></div>' );

});
