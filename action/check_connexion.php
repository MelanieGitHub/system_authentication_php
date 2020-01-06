<?php

session_start();

// connexion à la base de données
include('../sql/Database.php');
$db = new Database();
$cnn = $db->dbConnection();

////////////////////////////////////////////////////////
/////////////// CONNEXION //////////////////////////////
////////////////////////////////////////////////////////

if (isset($_POST['username']) && isset($_POST['password'])) {

   // mysqli_real_escape_string et htmlspecialchars || éliminer attaque de type injection SQL et XSS
   $username = htmlspecialchars($_POST['username']);
   $password = htmlspecialchars($_POST['password']);

   //Vérifie si un compte correspond
   $req_count_compte = $cnn->prepare("SELECT count(*) FROM compte WHERE Pseudo = ? AND Password = ?");
   $req_count_compte->execute(array($username, $password));
   $rep = $req_count_compte->fetch();

   $count = $rep['count(*)'];

   if ($count != 0) {
      //Récupère les données utilisateur dans la session
      $req_connexion = $cnn->prepare("SELECT ID_Compte, Pseudo FROM compte WHERE Pseudo = ? AND Password = ?");
      $req_connexion->execute(array($username, $password));
      $ligne = $req_connexion->fetch();

      $_SESSION['username'] = $ligne['Pseudo'];
      $_SESSION['idsession'] = $ligne['ID_Compte'];;

      header('Location: ../amelie_crepe.php');
   } else {
      header('Location: ../index.php?erreur=1');
   }
} else {
   header('Location: ../index.php');
}

////////////////////////////////////////////////////////
/////////////// INSCRIPTION ////////////////////////////
////////////////////////////////////////////////////////

if (isset($_POST['username_i']) && isset($_POST['mail_i']) && isset($_POST['password_i']) && isset($_POST['password2_i'])) {


   // mysqli_real_escape_string et htmlspecialchars || éliminer attaque de type injection SQL et XSS
   $username = htmlspecialchars($_POST['username_i']);
   $mail = htmlspecialchars($_POST['mail_i']);
   $password = htmlspecialchars($_POST['password_i']);
   $password2 = htmlspecialchars($_POST['password2_i']);


   $regexPseudo = '/^[a-zA-Z0-9._-]{2,}$/';
   $regexMail = '/^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/';
   $regexPassword = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$/';

   $testPseudo = preg_match($regexPseudo, $username);
   $testMail = preg_match($regexMail, $mail);
   $testPassword = preg_match($regexPassword, $password);

   if ($username !== "" && $mail !== "" && $password !== "" && $password2 !== "") {
      if ($testPseudo == true) {
         if ($testMail == true) {

            if ($testPassword == true) {

               if ($password == $password2) {

                  $req_inscription = $cnn->prepare("INSERT INTO compte VALUES(?,?,?,?)");
                  $req_inscription->execute(array(NULL, $username, $mail, $password));

                  header('Location: ../index.php?inscription=1');
               } else {
                  header('Location: ../index.php?erreur=6');
               }
            } else {
               header('Location: ../index.php?erreur=5');
            }
         } else {
            header('Location: ../index.php?erreur=3');
         }
      } else {
         header('Location: ../index.php?erreur=4');
      }
   } else {
      header('Location: ../index.php');
   }
}

$cnn = null;
