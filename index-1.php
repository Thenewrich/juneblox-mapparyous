<?php
  include("../../site/bannedheader.php");

    $bannedSQL = "SELECT * FROM `moderation` WHERE `active`='yes' AND `user_id`='$currentID'";
    $banned = $conn->query($bannedSQL);
    if($banned->num_rows != 0) {//they are banned
      $URI = $_SERVER['REQUEST_URI'];
      if ($URI != '/banned/') {
      header('Location: /banned/');
    
      $bannedRow = $banned->fetch_assoc();
      $banID = $bannedRow['id'];
      $currentDate = strtotime($curDate);
      $banEnd = strtotime($bannedRow['issued'])+($bannedRow['length']*60);
      if($bannedRow['length'] <= 0) {$title = "Warning";}
      elseif($bannedRow['length'] < 60) {$title = "".$bannedRow['length']." minutes";}
      elseif($bannedRow['length'] >= 60) {$title = "".round($bannedRow['length']/60)." hours";}
      elseif($bannedRow['length'] >= 1440) {$title = "".round($bannedRow['length']/1440)." days";}
      elseif($bannedRow['length'] >= 43200) {$title = "".round($bannedRow['length']/43200)." months";}
      elseif($bannedRow['length'] >= 525600) {$title = "".round($bannedRow['length']/525600)." years";}
      elseif($bannedRow['length'] >= 36792000) {$title = "Terminated";}
    
      echo '<head>
          <title>Suspended - Planet Hill</title>
        <style>
      .suspended-header-icon i {
        font-size: 18px;
        margin-right: 10px;
        vertical-align: middle;
      }
      
      .suspended-divider {
        margin: 15px 0;
        background: #373840;
        width: 100%;
        height: 1px;
      }
      
      .suspended-content {
        background: #383945;
        box-shadow: 0 1px 1px #121721;
        padding: 15px;
        border-radius: 5px;
      }
    </style>
        </head>
        <body>
        
        <div class="grid-x grid-margin-x">
      <div class="large-8 large-offset-2 cell">
        <div class="container-header md-padding">
          <strong><span class="suspended-header-icon"><i class="material-icons">gavel</i></span><span>Suspension issued against account</span></strong>
        </div>
        <div class="container border-wh md-padding">
          <div>A suspension has been issued against your account for violating our community guidelines and/or terms and conditions.</div>
          <div class="suspended-divider"></div>
                    <div class="push-25"></div>

          <div class="grid-x grid-margin-x">
            <div class="large-4 cell"><p>Length: <strong>'.$title.'</strong></p></div>
            <div class="large-4 cell"><p>Reason provided: <strong>' . $bannedRow['admin_note'] . '</strong></p></div>
            <div class="large-4 cell"><p>Issued on: <strong>' . gmdate('m/d/Y',strtotime($bannedRow['issued'])) . '</strong></p></div>
          </div>

                    <div class="suspended-divider"></div>
                    <div class="push-25"></div>
                    </div>';

        if($currentDate >= $banEnd) {
        if(isset($_POST['unban'])) {
          $unbanSQL = "UPDATE `moderation` SET `active`='no' WHERE `id`='$banID'";
          $unban = $conn->query($unbanSQL);
          echo'<script>location.reload();</script>';
          //header("Refresh:0");
        }
        echo '
        <form action="" method="POST">
          <input type="submit" class="button button-green left" name="unban" value="Reactivate">
        </form>';
         } else {
        
      }
        echo'<form action="/auth/logout" method="POST">
          <input type="submit" class="button button-grey right" name="logoutButton" value="Logout Here">
        </form>
      </div>
    </div>';
        include("footer.php");
      echo '
             
            </div>
          </div>
        </body>';

      exit;
    }
  }
  
  

        



echo'</div>
</div>
  
          ';
      
      
     
  
?>