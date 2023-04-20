<?php

function is_ajax()
{
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {

  session_start();
  require('../include/config.php');
  global $mysqli;

  if ($_SESSION['user']['authentification'] == "1" && $_SESSION['user']['group'] == "admin") {
    
?>


    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h3 class="pb-4 mb-4 fst-italic border-bottom">
            Administration
          </h3>

          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="true">Users</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="stade-tab" data-bs-toggle="tab" data-bs-target="#stade" type="button" role="tab" aria-controls="stade" aria-selected="false">Stades</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="stadet-tab" data-bs-toggle="tab" data-bs-target="#stadet" type="button" role="tab" aria-controls="stadet" aria-selected="false">Stade Type</button>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
              <br>
              <div class="container">
                <div class="row justify-content-end">
                  <div class="col-xs-12 col-md-3 justify-content-end d-flex">
                    <a data-bs-toggle="modal" data-ajaction="adduser" data-action="Ajouter un utilisateur" data-bs-target="#moduser" class="btn btn-outline-success bi-person-fill-add"> Ajouter un utilisateur</a>
                  </div>
                </div>
              </div>
              <br>
              <table id="userstable" class="hidden" data-classes="table table-hover table-sm">
                <thead>
                  <tr>
                    <th class="d-none d-lg-table-cell" data-field="nom" data-align="left">Nom</th>
                    <th class="d-none d-lg-table-cell" data-field="prenom" data-align="left">Prénom</th>
                    <th data-field="email" data-align="center">Email</th>
                    <th class="d-none d-lg-table-cell" data-field="tel" data-align="center">Téléphone</th>
                    <th class="d-none d-lg-table-cell" data-field="addresse" data-align="center">Adresse</th>
                    <th data-field="actif" data-align="center">Status</th>
                    <th data-field="group" data-align="center">Groupes</th>
                    <th data-field="modusers" data-align="center"></th>

                  </tr>
                </thead>
                <tbody>
                  <?php

                  foreach ($_SESSION['users'] as $crow) {
                    if ($crow['actif'] == "1" ) {
                      $statusu = "Actif";
                    } else {
                      $statusu = "Inactif";
                    }
                    if ($crow['groups'] == "admin" ) {
                      $sgroups = "1";
                    } elseif ($crow['groups'] == "user" ){
                      $sgroups = "2";
                    }
                    echo '<tr>
                    
                    <td> ' . $crow['nom'] . ' </td>
                    <td> ' . $crow['prenom'] . ' </td>
                    <td> ' . $crow['email'] . ' </td>
                    <td> ' . $crow['tel'] . ' </td>
                    <td> ' . $crow['adresse'] . ' </td>
                    <td> ' . $statusu . ' </td>
                    <td> ' . $crow['groups'] . ' </td>
                    <td> 
                    	<a title="Modifier" data-bs-toggle="modal"  data-ajaction="modifuser" data-action="Modification utilisateur" data-iduser="' . $crow['id_user'] . '" data-nom="' . $crow['nom'] . '"  data-prenom="' . $crow['prenom'] . '"  data-email="' . $crow['email'] . '" data-tel="' . $crow['tel'] . '" data-addresse="' . $crow['adresse'] . '" data-actif="' . $crow['actif'] . '" data-group="' . $sgroups . '" data-bs-target="#moduser" class="btn btn-outline-info bi-person-fill-gear"></a>';
                    	$ckv = array_search($crow['id_user'], array_column($_SESSION['event'], 'id_user'));
                    	if ( $crow['email'] == $_SESSION['user']['email'] ) {
                    	      echo '<a title="Supprimer" class="btn btn-outline-secondary bi-person-x-fill disabled" disabled></a>'; 
                    	} elseif ( $ckv !== false ) {
                    	      echo '<a title="Impossible de supprimer des réservations existe pour cet utilisateur" class="btn btn-outline-danger bi-lock-fill disabled" disabled></a>'; 
                    	} else {
                    	      echo '<a title="Supprimer" data-bs-toggle="modal"  data-iduser="' . $crow['id_user'] . '"  data-bs-target="#deluser" class="btn btn-outline-danger bi-person-x-fill"></a>'; 
     			}
     			          	 	                  
                    echo '</td>
                    
                    </tr>';
                  }

                  ?>
                </tbody>
              </table>


            </div>
            <div class="tab-pane fade" id="stade" role="tabpanel" aria-labelledby="stade-tab">
              <br>
              <div class="container">
                <div class="row justify-content-end">
                  <div class="col-xs-12 col-md-3 justify-content-end d-flex">
                    <a data-bs-toggle="modal" data-ajaction="addstade" data-action="Ajouter un stade" data-bs-target="#modstade" class="btn btn-outline-success bi-plus-circle-fill"> Ajouter un stade</a>
                  </div>
                </div>
              </div>
              <br>
              <table id="stadetable" class="hidden" data-classes="table table-hover table-sm">
                <thead>
                  <tr>
                  <th data-field="snom" data-align="left">Nom</th>
                    <th data-field="stype" data-align="left">Type</th>
                    <th class="d-none d-lg-table-cell" data-field="clundi" data-align="center">Lundi</th>
                    <th class="d-none d-lg-table-cell" data-field="cmardi" data-align="center">Mardi</th>
                    <th class="d-none d-lg-table-cell" data-field="cmercredi" data-align="center">Mercredi</th>
                    <th class="d-none d-lg-table-cell" data-field="cjeudi" data-align="center">Jeudi</th>
                    <th class="d-none d-lg-table-cell" data-field="cvendredi" data-align="center">Vendredi</th>
                    <th class="d-none d-lg-table-cell" data-field="csamedi" data-align="center">Samedi</th>
                    <th class="d-none d-lg-table-cell" data-field="cdimanche" data-align="center">Dimanche</th>
                    <th data-field="dateend" data-align="center">Date fin</th>
                    <th data-field="sstatus" data-align="center">Status</th>
                    <th data-field="smod" data-align="center"></th>

                  </tr>
                </thead>
                <tbody>
                  <?php

                  foreach ($_SESSION['allstade'] as $crow) {
                    if ($crow['actif'] == "1" ) {
                      $statuss = "Actif";
                    } else {
                      $statuss = "Inactif";
                    }
                    $lundi = substr($crow['lundi'], 0, 10).'...';
                    $mardi = substr($crow['mardi'], 0, 10).'...';
                    $mercredi = substr($crow['mercredi'], 0, 10).'...';
                    $jeudi = substr($crow['jeudi'], 0, 10).'...';
                    $vendredi = substr($crow['vendredi'], 0, 10).'...';
                    $samedi = substr($crow['samedi'], 0, 10).'...';
                    $dimanche = substr($crow['dimanche'], 0, 10).'...';
                    echo '<tr>
                    <td> ' . $crow['nom'] . ' </td>
                    <td> ' . $crow['type'] . ' </td>
                    <td> ' . $lundi   . ' </td>
                    <td> ' . $mardi . ' </td>
                    <td> ' . $mercredi . ' </td>
                    <td> ' . $jeudi . ' </td>
                    <td> ' . $vendredi . ' </td>
                    <td> ' . $samedi . ' </td>
                    <td> ' . $dimanche . ' </td>
                    <td> ' . $crow['dateend'] . ' </td>
                    <td> ' . $statuss . ' </td>
                    <td> 
                    	<a title="Modifier" data-bs-toggle="modal"  data-ajaction="modifstade" data-action="Modification stade" data-idstade="' . $crow['id_stade'] . '" data-type="' . $crow['type'] . '"  data-nom="' . $crow['nom'] . '"  data-lundi="' . $crow['lundi'] . '" data-mardi="' . $crow['mardi'] . '" data-mercredi="' . $crow['mercredi'] . '" data-jeudi="' . $crow['jeudi'] . '" data-vendredi="' . $crow['vendredi'] . '" data-samedi="' . $crow['samedi'] . '" data-dimanche="' . $crow['dimanche'] . '" data-dateend="' . $crow['dateend'] . '" data-actif="' . $crow['actif'] . '"data-bs-target="#modstade" class="btn btn-outline-info bi-gear-fill"></a>';
                    	$ckv = array_search($crow['nom'], array_column($_SESSION['event'], 'nom_stade'));
                    	if ( $ckv !== false ) {
                    	//if (in_array($crow['nom'], $_SESSION['event'])) {
                    		echo '<a title="Impossible de supprimer des réservations existe pour ce stade" class="btn btn-outline-secondary bi-lock-fill"></a>'; 
                    	} else {
                    		echo '<a title="Supprimer" data-bs-toggle="modal"  data-idstade="' . $crow['id_stade'] . '"  data-bs-target="#delstade" class="btn btn-outline-danger bi-trash-fill"></a>';                    }
                    echo '</td>
                    
                    </tr>';
                  }

                  ?>
                </tbody>
              </table>

            </div>
            
            <div class="tab-pane fade show" id="stadet" role="tabpanel" aria-labelledby="stadet-tab">

            <br>
              <div class="container">
                <div class="row justify-content-end">
                  <div class="col-xs-12 col-md-3 justify-content-end d-flex">
                    <a data-bs-toggle="modal" data-ajaction="addstadetype" data-action="Ajouter type de stade" data-bs-target="#modstadetype" class="btn btn-outline-success bi-plus-circle-fill"> Ajouter un type</a>
                  </div>
                </div>
              </div>
              <br>
              <table id="stadetypetable" class="hidden" data-classes="table table-hover table-sm">
                <thead>
                  <tr>
                    <th data-field="sstnom" data-align="left">Type </th>
                    <th data-field="sststate" data-align="left">Status </th>
                    <th data-field="sstmod" data-align="center"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php

                  foreach ($_SESSION['stadetype'] as $crow) {
                    
                    echo '<tr>
                    <td> ' . $crow['nom_type'] . ' </td>';
                    if ( $crow['type_actif'] == '1' ) {
                    	echo '<td>Actif</td>';
                    } else {
                        echo '<td>Inactif</td>';
                    }
                    echo '<td> 
                    	<a title="Modifier" data-bs-toggle="modal"  data-ajaction="modifstadetype" data-action="Modification type de stade" data-idstadetype="' . $crow['id_type_stade'] . '" data-nomtype="' . $crow['nom_type'] . '"  data-statustype="' . $crow['type_actif'] . '" data-bs-target="#modstadetype" class="btn btn-outline-info bi-gear-fill"></a>';
                    $ckv = array_search($crow['nom_type'], array_column($_SESSION['event'], 'type_stade'));
                    if ( $ckv !== false ) {
                    	echo '<a title="Impossible de supprimer des réservations existe pour ce type de stade" class="btn btn-outline-secondary bi-lock-fill"></a>'; 
                    } else {
                    	echo '<a title="Supprimer" data-bs-toggle="modal"  data-idstadetype="' . $crow['id_type_stade'] . '"  data-bs-target="#delstadetype" class="btn btn-outline-danger bi-trash-fill"></a>';
                    }

                    echo '</td></tr>';
                  }

                  ?>
                </tbody>
              </table>
            <div>
          </div>



        </div>
      </div>
    </div>

    <div class="modal fade" id="modstade" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
              <input type="text" class="form-control inputnob" id="msaction">
              <input type="text" class="form-control hidden" id="msajaction">
              <input type="text" class="form-control hidden" id="msidstatde">
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="row justify-content-start">
                <div class="col-md-4">
                  <label for="recipient-name" class="col-form-label">Nom:</label>
                  <input type="text" class="form-control cblue" id="msnom">
                </div>
                <div class="col-md-4">
                  <label for="recipient-name" class="col-form-label">Type:</label>
                  <select class="form-control cblue" id="mstype">
		                  <option value='foot'>Foot</option>
                      <option value='tennis'>Tennis</option>
                      <option value='rugby'>Rugby</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="recipient-name" class="col-form-label">Status:</label>
                  <select class="form-control cblue" id="msstatus">
		                  <option value='1'>actif</option>
                      <option value='0'>inactif</option>
                  </select>
                </div>
              </div>

              <div class="row justify-content-start">
                <div class="col-xs-12 col-12 mb-3">
                  <label for="recipient-name" class="col-form-label">Date de fin ( indiquer jusqu'à quel date pourrait-on reserver ):</label>
                  <input type="date" class="form-control cblue" id="msdateend">
                </div>
              </div>
              <div class="alert alert-danger" role="alert">
                  Les créneaux doivent etre entre séparer par une virgule sans chévauchement ( ex : 08h00-10h00,10h00-12h00,.... )
              </div>      
              <div class="row justify-content-start">
                <div class="col-xs-12 col-12 mb-3">
                  <label for="recipient-name" class="col-form-label">Créneau Lundi :</label>
                  <input type="textarea" class="form-control cblue" id="mslundi">
                </div>
              </div>

              <div class="row justify-content-start">
                <div class="col-xs-12 col-12 mb-3">
                  <label for="recipient-name" class="col-form-label">Créneau Mardi :</label>
                  <input type="textarea" class="form-control cblue" id="msmardi">
                </div>
              </div>

              <div class="row justify-content-start">
                <div class="col-xs-12 col-12 mb-3">
                  <label for="recipient-name" class="col-form-label">Créneau Mercredi :</label>
                  <input type="textarea" class="form-control cblue" id="msmercredi">
                </div>
              </div>

              <div class="row justify-content-start">
                <div class="col-xs-12 col-12 mb-3">
                  <label for="recipient-name" class="col-form-label">Créneau Jeudi :</label>
                  <input type="textarea" class="form-control cblue" id="msjeudi">
                </div>
              </div>

              <div class="row justify-content-start">
                <div class="col-xs-12 col-12 mb-3">
                  <label for="recipient-name" class="col-form-label">Créneau Vendredi :</label>
                  <input type="textarea" class="form-control cblue" id="msvendredi">
                </div>
              </div>

              <div class="row justify-content-start">
                <div class="col-xs-12 col-12 mb-3">
                  <label for="recipient-name" class="col-form-label">Créneau Samedi :</label>
                  <input type="textarea" class="form-control cblue" id="mssamedi">
                </div>
              </div>

              <div class="row justify-content-start">
                <div class="col-xs-12 col-12 mb-3">
                  <label for="recipient-name" class="col-form-label">Créneau Dimanche :</label>
                  <input type="textarea" class="form-control cblue" id="msdimanche">
                </div>
              </div>

            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-primary" id="modstadesub">Valider</button>
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade" id="moduser" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><input type="text" class="form-control inputnob" id="muaction"><input type="text" class="form-control hidden" id="muajaction"><input type="text" class="form-control hidden" id="muiduser"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="row justify-content-start">
                <div class="col-md-6">
                  <label for="recipient-name" class="col-form-label">Nom:</label>
                  <input type="text" class="form-control cblue" id="munom">
                </div>
                <div class="col-md-6">
                  <label for="recipient-name" class="col-form-label">Prenom:</label>
                  <input type="text" class="form-control cblue" id="muprenom">
                </div>
              </div>
              <div class="row justify-content-start">
                <div class="col-xs-12 col-6 mb-3">
                  <label for="recipient-name" class="col-form-label">Email:</label>
                  <input type="text" class="form-control cblue" id="muemail">
                </div>
                <div class="col-xs-12 col-6 mb-3">
                  <label for="recipient-name" class="col-form-label">Téléphone:</label>
                  <input type="text" class="form-control cblue" id="mutel">
                </div>
              </div>
              <div class="row justify-content-start">
                <div class="col-xs-12 col-12 mb-3">
                  <label for="recipient-name" class="col-form-label">Adresse :</label>
                  <input type="textarea" class="form-control cblue" id="muaddresse">
                </div>
              </div>
              <div class="row justify-content-start">
                <div class="col-xs-12 col-6 mb-3">
                  <label for="recipient-name" class="col-form-label">Status:</label>
                  <select class="form-control cblue" id="muactif">
		      <option value='1'>actif</option>
                      <option value='0'>inactif</option>
                  </select>
                </div>
                <div class="col-xs-12 col-6 mb-3">
                  <label for="recipient-name" class="col-form-label">Groupes:</label>
                  <select class="form-control cblue" id="mugroup">
		      <option value='1'>admin</option>
                      <option value='2'>user</option>
                  </select>
                  
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-primary" id="modusersub">Valider</button>
          </div>
        </div>
      </div>
    </div>
        
    <div class="modal fade" id="modstadetype" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title" id="staticBackdropLabel">
              <input type="text" class="form-control inputnob" id="mtaction">
              <input type="text" class="form-control hidden" id="mtajaction">
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">          
            <div class="row justify-content-start">
                <div class="col-xs-12 col-6 mb-3">
                  <label for="recipient-name" class="col-form-label">Nom:</label>
                    <input type="text" class="form-control" id="msnomstadetype">
                    <input type="text" class="form-control hidden" id="msidstadetype">
                </div>
                <div class="col-xs-12 col-6 mb-3">
                  <label for="recipient-name" class="col-form-label">Status:</label>
                  <select class="form-control cblue" id="mttypeactif">
		    <option value='1'>actif</option>
                    <option value='0'>inactif</option>
                  </select>
                </div>
             </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-primary" id="modstadetypesub">Modifier</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="deluser" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirmation la suppression ? </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <input type="text" class="form-control hidden" id="mudiduser">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-danger" id="delusersub">Supprimer</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="delstade" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirmation la suppression ? </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <input type="text" class="form-control hidden" id="msdidstade">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-danger" id="delstadesub">Supprimer</button>
          </div>
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="delstadetype" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirmation la suppression ? </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <input type="text" class="form-control hidden" id="msdidstadetype">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-danger" id="delstadetypesub">Supprimer</button>
          </div>
        </div>
      </div>
    </div>
    

    <script>
    
      // Scrool to top on page load
      $('html, body').animate({
	scrollTop: '0px'
       	},
      1500);
    
      $('body').removeClass("modal-open");
      $('.modal-backdrop').remove();

      $('#userstable').bootstrapTable();
      $('#userstable').bootstrapTable('showLoading');
      setTimeout(function() {
        $('#userstable').bootstrapTable('hideLoading');
        $('#titlenba').removeClass('pb-5');
        $('#userstable').removeClass('hidden');
      }, 1000);

      $('#stadetable').bootstrapTable();
      $('#stadetable').bootstrapTable('showLoading');
      setTimeout(function() {
        $('#stadetable').bootstrapTable('hideLoading');
        $('#titlenba').removeClass('pb-5');
        $('#stadetable').removeClass('hidden');
      }, 1000);
      
      $('#stadetypetable').bootstrapTable();
      $('#stadetypetable').bootstrapTable('showLoading');
      setTimeout(function() {
        $('#stadetypetable').bootstrapTable('hideLoading');
        $('#titlenba').removeClass('pb-5');
        $('#stadetypetable').removeClass('hidden');
      }, 1000);
      

      $('#moduser').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        $(this).find('.modal-header #muaction').val(button.data('action'))
        $(this).find('.modal-header #muajaction').val(button.data('ajaction'))
        $(this).find('.modal-header #muiduser').val(button.data('iduser'))
        $(this).find('.modal-body #munom').val(button.data('nom'))
        $(this).find('.modal-body #muprenom').val(button.data('prenom'))
        $(this).find('.modal-body #muemail').val(button.data('email'))
        $(this).find('.modal-body #mutel').val(button.data('tel'))
        $(this).find('.modal-body #muaddresse').val(button.data('addresse'))
        $(this).find('.modal-body #muactif').val(button.data('actif'))
        $(this).find('.modal-body #mugroup').val(button.data('group'))
      });

      $('#modstade').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        $(this).find('.modal-header #msaction').val(button.data('action'))
        $(this).find('.modal-header #msajaction').val(button.data('ajaction'))
        $(this).find('.modal-header #msidstatde').val(button.data('idstade'))
        $(this).find('.modal-body #mstype').val(button.data('type'))
        $(this).find('.modal-body #msnom').val(button.data('nom'))
        $(this).find('.modal-body #msstatus').val(button.data('actif'))
        $(this).find('.modal-body #mslundi').val(button.data('lundi'))
        $(this).find('.modal-body #msmardi').val(button.data('mardi'))
        $(this).find('.modal-body #msmercredi').val(button.data('mercredi'))
        $(this).find('.modal-body #msjeudi').val(button.data('jeudi'))
        $(this).find('.modal-body #msvendredi').val(button.data('vendredi'))
        $(this).find('.modal-body #mssamedi').val(button.data('samedi'))
        $(this).find('.modal-body #msdimanche').val(button.data('dimanche'))
        $(this).find('.modal-body #msdateend').val(button.data('dateend'))
      });
      
      $('#modstadetype').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        $(this).find('.modal-body #msnomstadetype').val(button.data('nomtype'))
        $(this).find('.modal-body #msidstadetype').val(button.data('idstadetype'))
        $(this).find('.modal-header #mtaction').val(button.data('action'))
        $(this).find('.modal-header #mtajaction').val(button.data('ajaction'))
        $(this).find('.modal-body #mttypeactif').val(button.data('statustype'))
      });

      $('#deluser').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        $(this).find('.modal-header #mudiduser').val(button.data('iduser'))
      });

      $('#delstade').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        $(this).find('.modal-header #msdidstade').val(button.data('idstade'))
      });
      
      $('#delstadetype').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        $(this).find('.modal-header #msdidstadetype').val(button.data('idstadetype'))
      });
      
     
     $("#delstadetypesub").on('click', function(e) {
        e.preventDefault();
        $.ajax({
          url: "include/function.php",
          type: "POST",
          async: true,
          data: {
            action: 'delstadetype',
            msdidstadetype: $("#msdidstadetype").val(),
          },
          dataType: "json",
          success: function(response) {
            switch (response.data) {
              case 'ok':
                $.toast({
                  heading: "Ajout/Modification",
                  text: 'Réussit.',
                  showHideTransition: 'slide',
                  position: 'bottom-center',
                  icon: 'success'
                })
                break;

              default:
                $.toast({
                  heading: 'Warning',
                  text: 'Echec !',
                  showHideTransition: 'plain',
                  position: 'bottom-center',
                  icon: 'warning'
                })
            }
          },
        });
        setTimeout(function() {
          $('#ajax-content').load('ajax/admin.php');
        }, 2000);
      });

      
      $("#modstadetypesub").on('click', function(e) {
        e.preventDefault();
        $.ajax({
          url: "include/function.php",
          type: "POST",
          async: true,
          data: {
            action: $("#mtajaction").val(),
            mtidstatde: $("#msidstadetype").val(),
            mtnomstade: $("#msnomstadetype").val(),
            mtstatussatde: $("#mttypeactif").val()
          },
          dataType: "json",
          success: function(response) {
            switch (response.data) {
              case 'ok':
                $.toast({
                  heading: "Ajout/Modification",
                  text: 'Réussit.',
                  showHideTransition: 'slide',
                  position: 'bottom-center',
                  icon: 'success'
                })
                break;

              default:
                $.toast({
                  heading: 'Warning',
                  text: 'Echec !',
                  showHideTransition: 'plain',
                  position: 'bottom-center',
                  icon: 'warning'
                })
            }
          },
        });
        setTimeout(function() {
          $('#ajax-content').load('ajax/admin.php');
        }, 2000);
      });

      $("#modusersub").on('click', function(e) {
        e.preventDefault();
        $.ajax({
          url: "include/function.php",
          type: "POST",
          async: true,
          data: {
            action: $("#muajaction").val(),
            muiduser: $("#muiduser").val(),
            munom: $("#munom").val(),
            muprenom: $("#muprenom").val(),
            muemail: $("#muemail").val(),
            mutel: $("#mutel").val(),
            muaddresse: $("#muaddresse").val(),
            muactif: $("#muactif").val(),
            mugroup: $("#mugroup").val()
          },
          dataType: "json",
          success: function(response) {
            switch (response.data) {
              case 'ok':
                $.toast({
                  heading: "Ajout/Modification",
                  text: 'Réussit.',
                  showHideTransition: 'slide',
                  position: 'bottom-center',
                  icon: 'success'
                })
                break;

              default:
                $.toast({
                  heading: 'Warning',
                  text: 'Echec !',
                  showHideTransition: 'plain',
                  position: 'bottom-center',
                  icon: 'warning'
                })
            }
          },
        });
        setTimeout(function() {
          $('#ajax-content').load('ajax/admin.php');
        }, 2000);
      });

      $("#modstadesub").on('click', function(e) {
        e.preventDefault();
        $.ajax({
          url: "include/function.php",
          type: "POST",
          async: true,
          data: {
            action: $("#msajaction").val(),
            msidstatde: $("#msidstatde").val(),
            mstype: $("#mstype").val(),
            msnom: $("#msnom").val(),
            msstatus: $("#msstatus").val(),
            mslundi: $("#mslundi").val(),
            msmardi: $("#msmardi").val(),
            msmercredi: $("#msmercredi").val(),
            msjeudi: $("#msjeudi").val(),
            msvendredi: $("#msvendredi").val(),
            mssamedi: $("#mssamedi").val(),
            msdimanche: $("#msmsdimanchejeudi").val(),
            msdateend: $("#msdateend").val()
          },
          dataType: "json",
          success: function(response) {
            switch (response.data) {
              case 'ok':
                $.toast({
                  heading: "Ajout/Modification",
                  text: 'Réussit.',
                  showHideTransition: 'slide',
                  position: 'bottom-center',
                  icon: 'success'
                })
                break;

              default:
                $.toast({
                  heading: 'Warning',
                  text: 'Echec !',
                  showHideTransition: 'plain',
                  position: 'bottom-center',
                  icon: 'warning'
                })
            }
          },
        });
        setTimeout(function() {
          $('#ajax-content').load('ajax/admin.php');
        }, 2000);
      });

      $("#delusersub").on('click', function(e) {
        e.preventDefault();
        $.ajax({
          url: "include/function.php",
          type: "POST",
          async: true,
          data: {
            action: "deluser",
            muiduser: $("#mudiduser").val(),
          },
          dataType: "json",
          success: function(response) {
            switch (response.data) {
              case 'ok':
                $.toast({
                  heading: "Suppression",
                  text: 'Réussit.',
                  showHideTransition: 'slide',
                  position: 'bottom-center',
                  icon: 'success'
                })
                break;

              default:
                $.toast({
                  heading: 'Warning',
                  text: 'Echec !',
                  showHideTransition: 'plain',
                  position: 'bottom-center',
                  icon: 'warning'
                })
            }
          },
        });
        setTimeout(function() {
          $('#ajax-content').load('ajax/admin.php');
        }, 2000);
      });

      $("#delstadesub").on('click', function(e) {
        e.preventDefault();
        $.ajax({
          url: "include/function.php",
          type: "POST",
          async: true,
          data: {
            action: "delstade",
            msdidstade: $("#msdidstade").val(),
          },
          dataType: "json",
          success: function(response) {
            switch (response.data) {
              case 'ok':
                $.toast({
                  heading: "Suppression",
                  text: 'Réussit.',
                  showHideTransition: 'slide',
                  position: 'bottom-center',
                  icon: 'success'
                })
                break;

              default:
                $.toast({
                  heading: 'Warning',
                  text: 'Echec !',
                  showHideTransition: 'plain',
                  position: 'bottom-center',
                  icon: 'warning'
                })
            }
          },
        });
        setTimeout(function() {
          $('#ajax-content').load('ajax/admin.php');
        }, 2000);
      });
    </script>


<?php

  }
  
} else {
  header('Location: ../index.php');
}
?>
