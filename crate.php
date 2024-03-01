<?php
include("SiT_3/config.php");
if(isset($_GET['id'])) {
  $userID = mysqli_real_escape_string($conn,$_GET['id']);
  if(isset($_GET['type']) && isset($_GET['page'])) {
    $page = mysqli_real_escape_string($conn,$_GET['page']);
    $type = mysqli_real_escape_string($conn,$_GET['type']);
    
    
    $typeName = array('all' => 'items','hat' => 'hats','tool' => 'tools','tshirt' => 't-shirts','face' => 'faces','shirt' => 'shirts','pants' => 'pants','head' => 'heads');
    if($type == 'all') {$type = "%%";}
  
    $itemsSQL = "SELECT `item_id` FROM `crate` WHERE `user_id`='$userID' AND `own`='yes'";
    $itemsResult = $conn->query($itemsSQL);
    
    $invItems = array();
    $invItems[] = "0";
    while($row=$itemsResult->fetch_assoc()){
      $invItems[] = $row['item_id'];
    }
    
    $page = max($page,1);
  
    $limit = ($page-1)*8;
    $shopItemsSQL = "SELECT * FROM `shop_items` WHERE `id` IN (".implode(',',array_map('intval',$invItems)).") AND `type` LIKE '$type' ORDER BY `id` DESC LIMIT $limit,8";
    $shopItems = $conn->query($shopItemsSQL);
    
    $shopCountSQL = "SELECT * FROM `shop_items` WHERE `id` IN (".implode(',',array_map('intval',$invItems)).") AND `type` LIKE '$type'";
    $shopCount = $conn->query($shopCountSQL);
    $items = $shopCount->num_rows;
    
    if($items < 1) {echo 'This user has no '.$typeName[$type].' in their crate';}
    echo '<div style="width:100%;display:block;overflow:auto;">';
    
    $r = 0;
    while($itemRow=$shopItems->fetch_assoc()){ 
      $r++;
      //if($r >= (($page+1)*9)) {break;}
      //if($r >= ($page*9)) {
        if (strlen(htmlentities($itemRow['name'])) >= 20) {$dot = '...';} else {$dot = '';}
        if ($itemRow['approved'] == 'yes') {$thumbnail = $itemRow['id'];}
        elseif ($itemRow['approved'] == 'declined') {$thumbnail = 'declined';}
        else {$thumbnail = 'pending';}
        /*echo '<div style="clear:right;float:left;display:inline-block;text-align:left;margin-right:20px;">
        <a style="color:#000;" href="/shop/item/'.$itemRow['id'].'">
        <img id="shopItem" src="/shop_storage/thumbnails/'.$thumbnail.'.png"><br>'.substr(htmlentities($itemRow['name']),0,18).$dot.'</a>
        </div>';*/
      
      /*echo'<div style="clear:right;float:left;display:inline-block;text-align:left;margin-right:20px;"><span style="position:absolute;"></span><a style="color:#000;" href="/shop/item/'.$itemRow['id'].'">
<img id="shopItem" src="/shop_storage/thumbnails/'.$thumbnail.'.png">
<br>'.substr(htmlentities($itemRow['name']),0,18).$dot.'</a>
</div>';*/
      echo'<li class="col-1-5 mobile-col-1-2" style="padding-left: 3px; padding-right: 3px;">
        <a href="/shop/item/'.$itemRow['id'].'/"><div class="profile-card crate">
        <!--v-if--><img src="/shop_storage/thumbnails/'.$thumbnail.'.png">
        <div class="ellipsis" style="color: rgb(118, 118, 118); height: 19px;">'.substr(htmlentities($itemRow['name']),0,18).$dot.'</div></div></a></li>';
      if($r == 4) {echo '<br>';}
      //}
    }
    
    echo '</div><div style="width:100%;display:block;overflow:auto;">';
    
    if(($items/8) > 1) {
      for($i = 0; $i < ($items/8); $i++)
      {
        echo '<a style="color:#000;" onclick="getPage(\''.$type.'\', '.($i+1).')">'.($i+1).'</a> ';
      }
    }
    
    echo '</div>';
  } else {
    echo 'Error loading inventory';
  }
} else {
  header("Location: ../login/");
}
?>