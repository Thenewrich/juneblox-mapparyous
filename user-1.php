<?php
include('func/config.php');
include('func/header.php');
if (isset($_GET['id'])) {
  $id = mysqli_real_escape_string($conn,intval($_GET['id']));
  $sqlUser = "SELECT * FROM `beta_users` WHERE  `id` = '$id'";
  $userResult = $conn->query($sqlUser);
  $userRow=$userResult->fetch_assoc();
  if($userResult->num_rows <= 0){
    header('Location: /search/');
    die();
  }
} else {
  header('Location: /search/');
  die();
}
  
if(isset($_GET['desc']) && $power >= 1) {
  $scrubSQL = "UPDATE `beta_users` SET `description`='[Content Removed]' WHERE `id`='$id'";
  $scrub = $conn->query($scrubSQL);
}
if(isset($_GET['name']) && $power >= 1) {
  $scrubSQL = "UPDATE `beta_users` SET `username`='[Deleted $id]' WHERE `id`='$id'";
  $scrub = $conn->query($scrubSQL);
}

$statusReq = mysqli_query($conn,"SELECT * FROM `statuses` WHERE `owner_id`='$id' ORDER BY `id` DESC");
$statusReqData = mysqli_fetch_assoc($statusReq);
$currStatus = $statusReqData['body'];



////REWARDS ARE UPDATED AND CHECKED HERE

//Classic - have been a member for more than a year
/*if((time()-strtotime($userRow['date'])) >= 31536000) {
  $rewardSQL = "SELECT * FROM `user_rewards` WHERE `user_id`='$id' AND `reward_id`='1'";
  $reward = $conn->query($rewardSQL);
  if($reward->num_rows == 0)
  {
    $addSQL = "INSERT INTO `user_rewards` (`id`,`user_id`,`reward_id`) VALUES (NULL ,'$id','1');";
    $add = $conn->query($addSQL);
  }
}
*/



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
<?php
            $postCountSQL = "SELECT * FROM `forum_posts` WHERE `author_id`='$id'";
            $postCount = $conn->query($postCountSQL);
            $posts = $postCount->num_rows;
                        $lastonline = strtotime($curDate)-strtotime($userRow['last_online']);

            $threadCountSQL = "SELECT * FROM `forum_threads` WHERE `author_id`='$id'";
            $threadCount = $conn->query($threadCountSQL);
            $threads = $threadCount->num_rows;
            
            $userPostCount = ($threads+$posts);
   ?>

<?php
  include("func/alert.php");
  ?>
<!DOCTYPE html>
  <head>
    <?php /* if($_SERVER['REQUEST_URI'] != 'beta.brick-hill.com/user.php?='.$id.'') { echo '
    <script type="text/javascript">location.href = "/user?id='.$id.'";</script>';
    } else {
  //Do nonthing.
    } */
    ?>
    <title><?php echo $userRow['username']; ?> - Good-Hill</title>
  <meta charset="UTF-8">
  <meta name="description" content="<?php echo $userRow['username'] ?> is a user on Brick Hill! Sign up today to get started!">
  <meta name="keywords" content="free,game">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <meta property="og:title" content="<?php echo $userRow['username']; ?>'s Profile" />
  <meta property="og:description" content="<?php echo $userRow['username'] ?> is a user on Brick Hill! Sign up today to get started!" />
  <meta property="og:image" content="<?php echo 'http://storage.brick-hill.com/images/avatars/'; ?><?php echo $userRow['id'];?><?php echo ".png?c=";?><?php echo $userRow['avatar_id']; ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="http://www.brick-hill.com/user?id=<?php echo $userRow['id'] ?>" />
  </head>
  <body>
  
    <div id="body">
    <?php
    $bannedSQL = "SELECT * FROM `moderation` WHERE `active`='yes' AND `user_id`='$id'";
    $banned = $conn->query($bannedSQL);
    if($banned->num_rows > 0) {
    echo '<div class="banned">
          This user is banned
      </div>';
    }
    
    ?>
     <div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="col-6-12">
<div class="card">
<div class="content text-center bold medium-text relative ellipsis">
  <?php           $lastonlineTime = strtotime($userRow['last_online']);
      $lastOnline = time()-$lastonlineTime;
      
          if ($lastOnline <= 300) {
            echo ' <span class="online"><i class="status-dot online"></i></span>';
            } else {
            echo ' <span class="offline"><i class="status-dot"></i></span>';
            }  
  
  

          
        
          
          ?> 
<span class="ellipsis"><?php echo $userRow['username']; ?></span>
<br>
<img src="/avatar/avatars/<?php echo $userRow['id']; ?>.png?c=<?php echo $userRow['avatar_id']; ?>" style="height:350px;">
<div class="user-description-box closed">
<div class="toggle-user-desc gray-text">
<div class="user-desc p2 darker-grey-text" style="font-size:16px;line-height:17px;">
<?php if (!empty($userRow['description']) || $userRow['description'] !== NULL ) {echo nl2br(htmlentities($userRow['description']));} ?>
</div>
<a class="darker-grey-text read-more-desc" style="font-size:16px;">Read More</a>
</div>
</div>

  <?php
if($loggedIn) {
          if ($userRow['id'] != $_SESSION['id']) {
            if ($id != -1) {
            echo '<form action="/messages/compose" method="POST" style="display:inline-block;">
              <input type="hidden" name="recipient" value="'.$userRow['id'].'">
              <input type="submit" value="Send Message">
              </form>';
      }
            // Check if they are friends
            $senderID = $_SESSION['id'];
        $AlreadyFriendsQ = mysqli_query($conn,"SELECT * FROM `friends` WHERE `to_id`='$id' AND `from_id`='$senderID' AND `status`='accepted' OR `to_id`='$senderID' AND `from_id`='$id' AND `status`='accepted'");
        $AlreadyFriends = mysqli_num_rows($AlreadyFriendsQ);
            
            if($AlreadyFriends<1){
              echo '<a href="/friends/add?id=' . $id . '"><input class="blue-button" type="button" value="Add Friend"></a>';
            } else {
              echo '<a href="/friends/remove?id=' . $id . '"><input class="red-button" type="button" value="Remove Friend"></a>';
            }
          
          } else {
          echo '<br><a href="/customize"><input type="button" value="Customize"></a><a style="padding-left:5px;" href="/settings"><input type="button" value="Settings"></a>';
          }
        if($power >= 1 && $userRow['power'] < $power) {
          echo '<br><a href="/ban?id='.$id.'">
          <input class="red-button" type="button" value="Ban User">
          </a>
          <a href="/avatar/render/?id='.$id.'">
          <input class="blue-button" type="button" value="Render">
          </a><br>';
          
          if ($power >= 1) {
          echo'
          <a href="/admin/user?id='.$id.'">
          <input class="red-button" type="button" value="Audits">
          </a>';
          }
        }
}
        ?>
</div>
  </div>
<div class="card">
<div class="top green">
Awards
</div>
<div class="content" style="text-align:center;">
<?php
          $rewardsSQL = "SELECT * FROM `user_rewards` WHERE `user_id`='$id'";
          $rewards = $conn->query($rewardsSQL);
          if($rewards->num_rows != 0){
            while($rewardsRow = $rewards->fetch_assoc()){
              $rewardID = $rewardsRow['reward_id'];
              $findRewardSQL = "SELECT * FROM `awards` WHERE `id`='$rewardID'";
              $findReward = $conn->query($findRewardSQL);
              $rewardRow = $findReward->fetch_assoc();
              
              echo '<div style="margin: 10px;width: 107px;display:inline-block;float: left;"><a href="/awards/" style="color:#333;">
                <img style="border: 1px solid #000;background-color: #FDFDFD;width: 118px;height: 118px;" src="/assets/awards/'.$rewardRow['id'].'.png">
                <span style="text-align:center;display:inline-block;float:left;width: 100%;padding-left: 0;padding-right: 0;">
                  <p class="shopTitle">'.$rewardRow['name'].'<br></a>
                </span></a>
              </div>';
              }
          }
    $membershipSQL = "SELECT * FROM `membership` WHERE `active`='yes' AND `user_id`='$id'";
  $membership = $conn->query($membershipSQL);
  while($membershipRow = $membership->fetch_assoc()) {
    $memSQL = "SELECT * FROM `membership_values` WHERE `value`='".$membershipRow['membership']."'";
    $mem = $conn->query($memSQL);
    $memRow = $mem->fetch_assoc();
    echo '<div style="margin: 10px;width: 107px;display:inline-block;float: left;"><a href="/awards/" style="color:#333;">
            <img style="border: 1px solid #000;background-color: #FDFDFD;width: 118px;height: 118px;" src="/assets/membership/'.$memRow['value'].'.png">
              <span style="text-align:center;display:inline-block;float:left;width: 100%;padding-left: 0;padding-right: 0;">
                <p class="shopTitle">'.$memRow['name'].'<br></a>
              </span></a>
            </div>';
    }
          
          ?>
</div>
</div>
</div>
<div class="col-6-12" style="padding-right:0;">
<sets id="sets-v" user_id="-1" class="set-slider" style="position: relative"></sets>
<div style="text-align:center;">
<script async src="//web.archive.org/web/20191117232140js_/https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle" style="display:inline-block;width:336px;height:280px;margin-top:10px;" data-ad-client="ca-pub-8506355182613043" data-ad-slot="6353770815"></ins>
<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
</div>
</div>
<div class="col-1-1 tab-buttons">
<button class="tab-button blue" data-tab="1">CRATE</button>
<button class="tab-button transparent" data-tab="2">SOCIAL</button>
<button class="tab-button transparent" data-tab="3">STATS</button>
</div>
<div class="col-1-1" id="tabs">
<div class="button-tabs">
<div class="button-tab active" data-tab="1">
<div class="col-1-1">
<div class="card">
<div class="top red">
Crate
</div>
<div class="content">
<div><div class="col-2-12"><ul class="crate-types"><li class="active">All</li><li class="">Hats</li><li class="">Tools</li><li class="">Faces</li><li class="">Heads</li><li class="">T-Shirts</li><li class="">Shirts</li><li class="">Pants</li><li class="">Specials</li></ul></div><div>

<crate id="crate" data-v-app=""></crate>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="button-tab" data-tab="2">
<div class="row" style="padding-right:0.1px;">
<div class="col-6-12">
<div class="card">
<div class="top orange" style="position:relative;">
Clans
<a class="button orange" href="/user/1/clans" style="position:absolute;right:5px;top:4px;padding:5px;">SEE ALL</a>
</div>
<div class="content" style="text-align:center;min-height:330.86px;">
<a class="col-1-3" href="/clan/1/" style="padding-right:5px;padding-left:5px;">
<div class="profile-card">
<img src="https://brkcdn.com/v2/assets/9f0898a3-1f22-5307-9a7f-4236dc20a37b">
<span class="ellipsis">Brick Masters
</span>
</div>
</a>
<a class="col-1-3" href="/clan/2/" style="padding-right:5px;padding-left:5px;">
<div class="profile-card">
<img src="https://brkcdn.com/v2/assets/c3d1476f-1c1f-56bc-b8b3-8484dd059e7f">
<span class="ellipsis">Space Rangers
</span>
</div>
</a>
<a class="col-1-3" href="/clan/4/" style="padding-right:5px;padding-left:5px;">
<div class="profile-card">
<img src="https://brkcdn.com/v2/assets/76409ed8-53c8-5b6c-ae5e-20792eb50a1a">
<span class="ellipsis">Brick Hat Hackers</span>
</div>
</a>
<a class="col-1-3" href="/clan/27/" style="padding-right:5px;padding-left:5px;">
<div class="profile-card">
<img src="https://brkcdn.com/v2/assets/b0887186-0814-514f-aaae-3b74310e88f1">
<span class="ellipsis">Brick Hill Staff
</span>
</div>
</a>
<a class="col-1-3" href="/clan/40/" style="padding-right:5px;padding-left:5px;">
<div class="profile-card">
<img src="https://brkcdn.com/v2/assets/b06b2538-c187-5b47-b3fe-4c4493c4ef50">
<span class="ellipsis">The Jolly Rogers
</span>
</div>
</a>
<a class="col-1-3" href="/clan/48/" style="padding-right:5px;padding-left:5px;">
<div class="profile-card">
<img src="https://brkcdn.com/v2/assets/1fc99305-697c-5d8d-a7d6-cf18cadfd7bd">
<span class="ellipsis">Brick Hill
</span>
 </div>
</a>
</div>
</div>
</div>
<div class="col-6-12">
<div class="card">
<div class="top red" style="position:relative;">
Friends
<a class="button red" href="/user/1/friends/1" style="position:absolute;right:5px;top:4px;padding:5px;">SEE ALL</a>
</div>
<div class="content" style="text-align:center;min-height:330.86px;">

<?php
      $friendsListCount = $conn->query("SELECT * FROM `friends` WHERE  `to_id` = '$id' AND `status`='accepted' OR `from_id` = '$id' AND `status`='accepted'")->num_rows;
  $friendsList = mysqli_query($conn, "SELECT * FROM `friends` WHERE  `to_id` = '$id' AND `status`='accepted' OR `from_id` = '$id' AND `status`='accepted' ORDER BY `id` DESC LIMIT 0,8");
          $friendCount = mysqli_num_rows($friendsList);
          ?>
      
      <?php
          if (mysqli_num_rows($friendsList) > 0) {
                while($friendsListRow = mysqli_fetch_assoc($friendsList)) {
              $friendRowQ = mysqli_query($conn,"SELECT * FROM `beta_users` WHERE (`id`='$friendsListRow[from_id]' OR `id`='$friendsListRow[to_id]') AND `id`!='$id' ");
                  $friendRow = mysqli_fetch_array($friendRowQ);
                  $friendUsername = $friendRow['username'];
          if (strlen($friendUsername) > 9) {
            $friendUsername = substr($friendUsername, 0, 9) . '...';
          }
                  echo '
                    <a class="col-1-3" href="/user/'.$friendRow['id'].'" style="padding-right:5px;padding-left:5px;">
<div class="profile-card user">
<img src="/avatar/avatars/'.$friendRow['id'].'.png?c='.$friendRow['avatar_id'].' ">
<span class="ellipsis">'.$friendUsername.'</span>
</div>
</a>';
    }
    } else {
              echo "<i>This user has no friends!</i>";
          }
          ?>
</div>
</div>
</div>
</div>
</div>
<div class="button-tab" data-tab="3">
<div class="col-1-1">
<div class="card">
<div class="top red">
Statistics
</div>
<div class="content" style="min-height:330.86px;">
<table class="stats-table">
<tbody><tr>
<td>
<b>Join Date:</b>
</td>
<td id="join-date">
<?php echo''.$userRow['date'].''; ?></td>
</tr>
<tr>
<td>
<b>Last Online:</b>
</td>
<td id="last-online">
<?php if ($lastonline >= 300) {
    $timedif = $lastonline . " seconds";
    if ($lastonline >= 300) {$timedif = (int)gmdate('i',$lastonline) . " minutes";}
    if ($lastonline >= 3600) {$timedif = (int)gmdate('H',$lastonline) . " hours";}
    if ($lastonline >= 86400) {$timedif = (int)gmdate('d',$lastonline) . " days";}
    if ($lastonline >= 2592000) {$timedif = (int)gmdate('m',$lastonline) . " months";}
    if ($lastonline >= 31536000) {$timedif = (int)gmdate('Y',$lastonline) . " years";}
    echo '<ul>
              
        <li><span style="font-weight:bold;"></span>' . $timedif . ' ago
            </ul>';}
            

          else {        //finally, i made last online thing way better than planet hill
              

            echo'<ul>
              
        <li><span style="font-weight:bold;"></span>Now
            </ul>';
            }?>
</td>
</tr>
<tr>

</tr>
<tr>
<td>
<b>Forum Posts:</b>
</td>
<td id="forum-posts">
<?php echo' '.$userPostCount.''; ?>
</td>
</tr>
<tr>

</tr>
</tbody></table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<script>
    if($('.user-description-box .user-desc').height() <= 80) {
        $('.read-more-desc').css('display', 'none');
        $('.toggle-user-desc').addClass('open');
    }
    $(document).on('click', '.read-more-desc', function () {
        $(this).parent().parent().toggleClass('closed');
        if($(this).text() == 'Read More') {
            $(this).text('Show Less');
            $('.user-description-box .content').css('min-height', $('.user-description-box .content').height() + 33)
        } else {
            $(this).text('Read More');
            $('.user-description-box .content').css('min-height', $('.user-description-box .content').height() - 33)
        }
    })
</script>
        <script>
  var id = "<?php echo $id; ?>";

  window.onload = function() {
    getPage('hat',0);
  };
  
  function getPage(type, page) {
    $("#crate").load("../crate?id="+id+"&type="+type+"&page="+page);
  };
</script>
<div class="col-10-12 push-1-12">
<div style="text-align:center;margin-top:20px;padding-bottom:25px;">
</div>
</div>
</div>
      </div>
  </div>
        </div>
      </div>
    </div>
      <?php
include('func/footer.php');
  ?>      