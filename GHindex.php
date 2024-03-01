   <?php
  include("SiT_3/config.php");
     include("SiT_3/header.php");

  //if($loggedIn) {header("Location: /dashboard"); die();}
     if($loggedIn) {
       ?>
<script>window.location = '/dashboard';</script>
<?
       }

 ?>
<?php
$fetch_users = $conn->query("SELECT * FROM beta_users");
$total_users = mysqli_num_rows($fetch_users);



?>

<html>
<head>
  <title>Welcome - <?php echo $sitename; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
</head>
<body>


<header class="landing-header">
<div class="header-content">
<div class="container">
<div class="row">
<div class="col-md-8 align-self-center text-center-sm">
<h1>The place to create.</h1>
<p><?php echo $sitename; ?> is an online 3D gaming platform where users can enable their creativity. Customize your character, create your own clothing, participate in a virtual economy, create groups, chat with others, and much more.</p>
<div class="buttons">
<a href="/register" class="btn btn-success"><i class="fas fa-user-plus mr-1"></i> Create Account</a>
<a href="/login" class="btn btn-warning"><i class="fas fa-key mr-1"></i> Existing User</a>
</div>
</div>
<div class="col-md-4 hide-sm">
<img src="/avatar/render/avatars/1.png">
</div>
</div>
</div>
</div>
</header>
  <?php
include('SiT_3/footer.php');
?>