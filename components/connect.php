<?php
   $db_host = 'localhost';
   $db_port = '3308'; // Port du serveur de base de données
   $db_name = 'apprentissage';
   $user_name = 'root';
   $user_password = '';

   $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name";

   try {
      // Connexion à la base de données
      $conn = new PDO($dsn, $user_name, $user_password);
      // Définir le mode d'erreur PDO sur exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // Autres configurations PDO si nécessaire
   } catch (PDOException $e) {
      // En cas d'erreur de connexion, afficher le message d'erreur
      echo "Erreur de connexion à la base de données : " . $e->getMessage();
      // Arrêter le script ou gérer l'erreur selon votre besoin
      exit();
   }

   // Définition de la fonction unique_id() si nécessaire
   function unique_id()
   {
      $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
      $rand = array();
      $length = strlen($str) - 1;
      for ($i = 0; $i < 20; $i++) {
         $n = mt_rand(0, $length);
         $rand[] = $str[$n];
      }
      return implode($rand);
   }

?>