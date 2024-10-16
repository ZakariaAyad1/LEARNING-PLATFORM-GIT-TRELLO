<?php

include 'components/connect.php';

// Function to get file extension
function getFileExtension($filename) {
   return pathinfo($filename, PATHINFO_EXTENSION);
}

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:home.php');
}

if(isset($_POST['like_content'])){

   if($user_id != ''){

      $content_id = $_POST['content_id'];
      $content_id = filter_var($content_id, FILTER_SANITIZE_STRING);

      $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $select_content->execute([$content_id]);
      $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);

      $tutor_id = $fetch_content['tutor_id'];

      $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND content_id = ?");
      $select_likes->execute([$user_id, $content_id]);

      if($select_likes->rowCount() > 0){
         $remove_likes = $conn->prepare("DELETE FROM `likes` WHERE user_id = ? AND content_id = ?");
         $remove_likes->execute([$user_id, $content_id]);
         $message[] = 'removed from likes!';
      }else{
         $insert_likes = $conn->prepare("INSERT INTO `likes`(user_id, tutor_id, content_id) VALUES(?,?,?)");
         $insert_likes->execute([$user_id, $tutor_id, $content_id]);
         $message[] = 'added to likes!';
      }

   }else{
      $message[] = 'please login first!';
   }

}

if(isset($_POST['add_comment'])){

   if($user_id != ''){

      $id = unique_id();
      $comment_box = $_POST['comment_box'];
      $comment_box = filter_var($comment_box, FILTER_SANITIZE_STRING);
      $content_id = $_POST['content_id'];
      $content_id = filter_var($content_id, FILTER_SANITIZE_STRING);

      $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $select_content->execute([$content_id]);
      $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);

      $tutor_id = $fetch_content['tutor_id'];

      if($select_content->rowCount() > 0){

         $insert_comment = $conn->prepare("INSERT INTO `comments`(id, content_id, user_id, tutor_id, comment) VALUES(?,?,?,?,?)");
         $insert_comment->execute([$id, $content_id, $user_id, $tutor_id, $comment_box]);
         $message[] = 'new comment added!';

      }else{
         $message[] = 'something went wrong!';
      }

   }else{
      $message[] = 'please login first!';
   }

}

if(isset($_POST['delete_comment'])){

   $delete_id = $_POST['comment_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ? AND user_id = ?");
   $verify_comment->execute([$delete_id, $user_id]);

   if($verify_comment->rowCount() > 0){
      $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
      $delete_comment->execute([$delete_id]);
      $message[] = 'comment deleted successfully!';
   }else{
      $message[] = 'comment not found or you are not authorized to delete it!';
   }

}

if(isset($_POST['edit_comment'])){

   $edit_id = $_POST['comment_id'];
   $edit_id = filter_var($edit_id, FILTER_SANITIZE_STRING);

   $select_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ? AND user_id = ?");
   $select_comment->execute([$edit_id, $user_id]);
   $fetch_comment = $select_comment->fetch(PDO::FETCH_ASSOC);

   if($select_comment->rowCount() > 0){
      // Here you can include HTML and PHP code for editing the comment, and update it accordingly
      $message[] = 'Edit your comment here!';
   }else{
      $message[] = 'Comment not found or you are not authorized to edit it!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch Video</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">
    <style>
    /* CSS for enlarging and centering the file viewer */

    .watch-video {
        display: flex;
        flex-direction: column-reverse;
        justify-content: center;
        align-items: center;
        height: 80vh;
        /* Adjust the height as needed */
    }

    .watch-video p {
        font-weight: 600;
        color: wheat;
        border: 5px double wheat;
        border-radius: 20px 0 20px 0;
        margin: 10px 0 10px 0;
        padding: 10px;
        text-align: center;

    }

    .watch-video span {
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        text-decoration: underline;
        text-transform: capitalize;
    }

    /* Adjustments for the file viewer */
    .video,
    .audio {
        max-width: 100%;
        max-height: 100%;
    }

    .pdf-viewer {
        width: 100%;
        height: 100%;
    }
    </style>
</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <!-- Watch Video section starts -->
    <section class="watch-video">
        <?php
      $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? AND status = ?");
      $select_content->execute([$get_id, 'active']);
      if($select_content->rowCount() > 0){
         while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){
            $content_id = $fetch_content['id'];

            // Display prerequisites
            echo "<p> <span>Prerequisites:</span> {$fetch_content['prerequisites']}</p>";

            $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE content_id = ?");
            $select_likes->execute([$content_id]);
            $total_likes = $select_likes->rowCount();  

            $verify_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND content_id = ?");
            $verify_likes->execute([$user_id, $content_id]);

            $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? LIMIT 1");
            $select_tutor->execute([$fetch_content['tutor_id']]);
            $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

            // Get file extension
            $file_extension = getFileExtension($fetch_content['video']);

            // Check file extension and display appropriate viewer
            if ($file_extension === 'pdf') {
               // Display PDF viewer
               echo '<embed src="uploaded_files/' . $fetch_content['video'] . '" type="application/pdf" class="pdf-viewer">';
            } elseif (in_array($file_extension, ['mp4', 'webm', 'ogg'])) {
               // Display video player for supported video formats
               echo '<video src="uploaded_files/' . $fetch_content['video'] . '" class="video" poster="uploaded_files/' . $fetch_content['thumb'] . '" controls autoplay></video>';
            } elseif ($file_extension === 'mp3') {
               // Display audio player for MP3 files
               echo '<audio controls class="audio"><source src="uploaded_files/' . $fetch_content['video'] . '" type="audio/mpeg">Your browser does not support the audio element.</audio>';
            } else {
               // Display an error message for unsupported file types
               echo '<p>Unsupported file type.</p>';
            }
   ?>
        <!-- Remaining HTML for displaying other content details -->
        <!-- Add the like, comment functionality, tutor info, etc. -->
        <?php
         }
      } else {
         echo '<p class="empty">No videos added yet!</p>';
      }
   ?>
    </section>
    <!-- Watch Video section ends -->

    <!-- Comments section starts -->
    <section class="comments">
        <h1 class="heading">Add a Comment</h1>
        <form action="" method="post" class="add-comment">
            <input type="hidden" name="content_id" value="<?= $get_id; ?>">
            <textarea name="comment_box" required placeholder="Write your comment..." maxlength="1000" cols="30"
                rows="10"></textarea>
            <input type="submit" value="Add Comment" name="add_comment" class="inline-btn">
        </form>

        <h1 class="heading">User Comments</h1>

        <div class="show-comments">
            <?php
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ?");
         $select_comments->execute([$get_id]);
         if($select_comments->rowCount() > 0){
            while($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)){   
               $select_commentor = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
               $select_commentor->execute([$fetch_comment['user_id']]);
               $fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC);
      ?>
            <div class="box" style="<?php if($fetch_comment['user_id'] == $user_id){echo 'order:-1;';} ?>">
                <div class="user">
                    <img src="uploaded_files/<?= $fetch_commentor['image']; ?>" alt="">
                    <div>
                        <h3><?= $fetch_commentor['name']; ?></h3>
                        <span><?= $fetch_comment['date']; ?></span>
                    </div>
                </div>
                <p class="text"><?= $fetch_comment['comment']; ?></p>
                <?php
            if($fetch_comment['user_id'] == $user_id){ 
         ?>
                <form action="" method="post" class="flex-btn">
                    <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
                    <button type="submit" name="edit_comment" class="inline-option-btn">Edit Comment</button>
                    <button type="submit" name="delete_comment" class="inline-delete-btn"
                        onclick="return confirm('Delete this comment?');">Delete Comment</button>
                </form>
                <?php
            }
         ?>
                <!-- Add a reply form for each comment -->
                <form action="" method="post" class="add-reply">
                    <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
                    <textarea name="reply_box" required placeholder="Write your reply..." maxlength="100000" cols="305"
                        rows="58"></textarea>
                    <input type="submit" value="Add Reply" name="add_reply" class="inline-btn">
                </form>
                <!-- Display existing replies for each comment -->
                <div class="replies">
                    <?php
               // Retrieve and display replies for the current comment
               $select_replies = $conn->prepare("SELECT * FROM `comment_replies` WHERE comment_id = ?");
               $select_replies->execute([$fetch_comment['id']]);
               if($select_replies->rowCount() > 0){
                  while($fetch_reply = $select_replies->fetch(PDO::FETCH_ASSOC)){
                     // Display each reply
            ?>
                    <div class="reply">
                        <span><?= $fetch_reply['date']; ?></span>
                        <p><?= $fetch_reply['reply_content']; ?></p>
                    </div>
                    <?php
                  }
               } else {
                  echo '<p class="empty">No replies yet!</p>';
               }
            ?>
                </div>
            </div>
            <?php
            }
         }else{
            echo '<p class="empty">No comments added yet!</p>';
         }
      ?>
        </div>
    </section>
    <!-- Comments section ends -->

    <script src="js/script.js"></script>

</body>

</html>