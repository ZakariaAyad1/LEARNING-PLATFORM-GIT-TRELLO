<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

// Functionality to add announcement
if(isset($_POST['add_announcement'])){
   $title = $_POST['title'];
   $content = $_POST['content'];
   $status = $_POST['status'];
   
   // Insert the new announcement into the database
   $add_announcement = $conn->prepare("INSERT INTO `Announcements` (tutor_id, title, content, status) VALUES (?, ?, ?, ?)");
   $add_announcement->execute([$tutor_id, $title, $content, $status]);
   $message[] = 'Announcement added successfully!';
}

// Functionality to delete announcement
if(isset($_POST['delete_announcement'])){
   $delete_id = $_POST['announcement_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
   $verify_announcement = $conn->prepare("SELECT * FROM `Announcements` WHERE announcement_id = ? AND tutor_id = ? LIMIT 1");
   $verify_announcement->execute([$delete_id, $tutor_id]);
   if($verify_announcement->rowCount() > 0){
      // Fetch announcement data
      $fetch_announcement = $verify_announcement->fetch(PDO::FETCH_ASSOC);
      // Delete announcement from database
      $delete_announcement = $conn->prepare("DELETE FROM `Announcements` WHERE announcement_id = ?");
      $delete_announcement->execute([$delete_id]);
      $message[] = 'Announcement deleted!';
   }else{
      $message[] = 'Announcement not found or unauthorized!';
   }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/teacher_style.css">

</head>
<body>

<?php include '../components/teacher_header.php'; ?>
   
<section class="contents">

   <!-- Display messages -->
   <?php if(isset($message)): ?>
      <div class="message">
         <?php foreach($message as $msg): ?>
            <p><?php echo $msg; ?></p>
         <?php endforeach; ?>
      </div>
   <?php endif; ?>

   <!-- Form to add new announcement -->
   <section class="contents">

<h1 class="heading">your announcements </h1>

<div class="box-container">

<div class="box" style="text-align: center;">
   <h3 class="title" style="margin-bottom: .5rem;">create new announcement</h3>
   <a href="add_annoucements.php" class="btn">add announcement</a>
</div>

   <!-- Display existing announcements -->
   <div class="box-container">

      <?php
         // Fetch and display announcements
         $select_announcements = $conn->prepare("SELECT * FROM `Announcements` WHERE tutor_id = ? ORDER BY created_at DESC");
         $select_announcements->execute([$tutor_id]);
         if($select_announcements->rowCount() > 0){
            while($announcement = $select_announcements->fetch(PDO::FETCH_ASSOC)){ 
               $announcement_id = $announcement['announcement_id'];
      ?>
            <div class="box">
               <div class="flex">
                  <div>
                     <i class="fas fa-dot-circle" style="<?= ($announcement['status'] == 'active') ? 'color: limegreen;' : 'color: red;' ?>"></i>
                     <span style="<?= ($announcement['status'] == 'active') ? 'color: limegreen;' : 'color: red;' ?>"><?= $announcement['status']; ?></span>
                  </div>
                  <div>
                     <i class="fas fa-calendar"></i>
                     <span><?= $announcement['created_at']; ?></span>
                  </div>
               </div>
               <h3 class="title"><?= $announcement['title']; ?></h3>
               <p><?= $announcement['content']; ?></p>
               <form action="" method="post" class="flex-btn">
                  <input type="hidden" name="announcement_id" value="<?= $announcement_id; ?>">
                  <a href="update_announcement.php?get_id=<?= $announcement_id; ?>" class="option-btn">Update</a>
                  <input type="submit" value="Delete" class="delete-btn" onclick="return confirm('Delete this announcement?');" name="delete_announcement">
               </form>
               <a href="view_announcement.php?get_id=<?= $announcement_id; ?>" class="btn">View Announcement</a>
            </div>
         <?php
            }
         }else{
            echo '<p class="empty">no announcements added yet!</p>';
         }
      ?>

   </div>

</section>

<script src="../js/teacher_script.js"></script>

</body>
</html>



