<?php

  ob_start();

  //print_r($_POST);
  error_reporting( ~E_DEPRECATED & ~E_NOTICE );
  session_start();

  if(isset($_SESSION['user'])!="" ){
    require_once('login.php');
    exit();
  }

  require_once("connection.php");

  $error = false;

 if ( isset($_POST['btn-signup']) ) {
  
  // clean user inputs to prevent sql injections
  $name = trim($_POST['name']);
  $name = strip_tags($name);
  $name = htmlspecialchars($name);
  
  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);
  
  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);
  
  // basic name validation
  if (empty($name)) {
    $error = true;
    $nameError = "Per favore, inserisci il tuo vero nome.";
  } 

  else if (strlen($name) < 3) {
   
    $error = true;
    $nameError = "Il nome deve contenere almeno 3 caratteri.";
  } 

  else if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
    $error = true;
    $nameError = "Il nome può contenere solo lettere e spazi.";
  }
  
  //basic email validation
  if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
    $error = true;
    $emailError = "Per favore inserisci un indirizzo email valido.";
  } else {
    // check email exist or not
    $query = "SELECT email FROM users WHERE email='$email'";
    $result = $conn->query($query);
    $count = $result->num_rows;
  
  if($count!=0) {
    $error = true;
    $emailError = "Questo indirizzo è già registrato.";
   }
  }
  // password validation
  if (empty($pass)){
   $error = true;
   $passError = "Inserisci una password.";
  } else if(strlen($pass) < 6) {
   $error = true;
   $passError = "La password deve contenere almeno 6 caratteri.";
  }
  
  // password encrypt using SHA256();
  $password = hash('sha256', $pass);
  
  // if there's no error, continue to signup
  if( !$error ) {
   
   $query = "INSERT INTO users(nome,email,pass,ban) VALUES('$name','$email','$password',0)";
   $res = $conn->query($query);
    
   if ($res) {
    $errTyp = "success";
    $errMSG = "Registrazione avvenuta con successo! Puoi ora loggarti.";
    unset($name);
    unset($email);
    unset($pass);
    
    require_once('success_registration.php');
    exit();
   } else {
    $errTyp = "danger";
    $errMSG = "Qualcosa è andato storto, riprova più tardi..."; 
   } 
    
  }
 }

?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Registrazione</h4>
        </div>
        <div class="modal-body">
          <form id="submitUser" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  autocomplete="off">
      
       <div class="">
          
           <div class="form-group">
              </div>
              
              <?php
     if ( isset($errMSG) ) {
      
      ?>
      <div class="form-group">
               <div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
      <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                  </div>
               </div>
                  <?php
     }
     ?>
              
              <div class="form-group">
               <div class="input-group">
                  <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
               <input id="rName" type="text" name="name" class="form-control" placeholder="Inserisci nome e cognome" maxlength="50" value="<?php echo $name ?>" />
                  </div>
                  <span class="text-danger"><?php echo $nameError; ?></span>
              </div>
              
              <div class="form-group">
               <div class="input-group">
                  <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
               <input id="rMail" type="email" name="email" class="form-control" placeholder="Inserisci la tua email" maxlength="40" value="<?php echo $email ?>" />
                  </div>
                  <span class="text-danger"><?php echo $emailError; ?></span>
              </div>
              
              <div class="form-group">
               <div class="input-group">
                  <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
               <input id="rPass" type="password" name="pass" class="form-control" placeholder="Scegli una password" maxlength="15" />
                  </div>
                  <span class="text-danger"><?php echo $passError; ?></span>
              </div>
              
              <div class="form-group">
               <hr />
              </div>
              
              <div class="form-group">
               <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Registrami!</button>
              </div>
              
              <div class="form-group">
               <hr />
              </div>
              
              <div class="form-group">
               <a class="loginModal" href="index.php">Login da Qui...</a>
              </div>
          
          </div>
     
      </form>
        </div>






<?php ob_end_flush(); ?>
