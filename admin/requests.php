<?php
include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

$select_admin = $conn->query("SELECT id FROM admin LIMIT 1");
$admin_id = $select_admin->fetchColumn();

if(isset($_POST['action'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    if($action == 'accept') {
        // Supprimer le compte de l'utilisateur correspondant
        $delete_user = $conn->prepare("DELETE FROM users WHERE id = (SELECT user_id FROM deletion_requests WHERE id = ?)");
        $delete_user->execute([$request_id]);
    }

    // Supprimer la demande de la table deletion_requests
    $delete_request = $conn->prepare("DELETE FROM deletion_requests WHERE id = ?");
    $delete_request->execute([$request_id]);

    // Rediriger pour éviter la soumission en double
    header('location: requests.php');
}

$select_existing_request = $conn->prepare("SELECT * FROM deletion_requests WHERE user_id = ?");
$select_existing_request->execute([$tutor_id]);
$existing_request = $select_existing_request->fetch(PDO::FETCH_ASSOC);

if(!$existing_request && isset($_POST['delete_account'])) {
    $insert_request = $conn->prepare("INSERT INTO deletion_requests (user_id, name) VALUES (?, ?)");
    $insert_request->execute([$tutor_id, $tutor_id]);

    header('location: dashboard.php');
}

$select_deletion_requests = $conn->prepare("SELECT d.id, u.name AS user_name FROM deletion_requests d INNER JOIN users u ON d.user_id = u.id WHERE d.admin_id IS NULL");
$select_deletion_requests->execute();
$deletion_requests = $select_deletion_requests->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
<?php include '../components/admin_header.php'; ?>
<div class="deletion-requests">
    <h1><center>Demandes de suppression en attente</center></h1>
    <ul>
        <?php foreach ($deletion_requests as $request) : ?>
            <li><?= $request['user_name']; ?> - attend la suppression de son compte
                <form action="" method="post">
                    <input type="hidden" name="request_id" value="<?= $request['id']; ?>">
                    <button type="submit" name="action" id="respond" value="accept">Accepter</button>
                    <button type="submit" name="action" id="respond" value="reject">Refuser</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<script src="../js/admin_script.js"></script>
</body>
</html>
