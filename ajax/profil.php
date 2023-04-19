<?php

function is_ajax()
{
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {

  session_start();
  require('../include/config.php');
  global $mysqli;

  if ($_SESSION['user']['authentification'] == "1") {

    ?>
    <div class="container">
      <div class="row">
        <div class="col-md-9">
          <h3 class="pb-4 mb-4 fst-italic border-bottom">
            Mon profil
          </h3>

          <article class="profil">

            <div class="mb-3">
              <label for="speudol" class="form-label">Nom</label>
              <input type="email" class="form-control" id="speudol" value="<?php echo $_SESSION['user']['nom']; ?>">
            </div>
            <div class="mb-3">
              <label for="nompl" class="form-label">Prenom</label>
              <input type="text" class="form-control" id="nompl" value="<?php echo $_SESSION['user']['prenom']; ?>">
            </div>
            <div class="mb-3">
              <label for="emaill" class="form-label">Email</label>
              <input type="email" class="form-control" id="emaill" value="<?php echo $_SESSION['user']['email']; ?>">
            </div>
            <div class="mb-3">
              <label for="dsl" class="form-label">Adresse</label>
              <input type="email" class="form-control" id="dsl" value="<?php echo $_SESSION['user']['adresse']; ?>">
            </div>
            <div class="mb-3">
              <label for="dfal" class="form-label">Telephone</label>
              <input type="email" class="form-control" id="dfal" value="<?php echo $_SESSION['user']['tel']; ?>">
            </div>

            <div class="d-grid gap-2 col-6 mx-auto d-flex justify-content-between">
              <button class="btn btn-danger bg-danger" type="button" data-bs-toggle="modal"
                data-bs-target="#reinitpwd">Reinitialiser mot de passe</button>
              <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#majprof">Mettre à
                jour</button>

            </div>
          </article>



        </div>

        <div class="col-md-3">
          <div class="position-sticky" style="top: 11rem;">
            <div class="p-4 mb-3 bg-light rounded">
              <h4 class="fst-italic">Avatar</h4>
              <img src="./assets/_img/img_avatar_bussy.png" class="text-center" style="width: 230px" alt="img">
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="reinitpwd" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Confirmer la réinitialisation de votre mot de passe</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="subreinitpwd">Confirmer</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      $('#info').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        $(this).find('.modal-body #commentcote').val(button.data('infocote'))
      });
      $('#mod').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        $(this).find('.modal-body #subcote').val(button.data('subcote'))
        $(this).find('.modal-body #subid').val(button.data('id'))
        $(this).find('.modal-body #subcommentcote').val(button.data('subinfocote'))
      });
    </script>

    <script>
      $(document).ready(function () {
        $("#subreinitpwd").on('click', function (e) {
          e.preventDefault();
          $.ajax({
            url: "./include/function.php",
            type: "POST",
            async: true,
            data: { action: "subreinitpwd", email: $("#rcemail").val() },
            dataType: "json",
            success: function (response) {
              switch (response.data) {
                case 'ok':
                  $.toast({
                    heading: "Envoye email pour reinitialiser votre mot de passe",
                    text: 'Réussit.',
                    position: 'bottom-center',
                    showHideTransition: 'slide',
                    icon: 'success'
                  })
                  break;

                default:
                  $.toast({
                    heading: 'Envoye email pour reinitialiser votre mot de passe',
                    text: 'Error !',
                    position: 'bottom-center',
                    showHideTransition: 'plain',
                    icon: 'warning'
                  })
              }
            },
          });
        });


      });
    </script>

  <?php

  }
} else {
  header('Location: ../index.php');
}
?>