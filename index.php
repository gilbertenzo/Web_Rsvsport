<?php

session_start();

require('./include/config.php');

$token = $_GET['token'];
$idrecup = $_GET['idrecup'];

// si deja connecter tuer la session afin de charger les pages ajax reset password
if (!empty($token) && !empty($idrecup)) {

  session_destroy();

}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

  <title>Reservation Sport</title>

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <meta name="description" content="reservation sport">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="./assets/_img/favo.ico" rel="icon">

  <style type="text/css" title="currentStyle">
    @import "./assets/_vendor/bootstrap-5.0.2/css/bootstrap.min.css";
    @import "./assets/_vendor/bootstrap-icons-1.10.3/bootstrap-icons.css";
    @import "./assets/_vendor/bootstrap-table/bootstrap-table.min.css";
    @import "./assets/_vendor/jquery-toast-plugin/jquery.toast.min.css";
    @import "./assets/_css/googleapi.css";
    @import "./assets/_css/styles.css";
  </style>

</head>

<body>

  <?php if ($_SESSION['user']['authentification'] == "1") { ?>

    <div class="site-mobile-menu site-navbar-target" id="mobile-menu">
      <div class="site-mobile-menu-header ">
        <div class="d-md-none ml-md-0 text-right">
          <i id="close-mobile-list" class="d-block d-sm-none bi-x-lg"></i>
        </div>
      </div>

      <div class="site-mobile-menu-body">
        <ul class="site-nav-wrap">
          <li><a href="accueil.php" class="nav-link nav-link-mobile ajax active">Accueil</a></li>
          <?php
          	foreach ($_SESSION['stadetype'] as $crow) {
          	   if ( $crow['type_actif'] == '1' ) {
                	echo '<li><a href="reservation.php?sport='. $crow['nom_type'] .'" class="nav-link nav-link-mobile ajax text-capitalize">'. $crow['nom_type'] .'</a></li>';
               	   }
                }
          ?>
          <li><a href="profil.php" class="nav-link nav-link-mobile ajax">Profil</a></li>

          <?php if ($_SESSION['user']['authentification'] == "1" && $_SESSION['user']['group'] == "admin") { ?>

            <li><a href="admin.php" class="nav-link nav-link-mobile ajax">Administration</a></li>

          <?php } ?>


          <li><a href="#" data-bs-toggle="modal" data-bs-target="#mlogout" class="nav-link nav-link-mobile">Déconnection</a></li>
        </ul>
      </div>
    </div>

    <div class="site-navbar-wrap">
      <div id="sticky-wrapper" class="sticky-wrapper" style="height: 64.25px;">
        <div class="site-navbar site-navbar-target js-sticky-header">
          <div class="container">
            <div class="row align-items-center d-flex">
              <div class="col-xs-8 col-md-4 d-flex justify-content-start" id="ml">
                <h1 class="site-logo"><a href="./index.php">Reservation Sport</a></h1>
              </div>
              <div class="col-xs-2 col-md-8 d-flex justify-content-end" id="md">
                <nav class="site-navigation text-end" role="navigation">
                  <div class="container">
                    <div class="d-inline-block d-md-none ml-md-0 mr-auto">

                      <i id="mobile-list" class="d-block d-sm-none bi-list" style="font-size: 2rem;color: white;"></i>

                    </div>
                    <ul class="site-menu main-menu js-clone-nav d-none d-lg-block">
                      <li><a href="accueil.php" class="nav-link ajax active">Accueil</a></li>
                      <?php
                      	foreach ($_SESSION['stadetype'] as $crow) {
                      	    if ( $crow['type_actif'] == '1' ) {
                      		echo '<li><a href="reservation.php?sport='. $crow['nom_type'] .'" class="nav-link ajax text-capitalize">'. $crow['nom_type'] .'</a></li>';
                      	    }
                      	}
                      ?>
                      <li><a href="profil.php" class="nav-link ajax">Profil</a></li>

                      <?php if ($_SESSION['user']['authentification'] == "1" && $_SESSION['user']['group'] == "admin") { ?>

                        <li><a href="admin.php" class="nav-link ajax">Administration</a></li>

                      <?php } ?>

                      <li><a href="#" data-bs-toggle="modal" data-bs-target="#mlogout" class="nav-link">Déconnection</a>
                      </li>
                    </ul>
                  </div>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div style="height:85px;background-color:#000"></div>

    <main id="main">
      <div id="ajax-content" class="col-12" style="margin-top: 100px;padding-bottom: 150px;"></div>
    </main>

    <div class="modal fade" id="mlogout" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Déconnection</h5>
          </div>
          <form>
            <div class="modal-footer border-top-0 d-flex justify-content-center">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
              <a class="btn btn-primary" type="button" id="slogout">Déconnecter</a>
            </div>
          </form>
        </div>
      </div>
    </div>

  <?php } elseif (!empty($token) && !empty($idrecup)) { ?>

    <main id="main">
      <div id="ajax-content" class="col-12" style="margin-top: 100px;padding-bottom: 150px;"></div>
    </main>


  <?php } else { ?>

    <section class="gradient-form" style="background-color: #eee;">
      <div class="container py-5">
        <div class="row d-flex justify-content-center align-items-center ">
          <div class="col-xl-10">
            <div class="card rounded-3 text-black">
              <div class="row g-0">
                <div class="col-lg-6">
                  <div class="card-body p-md-5 mx-md-4">

                    <div class="text-center">
                      <img src="./assets/_img/logo_connexion_bussy.png" style="width: 100px;" alt="logo">
                    </div>

                    <form>
                      <p>Veuillez vous connecter à votre compte.</p>

                      <div class="form-outline mb-2">
                        <input type="email" id="login" class="form-control" placeholder="login ou addresse mail"
                          required />
                        <label class="form-label" for="form2Example11"></label>
                      </div>

                      <div class="form-outline mb-2">
                        <input type="password" id="password" class="form-control" placeholder="mot de passe" required>
                        <label class="form-label" for="form2Example22"></label>
                        <input type="piege" id="piege" class="form-control hidden" placeholder="">
                      </div>

                      <div class="text-center pt-1 mb-3 pb-1">
                        <a href="./index.php" type="button" class="btn btn-secondary mb-3" type="button" id="cancel">
                          Annuler </a>
                        <button class="btn btn-primary mb-3" type="button" id="sublogin">Connexion</button>
                      </div>

                      <div class="d-flex align-items-center justify-content-center pb-4">
                        <a href="" class="mb-0 me-2" data-bs-toggle="modal" data-bs-target="#reinitpwd">Mot de passe
                          oublié / Première connexion ?</a>
                      </div>

                    </form>

                  </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                  <div class="text-dark px-3 py-4 p-md-5 mx-md-4">
                    <h4 class="mb-4">Bienvenue sur le site de reservation de stade de la ville de Bussy-Saint-Georges.
                    </h4>
                    <img src="./assets/_img/mairie_connexion_bussy.jpg" style="width: 400px;" alt="mairie">
                    <p class="small mb-0"> Retrouvez toutes les informations, services sportif dont vous avez besoin.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="modal fade" id="reinitpwd" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Mot de passe oublié / Première connexion ?</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body mb-3">
              <label for="emailrecup-name" class="col-form-label">Veuillez indiquer votre adresse mail</label>
              <input type="text" class="form-control" id="emailrecup">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="subreinitpwd">Envoyer</button>
            </div>
          </div>
        </div>
      </div>


    </section>

  <?php } ?>


  <script src="./assets/_vendor/jquery-3.3.1.min.js"></script>
  <script src="./assets/_vendor/popper-2.5.4.min.js"></script>
  <script src="./assets/_vendor/bootstrap-5.0.2/js/bootstrap.bundle.js"></script>
  <script src="./assets/_vendor/bootstrap-table/bootstrap-table.min.js"></script>
  <script src="./assets/_vendor/bootstrap-table/locale/bootstrap-table-fr-FR.min.js"></script>
  <script src="./assets/_vendor/bootstrap-table/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>
  <script src="./assets/_vendor/jquery-toast-plugin/jquery.toast.min.js"></script>
  <script src="./assets/_vendor/cookies.js"></script>

  <?php if ($_SESSION['user']['authentification'] == "1") { ?>



    <!--footer id="main-footer">
      <div id="footer-bottom">
        <div class="container clearfix text-center ">
          <a href="https://www.facebook.com/BussyOfficiel" target="_blank" class="p-2"><i class="bi bi-facebook"></i></a>
          <a href="https://www.instagram.com/villedebussysaintgeorges/" target="_blank" class="p-2"><i class="bi bi-instagram"></i></a>
          <a href="https://twitter.com/VilledeBussy" target="_blank" class="p-2"><i class="bi bi-twitter"></i></a>
          <a href="https://www.youtube.com/channel/UC7lb7nsDjZYh0Cf1F_8nKcg" target="_blank" class="p-2"><i class="bi bi-youtube"></i></a>
          <a href="https://www.linkedin.com/in/mairie-bussy-saint-georges-142652109/" target="_blank" class="p-2"><i class="bi bi-linkedin"></i></a>
          <hr class="bg-white">
          <p class="text-center">Copyright © Bussy-Saint-Georges | Tous droits réservés.</p>
        </div>
      </div>
    </footer-->




  <?php } ?>

  <script>
    $(document).ready(function() {

      $("#mobile-list").click(function() {
      $("#mobile-menu").css('right', '220px');
    });

      $("#close-mobile-list, .nav-link-mobile").click(function() {
      $("#mobile-menu").css('right', '0');
    });
      // Function inclusion des pages web
      <?php if (empty($token) || empty($idrecup)) { ?>
      $('#ajax-content').load('ajax/accueil.php');
      <?php } else { ?>
        $('#ajax-content').load('ajax/reset.php?idrecup=<?php echo $idrecup; ?>&token=<?php echo $token; ?>');
      <?php } ?>

      $('a.ajax').click(function(e) {
        e.preventDefault();
        $("a").removeClass("active");
        $(this).addClass("active");
        var page = $(this).attr('href');
        $('#ajax-content').load('ajax/' + page);
        return false;
      });

      /* enter key */
      $(document).bind('keypress', function(e) {
        if (e.keyCode == 13) {
          $('#sublogin').trigger('click');
        }
      });

      /* Function ajax authentification */

      $("#sublogin").on('click', function(e) {
        e.preventDefault();
        $.ajax({
          url: "./include/function.php",
          type: "POST",
          async: true,
          data: {
            action: "login",
            login: $("#login").val(),
            password: $("#password").val(),
            piege: $("#piege").val()
          },
          dataType: "json",
          success: function(response) {
            switch (response.data) {
              case 'ok':
                $.toast({
                  heading: "Authentification",
                  text: 'Réussit.',
                  position: 'bottom-center',
                  showHideTransition: 'slide',
                  icon: 'success'
                })
                break;

              default:
                $.toast({
                  heading: 'Authentification',
                  text: 'Error !',
                  position: 'bottom-center',
                  showHideTransition: 'plain',
                  icon: 'warning'
                })
            }
          },
        });
        setTimeout(function() {
          location.reload()
        }, 3000);
      });

      $("#slogout").on('click', function(e) {
        console.log('ok');
        e.preventDefault();
        $.ajax({
          url: "./include/function.php",
          type: "POST",
          async: true,
          data: {
            action: "slogout"
          },
          dataType: "json",
          success: function(response) {
            switch (response.data) {
              case 'ok':
                $.toast({
                  heading: "Déconnection",
                  text: 'Réussit.',
                  position: 'bottom-center',
                  showHideTransition: 'slide',
                  icon: 'success'
                })
                break;

              default:
                $.toast({
                  heading: 'Déconnection',
                  text: 'Error !',
                  position: 'bottom-center',
                  showHideTransition: 'plain',
                  icon: 'warning'
                })
            }
          },
        });
        setTimeout(function() {
          location.reload()
        }, 2000);
      });

      $("#subreinitpwd").on('click', function(e) {
        e.preventDefault();
        $.ajax({
          url: "include/function.php",
          type: "POST",
          async: true,
          data: {
            action: "subreinitpwd",
            emailrecup: $("#emailrecup").val()
          },
          dataType: "json",
          success: function(response) {
            switch (response.data) {
              case 'ok':
                $.toast({
                  heading: "Si votre email est connu un lien est envoyé pour reinitialiser votre mot de passe",
                  text: 'Réussit.',
                  position: 'bottom-center',
                  showHideTransition: 'slide',
                  icon: 'info'
                })

            }
          },
        });
        setTimeout(function() {
          location.reload()
        }, 6000);
      });

    $('html, body').animate({
	scrollTop: '0px'
        },
        1500);
     return false;


    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function(event) {
      cookieChoices.showCookieConsentBar('Ce site utilise des cookies pour vous offrir le meilleur service. En poursuivant votre navigation, vous acceptez l’utilisation des cookies.', 'J’accepte', 'En savoir plus', 'https://enzo.dinetcola.fr/mentions-legales/');
    });
  </script>

</body>

</html>
