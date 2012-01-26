function aExtraSlotsConstructor()
{
	// JS for Extra Slots goes here

	this.setupAAnchorNavigation = function(options)
	{
		apostrophe.log('aExtraSlots.setupAAnchorNavigation');
		// Listen for hash changes and update navigation component class names

		var win = $(window),
				nav = $('#' + options['navId']),
				anchors = nav.find('.a-nav-item a');
				hash = window.location.hash;

		if (nav.length)
		{
			// On load, check for the hash
			// If it's not empty, look to apply .a-current-page to an anchor
			if (hash !== '') 
			{
				currentAnchor = nav.find('a[href="' + hash + '"]');
				if (currentAnchor && currentAnchor.length) 
				{
					apostrophe.log(currentAnchor);
					currentAnchor.closest('.a-nav-item').addClass('a-current-page');					
				}
			}	
			// Click even for nav items updates .a-current-page to last clicked item
			anchors.unbind('click.setupAAnchorNavigation').bind('click.setupAAnchorNavigation', function(event){
				var anchor = $(this);
				nav.find('.a-nav-item').removeClass('a-current-page');
				anchor.parent().addClass('a-current-page');
			});
		}
		else
		{
			throw "aExtraSlots.setupAAnchorNavigation -- No navigation found!";
		}	
	};
	
	this.setupReusableSlideshowSlot = function(options)
	{
		var form = $('#' + options.id);
		form.find('.a-js-label-or-reuse').change(function() {
			update();
		});
		update();
		function update()
		{
			var val = form.find('.a-js-label-or-reuse:checked').val();
			if (val === 'label')
			{
				form.find('.a-js-label').removeAttr('disabled');
				form.find('.a-js-reuse').attr('disabled', 'disabled');
			}
			else
			{
				form.find('.a-js-label').attr('disabled', 'disabled');
				form.find('.a-js-reuse').removeAttr('disabled');
			}
		}
	}
	
	// Initialize lightbox js for photoGridSlot :
	
	this.initLightbox = function()
	{
		$('.light-box').lightBox();
	}
}

function aMapSlotConstructor()
{
	// API loaded flag
	// Don't let the object context change -
	// in setTimeout(), for instance, 'this' is
	// something else entirely later if you pass a method
	// that's a property of our object. $this is guaranteed
	// to be the original object when we constructed it

	var $this = this;
	$this.isLoaded = 0;
  $this.isLoading = 0;
	$this.loaded = function()
	{
		$this.isLoaded = 1;
		apostrophe.log('aMapSlot -- Maps API Loaded');
	};

	// Call to load the Google Maps API
	$this.loadGoogleMapsAPI = function() {
		if ($this.isLoading)
		{
			return;
		}
		$this.isLoading = true;
		apostrophe.log('aMapSlot -- loadGoogleMapsAPI');
		if (!$this.isLoaded)
		{
			apostrophe.log('aMapSlot -- loadGoogleMapsAPI: NOT Loaded');
		  var script = document.createElement("script");
		  script.type = "text/javascript";
		  script.src = "http://maps.google.com/maps/api/js?sensor=false&callback=aMapSlot.loaded";
		  document.body.appendChild(script);
		}
	};

	$this.createGoogleMap = function(options)
	{
		apostrophe.log('aMapSlot -- createGoogleMap');
		if ($this.isLoaded)
		{
				apostrophe.log('aMapSlot -- createGoogleMap: isLoaded');
				var aLatitude = options['latitude'] ? options['latitude'] : '39.934259'; // Cute default: Punk Ave Studio
				var aLongitude = options['longitude'] ? options['longitude'] : '-75.158228'; // Cute default: Punk Ave Studio
				var aZoom = options['zoom'] ? options['zoom'] : 14;
				var aMapType = options['mapType'] ? options['mapType'] : 'roadmap';
				var aMapContainer = $(options['container']);
				var aControls = options['controls'];
				var aMapTypeOptions = {
					'terrain' : google.maps.MapTypeId.TERRAIN,
					'roadmap' : google.maps.MapTypeId.ROADMAP,
					'satellite' : google.maps.MapTypeId.SATELLITE,
					'hybrid' : google.maps.MapTypeId.HYBRID
				}

				if (aMapContainer.length)
				{
					var aLatlng = new google.maps.LatLng(aLatitude, aLongitude);
					var aOptions = {
						zoom: parseInt(aZoom),
						center: aLatlng,
						disableDefaultUI: true, // Disables the entire UI
				    panControl: aControls['pan'], // Selectively re-enable elements
				    zoomControl: aControls['zoom'],
				    scaleControl: aControls['scale'],
						mapTypeId: aMapTypeOptions[aMapType]
					}
					var map = new google.maps.Map(aMapContainer[0], aOptions);
					var marker = new google.maps.Marker({
			      position: aLatlng,
			      map: map,
			      title: options['title']
				  });
				}
				else
				{
					// Letting you know your map container ID is bad
					apostrophe.log('aMapSlot -- Map container not found.');
				}
		}
		else
		{
			apostrophe.log('aMapSlot -- Maps API is NOT loaded');
			$this.loadGoogleMapsAPI();
			var tryAgain = window.setTimeout(function() {
				$this.createGoogleMap(options);
			}, 1000);
		}
	};

}

window.aMapSlot = new aMapSlotConstructor();
window.aExtraSlots = new aExtraSlotsConstructor();