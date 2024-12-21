
<?php

// could be in separate file


$running_localy=true;
$connection;

if($running_localy){
	$config = require_once(__DIR__ . '/local.php');

	$connection = mysqli_connect(
	    $config['DB_HOST'],
	    $config['DB_USER'],
	    $config['DB_PASSWORD'],
	    $config['DB_NAME']
	);
}else{
	$config = require_once(__DIR__ . '/config.php');
	
	$connection = mysqli_connect(
	    $config['DB_HOST'],
	    $config['DB_USER'],
	    $config['DB_PASSWORD'],
	    $config['DB_NAME']
	);
}
//
