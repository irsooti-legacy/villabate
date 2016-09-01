var App = App || {};

App.control.postReport = function(obj) {
	var xhr;

	// Get the document, attend a response...
	xhr = $.ajax({
		method: "POST",
  		url: "inc/post_markers.php",
  		data: obj,
  		dataType: "JSON"
  		//context: document.body
	});

	// Done
	xhr.done(function(data) {
		var insertData = data;
		console.log(insertData);
		App.control.addMarker( 		// ... Marker is added
			insertData.lat, 		// * Position: { lat: float }
			insertData.lng,			// * Position: { lng: float }
			insertData.title		// * Title - 'String'
		);							

		App.view.$createDiv(insertData.title, insertData.lat, insertData.lng, insertData.voti, insertData.id); // Render the div with data stored
		App.control.storeLocalMemory(insertData.id ,'up');
		App.view.$votedBlock();		// Greenify o redify
		App.view.$modalToggle();												// Close the modal
		App.view.$textReset();													// Reset the value
	});
};

App.control.postVote = function(obj, UpDown) {
	var xhr;

	// Preliminar check

	if (App.control.storeLocalMemory(obj.id ,UpDown)) {
		xhr = $.ajax({
			method: "POST",
	  		url: "inc/post_votes.php",
	  		data: obj,
	  		dataType: "JSON"
	  		//context: document.body
		});

		xhr.done(function(data) {
			var updatedData = data;
			console.log(updatedData);
			App.view.$votedBlock();		// Greenify o redify
			App.view.$changeVotes(updatedData.id, updatedData.voti);
			//$('#report-' + updatedData.id).text(updatedData.voti);
		});
	}


}