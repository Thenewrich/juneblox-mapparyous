<?php
include('SiT_3/header.php');
if(isset($_POST['wall'])) {
    
    $posted = str_replace("'","\'",$_POST['wall']);
    $postSQL = "INSERT INTO `user_walls` (`id`,`user_id`,`owner_id`,`post`,`time`,`type`)VALUES (NULL ,  '$usersID',  '$userID',  '$posted',  '$curDate',  'normal');";
    $post = $conn->query($postSQL);
    }
  
  

if (isset($_GET['id'])) {
  $id = mysqli_real_escape_string($conn,intval($_GET['id']));
  $sqlUser = "SELECT * FROM `beta_users` WHERE  `id` = '$id'";
  $userResult = $conn->query($sqlUser);
  $userRow=$userResult->fetch_assoc();
  if($userResult->num_rows <= 0){
    header('Location: /user/search/?search=');
    die();
  }
} else {
  header('Location: /user/search/?search=');
  die();
}
  
//anti XshitSHIT starts here

  function bbcode_to_html($bbtext){
    $bbtags = array(
      '[heading1]' => '<h1>','[/heading1]' => '</h1>',
      '[heading2]' => '<h2>','[/heading2]' => '</h2>',
      '[heading3]' => '<h3>','[/heading3]' => '</h3>',
      '[h1]' => '<h1>','[/h1]' => '</h1>',
      '[h2]' => '<h2>','[/h2]' => '</h2>',
      '[h3]' => '<h3>','[/h3]' => '</h3>',
  
      '[paragraph]' => '<p>','[/paragraph]' => '</p>',
      '[para]' => '<p>','[/para]' => '</p>',
      '[p]' => '<p>','[/p]' => '</p>',
      '[left]' => '<p style="text-align:left;">','[/left]' => '</p>',
      '[right]' => '<p style="text-align:right;">','[/right]' => '</p>',
      '[center]' => '<p style="text-align:center;">','[/center]' => '</p>',
      '[justify]' => '<p style="text-align:justify;">','[/justify]' => '</p>',
  
      '[bold]' => '<span style="font-weight:bold;">','[/bold]' => '</span>',
      '[italic]' => '<i>','[/italic]' => '</i>',
      '[underline]' => '<span style="text-decoration:underline;">','[/underline]' => '</span>',
      '[b]' => '<span style="font-weight:bold;">','[/b]' => '</span>',
      '[i]' => '<i>','[/i]' => '</i>',
      '[u]' => '<span style="text-decoration:underline;">','[/u]' => '</span>',
      '[s]' => '<s>','[/s]' => '</s>',
      '[break]' => '<br>',
      '[br]' => '<br>',
      '[newline]' => '<br>',
      '[nl]' => '<br>',
      
      '[unordered_list]' => '<ul>','[/unordered_list]' => '</ul>',
      '[ul]' => '<ul>','[/ul]' => '</ul>',
    
      '[ordered_list]' => '<ol>','[/ordered_list]' => '</ol>',
      '[ol]' => '<ol>','[/ol]' => '</ol>',
      '[list]' => '<li>','[/list]' => '</li>',
      '[li]' => '<li>','[/li]' => '</li>',
        
      '[*]' => '<li>','[/*]' => '</li>',
      '[code]' => '<pre>','[/code]' => '</pre>',
      '[quote]' => '<blockquote>','[/quote]' => '</blockquote>',
      '[preformatted]' => '<pre>','[/preformatted]' => '</pre>',
      '[pre]' => '<pre>','[/pre]' => '</pre>',  
      
      //Emojis
      //Created by Tech
      //I wouldn't brag about that, buddy - Luke
      
      ':)' => '<img src="/assets/emojis/smile.png"></img>',
      ':(' => '<img src="/assets/emojis/sad.png"></img>',
      ':P' => '<img src="/assets/emojis/tongue.png"></img>',
      ':p' => '<img src="/assets/emojis/tonuge.png"></img>',       
      ':*' => '<img src="/assets/emojis/kiss.png"></img>',    
      ':|' => '<img src="/assets/emojis/none.png"></img>',    
      ':^)' => '<img src="/assets/emojis/oops.png"></img>',   
      ':D' => '<img src="/assets/emojis/grin.png"></img>',


  // deleting links !!!
  'goatse.info' => '[ Link Removed ]',
  'pornhub.com' => '[ Link Removed ]',
  'rule34.xxx' => 'i want to touch grass',

  //deleting shitty af <script> attempts
  '</script>' => 'NO',
  '<script>' => 'haha i am funny js guy who wants to window.location.replace the whole plutonium server',
    );
    
    $bbtext = str_ireplace(array_keys($bbtags), array_values($bbtags), $bbtext);
  
    $bbextended = array(
      "/\[url](.*?)\[\/url]/i" => "<a style=\"color:#444\" href=\"$1\">$1</a>",
      "/\[url=(.*?)\](.*?)\[\/url\]/i" => "<a style=\"color:#444\" href=\"$1\">$2</a>",
      "/\[email=(.*?)\](.*?)\[\/email\]/i" => "<a href=\"mailto:$1\">$2</a>",
      "/\[mail=(.*?)\](.*?)\[\/mail\]/i" => "<a href=\"mailto:$1\">$2</a>",
      "/\[youtube\]([^[]*)\[\/youtube\]/i" => "<iframe src=\"https://youtube.com/embed/$1\" width=\"560\" height=\"315\"></iframe>",
    );
  
    foreach($bbextended as $match=>$replacement){
      $bbtext = preg_replace($match, $replacement, $bbtext);
    }
    return $bbtext;
  }

//anti XshitSHIT ends here


if(isset($_GET['desc']) && $power >= 5) {
  $scrubSQL = "UPDATE `beta_users` SET `description`='[Content Removed]' WHERE `id`='$id'";
  $scrub = $conn->query($scrubSQL);
}
if(isset($_GET['name']) && $power >= 5) {
  $scrubSQL = "UPDATE `beta_users` SET `username`='[Deleted $id]' WHERE `id`='$id'";
  $scrub = $conn->query($scrubSQL);
}

$statusReq = mysqli_query($conn,"SELECT * FROM `statuses` WHERE `owner_id`='$id' ORDER BY `id` DESC");
$statusReqData = mysqli_fetch_assoc($statusReq);
$currStatus = $statusReqData['body'];


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
    <title><?php echo $userRow['username']; ?> - Planet Hill</title>
  <meta charset="UTF-8">
  <meta name="description" content="<?php echo $userRow['username'] ?> - Planet Hill">
  <meta name="keywords" content="free,game">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <meta property="og:title" content="<?php echo $userRow['username']; ?> - Planet Hill" />
  <meta property="og:description" content="<?php echo $userRow['description']; ?>" />
  <meta property="og:image" content="<?php echo '/assets/images/avatars/'; ?><?php echo $userRow['id'];?><?php echo ".png?c=";?><?php echo $userRow['avatar_id']; ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="/user?id=<?php echo $userRow['id'] ?>" />
  </head>
  <body>
  <style>
      :root {
        
        
        --astro-txt: #ae73e4;
      }
    </style>
    <div class="show-for-small-only">
<div class="grid-x grid-margin-x align-middle">
<div class="shrink cell no-margin">
<div class="profile-username">
<?php echo $userRow['username']; ?>
  <?php


if($userRow['power'] >= 3) {echo '<i class="material-icons item-creator-is-verified verified-sm has-tip" data-tooltip aria-haspopup="true" data-disable-hover="false" title="This user is verified.">verified_user</i>';}
   ?>
</div>
</div>
<div class="auto cell no-margin">
<?php
  $lastonlineTime = strtotime($userRow['last_online']);
      $lastOnline = time()-$lastonlineTime;
      
          if ($lastOnline <= 300) {
            echo '<span class="online"><span class="profile-online">ONLINE</span>';
            } else {
            echo '<span class="offline"><span class="profile-offline">OFFLINE</span>';
            }
            
            echo '<span style="float: right;margin-left: -20px;margin-top:10px;"><a href="/report?type=user&id='.$userRow['id'].'"><i style="color:#444;font-size:13px;" class="fa fa-flag"></i></a></span>';
          
          
          ?>
</div>
  <?php
            $postCountSQL = "SELECT * FROM `forum_posts` WHERE `author_id`='$id'";
            $postCount = $conn->query($postCountSQL);
            $posts = $postCount->num_rows;
            
            $threadCountSQL = "SELECT * FROM `forum_threads` WHERE `author_id`='$id'";
            $threadCount = $conn->query($threadCountSQL);
            $threads = $threadCount->num_rows;
            
            $userPostCount = ($threads+$posts);
            
            echo '
<div class="shrink show-for-medium cell no-margin right">
<div class="push-15 hide-for-large"></div>
<div class="container border-r profile-info-box">
<div class="number-stat" title="'.$userPostCount.' Forum Posts">
<span>'.$userPostCount.'</span>
Forum Posts
</div>
<div class="number-stat" title="13,945 Friends">
<span>13K+</span>
Friends
</div>
<div class="number-stat" title="'.$userRow['views'].' Profile Views">
<span>'.$userRow['views'].'</span>
Profile Views
</div>
</div>
</div>
</div>
              ';
          ?>
<div class="push-25 hide-for-medium"></div>
</div>
<div class="grid-x grid-margin-x">

<?php if (!empty($currStatus) || $currStatus !== NULL ) { echo '<div class="profile-speechBubble" style="width:auto;max-width:100%;">
<div class="user-profile-status">
<i class="fa fa-quote-left" style="color:#595E6E;"></i>
<font style="padding:10px;font-weight:600;font-size:16px">'.bbcode_to_html(nl2br(htmlentities($currStatus))).'</font>
<i class="fa fa-quote-right" style="color:#595E6E;"></i>
</div>
<div class="user-profile-status">';
  if($loggedIn && $power >= 5) {echo '<a href="/users/status?id='.$statusReqData['id'].'&scrub">Scrub</a>';}
  echo'</div></div>
';
                                                    }
  ?>
  </div>

<div class="grid-x grid-margin-x">
<div class="profile-left medium-5 cell">
<div class="container md-padding avatar-container-br">
<img src="/assets/images/avatars/<?php echo $userRow['id']; ?>.png?c=<?php echo $userRow['avatar_id']; ?>" class="avatar-profile">
</div>
  <br>
  <?php
    $bannedSQL = "SELECT * FROM `moderation` WHERE `active`='yes' AND `user_id`='$id'";
    $banned = $conn->query($bannedSQL);
    if($banned->num_rows > 0) {
    echo '
  <div class="container border-r sm-padding text-center" style="background-color:#711c1c;">
            <div class="profile-info-line">
              <span><i class="material-icons" style="color:#f5f5f5;line-height:1;">info_outline</i></span><span style="color:#f5f5f5;"><strong>This player has been suspended.</strong></span>
            </div>
          </div><br>';
    }
    
    ?>   

  <?php
if($loggedIn) {
          if ($userRow['id'] != $_SESSION['id']) {
            if ($id != -1) {
            echo '<div class="col">
              <a href="#" class="button button-blue text-center"><i class="material-icons">message</i><span>Open Chat</span></a>
                                        </div>
                                  <div class="push-15"></div>';
      }
            // Check if they are friends
            $senderID = $_SESSION['id'];
        $AlreadyFriendsQ = mysqli_query($conn,"SELECT * FROM `friends` WHERE `to_id`='$id' AND `from_id`='$senderID' AND `status`='accepted' OR `to_id`='$senderID' AND `from_id`='$id' AND `status`='accepted'");
        $AlreadyFriends = mysqli_num_rows($AlreadyFriendsQ);
            
            if($AlreadyFriends<1){
              
              echo '<div class="col">
                <a href="../../../account/friends/add?id=' . $id . '" class="button button-green text-center"><i class="material-icons">add_box</i><span>Add As Friend</span></a>
                                        </div>
                                             
                <div class="push-15"></div>';
            } else {
              echo '<div class="col">
                <a href="../../../account/friends/remove?id=' . $id . '" class="button button-red text-center"><i class="material-icons">group_remove</i><span>Remove Friend</span></a>
                                        </div>
                                             
                <div class="push-15"></div>';
            }
          
          } 
}  ?>
                                        
                                        
<?php  if ($power >= 1) {
          echo'
  <a class="button button-red text-center"
             href="../../../admin/manage-users/view/'.$id.'" target="_blank">
         
            
            <i class="material-icons">gavel</i> View in Panel</a>';
          }
              
  ?>
<div class="push-25"></div>
<h5>About</h5>
<style>
      .profile-description {
        word-wrap: break-word;
        overflow-wrap: break-word;
        overflow: hidden;
      }
      </style>
<div class="container border-r md-padding">
<div class="profile-description">
<?php if (!empty($userRow['description']) || $userRow['description'] !== NULL ) {echo nl2br(htmlentities($userRow['description']));}
          
        
          ?>
</div>
</div>
  

  
  <?php
      
            $postCountSQL = "SELECT * FROM `forum_posts` WHERE `author_id`='$id'";
            $postCount = $conn->query($postCountSQL);
            $posts = $postCount->num_rows;
            $lastonline = strtotime($curDate)-strtotime($userRow['last_online']);
            $threadCountSQL = "SELECT * FROM `forum_threads` WHERE `author_id`='$id'";
            $threadCount = $conn->query($threadCountSQL);
            $threads = $threadCount->num_rows;
            
            $userPostCount = ($threads+$posts);
            
    if ($lastonline >= 300) {
    $timedif = $lastonline . " seconds";
    if ($lastonline >= 300) {$timedif = (int)gmdate('i',$lastonline) . " minutes";}
    if ($lastonline >= 3600) {$timedif = (int)gmdate('H',$lastonline) . " hours";}
    if ($lastonline >= 86400) {$timedif = (int)gmdate('d',$lastonline) . " days";}
    if ($lastonline >= 2592000) {$timedif = (int)gmdate('m',$lastonline) . " months";}
    if ($lastonline >= 31536000) {$timedif = (int)gmdate('Y',$lastonline) . " years";}
    echo '<div class="push-25"></div>
<h5>Statistics</h5>
<div class="container border-r md-padding">';
      $membershipSQL = "SELECT * FROM `membership` WHERE `active`='yes' AND `user_id`='$id'";
  $membership = $conn->query($membershipSQL);
  while($membershipRow = $membership->fetch_assoc()) {
    $memSQL = "SELECT * FROM `membership_values` WHERE `value`='".$membershipRow['membership']."'";
    $mem = $conn->query($memSQL);
    $memRow = $mem->fetch_assoc();
    echo '
      <div class="profile-info-line">
<img src="http://oisfidusoduewisfu0ehwofuiewbdf-uip3orehwughr8ghr8g9hwfondnfrifn.ml/assets/images/profile/astro.png" width="20">
<span style="color:var(--astro-txt);font-weight:600;">Astro Membership</span>
</div>';
    }
    echo'
<div class="profile-info-line">
<i class="material-icons">access_time</i>
<span>Last seen ' . $timedif . ' ago</span>
</div>
<div class="profile-info-line">
<i class="material-icons">date_range</i>
<span>Joined '.gmdate('M dS Y',strtotime($userRow['date'])).'</span>
</div>
<div class="profile-info-line">
<i class="material-icons">forum</i>
<span>'.$userPostCount.' forum posts</span>
</div>
</div>';}
            

          else {        //using plutonium:revived source is fire
              

            echo'<div class="push-25"></div>
<h5>Statistics</h5>
<div class="container border-r md-padding">';
            $membershipSQL = "SELECT * FROM `membership` WHERE `active`='yes' AND `user_id`='$id'";
  $membership = $conn->query($membershipSQL);
  while($membershipRow = $membership->fetch_assoc()) {
    $memSQL = "SELECT * FROM `membership_values` WHERE `value`='".$membershipRow['membership']."'";
    $mem = $conn->query($memSQL);
    $memRow = $mem->fetch_assoc();
    echo '
<div class="profile-info-line">
<img src="http://oisfidusoduewisfu0ehwofuiewbdf-uip3orehwughr8ghr8g9hwfondnfrifn.ml/assets/images/profile/astro.png" width="20">
<span style="color:var(--astro-txt);font-weight:600;">Astro Membership</span>
</div>';
    }
    echo'
<div class="profile-info-line">
<i class="material-icons">access_time</i>
<span>Last seen Now</span>
</div>
<div class="profile-info-line">
<i class="material-icons">date_range</i>
<span>Joined '.gmdate('M dS Y',strtotime($userRow['date'])).'</span>
</div>
<div class="profile-info-line">
<i class="material-icons">forum</i>
<span>'.$userPostCount.' forum posts</span>
</div>
</div>';}
          ?>
  
<div class="push-25"></div>
<div class="grid-x grid-margin-x align-middle">
<div class="auto cell no-margin">
<h5 style="margin:0;">Friends</h5>
</div>
<div class="shrink cell right no-margin">
<a href="https://web.archive.org/web/20191207175844/https://www.brickplanet.com/user/Brickplanet/friends/" class="button button-grey" style="padding: 3px 15px;font-size:13px;line-height:1.25;">View All</a>
</div>
</div>
<div class="push-10"></div>
<div class="container border-r">
<div class="grid-x grid-margin-x align-middle">
  <?php
      $friendsListCount = $conn->query("SELECT * FROM `friends` WHERE  `to_id` = '$id' AND `status`='accepted' OR `from_id` = '$id' AND `status`='accepted'")->num_rows;
  $friendsList = mysqli_query($conn, "SELECT * FROM `friends` WHERE  `to_id` = '$id' AND `status`='accepted' OR `from_id` = '$id' AND `status`='accepted' ORDER BY `id` DESC LIMIT 0,6");
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
                  echo "
                    <div class='large-4 medium-4 small-4 cell profile-friend text-center'>
<a href='https://web.archive.org/web/20191207175844/https://www.brickplanet.com/users/Ricko/'>
<div class='profile-friend-preview relative' style='background-image:url(http://oisfidusoduewisfu0ehwofuiewbdf-uip3orehwughr8ghr8g9hwfondnfrifn.ml/assets/images/avatars/".$friendRow['id'].".png?c=".$friendRow['avatar_id'].");background-size:cover;background-size:100px;background-position:45% 0%;-webkit-transform: scaleX(-1);-khtml-transform: scaleX(-1);-moz-transform: scaleX(-1);-ms-transform: scaleX(-1);-o-transform: scaleX(-1);transform: scaleX(-1);'>

</div>
</a>
<a href='https://web.archive.org/web/20191207175844/https://www.brickplanet.com/users/Ricko/' title='".$friendUsername."'>".$friendUsername."</a>
</div>";
    }
    } else {
              echo "<i class='material-icons user-friends-icon'>sentiment_dissatisfied</i>
          <div class='user-friends-msg'><strong>username</strong> hasn't added any friends yet.</div>
                 
          <div class='user-friends-add'><a onclick='document.getElementById('friend-status').submit();'>Send a friend request</a></div>";
          }
          ?>

</div>
<div class="push-15"></div>
</div>
<div class="push-25"></div>
<div class="grid-x grid-margin-x align-middle">
<div class="auto cell no-margin">
<h5 style="margin:0;">Groups</h5>
</div>
<div class="shrink cell right no-margin">
<a href="https://web.archive.org/web/20191207175844/https://www.brickplanet.com/user/Brickplanet/groups/" class="button button-grey" style="padding: 3px 15px;font-size:13px;line-height:1.25;">View All</a>
</div>
</div>
<div class="push-10"></div>
<div class="container border-r md-padding">
<div style="margin:0 auto;text-align:center;"><i class="material-icons" style="font-size:38px;">sentiment_dissatisfied</i></div>
<div style="font-size:13px;text-align:center;"><b>Brickplanet</b> is not a member of any groups yet.</div>
</div>
<div class="push-25"></div>
</div>
  
<div class="profile-right medium-7 cell">
<div class="show-for-medium">
<div class="grid-x grid-margin-x align-middle">
<div class="shrink cell no-margin">
<div class="profile-username">
<?php echo $userRow['username']; ?>
  <?php


if($userRow['power'] >= 3) {echo '<i class="material-icons item-creator-is-verified verified-sm has-tip" data-tooltip aria-haspopup="true" data-disable-hover="false" title="This user is verified.">verified_user</i>';}
   ?>
</div>
</div>
<div class="auto cell no-margin">
<?php
  $lastonlineTime = strtotime($userRow['last_online']);
      $lastOnline = time()-$lastonlineTime;
      
          if ($lastOnline <= 300) {
            echo '<span class="online"><span class="profile-online">ONLINE</span>';
            } else {
            echo '<span class="offline"><span class="profile-offline">OFFLINE</span>';
            }
            
            echo '<span style="float: right;margin-left: -20px;margin-top:10px;"><a href="/report?type=user&id='.$userRow['id'].'"><i style="color:#444;font-size:13px;" class="fa fa-flag"></i></a></span>';
          
          
          ?>
</div>
<?php
            $postCountSQL = "SELECT * FROM `forum_posts` WHERE `author_id`='$id'";
            $postCount = $conn->query($postCountSQL);
            $posts = $postCount->num_rows;
            
            $threadCountSQL = "SELECT * FROM `forum_threads` WHERE `author_id`='$id'";
            $threadCount = $conn->query($threadCountSQL);
            $threads = $threadCount->num_rows;
            
            $userPostCount = ($threads+$posts);
            
            echo '
<div class="shrink show-for-medium cell no-margin right">
<div class="push-15 hide-for-large"></div>
<div class="container border-r profile-info-box">
<div class="number-stat" title="'.$userPostCount.' Forum Posts">
<span>'.$userPostCount.'</span>
Forum Posts
</div>
<div class="number-stat" title="13,945 Friends">
<span>13K+</span>
Friends
</div>
<div class="number-stat" title="'.$userRow['views'].' Profile Views">
<span>'.$userRow['views'].'</span>
Profile Views
</div>
</div>
</div>
</div>
              ';
          ?>
<div class="push-25 hide-for-medium"></div>
</div>
<div class="push-25"></div>
<style>
      .achievement-image-profile {
        vertical-align: top;
        width: 65px;
        height: 65px;
        display: inline-block;
        background-size: cover;
      }

      .inline-block {
        display: inline-block;
      }

      .panel {
        margin: 0 15px;
        margin-bottom: 15px;
        padding: 12px;
        border-radius: 4px;
        background: #1E2024;
      }
      </style>
<h5>Achievements</h5>
<div class="container border-r">
<div class="push-15"></div>
<div class="grid-x grid-margin-x align-middle">
  <?php


if($userRow['power'] >= 1) {echo '
<div class="shrink cell text-center no-margin">
<div class="panel text-left inline-block achievement-card">
<span data-tooltip aria-haspopup="true" class="has-tip" data-disable-hover="false" title="Administrator">
<a href="/web/20191207175844/https://www.brickplanet.com/user/achievements/"><div class="achievement-image-profile" style="background-image:url(http://oisfidusoduewisfu0ehwofuiewbdf-uip3orehwughr8ghr8g9hwfondnfrifn.ml/assets/images/profile/admin.png)"></div></a>
</span>
</div>
</div>
  ';}
   ?>
<?php


if($userRow['bits'] >= 5000) {echo '
<div class="shrink cell text-center no-margin">
<div class="panel text-left inline-block achievement-card">
<span data-tooltip aria-haspopup="true" class="has-tip" data-disable-hover="false" title="Pile of Gold">
<a href="/web/20191207175844/https://www.brickplanet.com/user/achievements/"><div class="achievement-image-profile" style="background-image:url(http://oisfidusoduewisfu0ehwofuiewbdf-uip3orehwughr8ghr8g9hwfondnfrifn.ml/assets/images/profile/pilegold.svg)"></div></a>
</span>
</div>
</div>
  ';}
   ?>
<?php


if($userRow['beta'] >= 1) {echo '
<div class="shrink cell text-center no-margin">
<div class="panel text-left inline-block achievement-card">
<span data-tooltip aria-haspopup="true" class="has-tip" data-disable-hover="false" title="Beta Tester">
<a href="/web/20191207175844/https://www.brickplanet.com/user/achievements/"><div class="achievement-image-profile" style="background-image:url(http://oisfidusoduewisfu0ehwofuiewbdf-uip3orehwughr8ghr8g9hwfondnfrifn.ml/assets/images/profile/beta-tester.png)"></div></a>
</span>
</div>
</div>
  ';}
   ?>
<?php


if($userRow['powers'] >= 1) {echo '
<div class="shrink cell text-center no-margin">
<div class="panel text-left inline-block achievement-card">
<span data-tooltip aria-haspopup="true" class="has-tip" data-disable-hover="false" title="Administrator">
<a href="/web/20191207175844/https://www.brickplanet.com/user/achievements/"><div class="achievement-image-profile" style="background-image:url(https://web.archive.org/web/20191207175844im_/https://cdn.brickplanet.com/assets/images/profile/staff-pic.png)"></div></a>
</span>
</div>
</div>
  ';}
   ?>
<?php


if($userRow['powers'] >= 1) {echo '
<div class="shrink cell text-center no-margin">
<div class="panel text-left inline-block achievement-card">
<span data-tooltip aria-haspopup="true" class="has-tip" data-disable-hover="false" title="Administrator">
<a href="/web/20191207175844/https://www.brickplanet.com/user/achievements/"><div class="achievement-image-profile" style="background-image:url(https://web.archive.org/web/20191207175844im_/https://cdn.brickplanet.com/assets/images/profile/staff-pic.png)"></div></a>
</span>
</div>
</div>
  ';}
   ?>
<?php


if($userRow['powers'] >= 1) {echo '
<div class="shrink cell text-center no-margin">
<div class="panel text-left inline-block achievement-card">
<span data-tooltip aria-haspopup="true" class="has-tip" data-disable-hover="false" title="Administrator">
<a href="/web/20191207175844/https://www.brickplanet.com/user/achievements/"><div class="achievement-image-profile" style="background-image:url(https://web.archive.org/web/20191207175844im_/https://cdn.brickplanet.com/assets/images/profile/staff-pic.png)"></div></a>
</span>
</div>
</div>
  ';}
   ?>
<?php


if($userRow['powers'] >= 1) {echo '
<div class="shrink cell text-center no-margin">
<div class="panel text-left inline-block achievement-card">
<span data-tooltip aria-haspopup="true" class="has-tip" data-disable-hover="false" title="Administrator">
<a href="/web/20191207175844/https://www.brickplanet.com/user/achievements/"><div class="achievement-image-profile" style="background-image:url(https://web.archive.org/web/20191207175844im_/https://cdn.brickplanet.com/assets/images/profile/staff-pic.png)"></div></a>
</span>
</div>
</div>
  ';}
  
  
    $membershipSQL = "SELECT * FROM `membership` WHERE `active`='yes' AND `user_id`='$id'";
  $membership = $conn->query($membershipSQL);
  while($membershipRow = $membership->fetch_assoc()) {
    $memSQL = "SELECT * FROM `membership_values` WHERE `value`='".$membershipRow['membership']."'";
    $mem = $conn->query($memSQL);
    $memRow = $mem->fetch_assoc();
    echo '<div class="shrink cell text-center no-margin">
<div class="panel text-left inline-block achievement-card">
<span data-tooltip aria-haspopup="true" class="has-tip" data-disable-hover="false" title="'.$memRow['name'].'">
<div class="achievement-image-profile" style="background-image:url(http://oisfidusoduewisfu0ehwofuiewbdf-uip3orehwughr8ghr8g9hwfondnfrifn.ml/assets/images/profile/astro.png)"></div>
</span>
</div>
</div>';
    }
          
          ?>
</div>
</div>
  
<div class="push-25"></div>
<ul class="tabs profile-tabs" data-tabs id="tabs">
<li class="tabs-title is-active"><a href="#wall" aria-selected="true">Wall</a></li>
<li><a href="https://web.archive.org/web/20200229222300/https://www.brickplanet.com/users/Isaac/inventory" class="no-right-border">Inventory</a></li>
</ul>
<div class="tabs-content" data-tabs-content="tabs">
  <div class="container border-wh md-padding"><a name="UserWall"></a>
    <form action="" method="POST">
                <textarea class="normal-input wall-textarea" name="wall" placeholder="Write a message to the on <?php echo $userRow['username']; ?>'s wall"></textarea>
                <div class="wall-options clearfix">
                  <div class="float-right">
                    <input type="submit" class="button button-green" value="Post">
                    <input type="hidden" name="csrf_token" value="'.$_SESSION['csrf_token'].'">
                  </div>
                </div>
              </form>
<div id="wall" class="tabs-panel is-active">
</a>
  
<div id="wall">

<div class="grid-x grid-margin-x align-middle">
<div class="push-25"></div>
<ul class="pagination" role="navigation" aria-label="Pagination">
<li class="pagination-previous disabled">Previous <span class="show-for-sr">page</span></li>
<li class="current" aria-label="Page 1"><a href="https://web.archive.org/web/20180716175557/https://www.brickplanet.com/users/Isaac/?page=1#UserWall">1</a></li><li aria-label="Page 2"><a href="https://web.archive.org/web/20180716175557/https://www.brickplanet.com/users/Isaac/?page=2#UserWall">2</a></li><li aria-label="Page 3"><a href="https://web.archive.org/web/20180716175557/https://www.brickplanet.com/users/Isaac/?page=3#UserWall">3</a></li><li aria-label="Page 4"><a href="https://web.archive.org/web/20180716175557/https://www.brickplanet.com/users/Isaac/?page=4#UserWall">4</a></li><li aria-label="Page 5"><a href="https://web.archive.org/web/20180716175557/https://www.brickplanet.com/users/Isaac/?page=5#UserWall">5</a></li><li aria-label="Page 6"><a href="https://web.archive.org/web/20180716175557/https://www.brickplanet.com/users/Isaac/?page=6#UserWall">6</a></li>
<li class="pagination-next" aria-label="Next page"><a href="https://web.archive.org/web/20180716175557/https://www.brickplanet.com/users/Isaac/?page=2#UserWall">Next <span class="show-for-sr">page</span></a></li>
</ul>
</div>
<div id="games" class="tabs-panel">
<script data-cfasync="false" src="/web/20191207175844js_/https://www.brickplanet.com/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>
              var openDivs = [1];
              function toggleDiv(id) {
                $display = $("#game" + id).css("display");
                if ($display == "block") {
                  $game = $("#game" + id);
                  $game.slideToggle(500);
                  openDivs.splice(i, 1);
                }
                else {
                  for (var i=0, len = openDivs.length; i < len; i++) {
                    $game = $("#game" + openDivs[i]);
                    $game.slideToggle(500);
                    openDivs.splice(i, 1);
                  }
                  $game = $("#game" + id);
                  $game.slideToggle(500);
                  openDivs.push(id);
                }
              }

              function playGameByType(GameId, loadType) {
                loadType = (loadType == "Client" || loadType == "Workshop") ? loadType : "Client";
                $.get("https://web.archive.org/web/20191207175844/https://www.brickplanet.com/API/Engine/generateGameAuthToken.php?gameId=" + GameId + "&clientType="+loadType, function(res, status) {
                  var token = res.token;
                  window.location.assign("brickplanet://" + loadType + "_" + token);
                });
              }
            </script>
  
<div class="games-parent">
<div class="games-header" onclick="toggleDiv(19683)">BrickPlanet's Untitled Game</div>
<div class="games-content" id="game19683">
<div class="grid-x grid-margin-x align-middle">
<div class="shrink cell">
<a href="https://web.archive.org/web/20191207175844/https://www.brickplanet.com/games/19683/BrickPlanet's-Untitled-Game"><div style="width:256px;height:256px;background-color:#17171C;background-image:url(https://web.archive.org/web/20191207175844im_/https://cdn.brickplanet.com/game/thumbnails/a7591e80e76012d7fd529c8067312437.jpeg);background-size:cover;"></div></a>
</div>
<div class="auto cell">
<div class="grid-x grid-margin-x">
<div class="shrink cell no-margin">
<a href="https://web.archive.org/web/20191207175844/https://www.brickplanet.com/games/19683/BrickPlanet's-Untitled-Game" class="games-header-text">BrickPlanet's Untitled Game</a>
</div>
<div class="shrink cell no-margin">
<a href="https://web.archive.org/web/20191207175844/https://www.brickplanet.com/games/edit/19683" class="my-profile-game-settings"><i class="material-icons">settings</i></a>
</div>
</div>
<div class="games-creator-text">Created by: <a href="https://web.archive.org/web/20191207175844/https://www.brickplanet.com/users/Brickplanet/">Brickplanet</a></div>
<div class="games-divider"></div>
<div class="games-description-text"></div>
<div class="games-divider"></div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div style="height:75px;"></div>
<script>
window.onload = function() {
  getWall(0);
}



function getWall(page) {
  $("#wall").load("/users/wall?profile=<?php echo $userID; ?>&page="+page);
}
</script>
<?php
    include("SiT_3/footer.php");
  ?>
  