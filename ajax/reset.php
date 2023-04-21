<?php

function is_ajax()
{
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {

	session_start();

	require('../include/config.php');

	global $mysqli;

	$token   = $_GET['token'];
	$idrecup = $_GET['idrecup'];

	// Le token n'est valide qu'une heure pour reseter son mot de passe !!!
	$sqlcktoken = "select * from users where token = '$token' and email = '$idrecup' and actif = '1' and timestamp <= NOW() - INTERVAL 1 HOUR";

	// si le token n'existe pas on retourne la requete vers la page d'accueil
	if (!$cfg = $mysqli->query($sqlcktoken)) {
?>
		<script>
			$(document).ready(function() {
				window.location.href = 'index.php';
			});
		</script>

	<?php

	} else {
		$cfg = $cfg->fetch_assoc();

		// Si il n'y a pas d'id_user on retourne la requete vers la page d'accueil
		if (empty($cfg['id_user'])) {
	?>
			<script>
				$(document).ready(function() {
					window.location.href = 'index.php';
				});
			</script>
	<?php
		}
	}

	?>

<section class="gradient-form" style="background-color: #eee;">
      <div class="container py-5">
        <div class="row d-flex justify-content-center align-items-center ">
          <div class="col-xl-6">
            <div class="card rounded-3 text-black">
              <div class="row g-0">
                <div class="col-lg-12">
                  <div class="card-body p-md-5 mx-md-4">

                    <div class="text-center">
                      <img src="./assets/_img/logo_connexion_bussy.png" style="width: 100px;" alt="logo">
                    </div>

                    <form id="formresetpass">
                      <p>Initialisation / Reinitialisation de votre mot de passe.</p>

                      <div class="form-outline mb-2">
                        <input type="password" id="Password" class="form-control fadeIn second" placeholder="password" required>
                      </div>
                      <div id="password-strength-status" class="pb-2"></div>

                      <div class="form-outline mb-2 pb-2">
                        <input type="password" id="ConfirmPassword" class="form-control fadeIn third disabled" disabled placeholder="confirmation password" required>
                      </div>
                      <div style="margin-top: 7px;" id="CheckPasswordMatch" class="pb-1"></div>

                      <div class="text-center pt-1 mb-3 pb-1">
                        <button class="btn btn-primary btn-rses disabled mb-3" type="button" id="resetpasswd">Envoyer</button>
                      </div>

                    </form>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
	</section>

	<script>
		$(document).ready(function() {
		
		
			$("#Password").on('keyup', function(){
    var number = /([0-9])/;
    var alphabets = /([a-zA-Z])/;
    var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
    if ($('#Password').val().length < 6) {
        $('#password-strength-status').removeClass();
        $('#password-strength-status').addClass('weak-password');
        $('#password-strength-status').html("Weak (should be atleast 6 characters.)");
        $('#ConfirmPassword').prop('disabled', true);
        $('#ConfirmPassword').addClass("disabled");
        $('#resetpasswd').addClass("disabled");
    } else {
        if ($('#Password').val().match(number) && $('#Password').val().match(alphabets) && $('#Password').val().match(special_characters)) {
            $('#password-strength-status').removeClass();
            $('#password-strength-status').addClass('strong-password');
            $('#password-strength-status').html("Strong");
            $('#ConfirmPassword').removeAttr("disabled");
            $('#ConfirmPassword').removeClass("disabled");
        } else {
            $('#password-strength-status').removeClass();
            $('#password-strength-status').addClass('medium-password');
            $('#password-strength-status').html("Medium (should include alphabets, numbers and special characters or some combination.)");
            $('#ConfirmPassword').prop('disabled', true);
            $('#ConfirmPassword').addClass("disabled");
            $('#resetpasswd').addClass("disabled");
        }
    }
  });
  
  
		
		        $("#ConfirmPassword").on('keyup', function(){
    				var password = $("#Password").val();
    				var confirmPassword = $("#ConfirmPassword").val();
    				if (password != confirmPassword) {
    				    $("#CheckPasswordMatch").html("Les deux mots de passe ne sont pas identique !").css("color","red");
    				    $('#resetpasswd').addClass("disabled");
    				} else {
    				    $("#CheckPasswordMatch").html("Les deux mots de passe sont identique !").css("color","green");
    				    $('#resetpasswd').removeClass("disabled");
    				}
   			});
   
   
   

			

			$("#resetpasswd").on('click', function() {
				$.ajax({
					url: "./include/function.php",
					type: "POST",
					async: true,
					data: {
						action: "srpasswd",
						password: $("#Password").val(),
						confirmpassword: $("#ConfirmPassword").val(),
						token: <?php echo "'" . $token . "'"; ?>,
						idrecup: <?php echo "'" . $idrecup . "'"; ?>
					},
					dataType: "json",
					success: function(response) {
						switch (response.data) {
							case 'ok':
								$.toast({
									heading: "Initialisation mot de passe",
									text: 'Réussit.',
									showHideTransition: 'slide',
									position: 'bottom-center',
									icon: 'success'
								})
								break;

							default:
								$.toast({
									heading: 'Initialisation mot de passe',
									text: 'Error !',
									showHideTransition: 'plain',
									position: 'bottom-center',
									icon: 'warning'
								})
						}
					},
				});
				setTimeout(function() {
					$('#ajax-content').load('ajax/accueil.php')
				}, 3000);
			});

		});
	</script>

<?php
} else {
	header('Location: ./index.php');
}
?>
