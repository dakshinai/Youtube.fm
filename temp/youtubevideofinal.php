<?php

// Call set_include_path() as needed to point to your client library.
require_once 'google-api-php-client-0.6.7\google-api-php-client\src\Google_Client.php';
require_once 'google-api-php-client-0.6.7\google-api-php-client\src\contrib\Google_YouTubeService.php';
session_start();

/* You can acquire an OAuth 2 ID/secret pair from the API Access tab on the Google APIs Console
 <http://code.google.com/apis/console#access>
For more information about using OAuth2 to access Google APIs, please visit:
<https://developers.google.com/accounts/docs/OAuth2>
Please ensure that you have enabled the YouTube Data API for your project. */
$OAUTH2_CLIENT_ID = '826178611003-rt8cu1i1093nnntbsee56qa7crm10fv2.apps.googleusercontent.com';
$OAUTH2_CLIENT_SECRET = 'nv2NHQYJibhc_22xUz-whW6D';

$client = new Google_Client();
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
$redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
    FILTER_SANITIZE_URL);
$client->setRedirectUri($redirect);
$flag = 0;
$videoid='';
$temp = $_GET["xx"];
print_r($_GET["xx"]);
//$temp = "pull me under";
// YouTube object used to make all API requests.
$youtube = new Google_YoutubeService($client);

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
      // Trying to get playlists in my account
    $playlistItemResponse = $youtube->playlists->listPlaylists('snippet', array(
      'mine' => 'true',
    ) );
    foreach ($playlistItemResponse['items'] as $playlistItem) 
    {
        $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
        'playlistId' => $playlistItem['id']));
        foreach ($playlistItemsResponse['items'] as $playlistItems) {
          //  print_r($playlistItems['snippet']['title']);
            if(strpos($playlistItems['snippet']['title'],$temp)==true)
            {
                $videoid = $playlistItems['snippet']['resourceId']['videoId'];
                $url=('//www.youtube.com/embed/'.$playlistItems['snippet']['resourceId']['videoId']);
             //   print_r('sssssssss');
                $flag = 1;
                break;
            }
         // print_r($playlistItems['snippet']['resourceId']['videoId']);
      }
    }
    if($flag == 0)
    {
        $searchResponse = $youtube->search->listSearch('id,snippet', array(
      'q' => $temp,
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
            $videoid = $searchResult['id']['videoId'];
            $url=('//www.youtube.com/embed/'.$searchResult['id']['videoId']);
            print_r($url);
          break;
        case 'youtube#channel':
          $channels .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],
            $searchResult['id']['channelId']);
            $videoid = $searchResult['id']['videoId'];
            $url=('//www.youtube.com/embed/'.$searchResult['id']['channelId']);
            print_r($url);
          break;
        case 'youtube#playlist':
          $playlists .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],
            $searchResult['id']['playlistId']);
          break;
      }
    }
  } 
  } catch (Google_ServiceException $e) {
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

<!doctype html>
<html>
<head>
<title>New Playlist</title>
</head>
<body>
  <?=$htmlBody?>
    <div align="center"><iframe width="420" height="315" src=<?=$url?>> frameborder="0" allowfullscreen></iframe>
</div>
    <?php print_r($videoid);?>
    <p>Would u like to add this video to one of your playlist? If so click the button.</p>
    <form action="success.php" method="post">
            <input type="hidden" name="good" value=<?=$videoid?>/>
            <input type="submit" value="youtube video!"/>
        </form>
</body>
</html>