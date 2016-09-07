<?php
 ob_start();
 session_start();

 require_once 'connection.php';
 error_reporting( ~E_DEPRECATED & ~E_NOTICE );
 
 // it will never let you open index(login) page if session is set
 if ( isset($_SESSION['user'])!="" ) {
  exit();
 }
 
 $error = false;
 
 if( isset($_POST['btn-login']) ) { 
  
  // prevent sql injections/ clear user invalid inputs
  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);
  
  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);
  // prevent sql injections / clear user invalid inputs
  
  if(empty($email)){
   $error = true;
   $emailError = "Inserisci il tuo indirizzo email.";
  } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
   $error = true;
   $emailError = "Per favore inserisci un indirizzo email corretto.";
  }
  
  if(empty($pass)){
   $error = true;
   $passError = "Inserisci la tua password.";
  }
  
  // if there's no error, continue to login
  if (!$error) {
   
   $password = hash('sha256', $pass); // password hashing using SHA256
  
   $res = $conn->query("SELECT id, nome, pass, ban FROM users WHERE email='$email'") or trigger_error($conn->error);

   $row = $res->fetch_array();

   $count = $res->num_rows; // if uname/pass correct it returns must be 1 row
   
   if( $count == 1 && $row['pass']==$password ) {

    if ($row['ban']) {

      $htmlResponse = "Il tuo account è stato sospeso";
      $htmlH2 = "Le tue segnalazioni sono temporaneamente sospese.";
      require_once('success_login.php');

      
      exit();
    }

    else {
        $_SESSION['user'] = $row['id'];
        $_SESSION['name'] = $row['nome'];

        $htmlResponse = '<h4 class="modal-title">Login avvenuto con successo!</h4>';
        $htmlH2 = "Ciao ".$_SESSION["name"]."!";
        require_once('success_login.php');
        exit();
      }
   } else {
    $errMSG = "Dati errati, riprova...";
   }
    
  }
  
 }


?>

 <div id="login-form">
    <form id="userLogin" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Accesso</h4>
        </div>
        <div class="modal-body">
         <div class="form-group">
            </div>
            
            <?php
   if ( isset($errMSG) ) {
    
    ?>
    <div class="form-group">
             <div class="alert alert-danger">
    <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
             </div>
                <?php
   }
   ?>
            
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
             <input id="lMail" type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $email; ?>" maxlength="40" />
                </div>
                <span class="text-danger"><?php echo $emailError; ?></span>
            </div>
            
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
             <input id="lPass" type="password" name="pass" class="form-control" placeholder="Password" maxlength="15" />
                </div>
                <span class="text-danger"><?php echo $passError; ?></span>
            </div>
            
            <div class="form-group">
             <hr />
            </div>
            
            <div class="form-group">
             <button type="submit" class="btn btn-block btn-primary" name="btn-login">Accedi</button>
            </div>
            
            <div class="form-group">
             <hr />
            </div>
            
            <div class="form-group">
             <a class="registerModal" href="register.php">Registrati...</a>
            </div>
        
    
   
    </form>
    </div> 
    </div>

<?php ob_end_flush(); ?>