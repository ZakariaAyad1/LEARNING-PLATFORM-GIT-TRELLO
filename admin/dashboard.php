<?php
include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

// Fetch teacher's content
$select_teacher_content = $conn->prepare("SELECT * FROM content WHERE tutor_id = ?");
$select_teacher_content->execute([$tutor_id]);
$teacher_content = $select_teacher_content->fetchAll();

// Fetch users
$select_users = $conn->query("SELECT * FROM users");
$users = $select_users->fetchAll();

// Fetch tutors
$select_tutors = $conn->query("SELECT * FROM tutors");
$tutors = $select_tutors->fetchAll();

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
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="dashboard">

   <h1 class="heading">Tableau de bord</h1>

   <div class="box-container">

      <div class="box">
         <h2>Tutors</h2>
         <table>
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Password</th>
                  <th>Image</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($tutors as $tutor) { ?>
                  <tr>
                     <td><?php echo $tutor['id']; ?></td>
                     <td><?php echo $tutor['name']; ?></td>
                     <td><?php echo $tutor['email']; ?></td>
                     <td><?php echo $tutor['password']; ?></td>
                     <td class="image-cell"><img src="<?php echo $tutor['image']; ?>" alt="Tutor Image"></td>
                  </tr>
               <?php } ?>
            </tbody>
         </table>
      </div>

      <div class="box1">
         <h2>Users</h2>
         <table>
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Password</th>
                  <th>Image</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($users as $user) { ?>
                  <tr>
                     <td><?php echo $user['id']; ?></td>
                     <td><?php echo $user['name']; ?></td>
                     <td><?php echo $user['email']; ?></td>
                     <td><?php echo $user['password']; ?></td>
                     <td class="image-cell"><img src="<?php echo $user['image']; ?>" alt="User Image"></td>
                  </tr>
               <?php } ?>
            </tbody>
         </table>
      </div>

   </div>
   
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>
