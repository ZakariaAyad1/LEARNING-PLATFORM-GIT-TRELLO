<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = '';
    header('location:login.php');
}

// Vérifier si une demande de suppression existe pour cet utilisateur
$select_request = $conn->prepare("SELECT * FROM deletion_requests WHERE user_id = ?");
$select_request->execute([$user_id]);
$request_exists = $select_request->fetch(PDO::FETCH_ASSOC);

// Initialiser le message à vide
$message = '';

// Vérifier si l'utilisateur a soumis une demande de suppression
if(isset($_POST['delete_account'])) {
    // Si une demande de suppression existe, afficher un message d'attente
    if($request_exists) {
        $message = "Votre demande de suppression est en attente de réponse de l'administrateur.";
    } else {
        // Insérer une demande de suppression dans la table deletion_requests
        $insert_request = $conn->prepare("INSERT INTO deletion_requests (user_id) VALUES (?)");
        $insert_request->execute([$user_id]);

        // Afficher un message de confirmation
        $message = "Votre demande de suppression a été envoyée à l'administrateur pour examen.";
    }
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile</title>
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="profile">

   <h1 class="heading">Profile Details</h1>

   <div class="details">

      <div class="user">
         <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <p>Student</p>
         <a href="update.php" class="inline-btn">Update Profile</a>
         <form action="delete_account.php" method="post">
            <!-- Bouton "Supprimer mon compte" -->
            <button type="submit" name="delete_account">delete account</button>
         </form>
         <!-- Afficher le message s'il existe -->
         <?php if(!empty($message)): ?>
            <p><?php echo $message; ?></p>
         <?php endif; ?>
      </div>

      <div class="box-container">

         <div class="box">
            <div class="flex">
               <i class="fas fa-bookmark"></i>
               <div>
                  <h3><?= $total_bookmarked; ?></h3>
                  <span>Saved Playlists</span>
               </div>
            </div>
            <a href="#" class="inline-btn">View Playlists</a>
         </div>

         <div class="box">
            <div class="flex">
               <i class="fas fa-heart"></i>
               <div>
                  <h3><?= $total_likes; ?></h3>
                  <span>Liked Tutorials</span>
               </div>
            </div>
            <a href="#" class="inline-btn">View Liked</a>
         </div>

         <div class="box">
            <div class="flex">
               <i class="fas fa-comment"></i>
               <div>
                  <h3><?= $total_comments; ?></h3>
                  <span>Video Comments</span>
               </div>
            </div>
            <a href="#" class="inline-btn">View Comments</a>
         </div>

      </div>

   </div>

</section>

<!-- Profile section ends -->

<!-- Custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>
