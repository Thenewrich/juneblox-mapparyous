<?php
  include('SiT_3/config.php');
  include('SiT_3/header.php');
  
  if(!$loggedIn) {header("Location: index");}
  
  $error = array();
  if($power >= 1) {
    if(isset($_GET['id'])) {
      if(isset($_POST['submit'])) {
        if(isset($_POST['note']) && strlen($_POST['note']) > 1) {
          if(isset($_POST['length']) && $_POST['length'] >= 0 && $_POST['length'] != null) {
            $user = mysqli_real_escape_string($conn,$_GET['id']);
            $admin = $_SESSION['id'];
            $note = str_replace("'","\'",$_POST['note']);
            $reason = str_replace("'","\'",$_POST['reason']);
            $length = mysqli_real_escape_string($conn,$_POST['length']);
            $banSQL = "INSERT INTO `moderation` (`id`,`user_id`,`admin_id`,`reason`, `admin_note`,`issued`,`length`,`active`) VALUES (NULL ,'$user','$admin','$reason','$note', '$curDate','$length','yes')";
            $ban = $conn->query($banSQL);
            
            $action = 'Banned user'.$user.' for '.$length.' minutes';
            $date = date('d-m-Y H:i:s');
            $adminSQL = "INSERT INTO `admin` (`id`,`admin_id`,`action`,`time`) VALUES (NULL ,  '$admin',  '$action',  '$date')";
            $admin = $conn->query($adminSQL);
            
            header("location: index");
          } else {
            $error[] = "Invalid ban length";
          }
        } else {
          $error[] = "Please include a moderator note";
        }
      }
    } else {
      $error[] = 'Invalid user ID';
    }
  } else {
    header("location: index");
  }

?>
<!DOCTYPE html>
  <head>
    <title>Ban User - <?php echo $sitename; ?></title>
  </head>
  <body>
    <div id="body">
      <div id="box" style="padding:10px;">
        <?php
        if(!empty($error)) {
          echo '<div style="background-color:#EE3333;margin:10px;padding:5px;color:white;">';
          foreach($error as $errno) {
            echo $errno."<br>";
          } 
          echo '</div>';
        }
        if(isset($_POST['submit']) && empty($error)) {
          echo '<h3>User has been banned</h3>';
        }
        ?>
        <!--form action="" method="POST">
          Ban user <?php echo $_GET['id']; ?><br>
          <label>Moderator Note:</label><br>
          <textarea name="note" style="width:300px;height:140px;"></textarea>
          <br>
          <label>Ban Length (Minutes):</label>
          <input type="text" name="length"><br>
          <input type="submit" name="submit" value="Ban User">
        </form>
      </div>
    </div>-->
 <div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="card">
<div class="top blue">
Ban User
</div>
<div class="content">
<span class="darker-grey-text bold block">This user will be banned once you fill out the information below.</span>
<form method="POST" action="">
<input type="hidden" name="_token" value="Vr17QfpHCqB7xnEvUkl5tUmtijC8w91k4QnwicNj"> <input type="hidden" name="reportable_type" value="1">
<input type="hidden" name="reportable_id" value="357996">
<select name="length">
<option value="0">Warning</option>
<option value="4320">3 Days</option>
<option value="10080">1 Week</option>
<option value="20160">2 Weeks</option>
<option value="2147483647">Termination</option>
</select>
  <select name="reason">
<option value="Excessive or inappropriate use of profanity">Excessive or inappropriate use of profanity</option>
<option value="Inappropriate/adult content">Inappropriate/adult content</option>
<option value="Requesting or giving private information">Requesting or giving private information</option>
<option value="Engaging in third party/offsite deals">Engaging in third party/offsite deals</option>
<option value="Harassing/bullying other users">Harassing/bullying other users</option>
<option value="Exploiting/scamming other users">Exploiting/scamming other users</option>
<option value="Stolen account">Stolen account</option>
<option value="Phishing/hacking/trading accounts">Phishing/hacking/trading accounts</option>
<option value="Other">Other</option>
</select>
<textarea name="note" style="width:100%;box-sizing:border-box;margin-top:10px;height:100px;" placeholder="Mod note"></textarea>
<button type="submit" name="submit" class="red">BAN</button>
</form>
</div>
</div>
</div>
   </div></div></div></div>
   <?php
  include('SiT_3/footer.php');
  ?>