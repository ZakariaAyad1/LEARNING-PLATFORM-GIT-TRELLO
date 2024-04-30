<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:dashboard.php');
}

if(isset($_POST['update'])){

   $announcement_id = $_POST['announcement_id'];
   $announcement_id = filter_var($announcement_id, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_STRING);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);

   $update_announcement = $conn->prepare("UPDATE `Announcements` SET title = ?, content = ?, status = ? WHERE announcement_id = ?");
   $update_announcement->execute([$title, $content, $status, $announcement_id]);

   $message[] = 'Announcement updated!';

}

if(isset($_POST['delete_announcement'])){

   $delete_id = $_POST['announcement_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $delete_announcement = $conn->prepare("DELETE FROM `Announcements` WHERE announcement_id = ?");
   $delete_announcement->execute([$delete_id]);
   header('location:dashboard.php');
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Announcement</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/teacher_style.css">

</head>
<body>

<?php include '../components/teacher_header.php'; ?>
   
<section class="video-form">

   <h1 class="heading">Update Announcement</h1>

   <?php
      $select_announcement = $conn->prepare("SELECT * FROM `Announcements` WHERE announcement_id = ? AND tutor_id = ?");
      $select_announcement->execute([$get_id, $tutor_id]);
      if($select_announcement->rowCount() > 0){
         while($fetch_announcement = $select_announcement->fetch(PDO::FETCH_ASSOC)){ 
            $announcement_id = $fetch_announcement['announcement_id'];
   ?>
   <form action="" method="post">
      <input type="hidden" name="announcement_id" value="<?= $announcement_id; ?>">
      <p>Update Status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fetch_announcement['status']; ?>" selected><?= $fetch_announcement['status']; ?></option>
         <option value="active">Active</option>
         <option value="deactive">Deactive</option>
      </select>
      <p>Update Title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Enter announcement title" class="box" value="<?= $fetch_announcement['title']; ?>">
      <p>Update Content <span>*</span></p>
      <textarea name="content" class="box" required placeholder="Write announcement content" maxlength="1000" cols="30" rows="10"><?= $fetch_announcement['content']; ?></textarea>
      <input type="submit" value="Update Announcement" name="update" class="btn">
      <input type="submit" value="Delete Announcement" name="delete_announcement" class="delete-btn">
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">Announcement not found!</p>';
      }
   ?>

</section>

</body>
</html>
