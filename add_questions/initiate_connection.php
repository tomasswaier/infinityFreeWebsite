
<?php

// could be in separate file


$running_localy=true;
$connection;
$config = require $_SERVER['DOCUMENT_ROOT'] . '/config/local.php';
if($running_localy){

	$connection = mysqli_connect(
	    $config['DB_HOST'],
	    $config['DB_USER'],
	    $config['DB_PASSWORD'],
	    $config['DB_NAME']
	);
}else{
	
	$connection = mysqli_connect(
	    $config['DB_HOST'],
	    $config['DB_USER'],
	    $config['DB_PASSWORD'],
	    $config['DB_NAME']
	);
}
//
