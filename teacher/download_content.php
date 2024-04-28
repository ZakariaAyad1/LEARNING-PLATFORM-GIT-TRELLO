<?php
// download_content.php

include '../components/connect.php';

if(isset($_POST['content_id'])){
    $content_id = $_POST['content_id'];
    
    // Fetch content details from the database
    $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
    $select_content->execute([$content_id]);
    $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);

    // File path of the content to be downloaded
    $file_path = "../uploaded_files/" . $fetch_content['video'];

    // Set headers for file download
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    header('Content-Length: ' . filesize($file_path));

    // Output file for download
    readfile($file_path);
    exit();
} else {
    // Redirect back if content_id is not provided
    header('Location: contents.php');
    exit();
}
?>
