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

  /* Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
  Google APIs Console <http://code.google.com/apis/console#access>
  Please ensure that you have enabled the YouTube Data API for your project. */
  $DEVELOPER_KEY = 'AIzaSyCsfz-K3dcECSBoyn-eYn0d3p2y3vdh6Ds';

  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);

  $youtube = new Google_YoutubeService($client);

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
            $url=('//www.youtube.com/embed/'.$searchResult['id']['videoId']);
          //  print_r($url);
          break;
        case 'youtube#channel':
          $channels .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],
            $searchResult['id']['channelId']);
            $url=('//www.youtube.com/embed/'.$searchResult['id']['channelId']);
           // print_r($url);
          break;
        case 'youtube#playlist':
          $playlists .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],
            $searchResult['id']['playlistId']);
          break;
      }
    }
  } catch (Google_ServiceException $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  }?>

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

?>

</body>
</html>