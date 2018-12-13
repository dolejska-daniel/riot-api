<?php

require __DIR__ . "/../../../vendor/autoload.php";

use RiotAPI\DataDragonAPI\DataDragonAPI;

DataDragonAPI::initByCdn();

$id = 1;

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body class="container">
		<p class="lead">Fetching profile icon with ProfileIconID: <code><?=$id?></code>.</p>

		<?=DataDragonAPI::getProfileIcon($id)?>

		<p class="lead">With additional classes:</p>

		<?=DataDragonAPI::getProfileIcon($id, [ 'class' => 'img-thumbnail' ])?>
		<?=DataDragonAPI::getProfileIcon($id, [ 'class' => 'rounded' ])?>
		<?=DataDragonAPI::getProfileIcon($id, [ 'class' => 'rounded-circle' ])?>
	</body>
</html>
