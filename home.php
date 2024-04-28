<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
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
    <title>home</title>
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

    <!-- quick select section starts  -->

    <section class="quick-select">

        <h1 class="heading">quick options</h1>

        <div class="box-container">

            <?php
         if($user_id != ''){
      ?>
            <div class="box">
                <h3 class="title">likes and comments</h3>
                <p>total likes : <span><?= $total_likes; ?></span></p>
                <a href="likes.php" class="inline-btn">view likes</a>
                <p>total comments : <span><?= $total_comments; ?></span></p>
                <a href="comments.php" class="inline-btn">view comments</a>
                <p>saved playlist : <span><?= $total_bookmarked; ?></span></p>
                <a href="bookmark.php" class="inline-btn">view bookmark</a>
            </div>
            <?php
         }else{ 
      ?>
            <div class="box" style="text-align: center;">
                <h3 class="title">please login or register</h3>
                <div class="flex-btn" style="padding-top: .5rem;">
                    <a href="login.php" class="option-btn">login</a>
                    <a href="register.php" class="option-btn">register</a>
                </div>
            </div>
            <?php
      }
      ?>

            

            

            <div class="box tutor">
                <h3 class="title">become a tutor</h3>
                <p>become a teacher by creating an account an putting videos or pdfs or ppts, road vers l3alamia</p>
                <a href="teacher/register.php" class="inline-btn">get started</a>
            </div>
            <div class="box admin">
                <h1>if you are admin tap here </h3>
                <a href="admin/login.php" class="inline-btn">go to admin page </a>
            </div>
            <div class="box">
                <h3 class="title">Modules</h3>
                <div class="flex">
                    <a href="playlist.php?get_id=6gYI2q4qQjrQAOMdPQrH"><i class="fas fa-code"></i><span>Programmation web1</span></a>
                    <a href="#"><i class="fa-solid fa-gears"></i><span>Electronique Numérique</span></a>
                    <a href="#"><i class="fa-solid fa-c"></i><span>Programmation avancé</span></a>
                    <a href="#"><i class="fa-brands fa-contao"></i><span>Compilation & Theorie de Language</span></a>
                    <a href="#"><i class="fas fa-cog"></i><span>Système d'exploitation</span></a>
                    <a href="#"><i class="fa-solid fa-laptop-code"></i><span>Architecture des Ordinateurs</span></a>

                </div>
            </div>

        </div>

    </section>

    <!-- quick select section ends -->

    <!-- courses section starts  -->

    <section class="courses">

        <h1 class="heading">latest courses</h1>

        <div class="box-container">

            <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC LIMIT 6");
         $select_courses->execute(['active']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_course['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
            <div class="box">
                <div class="tutor">
                    <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
                    <div>
                        <h3><?= $fetch_tutor['name']; ?></h3>
                        <span><?= $fetch_course['date']; ?></span>
                    </div>
                </div>
                <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
                <h3 class="title"><?= $fetch_course['title']; ?></h3>
                <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">view playlist</a>
            </div>
            <?php
         }
      }else{
         echo '<p class="empty">no courses added yet!</p>';
      }
      ?>

        </div>

        <div class="more-btn">
            <a href="courses.php" class="inline-option-btn">view more</a>
        </div>

    </section>

    <!-- courses section ends -->














    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>

</html>