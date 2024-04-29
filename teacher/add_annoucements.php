<?php
include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_POST['submit'])){
   $title = $_POST['title'];
   $content = $_POST['content'];

   // Sanitize input
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $content = filter_var($content, FILTER_SANITIZE_STRING);

   // Insert announcement into database
   $insert_announcement = $conn->prepare("INSERT INTO Announcements (tutor_id, title, content) VALUES (?, ?, ?)");
   $insert_announcement->execute([$tutor_id, $title, $content]);

   $message[] = 'Announcement added successfully!';
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

        <h1 class="heading">Add Announcement</h1>

        <form action="" method="post">
            <p>Announcement Title <span>*</span></p>
            <input type="text" name="title" required placeholder="Enter announcement title" class="box">
            <p>Announcement Content <span>*</span></p>
            <textarea name="content" required placeholder="Write announcement content" class="box" cols="30"
                rows="10"></textarea>
            <input type="submit" value="Add Announcement" name="submit" class="btn">
        </form>

    </section>

    <script src="../js/teacher_script.js"></script>

</body>

</html>
