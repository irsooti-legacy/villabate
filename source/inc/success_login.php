<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><?php echo $htmlResponse ?></h4>
          
        </div>
        <div class="modal-body text-center">
            <h2>
                <?php echo $htmlH2 ?>
            </h2>
        </div>

<script type="text/javascript">

user = <?php if(isset($_SESSION['user'])!="" ) { ?>

$('#registrati, #loginModal').css('display','none');
$('#userBar .dropdown-menu')
    .prepend('<li><a id="logout" href="#">Logout</a></li>');
$('#logout').click(function(event) {
    console.log('bau');
    App.control.logout();
    event.preventDefault();
});
$('#userLogged').text('<?php echo $_SESSION['name'] ?>');

<?php 
echo json_encode([['name' => $_SESSION['name'], 'id' => $_SESSION['user']]]).";\r\n";

}
else echo "[];\r\n" ?>
<?php require_once('new_user_votes.php') ?>;

App.view.$votedBlock();
</script>