<?php

  include("connect_status_script.php");

  $connection_status = connectDB();

  if(!$connection_status){
    //header("location: ./error_db_page.php")
    $new_url = "server_main/index.php?";
    $first = 0;
    foreach ($_GET as $key => $value) {
      if ($first==0){
        $new_url = $new_url . $key . "=" . $value;
        $first = 1;
      } else {
        $new_url = $new_url . "&" .  $key . "=" . $value;
      }

      $new_url = $new_url . "&status=error_code";
      header("location: ".$new_url);
      exit;

     }

    //echo "<h3 align='center'>Le service [ID] ne marche pas pour l'instant, essayer plus tard </h3>";
  } else {
    $user_id = $_GET['userid'];
    $statusquery = "SELECT status_played FROM users WHERE id =" . $user_id;

    $result = mysqli_query($connection_status, $statusquery);

    if (!$result) {
      $new_url = "server_main/index.php?";
      $first = 0;
      foreach ($_GET as $key => $value) {
        if ($first==0){
          $new_url = $new_url . $key . "=" . $value;
          $first = 1;
        } else {
          $new_url = $new_url . "&" .  $key . "=" . $value;
        }
       }
       $new_url = $new_url . "&status=error_code";
       header("location: ".$new_url);
       exit;
      //echo "<h3 align='center'>Le service [ID] ne marche pas pour l'instant, essayer plus tard.</h3>";
    } else {
  		$row = mysqli_fetch_row($result);
      if ($row[0]==""){
        $new_url = "server_main/index.php?";
        $first = 0;
        foreach ($_GET as $key => $value) {
          if ($first==0){
            $new_url = $new_url . $key . "=" . $value;
            $first = 1;
          } else {
            $new_url = $new_url . "&" .  $key . "=" . $value;
          }
         }
         $new_url = $new_url . "&status=not_found";
         header("location: ".$new_url);
        exit;
      } else {

        $new_url = "server_main/index.php?";
        $first = 0;
        foreach ($_GET as $key => $value) {
          if ($first==0){
            $new_url = $new_url . $key . "=" . $value;
            $first = 1;
          } else {
            $new_url = $new_url . "&" .  $key . "=" . $value;
          }
         }

         if($row[0]==0){
           $new_url = $new_url . "&status=not_played";
         } else {
           $new_url = $new_url . "&status=played";
         }

         header("location: ".$new_url);
         exit;
      }
    }
  }

}
?>
