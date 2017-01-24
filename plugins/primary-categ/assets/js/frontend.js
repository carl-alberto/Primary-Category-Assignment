jQuery(document).ready(function($) {

	var ajaxurl = pc_ajax_object.ajax_url;
	var searchq;
	var ajax2 = {
			'action': 'pc_ajax_request'
		};

	$.post(ajaxurl, ajax2, function(response) {
	console.log(response);
		document.getElementById(".searchgroup").innerHTML = "a123sdfa adsfa s";
	});

	function search_string() {
		return '/?q=' + $( ".search-field" ).val();
	}

	function search_taxo() {
		return '&primacat=' + $( "#primacat" ).val();
	}

	$( ".search-submit" ).hover(
		function() {
			searchq = pc_ajax_object.site_search_url + search_string() + search_taxo() ;
			console.log(searchq);
		}
	);

	$( ".sc-search-submit" ).click(function() {
		if ( $( ".search-field" ).val() ) {
			searchq = pc_ajax_object.site_search_url + search_string() + search_taxo() ;
			window.location.href = searchq;
		} else {
			 alert( "Please enter your search query" );
		}
	});

	$(".search-form").after("<div id='searchgroup'>ADding dropdown</div>" );

});
