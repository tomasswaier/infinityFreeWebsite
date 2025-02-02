<?php
	
	$test_id=$_POST['test_id'];
	if(!is_numeric($test_id)){
		echo "test id isn't numeric";
		exit(0);
	}
	$questionsQuery = "UPDATE test SET number_of_submits = number_of_submits + 1 WHERE test_id = $test_id; ";
	require $_SERVER['DOCUMENT_ROOT'] . '/config/initiate_connection.php';
	mysqli_set_charset($connection, "utf8");
	$result=mysqli_query($connection,$questionsQuery);




?>
