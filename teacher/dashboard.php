<?php
include '../components/connect.php';

if (!isset($_COOKIE['tutor_id'])) {
    header('location:login.php');
    exit();
}

$tutor_id = $_COOKIE['tutor_id'];

// Prepared statement to avoid SQL injection, for PDO
$stmt = $conn->prepare("SELECT u.name, u.email, p.title AS playlist_title, c.comment FROM users u
                        INNER JOIN bookmark b ON u.id = b.user_id
                        INNER JOIN playlist p ON b.playlist_id = p.id
                        LEFT JOIN comments c ON u.id = c.user_id AND b.playlist_id = c.content_id
                        WHERE p.tutor_id = ?");
$stmt->bindParam(1, $tutor_id);
$stmt->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../css/teacher_style.css">
</head>
<body>
    <?php include '../components/teacher_header.php'; ?>
    <section class="dashboard">
        <h1 class="heading">Subscriptions</h1>
        <div class="box-container">
            <div class="box">
                <table>
                    <thead>
                        <tr>
                            <th>Student's name</th>
                            <th>Email</th>
                            <th>Playlist's name</th>
                            <th>Student's questions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['playlist_title']) ?></td>
                            <td><?= htmlspecialchars($user['comment']) ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <script src="../js/teacher_script.js"></script>
</body>
</html>
