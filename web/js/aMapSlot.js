function aMapSlotConstructor() 
{
	// API loaded flag
	this.isLoaded = 0;

	this.loaded = function()
	{
		this.isLoaded = 1;
	};
	
	// Call to load the Google Maps API
	this.loadGoogleMapsAPI = function() {
		if (!this.isLoaded)
		{
		  var script = document.createElement("script");
		  script.type = "text/javascript";
		  script.src = "http://maps.google.com/maps/api/js?sensor=false&callback=aMapSlot.loaded";
		  document.body.appendChild(script);
		}
	};
	
	this.createGoogleMap = function(options)
	{
		if (this.IsLoaded)
		{
			// Cute default: Punk Ave Studio
			var aLatitude = options['latitude'] ? options['latitude'] : '39.934259';
			var aLongitude = options['longitude'] ? options['longitude'] : '-75.158228';
			var aZoom = options['zoom'] ? options['zoom'] : 8;
			var aMapType = options['mapType'] ? options['mapType'] : 'ROADMAP';
			var aMapContainer = $(options['mapContainer']);			
			
			if (aMapContainer.length)
			{
				var aLatlng = new google.maps.LatLng(aLatitude, aLongitude);
				var aOptions = {
					zoom: aZoom,
					center: aLatlng,
					mapTypeId: google.maps.MapTypeId.ROADMAP
					// mapTypeId: google.maps.MapTypeId. + aMapType
				}		
				var map = new google.maps.Map(aMapContainer[0], aOptions);				
			}
			else
			{
				// Letting you know your map container ID is bad
				apostrophe.log('aMapSlot -- Map container not found.');
			}
		}
		else
		{
			// It would be cool to load the Google Maps API 
			// and then load the map again. But I'll just settle for
			// giving you a message for now.
			// this.loadGoogleMapsAPI(options);
			apostrophe.log('aMapSlot -- Maps API is not loaded.');
		}
	};
	
}

window.aMapSlot = new aMapSlotConstructor();