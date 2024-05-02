<?php
include 'components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $user_id = $_COOKIE['tutor_id'];
}else{
   $user_id = '';
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:home.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>View Announcement</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Announcement section starts -->
<section class="announcement-details">

<h1 class="heading">Announcement Details</h1>

<div class="announcement-content">

<?php
$select_announcement = $conn->prepare("SELECT * FROM `Announcements` WHERE announcement_id = ? AND tutor_id = ?");
$select_announcement->execute([$get_id, $tutor_id]);
if($select_announcement->rowCount() > 0){
    while($fetch_announcement = $select_announcement->fetch(PDO::FETCH_ASSOC)){
        $announcement_id = $fetch_announcement['announcement_id'];
?>
<div class="details">
    <h3><?= $fetch_announcement['title']; ?></h3>
    <p><?= $fetch_announcement['content']; ?></p>
    <div class="date"><i class="fas fa-calendar"></i><span><?= $fetch_announcement['created_at']; ?></span></div>
</div>
<?php
    }
}else{
    echo '<p class="empty">This announcement was not found!</p>';
}  
?>


</div>

</section>
<!-- Announcement section ends -->

<!-- Custom JS file link -->
<script src="js/script.js"></script>
   
</body>
</html>

