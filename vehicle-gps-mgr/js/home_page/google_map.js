var map;
var geocoder;

function initialize() {
	geocoder = new google.maps.Geocoder();

	showAddress("北京");
}

function load_map(latlng) {
	var myOptions = {
		zoom : 8,
		center : latlng,
		mapTypeId : google.maps.MapTypeId.SATELLITE
	};

	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
}

function showAddress(address) {
	if (geocoder) {
		geocoder.geocode({
			'address' : address
		}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				load_map(results[0].geometry.location);
			} else {
				alert("Geocode was not successful for the following reason: "
						+ status);
			}
		});
	}
}