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

	$sqlcktoken = "select * from users where token = '$token' and email = '$idrecup' ";

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
                        <input type="password" id="password" class="form-control fadeIn second" placeholder="password" required>
                      </div>

                      <div class="form-outline mb-2">
                        <input type="password" id="confirm_password" class="form-control fadeIn third" placeholder="confirmation password" required>
                      </div>

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

			$.validator.addMethod("strongePassword", function(value) {
				return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) && /[a-z]/.test(value) && /\d/.test(value) && /[A-Z]/.test(value);
			}, "Le mot de passe doit contenir au moins 1 nombre, 1 majuscule et 1 misnucule");


			$("#formresetpass").validate({
				rules: {
					password: {
						required: true,
						minlength: 8,
						maxlength: 20,
						strongePassword: true,
					},
					confirm_password: {
						required: true,
						equalTo: "#password",
					},
				},
				messages: {
					password: {
						required: "Merci d'enter un mot de passe",
						minlength: "Le mot de passe doit comporter au minimum 8 caractères",
					},
					confirm_password: {
						required: "Merci d'enter un mot de passe",
						equalTo: "Les mot de passe ne sont pas identique",
					},
				},
				highlight: function(element) {
					$('#resetpasswd').addClass("disabled");
				},
				unhighlight: function(element) {
					$('#resetpasswd').removeClass("disabled");
				},
			});

			$("#resetpasswd").on('click', function() {
				$.ajax({
					url: "./include/function.php",
					type: "POST",
					async: true,
					data: {
						action: "srpasswd",
						password: $("#password").val(),
						confirmpassword: $("#confirm_password").val(),
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
					$('#ajax-content').load('ajax/Accueil.php')
				}, 6000);
			});

		});
	</script>

<?php
} else {
	header('Location: ./index.php');
}
?>