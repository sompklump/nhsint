<?php
require("../php/msql.php");
ob_start();
session_start();

function logoutbutton() {
	echo "<form action='' method='get'><button name='logout' type='submit'>Logout</button></form>"; //logout button
}

function loginbutton($buttonstyle = "square") {
	$button['rectangle'] = "01";
	$button['square'] = "02";
	$button = "<a href='?login'><img src='https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_".$button[$buttonstyle].".png'></a>";
	
	echo $button;
}

if (isset($_GET['login'])){
	require 'openid.php';
	try {
		require 'SteamConfig.php';
		$openid = new LightOpenID($steamauth['domainname']);
		
		if(!$openid->mode) {
			$openid->identity = 'https://steamcommunity.com/openid';
			header('Location: ' . $openid->authUrl());
		} elseif ($openid->mode == 'cancel') {
			echo 'User has canceled authentication!';
		} else {
			if($openid->validate()) { 
				$id = $openid->identity;
				$ptn = "/^https?:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
				preg_match($ptn, $id, $matches);
        $sql = "SELECT * FROM users WHERE steamid = '" . mysqli_real_escape_string($conn, $matches[1]) . "'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0) {
          if (!headers_sent()) {
            $_SESSION['steamid'] = $matches[1];
            header('Location: ?update');
            exit();
          }
        }
        else {
          echo "Failed, no access!";
        }
			} 
      else {
				echo "User is not logged in.\n";
			}
		}
	} catch(ErrorException $e) {
		echo $e->getMessage();
	}
}

if (isset($_GET['logout'])){
	require 'SteamConfig.php';
	session_unset();
	session_destroy();
	header('Location: '.$steamauth['logoutpage']);
	exit;
}

if(isset($_GET['update'])){
  if(isset($_SESSION['steamid'])) {
    unset($_SESSION['steam_uptodate']);
    require '../php/userInfo.php';
    $sql = "UPDATE users SET username='". mysqli_real_escape_string($conn, $steamprofile['personaname']) ."' WHERE steamid='{$_SESSION['steamid']}'";
    if(mysqli_query($conn, $sql)) {
      header("Location: {$steamauth['loginpage']}");
      exit;
    }
    else{
      echo "Could not update credentials!";
    }
  }
  else{
    echo "Not logged in!";
    exit();
  }
}

// Version 4.0
if(!isset($_SESSION['steamid'])) {loginbutton();}
else {logoutbutton();}
?>
