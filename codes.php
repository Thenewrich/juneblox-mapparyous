<?php
include('SiT_3/header.php');
include('SiT_3/config.php');
  $error = array();
  if (isset($_POST['submit'])) {
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    
    
    $findcodeSQL = "SELECT * FROM `promocode` WHERE `code` = '$code' AND `used` = 0";
      
    $findcode = $conn->query($findcodeSQL);

    
    if(empty($error)) {
      if ($findcode->num_rows == 0) {
          $error[] = 'The promocode is Invalid! It has probably gotten expired or been used.';
          
      } elseif($findcode->num_rows > 0) {
        
        $codeRow = $findcode->fetch_assoc();
        $codeID = $codeRow['id'];
        $citemID = $codeRow['item_earn'];
        
        $updatecodeSQL = "UPDATE `promocode` SET `used` = '0' WHERE `id` = '$codeID' ";
        $updatecode = $conn->query($updatecodeSQL);
        
        $itemcode = rand(4);
        $additemcodeSQL = "INSERT INTO `crate` (`id`,`user_id`,`item_id`,`serial`,`payment`,`price`,`date`,`own`) VALUES (NULL,'$UID','$citemID','0','bits','0','$curDate','yes')";
        $additemcode = $conn->query($additemcodeSQL);
        $success[] = 'Successfully redeemed promocode!';
      }
    }
  }
    if(empty($error)) {
      }
?>
<!DOCTYPE html>
  <head>
    <title>Promocode - Shut-Hill</title>
  </head>
  <body>
    <div id="body">
      <div id="box">
        <div id="subsect">
          <center><h3>Promocodes</h3>
        </div>
        <?php
          if(!empty($error)) {
            echo '<div style="background-color:#EE3333;margin:10px;padding:5px;color:white;">';
            foreach($error as $line) {
              echo $line.'<br>';
            }
            echo '</div>';
          }
        ?>
            <?php
          if(!empty($success)) {
            echo '<div style="background-color:#6aad5f;margin:10px;padding:5px;color:white;">';
            foreach($success as $line) {
              echo $line.'<br>';
            }
            echo '</div>';
          }
        ?>
      <form action="" method="POST">
          <center><input type="text" name="code">
          <br>
          <br>
          <center><input type="submit" name="submit" value="Redeem">
        </form>
        <br>
        <br>
        <h5>Promocodes can give you items!</h5>
</div>
</div>

<?php
include('SiT_3/footer.php')
?>
