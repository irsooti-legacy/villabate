var App = App || {};

App.control.logout = function() {
	var xhr; 

	xhr = $.ajax({
		method: "GET",
  		url: "inc/logout.php",
  		dataType: "JSON"
  		//context: document.body
	});

	xhr.done(function(data) {
		$('#registrati, #loginModal').css('display','block');
		$('#logout').remove();
		$('#userLogged').text('Ospite');
		user_votes = data;
		App.view.$votedBlock();
	});
};
App.control.getRegistrationModule = function() {
	var xhr; 

	xhr = $.ajax({
		method: "GET",
  		url: "inc/register.php",
  		dataType: "html"
  		//context: document.body
	});

	xhr.done(function(data) {
		$('#registration-content').html(data);
		App.view.ajaxListener();
	});
};

App.control.postVoteStatus = function(obj, index) {
	var xhr; 

	xhr = $.ajax({
		method: "POST",
		data: obj,
  		url: "inc/CRUD_votes.php",
  		dataType: "JSON"
  		//context: document.body
	});

	xhr.done(function(data) {
		if (data.response == 'New')
			App.control.saveVoteMemory({ 'id': data.data.id, 'vote': data.data.vote});
		if (data.response == 'Updated')
			App.control.getVoteMemory()[index].vote = data.data.vote;
		App.view.$votedBlock();
	});
};

App.control.getLoginModule = function() {
	var xhr; 

	xhr = $.ajax({
		method: "GET",
  		url: "inc/login.php",
  		dataType: "html"
  		//context: document.body
	});

	xhr.done(function(data) {
		$('#registration-content').html(data);
		App.view.ajaxListener();
	});
};

App.control.postRegistrationModule = function(obj) {
	var xhr; 

	xhr = $.ajax({
		method: "POST",
		data: obj,
  		url: "inc/register.php",
  		dataType: "html"
  		//context: document.body
	});

	xhr.done(function(data) {
		$('#registration-content').html(data);
		App.view.ajaxListener();
	});
};

App.control.postLoginModule = function(obj) {
	var xhr; 

	xhr = $.ajax({
		method: "POST",
		data: obj,
  		url: "inc/login.php",
  		dataType: "html"
  		//context: document.body
	});

	xhr.done(function(data) {
		$('#registration-content').html(data);
		App.view.ajaxListener();
	});
};

App.control.postReport = function(obj) {
	var xhr;

	if(App.view.$registerPlz('Registrati per segnalare!')) return false;
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

		App.view.$createDiv(insertData.title, insertData.lat, insertData.lng, insertData.voti, insertData.id, insertData.reporter); // Render the div with data stored
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