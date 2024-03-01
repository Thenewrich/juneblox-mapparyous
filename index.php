<?php
  include("SiT_3/config.php");
  include("SiT_3/head.php");

  if($loggedIn) {header("Location: /dashboard"); die();}
     
       ?>
<?php
$fetch_users = $conn->query("SELECT * FROM beta_users");
$total_users = mysqli_num_rows($fetch_users);



?>
<head>    
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $sitename; ?></title>
  </head>
<div class="container">
    <div class="loginform">
                   <center style="font-size: 9px;"><p>            <img style="width:70px;vertical-align: middle;" src="/gtorialogo.png">
</p></center>

              <center style="font-size: 9px;"><p>Total Users: <?php echo $total_users ?></p></center>

                 <center style="font-size: 9px;"><p>Don't have an account? <a href="/register/">Sign up</a></p></center>

        </div>
    <div class="rightcontainer">
      <h1 style="margin-top: 0px;">
          Graphictoria
      </h1>
      <ul id="Bullets">
        <li id="Bullet1">
          <h3>Build your personal Place</h3>
          <div>Create buildings, vehicles, scenery, and traps with thousands of virtual bricks.</div>
        </li>
        <li id="Bullet2">
          <h3>Meet new friends</h3>
          <div>Visit your friend's place, chat in 3D, and build together.</div>
        </li>
        <li id="Bullet3">
          <h3>Customize your character.</h3>
          <div>Adjust the look of your player, by buying hats from the catalog, uploading shirts, and changing the colors of your body parts!</div>
        </li>
      </ul>
    </div>
  </div>
   <div class="containergrey" style="margin-top: -160px;">
    Copyright © Graphictoria 2024.
  </div>

 