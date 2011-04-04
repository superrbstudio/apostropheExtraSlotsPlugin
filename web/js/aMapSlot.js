function aMapSlotConstructor() 
{
	this.isLoaded = 0;
	
	this.loadGoogleMapsAPI = function() {
		if (!this.isLoaded)
		{
		  var script = document.createElement("script");
		  script.type = "text/javascript";
		  script.src = "http://maps.google.com/maps/api/js?sensor=false&callback=initialize";
		  document.body.appendChild(script);
			this.isLoaded = 1;
		};
	}
	
	this.loadMap = function(){
		
	}
	
}

window.aMapSlot = new aMapSlotConstructor();