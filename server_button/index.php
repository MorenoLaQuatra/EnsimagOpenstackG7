<?php

if(!isset($_GET['userid'])){
  $new_url = $_SERVER['HTTP_REFERER'];
  $new_url = substr($new_url,0,strrpos($new_url,'index.php?'));
  header("location: " . $new_url . "error_db_page.php");
  exit;
}

include("connect_status_script.php");

$connection_status = connectDB();

if(!$connection_status){
  $new_url = $_SERVER['HTTP_REFERER'];
  $new_url = substr($new_url,0,strrpos($new_url,'index.php?'));
  header("location: " . $new_url . "error_db_page.php");
  exit;
  //echo "<h3 align='center'>Le service [ID] ne marche pas pour l'instant, essayer plus tard </h3>";
} else {

  //INTERFACE Worker
  $user_id = $_GET['userid'];

  $selectQuery = "SELECT firstname FROM ps_customer WHERE id_customer=".$user_id;

  $result = mysqli_query($connection_status, $selectQuery);
  $row = mysqli_fetch_row($result);

  if ($row[0]==""){
    $new_url = $_SERVER['HTTP_REFERER'];
    $new_url = substr($new_url,0,strrpos($new_url,'index.php?'));
    header("location: " . $new_url . "error_not_exist.php");
    exit;
  }

  $answer = file_get_contents("server_worker/play/".$user_id);
  /*
  img: base64 json image
  price: name of the price
  */
  //$image_array = json_decode($answer);
  //$image = base64_decode($image_array['img']);

  $address_swift = file_get_contents("address.swift");
  $curl = curl_init($address_swift."/".$user_id.".jpg");

  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($answer));

  // Make the REST call, returning the result
  $response = curl_exec($curl);
  if (!$response) {
    $new_url = $_SERVER['HTTP_REFERER'];
    $new_url = substr($new_url,0,strrpos($new_url,'index.php?'));
    header("location: " . $new_url . "error_dbimage_page.php");
  }


  $updateStatusQuery = "INSERT INTO ps_played (id_customer, status) VALUES (".$user_id.", true)";
  $result = mysqli_query($connection_status, $updateStatusQuery);

  if (!$result) {

    $curl = curl_init("server_picture/".$user_id.".jpg");
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: image/png'));

    $new_url = $_SERVER['HTTP_REFERER'];
    $new_url = substr($new_url,0,strrpos($new_url,'index.php?'));
    header("location: " . $new_url . "error_db_page.php");
    exit;
  } else {



    string shell_exec ( "curl -s --user 'api:key-f94069c3ff1b0faae0527d532e6a3d57' \
    https://api.mailgun.net/v3/sandboxa37f743f990d4d989c69f315dc097fdb.mailgun.org/messages \
    -F from='Mailgun Sandbox <postmaster@sandboxa37f743f990d4d989c69f315dc097fdb.mailgun.org>' \
    -F to='Moreno <djlinux93@gmail.com>' \
    -F subject='Game Played' \
    -F text='User ".$user_id." played the game '" )

    

  }



}



 ?>
