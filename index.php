<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Villabate Segnala</title>
	<style>
	  body {
	  	font-family: 'Open Sans', sans-serif;
	  	overflow: hidden;
	  }
	  header {
	  	background: #f75357;
	  	color: #FFF;
	  }
      #map {
        height: 100%;
        position: relative;
      }
      #reports p {
  		font-size: 1.3em;
      }

      .first-column {
      	box-shadow: 1px 1px 10px;
      	overflow: auto;
      }

      #helpBlock {
      	display: none;
      }

     .has-error #helpBlock {
     	display: block !important;
     }

     .report {
     	padding-top: 5px;
     	padding-bottom: 5px;
     	border-bottom: solid 1px #CECECE;
     	cursor: pointer;
     }

     .votes, .fa-chevron-circle-down, .fa-chevron-circle-up {
     	padding-left: 10px;
     }



	#scrollDown {
		position: absolute;
		z-index: 100;
		bottom: 0;
		left: 0;
		width: 100%;
		background: #5e97f6;
		display: none;
	}

	#scrollTop {
		background: #5e97f6;
		display: none;
	}
	#scrollDown a, #scrollTop a {
		padding: 0.5em;
		font-size: 2em;
		color: #FFF;
		font-weight: 300;
		text-decoration: none;
	}

	@media screen and (max-width: 990px) {
		#scrollDown, #scrollTop {
			display: block;
		}
	}
    </style>
    <link rel="stylesheet" type="text/css" href="./bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./bower_components/font-awesome/css/font-awesome.min.css">
</head>
<body>
<div class="container-fluid">

	<div class="row">
		<div class="col-lg-9 col-md-push-3">
			<div class="row" id="map"></div>
			<div id="scrollDown" class="text-center">
				<a href="#"><i class="fa fa-angle-down" aria-hidden="true"></i> Segnalazioni</a>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="first-column col-lg-3 col-md-pull-9">

			<div id="scrollTop" class="text-center row">
				<a href="#"><i class="fa fa-angle-up" aria-hidden="true"></i> Mappa</a>
			</div>
			<header class="row">
				<div class="col-sm-12">
					<h1>Villabate segnala!</h1>
					<p>Segnala le irregolarità, contribuisci a rendere pulita la tua città!</p>
				</div>
			</header>
			<div class="row" style="padding-top: 2em">
				<div class="col-sm-12" id="reports"></div>
			</div>
		</div>
	</div>	
</div>
<div id="modal">
	<div class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Cosa succede qui?</h4>
	      </div>
	      <div class="modal-body">
	        <p id="maybeSuccess" class="form-group">
	        	<input type="text" id="content" class="form-control">
	        	<span id="helpBlock" class="help-block">Sii più specifico, assegna un breve titolo alla zona che stai segnalando</span>
        	</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
	        <button type="button" type="submit" id="closeModal" onclick="App.control.saveMarker(event)" class="btn btn-primary">Segnala!</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>
<script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/app.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="inc/request.php"></script>
<script async defer
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDK1BpGwU4duuKhm0tnYqzkzFhNVDjURvg&callback=App.control.render&region=IT">
</script>
</body>
</html> 