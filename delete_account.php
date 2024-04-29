<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = '';
    header('location:login.php');
}

if(isset($_POST['delete_account'])) {
    // Vérifier d'abord si une demande de suppression existe déjà pour cet utilisateur
    $select_existing_request = $conn->prepare("SELECT id FROM deletion_requests WHERE user_id = ?");
    $select_existing_request->execute([$user_id]);
    $existing_request = $select_existing_request->fetchColumn();

    // Si aucune demande de suppression n'existe pas encore, insérer une nouvelle demande
    if(!$existing_request) {
        // Récupérer le nom de l'utilisateur à partir de la table users
        $select_user = $conn->prepare("SELECT name FROM users WHERE id = ?");
        $select_user->execute([$user_id]);
        $user = $select_user->fetch(PDO::FETCH_ASSOC);

        // Insérer une demande de suppression dans la table deletion_requests
        $insert_request = $conn->prepare("INSERT INTO deletion_requests (user_id, name) VALUES (?, ?)");
        $insert_request->execute([$user_id, $user['name']]);
    }

    // Rediriger l'utilisateur vers une page de confirmation
    header('location: profile.php');
}
?>
