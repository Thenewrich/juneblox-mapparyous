<?php
  include("SiT_3/config.php");
  include("SiT_3/header.php");
  
    
  $clanID = mysqli_real_escape_string($conn, intval($_GET['id']));
  $sqlClan = "SELECT * FROM `clans` WHERE `id`='$clanID'";
  $result = $conn->query($sqlClan);
  if($result->num_rows == 0) {
    header("Location: /clans/");
    die();
  }
  $clanRow = $result->fetch_assoc();
  
  if ($clanRow['id'] == 1 && $loggedIn) {
    echo '<script>
      console.log("Psst... Looking for eggs?");
      function eggMe() {
        $.post("clan?id='.$clanRow['id'].'", {eggMe: 1}, function(){console.log("Congratulations, you have found the Binary Egg!");});
      }
    </script>';
    
    if(isset($_POST['eggMe'])) {
      $userID = $_SESSION['id'];
      $itemID = 913;
      $checkSQL = "SELECT * FROM `crate` WHERE `item_id`='$itemID' AND `user_id`='$userID' AND `own`='yes'";
      $check = $conn->query($checkSQL);
      if($check->num_rows <= 0) {
        $serialSQL = "SELECT * FROM `crate` WHERE `item_id`='$itemID' ORDER BY `serial` DESC";
        $serialQ = $conn->query($serialSQL);
        $serialRow = $serialQ->fetch_assoc();
        $serial = $serialRow['serial']+1;
        
        $addSQL = "INSERT INTO `crate` (`id`,`user_id`,`item_id`,`serial`) VALUES (NULL,'$userID','$itemID','$serial')";
        $add = $conn->query($addSQL);
      }
    }
  }
    
  /*if ($clanRow['id'] == 4) {
    if(isset($_POST['egg'])) {
      if ($_POST['egg'] == 'iLoveEggs') {
        die('yay');
      }
    }
    ?>
    <script>
    eval(function(p,a,c,k,e,d){e=function(c){return c};if(!''.replace(/^/,String)){while(c--){d[c]=k[c]||c}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('0.1(\'2 3 4? :^)\');',5,5,'console|log|Looking|for|eggs'.split('|'),0,{}))
eval(function(p,a,c,k,e,d){e=function(c){return c.toString(36)};if(!''.replace(/^/,String)){while(c--){d[c.toString(a)]=k[c]||c.toString(a)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('0 5(){$.4(\'?\',{3:\'2\'},0(1){7(1==\'9\'){8(\'b 6 a\')}})}',12,12,'function|data|iLoveEggs|egg|post|eggMe|u|if|alert|yay|h4x3r|wow'.split('|'),0,{}))


// eggMe(); :^)
    </script>
    <?php
  }*/
    
  
  $memCountSQL = "SELECT * FROM `clans` WHERE `id`='$clanID';";
  $memCount = $conn->query($memCountSQL);
  $memRow = $memCount->fetch_assoc();
  $memCount = $memRow['members'];
  
  if($loggedIn) {$userID = $_SESSION['id'];} else {$userID = 0;}
  $checkSQL = "SELECT * FROM `clans_members` WHERE `user_id`='$userID' AND `group_id`='$clanID' AND `status`='in';";
  $check = $conn->query($checkSQL);
  $isIn = $check->num_rows;
  if($isIn) {
    $currentRow = $check->fetch_assoc();
    $currentPower = $currentRow['rank'];
    $currentRankSQL = "SELECT * FROM `clans_ranks` WHERE `group_id`='$clanID' AND `power`='$currentPower'";
    $currentRankQuery = $conn->query($currentRankSQL);
    $currentRank = $currentRankQuery->fetch_assoc();
  }
  
  if(isset($_POST['join']))
  { echo'<script>location.replace("../../clan/'.$clanRow['id'].'");</script>';
    $clansSQL = "SELECT * FROM `clans_members` WHERE `user_id` = '$userID' AND `status` = 'in'";
    $clansQ = $conn->query($clansSQL);
    $clans = $clansQ->num_rows;
    
    //if($clans < $membershipRow['join_clans']) {
      if(!$isIn && $userID != 0)
      {
        $joinSQL = "INSERT INTO `clans_members` (`id`,`group_id`,`user_id`,`rank`,`status`) VALUES (NULL ,'$clanID','$userID','1','in');";
        $join = $conn->query($joinSQL);
        
        $newCount = ($memCount+1);
         
        $newCountSQL = "UPDATE `clans` SET `members`='$newCount' WHERE `id`='$clanID';";
        $newCountR = $conn->query($newCountSQL);
      }
      else
      {
        echo "You're already in this clan";
      }
    } //else {
      //echo "You can only join up to 5 clans";
    //}
  //}
  if(isset($_POST['leave']))
  { echo'<script>location.replace("../../clan/'.$clanRow['id'].'");</script>';
    if($isIn) {
      $leaveSQL = "UPDATE `clans_members` SET `status`='out' WHERE `group_id`='$clanID' AND `user_id`='$userID'";
      $leave = $conn->query($leaveSQL);
      
      $newCount = ($memCount-1);
        
      $newCountSQL = "UPDATE `clans` SET `members`='$newCount' WHERE `id`='$clanID';";
      $newCountR = $conn->query($newCountSQL);
    }
  }
  if(isset($_POST['wall'])) {
    if($isIn)
    {
    $posted = str_replace("'","\'",$_POST['wall']);
    $postSQL = "INSERT INTO `clans_walls` (`id`,`group_id`,`owner_id`,`post`,`time`,`type`)VALUES (NULL ,  '$clanID',  '$userID',  '$posted',  '$curDate',  'normal');";
    $post = $conn->query($postSQL);
    }
  }
  if(isset($_GET['approve']) && $userRow->{'power'} >= 1) {
    $approveSQL = "UPDATE `clans` SET `approved`='yes' WHERE `id`='$clanID'";
    $approve = $conn->query($approveSQL);
  }
  if(isset($_GET['decline']) && $userRow->{'power'} >= 1) {
    $declineSQL = "UPDATE `clans` SET `approved`='declined' WHERE `id`='$clanID'";
    $decline = $conn->query($declineSQL);
  }
  if(isset($_GET['desc']) && $userRow->{'power'} >= 2) {
    $scrubSQL = "UPDATE `clans` SET `description`='[ Content Removed ]' WHERE `id`='$clanID'";
    $scrub = $conn->query($scrubSQL);
  }
  if(isset($_GET['title']) && $userRow->{'power'} >= 2) {
    $scrubSQL = "UPDATE `clans` SET `name`='[ Deleted $clanID ]' WHERE `id`='$clanID'";
    $scrub = $conn->query($scrubSQL);
  }
  if(isset($_GET['primary']) && $userRow->{'power'} >= 0) {
      $primarydestroySQL= "UPDATE `beta_users` SET `primary_group`='$clanID' WHERE `id`='$userID'";
      $primarydestroy = $conn->query($primarydestroySQL);
    echo"<script>location.replace('../clan/$clanID');</script>";
  }
  if(isset($_GET['unprimary']) && $userRow->{'power'} >= 0) {
      $primarydestroySQL= "UPDATE `beta_users` SET `primary_group`='0' WHERE `id`='$userID'";
      $primarydestroy = $conn->query($primarydestroySQL);
    echo"<script>location.replace('../clan/$clanID');</script>";
  }
  $ownerID = $clanRow['owner_id'];
  $clanOwnerSQL = "SELECT * FROM `beta_users` WHERE `id`='$ownerID';";
  $ownerResult = $conn->query($clanOwnerSQL);
  $ownerRow = $ownerResult->fetch_assoc();
  
  if ($clanRow['approved'] == 'yes') {$thumbnail = $clanRow['id'];}
  elseif ($clanRow['approved'] == 'declined') {$thumbnail = 'declined';}
  else {$thumbnail = 'pending';}
  
  if (isset($_GET['pin']) && $currentRank['perm_posts'] == 'yes') {
    $pinID = mysqli_real_escape_string($conn,$_GET['pin']);
    $pinSQL = "UPDATE `clans_walls` SET `type`='pinned' WHERE `id`='$pinID' AND `group_id`='$clanID'";
    $pin = $conn->query($pinSQL);
    header("Location: /clan?id=".$clanID);
  }
  if (isset($_GET['unpin']) && $currentRank['perm_posts'] == 'yes') {
    $pinID = mysqli_real_escape_string($conn,$_GET['unpin']);
    $pinSQL = "UPDATE `clans_walls` SET `type`='normal' WHERE `id`='$pinID' AND `group_id`='$clanID'";
    $pin = $conn->query($pinSQL);
    header("Location: /clan?id=".$clanID);
  }
  if (isset($_GET['delete']) && $currentRank['perm_posts'] == 'yes') {
    $pinID = mysqli_real_escape_string($conn,$_GET['delete']);
    $pinSQL = "UPDATE `clans_walls` SET `type`='deleted' WHERE `id`='$pinID' AND `group_id`='$clanID'";
    $pin = $conn->query($pinSQL);
    header("Location: /clan?id=".$clanID);
  }
  
  
    ?>
    
    <?php
  
  
?>
<!DOCTYPE html>
  <head>
    <title><?php echo htmlentities ( $clanRow['name'] ); ?> - Planet Hill</title>
  </head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="card">
<div class="top" style="position:relative;">

<dropdown id="dropdown-v" class="dropdown" style="right:7.5px;">
<ul>
<?php
              if($isIn && ($currentRank['perm_ranks'] == 'yes' || $currentRank['perm_members'] == 'yes'))
                {
                  echo'<li>
<a href="/clans/edit/'.$clanID.'">Edit</a>
</li>';
                  }
  ?>
<li>
<a href="#">Report</a>
</li>
</ul>
</dropdown>
<span class="clan-title"><?php echo htmlentities ( $clanRow['name'] ); ?></span><b><?php echo '['.$clanRow['tag'].']'; ?></b>
</div>
<div class="content" style="position:relative;">
<div class="col-3-12">
<div class="clan-img-holder mb1">
<img class="width-100" src="/clans/icons/<?php echo $thumbnail; ?>.png">
</div>
<div class="dark-gray-text bold">
<div>
Owned by
<b>
<a href="/user/<?php echo $ownerID; ?>/" class="black-text"><?php echo $ownerRow['username']; ?></a>
</b>
</div>
<div><?php echo' ' . $memCount . ' ';?> Members</div>
</div>
<form method="POST" action='' style="display:inline-block">
                <?php
                if($loggedIn) {
                  if(!$isIn)
                  {
                    echo '<button class="green" style="font-size:12px;" name="join" type="submit">JOIN</button>';
                  } else {
                    echo '<button class="red" style="font-size:12px;" name="leave" type="submit">LEAVE</button>';
                  }
                }
                ?>
              </form>
  
  
   <?php if($isIn){?>
<? if($userRow->{'primary_group'} != $clanRow['id']){?>
<!-- put your "make primary" stuff -->
<form method="POST" action="/clan/<?php echo''.$clanRow['id'].'';?>&primary" class="inline">
<input type="hidden" name="_token" value="4hMgt2WHjpkB21CpQ01b60ZepfvsUSaEwRwb7ks6"> <input type="hidden" name="clan_id" value="7777">
<button class="green" style="font-size:12px;width:120px;padding-left:5px;padding-right:5px;" type="submit">MAKE PRIMARY</button>
</form>             
<?
}else{
?>
<!-- put your remove stuff -->
<form method="POST" action="/clan/<?php echo''.$clanRow['id'].'';?>&unprimary" class="inline">
<input type="hidden" name="_token" value="4hMgt2WHjpkB21CpQ01b60ZepfvsUSaEwRwb7ks6"> <input type="hidden" name="clan_id" value="7444">
<button class="red" style="font-size:12px;width:130px;padding-left:5px;padding-right:5px;" type="submit">REMOVE PRIMARY</button>
</form>          
<?
}
}
  ?>
  </div>
<div class="col-9-12">
<div class="clan-description darkest-gray-text bold">
<span><?php echo str_replace("\n","<br>",htmlentities($clanRow['description'])); ?></span>
</div>
</div>
</div>
</div>
<div class="col-1-1 tab-buttons">
<button class="tab-button blue w600" data-tab="1">MEMBERS</button>
<button class="tab-button transparent w600" data-tab="2">RELATIONS</button>
<button class="tab-button transparent w600" data-tab="3">WALL</button>
</div>
<div class="col-1-1">
<div class="button-tabs">
<div class="button-tab active" data-tab="1">
<div class="col-1-1">
<div class="card">
<div class="top blue">
Members
</div>
<div class="content" style="min-height:250px;">
<div class="mb1 overflow-auto">
<?php if($isIn) {
              echo '
<span class="dark-gray-text">Your rank: <b class="black-text">'.$currentRank['name'].'</b></span>';
            } ?>
<div class="rank-select" style="width:150px;float:right;">

<select class="push-right select" onchange="getRank(0, this.value)">
<?php
                $sqlRanks = "SELECT * FROM `clans_ranks` WHERE `group_id`='$clanID' ORDER BY `power` ASC;";
                $rankResult = $conn->query($sqlRanks);
                while($rankRow = $rankResult->fetch_assoc())
                {
                  echo '
  <option value='.$rankRow['power'].'>'.$rankRow['name'].'</option>
';
                }
              ?>
</select>
</div>
  <div id="members"></div>
</div>
<div class="text-center">
<div class="member-holder overflow-auto unselectable">
</div>
<div class="member-pages pages blue unselectable">
</div>
</div>
</div>
</div>
</div>
</div>
<div class="button-tab" data-tab="2">
<div class="col-1-1">
<div class="card">
<div class="top">
Relations
</div>
<div class="content">
<div>
<fieldset class="fieldset green mb1">
<legend>Allies</legend>
<div class="p1 overflow-auto">
</div>
  Coming Soon
</fieldset>
<fieldset class="fieldset red">
<legend>Enemies</legend>
<div class="p1 overflow-auto">
</div>
  Coming Soon
</fieldset>
</div>
</div>
</div>
</div>
</div>
<div class="button-tab" data-tab="3">
<div class="col-1-1">
<div class="card">
<div class="top red">
Wall
</div>
<div class="content">
<?php
            if($isIn) {
              /*echo '<form action="" method="POST">
                <textarea name="wall" style="width:320px; height:70px;"></textarea>
                <br><input type="submit" name="eggMe" value="Post">
              </form>';*/
                echo'
                <form method="POST" action="#">
               <input type="hidden" name="_token" value="sGsaUrjTDQj5tmh909ByNS6f4wrqccgm1XwoBby3"> 
               <input type="hidden" name="id" value="162061"> <span class="smedium-text bold">Post</span> 
                <textarea name="wall" placeholder="Enter Post" class="width-100 mb2" style="height: 80px;"></textarea> 
                <input type="submit" name="eggMe" value="Post" class="button blue"></form>';
            } else {
              echo '<em>You are not in this group</em>';
            }
            ?>
            
            <div id="wall"></div>
          </div>
</div>
</div>
</div>
</div>
</div>
<script>
window.onload = function() {
  getRank(0,1);
  getWall(0);
}

function getRank(page, rank) {
  $("#members").load("/clans/members?clan=<?php echo $clanID; ?>&rank="+rank+"&page="+page);
}

function getWall(page) {
  $("#wall").load("/clans/wall?clan=<?php echo $clanID; ?>&page="+page);
}
</script>
</div>
</div>
<?php
  include("SiT_3/footer.php");
  ?>