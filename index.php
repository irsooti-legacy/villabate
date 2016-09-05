<!DOCTYPE html>

<?php session_start() ?>
<html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Villabate Segnala</title>
	<meta name="description" content="Villabate Segnala è un progetto che spinge la comunità a registrare le irregolarità e segnalarle, al fine di aiutare le autorità competenti a trovare una soluzione">
	<meta name="author" content="Daniele Irsuti">
	<link rel="icon" href="./favicon.png">
	<meta property="og:title" content="Villabate segnala">
	<meta property="og:site_name" content="Villabate segnala">
	<meta property="og:type" content="website">
	<meta property="og:url" content="http://pc.danieleirsuti.com/villabate/">
	<meta property="og:image" content="http://pc.danieleirsuti.com/villabate/img/villabate.png">
    <link rel="stylesheet" type="text/css" href="./bower_components/bootstrap/dist/css/bootstrap.min.css">
	<style>
	@import 'https://fonts.googleapis.com/css?family=Open+Sans';
	body {
		font-family: 'Open Sans', sans-serif;
		overflow: hidden;
	}
	header {
		//background: #f75357;
		//color: #FFF;
	}

	#logo {
		width: 100%;
		padding-top: 15px;
		padding-bottom: 15px;
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
	.red .fa-chevron-circle-down {
		color: #d80027;
	}

	.green .fa-chevron-circle-up {
		color: #b6db8a;
	}

	.votes, .fa-chevron-circle-down, .fa-chevron-circle-up {
		padding-left: 10px;
	}

	#scrollDown {
	    padding: 5px;
		position: absolute;
		z-index: 100;
		bottom: 0;
		left: 0;
		width: 100%;
		background: #337ab7;
		display: none;
	}

	#scrollTop {
	    padding: 5px;
		background: #337ab7;
		display: none;
	}
	#scrollDown a, #scrollTop a {
		padding: 0.5em;
		font-size: 2em;
		color: #FFF;
		font-weight: 300;
		text-decoration: none;
	}

	#userBar {
		position: absolute;
		top: 10px;
		right: 15px;
		z-index: 100;
	}

	#userBar .dropdown-menu {
		left: unset;
		right: 0;
	}

	@media screen and (max-width: 990px) {
		#scrollDown, #scrollTop {
			display: block;
		}
	}

	/* GMAP */

	.controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
      	left: 0 !important;
      	top: 40px !important;
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 10px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 300px;
        max-width: 100%;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }
      #target {
        width: 345px;
      }
    </style>
    <link rel="stylesheet" type="text/css" href="./bower_components/font-awesome/css/font-awesome.min.css">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
    	var user = <?php if(isset($_SESSION['user'])!="" )
    		echo json_encode([['name' => $_SESSION['name'], 'id' => $_SESSION['user']]]);
    		else echo '[]' ?>
    </script>
</head>
<body>
<div class="container-fluid">

	<div class="row">

<div id="userBar" class="btn-group">
  
  <button type="button" class="btn btn-primary " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span class="caret"></span>
    <span class="sr-only">Toggle Dropdown</span>
  </button>
  <ul class="dropdown-menu">
    <li><a id="registrati" href="#">Registrati</a></li>
    <li><a id="loginModal" href="#">Accedi</a></li>
  </ul>
  <button type="button" class="btn btn-primary dropdown-toggle">
  <span id="userLogged">Ospite</span>
  <span> <i class="fa fa-user" aria-hidden="true"></i></span></button>
</div>
		<aside class="col-lg-9 col-md-push-3">
		<input id="pac-input" class="controls" type="text" placeholder="Cerca la via...">
			<div class="row" id="map"></div>
			<div id="scrollDown" class="text-center">
				<a href="#"><i class="fa fa-angle-down" aria-hidden="true"></i> Segnalazioni</a>
			</div>
			<div class="clearfix"></div>
		</aside>
		<div class="first-column col-lg-3 col-md-pull-9">

			<div id="scrollTop" class="text-center row">
				<a href="#"><i class="fa fa-angle-up" aria-hidden="true"></i> Mappa</a>
			</div>
			<header>
				<div class="row">
					<div class="col-sm-12">
						<img id="logo" alt="Villabate segnala! | Segnala le irregolarità, contribuisci a rendere pulita la tua città!" 
						src="./img/logo.png"
						srcset="./img/logo.svg">
					</div>
				</div>
			</header>
			<div class="row" style="color: #FFF; background: #d80027; padding-top: 10px">
			<div class="col-sm-12">
			<p>Segnala le irregolarità, contribuisci a rendere pulita la tua città!</p></div>
			</div>
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

<div id="modal-registration">
	<div class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	    <div id="registration-content"></div>
	        <div class="modal-footer">
	      		<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
	        </div>
      </div><!-- /.modal-content -->
	    <!-- Cose -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>
<script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/app.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="inc/request.php"></script>
<script async defer
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDK1BpGwU4duuKhm0tnYqzkzFhNVDjURvg&callback=App.control.render&region=IT&libraries=places">
</script>
</body>
</html> 