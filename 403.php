<?php
include('SiT_3/config.php');
include('SiT_3/head.php');
?>

<!DOCTYPE html>
  <head>
    <title>Forbidden - <?php echo $sitename; ?></title>
  </head>

 <div class="main-holder grid">
<div style="text-align:center;padding-top:50px;">
<span style="font-weight:600;font-size:3rem;display:block;">Error 403: Forbidden</span>
  <hr>
  <?php
        $users = array(1,2,3,4);
        shuffle($users);
        foreach($users as $u) {
          echo '<img style="height: 250px;" src="/avatar/render/avatars/'.$u.'.png">';
        }
        ?>
</div>
</div>
    <?php
    include('SiT_3/footer.php');
    ?>
  </body>
</html>
