<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_POST['submit'])){

   $id = unique_id();
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
   $playlist = $_POST['playlist'];
   $playlist = filter_var($playlist, FILTER_SANITIZE_STRING);

   $thumb = $_FILES['thumb']['name'];
   $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
   $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
   $rename_thumb = unique_id().'.'.$thumb_ext;
   $thumb_size = $_FILES['thumb']['size'];
   $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
   $thumb_folder = '../uploaded_files/'.$rename_thumb;

   $video = $_FILES['video']['name'];
   $video = filter_var($video, FILTER_SANITIZE_STRING);
   $video_ext = pathinfo($video, PATHINFO_EXTENSION);
   $rename_video = unique_id().'.'.$video_ext;
   $video_tmp_name = $_FILES['video']['tmp_name'];
   $video_folder = '../uploaded_files/'.$rename_video;

   // Check if the uploaded file is of allowed type
   // No need for file type restriction now
   $thumb_uploaded = move_uploaded_file($thumb_tmp_name, $thumb_folder);
   $video_uploaded = move_uploaded_file($video_tmp_name, $video_folder);
   
   if ($status !== '' && $title !== '') {
       $add_playlist = $conn->prepare("INSERT INTO `content`(id, tutor_id, playlist_id, title, description, video, thumb, status) VALUES(?,?,?,?,?,?,?,?)");
       $add_playlist->execute([$id, $tutor_id, $playlist, $title, $description, $rename_video, $rename_thumb, $status]);
       $message[] = 'New course uploaded!';
   } else {
       $message[] = 'Please fill in the required fields: Video Status and Video Title.';
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


    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/teacher_style.css">

</head>

<body>

    <?php include '../components/teacher_header.php'; ?>

    <section class="video-form">

        <h1 class="heading">upload content</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <p>video status <span>*</span></p>
            <select name="status" class="box" required>
                <option value="" selected disabled>Status</option>
                <option value="active">Active</option>
                <option value="deactive">Desactive</option>
            </select>
            <p>video title <span>*</span></p>
            <input type="text" name="title" maxlength="100" required placeholder="enter video title" class="box">
            <p>video description</p>
            <textarea name="description" class="box" placeholder="write description" maxlength="1000" cols="30"
                rows="10"></textarea>
            <p>video playlist</p>
            <select name="playlist" class="box">
                <option value="" disabled selected>select playlist</option>
                <?php
         $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
         $select_playlists->execute([$tutor_id]);
         if($select_playlists->rowCount() > 0){
            while($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)){
         ?>
                <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
                <?php
            }
         ?>
                <?php
         }else{
            echo '<option value="" disabled>no playlist created yet!</option>';
         }
         ?>
            </select>
            <p>select picture</p>
            <input type="file" name="thumb" accept="image/*" class="box">
            <p>select a document (pdf/ppt/pdf/else)</p>
            <input type="file" name="video" accept="*" class="box">
            <input type="submit" value="upload document" name="submit" class="btn">
        </form>

    </section>

    <script src="../js/teacher_script.js"></script>

</body>

</html>
