$(function(e){
	'use strict';

	// Data Table
	$('#support-categorylist').DataTable({
		"order": [
			[ 0, "desc"]
		],
		order: [],
		columnDefs: [ 
			{ orderable: false, targets: [0, 2, 3, 4, 5] } ,
		],
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
			
		}
	});

	// Select2
	$('.select2').select2({
		minimumResultsForSearch: Infinity
	});

 });

 