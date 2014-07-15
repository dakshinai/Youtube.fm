<!doctype html>
<html>
  <head>
    <title>YouTube Search</title>
  </head>
  <body>
<?php


//if ($_GET['q'] && $_GET['maxResults']) {
  // Call set_include_path() as needed to point to your client library.
require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_YouTubeService.php';

  /* Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
  Google APIs Console <http://code.google.com/apis/console#access>
  Please ensure that you have enabled the YouTube Data API for your project. */
  $DEVELOPER_KEY = 'AIzaSyCsfz-K3dcECSBoyn-eYn0d3p2y3vdh6Ds';

  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);

  $youtube = new Google_YoutubeService($client);

  try {
    $searchResponse = $youtube->search->listSearch('id,snippet', array(
      'q' => "computer",
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
            print_r($url);
          break;
        case 'youtube#channel':
          $channels .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],
            $searchResult['id']['channelId']);
            $url=('//www.youtube.com/embed/'.$searchResult['id']['channelId']);
            print_r($url);
          break;
        case 'youtube#playlist':
          $playlists .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],
            $searchResult['id']['playlistId']);
          break;
      }
    }
    print_r($channels);
    print_r($videos);
  } catch (Google_ServiceException $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  }
//}
?>

  </body>
</html>
