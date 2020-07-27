jQuery( document ).ready( function( $ ) {
	$( '.wpcm-location-picker' ).locationpicker({
		location: {
			latitude: Number( $( '.wpcm-latitude' ).val() ),
			longitude: Number( $( '.wpcm-longitude' ).val() ),
            place_id: String( $( '.place-id' ).val() )
		},
		radius: 0,
		inputBinding: {
	        latitudeInput: $( '.wpcm-latitude' ),
	        longitudeInput: $( '.wpcm-longitude' ),
	        locationNameInput: $( '.wpcm-address' ),
            locationPlaceIdInput: $( '.place-id' )
	    },
	    addressFormat: null,
	    enableAutocomplete: true
	});
});