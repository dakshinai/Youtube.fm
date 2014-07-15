<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
<head>
<title>Top Tracks</title>
</head>
<body>

<?php
session_start();
echo "The selected user name is: ".$_SESSION["username"]; 

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
//if ($_GET['q'] && $_GET['maxResults']) {
  // Call set_include_path() as needed to point to your client library.
require_once 'google-api-php-client-0.6.7/google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client-0.6.7/google-api-php-client/src/contrib/Google_YouTubeService.php';
require_once 'google-api-php-client-0.6.7\google-api-php-client\src\contrib\Google_YouTubeService.php';
//session_start();

/* You can acquire an OAuth 2 ID/secret pair from the API Access tab on the Google APIs Console
 <http://code.google.com/apis/console#access>
For more information about using OAuth2 to access Google APIs, please visit:
<https://developers.google.com/accounts/docs/OAuth2>
Please ensure that you have enabled the YouTube Data API for your project. */
$OAUTH2_CLIENT_ID = '826178611003-rt8cu1i1093nnntbsee56qa7crm10fv2.apps.googleusercontent.com';
$OAUTH2_CLIENT_SECRET = 'nv2NHQYJibhc_22xUz-whW6D';
$url = '';
$client = new Google_Client();
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
$redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
    FILTER_SANITIZE_URL);
$client->setRedirectUri($redirect);

  /* Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
  Google APIs Console <http://code.google.com/apis/console#access>
  Please ensure that you have enabled the YouTube Data API for your project. */
  $DEVELOPER_KEY = 'AIzaSyCsfz-K3dcECSBoyn-eYn0d3p2y3vdh6Ds';

  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);

  $youtube = new Google_YoutubeService($client);
  $flag = 0;
  if (isset($_GET['code'])) {
  if (strval($_SESSION['state']) !== strval($_GET['state'])) {
    die('The session state did not match.');
  }

  $client->authenticate();
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: ' . $redirect);
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

// Check if access token successfully acquired
if ($client->getAccessToken()) {
  try {

if ( $tracks = $userClass->getTopTracks($methodVars) ) {
	//echo '<b>Data Returned</b>';
	//echo '<pre>';
	//print_r($tracks);
	//echo '</pre>';?>
 <?php foreach ($tracks as $track) { ?>
<p>
Title: <?= $track['name'] ?><br />
Artist: <?= $track['artist']['name'] ?><br />
link: <a href=<?= $track['url']?>>LINK </a> <br/>
<?php try {
    print_r('vvvvvvvvvvvvvvvvvvvvvvv');
    print_r('2222222222222222222');
    $playlistItemResponse = $youtube->playlists->listPlaylists('snippet', array(
      'mine' => 'true'
    ) );
    print_r('bbbbbbbbbbbbbbbbbbbbbb');
    print_r($playlistItemResponse);
    foreach ($playlistItemResponse['items'] as $playlistItem) {
        $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
        'playlistId' => $playlistItem['id']));
              foreach ($playlistItemsResponse['items'] as $playlistItems) {
        print_r($playlistItems['snippet']['title']);
         if(strcmp($playlistItems['snippet']['title'],"Charlie bit my finger - again !")==0)
        {
            print_r('sssssssss');
            $flag = 1;
            break;
        }
        print_r('wwwwwwwwwwwwwwwwwwwwwwww');
          print_r($playlistItems['snippet']['resourceId']['videoId']);
        $url=$playlistItems['snippet']['resourceId']['videoId'];
      }
    }
    if ($flag == 0){
    $searchResponse = $youtube->search->listSearch('id,snippet', array(
      'q' => $track['name'],
      'maxResults' => 1,
    ));

    $videos = '';
    $channels = '';
    $playlists = '';

    foreach ($searchResponse['items'] as $searchResult) {
      switch ($searchResult['id']['kind']) {
        case 'youtube#video':
          $videos .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],
            $searchResult['id']['videoId']);
            $url=$searchResult['id']['videoId'];
           print_r($url);
          break;
        case 'youtube#channel':
          $channels .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],
            $searchResult['id']['channelId']);
            $url=$searchResult['id']['channelId'];
            print_r($url);
          break;
        case 'youtube#playlist':
          $playlists .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],
            $searchResult['id']['playlistId']);
          break;
      }
    }}
  } catch (Google_ServiceException $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
 }
 print_r($url);
 ?>

    <form action="youtubevideo.php" method="post">
            <input type="hidden" name="xx" value=<?=$url?>/>
            <input type="submit" value="youtube video!"/>
        </form>

</p>
<?php } 

  }
  
else {
	die('<b>Error '.$userClass->error['code'].' - </b><i>'.$userClass->error['desc'].'</i>');
  }
  
}
   catch (Google_ServiceException $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  }

  $_SESSION['token'] = $client->getAccessToken();
} else {
  // If the user hasn't authorized the app, initiate the OAuth flow
  $state = mt_rand();
  $client->setState($state);
  $_SESSION['state'] = $state;

  $authUrl = $client->createAuthUrl();
  $htmlBody = <<<END
  <h3>Authorization Required</h3>
  <p>You need to <a href="$authUrl">authorize access</a> before proceeding.<p>
END;
}
?>

?>

</body>
</html>