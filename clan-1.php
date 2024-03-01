<?php
  include("SiT_3/config.php");
  include("SiT_3/header.php");
  if(!$loggedIn) {header("Location: /index"); die();}
  $clanID = mysqli_real_escape_string($conn, intval($_GET['id']));
  $sqlClan = "SELECT * FROM `clans` WHERE `id`='$clanID'";
  $result = $conn->query($sqlClan);
  if($result->num_rows == 0) {
    header("Location: /clans/index");
    die();
  }
  $clanRow = $result->fetch_assoc();
  
  if ($clanRow['id'] == 4 && $loggedIn) {
    echo '<script>
      console.log("Psst... Looking for eggs?");
      function eggMe() {
        $.post("clan?id='.$clanRow['id'].'", {eggMe: 1}, function(){console.log("Congratulations, you have found the Binary Egg!");});
      }
    </script>';
    
    if(isset($_POST['eggMe'])) {
      $userID = $_SESSION['id'];
      $itemID = 19;
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
    
  if ($clanRow['id'] == 4) { 
    if(isset($_POST['egg'])) {
      if ($_POST['egg'] == 'iLoveEggs') {
        die('yay');
      } 
    }
    }
    ?>
    <script>
    eval(function(p,a,c,k,e,d){e=function(c){return c};if(!''.replace(/^/,String)){while(c--){d[c]=k[c]||c}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('0.1(\'2 3 4? :^)\');',5,5,'console|log|Looking|for|eggs'.split('|'),0,{}))
eval(function(p,a,c,k,e,d){e=function(c){return c.toString(36)};if(!''.replace(/^/,String)){while(c--){d[c.toString(a)]=k[c]||c.toString(a)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('0 5(){$.4(\'?\',{3:\'2\'},0(1){7(1==\'9\'){8(\'b 6 a\')}})}',12,12,'function|data|iLoveEggs|egg|post|eggMe|u|if|alert|yay|h4x3r|wow'.split('|'),0,{}))


 eggMe(); :^)
    </script> 
    <?php
  
    
  
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
  {echo"<script>location.replace('');</script>";
   
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
  {echo"<script>location.replace('');</script>";
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
    <title><?php echo htmlentities ( $clanRow['name'] ); ?> - Builder Land</title>
  </head>
  <body>
    <div id="body">
      <div id="box">
        <div style="margin:10px; vertical-align:top;">
          <h3><span style="color:#555;font-size:18px;"><?php echo '['.$clanRow['tag'].']'; ?></span><?php echo $clanRow['name']; ?><?php if($clanRow['verified'] >= 1) {echo '<i style="color: green;" class="fa fa-check"></i>';}
   ?></h3>
          <div style="height:240px;">
            <div style="width:200px;float:left;">
              <img style="width:200px;height:200px;" src="/clans/icons/<?php echo $thumbnail; ?>.png?c=<?php echo'' . rand() . '';?>">
              <h5>Owned by <a style="color:#555;" href=/user?id=<?php echo $ownerID; ?>"><?php echo $ownerRow['username']; ?></a></h5>
              <?php
              if($loggedIn && $clanRow['approved'] != 'yes' && $power >= 1) {
                echo '<h5><a style="color:#555;" href="?id='.$clanRow['id'].'&approve">Approve</a></h5><br>';
              }
              if($loggedIn && $power >= 1) {
                echo '<h5><a style="color:#555;" href="?id='.$clanRow['id'].'&decline">Decline</a></h5><br>';
              }
              if($loggedIn && $isIn) {
                //echo '<h5><a style="color:#555;" href="?id='.$clanRow['id'].'&primary">Make Primary</a></h5><br>';
              }
              if($loggedIn && $isIn) {
                //echo '<h5><a style="color:#555;" href="?id='.$clanRow['id'].'&unprimary">Remove Primary</a></h5><br>';
              }
  
  ?>
               
              
              <a href="/report?type=clan&id=<?php echo $clanRow['id'] ?>"><i style="color:#444;font-size:13px;" class="fa fa-flag"></i></a>
            </div>
            <div style="float:left;margin:10px;">
              <p style="margin:0px;"><?php echo str_replace("\n","<br>",htmlentities($clanRow['description'])); ?></p>
            
              <?php
              if($loggedIn) {
                if($power >= 2) {
                  echo '<span><a class="label" href="clan?id='.$clanRow['id'].'&desc">Scrub</a></span>';
                }
              }
              ?>
              <?php if($isIn){?>
<? if($userRow->{'primary_group'} != $clanRow['id']){?>
<!-- put your "make primary" stuff -->
<h5><a style="padding:5px 7px 5px 5px; margin-top: 10px;" class="button-style" href="http://builderland.ct8.pl/clan/<?php echo''.$clanRow['id'].'';?>&primary">Make Primary</a></h5><br>             
<?
}else{
?>
<!-- put your remove stuff -->
<h5><a style="padding:5px 7px 5px 5px; margin-top: 10px; background-color: red;" class="button-style" href="http://builderland.ct8.pl/clan/<?php echo''.$clanRow['id'].'';?>&unprimary">Remove Primary</a></h5><br>          
<?
}
}
?>
            </div>
            <div style="float:right;margin:10px;">
              <form method="POST" action=''>
                <?php
                if($loggedIn) {
                  if(!$isIn)
                  {
                    echo '<input  type="submit" name="join" value="Join" style="padding:5px 7px 5px 5px; background-color:green;">';
                  } else {
                    echo '<input style="padding:5px 7px 5px 5px; background-color:#FF3333;" type="submit" name="leave" value="Leave">';
                  }
                }
                ?>
              </form>
              <?php
              if($isIn && ($currentRank['perm_ranks'] == 'yes' || $currentRank['perm_members'] == 'yes'))
                {
                  //echo '<form style="margin-top:10px;" action="/clans/edit?id='.$clanID.'">
                  //  <input style="padding:5px 7px 5px 5px; background-color:#FF3333;" type="submit" value="Edit">
                  //</form>';
                  
                  echo '<a href="/clans/edit?id='.$clanID.'"><button style="margin-top:10px; padding:5px 7px 5px 5px; background-color:#FF3333;">Edit</button></a>';
                }
              ?>
            </div>
          </div>
        </div>
      </div>
      <div onclick="panel('members')" style="float:left;margin-top:10px;background-color:#77B9FF;border: 1px solid black;color:white;border-bottom:0px;padding:8px;width:100px;text-align:center;"><label>Members</label></div>
<div onclick="panel('relations')" style="float:left;margin-top:10px;background-color:#77B9FF;border-top: 1px solid black;color:white;border-bottom:0px;padding:8px;width:100px;text-align:center;"><label>Relations</label></div>
<div onclick="panel('funds')" style="float:left;margin-top:10px;background-color:#77B9FF;border: 1px solid black;color:white;border-bottom:0px;padding:8px;width:100px;text-align:center;"><label>Funds</label></div>
<div id="box" style="clear:both;">
<div id="panel-members" style="display:block;">
<div style="clear:both;margin:10px;height:320px;">
<h3>Members</h3>
<?php if($isIn) { 
              echo '<h5>You are '.$currentRank['name'].'</h5>';
            } ?>
<select onchange="getRank(0, this.value)">
              <?php
                $sqlRanks = "SELECT * FROM `clans_ranks` WHERE `group_id`='$clanID' ORDER BY `power` ASC;";
                $rankResult = $conn->query($sqlRanks);
                while($rankRow = $rankResult->fetch_assoc())
                {
                  echo '<option value='.$rankRow['power'].'>'.$rankRow['name'].'</option>';
                }
              ?>
            </select>
<div id="members" align="center">
Loading members... </div>
</div>
</div>
<div id="panel-relations" style="display:none;">
<div style="clear:both;margin:10px;height:340px;">
<h3>Relations</h3>
<h5>Allies:</h5>
<div style="text-align:center;height:140px;">
Coming Soon

</div>
<h5>Enemies:</h5>
<div style="text-align:center;height:140px;">
Coming Soon

</div>
</div>
</div>
<div id="panel-funds" style="display:none;">
<div style="clear:both;margin:10px;height:260px;">
<h3>Funds: <i class="fa fa-money"></i><?php echo' '.$clanRow['funds'].''; ?></h3>
</div>
</div>
</div>
      <div id="box" style="padding:5px; margin-top:10px;">
          <div>
            <h4>Wall</h4>
            <?php
            if($isIn) {
              echo '<form action="" method="POST">
                <textarea name="wall" style="width:320px; height:70px;"></textarea>
                <br><input type="submit" name="eggMe" value="Post">
              </form>';
            } else {
              echo '<em>You are not in this group</em>';
            }
            ?>
            
            <div id="wall"></div>
          </div>
        </div>
      </div>
    </div>
  </body>
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
  function panel(box) {
  document.getElementById('panel-funds').style.display = "none";
  document.getElementById('panel-relations').style.display = "none";
  document.getElementById('panel-members').style.display = "none";
  document.getElementById('panel-'+box).style.display = "block";
}
</script>
</html>
<?php
  include("SiT_3/footer.php");
?>
