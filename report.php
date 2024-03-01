<?php
include('SiT_3/config.php');
include('SiT_3/header.php');

if(!$loggedIn) {header("Location: index"); die();}

$error = array();
$reportTypes = array('post','thread','user','game','clan','item','message');
if(isset($_POST['report'])) {
  if(isset($_POST['type']) && isset($_POST['id']) && isset($_POST['reason'])) {
    $type = mysqli_real_escape_string($conn,$_POST['type']);
    if(!in_array($type, $reportTypes)) {
      $error[] = "Invalid report type";
    } else {
      $id = mysqli_real_escape_string($conn,intval($_POST['id']));
      $reason = mysqli_real_escape_string($conn,$_POST['reason']);
      $userID = $_SESSION['id'];
      $reportSQL = "INSERT INTO  `reports` (`id`,`user_id`,`r_type`,`r_id`,`r_reason`,`seen`) VALUES (NULL,'$userID','$type','$id','$reason','no')";
      $report = $conn->query($reportSQL);
    }
  } else {
    $error[] = "Invalid report data";
  }
} else {
  if(!(isset($_GET['type']) && isset($_GET['id']))) {
    $error[] = "Invalid report data";
  } else {
    $r_type = mysqli_real_escape_string($conn,$_GET['type']);
    $r_id = mysqli_real_escape_string($conn,intval($_GET['id']));
    if(!in_array($r_type, $reportTypes)) {
      $error[] = "Invalid report type";
    }
  }
}

?>

<!DOCTYPE html>
  <head>
    <title>Report - <?php echo $sitename; ?></title>
  </head>
  <body>
    <div id="body">
      <div id="box">
        <?php
        if(!isset($_POST['report'])) {
          if(!empty($error)) {
            echo '<div style="background-color:#EE3333;margin:10px;padding:5px;color:white;">';
            foreach($error as $errno) {
              echo $errno."<br>";
            } 
            echo '</div>';
          } else {
          /*echo '<div style="padding:10px;">
          <h3>Report</h3>
          <form action="report" method="POST">
            <input type="hidden" name="type" value="'.$r_type.'">
            <input type="hidden" name="id" value="'.$r_id.'">
            <h5>Reason:</h5>
            <textarea name="reason" style="margin:5px;width:310px;height:150px;"></textarea><br>
            <input type="submit" name="report" style="margin:5px;">
          </form>
          </div>';*/
            echo'
            <div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="card">
<div class="top blue">
Report
</div>
<div class="content">
<span class="darker-grey-text bold block">Tell us how you think this is breaking the '.$sitename.' rules.</span>
<form method="POST" action="/report/send">
<input type="hidden" name="_token" value="Vr17QfpHCqB7xnEvUkl5tUmtijC8w91k4QnwicNj"> <input type="hidden" name="reportable_type" value="1">
<input type="hidden" name="reportable_id" value="357996">
<!--<select name="reason">
<option value="1">Excessive or inappropriate use of profanity</option>
<option value="2">Inappropriate/adult content</option>
<option value="3">Requesting or giving private information</option>
<option value="4">Engaging in third party/offsite deals</option>
<option value="5">Harassing/bullying other users</option>
<option value="6">Exploiting/scamming other users</option>
<option value="7">Stolen account</option>
<option value="8">Phishing/hacking/trading accounts</option>
<option value="9">Other</option>
</select>-->
<textarea name="reason" style="width:100%;box-sizing:border-box;margin-top:10px;height:100px;" placeholder="Reason"></textarea>
<button type="submit" name="report" class="blue">SUBMIT</button>
</form>
</div>
</div>
</div>';
            }
        } else {
          echo '<div style="padding:10px;">
          <h3>Your report will be reviewed shortly</h3>
          <h5>Thank you for keeping this community safe</h5>
          </div>';
        }
        ?>
      </div>
    </div>
  </body>
<?php
include('SiT_3/footer.php');
  ?>