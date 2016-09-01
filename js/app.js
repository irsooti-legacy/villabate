var App = App || {};

App = {
	model: { 
		coordinates: // Interested centered zone
		{
			lat: 38.0788619, 
			lng: 13.4377403
		},

		img: []
	},

	// "`temp`" is a temporary variable to store lat and lng onclick
	temp: {},

	// map (initially empty) is the gMap already initialized
	map: {},

	// Save temporary user

	voteMemory: {},

	// markers returns the markers var stored in database at request.php
	markers: function() {
		return markers;
	},

	control: {

		getVoteMemory: function() {
			if (!localStorage.villapp)
				localStorage.villapp = JSON.stringify([]);
			return JSON.parse(localStorage.villapp);
		},

		saveVoteMemory: function(obj) {
			var newMemory = App.control.getVoteMemory();
			newMemory.push(obj);
			localStorage.villapp = JSON.stringify(newMemory);
		},

		storeLocalMemory: function(id, UpDown) {
			// GET VOTEMEMORY
			// CHECK IN EACH ARRAY IF EXISTS ID
			// IF EXIST CHANGE IT
			// ELSE CREATE NEW
			var newMemory = App.control.getVoteMemory(),
				idFinded = false,
				post = false

			$.each(App.control.getVoteMemory(), function(index, value) {

				if (id == value.id) {

					//

					if (value.vote && value.vote == UpDown) {

						// Well, there was a vote, but is the same given.
						// Throw the error and break the cycle.

						//alert('Vote already exists!');

						idFinded = true;	// ID found!
						post = false;		// Don't post, the vote already exists
						return false		// Break the cycle
					}

					newMemory[index].vote = UpDown;
					localStorage.villapp = JSON.stringify(newMemory);

					//alert('Vote updated succesfully!');
					idFinded = true;	// ID found!
					post = true;		// Post, there's a vote but is different
					return false;		// Break the cycle
				}

			});

			if (!idFinded) {
				//alert('Vote not found!');
				App.control.saveVoteMemory({ 'id': id, 'vote': UpDown});
				console.log(App.control.getVoteMemory().length);

				post = true;		// Post it, record is new!
			}

			return post;

		},
		// Returns the interested zone...
		getCoordinates: function () {
			return App.model.coordinates;
		},

		// Initialize google maps area and store the data returned in map var
		getMap: function() {
			var newMap;
			var minZoomLevel = 14;

			newMap = new google.maps.Map(App.view.$map, {
		      center: App.model.coordinates,
		      zoom: 16, //Zoom can be setted
		      styles: [{"featureType":"water","elementType":"all","stylers":[{"hue":"#76aee3"},{"saturation":38},{"lightness":-11},{"visibility":"on"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"hue":"#8dc749"},{"saturation":-47},{"lightness":-17},{"visibility":"on"}]},{"featureType":"poi.park","elementType":"all","stylers":[{"hue":"#c6e3a4"},{"saturation":17},{"lightness":-2},{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"hue":"#cccccc"},{"saturation":-100},{"lightness":13},{"visibility":"on"}]},{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"hue":"#5f5855"},{"saturation":6},{"lightness":-31},{"visibility":"on"}]},{"featureType":"road.local","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"simplified"}]},{"featureType":"water","elementType":"all","stylers":[]}]
		    });
			var strictBounds = new google.maps.LatLngBounds(
				new google.maps.LatLng(38.072775, 13.422820), 
				new google.maps.LatLng(38.086544, 13.460637)
			);

			// Listen for the dragend event
			google.maps.event.addListener(newMap, 'dragend', function() {
				if (strictBounds.contains(newMap.getCenter())) return;

			 // We're out of bounds - Move the map back within the bounds

		 	var c = newMap.getCenter(),
				x = c.lng(),
				y = c.lat(),
				maxX = strictBounds.getNorthEast().lng(),
				maxY = strictBounds.getNorthEast().lat(),
				minX = strictBounds.getSouthWest().lng(),
				minY = strictBounds.getSouthWest().lat();

				if (x < minX) x = minX;
				if (x > maxX) x = maxX;
				if (y < minY) y = minY;
				if (y > maxY) y = maxY;

		 		newMap.setCenter(new google.maps.LatLng(y, x));
			});

			// Limit the zoom level
			google.maps.event.addListener(newMap, 'zoom_changed', function() {
		 		if (newMap.getZoom() < minZoomLevel) newMap.setZoom(minZoomLevel);
			});

			App.map = newMap;

		},

		getReporter: function() {
			return 'Anonimo';
		},

		// Render all the initialized data
		render: function() {
			App.control.getMap();
			App.markers().forEach(function(langLat) {
				App.control.addMarker(langLat.lat, langLat.lng, langLat.titolo);
				App.view.$createDiv(langLat.titolo, langLat.lat, langLat.lng, langLat.voti, langLat.id);
			});
			App.view.init();
		},

		// Add new markers, storing:
		// * Position { lat, lng }
		// * title 'string'
		addMarker: function (lat, lng, title) {
	        var marker = new google.maps.Marker({
	            position: {lat: lat, lng: lng},
	            map: App.map,
	            title: title,

	            icon: {
    					url: './img/error.png',
    					// This marker is 20 pixels wide by 32 pixels high.
    					size: new google.maps.Size(32, 32)
    				}
	        });

	        var infowindow = new google.maps.InfoWindow({
			    content: '<h1>' + title + '</h1>'
			  });

         	marker.addListener('click', function() {
			    infowindow.open(map, marker);
		  	});
    	},

    	// This function center another area by clicking stored zone
    	changeLocation: function(lat, long) {
    		App.map.setCenter(
    			{lat: lat, lng: long})
    	},

    	// This function check the dialog after the click over the map. 
    	// If the value isn't empty ...
    	saveMarker: function(event) {
  			var contentData = {};
  			var value = App.view.$text();
        	if (value) {
	         	App.control.postReport({
	         		reporter: App.control.getReporter(),
	         		title: value,
	         		lat: App.control.temp.lat, 
	         		lng: App.control.temp.lng
	         	});
         	}

         	else value;
    	},

		postReport : function() {},
		postVote: function() {}
	},

	view: {
		init: function() {
			App.control.getVoteMemory();
			$(App.view.$map).css('height', App.view.$mapHeight());		// Set the current size of windows, just to be responsive
			$('.first-column').css('height', App.view.$mapHeight());	// Same, with left column\bottom column if mobile
			App.view.addListener();
			App.view.$votedBlock();
			// Add the listener on modal buttons
			$(document).ready(function(){
			    $('#content').keypress(function(e){
			      if(e.keyCode==13) // Mean the 'Enter Buttn'
			      $('#closeModal').click();
			    });
			});

			$('#scrollDown, #scrollDown a').click(function(event){
				event.preventDefault();
				var windowX2 = App.view.$mapHeight() * 2;
				$('html, body').animate(
					{ scrollTop: windowX2, easing: 'linear' }, 500);
			});

			$('#scrollTop, #scrollTop a').click(function(event){
				event.preventDefault();
				$('html, body').animate(
					{ scrollTop: 0, easing: 'linear' }, 500);
			});
		},
		$text: function() {
			var $val;
			$val = $('#content').val();
			console.log($val);
			if ($val == '') {
				$('#maybeSuccess').addClass('has-error'); // If the provided value is empty add this class
				return false;
			}
			
			return $val;
		},

		$textReset: function() {	// Clear the previous value provided
			$('#content').val('');
			$('#maybeSuccess').removeClass('has-error');
		},
		$createDiv: function(title, lat, lng, votes, id) { 	// Generate the marker's div [!] - This function generate an extra div
			var $el, $voteUp, $voteDown, $voteBlock;
			$el = $('<div id="report-' + id + '" class="report row"><div>');
			$voteDown = $('<i class="fa fa-chevron-circle-down fa-2x" aria-hidden="true"></i>');
			$voteUp = $('<i class="fa fa-chevron-circle-up fa-2x" aria-hidden="true"></i></p>');
			$voteBlock = $('<p class="col-xs-5 text-right"></p>');
			$voteNums = $('<span class="votes">' + votes + '</span>');

			$voteBlock.append($voteNums);
			$voteBlock.append($voteDown);
			$voteBlock.append($voteUp);

			$title = $('<p class="col-xs-7">' + title + '</p>');
			

			$('#reports').append($el);
			$el.append($title);
			$el.append($voteBlock);
			$el.append('<small class="col-sm-12 text-right">Segnalato da <b>Anonimo</b></small>');


			$voteUp.bind('click', function() {
				var plus1 = parseInt($el.find( "span" ).text()) + 1;
				console.log(id);

				/**	[POST]
				***	"id" => $id,
				***	"voti" => $voti
				**/

				App.control.postVote({id: id, voti: plus1}, 'up');
				//$el.find( "span" ).text(minus1); // TODO: put in Ajax call
				// Ajax call... 
			});

			$voteDown.bind('click', function() {
				var minus1 = parseInt($el.find( "span" ).text()) - 1;
				App.control.postVote({id: id, voti: minus1}, 'down');


				//$el.find( "span" ).text(plus1); // TODO: put in Ajax call
				// Ajax call... 
			});

			$title.bind('click', function() {
				//console.log(title);
				App.control.changeLocation(lat, lng);
			});
		},

		$map: document.getElementById('map'), // Get the map's container
		
		$mapHeight: function() {
			return $(window).height();
		},

		$changeVotes: function(id, num) {
			$('#report-' + id + ' .votes').text(num);
		},

		$modalToggle: function() {
			$('#modal > .modal').modal('toggle');
		},

		$votedBlock: function() {

			$('.report').removeClass('green red');
			$.each(App.control.getVoteMemory(), function(index, value) {

			if (value.vote == 'up') {
				$('#report-' + value.id).addClass('green');
			}

			if (value.vote == 'down') {
				$('#report-' + value.id).addClass('red');
			}

			});
		},

		addListener: function() {	// Add all useful listener
			window.addEventListener('resize', function(){
				$(App.view.$map).css('height', App.view.$mapHeight());
				$('.first-column').css('height', App.view.$mapHeight());
			});
	        google.maps.event.addListener(App.map, 'click', function(event) {
	        	App.view.$modalToggle();
	        	App.control.temp = { lat: event.latLng.lat(), lng: event.latLng.lng()};
	        });
		}

	}
}



/*
  function success(position) {
  	var latLong = {
  		lat: position.coords.latitude,
  		lng: position.coords.longitude
  	};

  	console.log(latLong);
  	return latLong;
  };

  function error() {
    return 'false'
  };

  navigator.geolocation.getCurrentPosition(success, error);

*/

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
}

$(window).height();