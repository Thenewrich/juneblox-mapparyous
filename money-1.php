<?php
  include('SiT_3/header.php');
  
  if(!$loggedIn) {header("Location: /"); die();}
  
  $error = array();
  if(isset($_POST['submit']) && isset($_POST['currency']) && isset($_POST['value'])) {
    $currency = mysqli_real_escape_string($conn,$_POST['currency']);
    $value = mysqli_real_escape_string($conn,$_POST['value']);
    
    if($value > 0) {
      $userID = $_SESSION['id'];
      
      $currentBits = $userRow->{'bits'};
      $currentBucks = $userRow->{'bucks'};
      
      if($currency == 'toBits') {
        $newBits = $currentBits+10*$value;
        $newBucks = $currentBucks-$value;
        if($newBucks >= 0 && $newBits >= 0) {
          $convertSQL = "UPDATE `beta_users` SET `bucks`='$newBucks', `bits`='$newBits' WHERE `id`='$userID'";
          $convert = $conn->query($convertSQL);
          header("Location: money");
        } else {
          $error[] = "Insufficient bucks!";
        }
      }
      elseif($currency == 'toBucks') {
        if($value/10 == (int)($value/10)) {
          $newBits = $currentBits-$value;
          $newBucks = $currentBucks+(int)($value/10);
          if($newBucks >= 0 && $newBits >= 0) {
            $convertSQL = "UPDATE `beta_users` SET `bucks`='$newBucks', `bits`='$newBits' WHERE `id`='$userID'";
            $convert = $conn->query($convertSQL);
            header("Location: money");
          } else {
            $error[] = "Insufficient bits!";
          }
        } else {
          $error[] = "Value must be divisible by 10";
        }
      } else {$error[] = "Invalid currency";}
    } else {
      $error[] = "Amount must be greater than 0";
    }
  }



  
  if(isset($_GET['page'])) {$page = mysqli_real_escape_string($conn,intval($_GET['page']));}
  $page = max($page,1);
  $limit = ($page-1)*20;
  
  $mID = $_SESSION['id'];
  $sqlSearch = "SELECT * FROM `crate` WHERE  `user_id` = '$mID' AND `own` = 'yes' ORDER BY `id` DESC LIMIT $limit,20";
  $result = $conn->query($sqlSearch);



?>

<!DOCTYPE html>
  <head>
    <title>Currency - <?php echo $sitename;?></title>
  </head>
  <body>
    <div id="body">
      <div class="col-10-12 push-1-12">

<div class="tabs">
<div class="tab active col-3-12" data-tab="1">
Exchange
</div>
<div class="tab col-3-12" data-tab="2">
Transactions
</div>
<div class="tab col-3-12" data-tab="3">
Items on sale
</div>
<div class="tab col-3-12" id="buyrequests" data-tab="4">
Buy requests
</div>
<div class="tab-holder" style="box-shadow:none;">
<div class="tab-body active" data-tab="1" style="text-align:center;">
  
<form method="POST" action="">
  
<input type="hidden" name="_token" value="VC2BK8vh2XxPmm22JTETMfRNlFPPetIllv6ShvCD"> <div class="block">
          <?php
        if(!empty($error)) {
          echo '<div style="background-color:#EE3333;margin:10px;padding:5px;color:white;">';
          foreach($error as $errno) {
            echo $errno."<br>";
          }
          echo '</div>';
        }
        ?>
<select name="type" class="select mb2">
<option value="toBits">To bits</option>
<option value="toBucks">To bucks</option>
</select>
<br>
 Amount of Currency you want to convert:<br><br> <input type="number" name="value" value="" style="margin-bottom:10px;"></div>
<input type="submit" name="submit" class="blue smaller-text" value="CONVERT">
</form>
</div><?php

            while($messageRow=$result->fetch_assoc()) {
if($messageRow['item_id'] > "1") {
              ?>
<div class="tab-body" data-tab="2" id="transactions">
<vue-comp id="transactions-v" data-v-app=""><div>
  <select name="type" class="select width-100 small-padding" style="margin-bottom: 10px; height: 30px;">
    <option value="purchases">Purchases</option>
    <option value="sales">Sales</option>
  </select><table style="width: 100%;"><tr>
  <th class="col-2-12" style="text-align: left; float: none;">
    <span class="agray-text block" title="2020-09-16T11:05:05.000000Z">16/09/2020</span>
  </th><th class="ellipsis col-4-12" style="text-align: left; float: none;">
  <a class="agray-text ellipsis" href="/user/1/">
    <img src="/avatar/render/avatars/1.png" style="width: 64px;">
    <div>Epic</div></a></th>
  <th class="col-4-12" style="text-align: left; float: none;">
    <a class="agray-text" href="/shop/<?php echo $messageRow['item_id']; ?>/">
      <img src="/shop/thumbnails/<?php echo $messageRow['item_id']; ?>.png" alt="Umbrella Hat" style="height: 56px;">
      <div>Umbrella Hat</div></a></th><th class="col-2-12" style="text-align: left; float: none;">
  <span class="bits-text"><?php if($messageRow['serial'] == "-1") {echo 'redeemed by promocode';} else { echo $messageRow['payment']; echo " : "; echo $messageRow['price'];} ?><span class="bits-icon"></span>
  </span></th></tr></table>
  <div style="text-align: center;">
  <button class="blue">LOAD MORE</button>
  </div>
  </div>
  </vue-comp>
  
          
          
          
        </tbody>
      </table>
      
      <?php
      echo '</div><div class="numButtonsHolder">';
      
      if($count/20 > 1) {
        for($i = 0; $i < ($count/20); $i++)
        {
          echo '<a href="?page='.$i.'">'.($i+1).'</a> ';
        }
      }
      }
      echo '</div>';
      ?>

    </div>
</div>
<div class="tab-body" data-tab="3">
<p>You have no items on sale.</p>
</div>
<div class="tab-body" data-tab="4">
<p>You have no buy requests.</p>
</div>
</div>
</div>
</div>
      
      
  </body>
</html>



<?php
include("SiT_3/footer.php");
}?>