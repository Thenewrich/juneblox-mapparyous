<?php
  include("SiT_3/config.php");
  include("SiT_3/header.php");
  
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
  {
    $clansSQL = "SELECT * FROM `clans_members` WHERE `user_id` = '$userID' AND `status` = 'in'";
    $clansQ = $conn->query($clansSQL);
    $clans = $clansQ->num_rows;
    
    if($clans < $membershipRow['join_clans']) {
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
    } else {
      echo "You can only join up to 5 clans";
    }
  }
  if(isset($_POST['leave']))
  {
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
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> <?php echo ''.$clanRow['name'].''; ?> - Good-Hill</title>
  </head>
<div class="col-10-12 push-1-12">
<div class="card">
<div class="top" style="position:relative;">
<span class="clan-title"><?php echo ''.$clanRow['name'].''; ?></span><b><?php echo '['.$clanRow['tag'].']'; ?></b>
</div>
<div class="content" style="position:relative;">
<div class="col-3-12">
<div class="clan-img-holder mb1">
<img class="width-100" src="/clans/icons/<?php echo ''.$clanRow['id'].''; ?>.png">
</div>
<div class="dark-gray-text bold">
<div>
Owned by
<b>
<a href="/user?id=<?php echo $ownerID; ?>" class="black-text"><?php echo $ownerRow['username']; ?></a>
</b>
</div>
<div>Placeholder Members</div>
</div>
</div>
<div class="col-9-12">
<div class="clan-description darkest-gray-text bold">
<?php echo str_replace("\n","<br>",htmlentities($clanRow['description'])); ?>
</div>
</div>
</div>
</div>
<div class="col-1-1 tab-buttons">
<button class="tab-button blue w600" data-tab="1">MEMBERS</button>
<button class="tab-button transparent w600" data-tab="2">RELATIONS</button>
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
<div class="rank-select" style="width:150px;float:right;">
<select class="push-right select">
<option value="1">Pending...</option>
<option value="2">Common Username</option>
<option value="3">Uncommon Username</option>
<option value="4">Rare Username</option>
<option value="5">Legendary Username</option>
<option value="6">Mythic Username</option>
<option value="7">Deleted Username</option>
<option value="8">Epic Username</option>
<option value="9">VIP</option>
<option value="10">Special Username</option>
<option value="11">——————</option>
<option value="12">Supervisor</option>
<option value="98">Co-Owner</option>
<option value="99">Buiider’s alts</option>
<option value="100">Owner</option>
</select>
</div>
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
<a href="/web/20200727123712/https://www.brick-hill.com/clan/26/">
<div class="profile-card">
<img src="https://web.archive.org/web/20200727123712im_/https://brkcdn.com/v2/images/clans/8e94702360d172691736fb90b8437231.png">
<span class="ellipsis">Namesnipe.</span>
</div>
</a>
<a href="/web/20200727123712/https://www.brick-hill.com/clan/143/">
<div class="profile-card">
<img src="https://web.archive.org/web/20200727123712im_/https://brkcdn.com/v2/images/clans/9be1402fba5082c4f2198c8415341db4.png">
<span class="ellipsis">[Deleted 143]</span>
</div>
</a>
<a href="/web/20200727123712/https://www.brick-hill.com/clan/242/">
<div class="profile-card">
<img src="https://web.archive.org/web/20200727123712im_/https://brkcdn.com/v2/images/clans/242.png">
<span class="ellipsis">Canada</span>
</div>
</a>
<a href="/web/20200727123712/https://www.brick-hill.com/clan/69/">
<div class="profile-card">
<img src="https://web.archive.org/web/20200727123712im_/https://brkcdn.com/v2/images/clans/69.png">
<span class="ellipsis">C00L KIDDS</span>
</div>
</a>
<a href="/web/20200727123712/https://www.brick-hill.com/clan/449/">
<div class="profile-card">
<img src="https://web.archive.org/web/20200727123712im_/https://brkcdn.com/v2/images/clans/48e3e46331ccc446ed4fa6079893331f.png">
<span class="ellipsis">Forum󠀡󠀡</span>
</div>
</a>
<a href="/web/20200727123712/https://www.brick-hill.com/clan/477/">
<div class="profile-card">
<img src="https://web.archive.org/web/20200727123712im_/https://brkcdn.com/v2/images/clans/477.png">
<span class="ellipsis">baked beans</span>
</div>
</a>
</div>
</fieldset>
<fieldset class="fieldset red">
<legend>Enemies</legend>
<div class="p1 overflow-auto">
<a href="/web/20200727123712/https://www.brick-hill.com/clan/124/">
<div class="profile-card">
<img src="https://web.archive.org/web/20200727123712im_/https://brkcdn.com/default/declined.png">
<span class="ellipsis">[Deleted 124]</span>
</div>
</a>
<a href="/web/20200727123712/https://www.brick-hill.com/clan/361/">
<div class="profile-card">
<img src="https://web.archive.org/web/20200727123712im_/https://brkcdn.com/v2/images/clans/361.png">
<span class="ellipsis">It&#039;s 123Master</span>
</div>
</a>
<a href="/web/20200727123712/https://www.brick-hill.com/clan/268/">
<div class="profile-card">
<img src="https://web.archive.org/web/20200727123712im_/https://brkcdn.com/v2/images/clans/268.png">
<span class="ellipsis">The Noob Kingdom</span>
</div>
</a>
<a href="/web/20200727123712/https://www.brick-hill.com/clan/180/">
<div class="profile-card">
<img src="https://web.archive.org/web/20200727123712im_/https://brkcdn.com/v2/images/clans/180.png">
<span class="ellipsis">Roblox</span>
</div>
</a>
<a href="/web/20200727123712/https://www.brick-hill.com/clan/263/">
<div class="profile-card">
<img src="https://web.archive.org/web/20200727123712im_/https://brkcdn.com/v2/images/clans/263.png">
<span class="ellipsis">RetroBLOX</span>
</div>
</a>
<a href="/web/20200727123712/https://www.brick-hill.com/clan/288/">
<div class="profile-card">
<img src="https://web.archive.org/web/20200727123712im_/https://brkcdn.com/v2/images/clans/f96826f9255c968f457a78f5ee2af0be.png">
<span class="ellipsis">ROBLOXIAN YouTuber Golden lol :D</span>
</div>
</a>
</div>
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
Store
</div>
<div class="content">
Feature coming soon
</div>
</div>
</div>
</div>
</div>
</div>
<script>
        function loadMembers(rank = '', page = 1) {
            if(rank == '')
                rank = $('.rank-select option').first().val()
            $.getJSON(`/api/clans/members/339/${rank}/${page}`, (data) => {
                $('.member-holder').html();
                let html = '';
                for(let i in data.data) {
                    let user = data.data[i].user
                    html += `<a href="/user/${user.id}/">
                                <div class="col-1-5 mobile-col-1-2">
                                    <img style="width:145px;height:145px;" src="${BH.avatarImg(user.avatar_hash)}">
                                    <div class="ellipsis">${user.username}</div>
                                </div>
                            </a>`
                }
                $('.member-pages').html();
                let pagehtml = '';
                for(let i of data.pages.pages) {
                    pagehtml += `<a class="page${i == page ? ' active' : ''}">${i}</a>`
                }
                $('.member-pages').html(pagehtml)
                $('.member-holder').html(html)
            })
        }
        $('.rank-select select').on('change', (e) => {
            loadMembers($('option:selected', e.target).val())
        })
        $(document).on('click', '.member-pages a', function() {
            loadMembers($('.rank-select select option:selected').val(), $(this).text())
        })
        loadMembers(1)
    </script>
</div>
<div class="col-10-12 push-1-12">
<div style="text-align:center;margin-top:20px;padding-bottom:25px;">
<script async src="//web.archive.org/web/20200727123712js_/https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px;" data-ad-client="ca-pub-8506355182613043" data-ad-slot="4292052018"></ins>
<script>
                (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
</div>
</div>
</div>
<?php
  include("SiT_3/footer.php");
  ?>