
Reservation de Salles
======================

Application légère dans le but de fournir a un site web permettant aux utilisateurs
de se connecter et d'émettre une réservation aux sport proposés.
Les sport proposés sont le tennis, le rugby et le foot.


Prérequis de la mise en ligne 
--------------

Apache2, php7.4 minimum, mariadb-server

Prérequis de l'utilisation  
--------------

Wampserver, Visual Studio Code
Frameworks utilisés
--------------------

- jquery v3.3.1 
- popper v2.5.4 
- bootstrap v5.0.2
- bootstrap-table v1.19.0
- jquery-toast-plugin
- cookies v2.0
  
Installation
-------------

Installation paquet
 
  - apt install Apache2 php mariadb-server

Import de la base de donnée

  - mysql> create database rsvsport
  - mysql -b rsvsport < doc/rsvsport.sql
  
Connexion à l'application web

  - rename ./include/config.php.example ./include/config.php 
  
  renseigner les comptes de connexions à la bdd et pour l'envoi de mail
  
  - http://localhost/....
  
  compte admin par default : admin01@rsvsport.com / initpw2?
  compte user par default : user01@rsvsport.com / initpw1#

