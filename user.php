<?php 
include('SiT_3/config.php');
include('SiT_3/header.php');
//if(!$loggedIn) {header("Location: /index"); die();}
if (isset($_GET['id'])) {
  $id = mysqli_real_escape_string($conn,intval($_GET['id']));
  $sqlUser = "SELECT * FROM `beta_users` WHERE  `id` = '$id'";
  $userResult = $conn->query($sqlUser);
  $userRow=$userResult->fetch_assoc();
  if($userResult->num_rows <= 0){
    echo"<script>location.replace('/search/');</script>";
    //header('Location: /search/');
    die();
  }
} else {
  echo"<script>location.replace('/search/');</script>";
  //header('Location: /search/');
  die();
}
  
if(isset($_GET['desc']) && $power >= 1) {
  $scrubSQL = "UPDATE `beta_users` SET `description`='[Content Removed]' WHERE `id`='$id'";
  $scrub = $conn->query($scrubSQL);
}
if(isset($_GET['name']) && $power >= 1) {
  $scrubSQL = "UPDATE `beta_users` SET `username` = '[Deleted $id]', `usernameL` = '[deleted $id]' WHERE `id`='$id'";
  $scrub = $conn->query($scrubSQL);
}

$statusReq = mysqli_query($conn,"SELECT * FROM `statuses` WHERE `owner_id`='$id' ORDER BY `id` DESC");
$statusReqData = mysqli_fetch_assoc($statusReq);
$currStatus = $statusReqData['body'];



////REWARDS ARE UPDATED AND CHECKED HERE

//Classic - have been a member for more than a year
if((time()-strtotime($userRow['date'])) >= 31536000) {
  $rewardSQL = "SELECT * FROM `user_rewards` WHERE `user_id`='$id' AND `reward_id`='1'";
  $reward = $conn->query($rewardSQL);
  if($reward->num_rows == 0)
  {
    $addSQL = "INSERT INTO `user_rewards` (`id`,`user_id`,`reward_id`) VALUES (NULL ,'$id','1');";
    $add = $conn->query($addSQL);
  }
}


if (isset($_GET['pin']) && $currentRank = 1) {
    $pinID = mysqli_real_escape_string($conn,$_GET['pin']);
    $pinSQL = "UPDATE `user_walls` SET `type`='pinned' WHERE `id`='$pinID' AND `user_id`='$id'";
    $pin = $conn->query($pinSQL);
    header("Location: /user/".$id);
  }
  if (isset($_GET['unpin']) && $currentRank = 1) {
    $pinID = mysqli_real_escape_string($conn,$_GET['unpin']);
    $pinSQL = "UPDATE `user_walls` SET `type`='normal' WHERE `id`='$pinID' AND `user_id`='$id'";
    $pin = $conn->query($pinSQL);
    header("Location: /user/".$id);
  }
  if (isset($_GET['delete']) && $currentRank = 1) {
    $pinID = mysqli_real_escape_string($conn,$_GET['delete']);
    $pinSQL = "UPDATE `user_walls` SET `type`='deleted' WHERE `id`='$pinID' AND `user_id`='$id'";
    $pin = $conn->query($pinSQL);
    header("Location: /user/".$id);
  }


  if(isset($_POST['wall'])) {
    if($loggedIn)
    {
    $gold = $_SESSION['id'];
    $posted = str_replace("'","\'",$_POST['wall']);
    $postSQL = "INSERT INTO `user_walls` (`id`,`user_id`,`owner_id`,`post`,`time`,`type`)VALUES (NULL ,  '$id',  '$gold',  '$posted',  '$curDate',  'normal');";
    $post = $conn->query($postSQL);
    }
}

///////ADD PROFILE VIEW
$findViewsQuery = "SELECT * FROM `beta_users` WHERE `id`='$id'";
$findViews = $conn->query($findViewsQuery);
$viewRow = $findViews->fetch_assoc();
$views = $viewRow['views']+1;
$addViewQuery = "UPDATE `beta_users` SET `views`='$views' WHERE `id`='$id'";
$addView = $conn->query($addViewQuery);



//primary group
$primary = $userRow['primary_group'];
if($primary > 0){
  $clansSQL = "SELECT * FROM `clans` WHERE `id`='$primary'";
  $clans = $conn->query($clansSQL);
  $clanRow = $clans->fetch_assoc();
  $clanTag = '['.$clanRow['tag'].']';
} else {$clanTag = '';}

?>
<!DOCTYPE html>
  <head>
    <?php /* if($_SERVER['REQUEST_URI'] != 'beta.brick-hill.com/user.php?='.$id.'') { echo '
    <script type="text/javascript">location.href = "/user?id='.$id.'";</script>';
    } else {
  //Do nonthing.
    } */
    ?>
    <title><?php echo $userRow['username']; ?></title>
   
  <meta name="description" content="<?php echo $userRow['username'] ?> is a user on gtoria!">
  <meta name="keywords" content="free,game">
  <meta property="og:title" content="<?php echo $userRow['username']; ?>'s Profile" />
  <meta property="og:image" content="<?php echo '/avatar/render/avatars/'; ?><?php echo $userRow['id'];?><?php echo ".png?c=";?><?php echo $userRow['avatar_id']; ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="/user/<?php echo $userRow['id'] ?>" />
  </head>
  <body>
     <?php
    $bannedSQL = "SELECT * FROM `moderation` WHERE `active`='yes' AND `user_id`='$id'";
    $banned = $conn->query($bannedSQL);
    if($banned->num_rows > 0) {
    echo '
<div class="alert error">
User is banned
</div>
';
    }
    
     
  ?>
   
  <br>        

  <div class="container profile">
    <div class="profile_left">
      <div class="user_pane">
        <span class="username"><?php           $lastonlineTime = strtotime($userRow['last_online']);
      $lastOnline = time()-$lastonlineTime;
      
          if ($lastOnline <= 300) {
            echo ' <span class="online"><i class="status-dot online"></i></span>';
            } else {
            echo ' <span class="offline"><i class="status-dot"></i></span>';
            }  
  
  

          
        
          
          ?><?php echo $userRow['username']; ?></span><br>
         <img class="img-responsive" style="display:inline;" src="/avatar/render/avatars/<?php echo $userRow['id']; ?>.png?c=<?php echo $userRow['avatar_id']; ?>">
                   <br> <?php
if($loggedIn) {
          if ($userRow['id'] != $_SESSION['id']) {
            if ($id != -1) {
            echo '<form action="/messages/compose" method="POST" style="display:inline-block;">
              <input type="hidden" name="recipient" value="'.$userRow['id'].'">
              <input class="btn btn-primary" style="font-size:14px;" type="submit" value="Message">
              </form>';
      }
            
            echo'<a class="btn btn-primary" href="/trade/?id=971734" style="font-size:14px;">TRADE</a>';
            // Check if they are friends
            $senderID = $_SESSION['id'];
        $AlreadyFriendsQ = mysqli_query($conn,"SELECT * FROM `friends` WHERE `to_id`='$id' AND `from_id`='$senderID' AND `status`='accepted' OR `to_id`='$senderID' AND `from_id`='$id' AND `status`='accepted'");
        $AlreadyFriends = mysqli_num_rows($AlreadyFriendsQ);
            
            if($AlreadyFriends<1){
              echo '<a href="/friends/add?id=' . $id . '"><input class="btn btn-success" style="font-size:14px;" type="button" value="Add Friend"></a>';
            } else {
              echo '<a href="/friends/remove?id=' . $id . '"><input class="btn btn-danger" style="font-size:14px;" type="button" value="Remove Friend"></a>';
            }
          
          } else {
          echo '';
          }
        if($power >= 1 && $userRow['power'] < $power) {
          echo '<a href="/ban?id='.$id.'">
          <input class="btn btn-danger" style="font-size:14px;" type="button" value="Ban">
          </a><br>
          <a href="/avatar/render/?id='.$id.'">
          <input class="btn btn-primary" style="font-size:14px;" type="button" value="Render">
          </a>';
          
          if ($power >= 1) {
          echo'
          <a href="/admin/user?id='.$id.'">
          <input class="btn btn-danger" style="font-size:14px;" type="button" value="Information">
          </a>';
          }
        }
}?>                                   
        <div class="userpane_right">
          <p class="description"></p>
        </div>
      </div>

      <div class="badges_pane">
        <h4>Badges</h4>


                <div class="content">
        
       <div class="row"><?php
          $rewardsSQL = "SELECT * FROM `user_rewards` WHERE `user_id`='$id'";
          $rewards = $conn->query($rewardsSQL);
          if($rewards->num_rows != 0){
            while($rewardsRow = $rewards->fetch_assoc()){
              $rewardID = $rewardsRow['reward_id'];
              $findRewardSQL = "SELECT * FROM `awards` WHERE `id`='$rewardID'";
              $findReward = $conn->query($findRewardSQL);
              $rewardRow = $findReward->fetch_assoc();
              
              echo '
                            <img style="width:20px;vertical-align: middle;" src="/assets/membership/'.$rewardRow['id'].'.png">

<br>
<span class="ellipsis">'.$rewardRow['name'].'</span>
';
              }
          }
  if ($userRow['power'] >= 1) {      
  ?>
  <img style="width:120px;vertical-align: middle;" src="/assets/awards/3.png">
<br><span class="ellipsis">JuneBlox Team</span><br>
<?php
    } else {
      echo'';
              }
    $membershipSQL = "SELECT * FROM `membership` WHERE `active`='yes' AND `user_id`='$id'";
  $membership = $conn->query($membershipSQL);
  while($membershipRow = $membership->fetch_assoc()) {
    $memSQL = "SELECT * FROM `membership_values` WHERE `value`='".$membershipRow['membership']."'";
    $mem = $conn->query($memSQL);
    $memRow = $mem->fetch_assoc();
    echo '
<img style="width:120px;vertical-align: middle;" src="/assets/membership/'.$memRow['value'].'.png"><br>
<span class="ellipsis">'.$memRow['name'].'</span>
';
    }
          
          ?></div>
                    
        </div>  </div>


      </div>
   
    <div class="profile_right">
      <div class="friends_pane">
        <h4>Showcase</h4> 

                <div class="content">
                  <center>Public Place<br>
                    <a href="#"><button class="button">Vist Online</button></a>

                  <a href="#"><button class="button">Vist Solo</button></a>
</center>
            <img style="width:350px;vertical-align: middle;" src="/client.png">

        </div>
      </div>
    </div>
 
<br><br><br>
<div class="profile_right">
      <div class="friends_pane">
        <h4>Friends</h4>

                <div class="content">
  
         
      </div>
    </div>
  </div>

</div>