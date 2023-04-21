<?php

//Function to check if the request is an AJAX request
function is_ajax()
{
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {

	require('./config.php');
	global $mysqli;
	
	// Pour l'envoi de mail 
	include('Mail.php');
	global $hostsmtp;
	global $smtpcontact;
	global $smtppassword;
	global $smtpport;
	
	// Pour l'envoi de mail en html
	include('Mail/mime.php');
	

	session_start();

	$action = $_POST['action'];

	if ($action == 'login') {

		$_SESSION['user'] = array();
		$login = $_POST['login'];
		$password = $_POST['password'];
		$piege = $_POST['piege'];
		sleep(1);

		if(!empty($piege)) {
			echo json_encode(["data" => "ko"]);
			exit;
		}
		
		$sqlrecupauth = "select id_user, nom, prenom, password, email, token, tel, adresse, actif, nom_groups as groups ,timestamp from users u INNER JOIN groups g on u.id_groups = g.id_groups WHERE email = '$login' and actif = '1'";
		$reqsqlrecupauth = $mysqli->query($sqlrecupauth) or die('Erreur ' . $sqlrecupauth . ' ' . $mysqli->error);
		$authcheck = $reqsqlrecupauth->fetch_assoc();

		$password_hash = $authcheck['password'];

		if (password_verify($password, $password_hash)) {

			$_SESSION['user']['authentification'] = "1";
			$_SESSION['user']['id'] = $authcheck['id_user'];
			$_SESSION['user']['nom'] = $authcheck['nom'];
			$_SESSION['user']['prenom'] = $authcheck['prenom'];
			$_SESSION['user']['email'] = $authcheck['email'];
			$_SESSION['user']['group'] = $authcheck['groups'];
			$_SESSION['user']['tel'] = $authcheck['tel'];
			$_SESSION['user']['adresse'] = $authcheck['adresse'];
			$_SESSION['user']['actif'] = $authcheck['actif'];
			$_SESSION['user']['timestamp'] = $authcheck['timestamp'];

			echo json_encode(["data" => "ok"]);
			exit;
		} else {
			echo json_encode(["data" => "ko"]);
			exit;
		}
	}




	if ($action == 'insertevent') {

		if ($_SESSION['user']['authentification'] == "1") {

			$datestart = $_POST['datestart'];
			$dateend = $_POST['dateend'];
			$stypestade = $_POST['typestade'];
			$sstadename = $_POST['stadename'];
			$comment = $_POST['comment'];
			$id = $_SESSION['user']['id'];
			
			// recupere l'id du type stade
			$typestadesql = "select id_type_stade from stadetype where nom_type  = '$stypestade'";
			$reqtypestadesql = $mysqli->query($typestadesql) or die('Erreur ' . $typestadesql . ' ' . $mysqli->error);
			$resulttypestade = $reqtypestadesql->fetch_assoc();
			$typestade = $resulttypestade['id_type_stade'];
			
			// recupere l'id du nom stade
			$nomstadesql = "select id_stade from stade where nom  = '$sstadename'";
			$reqnomstadesql = $mysqli->query($nomstadesql) or die('Erreur ' . $nomstadesql . ' ' . $mysqli->error);
			$resultnomstade = $reqnomstadesql->fetch_assoc();
			$stadename = $resultnomstade['id_stade'];

			$sqlsubcote = "insert into event (id_user, id_type_stade, id_stade, date_start, date_end, commentaire ) values ('$id', '$typestade', '$stadename', '$datestart', '$dateend', '$comment' )";
			if ($mysqli->query($sqlsubcote)) {
				echo json_encode(["data" => "ok"]);
				exit;
			} else {
				echo json_encode(["data" => "ko"]);
				exit;
			}
		}
	}

	if ($action == 'delevent') {

		if ($_SESSION['user']['authentification'] == "1") {

			$idevent = $_POST['idevent'];
			$id = $_SESSION['user']['id'];

			$sqlsubcote = "delete from event where id_user = '$id' and id_event = '$idevent'";
			if ($mysqli->query($sqlsubcote)) {
				echo json_encode(["data" => "ok"]);
				exit;
			} else {
				echo json_encode(["data" => "ko"]);
				exit;
			}
		}
	}

	if ($action == 'ddelevent') {

		if ($_SESSION['user']['authentification'] == "1") {

			$datestart = $_POST['ddatestart'];
			$dateend = $_POST['ddateend'];
			$sstadename = $_POST['dstadename'];
			$id = $_SESSION['user']['id'];
			
			// recupere l'id du nom stade
			$nomstadesql = "select id_stade from stade where nom  = '$sstadename'";
			$reqnomstadesql = $mysqli->query($nomstadesql) or die('Erreur ' . $nomstadesql . ' ' . $mysqli->error);
			$resultnomstade = $reqnomstadesql->fetch_assoc();
			$stadename = $resultnomstade['id_stade'];

			$sqlsubcote = "delete from event where id_user = '$id' and date_start = '$datestart' and date_end = '$dateend' and id_stade = '$stadename'";
			if ($mysqli->query($sqlsubcote)) {
				echo json_encode(["data" => "ok"]);
				exit;
			} else {
				echo json_encode(["data" => "ko"]);
				exit;
			}
		}
	}

	if ($action == 'modifuser') {

		if ($_SESSION['user']['authentification'] == "1" && $_SESSION['user']['group'] == "admin") {

			$muiduser = $_POST['muiduser'];
			$munom = $_POST['munom'];
			$muprenom = $_POST['muprenom'];
			$muemail = $_POST['muemail'];
			$mutel = $_POST['mutel'];
			$muaddresse = $_POST['muaddresse'];
			$muactif = $_POST['muactif'];
			$mugroup = $_POST['mugroup'];
			
			
			// Echap simple cotes
			$search = array('\'');
			$replace = array('\\\'');
			$muaddresse = str_replace($search, $replace, $muaddresse);

			$sqlsubcote = "update users set nom = '$munom', prenom = '$muprenom', email = '$muemail', tel = '$mutel', adresse = '$muaddresse', actif = '$muactif', id_groups = '$mugroup' where id_user = '$muiduser' ";
			if ($mysqli->query($sqlsubcote)) {
				echo json_encode(["data" => "ok"]);
				exit;
			} else {
				echo json_encode(["data" => "ko"]);
				exit;
			}
		}
	}
	
	if ($action == 'majprof') {

		if ($_SESSION['user']['authentification'] == "1") {

			$speudol = $_POST['speudol'];
			$nompl = $_POST['nompl'];
			$emaill = $_POST['emaill'];
			$sdsl = $_POST['dsl'];
			$dfal = $_POST['dfal'];	
			$iduser = $_SESSION['user']['id'];
			
			// Echap simple cotes
			$search = array('\'');
			$replace = array('\\\'');
			$dsl = str_replace($search, $replace, $sdsl);

			$sqlsubcote = "update users set nom = '$speudol', prenom = '$nompl', email = '$emaill', tel = '$dfal', adresse = '$dsl' where id_user = '$iduser' ";
			if ($mysqli->query($sqlsubcote)) {
			        $_SESSION['user']['nom'] = $speudol;
				$_SESSION['user']['prenom'] = $nompl;
				$_SESSION['user']['email'] = $emaill;
				$_SESSION['user']['tel'] = $dfal;
				$_SESSION['user']['adresse'] = str_replace("\\", "", $dsl);
				echo json_encode(["data" => "ok"]);
				exit;
			} else {
				echo json_encode(["data" => "ko"]);
				exit;
			}
		}
	}

	if ($action == 'adduser') {

		if ($_SESSION['user']['authentification'] == "1" && $_SESSION['user']['group'] == "admin") {

			$munom = $_POST['munom'];
			$muprenom = $_POST['muprenom'];
			$muemail = $_POST['muemail'];
			$mutel = $_POST['mutel'];
			$muaddresse = $_POST['muaddresse'];
			$muactif = $_POST['muactif'];
			$mugroup = $_POST['mugroup'];

			// Echap simple cotes
			$search = array('\'');
			$replace = array('\\\'');
			$muaddresse = str_replace($search, $replace, $muaddresse);


			// set randam password
			function password_generate($chars)
			{
				$data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
				return substr(str_shuffle($data), 0, $chars);
			}
			$password = password_generate(8);
			$password = password_hash($password, PASSWORD_DEFAULT);

			$sqlsubcote = "insert into users ( nom, prenom, email, tel, adresse, actif, id_groups, password ) values ('$munom', '$muprenom', '$muemail', '$mutel', '$muaddresse', '$muactif', '$mugroup', '$password' ) ";
			if ($mysqli->query($sqlsubcote)) {
				echo json_encode(["data" => "ok"]);
				exit;
			} else {
				echo json_encode(["data" => "ko"]);
				exit;
			}
		}
	}

	if ($action == 'modifstade') {

		if ($_SESSION['user']['authentification'] == "1" && $_SESSION['user']['group'] == "admin") {

			$msidstatde = $_POST['msidstatde'];
			$smstype = $_POST['mstype'];
			$msnom = $_POST['msnom'];
			$msstatus = $_POST['mstadetypesstatus'];
			$mslundi = $_POST['mslundi'];
			$msmardi = $_POST['msmardi'];
			$msmercredi = $_POST['msmercredi'];
			$msjeudi = $_POST['msjeudi'];
			$msvendredi = $_POST['msvendredi'];
			$mssamedi = $_POST['mssamedi'];
			$msdimanche = $_POST['msdimanche'];
			$msdateend = $_POST['msdateend'];
			
			// recupere l'id du type stade
			$typestadesql = "select id_type_stade from stadetype where nom_type  = '$smstype'";
			$reqtypestadesql = $mysqli->query($typestadesql) or die('Erreur ' . $typestadesql . ' ' . $mysqli->error);
			$resulttypestade = $reqtypestadesql->fetch_assoc();
			$mstype = $resulttypestade['id_type_stade'];
			
			// Echap simple cotes
			$search = array('\'');
			$replace = array('\\\'');
			$muaddresse = str_replace($search, $replace, $muaddresse);

			// recupere l'ancien nom du stade
			$lastmsnomsql = "select nom from stade where id_stade = '$msidstatde'";
			$reqlastmsnomsql = $mysqli->query($lastmsnomsql);
			$flastmsnom = $reqlastmsnomsql->fetch_assoc();
			$lastmsnom = $flastmsnom['nom'];

			$sqlsubcote = "update stade set nom = '$msnom', id_type_stade = '$mstype', actif = '$msstatus', dateend = '$msdateend', lundi = '$mslundi', mardi = '$msmardi', mercredi = '$msmercredi', jeudi = '$msjeudi', vendredi = '$msvendredi', samedi = '$mssamedi', dimanche = '$msdimanche' where id_stade = '$msidstatde' ";
			if ($mysqli->query($sqlsubcote)) {
				echo json_encode(["data" => "ok"]);
				exit;
			
			} else {
				echo json_encode(["data" => "ko"]);
				exit;
			}
		}
	}

	if ($action == 'addstade') {

		if ($_SESSION['user']['authentification'] == "1" && $_SESSION['user']['group'] == "admin") {

			$smstype = $_POST['mstype'];
			$msnom = $_POST['msnom'];
			$msstatus = $_POST['msstatus'];
			$mslundi = $_POST['mslundi'];
			$msmardi = $_POST['msmardi'];
			$msmercredi = $_POST['msmercredi'];
			$msjeudi = $_POST['msjeudi'];
			$msvendredi = $_POST['msvendredi'];
			$mssamedi = $_POST['mssamedi'];
			$msdimanche = $_POST['msdimanche'];
			$msdateend = $_POST['msdateend'];
			
			// recupere l'id du type stade
			$typestadesql = "select id_type_stade from stadetype where nom_type  = '$smstype'";
			$reqtypestadesql = $mysqli->query($typestadesql) or die('Erreur ' . $typestadesql . ' ' . $mysqli->error);
			$resulttypestade = $reqtypestadesql->fetch_assoc();
			$mstype = $resulttypestade['id_type_stade'];

			$sqlsubcote = "insert into stade ( nom, id_type_stade, actif, dateend, lundi, mardi, mercredi, jeudi, vendredi, samedi, dimanche ) values ( '$msnom', '$mstype', '$msstatus', '$msdateend', '$mslundi', '$msmardi', '$msmercredi', '$msjeudi', '$msvendredi', '$mssamedi', '$msdimanche' )";
			if ($mysqli->query($sqlsubcote)) {
				echo json_encode(["data" => "ok"]);
				exit;
			} else {
				echo json_encode(["data" => "ko"]);
				exit;
			}
		}
	}

	if ($action == 'deluser') {

		if ($_SESSION['user']['authentification'] == "1" && $_SESSION['user']['group'] == "admin") {

			$muiduser = $_POST['muiduser'];

			$sqlsubcote = "delete from users where id_user = '$muiduser' ";
			if ($mysqli->query($sqlsubcote)) {
				echo json_encode(["data" => "ok"]);
				exit;
			} else {
				echo json_encode(["data" => "ko"]);
				exit;
			}
		}
	}

	if ($action == 'delstade') {

		if ($_SESSION['user']['authentification'] == "1" && $_SESSION['user']['group'] == "admin") {

			$msdidstade = $_POST['msdidstade'];

			$sqlsubcote = "delete from stade where id_stade = '$msdidstade' ";
			if ($mysqli->query($sqlsubcote)) {
				echo json_encode(["data" => "ok"]);
				exit;
			} else {
				echo json_encode(["data" => "ko"]);
				exit;
			}
		}
	}
		
	if ($action == 'delstadetype') {

		if ($_SESSION['user']['authentification'] == "1" && $_SESSION['user']['group'] == "admin") {

			$msdidstadetype = $_POST['msdidstadetype'];

			$sqlsubcote = "delete from stadetype where id_type_stade = '$msdidstadetype' ";
			if ($mysqli->query($sqlsubcote)) {
				echo json_encode(["data" => "ok"]);
				exit;
			} else {
				echo json_encode(["data" => "ko"]);
				exit;
			}
		}
	}
	
	if ($action == 'modifstadetype') {

		if ($_SESSION['user']['authentification'] == "1" && $_SESSION['user']['group'] == "admin") {

			$mtidstatde = $_POST['mtidstatde'];
            		$mtnomstade = $_POST['mtnomstade'];
            		$mtstatussatde = $_POST['mtstatussatde'];

			$sqlsubcote = "update stadetype set nom_type = '$mtnomstade', type_actif = '$mtstatussatde'  where id_type_stade = '$mtidstatde' ";
			if ($mysqli->query($sqlsubcote)) {
				echo json_encode(["data" => "ok"]);
				exit;
			} else {
				echo json_encode(["data" => "ko"]);
				exit;smtpcontact
			}
		}
	}
	
	if ($action == 'addstadetype') {

		if ($_SESSION['user']['authentification'] == "1" && $_SESSION['user']['group'] == "admin") {

			$mtidstatde = $_POST['mtidstatde'];
            		$mtnomstade = $_POST['mtnomstade'];
            		$mtstatussatde = $_POST['mtstatussatde'];

			$sqlsubcote = "insert into stadetype ( nom_type, type_actif ) values ('$mtnomstade','$mtstatussatde')";
			if ($mysqli->query($sqlsubcote)) {
				echo json_encode(["data" => "ok"]);
				exit;
			} else {
				echo json_encode(["data" => "ko"]);
				exit;
			}
		}
	}


	if ($action == 'slogout') {

		session_destroy();
		echo json_encode(["data" => "ok"]);
		exit;

	}

	if ($action == 'subreinitpwd') {

		$emailrecup = $_POST['emailrecup'];
		if (empty($emailrecup)) {
			$emailrecup = $_SESSION['user']['email'];
		}
		$token = bin2hex(random_bytes(64));
		$sqlcpass = "UPDATE users set token = '$token' where email = '$emailrecup' and actif = '1'";
		//$cpass=$mysqli->query($sqlcpass) or die ('Erreur '.$sqlcpass.' '.$mysqli->error);
		if (!$mysqli->query($sqlcpass)) {
			echo json_encode(["data" => "ko"]);
			exit;
		}

		$from = $smtpcontact;
		$to = $emailrecup;smtpcontact
		$subject_client = "[RSV-SPORT] - Initialisation/Reinitialisation de votre mot de passe";
		
		// Adresse site
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    			$url = "https"; 
  		} else {
    			$url = "http";
    		}
		$url .= "://"; 
    		$url .= $_SERVER['HTTP_HOST']; 
    		$url .= str_replace("/include/function.php", "", $_SERVER['REQUEST_URI']);

		// Message HTML
		$msg_client .= "
		<br> Bonjour, <br><br> Veuillez trouver ci-joint le lien afin de procèder à la réinitialisation de votre mot de passe  <br> <span style='color:red'>/!\ Le lien n'est pas qu'un heure </span><br><br> <a href='$url/index.php?idrecup=$emailrecup&token=$token'>Reinitialisation password</a><br><br> <strong>Merci </strong>" . "\r\n";
		$msg_client .= $content . "\r\n";
		
		$headers = array ('From' => $from, 'To' => $to, 'Subject' => $subject_client, 'Reply-To' => $from, 'MIME-Version' => 1, 'Content-Type' => 'text/html; charset="utf-8"');
		$smtp = Mail::factory('smtp', array ('host' => $hostsmtp, 'port' => $smtpport, 'auth' => true, 'username' => $smtpcontact, 'password' => $smtppassword));
		$mail = $smtp->send($to, $headers, $msg_client);
		if (PEAR::isError($mail)) {
			echo json_encode(["data" => "ok"]);
		} else {
			echo json_encode(["data" => "ok"]);
		}
		exit;
	}

	
	if ($action == 'srpasswd') {

		$password = $_POST['password'];
		$confirmpassword = $_POST['confirmpassword'];
		$token = $_POST['token'];
		$idrecup = $_POST['idrecup'];

		if ($password == $confirmpassword) {

			$options = [
				'cost' => 13
			];
			$password_hash = password_hash($password, PASSWORD_BCRYPT, $options);
			$sqlupauthmdp = "UPDATE users set password = '$password_hash', token = '' where token = '$token' and email = '$idrecup'";
			if ($mysqli->query($sqlupauthmdp) or die('Erreur ' . $sqlupauthmdp . ' ' . $mysqli->error)) {
				echo json_encode(["data" => "ok"]);
			} else {
				echo json_encode(["data" => "ko"]);
			}

		} else {
			echo json_encode(["data" => "ko"]);
		}
		exit;

	}

	echo json_encode(["data" => "ko"]);

} else {
	header('Location: ./index.php');
}
?>

?>
