<?php
// reply_comment.php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_POST['reply_comment'])){
   $comment_id = $_POST['comment_id'];
   $reply_content = $_POST['reply'];

   // Insert the reply into the database
   $insert_reply = $conn->prepare("INSERT INTO `comment_replies` (comment_id, tutor_id, reply_content) VALUES (?, ?, ?)");
   $insert_reply->execute([$comment_id, $tutor_id, $reply_content]);

   // Redirect back to the page after replying
   header('Location: view_content.php');
   exit();
}
?>
