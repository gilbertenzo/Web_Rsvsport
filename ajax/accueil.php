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
    $today = strtotime("now");
    
    ?>

    <div class="container">
      <div class="row">
        <div class="col-md-9">
          <h3 class="pb-4 mb-4 fst-italic border-bottom">
            <img src="./assets/_img/rsv.png" class="rounded-circle img-cover card-img-top" style="width: 8%;"> Mes reservations
          </h3>

          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="venir-tab" data-bs-toggle="tab" data-bs-target="#venir" type="button"
                role="tab" aria-controls="venir" aria-selected="true">A venir</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab"
                aria-controls="past" aria-selected="false">Passé</button>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="venir" role="tabpanel" aria-labelledby="venir-tab">

              <table width="100%" style="margin:30px 0px 30px 0px;font-size: 15px;">


                <?php
                foreach ($_SESSION['event'] as $row) {

                  $dateck = strtotime(str_replace("h", ":", $row['date_start']));
                  
                  if ($_SESSION['user']['id'] == $row['id_user']) {

                    if ($dateck >= $today) {
                      
                      echo '<tr>
                      <td width="20%"><img src="./assets/_img/' . $row['type_stade'] . '.png" class="rounded-circle img-cover card-img-top" style="width: 30%;"> ' . $row['type_stade'] . '</td>
                      <td width="20%">' . $row['nom_stade'] . '</td>
                      <td width="26%">' . $row['date_start'] . '</td>
                      <td width="26%">' . $row['date_end'] . '</td>
                      <td width="8%" style="text-align:right"> <a data-bs-toggle="modal" data-bs-target="#delrsv" data-idevent="' . $row['id_event'] . '" class="btn btn-outline-danger bi bi-x" title="Annuler la reservation"></a></td>
                      </tr>';
                    }
                  }
                }
                ?>

              </table>

            </div>
            <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">

              <table width="100%" style="margin:30px 0px 30px 0px">


                <?php
                foreach ($_SESSION['event'] as $row) {

                  $dateck = strtotime(str_replace("h", ":", $row['date_start']));
                  if ($_SESSION['user']['id'] == $row['id_user']) {

                    if ($dateck < $today) {
                      echo '<tr>
  				<td width="20%"><img src="./assets/_img/' . $row['type_stade'] . '.png" class="rounded-circle img-cover card-img-top" style="width: 30%;"> ' . $row['type_stade'] . '</td>
  				<td>' . $row['nom_stade'] . '</td>
  				<td>' . $row['date_start'] . '</td>
  				<td>' . $row['date_end'] . '</td>
  			</tr>';

                    }
                  }
                }
                ?>

              </table>


            </div>
          </div>


        </div>

        <div class="col-md-3">
          <div class="position-sticky" style="top: 11rem;">
            <div class="p-4 mb-3 bg-light rounded">
              <h4 class="fst-italic">Sports disponible</h4>
              <i> Tennis |</i>
              <i> Rugby |</i>
              <i> Football</i>
            </div>

            <div class="p-4 bg-light">
              <img src="./assets/_img/img_multisport_bassy.png" style="width: 100%;" alt="img">
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="delrsv" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Confirmer la suppression de la reservation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <input type="text" class="form-control hidden" id="idevent" disabled>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-danger" id="subdelevent">Confirmer</button>
          </div>
        </div>
      </div>
    </div>

    <script>

      $(document).ready(function () {
      
        // Scrool to top on page load
       	$('html, body').animate({
		scrollTop: '0px'
        	},
        1500);

        $('body').removeClass("modal-open");
        $('.modal-backdrop').remove();

        $('#delrsv').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget)
          $(this).find('.modal-header #idevent').val(button.data('idevent'))
        });

        $("#subdelevent").on('click', function (e) {
          e.preventDefault();
          $.ajax({
            url: "include/function.php",
            type: "POST",
            async: true,
            data: { action: "delevent", idevent: $("#idevent").val() },
            dataType: "json",
            success: function (response) {
              switch (response.data) {
                case 'ok':
                  $.toast({
                    heading: "Annulation",
                    text: 'Réussit.',
                    showHideTransition: 'slide',
                    position: 'bottom-center',
                    icon: 'success'
                  })
                  break;

                default:
                  $.toast({
                    heading: 'Warning',
                    text: 'Echec annulation !',
                    showHideTransition: 'plain',
                    position: 'bottom-center',
                    icon: 'warning'
                  })
              }
            },
          });
          setTimeout(function () {
            $('#ajax-content').load('ajax/accueil.php');
          }, 2000);
        });

      });
    </script>


  <?php


  }
  
} else {
  header('Location: ../index.php');
}

?>
