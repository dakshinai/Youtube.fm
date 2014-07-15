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
//print_r(strlen($_POST["good"])-1);
 //print_r(substr($_POST["good"],0,-1));
$client = new Google_Client();
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
$redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
    FILTER_SANITIZE_URL);
$client->setRedirectUri($redirect);

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
    print_r($_POST["good"]);
  try {
      
    $playlistItemResponse = $youtube->playlists->listPlaylists('snippet', array(
      'mine' => 'true',
    ) );
    
    $resourceId = new Google_ResourceId();
    $resourceId->setVideoId(substr($_POST["good"],0,-1));
    $resourceId->setKind('youtube#video');

    //   b. Create a snippet with resource id.
    foreach ($playlistItemResponse['items'] as $playlistItem) 
    {
    $playlistItemSnippet = new Google_PlaylistItemSnippet();
    $playlistItemSnippet->setTitle('First video in the test playlist');
    $playlistItemSnippet->setPlaylistId($playlistItem['id']);
    $playlistItemSnippet->setResourceId($resourceId);
    break;
    }
    //   c. Create a playlist item request request with snippet.
    $playlistItem = new Google_PlaylistItem();
    $playlistItem->setSnippet($playlistItemSnippet);

    //   d. Execute the request and return an object containing information about the
    //      new playlistItem
    $playlistItemResponse = $youtube->playlistItems->insert('snippet,contentDetails',
        $playlistItem, array());
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
</body>
</html>