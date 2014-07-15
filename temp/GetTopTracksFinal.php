<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
<head>
<title>Top Tracks</title>
<link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
<img class="logo" src="lastfmgoogle.png">     
<?php
session_start();

echo "<div>The selected user name is: ".$_SESSION["username"]."</div><br>"; 

// Include the API
require 'lastfmapi/lastfmapi.php';

// Put the auth data into an array
$authVars = array(
	'apiKey' => 'c8f21998ab06ac98bf163a9223592a82',
	'secret' => '76092b16a23a0160bd29ee42e9339fdb',
	'username' => 'dakshinai',
	'sessionKey' => 'test',
	'subscriber' => 'dakshinai'
);
$config = array(
	'enabled' => true,
	'path' => 'lastfmapi/',
	'cache_length' => 1800
);
// Pass the array to the auth class to eturn a valid auth
$auth = new lastfmApiAuth('setsession', $authVars);

$apiClass = new lastfmApi();
$userClass = $apiClass->getPackage($auth, 'user', $config);

// Setup the variables
$methodVars = array(
	'user' => $_SESSION["username"]
);

if ( $tracks = $userClass->getTopTracks($methodVars) ) {
	//echo '<b>Data Returned</b>';
	//echo '<pre>';
	//print_r($tracks);
	//echo '</pre>';?>
 <?php foreach ($tracks as $track) { ?>
 <div class="resultitem">
<p>
Title: <?= $track['name'] ?><br />
Artist: <?= $track['artist']['name'] ?><br />
link: <a href=<?= $track['url']?>>LINK </a> <br/>
<?php $combination = str_replace(' ', '',$track['name'].''.$track['artist']['name']); 
print_r($combination);
//$_SESSION["combo"]=$combination; 
?>
    <form action="youtubevideofinal.php" method="GET">
            <input  name="xx" value=<?=$combination?>/>
            
            <input type="submit" value="youtube video!"/>
        </form>

</p>
</div>
<?php } 

}
else {
	die('<b>Error '.$userClass->error['code'].' - </b><i>'.$userClass->error['desc'].'</i>');
}

?>

</body>
</html>