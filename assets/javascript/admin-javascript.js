/**
 * Admin javascript file.
 *
 * @package Plance\Plugin\My_Maps
 */

let map;
let marker;
let geocoder;

window.initMap = function() {
	map = new google.maps.Map(
		document.getElementById( 'my-map' ),
		{
			zoom: 12,
		}
	);

	geocoder = new google.maps.Geocoder();

	const input        = document.getElementById( 'my-map-address' );
	const autocomplete = new google.maps.places.Autocomplete(
		input,
		{
			types: ['geocode']
		}
	);
	autocomplete.bindTo( 'bounds', map );
	autocomplete.addListener(
		'place_changed',
		function() {
			const place = autocomplete.getPlace();
			if ( ! place.geometry || ! place.geometry.location ) {
				alert( 'Location not found' );
				return;
			}

			updateMapWithLocation( place.geometry.location );
		}
	);

	const initialAddress = input.value.trim();
	if ( '' !== initialAddress ) {
		geocodeAddress( initialAddress );
	}
}

function updateMapWithLocation( location ) {
	map.setCenter( location );
	map.setZoom( 16 );

	if ( marker ) {
		marker.setMap( null );
	}

	marker = new google.maps.Marker(
		{
			map:      map,
			position: location,
		}
	);
}

function geocodeAddress( address ) {
	geocoder.geocode(
		{
			address: address
		},
		function ( results, status ) {
			if ( 'OK' === status && results[0] ) {
				const location = results[0].geometry.location;
				updateMapWithLocation( location );
			} else {
				console.warn( 'Geocode error:', status );
			}
		}
	);
}
