<?php

// Include the API
require 'lastfmapi/lastfmapi.php';

// Get the session auth data
//$file = fopen('../auth.txt', 'r');
// Put the auth data into an array
$authVars = array(
    'apiKey' => 'c8f21998ab06ac98bf163a9223592a82',//'trim(fgets($file))',
    'secret' => '76092b16a23a0160bd29ee42e9339fdb',//trim(fgets($file)),
    'username' => 'dakshinai',//trim(fgets($file)),
    'sessionKey' => 'test',//trim(fgets($file)),
    'subscriber' => 'dakshinai'//trim(fgets($file))
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

$methodVars = array(
    'user' => 'RJ'
);

if ( $info = $userClass->getInfo($methodVars) ) {
	echo '<b>Data Returned</b>';
	echo '<pre>';
	print_r($info);
	echo '</pre>';
    
    
}
else {
	die('<b>Error '.$userClass->error['code'].' - </b><i>'.$userClass->error['desc'].'</i>');
}

?>