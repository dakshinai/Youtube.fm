<?php
include ("XML\RSS.php");
$rss =& new XML_RSS("http://ws.audioscrobbler.com/1.0/user/RJ/recenttracks.rss");
$rss->parse();
$channelInfo = $rss->getChannelInfo();
?>


<html>
<head>
<title><?= $channelInfo['title'] ?></title>
</head>
<body>
<h1><?= $channelInfo['description'] ?></h1>
<ol>
<?php foreach ($rss->getItems() as $item){ ?>
<li>
<a href="<?= $item ['link'] ?>"><?= $item ['link'] ?></a>
</li>
<?php } ?>
</ol>
Link to User:
<a href="<?= $channelInfo['link'] ?>"><?=
$channelInfo['link'] ?></a>
</body>
</html>

