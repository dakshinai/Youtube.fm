<?php
/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
require_once 'google-api-php-client-0.6.7\google-api-php-client\src\Google_Client.php';
require_once 'google-api-php-client-0.6.7\google-api-php-client\src\contrib\Google_Oauth2Service.php';
session_start();

$client = new Google_Client();
$client->setApplicationName("Google UserInfo PHP Starter Application");
// Visit https://code.google.com/apis/console?api=plus to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
	$client->setClientId('826178611003-rt8cu1i1093nnntbsee56qa7crm10fv2.apps.googleusercontent.com');
	$client->setClientSecret('nv2NHQYJibhc_22xUz-whW6D');
	$client->setRedirectUri('http://localhost/youtube/googleauth.php');
	$client->setDeveloperKey('AIzaSyBWm8d1Z8hJBY3nso6ds0g5cMlePp2wOOY');
$oauth2 = new Google_Oauth2Service($client);

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
  return;
}

if (isset($_SESSION['token'])) {
 $client->setAccessToken($_SESSION['token']);
}

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['token']);
  $client->revokeToken();
}

if ($client->getAccessToken()) {
  $user = $oauth2->userinfo->get();

  // These fields are currently filtered through the PHP sanitize filters.
  // See http://www.php.net/manual/en/filter.filters.sanitize.php
  $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
  print_r($email);
//  $img = filter_var($user['picture'], FILTER_VALIDATE_URL);
  //$personMarkup = "$email<div><img src='$img?sz=50'></div>";

  // The access token may have been updated lazily.
  $_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"></head>
<body>

<?php
  if(isset($authUrl)) {
   header('Location:'.$authUrl);
      die();
  }
  if(isset($_SESSION['token']))
  {
     // unset($_SESSION['token']);
     header('Location:http://localhost/youtube/GetTopTracksFinal.php') ;
    die();
  }
?>
</body></html>