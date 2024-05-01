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
        <p>Announcement status <span>*</span></p>
            <select name="status" class="box" required>
                <option value="" selected disabled>Status</option>
                <option value="active">Active</option>
                <option value="deactive">Desactive</option>
            </select>
            <p>Announcement Title <span>*</span></p>
            <input type="text" name="title" required placeholder="Enter announcement title" class="box">
            <p>Announcement Content <span>*</span></p>
            <textarea name="content" required placeholder="Write announcement content" class="box" cols="30"
                rows="10"></textarea>
            <input type="submit" value="Add Announcement" name="add_announcement" class="btn">
        </form>

    </section>

    <script src="../js/teacher_script.js"></script>

</body>

</html>
