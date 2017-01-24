jQuery(document).ready(function($) {

	var ajaxurl = pc_ajax_object.ajax_url;
	var searchq;
	var ajax2 = {
			'action': 'pc_ajax_request'
		};

	$.post(ajaxurl, ajax2, function(response) {
	console.log(response);
		$( "#pc-searchfilter" ).html( response );
	});

	function search_string() {
		return '/?q=' + $( ".search-field" ).val();
	}

	function search_taxo() {
		var searchsplit = $( "#alltaxonomies" ).val().split("--");
		if ( searchsplit[0].length !=0 ) {
			var searchfinal = "post_type=" + searchsplit[0] + '&' + searchsplit[1] + '=' + searchsplit[2];
			return searchfinal;
		} else {
			return value == '';
		}
	}

	$( ".sc-search-submit" ).click(function() {
		if ( $( ".search-field" ).val() ) {
			if ( $( "#alltaxonomies" ).val().length !=0  ) {
				searchq = pc_ajax_object.site_search_url + search_string() + search_taxo() ;
				window.location.href = searchq;
			} else {
				searchq = pc_ajax_object.site_search_url + search_string();
				window.location.href = searchq;
			}
		} else {
			$( "#pc-searchfilter" ).append( "<div class='searcherror'>Enter your search query</div>" );
			$('.searcherror').delay(1000).fadeOut('slow');
		}
	});

	$(".search-form.custom-search").after("<div id='pc-searchfilter'></div>" );

});
