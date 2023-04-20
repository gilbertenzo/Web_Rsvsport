<?php

setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');
date_default_timezone_set("Europe/Paris");

function is_ajax()
{
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {

  session_start();
  require('../include/config.php');
  global $mysqli;

  if ($_SESSION['user']['authentification'] == "1") {

    $today = date("Y-m-d");
    $todays = strtotime("now");  
    $sportname = $_GET['sport'];

    $sqllastdate = "select dateend from stade s INNER JOIN stadetype t on s.id_type_stade = t.id_type_stade where nom_type = '$sportname' order by dateend desc limit 1";
    $reqsqllastdate = $mysqli->query($sqllastdate) or die('Erreur ' . $sqllastdate . ' ' . $mysqli->error);
    $lastdateend = $reqsqllastdate->fetch_assoc();
    $Dateselect = getDatesFromRange($lastdateend['dateend']);

    ?>


    <div class="container">
      <div class="row">
        <div class="col-md-8">
          <h3 class="pb-4 mb-4 fst-italic border-bottom">
            <img src="./assets/_img/img_<?php echo $sportname; ?>_bassy.png" style="width: 50px;" alt="img">
            Reservation
            <?php echo $sportname; ?>
          </h3>

          <article class="blog-post">

            <div class="row pb-5 mb-4" id="titlenba">
              <div class="col-12 col-md-6 justify-content-start">
                <h2 class="blog-post-title">
                  <h2>
                    <p class="blog-post-meta"></p>
              </div>

              <div class="col-12 col-md-6 justify-content-end text-center">

                <i id="prev" class="btn btn-outline-primary bi-chevron-double-left"></i>

                <select id="seldate" class="btn btn-outline-primary">
                  <?php

                  foreach ($Dateselect as $row) {

                    if ($row == $today) {
                      echo '<option value="' . $row . '" selected>' . strftime("%A %d %B %Y", strtotime($row)) . '</option>';
                    } else {
                      echo '<option value="' . $row . '">' . strftime("%A %d %B %Y", strtotime($row)) . '</option>';
                    }
                  }

                  ?>
                </select>

                <i id="next" class="btn btn-outline-primary bi-chevron-double-right"></i>

              </div>
            </div>


            <table id="calnba" class="hidden" data-classes="table table-hover table-sm" style="font-size: 15px;">

              <thead>
                <tr>
                  <th data-field="stade" data-align="left">Stade</th>
                  <th data-field="creneau" data-align="left">Créneau</th>
                  <th data-field="date" data-visible="false" data-align="center" style="display:none;">Date</th>
                  <th data-field="rsv" data-align="center" class="noborder"></th>
                </tr>
              </thead>
              <tbody>

                <?php

                // boucles sur toutes les dates
                foreach ($Dateselect as $row) {

                  // boucle sur tous les stades
                  foreach ($_SESSION['stade'] as $crow) {

                    if ($crow['type'] == "$sportname") {

                      // recupere le jour de la semaine de la date
                      $dateofjour = strftime("%A", strtotime("$row"));

                      // si il existe des creneau pour ce jour de la semaine
                      if (!empty($crow["$dateofjour"])) {

                        $listcreneau = explode(',', $crow["$dateofjour"]);

                        foreach ($listcreneau as $frow) {

                          $cre = explode('-', $frow);
                          echo '<tr>';

                          echo '<td>' . $crow['nom'] . '</td>';
                          echo '<td>' . $frow . '</td>';
                          echo '<td>' . $row . '</td>';

                          // Contruction d'une variable id_user nom_stade date_start date_end
                          $ckdateeventthis = $_SESSION['user']['id'] . ' ' . $crow['nom'] . ' ' . $row . ' ' . $cre[0] . ' ' . $row . ' ' . $cre[1];


                          // Contruction d'une variable nom_stade date_start date_end
                          $ckdateevent = $crow['nom'] . ' ' . $row . ' ' . $cre[0] . ' ' . $row . ' ' . $cre[1];

                          // Contruction d'une variable id_user date_start date_end
                          $ckidevent = $_SESSION['user']['id'] . ' ' . $row . ' ' . $cre[0] . ' ' . $row . ' ' . $cre[1];
			
			 
			  // check if datetime depassé 
			  $dateck = strtotime(str_replace("h", ":", "$row $cre[0]"));
			  if ($dateck >= $todays) {
			
                            // check if reservation est deja presente et appartient à l'utilisateur
                            if (in_array($ckdateeventthis, $_SESSION['ckideventthis'])) {
                              echo '<td> <a data-bs-toggle="modal" data-bs-target="#delrsv" data-dstart="' . $row . ' ' . $cre[0] . '"  data-dend="' . $row . ' ' . $cre[1] . '" data-nstade="' . $crow['nom'] . '" class="btn btn-outline-danger bi bi-x" title="Annuler la reservation"></a></td>';
                              // check if reservation deja presente						
                              
                            } elseif (in_array($ckdateevent, $_SESSION['ckevent'])) {
                              echo '<td></td>';
                            } else {

                              // check if reservation meme date+horaire de l'utilisateur sur un autre stade
                              if (in_array($ckidevent, $_SESSION['ckidevent'])) {

                                echo '<td> <a data-bs-toggle="modal"  data-dstart="' . $row . ' ' . $cre[0] . '"  data-dend="' . $row . ' ' . $cre[1] . '" data-nstade="' . $crow['nom'] . '"  data-warn="/!\ Attention vous avez déjà réalisé une réservation pour ce crénau" title="Attention vous avez déjà réalisé une réservation pour ce crénau" data-bs-target="#mod" class="btn btn-outline-warning bi-exclamation-triangle"></a></td>';

                              } else {
                                echo '<td> <a data-bs-toggle="modal" title="reserver" data-dstart="' . $row . ' ' . $cre[0] . '"  data-dend="' . $row . ' ' . $cre[1] . '" data-nstade="' . $crow['nom'] . '"  data-bs-target="#mod" class="btn btn-outline-success bi-pencil"></a></td>';
                              }
                            }

                            echo '</tr>';
                            
                          } else {
                            echo '<td><a class="btn btn-outline-dark bi-hourglass-bottom" title="La date de début de réservation est dépassée"></a></td>';
                          
                          }

                        }

                      }
                    }

                  }

                }
                ?>
              </tbody>
            </table>

            <br><br>

          </article>



        </div>

        <div class="col-md-4">
          <div class="position-sticky" style="top: 11rem;">
            <div class="p-4 mb-3 bg-light rounded">
              <h4 class="fst-italic">Liste des stades disponibles</h4>
              <?php

              foreach ($_SESSION['stade'] as $crow) {

                if ($crow['type'] == "$sportname") {

                  echo '<img src="./assets/_img/' . $sportname . '.png" class="rounded-circle img-cover card-img-top" style="width: 8%;"><a class="mb-0" href="#"> ' . $crow['nom'] . '</a><br>';

                }
              }

              ?>
            </div>

            <div class="p-4">
              <h4 class="fst-italic"><img src="./assets/_img/rsv.png" class="rounded-circle img-cover card-img-top" style="width: 15%;"> Calendrier des réservations</h4>
              <ol class="list-unstyled mb-0">
                <?php
                foreach ($_SESSION['event'] as $row) {

                  $dateck = explode(' ', $row['date_start']);
                  $endcr = explode(' ', $row['date_end']);
                  if ($_SESSION['user']['id'] == $row['id_user']) {

                    if ($dateck[0] >= $today && $row['type_stade'] == $sportname) {
                      echo '<b>- ' . $row['nom_stade'] . ' : </b>' . $row['date_start'] . ' - ' . $endcr[1] . '<br>';

                    }
                  }
                }
                ?>

              </ol>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="mod" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Confirmation la réservation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="text" class="form-control inputnob" id="warn" disabled>
            <div class="mb-3 d-flex">
              <label for="recipient-name" class="col-form-label">Reservation:</label>
              <input type="text" class="form-control ms-4" style="width:20%" id="stadename" disabled>
              <input type="text" class="form-control ms-4" style="width:20%" id="datestart" disabled>
              <input type="text" class="form-control ms-4" style="width:20%" id="dateend" disabled>
              
            </div>
            <div class="mb-3">
              <label for="message-text" class="col-form-label">Ajoutez un commentaire :</label>
              <textarea class="form-control" id="comment"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="subevent">valider</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="delrsv" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Confirmer la suppression de la réservation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <input type="text" class="form-control hidden" id="ddatestart" disabled>
            <input type="text" class="form-control hidden" id="ddateend" disabled>
            <input type="text" class="form-control hidden" id="dstadename" disabled>
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

        $("#next").click(function () {
          valren = $("#seldate > option:selected").next().val();
          $("#seldate").val(valren);
          $('#calnba').bootstrapTable('filterBy', {
            date: valren
          });

        });

        $("#prev").click(function () {
          valrep = $("#seldate > option:selected").prev().val();
          $("#seldate").val(valrep);
          $('#calnba').bootstrapTable('filterBy', {
            date: valrep
          });

        });

        $("#seldate").change(function () {
          valsel = $("#seldate > option:selected").val();
          $("#seldate").val(valsel);
          $('#calnba').bootstrapTable('filterBy', {
            date: valsel
          });

        });
        

        $('#mod').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget)
          $(this).find('.modal-body #datestart').val(button.data('dstart'))
          $(this).find('.modal-body #dateend').val(button.data('dend'))
          $(this).find('.modal-body #stadename').val(button.data('nstade'))
          $(this).find('.modal-body #warn').val(button.data('warn'))
          if ($('#warn').val()) {
      	    $("#subevent").removeClass("btn-primary").addClass("btn-warning");
      	  } else {
      	    $("#subevent").removeClass("btn-warning").addClass("btn-primary");
      	  }
        });

        $('#delrsv').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget)
          $(this).find('.modal-header #ddatestart').val(button.data('dstart'))
          $(this).find('.modal-header #ddateend').val(button.data('dend'))
          $(this).find('.modal-header #dstadename').val(button.data('nstade'))
        });

        $('#calnba').bootstrapTable();
        $('#calnba').bootstrapTable('showLoading');
        setTimeout(function () {
          $('#calnba').bootstrapTable('hideLoading');
          $('#titlenba').removeClass('pb-5');
          $('#calnba').removeClass('hidden');
        }, 1000);

        $('#calnba').bootstrapTable('filterBy', {
          date: '<?php echo $today; ?>'
        });

        $("#subevent").on('click', function (e) {
          e.preventDefault();
          $.ajax({
            url: "include/function.php",
            type: "POST",
            async: true,
            data: { action: "insertevent", typestade: "<?php echo $sportname; ?>", datestart: $("#datestart").val(), dateend: $("#dateend").val(), stadename: $("#stadename").val(), comment: $("#comment").val() },
            dataType: "json",
            success: function (response) {
              switch (response.data) {
                case 'ok':
                  $.toast({
                    heading: "Reservation",
                    text: 'Réussit.',
                    showHideTransition: 'slide',
                    position: 'bottom-center',
                    icon: 'success'
                  })
                  break;

                default:
                  $.toast({
                    heading: 'Warning',
                    text: 'Echec inattendu !',
                    showHideTransition: 'plain',
                    position: 'bottom-center',
                    icon: 'warning'
                  })
              }
            },
          });
          setTimeout(function () {
            $('#ajax-content').load('ajax/reservation.php?sport=<?php echo $sportname; ?>');
          }, 2000);
        });

        $("#subdelevent").on('click', function (e) {
          e.preventDefault();
          $.ajax({
            url: "include/function.php",
            type: "POST",
            async: true,
            data: { action: "ddelevent", ddatestart: $("#ddatestart").val(), ddateend: $("#ddateend").val(), dstadename: $("#dstadename").val() },
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
            $('#ajax-content').load('ajax/reservation.php?sport=<?php echo $sportname; ?>');
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
