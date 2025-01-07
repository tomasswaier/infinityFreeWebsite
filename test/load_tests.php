<?php
	
	require $_SERVER['DOCUMENT_ROOT'] . '/config/initiate_connection.php';
	mysqli_set_charset($connection, "utf8");
	$questionsQuery = "SELECT test_id,test_name,test_author FROM test";

	$result=mysqli_query($connection,$questionsQuery);
	$formated=mysqli_fetch_all($result, MYSQLI_ASSOC);
	//header('Content-Type: application/json');
	echo json_encode($formated);
?>
