<?php
require_once 'C:\xampp\php\pear\File\XSPF.php';

$xspfObj =& new File_XSPF();
//Load the playlist into the XSPF object.
$xspfObj->parseFile('http://localhost/toptracks.xspf');
//Get all tracks in the playlist.
$tracks = $xspfObj->getTracks();
?>

<html>
<head>
<title>Shu Chow's Last.fm Top Tracks</title>
</head>
<body>
Title: <?= $xspfObj->getTitle() ?><br />
Created By: <?= $xspfObj->getCreator() ?>

<?php foreach ($tracks as $track) { ?>
<p>
Title: <?= $track->getTitle() ?><br />
Artist: <?= $track->getCreator() ?><br />
</p>
<?php } ?>

</body>
</html>








