<?php
  include('SiT_3/config.php');
  include('SiT_3/header.php');
?>
<!DOCTYPE html>
  <head>
    <title>Trade - Builder Land</title>
  </head>
  <body>
    <div id="body">
      <div id="box">
      <div id="crate"></div>
      </div>
    </div>
    <script>
      window.onload = function() {
        getPage(1);
      };
      
      function getPage(page) {
        $("#crate").load("http://builderland.ct8.pl/trade_crate.php?id="+<?php echo $id; ?>+"&type="+type+"&page="+page);
      };
    </script>
  </body>
</html>